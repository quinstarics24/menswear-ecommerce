<?php
require_once __DIR__ . '/header.php';
?>

<h1 class="mb-3">Admin Dashboard</h1>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-2">Products</h5>
      <p class="mb-2">Manage products: add, edit or remove items.</p>
      <a href="products.php" class="btn btn-sm btn-dark">Manage Products</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-2">Orders</h5>
      <p class="mb-2">View and update orders.</p>
      <a href="orders.php" class="btn btn-sm btn-dark">View Orders</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card p-3">
      <h5 class="mb-2">Settings</h5>
      <p class="mb-2">Shop settings and preferences.</p>
      <a href="settings.php" class="btn btn-sm btn-dark">Settings</a>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>