<?php
require_once 'php/composants.php';
require_once 'php/produits.php';
require_once 'php/images.php';
exiger_connexion('connexion.php?redirect=catalogue.php');

$products = getProducts();
$counts = getCategoryCounts();

$categories = [
  'all'       => 'All Pieces',
  'necklaces' => 'Necklaces',
  'rings'     => 'Rings',
  'earrings'  => 'Earrings',
  'bracelets' => 'Bracelets',
  'pendants'  => 'Pendants',
  'sets'      => 'Sets',
  'watches'   => 'Watches',
  'brooches'  => 'Brooches',
  'anklets'   => 'Anklets',
  'hair'      => 'Hair',
];


renderHead('Collection', '<link rel="stylesheet" href="css/catalog.css">');
renderNav('catalog');
// Mark body as catalog page so nav cart button shows
echo '<script>document.body.classList.add("page-catalog");</script>';
?>

<!-- ─── PAGE HERO ─────────────────────────────────────────────── -->
<section class="catalog-hero">
  <div class="ch-content">
    <span class="section-label animate-fade-up">The Perla Vita Edit</span>
    <h1 class="ch-title animate-fade-up delay-1">Our <em>Collection</em></h1>
    <p class="ch-desc animate-fade-up delay-2">Over 105 handcrafted jewels, each one a testament to the art of fine goldsmithing. Discover your perfect piece.</p>
  </div>
</section>

<!-- ─── CATALOG BODY ─────────────────────────────────────────── -->
<div class="catalog-layout">

  <!-- FILTERS SIDEBAR -->
  <aside class="filter-aside" id="filterAside">
    <div class="fa-header">
      <span class="section-label">Filter</span>
    </div>

    <!-- Search -->
    <div class="filter-search">
      <input type="text" id="catalogSearch" placeholder="Search pieces…" autocomplete="off">
      <span class="search-icon">⌕</span>
    </div>

    <!-- Categories -->
    <div class="filter-group">
      <h4 class="fg-title">Categories</h4>
      <div class="filter-btns">
        <?php foreach ($categories as $key => $label): ?>
        <button class="filter-btn <?= $key === 'all' ? 'active' : '' ?>" data-category="<?= $key ?>">
          <span><?= $label ?></span>
          <span class="fc-count"><?= $counts[$key] ?? 0 ?></span>
        </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Price Filter (visual only) -->
    <div class="filter-group">
      <h4 class="fg-title">Price Range</h4>
      <div class="price-range">
        <div class="pr-track">
          <div class="pr-fill"></div>
        </div>
        <div class="pr-labels">
          <span>$140</span>
          <span>$7,800</span>
        </div>
      </div>
    </div>

    <!-- Materials -->
    <div class="filter-group">
      <h4 class="fg-title">Materials</h4>
      <div class="material-list">
        <?php
        $mats = ['18k Gold','White Gold','Rose Gold','Platinum','Sterling Silver','Diamonds','Pearls','Sapphires','Emeralds','Rubies'];
        foreach ($mats as $m): ?>
        <label class="mat-item">
          <input type="checkbox" class="mat-cb">
          <span class="mat-box"></span>
          <span><?= $m ?></span>
        </label>
        <?php endforeach; ?>
      </div>
    </div>
  </aside>

  <!-- PRODUCTS AREA -->
  <main class="catalog-main">
    <div class="cm-header">
      <div class="cm-count">
        <span id="visibleCount"><?= count($products) ?></span> pieces
      </div>
      <div class="cm-sort">
        <select class="sort-select">
          <option>Featured</option>
          <option>Price: Low to High</option>
          <option>Price: High to Low</option>
          <option>Top Rated</option>
          <option>Newest</option>
        </select>
      </div>
    </div>

    <div class="catalog-grid" id="catalogGrid">
      <?php foreach ($products as $i => $p):
        $img = getProductImage($p['id']);
      ?>
      <div class="product-card catalog-item animate-fade-up delay-<?= ($i % 4) + 1 ?>"
           data-category="<?= $p['category'] ?>"
           data-id="<?= $p['id'] ?>"
           style="flex-direction:column">
        <div class="product-img-wrap">
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1602173574767-37ac01994b2a?w=600&q=80&auto=format&fit=crop';this.referrerPolicy='no-referrer'">
          <?php if ($p['badge']): ?>
          <div class="product-badge <?= in_array($p['badge'],['Top Rated','Luxury','Exclusive']) ? 'gold' : '' ?>"><?= $p['badge'] ?></div>
          <?php endif; ?>
          <div class="product-actions">
            <button class="add-to-cart-btn"
              onclick="Cart.add({id:<?= $p['id'] ?>,name:'<?= addslashes($p['name']) ?>',price:<?= $p['price'] ?>,category:'<?= $p['category'] ?>',icon:'<?= $p['icon'] ?>'})">
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
          <div class="catalog-item-footer">
            <div class="product-price">$<?= number_format($p['price']) ?></div>
            <button class="quick-add"
              onclick="Cart.add({id:<?= $p['id'] ?>,name:'<?= addslashes($p['name']) ?>',price:<?= $p['price'] ?>,category:'<?= $p['category'] ?>',icon:'<?= $p['icon'] ?>'})">
              +
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </main>
</div>

<!-- ─── CART SIDEBAR ──────────────────────────────────────────── -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<aside class="cart-sidebar" id="cartSidebar">
  <div class="cs-header">
    <div>
      <h3 class="cs-title">Your Selection</h3>
      <span class="cs-count"><span id="sidebarCount">0</span> pieces</span>
    </div>
    <button class="cs-close" onclick="document.getElementById('cartSidebar').classList.remove('open')">×</button>
  </div>

  <div class="cs-items" id="sidebarItems">
    <!-- Filled by JS -->
  </div>

  <div class="cs-footer">
    <div class="cs-total-row">
      <span>Total</span>
      <span id="sidebarTotal" class="cs-total-amt">$0.00</span>
    </div>
    <p class="cs-shipping">Complimentary shipping on orders over $300</p>
    <a href="commande.php" class="btn-primary" style="width:100%;justify-content:center;margin-top:16px">
      <span>Proceed to Checkout</span>
    </a>
    <button onclick="document.getElementById('cartSidebar').classList.remove('open')"
      class="btn-outline" style="width:100%;justify-content:center;margin-top:12px">
      <span>Continue Shopping</span>
    </button>
  </div>
</aside>

<!-- Floating Cart Toggle Button -->
<button class="floating-cart" id="floatingCart">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
    <path d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
    <line x1="3" y1="6" x2="21" y2="6"/>
    <path d="M16 10a4 4 0 01-8 0"/>
  </svg>
  <span>My Selection</span>
  <span class="fc-badge" id="floatingCount">0</span>
</button>

<script>
document.addEventListener('cartUpdated', e => {
  const b = document.getElementById('floatingCount');
  if (b) { b.textContent = e.detail.count; b.style.display = e.detail.count > 0 ? 'flex' : 'none'; }
});
</script>

<?php renderFooter(); ?>
</body>
</html>
