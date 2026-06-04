<?php
// Connexion à la base de données

$hote        = 'localhost';
$nom_bdd     = 'perlavita';
$utilisateur = 'root';
$mot_de_passe = '';

try {
    $bdd = new PDO("mysql:host=$hote;dbname=$nom_bdd;charset=utf8mb4", $utilisateur, $mot_de_passe);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('<h2 style="color:red">Erreur de connexion : ' . $e->getMessage() . '</h2>');
}

// Trouver un utilisateur par email
function trouver_utilisateur_par_email($email) {
    global $bdd;
    $req = $bdd->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $req->execute([strtolower(trim($email))]);
    return $req->fetch();
}

// Trouver un utilisateur par ID
function trouver_utilisateur_par_id($id) {
    global $bdd;
    $req = $bdd->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $req->execute([$id]);
    return $req->fetch();
}

// Récupérer tous les utilisateurs
function recuperer_tous_les_utilisateurs() {
    global $bdd;
    return $bdd->query("SELECT * FROM users ORDER BY created ASC")->fetchAll();
}

// Insérer un nouvel utilisateur
function inserer_utilisateur($id, $prenom, $nom, $email, $mot_de_passe, $role = 'user') {
    global $bdd;
    $req = $bdd->prepare("INSERT INTO users (id, firstName, lastName, email, password, role, avatar, created)
                          VALUES (?, ?, ?, ?, ?, ?, '', NOW())");
    $req->execute([$id, $prenom, $nom, strtolower(trim($email)), $mot_de_passe, $role]);
}

// Mettre à jour un utilisateur
function mettre_a_jour_utilisateur($id, $champs) {
    global $bdd;
    $colonnes_permises = ['firstName', 'lastName', 'email', 'password', 'role', 'avatar'];
    $parties = [];
    $valeurs = [];
    foreach ($champs as $col => $val) {
        if (in_array($col, $colonnes_permises)) {
            $parties[] = "$col = ?";
            $valeurs[] = $val;
        }
    }
    if (empty($parties)) return;
    $valeurs[] = $id;
    $req = $bdd->prepare("UPDATE users SET " . implode(', ', $parties) . " WHERE id = ?");
    $req->execute($valeurs);
}

// Supprimer un utilisateur
function supprimer_utilisateur($id) {
    global $bdd;
    $req = $bdd->prepare("DELETE FROM users WHERE id = ?");
    $req->execute([$id]);
}

// Créer le premier admin s'il n'y a aucun utilisateur
function creer_admin_si_vide() {
    global $bdd;
    $count = $bdd->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($count == 0) {
        inserer_utilisateur(
            uniqid('pv_', true),
            'Admin', 'Perla Vita',
            'admin@perlavita.com',
            password_hash('admin123', PASSWORD_DEFAULT),
            'admin'
        );
    }
}

// Récupérer tous les coupons
function recuperer_coupons() {
    global $bdd;
    return $bdd->query("SELECT * FROM coupons ORDER BY created DESC")->fetchAll();
}

// Valider un coupon
function valider_coupon($code, $total_commande) {
    global $bdd;
    $code = strtoupper(trim($code));
    $req  = $bdd->prepare("SELECT * FROM coupons WHERE code = ? LIMIT 1");
    $req->execute([$code]);
    $coupon = $req->fetch();
    if (!$coupon) return ['ok' => false, 'msg' => 'Code coupon invalide.'];
    if (!$coupon['active']) return ['ok' => false, 'msg' => 'Ce coupon est inactif.'];
    if ($total_commande < $coupon['minOrder']) return ['ok' => false, 'msg' => 'Montant minimum non atteint.'];
    $remise = $coupon['type'] === 'percent'
        ? round($total_commande * $coupon['value'] / 100, 2)
        : min($coupon['value'], $total_commande);
    return ['ok' => true, 'remise' => $remise, 'code' => $code];
}

// Récupérer toutes les commandes
function recuperer_commandes() {
    global $bdd;
    $rows = $bdd->query("SELECT * FROM orders ORDER BY created DESC")->fetchAll();
    $commandes = [];
    foreach ($rows as $r) {
        $data = json_decode($r['data'], true);
        $data['id']     = $r['id'];
        $data['date']   = $r['created'];
        $data['userId'] = $r['userId'];
        $commandes[] = $data;
    }
    return $commandes;
}

// Sauvegarder une commande
function sauvegarder_commande($commande) {
    global $bdd;
    $id      = $commande['id']     ?? uniqid('order_', true);
    $userId  = $commande['userId'] ?? '';
    $created = $commande['date']   ?? date('Y-m-d H:i:s');
    $req = $bdd->prepare("INSERT INTO orders (id, userId, data, created) VALUES (?, ?, ?, ?)");
    $req->execute([$id, $userId, json_encode($commande), $created]);
}

// Récupérer les commandes d'un utilisateur
function recuperer_commandes_utilisateur($userId) {
    global $bdd;
    $req = $bdd->prepare("SELECT * FROM orders WHERE userId = ? ORDER BY created DESC");
    $req->execute([$userId]);
    $rows = $req->fetchAll();
    $commandes = [];
    foreach ($rows as $r) {
        $data = json_decode($r['data'], true);
        $data['id']     = $r['id'];
        $data['date']   = $r['created'];
        $data['userId'] = $r['userId'];
        $commandes[] = $data;
    }
    return $commandes;
}

// Récupérer tous les produits personnalisés
function recuperer_produits_custom() {
    global $bdd;
    $rows = $bdd->query("SELECT * FROM products_custom ORDER BY id ASC")->fetchAll();
    $produits = [];
    foreach ($rows as $p) {
        $p['id']     = (int) $p['id'];
        $p['price']  = (float) $p['price'];
        $p['rating'] = (int) $p['rating'];
        $p['custom'] = true;
        $produits[] = $p;
    }
    return $produits;
}

// Insérer un produit personnalisé
function inserer_produit_custom($p) {
    global $bdd;
    $req = $bdd->prepare("INSERT INTO products_custom (name, category, price, icon, rating, badge, material)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    $req->execute([
        trim($p['name']     ?? 'New Item'),
        $p['category']      ?? 'necklaces',
        (float)($p['price'] ?? 0),
        trim($p['icon']     ?? '✦'),
        (int)($p['rating']  ?? 5),
        trim($p['badge']    ?? ''),
        trim($p['material'] ?? ''),
    ]);
    return (int) $bdd->lastInsertId();
}

