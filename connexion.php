<?php
require_once 'php/authentification.php';

// Already logged in → go home
if (est_connecte()) {
  header('Location: index.php');
  exit;
}

$error   = null;
$success = null;
$tab     = 'login'; // default tab

$result = traiter_formulaire_auth();
if ($result !== null) {
  if ($result['ok']) {
    // If this was a registration, the user is now auto-logged in — redirect home
    if (($_POST['action'] ?? '') === 'register') {
      header('Location: index.php');
      exit;
    }
    $success = 'Account created! You can now sign in.';
    $tab = 'login';
  } else {
    $error = $result['msg'];
    $tab   = ($_POST['action'] ?? 'login') === 'register' ? 'register' : 'login';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In — Perla Vita</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@200;300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<canvas id="bgCanvas"></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
(function(){
  'use strict';
  const canvas=document.getElementById('bgCanvas');
  const renderer=new THREE.WebGLRenderer({canvas,antialias:true,alpha:false});
  renderer.setPixelRatio(Math.min(window.devicePixelRatio,2));
  renderer.setSize(window.innerWidth,window.innerHeight);
  renderer.toneMapping=THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure=1.1;
  const scene=new THREE.Scene();
  scene.background=new THREE.Color(0x060d18);
  scene.fog=new THREE.FogExp2(0x060d18,0.035);
  const camera=new THREE.PerspectiveCamera(45,window.innerWidth/window.innerHeight,0.1,200);
  camera.position.set(0,2,14);
  camera.lookAt(0,0,0);
  scene.add(new THREE.AmbientLight(0xffffff,0.25));
  const kl=new THREE.DirectionalLight(0xfff4e0,2.2);kl.position.set(5,10,8);scene.add(kl);
  const rl=new THREE.DirectionalLight(0x4466ff,0.9);rl.position.set(-8,4,-5);scene.add(rl);
  const gs=new THREE.PointLight(0xd4ae5a,3.5,30);gs.position.set(0,3,3);scene.add(gs);
  const goldMat=new THREE.MeshStandardMaterial({color:0xd4ae5a,metalness:1.0,roughness:0.08});
  const ring=new THREE.Mesh(new THREE.TorusGeometry(2.2,0.42,64,128),goldMat);scene.add(ring);
  const gemMat=new THREE.MeshPhysicalMaterial({color:0xffffff,metalness:0,roughness:0,transmission:1.0,thickness:0.8,ior:2.4,envMapIntensity:3.0});
  const gem=new THREE.Mesh(new THREE.OctahedronGeometry(0.55,2),gemMat);gem.position.set(0,2.2,0);scene.add(gem);
  for(let i=0;i<8;i++){const a=(i/8)*Math.PI*2,r=2.2,m=new THREE.Mesh(new THREE.OctahedronGeometry(0.18,1),i%3===0?goldMat:gemMat);m.position.set(Math.cos(a)*r,Math.sin(a)*r,0);ring.add(m);}
  const pPos=new Float32Array(400*3);for(let i=0;i<400;i++){pPos[i*3]=(Math.random()-.5)*36;pPos[i*3+1]=(Math.random()-.5)*24;pPos[i*3+2]=(Math.random()-.5)*24;}
  const pg=new THREE.BufferGeometry();pg.setAttribute('position',new THREE.BufferAttribute(pPos,3));
  scene.add(new THREE.Points(pg,new THREE.PointsMaterial({color:0xd4ae5a,size:0.07,transparent:true,opacity:0.5})));
  const clock=new THREE.Clock();
  function animate(){requestAnimationFrame(animate);const t=clock.getElapsedTime();ring.rotation.y=t*0.28;ring.rotation.x=0.5+Math.sin(t*0.15)*0.1;gem.rotation.y=t*0.8;gem.position.y=2.2+Math.sin(t*0.9)*0.08;gs.intensity=3.5+Math.sin(t*1.1)*1.0;renderer.render(scene,camera);}
  animate();
  window.addEventListener('resize',()=>{camera.aspect=window.innerWidth/window.innerHeight;camera.updateProjectionMatrix();renderer.setSize(window.innerWidth,window.innerHeight);});
})();
</script>

<div class="auth-page">
  <!-- Logo -->
  <a href="index.php" class="auth-logo">
    <span class="logo-main">Perla Vita</span>
    <span class="logo-sub">Maison de Joaillerie</span>
  </a>

  <div class="auth-card animate-fade-up">
    <!-- Gem decoration -->
    <div class="auth-gem">◈</div>

    <!-- Tab switcher -->
    <div class="auth-tabs">
      <button class="auth-tab <?= $tab === 'login' ? 'active' : '' ?>" data-tab="login">Sign In</button>
      <button class="auth-tab <?= $tab === 'register' ? 'active' : '' ?>" data-tab="register">Create Account</button>
    </div>

    <?php if ($error): ?>
    <div class="auth-alert auth-alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
    <div class="auth-alert auth-alert--success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- LOGIN FORM -->
    <div class="auth-panel <?= $tab === 'login' ? 'active' : '' ?>" id="panel-login">
      <p class="auth-sub">Welcome back. Please sign in to your account.</p>
      <form method="POST" action="connexion.php" novalidate>
        <input type="hidden" name="action" value="login">
        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect'] ?? 'index.php') ?>">
        <div class="auth-field">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="elise@example.com" autocomplete="email" required>
        </div>
        <div class="auth-field">
          <label>Password</label>
          <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
        </div>
        <button type="submit" class="auth-submit">
          <span>Sign In</span>
          <span class="auth-submit-icon">→</span>
        </button>
      </form>
      <p class="auth-switch">New to Perla Vita? <a href="#" data-switch="register">Create an account</a></p>
    </div>

    <!-- REGISTER FORM -->
    <div class="auth-panel <?= $tab === 'register' ? 'active' : '' ?>" id="panel-register">
      <p class="auth-sub">Join us and discover our world of fine jewellery.</p>
      <form method="POST" action="connexion.php" novalidate>
        <input type="hidden" name="action" value="register">
        <div class="auth-grid">
          <div class="auth-field">
            <label>First Name</label>
            <input type="text" name="firstName" placeholder="Élise" autocomplete="given-name" required>
          </div>
          <div class="auth-field">
            <label>Last Name</label>
            <input type="text" name="lastName" placeholder="Fontaine" autocomplete="family-name" required>
          </div>
        </div>
        <div class="auth-field">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="elise@example.com" autocomplete="email" required>
        </div>
        <div class="auth-field">
          <label>Password <span class="auth-hint">(min. 6 characters)</span></label>
          <input type="password" name="password" placeholder="••••••••" autocomplete="new-password" required minlength="6">
        </div>
        <button type="submit" class="auth-submit">
          <span>Create Account</span>
          <span class="auth-submit-icon">✦</span>
        </button>
      </form>
      <p class="auth-switch">Already have an account? <a href="#" data-switch="login">Sign in</a></p>
    </div>
  </div>
</div>

<script>
// Tab switching
document.querySelectorAll('.auth-tab, [data-switch]').forEach(el => {
  el.addEventListener('click', e => {
    e.preventDefault();
    const target = el.dataset.tab || el.dataset.switch;
    document.querySelectorAll('.auth-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === target));
    document.querySelectorAll('.auth-panel').forEach(p => p.classList.toggle('active', p.id === 'panel-' + target));
  });
});
</script>
</body>
</html>
