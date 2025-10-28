<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$cart = $_SESSION['cart'] ?? [];

// Build cart items details
$cartItems = [];
$subtotal = 0.0;
if (!empty($cart) && is_array($cart)) {
    $products = getProductsByIds(array_keys($cart));
    foreach ($cart as $pid => $qty) {
        if (!isset($products[$pid])) continue;
        $p = $products[$pid];
        $line = ((float)$p['price']) * ((int)$qty);
        $subtotal += $line;
        $cartItems[] = [
            'id' => $p['id'],
            'name' => $p['name'],
            'image' => $p['image'],
            'price' => (float)$p['price'],
            'qty' => (int)$qty,
            'line' => $line,
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize inputs
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $address = trim($_POST['address'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');

    if ($name === '' || $email === '' || $address === '' || $payment_method === '') {
        $error = "All fields are required.";
    } elseif (empty($cartItems)) {
        $error = "Your cart is empty.";
    } else {
        // Simulate order processing
        $order_id = random_int(100000, 999999);
        // In real app: save order to DB and process payment here

        // Clear cart
        unset($_SESSION['cart']);

        $success = "Order #{$order_id} placed successfully. You'll receive an email confirmation at {$email}.";
        // recompute view variables
        $cartItems = [];
        $subtotal = 0.0;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Checkout — Menswear</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600,700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    .checkout-card { border-radius:12px; box-shadow:0 10px 30px rgba(11,18,32,0.06); }
    .small-muted { color:#6b7280; font-size:.95rem; }
  </style>
</head>
<body class="bg-light text-dark">
<?php include 'includes/header.php'; ?>

<main class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card checkout-card p-4">
          <h3 class="mb-3">Billing & Shipping</h3>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <div class="mb-3">
              <a href="index.php" class="btn btn-outline-dark">Continue Shopping</a>
            </div>
          <?php endif; ?>

          <form method="post" action="checkout.php" class="row g-3 needs-validation" novalidate>
            <div class="col-12">
              <label class="form-label">Full name</label>
              <input name="name" type="text" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
              <div class="invalid-feedback">Please enter your name.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
              <div class="invalid-feedback">Please enter a valid email.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Phone (optional)</label>
              <input name="phone" type="tel" class="form-control" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
            </div>

            <div class="col-12">
              <label class="form-label">Shipping address</label>
              <textarea name="address" class="form-control" rows="4" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
              <div class="invalid-feedback">Please enter your shipping address.</div>
            </div>

            <div class="col-12">
              <label class="form-label">Payment method</label>
              <select name="payment_method" class="form-select" required>
                <option value="">Choose...</option>
                <option value="credit_card" <?php if (($_POST['payment_method'] ?? '') === 'credit_card') echo 'selected'; ?>>Credit / Debit Card</option>
                <option value="mobile_money" <?php if (($_POST['payment_method'] ?? '') === 'mobile_money') echo 'selected'; ?>>Mobile Money</option>
                <option value="cash_on_delivery" <?php if (($_POST['payment_method'] ?? '') === 'cash_on_delivery') echo 'selected'; ?>>Cash on Delivery</option>
              </select>
              <div class="invalid-feedback">Please select a payment method.</div>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
              <button type="submit" class="btn btn-dark" <?php if (!empty($success)) echo 'disabled'; ?>>
                <i class="fa-solid fa-check me-2"></i>Place Order
              </button>
              <a href="cart.php" class="btn btn-outline-secondary">Review Cart</a>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card checkout-card p-3">
          <h5 class="mb-3">Order Summary</h5>

          <?php if (empty($cartItems)): ?>
            <div class="text-center text-muted py-4">
              <i class="fa-solid fa-box-open fa-2x mb-2"></i>
              <div class="small-muted">Your cart is empty.</div>
            </div>
          <?php else: ?>
            <ul class="list-group mb-3">
              <?php foreach ($cartItems as $it): ?>
                <li class="list-group-item d-flex align-items-center">
                  <?php if (!empty($it['image'])): ?>
                    <img src="<?php echo htmlspecialchars($it['image']); ?>" alt="" style="height:56px;width:56px;object-fit:cover;border-radius:8px;margin-right:12px;">
                  <?php endif; ?>
                  <div class="flex-fill">
                    <div class="fw-semibold"><?php echo htmlspecialchars($it['name']); ?></div>
                    <div class="small-muted"><?php echo intval($it['qty']); ?> × <?php echo formatPrice($it['price']); ?></div>
                  </div>
                  <div class="fw-bold"><?php echo formatPrice($it['line']); ?></div>
                </li>
              <?php endforeach; ?>
            </ul>

            <div class="d-flex justify-content-between small-muted mb-2">
              <div>Subtotal</div>
              <div><?php echo formatPrice($subtotal); ?></div>
            </div>

            <?php
              // simple shipping rule example
              $shipping = $subtotal > 0 ? 500.00 : 0.00; 
              $tax = 0; 
              $total = $subtotal + $shipping + $tax;
            ?>

            <div class="d-flex justify-content-between small-muted mb-2">
              <div>Shipping</div>
              <div><?php echo formatPrice($shipping); ?></div>
            </div>

            <div class="d-flex justify-content-between fw-bold fs-5">
              <div>Total</div>
              <div><?php echo formatPrice($total); ?></div>
            </div>
          <?php endif; ?>
        </div>

        <div class="mt-3 text-center small text-muted">
          Secure payments • Easy returns • Fast shipping
        </div>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 700 });
</script>

<script>
  // Bootstrap form validation
  (function () {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>
</body>
</html>
