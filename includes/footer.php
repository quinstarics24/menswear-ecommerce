
<footer class="site-footer bg-dark text-light py-5">
  <div class="container">
    <div class="row gy-4 align-items-start">
      <div class="col-md-4">
        <a class="d-inline-block mb-2 footer-brand-link" href="<?php echo function_exists('url') ? url('index.php') : 'index.php'; ?>">
          <span class="footer-brand fw-bold">Menswear</span>
        </a>
        <p class="small text-muted mb-3">Timeless menswear crafted with care — quality fabrics, modern tailoring, and honest prices.</p>

        <div class="d-flex gap-2 align-items-center">
          <a href="#" class="social-icon" aria-label="Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
          <a href="#" class="social-icon" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
          <a href="#" class="social-icon" aria-label="Twitter"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a>
        </div>
      </div>

      <div class="col-6 col-md-2">
        <h6 class="text-uppercase text-muted small mb-3">Shop</h6>
        <ul class="list-unstyled footer-links mb-0">
          <li><a href="<?php echo function_exists('url') ? url('products.php') : 'products.php'; ?>">All Products</a></li>
          <li><a href="<?php echo function_exists('url') ? url('products.php?q=shirts') : 'products.php?q=shirts'; ?>">Shirts</a></li>
          <li><a href="<?php echo function_exists('url') ? url('products.php?q=suits') : 'products.php?q=suits'; ?>">Suits</a></li>
          <li><a href="<?php echo function_exists('url') ? url('products.php?q=accessories') : 'products.php?q=accessories'; ?>">Accessories</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2">
        <h6 class="text-uppercase text-muted small mb-3">Support</h6>
        <ul class="list-unstyled footer-links mb-0">
          <li><a href="<?php echo function_exists('url') ? url('cart.php') : 'cart.php'; ?>">Cart</a></li>
          <li><a href="<?php echo function_exists('url') ? url('checkout.php') : 'checkout.php'; ?>">Checkout</a></li>
          <li><a href="<?php echo function_exists('url') ? url('privacy.php') : 'privacy.php'; ?>">Privacy Policy</a></li>
          <li><a href="<?php echo function_exists('url') ? url('contact.php') : 'contact.php'; ?>">Contact</a></li>
        </ul>
      </div>

      <div class="col-md-4">
        <h6 class="text-uppercase text-muted small mb-3">Join our newsletter</h6>
        <form class="d-flex newsletter-form" action="#" method="post" onsubmit="return false;" role="search" aria-label="Subscribe to newsletter">
          <input type="email" name="email" class="form-control form-control-sm me-2" placeholder="Your email" aria-label="Email">
          <button class="btn btn-warning btn-sm text-dark" type="submit">Subscribe</button>
        </form>
        <div class="mt-3 small text-muted">Secure payments • Easy returns • Fast shipping</div>

        <div class="mt-3">
          <img src="assets/images/payments.png" alt="payment methods" class="img-fluid" style="max-height:36px;">
        </div>
      </div>
    </div>

    <hr class="mt-4 border-secondary">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3">
      <div class="small text-muted footer-copyright">© <?php echo htmlspecialchars(date('Y')); ?> Menswear E-commerce. All rights reserved.</div>
      <nav class="footer-nav small mt-2 mt-md-0" aria-label="Footer navigation">
        <a href="<?php echo function_exists('url') ? url('index.php') : 'index.php'; ?>">Home</a>
        <span class="sep">·</span>
        <a href="<?php echo function_exists('url') ? url('cart.php') : 'cart.php'; ?>">Cart</a>
        <span class="sep">·</span>
        <a href="<?php echo function_exists('url') ? url('checkout.php') : 'checkout.php'; ?>">Checkout</a>
        <span class="sep">·</span>
        <a href="<?php echo function_exists('url') ? url('products.php') : 'products.php'; ?>">Products</a>
      </nav>
    </div>
  </div>
</footer>
