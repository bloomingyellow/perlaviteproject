<?php
require_once 'php/authentification.php';
require_once 'php/composants.php';
require_once 'php/produits.php';
exiger_admin();

$onglet = $_GET['tab'] ?? 'tableau_de_bord';
$message     = null;
$type_message = 'succes';

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['act'] ?? '';

    // Ajouter un produit
    if ($action === 'add_product') {
        inserer_produit_custom([
            'name'     => trim($_POST['name'] ?? ''),
            'category' => $_POST['category'] ?? 'necklaces',
            'price'    => (float)($_POST['price'] ?? 0),
            'icon'     => trim($_POST['icon'] ?? '✦'),
            'rating'   => (int)($_POST['rating'] ?? 5),
            'badge'    => trim($_POST['badge'] ?? ''),
            'material' => trim($_POST['material'] ?? ''),
        ]);
        $message = 'Produit ajouté avec succès.';
        $onglet  = 'produits';
    }

    // Modifier un produit
    if ($action === 'edit_product') {
        $pid = (int)($_POST['pid'] ?? 0);
        mettre_a_jour_produit_custom($pid, [
            'name'     => trim($_POST['name'] ?? ''),
            'category' => $_POST['category'] ?? 'necklaces',
            'price'    => (float)($_POST['price'] ?? 0),
            'icon'     => trim($_POST['icon'] ?? '✦'),
            'badge'    => trim($_POST['badge'] ?? ''),
            'material' => trim($_POST['material'] ?? ''),
        ]);
        $message = 'Produit modifié.';
        $onglet  = 'produits';
    }

    // Supprimer un produit
    if ($action === 'delete_product') {
        supprimer_produit_custom((int)($_POST['pid'] ?? 0));
        $message = 'Produit supprimé.';
        $onglet  = 'produits';
    }

    // Ajouter un coupon
    if ($action === 'add_coupon') {
        $code = strtoupper(trim($_POST['code'] ?? ''));
        if (!$code) {
            $message = 'Le code ne peut pas être vide.';
            $type_message = 'erreur';
        } else {
            try {
                inserer_coupon([
                    'code'     => $code,
                    'type'     => $_POST['type'] ?? 'percent',
                    'value'    => (float)($_POST['value'] ?? 10),
                    'minOrder' => (float)($_POST['minOrder'] ?? 0),
                ]);
                $message = 'Coupon créé.';
            } catch (PDOException $e) {
                $message = 'Ce code existe déjà.';
                $type_message = 'erreur';
            }
        }
        $onglet = 'coupons';
    }

    // Activer/désactiver un coupon
    if ($action === 'toggle_coupon') {
        basculer_coupon($_POST['code'] ?? '');
        $message = 'Coupon mis à jour.';
        $onglet  = 'coupons';
    }

    // Supprimer un coupon
    if ($action === 'delete_coupon') {
        supprimer_coupon($_POST['code'] ?? '');
        $message = 'Coupon supprimé.';
        $onglet  = 'coupons';
    }

    // Modifier un utilisateur
    if ($action === 'edit_user') {
        $uid = $_POST['uid'] ?? '';
        mettre_a_jour_utilisateur($uid, [
            'firstName' => $_POST['firstName'] ?? '',
            'lastName'  => $_POST['lastName']  ?? '',
            'email'     => $_POST['email']     ?? '',
            'role'      => $_POST['role']      ?? 'user',
        ]);
        if (!empty($_POST['password'])) {
            mettre_a_jour_utilisateur($uid, ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)]);
        }
        $message = 'Utilisateur modifié.';
        $onglet  = 'utilisateurs';
    }

    // Supprimer un utilisateur
    if ($action === 'delete_user') {
        $uid = $_POST['uid'] ?? '';
        $moi = utilisateur_connecte();
        if ($uid === $moi['id']) {
            $message = 'Vous ne pouvez pas supprimer votre propre compte.';
            $type_message = 'erreur';
        } else {
            supprimer_utilisateur($uid);
            $message = 'Utilisateur supprimé.';
        }
        $onglet = 'utilisateurs';
    }

    header('Location: administration.php?tab=' . $onglet . ($message ? '&msg=' . urlencode($message) . '&mt=' . $type_message : ''));
    exit;
}

