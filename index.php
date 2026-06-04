<?php
require_once 'php/composants.php';
require_once 'php/produits.php';
require_once 'php/images.php';

$topRated = getTopRated(6);
renderHead('Welcome', '<link rel="stylesheet" href="css/home.css">');
renderNav('home');
?>

<!-- ─── HERO (pure 3-D Three.js background) ────────────────── -->
<section class="hero">
  <!-- Three.js renders here -->
  <canvas id="heroCanvas"></canvas>
  <div class="hero-overlay"></div>

  <div class="hero-content">
    <div class="hero-text">
      <span class="hero-label animate-fade-up delay-1">Maison de Joaillerie · Est. 1998</span>
      <h1 class="hero-title animate-fade-up delay-2">
        Where Every<br>
        <em>Jewel</em> Holds<br>
        a Soul
      </h1>
      <p class="hero-desc animate-fade-up delay-3">
        Handcrafted fine jewellery for those who believe beauty<br>
        is not worn — it is expressed.
      </p>
      <div class="hero-actions animate-fade-up delay-4">
        <a href="catalogue.php" class="btn-primary"><span>Explore Collection</span></a>
        <a href="#story" class="btn-ghost">Our Atelier ↓</a>
      </div>
    </div>

    <div class="hero-visual animate-fade-up delay-3">
      <div class="hero-img-frame">
        <img
          src="https://images.unsplash.com/photo-1602173574767-37ac01994b2a?w=700&q=80&auto=format&fit=crop"
          alt="Lumière Collection ring"
          loading="eager"
        >
      </div>
      <div class="canvas-label">
        <span class="cl-name">Lumière Collection</span>
        <span class="cl-sub">18k Gold · Diamond</span>
      </div>
      <div class="hero-badges">
        <div class="hero-badge hb-left">
          <span class="hb-num">105+</span>
          <span class="hb-txt">Unique Pieces</span>
        </div>
        <div class="hero-badge hb-right">
          <span class="hb-num">100%</span>
          <span class="hb-txt">Certified Gems</span>
        </div>
      </div>
    </div>
  </div>

  <div class="hero-scroll">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- ─── MARQUEE ───────────────────────────────────────────────── -->
<div class="marquee-wrap">
  <div class="marquee-track">
    <?php
    $items = ['✦ Diamonds','◆ Sapphires','✧ Emeralds','◈ Rubies','✦ Pearls','◇ Platinum',
              '✦ Diamonds','◆ Sapphires','✧ Emeralds','◈ Rubies','✦ Pearls','◇ Platinum',
              '✦ Diamonds','◆ Sapphires','✧ Emeralds','◈ Rubies'];
    foreach ($items as $item) echo "<span>{$item}</span>";
    ?>
  </div>
</div>

<!-- ─── TOP RATED ─────────────────────────────────────────────── -->
<section class="top-rated" id="top-rated">
  <div class="container">
    <div class="section-header animate-fade-up">
      <span class="section-label">Acclaimed Creations</span>
      <h2 class="section-title">Most Beloved <em>Pieces</em></h2>
      <div class="section-divider"><div class="diamond"></div></div>
    </div>
    <div class="top-grid">
      <?php
            foreach ($topRated as $i => $p):
        $cat = strtolower($p['category']);
        $img = getProductImage($p['id']);
      ?>
      <div class="product-card animate-fade-up delay-<?= ($i % 4) + 1 ?>"
           onclick="window.location='catalogue.php'">
        <div class="product-img-wrap">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1602173574767-37ac01994b2a?w=600&q=80&auto=format&fit=crop';this.referrerPolicy='no-referrer'">
          <?php if ($p['badge']): ?>
          <div class="product-badge <?= $p['badge']==='Top Rated'?'gold':'' ?>"><?= $p['badge'] ?></div>
          <?php endif; ?>
          <div class="product-actions">
            <button class="add-to-cart-btn"
              onclick="event.stopPropagation();Cart.add({id:<?= $p['id'] ?>,name:'<?= addslashes($p['name']) ?>',price:<?= $p['price'] ?>,category:'<?= $p['category'] ?>',icon:'<?= $p['icon'] ?>'})">
              Add to Selection
            </button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category"><?= ucfirst($p['category']) ?></span>
          <h3 class="product-name"><?= $p['name'] ?></h3>
          <div class="product-stars">
            <?php for ($s=0;$s<$p['rating'];$s++) echo '★'; ?>
            <?php for ($s=$p['rating'];$s<5;$s++) echo '☆'; ?>
          </div>
          <div class="product-meta"><?= $p['material'] ?></div>
          <div class="product-price">$<?= number_format($p['price']) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="section-cta animate-fade-up">
      <a href="catalogue.php" class="btn-primary"><span>View Full Collection</span></a>
    </div>
  </div>
