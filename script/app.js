/* ── Perla Vita — Main JavaScript ── */

// ─── CART STATE ───────────────────────────────────────────────
const Cart = (() => {
  const STORAGE_KEY = 'perlavita_cart';

  function getAll() {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    } catch { return []; }
  }

  function save(items) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    dispatchUpdate();
  }

  function add(product) {
    const items = getAll();
    const existing = items.find(i => i.id === product.id);
    if (existing) {
      existing.qty = (existing.qty || 1) + 1;
    } else {
      items.push({ ...product, qty: 1 });
    }
    save(items);
    showToast(`${product.name} added to your selection`);
    animateCartBtn();
  }

  function remove(id) {
    const items = getAll().filter(i => i.id !== id);
    save(items);
  }

  function updateQty(id, qty) {
    const items = getAll();
    const item = items.find(i => i.id === id);
    if (item) {
      if (qty <= 0) return remove(id);
      item.qty = qty;
      save(items);
    }
  }

  function clear() {
    localStorage.removeItem(STORAGE_KEY);
    dispatchUpdate();
  }

  function total() {
    return getAll().reduce((sum, i) => sum + (parseFloat(i.price) * (i.qty || 1)), 0);
  }

  function count() {
    return getAll().reduce((sum, i) => sum + (i.qty || 1), 0);
  }

  function dispatchUpdate() {
    document.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count: count(), total: total() } }));
  }

  return { getAll, add, remove, updateQty, clear, total, count, dispatchUpdate };
})();

// ─── TOAST ────────────────────────────────────────────────────
function showToast(msg, duration = 3000) {
  let toast = document.getElementById('toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'toast';
    toast.className = 'toast';
    document.body.appendChild(toast);
  }
  toast.innerHTML = `<span style="color:var(--gold-light);margin-right:6px">✦</span><span>${msg}</span>`;
  toast.classList.add('show');
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), duration);
}

// ─── CART BUTTON ANIMATION ────────────────────────────────────
function animateCartBtn() {
  const btn = document.querySelector('.cart-btn');
  if (!btn) return;
  btn.style.transform = 'scale(1.3)';
  setTimeout(() => btn.style.transform = 'scale(1)', 300);
}

// ─── UPDATE CART COUNT BADGE ──────────────────────────────────
function updateCartBadge() {
  const badge = document.getElementById('cartCount');
  if (!badge) return;
  const n = Cart.count();
  badge.textContent = n;
  badge.style.display = n > 0 ? 'flex' : 'none';
}

document.addEventListener('cartUpdated', updateCartBadge);
window.addEventListener('load', () => {
  updateCartBadge();
  Cart.dispatchUpdate();
});

// ─── NAV SCROLL ───────────────────────────────────────────────
const nav = document.querySelector('nav');
if (nav) {
  window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);
  });
}

// ─── INTERSECTION OBSERVER ANIMATIONS ────────────────────────
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.style.animationPlayState = 'running';
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.animate-fade-up').forEach(el => {
  el.style.animationPlayState = 'paused';
  observer.observe(el);
});

