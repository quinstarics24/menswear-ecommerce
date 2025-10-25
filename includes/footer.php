<footer class="site-footer bg-dark text-light pt-5">
  <div class="container">
    <div class="row gy-4 align-items-start">
      
      <!-- Brand & Social -->
      <div class="col-md-4">
        <a class="d-inline-block mb-3 footer-brand-link text-decoration-none" href="<?php echo function_exists('url') ? url('index.php') : 'index.php'; ?>">
          <span class="footer-brand fw-bold fs-4 text-warning">Menswear</span>
        </a>
        <p class="small text-muted mb-3">Timeless menswear crafted with care — premium fabrics, modern tailoring, and honest prices.</p>
        <div class="d-flex gap-3">
          <a href="#" class="social-icon text-light fs-5"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-icon text-light fs-5"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-icon text-light fs-5"><i class="fab fa-twitter"></i></a>
        </div>
      </div>

      <!-- Shop Links -->
      <div class="col-6 col-md-2">
        <h6 class="text-uppercase text-muted small mb-3">Shop</h6>
        <ul class="list-unstyled footer-links mb-0">
          <li><a class="text-light text-decoration-none footer-link" href="<?php echo function_exists('url') ? url('products.php') : 'products.php'; ?>">All Products</a></li>
          <li><a class="text-light text-decoration-none footer-link" href="<?php echo function_exists('url') ? url('products.php?q=shirts') : 'products.php?q=shirts'; ?>">Shirts</a></li>
          <li><a class="text-light text-decoration-none footer-link" href="<?php echo function_exists('url') ? url('products.php?q=suits') : 'products.php?q=suits'; ?>">Suits</a></li>
          <li><a class="text-light text-decoration-none footer-link" href="<?php echo function_exists('url') ? url('products.php?q=accessories') : 'products.php?q=accessories'; ?>">Accessories</a></li>
        </ul>
      </div>

      <!-- Support Links -->
      <div class="col-6 col-md-2">
        <h6 class="text-uppercase text-muted small mb-3">Support</h6>
        <ul class="list-unstyled footer-links mb-0">
          <li><a class="text-light text-decoration-none footer-link" href="<?php echo function_exists('url') ? url('cart.php') : 'cart.php'; ?>">Cart</a></li>
          <li><a class="text-light text-decoration-none footer-link" href="<?php echo function_exists('url') ? url('checkout.php') : 'checkout.php'; ?>">Checkout</a></li>
          <li><a class="text-light text-decoration-none footer-link" href="<?php echo function_exists('url') ? url('privacy.php') : 'privacy.php'; ?>">Privacy Policy</a></li>
          <li><a class="text-light text-decoration-none footer-link" href="<?php echo function_exists('url') ? url('contact.php') : 'contact.php'; ?>">Contact</a></li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="col-md-4">
        <h6 class="text-uppercase text-muted small mb-3">Join Our Newsletter</h6>
        <form class="d-flex newsletter-form mb-3" action="#" method="post" onsubmit="return false;">
          <input type="email" name="email" class="form-control form-control-sm me-2 rounded-pill" placeholder="Your email" aria-label="Email">
          <button class="btn btn-warning btn-sm text-dark rounded-pill px-3" type="submit">Subscribe</button>
        </form>
        <p class="small text-muted mb-2">Secure payments • Easy returns • Fast shipping</p>
        <img src="assets/images/payments.png" alt="payment methods" class="img-fluid" style="max-height:36px;">
      </div>
      
    </div>

    <hr class="mt-4 border-secondary">

    <!-- Footer Bottom -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3">
      <div class="small text-muted">
        &copy; <?php echo date('Y'); ?> Menswear E-commerce. All rights reserved.
      </div>
      <nav class="footer-nav small mt-2 mt-md-0" aria-label="Footer navigation">
        <a href="<?php echo function_exists('url') ? url('index.php') : 'index.php'; ?>" class="text-light text-decoration-none me-2">Home</a>
        <span class="text-muted mx-1">·</span>
        <a href="<?php echo function_exists('url') ? url('cart.php') : 'cart.php'; ?>" class="text-light text-decoration-none me-2">Cart</a>
        <span class="text-muted mx-1">·</span>
        <a href="<?php echo function_exists('url') ? url('checkout.php') : 'checkout.php'; ?>" class="text-light text-decoration-none me-2">Checkout</a>
        <span class="text-muted mx-1">·</span>
        <a href="<?php echo function_exists('url') ? url('products.php') : 'products.php'; ?>" class="text-light text-decoration-none">Products</a>
      </nav>
    </div>
  </div>

  <!-- Footer Styles -->
  <style>
    .footer-link:hover, .footer-nav a:hover, .social-icon:hover {
      color: #f0a500 !important;
      transition: 0.3s;
    }
    .newsletter-form input:focus {
      outline: none;
      box-shadow: 0 0 6px #f0a500;
      border-color: #f0a500;
    }
    .footer-brand {
      letter-spacing: 1px;
    }
  </style>
</footer>
