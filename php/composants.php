<?php
require_once __DIR__ . '/authentification.php';
// ─── Shared partials for Perla Vita v7 ──────────────────────

function renderHead($title = 'Perla Vita', $extraCss = '') {
  echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$title} — Perla Vita</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@200;300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/auth.css">
  {$extraCss}
</head>
<body>
<!-- ─── PERSISTENT 3D BACKGROUND ─────────────────────────────── -->
<canvas id="bgCanvas"></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
(function () {
  'use strict';
  const canvas   = document.getElementById('bgCanvas');
  const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: false });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.1;
  const scene  = new THREE.Scene();
  scene.background = new THREE.Color(0x060d18);
  scene.fog = new THREE.FogExp2(0x060d18, 0.035);
  const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 200);
  camera.position.set(0, 2, 14);
  camera.lookAt(0, 0, 0);
  scene.add(new THREE.AmbientLight(0xffffff, 0.25));
  const keyLight = new THREE.DirectionalLight(0xfff4e0, 2.2);
  keyLight.position.set(5, 10, 8); scene.add(keyLight);
  const rimLight = new THREE.DirectionalLight(0x4466ff, 0.9);
  rimLight.position.set(-8, 4, -5); scene.add(rimLight);
  const goldSpot = new THREE.PointLight(0xd4ae5a, 3.5, 30);
  goldSpot.position.set(0, 3, 3); scene.add(goldSpot);
  const goldMat = new THREE.MeshStandardMaterial({ color: 0xd4ae5a, metalness: 1.0, roughness: 0.08 });
  const ring = new THREE.Mesh(new THREE.TorusGeometry(2.2, 0.42, 64, 128), goldMat);
  scene.add(ring);
  const band = new THREE.Mesh(new THREE.TorusGeometry(2.2, 0.12, 16, 128),
    new THREE.MeshStandardMaterial({ color: 0x8b6914, metalness: 1.0, roughness: 0.12 }));
  scene.add(band);
  const gemMat = new THREE.MeshPhysicalMaterial({ color: 0xffffff, metalness: 0, roughness: 0, transmission: 1.0, thickness: 0.8, ior: 2.4, reflectivity: 0.9, envMapIntensity: 3.0 });
  const gem = new THREE.Mesh(new THREE.OctahedronGeometry(0.55, 2), gemMat);
  gem.position.set(0, 2.2, 0); scene.add(gem);
  const smallGemMat = new THREE.MeshPhysicalMaterial({ color: 0xaaccff, metalness: 0, roughness: 0, transmission: 1.0, thickness: 0.4, ior: 2.2 });
  for (let i = 0; i < 8; i++) {
    const angle = (i / 8) * Math.PI * 2, r = 2.2;
    const m = new THREE.Mesh(new THREE.OctahedronGeometry(0.18, 1), i % 3 === 0 ? goldMat : smallGemMat);
    m.position.set(Math.cos(angle)*r, Math.sin(angle)*r, 0); ring.add(m);
  }
  const pCount = 400, pPos = new Float32Array(pCount*3);
  for (let i=0;i<pCount;i++){pPos[i*3]=(Math.random()-.5)*36;pPos[i*3+1]=(Math.random()-.5)*24;pPos[i*3+2]=(Math.random()-.5)*24;}
  const pGeo = new THREE.BufferGeometry();
  pGeo.setAttribute('position', new THREE.BufferAttribute(pPos, 3));
  scene.add(new THREE.Points(pGeo, new THREE.PointsMaterial({ color: 0xd4ae5a, size: 0.07, transparent: true, opacity: 0.5 })));
  const bgRing = new THREE.Mesh(new THREE.TorusGeometry(6,0.05,16,200), new THREE.MeshBasicMaterial({color:0xd4ae5a,transparent:true,opacity:0.10}));
  bgRing.rotation.x = Math.PI/6; scene.add(bgRing);
  let mouseX=0,mouseY=0;
  document.addEventListener('mousemove',e=>{mouseX=(e.clientX/window.innerWidth-.5)*2;mouseY=(e.clientY/window.innerHeight-.5)*2;});
  const clock = new THREE.Clock();
  function animate(){requestAnimationFrame(animate);const t=clock.getElapsedTime();ring.rotation.y=t*0.28;ring.rotation.x=0.5+Math.sin(t*0.15)*0.1+mouseY*0.07;ring.rotation.z=mouseX*0.05;band.rotation.copy(ring.rotation);gem.rotation.y=t*0.8;gem.position.y=2.2+Math.sin(t*0.9)*0.08;bgRing.rotation.z=t*0.05;goldSpot.intensity=3.5+Math.sin(t*1.1)*1.0;camera.position.x=mouseX*0.8;camera.position.y=2+mouseY*0.4;camera.lookAt(0,0,0);renderer.render(scene,camera);}
  animate();
  window.addEventListener('resize',()=>{camera.aspect=window.innerWidth/window.innerHeight;camera.updateProjectionMatrix();renderer.setSize(window.innerWidth,window.innerHeight);});
})();
</script>
HTML;
}

