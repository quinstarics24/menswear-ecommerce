<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// show orders table if exists
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'orders'");
    $exists = $stmt->fetchColumn();
    if (!$exists) {
        // create a simple orders table for admin usage
        $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(191),
            email VARCHAR(191),
            total DECIMAL(10,2),
            status VARCHAR(50) DEFAULT 'pending',
            items TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }
    $orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 200")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $orders = [];
}
?>

<h2>Orders</h2>

<?php if (empty($orders)): ?>
  <div class="alert alert-info">No orders found.</div>
<?php else: ?>
  <table class="table table-striped">
    <thead><tr><th>#</th><th>Customer</th><th>Email</th><th>Total</th><th>Status</th><th>Created</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?php echo (int)$o['id']; ?></td>
          <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
          <td><?php echo htmlspecialchars($o['email']); ?></td>
          <td><?php echo formatPrice($o['total']); ?></td>
          <td><?php echo htmlspecialchars($o['status']); ?></td>
          <td><?php echo htmlspecialchars($o['created_at']); ?></td>
          <td><a class="btn btn-sm btn-outline-secondary" href="order_view.php?id=<?php echo $o['id']; ?>">View</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>