// Message flash après redirection
if (isset($_GET['msg'])) {
    $message      = $_GET['msg'];
    $type_message = $_GET['mt'] ?? 'succes';
}

// Charger les données
$utilisateurs   = recuperer_tous_les_utilisateurs();
$coupons        = recuperer_coupons();
$commandes      = recuperer_commandes();
$produits_base  = getProducts();
$produits_custom = recuperer_produits_custom();
$tous_produits  = array_merge($produits_base, $produits_custom);

// Statistiques
$total_revenus  = array_sum(array_map(fn($o) => $o['total'] ?? 0, $commandes));
$total_commandes = count($commandes);

renderHead('Administration', '<link rel="stylesheet" href="css/admin.css">');
renderNav();
?>

<div class="admin-page">

  <!-- Barre latérale -->
  <div class="admin-sidebar">
    <div class="admin-sidebar-title">Administration</div>
    <nav class="admin-nav">
      <?php
      $onglets = [
        'tableau_de_bord' => ['icone' => '◈', 'label' => 'Tableau de bord'],
        'produits'        => ['icone' => '✦', 'label' => 'Produits'],
        'coupons'         => ['icone' => '%', 'label' => 'Coupons'],
        'utilisateurs'    => ['icone' => '◯', 'label' => 'Utilisateurs'],
        'commandes'       => ['icone' => '━', 'label' => 'Commandes'],
      ];
      foreach ($onglets as $cle => $o):
        $actif = $cle === $onglet ? ' active' : '';
      ?>
      <a href="administration.php?tab=<?= $cle ?>" class="admin-nav-item<?= $actif ?>">
        <span><?= $o['icone'] ?></span><?= $o['label'] ?>
      </a>
      <?php endforeach; ?>
    </nav>
    <a href="index.php" class="admin-back-link">← Retour au site</a>
  </div>

  <!-- Contenu principal -->
  <div class="admin-main">

    <?php if ($message): ?>
    <div class="admin-flash admin-flash--<?= $type_message === 'erreur' ? 'error' : 'success' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <!-- Tableau de bord -->
    <?php if ($onglet === 'tableau_de_bord'): ?>
    <div class="admin-section-title">Tableau de bord</div>
    <div class="admin-stats">
      <div class="admin-stat">
        <div class="admin-stat-val"><?= count($utilisateurs) ?></div>
        <div class="admin-stat-label">Utilisateurs</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-val"><?= count($tous_produits) ?></div>
        <div class="admin-stat-label">Produits</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-val"><?= $total_commandes ?></div>
        <div class="admin-stat-label">Commandes</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-val">$<?= number_format($total_revenus, 0) ?></div>
        <div class="admin-stat-label">Revenus</div>
      </div>
      <div class="admin-stat">
        <div class="admin-stat-val"><?= count($coupons) ?></div>
        <div class="admin-stat-label">Coupons</div>
      </div>
    </div>

    <!-- Produits -->
    <?php elseif ($onglet === 'produits'): ?>
    <div class="admin-section-title">Produits</div>
    <div class="admin-toolbar">
      <button class="admin-btn admin-btn--gold" onclick="showModal('modalAjouterProduit')">+ Ajouter un produit</button>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Icône</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Prix</th>
            <th>Badge</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($tous_produits as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td style="font-size:1.4rem;text-align:center"><?= htmlspecialchars($p['icon']) ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['category']) ?></td>
          <td>$<?= number_format($p['price'], 0) ?></td>
          <td><?= htmlspecialchars($p['badge'] ?? '') ?></td>
          <td>
            <?php if (!empty($p['custom'])): ?>
            <button class="admin-btn admin-btn--sm" onclick='modifierProduit(<?= json_encode($p) ?>)'>Modifier</button>
            <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer ce produit ?')">
              <input type="hidden" name="act" value="delete_product">
              <input type="hidden" name="pid" value="<?= $p['id'] ?>">
              <button type="submit" class="admin-btn admin-btn--sm admin-btn--danger">Supprimer</button>
            </form>
            <?php else: ?>
            <span class="admin-badge">Intégré</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Coupons -->
    <?php elseif ($onglet === 'coupons'): ?>
    <div class="admin-section-title">Coupons</div>
    <div class="admin-toolbar">
      <button class="admin-btn admin-btn--gold" onclick="showModal('modalAjouterCoupon')">+ Ajouter un coupon</button>
    </div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Valeur</th>
            <th>Commande min.</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($coupons)): ?>
        <tr><td colspan="6" style="text-align:center;opacity:0.5">Aucun coupon pour l'instant</td></tr>
        <?php else: foreach ($coupons as $c): ?>
        <tr>
          <td><strong><?= htmlspecialchars($c['code']) ?></strong></td>
          <td><?= $c['type'] === 'percent' ? 'Pourcentage %' : 'Fixe $' ?></td>
          <td><?= $c['type'] === 'percent' ? $c['value'].'%' : '$'.$c['value'] ?></td>
          <td><?= $c['minOrder'] > 0 ? '$'.number_format($c['minOrder'], 0) : '—' ?></td>
          <td>
            <span class="admin-badge admin-badge--<?= $c['active'] ? 'active' : 'inactive' ?>">
              <?= $c['active'] ? 'Actif' : 'Inactif' ?>
            </span>
          </td>
          <td>
            <form method="POST" style="display:inline">
              <input type="hidden" name="act" value="toggle_coupon">
              <input type="hidden" name="code" value="<?= htmlspecialchars($c['code']) ?>">
              <button type="submit" class="admin-btn admin-btn--sm">
                <?= $c['active'] ? 'Désactiver' : 'Activer' ?>
              </button>
            </form>
            <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer ce coupon ?')">
              <input type="hidden" name="act" value="delete_coupon">
              <input type="hidden" name="code" value="<?= htmlspecialchars($c['code']) ?>">
              <button type="submit" class="admin-btn admin-btn--sm admin-btn--danger">Supprimer</button>
            </form>
          </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Utilisateurs -->
    <?php elseif ($onglet === 'utilisateurs'): ?>
    <div class="admin-section-title">Utilisateurs</div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Avatar</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Inscrit le</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($utilisateurs as $u): ?>
        <tr>
          <td>
            <?php if (!empty($u['avatar'])): ?>
              <img src="uploads/avatars/<?= htmlspecialchars($u['avatar']) ?>" class="admin-user-avatar" alt="">
            <?php else: ?>
              <div class="admin-user-avatar admin-user-avatar--initials">
                <?= htmlspecialchars(mb_substr($u['firstName'], 0, 1) . mb_substr($u['lastName'], 0, 1)) ?>
              </div>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($u['firstName'] . ' ' . $u['lastName']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td>
            <span class="admin-badge admin-badge--<?= $u['role'] ?? 'user' ?>">
              <?= $u['role'] === 'admin' ? 'Admin' : 'Utilisateur' ?>
            </span>
          </td>
          <td><?= htmlspecialchars(substr($u['created'] ?? '', 0, 10)) ?></td>
          <td>
            <button class="admin-btn admin-btn--sm" onclick='modifierUtilisateur(<?= json_encode($u) ?>)'>Modifier</button>
            <?php $moi = utilisateur_connecte(); if ($u['id'] !== $moi['id']): ?>
            <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
              <input type="hidden" name="act" value="delete_user">
              <input type="hidden" name="uid" value="<?= htmlspecialchars($u['id']) ?>">
              <button type="submit" class="admin-btn admin-btn--sm admin-btn--danger">Supprimer</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Commandes -->
    <?php elseif ($onglet === 'commandes'): ?>
    <div class="admin-section-title">Commandes</div>
    <?php if (empty($commandes)): ?>
    <p style="opacity:0.5;margin-top:24px">Aucune commande pour l'instant.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID Commande</th>
            <th>Client</th>
            <th>Articles</th>
            <th>Total</th>
            <th>Coupon</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach (array_reverse($commandes) as $o): ?>
        <tr>
          <td style="font-family:monospace;font-size:0.7rem"><?= htmlspecialchars(substr($o['id'] ?? '', 0, 12)) ?>…</td>
          <td><?= htmlspecialchars($o['customer']['name'] ?? '—') ?></td>
          <td><?= count($o['items'] ?? []) ?></td>
          <td>$<?= number_format($o['total'] ?? 0, 2) ?></td>
          <td><?= htmlspecialchars($o['coupon'] ?? '—') ?></td>
          <td><?= htmlspecialchars(substr($o['date'] ?? '', 0, 10)) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
    <?php endif; ?>

  </div>
</div>

<!-- Modal : Ajouter un produit -->
<div class="admin-modal-overlay" id="modalAjouterProduit">
  <div class="admin-modal">
    <div class="admin-modal-title">Ajouter un produit</div>
    <form method="POST">
      <input type="hidden" name="act" value="add_product">
      <div class="auth-grid">
        <div class="auth-field"><label>Nom</label><input type="text" name="name" required placeholder="Nom du produit"></div>
        <div class="auth-field"><label>Icône</label><input type="text" name="icon" value="✦" maxlength="4"></div>
      </div>
      <div class="auth-grid">
        <div class="auth-field"><label>Catégorie</label>
          <select name="category">
            <option>necklaces</option><option>earrings</option><option>rings</option>
            <option>bracelets</option><option>sets</option>
          </select>
        </div>
        <div class="auth-field"><label>Prix ($)</label><input type="number" name="price" min="1" step="0.01" required></div>
      </div>
      <div class="auth-grid">
        <div class="auth-field"><label>Matière</label><input type="text" name="material" placeholder="ex: Or 18k"></div>
        <div class="auth-field"><label>Badge</label><input type="text" name="badge" placeholder="ex: Nouveau, Bestseller"></div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="admin-btn" onclick="hideModal('modalAjouterProduit')">Annuler</button>
        <button type="submit" class="admin-btn admin-btn--gold">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal : Modifier un produit -->
<div class="admin-modal-overlay" id="modalModifierProduit">
  <div class="admin-modal">
    <div class="admin-modal-title">Modifier le produit</div>
    <form method="POST">
      <input type="hidden" name="act" value="edit_product">
      <input type="hidden" name="pid" id="modif_pid">
      <div class="auth-grid">
        <div class="auth-field"><label>Nom</label><input type="text" name="name" id="modif_nom" required></div>
        <div class="auth-field"><label>Icône</label><input type="text" name="icon" id="modif_icone" maxlength="4"></div>
      </div>
      <div class="auth-grid">
        <div class="auth-field"><label>Catégorie</label>
          <select name="category" id="modif_categorie">
            <option>necklaces</option><option>earrings</option><option>rings</option>
            <option>bracelets</option><option>sets</option>
          </select>
        </div>
        <div class="auth-field"><label>Prix ($)</label><input type="number" name="price" id="modif_prix" min="1" step="0.01"></div>
      </div>
      <div class="auth-grid">
        <div class="auth-field"><label>Matière</label><input type="text" name="material" id="modif_matiere"></div>
        <div class="auth-field"><label>Badge</label><input type="text" name="badge" id="modif_badge"></div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="admin-btn" onclick="hideModal('modalModifierProduit')">Annuler</button>
        <button type="submit" class="admin-btn admin-btn--gold">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal : Ajouter un coupon -->
<div class="admin-modal-overlay" id="modalAjouterCoupon">
  <div class="admin-modal">
    <div class="admin-modal-title">Ajouter un coupon</div>
    <form method="POST">
      <input type="hidden" name="act" value="add_coupon">
      <div class="auth-field">
        <label>Code</label>
        <input type="text" name="code" required placeholder="EX: SAVE20" style="text-transform:uppercase">
      </div>
      <div class="auth-grid">
        <div class="auth-field"><label>Type</label>
          <select name="type">
            <option value="percent">Pourcentage (%)</option>
            <option value="fixed">Montant fixe ($)</option>
          </select>
        </div>
        <div class="auth-field"><label>Valeur</label><input type="number" name="value" min="1" step="0.01" required placeholder="10"></div>
      </div>
      <div class="auth-field">
        <label>Commande minimum ($) <span class="auth-hint">(0 = aucun minimum)</span></label>
        <input type="number" name="minOrder" min="0" step="0.01" value="0">
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="admin-btn" onclick="hideModal('modalAjouterCoupon')">Annuler</button>
        <button type="submit" class="admin-btn admin-btn--gold">Créer le coupon</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal : Modifier un utilisateur -->
<div class="admin-modal-overlay" id="modalModifierUtilisateur">
  <div class="admin-modal">
    <div class="admin-modal-title">Modifier l'utilisateur</div>
    <form method="POST">
      <input type="hidden" name="act" value="edit_user">
      <input type="hidden" name="uid" id="modif_uid">
      <div class="auth-grid">
        <div class="auth-field"><label>Prénom</label><input type="text" name="firstName" id="modif_prenom" required></div>
        <div class="auth-field"><label>Nom</label><input type="text" name="lastName" id="modif_nom_user" required></div>
      </div>
      <div class="auth-field"><label>Email</label><input type="email" name="email" id="modif_email" required></div>
      <div class="auth-grid">
        <div class="auth-field"><label>Rôle</label>
          <select name="role" id="modif_role">
            <option value="user">Utilisateur</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div class="auth-field">
          <label>Nouveau mot de passe <span class="auth-hint">(laisser vide = inchangé)</span></label>
          <input type="password" name="password" placeholder="••••••••">
        </div>
      </div>
      <div class="admin-modal-actions">
        <button type="button" class="admin-btn" onclick="hideModal('modalModifierUtilisateur')">Annuler</button>
        <button type="submit" class="admin-btn admin-btn--gold">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script>
function showModal(id) { document.getElementById(id).classList.add('open'); }
function hideModal(id) { document.getElementById(id).classList.remove('open'); }

// Fermer en cliquant en dehors
document.querySelectorAll('.admin-modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
});

// Remplir le modal modifier produit
function modifierProduit(p) {
    document.getElementById('modif_pid').value      = p.id;
    document.getElementById('modif_nom').value      = p.name;
    document.getElementById('modif_icone').value    = p.icon;
    document.getElementById('modif_prix').value     = p.price;
    document.getElementById('modif_matiere').value  = p.material || '';
    document.getElementById('modif_badge').value    = p.badge || '';
    var select = document.getElementById('modif_categorie');
    for (var i = 0; i < select.options.length; i++) {
        select.options[i].selected = (select.options[i].value === p.category);
    }
    showModal('modalModifierProduit');
}

// Remplir le modal modifier utilisateur
function modifierUtilisateur(u) {
    document.getElementById('modif_uid').value       = u.id;
    document.getElementById('modif_prenom').value    = u.firstName;
    document.getElementById('modif_nom_user').value  = u.lastName;
    document.getElementById('modif_email').value     = u.email;
    document.getElementById('modif_role').value      = u.role || 'user';
    showModal('modalModifierUtilisateur');
}
</script>

<?php renderFooter(); ?>