// ─── 3D JEWEL CANVAS (homepage) ──────────────────────────────
function initJewelCanvas(canvasId) {
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let W = canvas.offsetWidth, H = canvas.offsetHeight;
  canvas.width = W; canvas.height = H;

  let t = 0;
  const gems = Array.from({ length: 24 }, (_, i) => ({
    angle: (i / 24) * Math.PI * 2,
    r: 80 + Math.random() * 60,
    size: 3 + Math.random() * 5,
    speed: 0.003 + Math.random() * 0.004,
    color: ['#C9A84C','#102C57','#DAC0A3','#EADBC8','#e8c87a'][Math.floor(Math.random()*5)],
    opacity: 0.4 + Math.random() * 0.5,
  }));

  function drawCrystal(cx, cy, size, color, alpha) {
    ctx.save();
    ctx.globalAlpha = alpha;
    ctx.translate(cx, cy);
    ctx.rotate(t * 0.5);
    ctx.beginPath();
    // Diamond shape
    ctx.moveTo(0, -size * 1.6);
    ctx.lineTo(size, 0);
    ctx.lineTo(0, size * 0.8);
    ctx.lineTo(-size, 0);
    ctx.closePath();
    const grad = ctx.createRadialGradient(0, -size * 0.5, 0, 0, 0, size * 1.6);
    grad.addColorStop(0, '#fff');
    grad.addColorStop(0.3, color);
    grad.addColorStop(1, 'transparent');
    ctx.fillStyle = grad;
    ctx.fill();
    ctx.restore();
  }

  function drawMainGem(cx, cy) {
    ctx.save();
    ctx.translate(cx, cy);
    const pulse = 1 + Math.sin(t * 1.5) * 0.04;
    ctx.scale(pulse, pulse);

    // Outer glow
    const glow = ctx.createRadialGradient(0, 0, 20, 0, 0, 90);
    glow.addColorStop(0, 'rgba(201,168,76,0.3)');
    glow.addColorStop(1, 'rgba(201,168,76,0)');
    ctx.beginPath();
    ctx.arc(0, 0, 90, 0, Math.PI * 2);
    ctx.fillStyle = glow;
    ctx.fill();

    // Diamond facets
    const R = 52, sides = 8;
    for (let i = 0; i < sides; i++) {
      const a1 = (i / sides) * Math.PI * 2 + t * 0.2;
      const a2 = ((i + 1) / sides) * Math.PI * 2 + t * 0.2;
      const x1 = Math.cos(a1) * R, y1 = Math.sin(a1) * R;
      const x2 = Math.cos(a2) * R, y2 = Math.sin(a2) * R;
      const facetGrad = ctx.createLinearGradient(x1, y1, 0, 0);
      const hue = (i / sides) * 40;
      facetGrad.addColorStop(0, `hsla(${40 + hue}, 60%, 80%, 0.9)`);
      facetGrad.addColorStop(1, `hsla(${200 + hue}, 60%, 30%, 0.7)`);
      ctx.beginPath();
      ctx.moveTo(0, 0);
      ctx.lineTo(x1, y1);
      ctx.lineTo(x2, y2);
      ctx.closePath();
      ctx.fillStyle = facetGrad;
      ctx.fill();
      ctx.strokeStyle = 'rgba(255,255,255,0.3)';
      ctx.lineWidth = 0.5;
      ctx.stroke();
    }

    // Center highlight
    const hl = ctx.createRadialGradient(-10, -10, 0, 0, 0, 40);
    hl.addColorStop(0, 'rgba(255,255,255,0.8)');
    hl.addColorStop(1, 'rgba(255,255,255,0)');
    ctx.beginPath();
    ctx.arc(0, 0, 40, 0, Math.PI * 2);
    ctx.fillStyle = hl;
    ctx.fill();

    ctx.restore();
  }

  function frame() {
    ctx.clearRect(0, 0, W, H);
    const cx = W / 2, cy = H / 2;

    // Orbiting gems
    gems.forEach(g => {
      g.angle += g.speed;
      const x = cx + Math.cos(g.angle) * g.r;
      const y = cy + Math.sin(g.angle) * (g.r * 0.5);
      drawCrystal(x, y, g.size, g.color, g.opacity * (0.7 + Math.sin(g.angle * 3) * 0.3));
    });

    // Sparkles
    for (let i = 0; i < 6; i++) {
      const sa = t * 0.7 + (i / 6) * Math.PI * 2;
      const sr = 110 + Math.sin(t + i) * 20;
      const sx = cx + Math.cos(sa) * sr;
      const sy = cy + Math.sin(sa) * (sr * 0.5);
      ctx.save();
      ctx.globalAlpha = 0.6 + Math.sin(t * 2 + i) * 0.3;
      ctx.fillStyle = '#C9A84C';
      ctx.beginPath();
      ctx.arc(sx, sy, 1.5, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();
    }

    drawMainGem(cx, cy);
    t += 0.01;
    requestAnimationFrame(frame);
  }

  frame();

  window.addEventListener('resize', () => {
    W = canvas.offsetWidth; H = canvas.offsetHeight;
    canvas.width = W; canvas.height = H;
  });
}

// ─── CATALOG FILTER (catalog page) ────────────────────────────
function initCatalogFilters() {
  const filterBtns = document.querySelectorAll('.filter-btn');
  const cards = document.querySelectorAll('.catalog-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const cat = btn.dataset.category;
      cards.forEach(card => {
        const match = cat === 'all' || card.dataset.category === cat;
        card.style.display = match ? 'flex' : 'none';
        if (match) {
          card.style.animation = 'none';
          card.offsetHeight; // reflow
          card.style.animation = 'fadeUp 0.4s ease forwards';
        }
      });
    });
  });
}

