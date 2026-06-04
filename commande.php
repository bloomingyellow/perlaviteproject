<?php
// Coupon validation AJAX
if (isset($_POST['validate_coupon'])) {
  $result = valider_coupon($_POST['code'] ?? '', (float)($_POST['total'] ?? 0));
  header('Content-Type: application/json');
  echo json_encode($result);
  exit;
}

require_once 'php/composants.php';
require_once 'php/produits.php';
exiger_connexion('connexion.php?redirect=commande.php');
renderHead('Checkout', '<link rel="stylesheet" href="css/checkout.css">');
renderNav('');
?>

<!-- ─── CHECKOUT HERO ─────────────────────────────────────────── -->
<section class="checkout-hero">
  <div class="chk-steps">
    <div class="step step-done">
      <span class="step-num">1</span>
      <span class="step-lbl">Selection</span>
    </div>
    <div class="step-line done"></div>
    <div class="step step-active">
      <span class="step-num">2</span>
      <span class="step-lbl">Checkout</span>
    </div>
    <div class="step-line"></div>
    <div class="step">
      <span class="step-num">3</span>
      <span class="step-lbl">Confirmation</span>
    </div>
  </div>
  <h1 class="chk-title animate-fade-up">Complete Your <em>Order</em></h1>
</section>

<!-- ─── CHECKOUT BODY ─────────────────────────────────────────── -->
<div class="checkout-body">

  <!-- LEFT COLUMN: FORMS -->
  <div class="checkout-forms">

    <!-- CONTACT -->
    <div class="form-section animate-fade-up">
      <div class="fs-header">
        <span class="fs-num">01</span>
        <h2 class="fs-title">Contact Information</h2>
      </div>
      <div class="form-grid two-col">
        <div class="form-field">
          <label>First Name</label>
          <input type="text" id="fFirstName" placeholder="Élise" autocomplete="given-name">
        </div>
        <div class="form-field">
          <label>Last Name</label>
          <input type="text" id="fLastName" placeholder="Fontaine" autocomplete="family-name">
        </div>
        <div class="form-field full">
          <label>Email Address</label>
          <input type="email" id="fEmail" placeholder="elise@example.com" autocomplete="email">
        </div>
        <div class="form-field full">
          <label>Phone (optional)</label>
          <input type="tel" placeholder="+1 (555) 000-0000" autocomplete="tel">
        </div>
      </div>
    </div>

    <!-- SHIPPING -->
    <div class="form-section animate-fade-up delay-1">
      <div class="fs-header">
        <span class="fs-num">02</span>
        <h2 class="fs-title">Shipping Address</h2>
      </div>
      <div class="form-grid two-col">
        <div class="form-field full">
          <label>Street Address</label>
          <input type="text" id="fStreet" placeholder="14 Rue de la Paix" autocomplete="street-address">
        </div>
        <div class="form-field full">
          <label>Apartment / Suite (optional)</label>
          <input type="text" placeholder="Suite 400">
        </div>
        <div class="form-field">
          <label>City</label>
          <input type="text" id="fCity" placeholder="Paris" autocomplete="address-level2">
        </div>
        <div class="form-field">
          <label>State / Province</label>
          <input type="text" id="fState" placeholder="Île-de-France" autocomplete="address-level1">
        </div>
        <div class="form-field">
          <label>Postal Code</label>
          <input type="text" id="fZip" placeholder="75001" autocomplete="postal-code">
        </div>
        <div class="form-field">
          <label>Country</label>
          <select id="fCountry" autocomplete="country">
            <option>France</option>
            <option>United States</option>
            <option>United Kingdom</option>
            <option>Italy</option>
            <option>Germany</option>
            <option>Spain</option>
            <option>Switzerland</option>
            <option>UAE</option>
            <option>Japan</option>
            <option>Australia</option>
            <option>Canada</option>
            <option>Other</option>
          </select>
        </div>
      </div>

      <!-- Shipping Method -->
      <div class="shipping-methods">
        <div class="sm-option sm-selected">
          <div class="smo-radio"></div>
          <div class="smo-info">
            <span class="smo-name">Standard Courier</span>
            <span class="smo-time">5–8 business days</span>
          </div>
          <span class="smo-price">$25.00</span>
        </div>
        <div class="sm-option">
          <div class="smo-radio"></div>
          <div class="smo-info">
            <span class="smo-name">Express Delivery</span>
            <span class="smo-time">2–3 business days</span>
          </div>
          <span class="smo-price">$55.00</span>
        </div>
        <div class="sm-option">
          <div class="smo-radio"></div>
          <div class="smo-info">
            <span class="smo-name">White Glove Service</span>
            <span class="smo-time">Next business day</span>
          </div>
          <span class="smo-price">$120.00</span>
        </div>
      </div>
    </div>

    <!-- PAYMENT -->
    <div class="form-section animate-fade-up delay-2">
      <div class="fs-header">
        <span class="fs-num">03</span>
        <h2 class="fs-title">Payment Method</h2>
      </div>

      <!-- Payment Tabs -->
      <div class="payment-tabs">
        <button class="payment-tab active" data-method="card">
          <span>💳</span> Credit Card
        </button>
        <button class="payment-tab" data-method="paypal">
          <span>🅿</span> PayPal
        </button>
        <button class="payment-tab" data-method="transfer">
          <span>🏦</span> Bank Transfer
        </button>
        <button class="payment-tab" data-method="apple">
          <span>🍎</span> Apple Pay
        </button>
      </div>

      <!-- Card Panel -->
      <div class="payment-panel active" id="panel-card">
        <div class="card-visual">
          <div class="cv-chip">◈</div>
          <div class="cv-number">•••• •••• •••• ••••</div>
          <div class="cv-bottom">
            <span class="cv-holder">Cardholder Name</span>
            <span class="cv-exp">MM/YY</span>
          </div>
          <div class="cv-brand">VISA</div>
        </div>
        <div class="form-grid two-col">
          <div class="form-field full">
            <label>Cardholder Name</label>
            <input type="text" id="cardName" placeholder="Élise Fontaine" autocomplete="cc-name">
          </div>
          <div class="form-field full">
            <label>Card Number</label>
            <input type="text" id="cardNumber" placeholder="0000 0000 0000 0000" maxlength="19" autocomplete="cc-number">
          </div>
          <div class="form-field">
            <label>Expiry Date</label>
            <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5" autocomplete="cc-exp">
          </div>
          <div class="form-field">
            <label>CVV / CVC</label>
            <input type="text" id="cardCvv" placeholder="•••" maxlength="4" autocomplete="cc-csc">
          </div>
        </div>
      </div>

      <!-- PayPal Panel -->
      <div class="payment-panel" id="panel-paypal">
        <div class="alt-payment-box">
          <div class="apb-icon">🅿</div>
          <p>You will be redirected to PayPal to complete your purchase securely.</p>
          <p class="apb-note">Your order details will be pre-filled.</p>
        </div>
      </div>

      <!-- Bank Transfer Panel -->
      <div class="payment-panel" id="panel-transfer">
        <div class="alt-payment-box">
          <div class="apb-icon">🏦</div>
          <div class="bank-details">
            <div class="bd-row"><span>Bank:</span><span>Banque de France</span></div>
            <div class="bd-row"><span>IBAN:</span><span>FR76 1234 5678 9012 3456 7890 156</span></div>
            <div class="bd-row"><span>BIC/SWIFT:</span><span>BNPAFRPP</span></div>
            <div class="bd-row"><span>Reference:</span><span>PERLAV-ORDER</span></div>
          </div>
          <p class="apb-note">Please include your order reference. Orders ship after payment clears (1–3 days).</p>
        </div>
      </div>

      <!-- Apple Pay Panel -->
      <div class="payment-panel" id="panel-apple">
        <div class="alt-payment-box">
          <div class="apb-icon">🍎</div>
          <p>Authenticate with Face ID or Touch ID to complete your purchase.</p>
          <button class="apple-pay-btn">Pay with Apple Pay</button>
        </div>
      </div>

      <!-- Security Note -->
      <div class="security-note">
        <span>🔒</span>
        <span>All transactions are encrypted with 256-bit SSL. We never store your card details.</span>
      </div>
    </div>

    <!-- GIFT & SPECIAL -->
    <div class="form-section animate-fade-up delay-3">
      <div class="fs-header">
        <span class="fs-num">04</span>
        <h2 class="fs-title">Gift & Extras</h2>
      </div>
      <div class="gift-options">
        <label class="gift-toggle">
          <input type="checkbox" id="giftWrap">
          <span class="gt-box"></span>
          <div>
            <span class="gt-label">Premium Gift Wrapping</span>
            <span class="gt-sub">Signature Perla Vita box with satin ribbon — $12.00</span>
          </div>
        </label>
        <label class="gift-toggle">
          <input type="checkbox" id="giftMsg">
          <span class="gt-box"></span>
          <div>
            <span class="gt-label">Add a Personal Message</span>
            <span class="gt-sub">Handwritten on our ivory card stock</span>
          </div>
        </label>
      </div>
      <div class="message-field" id="messageField" style="display:none">
        <label>Your Message</label>
        <textarea placeholder="Write your heartfelt message here…" rows="4" maxlength="200"></textarea>
        <span class="char-count">0 / 200</span>
      </div>
    </div>

  </div>

  <!-- RIGHT COLUMN: ORDER SUMMARY -->
  <div class="order-summary">
    <div class="os-sticky">
      <div class="os-header">
        <h2 class="os-title">Order Summary</h2>
        <a href="catalogue.php" class="os-edit">Edit selection</a>
      </div>

      <!-- Items -->
      <div class="os-items" id="checkoutItems">
        <!-- Filled by JS -->
        <div style="text-align:center;padding:40px 20px;color:var(--text-light)">
          <p style="font-size:0.8rem">Loading your selection…</p>
        </div>
      </div>

      <!-- Totals -->
      <div class="os-totals">
        <div class="ot-row">
          <span>Subtotal</span>
          <span id="checkoutSubtotal">—</span>
        </div>
        <div class="ot-row">
          <span>Shipping</span>
          <span id="checkoutShipping">—</span>
        </div>
        <div class="ot-row">
          <span>Tax (8%)</span>
          <span id="checkoutTax">—</span>
        </div>
        <div class="ot-row ot-total">
          <span>Total</span>
          <span id="checkoutTotal">—</span>
        </div>
      </div>

      <!-- Discount Row (shown after coupon applied) -->
      <div class="ot-row" id="discountRow" style="display:none;color:#c9a84c">
        <span id="discountLabel">Discount</span>
        <span id="discountAmt">—</span>
      </div>

      <!-- Promo Code -->
      <div class="promo-wrap">
        <input type="text" class="promo-input" id="promoInput" placeholder="Promo code">
        <button class="promo-btn" id="promoBtn" onclick="applyPromo()">Apply</button>
      </div>
      <div id="promoMsg" style="font-size:0.72rem;margin-top:6px;padding:0 4px;min-height:18px"></div>

      <!-- Place Order -->
      <button class="btn-primary" style="width:100%;justify-content:center;font-size:0.78rem;padding:18px"
        id="placeOrderBtn" onclick="placeOrder(event)">
        <span>Place Order</span>
      </button>

      <div class="os-trust">
        <span>✦</span>
        <span>Free returns within 30 days · Lifetime warranty</span>
      </div>
    </div>
  </div>