</section>

<!-- ─── STORY / VALUES ──────────────────────────────────────── -->
<section class="story" id="story">
  <div class="story-bg"></div>
  <div class="container">
    <div class="story-grid">
      <div class="story-visual animate-fade-up">
        <div class="story-img-wrap">
          <img
            src="https://images.unsplash.com/photo-1617038220319-276d3cfab638?w=800&q=80&auto=format&fit=crop"
            alt="Artisan crafting jewellery"
            loading="lazy"
          >
          <div class="story-img-border"></div>
        </div>
      </div>
      <div class="story-text">
        <span class="section-label animate-fade-up">Our Philosophy</span>
        <h2 class="section-title animate-fade-up delay-1">Crafted for the<br><em>Extraordinary</em></h2>
        <p class="story-p animate-fade-up delay-2">Every piece in the Perla Vita collection is born from a dialogue between the rarest materials on earth and the hands of master artisans who have devoted their lives to the pursuit of perfection.</p>
        <p class="story-p animate-fade-up delay-3">We source only certified, ethically mined gemstones, setting them in precious metals using techniques passed down through generations of European goldsmiths.</p>
        <div class="story-stats animate-fade-up delay-4">
          <div class="stat"><span class="stat-num">27</span><span class="stat-lbl">Years of Excellence</span></div>
          <div class="stat-divider"></div>
          <div class="stat"><span class="stat-num">8,400+</span><span class="stat-lbl">Happy Clients</span></div>
          <div class="stat-divider"></div>
          <div class="stat"><span class="stat-num">100%</span><span class="stat-lbl">Ethically Sourced</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── FEATURED CATEGORIES (photo cards) ─────────────────── -->
<section class="categories">
  <div class="container">
    <div class="section-header animate-fade-up">
      <span class="section-label">Shop by Category</span>
      <h2 class="section-title">Discover Your <em>Perfect Piece</em></h2>
      <div class="section-divider"><div class="diamond"></div></div>
    </div>
    <div class="cat-grid">
      <?php
      $cats = [
        ['icon'=>'◆','name'=>'Rings',     'sub'=>'From $185','href'=>'catalogue.php#rings',
         'img'=>'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600&q=80&auto=format&fit=crop'],
        ['icon'=>'✦','name'=>'Necklaces', 'sub'=>'From $195','href'=>'catalogue.php#necklaces',
         'img'=>'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&q=80&auto=format&fit=crop'],
        ['icon'=>'☽','name'=>'Earrings',  'sub'=>'From $140','href'=>'catalogue.php#earrings',
         'img'=>'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&q=80&auto=format&fit=crop'],
        ['icon'=>'━','name'=>'Bracelets', 'sub'=>'From $140','href'=>'catalogue.php#bracelets',
         'img'=>'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=600&q=80&auto=format&fit=crop'],
        ['icon'=>'◈','name'=>'Sets',      'sub'=>'From $640','href'=>'catalogue.php#sets',
         'img'=>'https://images.unsplash.com/photo-1589128777073-263566ae5e4d?w=600&q=80&auto=format&fit=crop'],
        ['icon'=>'◯','name'=>'Pendants',  'sub'=>'From $215','href'=>'catalogue.php#pendants',
         'img'=>'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&q=80&auto=format&fit=crop'],
      ];
      foreach ($cats as $i => $cat): ?>
      <a href="<?= $cat['href'] ?>" class="cat-card animate-fade-up delay-<?= ($i%3)+1 ?>">
        <img src="<?= $cat['img'] ?>" alt="<?= $cat['name'] ?>" loading="lazy" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1602173574767-37ac01994b2a?w=600&q=80&auto=format&fit=crop';this.referrerPolicy='no-referrer'">
        <div class="cat-overlay"></div>
        <div class="cat-body">
          <span class="cat-icon"><?= $cat['icon'] ?></span>
          <span class="cat-name"><?= $cat['name'] ?></span>
          <span class="cat-sub"><?= $cat['sub'] ?></span>
          <span class="cat-arrow">→</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── TRUST STRIP ──────────────────────────────────────────── -->
