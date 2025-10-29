<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Privacy Policy — Menswear</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light text-dark">

<?php include 'includes/header.php'; ?>

<main class="py-5">
  <div class="container">
    <header class="mb-4">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h1 class="display-6 fw-bold mb-2">Privacy Policy</h1>
          <p class="text-muted mb-0">How we collect, use and protect your information when you shop with Menswear.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <a href="products.php" class="btn btn-outline-dark btn-sm">Browse Products</a>
          <a href="contact.php" class="btn btn-dark btn-sm ms-2">Contact Us</a>
        </div>
      </div>
    </header>

    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <p class="small text-muted mb-0">Effective date: <strong><?php echo date('F j, Y'); ?></strong></p>
      </div>
    </div>

    <section class="mb-4">
      <h4 class="h5 fw-semibold">1. Information we collect</h4>
      <p class="text-muted">We collect the information you provide when creating orders or accounts (name, email, shipping address, phone), payment details processed by our payment providers, and anonymous analytics data (pages visited, device and browser information).</p>
    </section>

    <section class="mb-4">
      <h4 class="h5 fw-semibold">2. How we use your information</h4>
      <ul class="text-muted">
        <li>To process and fulfil orders, send confirmations and updates.</li>
        <li>To respond to customer service requests and provide support.</li>
        <li>To improve our website and product offerings using aggregated analytics.</li>
        <li>To send marketing emails if you opted in (you can unsubscribe at any time).</li>
      </ul>
    </section>

    <section class="mb-4">
      <h4 class="h5 fw-semibold">3. Cookies & tracking</h4>
      <p class="text-muted">We use cookies and similar technologies to make the site work, keep you logged in, remember preferences, and provide analytics. Third‑party services (e.g. Google Analytics, payment providers) may set their own cookies — refer to their policies for details.</p>
    </section>

    <section class="mb-4">
      <h4 class="h5 fw-semibold">4. Data sharing</h4>
      <p class="text-muted">We only share personal data with service providers required to operate the store (payment processors, shipping partners, analytics providers). We do not sell your personal data to third parties.</p>
    </section>

    <section class="mb-4">
      <h4 class="h5 fw-semibold">5. Security</h4>
      <p class="text-muted">We implement reasonable technical and organisational measures to protect your data. However, no method of transmission over the internet is completely secure — if you have concerns, contact us at the address below.</p>
    </section>

    <section class="mb-4">
      <h4 class="h5 fw-semibold">6. Your rights</h4>
      <p class="text-muted">You may request access to, correction or deletion of your personal data. To exercise these rights, contact us at the email or address below. We will respond in accordance with applicable law.</p>
    </section>

    <section class="mb-4">
      <h4 class="h5 fw-semibold">7. Third‑party links</h4>
      <p class="text-muted">Our site may link to third‑party websites. This policy does not apply to those sites — review their privacy practices separately.</p>
    </section>

    <section class="mb-5">
      <h4 class="h5 fw-semibold">8. Contact us</h4>
      <p class="text-muted mb-1">If you have questions about this Privacy Policy or want to exercise your rights, contact:</p>
      <address class="text-muted">
        Menswear E-commerce<br>
        Email: <a href="mailto:privacy@menswear.example" class="text-decoration-none">privacy@menswear.example</a><br>
        Address: 123 Menswear St., City, Country
      </address>
    </section>

    <div class="text-center">
      <a href="index.php" class="btn btn-outline-dark me-2">Back to Home</a>
      <a href="products.php" class="btn btn-dark">Shop Now</a>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>