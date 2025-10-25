<?php
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menswear</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light text-dark" style="padding-top: 80px;"> <!-- padding-top to prevent content hidden -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Menswear</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>

        <!-- Supermarket-style cart icon only -->
        <li class="nav-item position-relative">
          <a class="nav-link" href="cart.php">
            <i class="fas fa-shopping-cart fa-lg"></i>
            <?php if($cartCount > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                <?php echo $cartCount; ?>
                <span class="visually-hidden">items in cart</span>
              </span>
            <?php endif; ?>
          </a>
        </li>

        <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Optional: Add custom CSS for professional hover -->
<style>
.navbar .nav-item .badge {
  font-size: 0.7rem;
  padding: 0.35em 0.5em;
  transition: transform 0.2s ease;
}
.navbar .nav-item .badge:hover {
  transform: scale(1.2);
}
.navbar .nav-link i {
  transition: transform 0.2s ease, color 0.2s ease;
}
.navbar .nav-link:hover i {
  transform: translateY(-2px) scale(1.1);
  color: #d4af37; /* gold accent on hover */
}
</style>
