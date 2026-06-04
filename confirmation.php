<?php
require_once 'php/composants.php';
exiger_connexion('connexion.php');
renderHead('Order Confirmed', '<link rel="stylesheet" href="css/checkout.css">');
renderNav('');
?>

<section class="checkout-hero" style="min-height:60vh;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:32px">
  <div class="chk-steps">
    <div class="step step-done">
      <span class="step-num">1</span>
      <span class="step-lbl">Selection</span>
    </div>
    <div class="step-line done"></div>
    <div class="step step-done">
      <span class="step-num">2</span>
      <span class="step-lbl">Checkout</span>
    </div>
    <div class="step-line done"></div>
    <div class="step step-active">
      <span class="step-num">3</span>
      <span class="step-lbl">Confirmation</span>
    </div>
  </div>

  <div style="text-align:center;max-width:560px;padding:0 30px">
    <div style="font-size:3.5rem;color:var(--gold);margin-bottom:24px;animation:float 3s ease-in-out infinite">✦</div>
    <h1 class="chk-title animate-fade-up" style="margin-bottom:20px">Your Order is <em>Confirmed</em></h1>
    <p style="font-size:0.88rem;color:var(--text-mid);line-height:1.9;margin-bottom:12px">
      Thank you for choosing Perla Vita. Your exquisite selection has been received and is being lovingly prepared by our atelier.
    </p>
    <p style="font-size:0.78rem;color:var(--text-light);letter-spacing:0.04em;margin-bottom:40px">
      A confirmation email will arrive shortly with your order details and tracking information.
    </p>
    <a href="catalogue.php" class="btn-primary" style="display:inline-flex"><span>Continue Shopping</span></a>
  </div>
</section>

<?php renderFooter(); ?>
</body>
</html>
