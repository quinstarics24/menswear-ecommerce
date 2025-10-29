<?php

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin_auth.php';
require_admin();

$search = trim((string)($_GET['q'] ?? ''));
$where = '';
$params = [];
if ($search !== '') {
  $where = " WHERE name LIKE :q OR category LIKE :q ";
  $params[':q'] = "%{$search}%";
}
try {
  $stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM products {$where} ORDER BY created_at DESC LIMIT 500");
  $stmt->execute($params);
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $products = [];
}
?>

<div class="d-flex align-items-center mb-3">
  <h2 class="me-auto">Products</h2>
  <form class="d-flex" method="get" action="products.php">
    <input name="q" class="form-control form-control-sm me-2" placeholder="Search name or category" value="<?php echo htmlspecialchars($search); ?>">
    <button class="btn btn-outline-secondary btn-sm" type="submit">Search</button>
  </form>
</div>

<p><a class="btn btn-warning btn-sm" href="product_add.php">+ Add product</a></p>

<table class="table table-hover">
  <thead><tr><th>ID</th><th>Thumb</th><th>Name</th><th>Category</th><th>Price</th><th>Created</th><th>Actions</th></tr></thead>
  <tbody>
    <?php foreach ($products as $p): ?>
      <tr>
        <td><?php echo (int)$p['id']; ?></td>
        <td style="width:80px;">
          <?php $img = !empty($p['image']) ? $p['image'] : 'assets/images/placeholder.jpg'; ?>
          <img src="<?php echo htmlspecialchars($img); ?>" alt="" style="height:48px;width:48px;object-fit:cover;border-radius:6px;">
        </td>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo htmlspecialchars($p['category'] ?? ''); ?></td>
        <td><?php echo formatPrice($p['price']); ?></td>
        <td><?php echo htmlspecialchars($p['created_at'] ?? ''); ?></td>
        <td>
          <a class="btn btn-sm btn-outline-secondary" href="product_edit.php?id=<?php echo $p['id']; ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="product_delete.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/footer.php'; ?>