// ─── SIDEBAR CART (catalog page) ──────────────────────────────
function initSidebarCart() {
  const sidebar = document.getElementById('cartSidebar');
  if (!sidebar) return;

  const overlay = document.getElementById('sidebarOverlay');

  function openSidebar() {
    sidebar.classList.add('open');
    if (overlay) overlay.classList.add('overlay-open');
  }

  function closeSidebar() {
    sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('overlay-open');
  }

  function render() {
    const items = Cart.getAll();
    const list = document.getElementById('sidebarItems');
    const total = document.getElementById('sidebarTotal');
    const count = document.getElementById('sidebarCount');
    if (!list) return;

    if (count) count.textContent = Cart.count();

    if (items.length === 0) {
      list.innerHTML = `
        <div style="text-align:center;padding:48px 20px;color:rgba(240,230,214,0.4)">
          <div style="font-size:2.5rem;margin-bottom:12px;color:var(--gold-light)">✦</div>
          <p style="font-size:0.78rem;letter-spacing:0.1em">Your selection is empty</p>
        </div>`;
    } else {
      list.innerHTML = items.map(item => `
        <div class="sidebar-item" data-id="${item.id}">
          <div class="si-icon">${item.icon || '💎'}</div>
          <div class="si-info">
            <div class="si-name">${item.name}</div>
            <div class="si-price">$${(parseFloat(item.price) * item.qty).toFixed(2)}</div>
          </div>
          <div class="si-qty">
            <button onclick="Cart.updateQty(${item.id}, ${item.qty - 1})">−</button>
            <span>${item.qty}</span>
            <button onclick="Cart.updateQty(${item.id}, ${item.qty + 1})">+</button>
          </div>
          <button class="si-remove" onclick="Cart.remove(${item.id})">×</button>
        </div>`).join('');
    }

    if (total) total.textContent = '$' + Cart.total().toFixed(2);
  }

  document.addEventListener('cartUpdated', render);
  render();

  // Toggle sidebar via nav cart button
  const toggleBtn = document.getElementById('toggleCart');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
  }

  // Toggle via floating cart button
  const floatingBtn = document.getElementById('floatingCart');
  if (floatingBtn) {
    floatingBtn.addEventListener('click', () => {
      sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
  }

  // Close on overlay click
  if (overlay) overlay.addEventListener('click', closeSidebar);

  // Close buttons inside sidebar (inline onclick still work, but also wire up cs-close)
  const closeBtn = sidebar.querySelector('.cs-close');
  if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
}

// ─── SEARCH (catalog page) ────────────────────────────────────
function initSearch() {
  const input = document.getElementById('catalogSearch');
  if (!input) return;
  input.addEventListener('input', () => {
    const q = input.value.toLowerCase();
    document.querySelectorAll('.catalog-item').forEach(card => {
      const name = card.querySelector('.product-name')?.textContent.toLowerCase() || '';
      card.style.display = name.includes(q) ? 'flex' : 'none';
    });
  });
}

// ─── CHECKOUT SUMMARY (checkout page) ─────────────────────────
function initCheckout() {
  const container = document.getElementById('checkoutItems');
  if (!container) return;

  const items = Cart.getAll();

  if (items.length === 0) {
    container.innerHTML = `
      <div style="text-align:center;padding:60px 20px;color:var(--text-light)">
        <p style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-style:italic">Your cart is empty</p>
        <a href="catalog.php" class="btn-outline" style="display:inline-flex;margin-top:24px">
          <span>← Browse Collection</span>
        </a>
      </div>`;
    return;
  }

  container.innerHTML = items.map(item => `
    <div class="checkout-item">
      <div class="ci-icon">${item.icon || '💎'}</div>
      <div class="ci-info">
        <div class="ci-name">${item.name}</div>
        <div class="ci-cat">${item.category || ''}</div>
      </div>
      <div class="ci-qty">× ${item.qty}</div>
      <div class="ci-price">$${(parseFloat(item.price) * item.qty).toFixed(2)}</div>
    </div>
  `).join('');

  recalcTotals();
}

// ─── PAYMENT METHOD TABS ───────────────────────────────────────
function initPaymentTabs() {
  const tabs = document.querySelectorAll('.payment-tab');
  const panels = document.querySelectorAll('.payment-panel');
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      const panel = document.getElementById('panel-' + tab.dataset.method);
      if (panel) panel.classList.add('active');
    });
  });
}

