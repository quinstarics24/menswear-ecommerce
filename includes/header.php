<?php

if (session_status() === PHP_SESSION_NONE) session_start();
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// Compute project base (useful when site is in a subfolder like /menswear-ecommerce)
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$basePrefix = ($base === '' || $base === '.' || $base === DIRECTORY_SEPARATOR) ? '' : $base;
function url($path) {
    global $basePrefix;
    return $basePrefix ? $basePrefix . '/' . ltrim($path, '/') : ltrim($path, '/');
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?php echo url('index.php'); ?>">Menswear</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="<?php echo url('index.php'); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo url('products.php'); ?>">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo url('cart.php'); ?>">Cart <span class="badge bg-warning text-dark ms-1"><?php echo $cartCount ? intval($cartCount) : ''; ?></span></a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo url('checkout.php'); ?>">Checkout</a></li>
         <li class="nav-item"><a class="nav-link" href="<?php echo url('contact.php'); ?>">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>