<section class="trust-strip">
  <div class="container">
    <div class="trust-grid">
      <div class="trust-item animate-fade-up delay-1">
        <div class="trust-icon">◆</div>
        <div><h4>Certified Diamonds</h4><p>GIA &amp; IGI certified stones</p></div>
      </div>
      <div class="trust-item animate-fade-up delay-2">
        <div class="trust-icon">○</div>
        <div><h4>Free Engraving</h4><p>Make it uniquely yours</p></div>
      </div>
      <div class="trust-item animate-fade-up delay-3">
        <div class="trust-icon">✦</div>
        <div><h4>Complimentary Shipping</h4><p>On all orders over $300</p></div>
      </div>
      <div class="trust-item animate-fade-up delay-4">
        <div class="trust-icon">◈</div>
        <div><h4>Lifetime Warranty</h4><p>Craftsmanship guaranteed</p></div>
      </div>
    </div>
  </div>
</section>

<?php renderFooter(); ?>

<!-- 3D background is now global via partials.php -->
<script>
// No-op — 3D scene managed globally
if (false) {
(function () {
  'use strict';

  const canvas  = document.getElementById('heroCanvas');
  const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFSoftShadowMap;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.2;

  const scene  = new THREE.Scene();
  scene.background = new THREE.Color(0x060e1a);
  scene.fog = new THREE.FogExp2(0x060e1a, 0.04);

  const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 200);
  camera.position.set(0, 2, 14);
  camera.lookAt(0, 0, 0);

  /* ── Lights ── */
  const ambient = new THREE.AmbientLight(0xffffff, 0.3);
  scene.add(ambient);

  const keyLight = new THREE.DirectionalLight(0xfff4e0, 2.5);
  keyLight.position.set(5, 10, 8);
  keyLight.castShadow = true;
  scene.add(keyLight);

  const rimLight = new THREE.DirectionalLight(0x4466ff, 1.0);
  rimLight.position.set(-8, 4, -5);
  scene.add(rimLight);

  const goldSpot = new THREE.PointLight(0xd4ae5a, 4, 30);
  goldSpot.position.set(0, 3, 3);
  scene.add(goldSpot);

  const blueSpot = new THREE.PointLight(0x6699ff, 2, 25);
  blueSpot.position.set(-5, -2, 2);
  scene.add(blueSpot);

  /* ── Gold material ── */
  const goldMat = new THREE.MeshStandardMaterial({
    color:     0xd4ae5a,
    metalness: 1.0,
    roughness: 0.08,
    envMapIntensity: 2.0,
  });

  /* ── Main ring ── */
  const ringGeo = new THREE.TorusGeometry(2.2, 0.42, 64, 128);
  const ring    = new THREE.Mesh(ringGeo, goldMat);
  ring.castShadow = true;
  ring.receiveShadow = true;
  scene.add(ring);

  /* ── Band engrave stripe (darker torus inside the ring) ── */
  const bandMat = new THREE.MeshStandardMaterial({
    color: 0x8b6914, metalness: 1.0, roughness: 0.12,
  });
  const bandGeo = new THREE.TorusGeometry(2.2, 0.12, 16, 128);
  const band    = new THREE.Mesh(bandGeo, bandMat);
  scene.add(band);

  /* ── Gemstone (diamond cut approximation with IcosahedronGeometry) ── */
  const gemMat = new THREE.MeshPhysicalMaterial({
    color:       0xffffff,
    metalness:   0.0,
    roughness:   0.0,
    transmission:1.0,
    thickness:   0.8,
    ior:         2.4,
    reflectivity:0.9,
    envMapIntensity: 3.0,
  });
  const gemGeo = new THREE.OctahedronGeometry(0.55, 2);
  const gem    = new THREE.Mesh(gemGeo, gemMat);
  gem.position.set(0, 2.2, 0);          // sits on top of the ring
  scene.add(gem);

  /* ── Small satellite gems ── */
  const smallGemMat = new THREE.MeshPhysicalMaterial({
    color: 0xaaccff, metalness: 0.0, roughness: 0.0,
    transmission: 1.0, thickness: 0.4, ior: 2.2,
    envMapIntensity: 2.5,
  });
  const satellites = [];
  const NUM_SAT = 8;
  for (let i = 0; i < NUM_SAT; i++) {
    const angle  = (i / NUM_SAT) * Math.PI * 2;
    const radius = 2.2;
    const sGeo   = new THREE.OctahedronGeometry(0.18, 1);
    const sMesh  = new THREE.Mesh(sGeo, i % 3 === 0 ? goldMat : smallGemMat);
    sMesh.position.set(Math.cos(angle) * radius, Math.sin(angle) * radius, 0);
    ring.add(sMesh);       // child of ring, so they rotate together
    satellites.push({ mesh: sMesh, angle, speed: 0.5 + Math.random() * 0.5 });
  }

  /* ── Floating particles ── */
  const particleCount = 350;
  const pPositions    = new Float32Array(particleCount * 3);
  for (let i = 0; i < particleCount; i++) {
    pPositions[i*3]   = (Math.random() - 0.5) * 30;
    pPositions[i*3+1] = (Math.random() - 0.5) * 20;
    pPositions[i*3+2] = (Math.random() - 0.5) * 20;
  }
  const pGeo = new THREE.BufferGeometry();
  pGeo.setAttribute('position', new THREE.BufferAttribute(pPositions, 3));
  const pMat = new THREE.PointsMaterial({
    color: 0xd4ae5a, size: 0.08, transparent: true, opacity: 0.55,
    sizeAttenuation: true,
  });
  scene.add(new THREE.Points(pGeo, pMat));

  /* ── Second background ring (large, translucent) ── */
  const bgRingGeo = new THREE.TorusGeometry(6, 0.06, 16, 200);
  const bgRingMat = new THREE.MeshBasicMaterial({ color: 0xd4ae5a, transparent: true, opacity: 0.12 });
  const bgRing    = new THREE.Mesh(bgRingGeo, bgRingMat);
  bgRing.rotation.x = Math.PI / 6;
  scene.add(bgRing);

  const bgRing2Geo = new THREE.TorusGeometry(8.5, 0.04, 16, 200);
  const bgRing2    = new THREE.Mesh(bgRing2Geo, new THREE.MeshBasicMaterial({
    color: 0x8899ff, transparent: true, opacity: 0.07,
  }));
  bgRing2.rotation.x = -Math.PI / 4;
  bgRing2.rotation.y = Math.PI / 5;
  scene.add(bgRing2);

  /* ── Mouse parallax ── */
  let mouseX = 0, mouseY = 0;
  document.addEventListener('mousemove', (e) => {
    mouseX = (e.clientX / window.innerWidth  - 0.5) * 2;
    mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
  });

  /* ── Clock ── */
  const clock = new THREE.Clock();

  /* ── Animate ── */
  function animate() {
    requestAnimationFrame(animate);
    const t = clock.getElapsedTime();

    // Main ring rotation
    ring.rotation.y  =  t * 0.28;
    ring.rotation.x  =  0.5 + Math.sin(t * 0.15) * 0.1 + mouseY * 0.08;
    ring.rotation.z  =  mouseX * 0.06;

    // Band follows the ring
    band.rotation.copy(ring.rotation);

    // Gem bobs
    gem.rotation.y   =  t * 0.8;
    gem.position.y   =  2.2 + Math.sin(t * 0.9) * 0.08;

    // Background rings drift
    bgRing.rotation.z  = t * 0.06;
    bgRing2.rotation.x = t * 0.04;

    // Gold light breathes
    goldSpot.intensity = 4 + Math.sin(t * 1.1) * 1.2;

    // Camera gentle sway
    camera.position.x = mouseX * 1.2;
    camera.position.y = 2 + mouseY * 0.6;
    camera.lookAt(0, 0, 0);

    renderer.render(scene, camera);
  }

  animate();

  /* ── Resize ── */
  window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });
})(); } // end no-op
</script>
</body>
</html>