// Mettre à jour un produit personnalisé
function mettre_a_jour_produit_custom($id, $champs) {
    global $bdd;
    $colonnes_permises = ['name', 'category', 'price', 'icon', 'rating', 'badge', 'material'];
    $parties = [];
    $valeurs = [];
    foreach ($champs as $col => $val) {
        if (in_array($col, $colonnes_permises)) {
            $parties[] = "$col = ?";
            $valeurs[] = $val;
        }
    }
    if (empty($parties)) return;
    $valeurs[] = $id;
    $req = $bdd->prepare("UPDATE products_custom SET " . implode(', ', $parties) . " WHERE id = ?");
    $req->execute($valeurs);
}

// Supprimer un produit personnalisé
function supprimer_produit_custom($id) {
    global $bdd;
    $req = $bdd->prepare("DELETE FROM products_custom WHERE id = ?");
    $req->execute([$id]);
}

// Insérer un coupon
function inserer_coupon($c) {
    global $bdd;
    $req = $bdd->prepare("INSERT INTO coupons (code, type, value, minOrder, active, created)
                          VALUES (?, ?, ?, ?, 1, NOW())");
    $req->execute([
        strtoupper(trim($c['code'])),
        $c['type']     ?? 'percent',
        (float)($c['value']    ?? 10),
        (float)($c['minOrder'] ?? 0),
    ]);
}

// Activer/désactiver un coupon
function basculer_coupon($code) {
    global $bdd;
    $req = $bdd->prepare("UPDATE coupons SET active = NOT active WHERE code = ?");
    $req->execute([strtoupper(trim($code))]);
}

// Supprimer un coupon
function supprimer_coupon($code) {
    global $bdd;
    $req = $bdd->prepare("DELETE FROM coupons WHERE code = ?");
    $req->execute([strtoupper(trim($code))]);
}

// Mettre à jour un utilisateur (admin)
function mettre_a_jour_utilisateur_admin($id, $champs) {
    global $bdd;
    mettre_a_jour_utilisateur($id, $champs);
    return true;
}

// Supprimer un utilisateur (admin)
function supprimer_utilisateur_admin($id) {
    global $bdd;
    $u = trouver_utilisateur_par_id($id);
    if (!$u) return ['ok' => false, 'msg' => 'Utilisateur introuvable.'];
    supprimer_utilisateur($id);
    return ['ok' => true];
}