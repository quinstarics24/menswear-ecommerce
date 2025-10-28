<?php

?>
  </main> <!-- /.admin main -->
</div> <!-- /.d-flex -->

<footer class="border-top py-3 mt-4">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="text-muted small">Menswear Admin &mdash; <?php echo date('Y'); ?></div>
    <div>
      <a href="../" class="btn btn-sm btn-outline-secondary me-2">View Store</a>
      <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // simple admin UI helpers
  document.addEventListener('click', function (e) {
    if (e.target.matches('[data-confirm]')) {
      if (!confirm(e.target.getAttribute('data-confirm'))) e.preventDefault();
    }
  });
</script>
</body>
</html>