function renderNav($active = 'home') {
  $links = [
    'home'    => ['href' => 'index.php',    'label' => 'Home'],
    'catalog' => ['href' => 'catalogue.php',  'label' => 'Collection'],
  ];
  $linksHtml = '';
  foreach ($links as $key => $link) {
    $cls = $key === $active ? ' class="active"' : '';
    $linksHtml .= "<li><a href=\"{$link['href']}\"{$cls}>{$link['label']}</a></li>\n";
  }

  $user = utilisateur_connecte();
  if ($user) {
    $firstName = htmlspecialchars($user['prenom']);
    $role      = $user['role'] ?? 'user';
    // Avatar
    $avatarSrc = '';
    if (!empty($user['avatar'])) {
      $avatarSrc = 'uploads/avatars/' . htmlspecialchars($user['avatar']);
    }
    $avatarHtml = $avatarSrc
      ? "<img src=\"{$avatarSrc}\" alt=\"\" class=\"nav-avatar-img\">"
      : "<span class=\"nav-avatar-initials\">" . htmlspecialchars(mb_substr($user['prenom'],0,1) . mb_substr($user['nom'],0,1)) . "</span>";

    $adminLink = $role === 'admin'
      ? '<a href="administration.php" class="nav-admin-link">⚙ Admin</a>'
      : '';

    $userHtml = <<<USER
<div class="nav-user">
  <div class="nav-user-avatar" id="navAvatarBtn">
    {$avatarHtml}
  </div>
  <div class="nav-user-dropdown" id="navDropdown">
    <div class="nav-dropdown-header">
      <strong>{$firstName}</strong>
      <span class="nav-dropdown-role">{$role}</span>
    </div>
    <a href="profil.php" class="nav-dropdown-item">My Profile</a>
    {$adminLink}
    <a href="deconnexion.php" class="nav-dropdown-item nav-dropdown-logout">Sign Out</a>
  </div>
</div>
USER;
  } else {
    $userHtml = '<a href="connexion.php" class="nav-signin-btn">Sign In</a>';
  }

  echo <<<HTML
<nav id="mainNav">
  <a href="index.php" class="nav-logo">
    <span class="logo-main">Perla Vita</span>
    <span class="logo-sub">Maison de Joaillerie</span>
  </a>
  <ul class="nav-links">
    {$linksHtml}
  </ul>
  <div class="nav-right">
    <div class="nav-cart nav-cart--catalog-only">
      <button class="cart-btn" id="toggleCart" aria-label="View cart">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
          <path d="M16 10a4 4 0 01-8 0"/>
        </svg>
        <span class="cart-count" id="cartCount" style="display:none">0</span>
      </button>
    </div>
    {$userHtml}
  </div>
</nav>
<script>
(function(){
  const btn = document.getElementById('navAvatarBtn');
  const drop = document.getElementById('navDropdown');
  if (!btn || !drop) return;
  btn.addEventListener('click', function(e){
    e.stopPropagation();
    drop.classList.toggle('open');
  });
  document.addEventListener('click', function(){
    drop && drop.classList.remove('open');
  });
})();
</script>
HTML;
}

function renderFooter() {
  echo <<<HTML
<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <span class="logo-main">Perla Vita</span>
      <span class="logo-sub">Maison de Joaillerie</span>
      <p>Handcrafted fine jewellery born from a passion for beauty, precision, and the timeless art of adornment.</p>
    </div>
    <div class="footer-col">
      <h4>Collections</h4>
      <ul>
        <li><a href="catalogue.php">Necklaces</a></li>
        <li><a href="catalogue.php">Rings</a></li>
        <li><a href="catalogue.php">Earrings</a></li>
        <li><a href="catalogue.php">Bracelets</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Client Care</h4>
      <ul>
        <li><a href="#">Shipping &amp; Returns</a></li>
        <li><a href="#">Size Guide</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 Perla Vita Maison de Joaillerie. All rights reserved.</p>
    <p>Crafted with love &amp; precision</p>
  </div>
</footer>
<div id="toast" class="toast"></div>
<script src="script/app.js"></script>
HTML;
}
?>