// ─── CARD INPUT FORMATTING ─────────────────────────────────────
function initCardFormatting() {
  const num = document.getElementById('cardNumber');
  if (num) {
    num.addEventListener('input', () => {
      let v = num.value.replace(/\D/g, '').substring(0, 16);
      num.value = v.replace(/(.{4})/g, '$1 ').trim();
    });
  }
  const exp = document.getElementById('cardExpiry');
  if (exp) {
    exp.addEventListener('input', () => {
      let v = exp.value.replace(/\D/g, '').substring(0, 4);
      if (v.length >= 2) v = v.substring(0, 2) + '/' + v.substring(2);
      exp.value = v;
    });
  }
  const cvv = document.getElementById('cardCvv');
  if (cvv) {
    cvv.addEventListener('input', () => {
      cvv.value = cvv.value.replace(/\D/g, '').substring(0, 4);
    });
  }
}

// ─── COUPON CODES (server-validated) ──────────────────────────
let appliedCoupon = null;

function applyPromo() {
  const input = document.getElementById('promoInput');
  const msg   = document.getElementById('promoMsg');
  const btn   = document.getElementById('promoBtn');
  if (!input || !msg) return;

  const code = input.value.trim();
  if (!code) { showPromoMsg(msg, 'Please enter a promo code.', false); return; }

  // Get current subtotal for min-order check
  const cart = loadCart();
  const sub  = cart.reduce((s, i) => s + i.price * i.qty, 0);

  const fd = new FormData();
  fd.append('action', 'validate_coupon');
  fd.append('code', code);
  fd.append('total', sub.toFixed(2));

  fetch('api.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => {
      if (res.ok) {
        appliedCoupon = { code: res.code, discount: res.discount, type: res.type, value: res.value };
        input.disabled = true;
        if (btn) { btn.textContent = 'Remove'; btn.onclick = removePromo; }
        const label = res.type === 'percent' ? res.value + '% Off' : '$' + res.value + ' Off';
        showPromoMsg(msg, `✦ "${res.code}" applied — ${label}!`, true);
        recalcTotals();
      } else {
        appliedCoupon = null;
        showPromoMsg(msg, `✗ ${res.msg || 'Invalid promo code.'}`, false);
        recalcTotals();
      }
    })
    .catch(() => showPromoMsg(msg, 'Could not validate coupon. Try again.', false));
}

function removePromo() {
  appliedCoupon = null;
  const input = document.getElementById('promoInput');
  const msg   = document.getElementById('promoMsg');
  const btn   = document.getElementById('promoBtn');
  if (input) { input.value = ''; input.disabled = false; }
  if (btn)   { btn.textContent = 'Apply'; btn.onclick = applyPromo; }
  if (msg)   msg.textContent = '';
  const row = document.getElementById('discountRow');
  if (row) row.style.display = 'none';
  recalcTotals();
}

function showPromoMsg(el, text, success) {
  el.textContent = text;
  el.style.color = success ? '#c9a84c' : '#e07070';
}

function recalcTotals() {
  const sub = Cart.total();
  const selectedShip = document.querySelector('.sm-option.sm-selected');
  const shipCost = selectedShip
    ? parseFloat(selectedShip.querySelector('.smo-price')?.textContent?.replace('$','') || 0)
    : (sub >= 300 ? 0 : 25);
  const ship = (sub >= 300 && shipCost === 25) ? 0 : shipCost;

  let discount = 0;
  const discRow = document.getElementById('discountRow');
  const discAmt = document.getElementById('discountAmt');
  const discLabel = document.getElementById('discountLabel');
  if (appliedCoupon) {
    discount = appliedCoupon.discount || (appliedCoupon.pct ? sub * appliedCoupon.pct / 100 : 0);
    const dLabel = appliedCoupon.type === 'percent' ? `${appliedCoupon.value}% Off` : `$${appliedCoupon.value} Off`;
    if (discRow) discRow.style.display = '';
    if (discAmt) discAmt.textContent = '-$' + discount.toFixed(2);
    if (discLabel) discLabel.textContent = `Discount (${dLabel})`;
  } else {
    if (discRow) discRow.style.display = 'none';
  }

  const discountedSub = sub - discount;
  const tax   = discountedSub * 0.08;
  const grand = discountedSub + tax + ship;

  const subtotalEl = document.getElementById('checkoutSubtotal');
  const taxEl      = document.getElementById('checkoutTax');
  const totalEl    = document.getElementById('checkoutTotal');
  const shipEl     = document.getElementById('checkoutShipping');
  if (subtotalEl) subtotalEl.textContent = '$' + sub.toFixed(2);
  if (taxEl)      taxEl.textContent      = '$' + tax.toFixed(2);
  if (totalEl)    totalEl.textContent    = '$' + grand.toFixed(2);
  if (shipEl)     shipEl.textContent     = ship === 0 ? 'Complimentary' : '$' + ship.toFixed(2);
}

