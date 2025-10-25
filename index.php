<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch featured products
$featuredProducts = getFeaturedProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Menswear E-commerce</title>

  <!-- Fonts / UI -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

</head>
<body class="bg-light text-dark">

<?php include 'includes/header.php'; ?>

<header class="hero d-flex align-items-center text-white">
  <div class="container">
    <div class="row">
      <div class="col-lg-7" data-aos="fade-right">
        <h1 class="display-4 fw-bold hero-title">Classic Menswear — Timeless Style</h1>
        <p class="lead">Quality fabrics, modern tailoring. Discover your next favorite outfit.</p>
        <p class="d-flex gap-2">
          <a href="products.php" class="btn btn-dark btn-lg shadow">Shop Products</a>
          <a href="#why" class="btn btn-outline-light btn-lg">Why Choose Us</a>
        </p>
      </div>
      <div class="col-lg-5 d-none d-lg-block" data-aos="zoom-in">
        <img src="assets/images/hero-man.jpg" alt="Menswear hero" class="img-fluid rounded shadow-lg">
      </div>
    </div>
  </div>
</header>

<main class="py-5">
  <section class="container mb-5" id="about" data-aos="fade-up">
    <div class="row align-items-center g-4">
      <div class="col-md-6">
        <img src="assets/images/about-showcase.jpg" alt="About Menswear" class="img-fluid rounded shadow-sm">
      </div>
      <div class="col-md-6">
        <h2 class="fw-bold section-title">About Menswear</h2>
        <p class="text-muted">We craft timeless menswear with careful attention to fit, fabric and finish. From everyday essentials to occasion wear, our pieces are designed to look great and last.</p>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="fa-solid fa-check text-warning me-2"></i>High-quality fabrics</li>
          <li class="mb-2"><i class="fa-solid fa-check text-warning me-2"></i>Modern tailoring</li>
          <li class="mb-2"><i class="fa-solid fa-check text-warning me-2"></i>Sustainable sourcing</li>
        </ul>
        <a href="products.php" class="btn btn-outline-dark mt-2">Browse Collection</a>
      </div>
    </div>
  </section>

  <section class="bg-white py-5" id="why" data-aos="fade-up">
    <div class="container">
      <div class="text-center mb-4">
        <h3 class="fw-bold section-title d-inline-block">Why Choose Us</h3>
        <p class="text-muted mb-0 mt-3">Thoughtful design, honest pricing, and an exceptional shopping experience.</p>
      </div>

      <div class="row g-4 mt-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="50">
          <div class="card h-100 border-0 shadow-sm p-3">
            <div class="card-body text-center">
              <i class="fa-solid fa-handshake-simple fa-2x text-warning mb-3"></i>
              <h5 class="fw-bold">Trusted Quality</h5>
              <p class="text-muted small">Carefully inspected garments made to last.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="card h-100 border-0 shadow-sm p-3">
            <div class="card-body text-center">
              <i class="fa-solid fa-truck-fast fa-2x text-warning mb-3"></i>
              <h5 class="fw-bold">Fast Shipping</h5>
              <p class="text-muted small">Reliable delivery and easy returns.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
          <div class="card h-100 border-0 shadow-sm p-3">
            <div class="card-body text-center">
              <i class="fa-solid fa-user-check fa-2x text-warning mb-3"></i>
              <h5 class="fw-bold">Excellent Support</h5>
              <p class="text-muted small">Responsive customer service for every order.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="container py-5" data-aos="fade-up">
    <div class="d-flex align-items-center mb-4">
      <h2 class="me-auto section-title">Featured Products</h2>
      <a href="products.php" class="small text-muted">View all products</a>
    </div>

    <?php if (empty($featuredProducts)): ?>
      <div class="alert alert-info">No featured products available right now.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($featuredProducts as $product): ?>
          <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
            <div class="card product-card h-100 shadow-sm">
              <?php if (!empty($product['image'])): ?>
                <a href="product.php?id=<?php echo $product['id']; ?>">
                  <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </a>
              <?php else: ?>
                <img src="assets/images/placeholder.jpg" class="card-img-top product-img" alt="placeholder">
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <h6 class="mb-1"><?php echo htmlspecialchars($product['name']); ?></h6>
                <p class="small text-muted mb-2"><?php echo htmlspecialchars($product['description']); ?></p>
                <div class="mt-auto d-flex align-items-center">
                  <div class="price me-auto fw-bold"><?php echo formatPrice($product['price']); ?></div>
                  <form method="post" action="cart.php" class="d-flex align-items-center">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm me-2" style="width:72px;">
                    <button type="submit" class="btn btn-dark btn-sm"><i class="fa-solid fa-cart-plus"></i></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 700 });
</script>
</body>
</html>