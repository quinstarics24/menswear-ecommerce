<footer class="site-footer mt-5 py-5">
  <div class="container">
    <div class="row gy-4">
      <div class="col-md-4">
        <a href="index.php" class="footer-brand-link">
          <h5 class="footer-brand">MENSWEAR</h5>
        </a>
        <p>Elevating men’s fashion with timeless, premium, and comfortable wear for every occasion.</p>
      </div>

      <div class="col-md-2">
        <h6 class="text-white mb-3">Quick Links</h6>
        <ul class="footer-links list-unstyled">
          <li><a href="index.php">Home</a></li>
          <li><a href="products.php">Products</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>

      <div class="col-md-3">
        <h6 class="text-white mb-3">Stay Connected</h6>
        <div class="d-flex gap-3 mb-3">
          <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
        </div>
        <form class="newsletter-form d-flex">
          <input type="email" class="form-control me-2" placeholder="Your email">
          <button class="btn btn-outline-light" type="submit">Subscribe</button>
        </form>
      </div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between flex-wrap align-items-center">
      <p class="footer-copyright mb-0">
        &copy; <?php echo date("Y"); ?> Menswear. All rights reserved.
      </p>
      <div class="footer-nav">
        <a href="privacy.php">Privacy</a>
        <span class="sep">|</span>
        <a href="#">Terms</a>
        <span class="sep">|</span>
        <a href="#">Support</a>
      </div>
    </div>
  </div>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 700 });
</script>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