// ─── CHECKOUT VALIDATION ───────────────────────────────────────
function validateCheckout() {
  const errors = [];

  // Helper: mark field invalid or clear
  function check(id, label, testFn) {
    const el = document.getElementById(id);
    if (!el) return;
    const val = el.value.trim();
    const ok = testFn(val);
    el.style.outline = ok ? '' : '2px solid #e07070';
    el.style.borderColor = ok ? '' : '#e07070';
    if (!ok) errors.push(label);
  }

  check('fFirstName', 'First Name',  v => v.length >= 1);
  check('fLastName',  'Last Name',   v => v.length >= 1);
  check('fEmail',     'Email',       v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v));
  check('fStreet',    'Street Address', v => v.length >= 3);
  check('fCity',      'City',        v => v.length >= 2);
  check('fState',     'State/Province', v => v.length >= 1);
  check('fZip',       'Postal Code', v => v.length >= 3);

  // Payment validation (only if card tab active)
  const activeTab = document.querySelector('.payment-tab.active');
  if (activeTab && activeTab.dataset.method === 'card') {
    check('cardName',   'Cardholder Name', v => v.length >= 2);
    check('cardNumber', 'Card Number',
      v => v.replace(/\s/g,'').length === 16 && /^\d+$/.test(v.replace(/\s/g,'')));
    check('cardExpiry', 'Expiry Date',
      v => /^\d{2}\/\d{2}$/.test(v) && (() => {
        const [m, y] = v.split('/').map(Number);
        const now = new Date();
        const fullYear = 2000 + y;
        return m >= 1 && m <= 12 && (fullYear > now.getFullYear() ||
          (fullYear === now.getFullYear() && m >= now.getMonth() + 1));
      })());
    check('cardCvv', 'CVV', v => /^\d{3,4}$/.test(v));
  }

  // Cart must not be empty
  if (Cart.count() === 0) {
    errors.push('Cart is empty');
  }

  return errors;
}

// ─── PLACE ORDER ───────────────────────────────────────────────
function placeOrder(e) {
  e && e.preventDefault();

  const errors = validateCheckout();
  if (errors.length > 0) {
    // Show a summary toast
    const msg = errors.length === 1
      ? `Please fill in: ${errors[0]}`
      : `Please complete: ${errors.slice(0,3).join(', ')}${errors.length > 3 ? '…' : ''}`;
    showToast(msg, 4000);
    // Scroll to first invalid field
    const firstBad = document.querySelector('[style*="e07070"]');
    if (firstBad) firstBad.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  const btn = document.getElementById('placeOrderBtn');
  if (btn) { btn.innerHTML = '<span>Processing…</span>'; btn.disabled = true; }

  // Build order payload
  const cart  = loadCart();
  const sub   = Cart.total();
  const discount = appliedCoupon ? (appliedCoupon.discount || 0) : 0;
  const tax   = (sub - discount) * 0.08;
  const ship  = sub >= 300 ? 0 : 25;
  const total = sub - discount + tax + ship;
  const name  = (document.getElementById('firstName')?.value || '') + ' ' + (document.getElementById('lastName')?.value || '');

  const orderPayload = {
    items:    cart,
    subtotal: sub,
    discount: discount,
    tax:      parseFloat(tax.toFixed(2)),
    shipping: ship,
    total:    parseFloat(total.toFixed(2)),
    coupon:   appliedCoupon ? appliedCoupon.code : null,
    customer: { name: name.trim(), email: document.getElementById('email')?.value || '' },
  };

  fetch('api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ action: 'save_order', ...orderPayload })
  }).catch(() => {}); // non-blocking

  setTimeout(() => { Cart.clear(); window.location.href = 'confirmation.php'; }, 1800);
}

// ─── PARALLAX ─────────────────────────────────────────────────
function initParallax() {
  window.addEventListener('scroll', () => {
    const hero = document.querySelector('.hero-bg');
    if (hero) hero.style.transform = `translateY(${window.scrollY * 0.3}px)`;
  });
}

// ─── INIT ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initJewelCanvas('jewelCanvas');
  initCatalogFilters();
  initSidebarCart();
  initSearch();
  initCheckout();
  initPaymentTabs();
  initCardFormatting();
  initParallax();
});