</div>

<?php renderFooter(); ?>
<script>
// Gift message toggle
document.getElementById('giftMsg')?.addEventListener('change', function() {
  document.getElementById('messageField').style.display = this.checked ? 'block' : 'none';
});

// Message char counter
document.querySelector('textarea')?.addEventListener('input', function() {
  const cc = document.querySelector('.char-count');
  if (cc) cc.textContent = this.value.length + ' / 200';
});

// Shipping method selector
document.querySelectorAll('.sm-option').forEach(opt => {
  opt.addEventListener('click', () => {
    document.querySelectorAll('.sm-option').forEach(o => o.classList.remove('sm-selected'));
    opt.classList.add('sm-selected');
    recalcTotals();
  });
});

// Card visual update
document.getElementById('cardName')?.addEventListener('input', function() {
  document.querySelector('.cv-holder').textContent = this.value || 'Cardholder Name';
});
document.getElementById('cardNumber')?.addEventListener('input', function() {
  const v = this.value || '•••• •••• •••• ••••';
  document.querySelector('.cv-number').textContent = v.length > 0 ? v : '•••• •••• •••• ••••';
});
document.getElementById('cardExpiry')?.addEventListener('input', function() {
  document.querySelector('.cv-exp').textContent = this.value || 'MM/YY';
});
</script>
</body>
</html>