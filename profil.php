<?php
require_once 'php/authentification.php';
require_once 'php/composants.php';
exiger_connexion();

$user = utilisateur_connecte();
$error = null;
$success = null;

// Load full user record for avatar
$users = recuperer_tous_les_utilisateurs();
$fullUser = null;
foreach ($users as $u) {
  if ($u['id'] === $user['id']) { $fullUser = $u; break; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $avatarFile = $_FILES['avatar'] ?? null;
  $result = updateProfile(
    $user['id'],
    [
      'firstName' => $_POST['firstName'] ?? '',
      'lastName'  => $_POST['lastName']  ?? '',
      'password'  => $_POST['password']  ?? '',
    ],
    $avatarFile && $avatarFile['error'] !== UPLOAD_ERR_NO_FILE ? $avatarFile : null
  );
  if ($result['ok']) {
    $success = 'Profile updated successfully.';
    $user = utilisateur_connecte(); // refreshed
    // Reload full user
    $users = recuperer_tous_les_utilisateurs();
    foreach ($users as $u) { if ($u['id'] === $user['id']) { $fullUser = $u; break; } }
  } else {
    $error = $result['msg'];
  }
}

renderHead('My Profile', '<link rel="stylesheet" href="css/profile.css">');
renderNav();
?>

<div class="profile-page">
  <div class="profile-card animate-fade-up">
    <div class="profile-header">
      <div class="profile-avatar-wrap">
        <?php if (!empty($fullUser['avatar'])): ?>
          <img src="uploads/avatars/<?= htmlspecialchars($fullUser['avatar']) ?>" class="profile-avatar-img" id="avatarPreview" alt="Profile photo">
        <?php else: ?>
          <div class="profile-avatar-placeholder" id="avatarPreview">
            <?= htmlspecialchars(mb_substr($user['firstName'],0,1) . mb_substr($user['lastName'],0,1)) ?>
          </div>
        <?php endif; ?>
        <label class="profile-avatar-change" for="avatarInput" title="Change photo">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
            <circle cx="12" cy="13" r="4"/>
          </svg>
        </label>
      </div>
      <div>
        <h2 class="profile-name"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></h2>
        <span class="profile-role-badge profile-role-<?= htmlspecialchars($user['role'] ?? 'user') ?>">
          <?= ucfirst(htmlspecialchars($user['role'] ?? 'user')) ?>
        </span>
        <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
      </div>
    </div>

    <?php if ($error): ?>
      <div class="auth-alert auth-alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="auth-alert auth-alert--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="profile-form">
      <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none">

      <div class="profile-section-title">Personal Information</div>
      <div class="auth-grid">
        <div class="auth-field">
          <label>First Name</label>
          <input type="text" name="firstName" value="<?= htmlspecialchars($user['firstName']) ?>" required>
        </div>
        <div class="auth-field">
          <label>Last Name</label>
          <input type="text" name="lastName" value="<?= htmlspecialchars($user['lastName']) ?>" required>
        </div>
      </div>
      <div class="auth-field">
        <label>Email Address <span class="auth-hint">(cannot be changed here)</span></label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
      </div>

      <div class="profile-section-title" style="margin-top:28px">Change Password</div>
      <div class="auth-field">
        <label>New Password <span class="auth-hint">(leave blank to keep current)</span></label>
        <input type="password" name="password" placeholder="••••••••" minlength="6">
      </div>

      <button type="submit" class="auth-submit">
        <span>Save Changes</span>
        <span class="auth-submit-icon">✦</span>
      </button>
    </form>
  </div>
</div>

<script>
// Live avatar preview
document.getElementById('avatarInput').addEventListener('change', function() {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function(e) {
    const preview = document.getElementById('avatarPreview');
    // Replace with img or update existing
    if (preview.tagName === 'IMG') {
      preview.src = e.target.result;
    } else {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.className = 'profile-avatar-img';
      img.id = 'avatarPreview';
      preview.parentNode.replaceChild(img, preview);
    }
  };
  reader.readAsDataURL(file);
});
</script>

<?php renderFooter(); ?>
