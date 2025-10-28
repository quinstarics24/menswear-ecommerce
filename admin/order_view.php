<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: orders.php'); exit; }

try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) { header('Location: orders.php'); exit; }
} catch (PDOException $e) {
    $order = null;
}

$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = trim($_POST['status'] ?? $order['status']);
    try {
        $upd = $pdo->prepare("UPDATE orders SET status = :s WHERE id = :id");
        $upd->execute([':s' => $status, ':id' => $id]);
        $success = 'Order status updated.';
        $order['status'] = $status;
    } catch (PDOException $e) {
        $errors[] = 'Unable to update.';
    }
}
?>

<h2>Order #<?php echo $id; ?></h2>

<?php if ($errors): ?><div class="alert alert-danger"><?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

<div class="card mb-3 p-3">
  <div><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></div>
  <div><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></div>
  <div><strong>Total:</strong> <?php echo formatPrice($order['total']); ?></div>
  <div><strong>Created:</strong> <?php echo htmlspecialchars($order['created_at']); ?></div>
  <div class="mt-3">
    <h6>Items</h6>
    <pre class="small bg-light p-2"><?php echo htmlspecialchars($order['items']); ?></pre>
  </div>
</div>

<form method="post" action="order_view.php?id=<?php echo $id; ?>" class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <?php $statuses = ['pending','processing','shipped','completed','cancelled']; ?>
      <?php foreach ($statuses as $s): ?>
        <option value="<?php echo $s; ?>" <?php if ($order['status'] === $s) echo 'selected'; ?>><?php echo ucfirst($s); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12">
    <button class="btn btn-dark">Update Status</button>
    <a href="orders.php" class="btn btn-outline-secondary">Back</a>
  </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>