<?php

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }

$storageDir = __DIR__ . '/../storage';
@mkdir($storageDir, 0755, true);
$cartsFile = $storageDir . '/carts.log';

// actions: delete single cart, clear all, export carts
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'delete_cart' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        if (is_file($cartsFile)) {
            $lines = file($cartsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (isset($lines[$id])) {
                unset($lines[$id]);
                file_put_contents($cartsFile, implode(PHP_EOL, $lines) . (count($lines) ? PHP_EOL : ''), LOCK_EX);
            }
        }
        header('Location: cart.php'); exit;
    }
    if ($action === 'clear_carts') {
        if (is_file($cartsFile)) unlink($cartsFile);
        header('Location: cart.php'); exit;
    }
    if ($action === 'export_carts') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="active_carts_' . date('Ymd_His') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['id','session_id','user','items_count','total','updated_at','raw_items']);
        if (is_file($cartsFile)) {
            $lines = file($cartsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $i => $ln) {
                $row = json_decode($ln, true);
                $itemsCount = isset($row['items']) && is_array($row['items']) ? count($row['items']) : 0;
                $total = $row['total'] ?? '';
                fputcsv($out, [$i, $row['session_id'] ?? ($row['user'] ?? 'guest'), $row['user'] ?? '', $itemsCount, $total, $row['updated_at'] ?? $row['created_at'] ?? '', json_encode($row['items'] ?? [])]);
            }
        }
        fclose($out); exit;
    }
}

// read DB orders (summary)
$dbOrders = [];
try {
    $stmt = $pdo->query("SELECT id, customer_name, email, total, status, created_at FROM orders ORDER BY created_at DESC LIMIT 150");
    $dbOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $dbError = $e->getMessage();
}

// read storage orders (fallback) for reference
$fileOrders = [];
$ordersLog = $storageDir . '/orders.log';
if (is_file($ordersLog)) {
    $lines = file($ordersLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $ln) {
        $item = json_decode($ln, true);
        if (is_array($item)) $fileOrders[] = $item;
    }
}

// read active carts
$carts = [];
if (is_file($cartsFile)) {
    $lines = file($cartsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $ln) {
        $c = json_decode($ln, true);
        if (is_array($c)) $carts[] = $c;
    }
}

// view cart details if requested
$viewCartIndex = isset($_GET['view_cart']) ? (int)$_GET['view_cart'] : null;
$viewCart = null;
if ($viewCartIndex !== null && isset($carts[$viewCartIndex])) {
    $viewCart = $carts[$viewCartIndex];
}

// helpers
function human_time($ts) {
    if (!$ts) return '';
    $t = strtotime($ts);
    if ($t === false) return $ts;
    return date('Y-m-d H:i:s', $t);
}
?>

<div class="d-flex align-items-center mb-3">
  <h2 class="me-auto">Carts & Orders</h2>

  <div class="btn-group">
    <a class="btn btn-outline-secondary btn-sm" href="cart.php?action=export_carts"><i class="fa-solid fa-file-csv me-1"></i>Export Carts</a>
    <a class="btn btn-outline-danger btn-sm" href="cart.php?action=clear_carts" onclick="return confirm('Clear all active carts?')"><i class="fa-solid fa-trash-can me-1"></i>Clear All</a>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title mb-3">Orders (database)</h5>

        <?php if (!empty($dbError)): ?>
          <div class="alert alert-warning">DB error: <?php echo htmlspecialchars($dbError); ?></div>
        <?php endif; ?>

        <?php if (empty($dbOrders)): ?>
          <div class="alert alert-info">No orders found in the database.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
              <thead><tr><th>#</th><th>Customer</th><th>Email</th><th>Total</th><th>Status</th><th>Created</th></tr></thead>
              <tbody>
              <?php foreach ($dbOrders as $o): ?>
                <tr>
                  <td><?php echo (int)$o['id']; ?></td>
                  <td><?php echo htmlspecialchars($o['customer_name'] ?? '—'); ?></td>
                  <td><?php echo htmlspecialchars($o['email'] ?? ''); ?></td>
                  <td><?php echo htmlspecialchars(formatPrice($o['total'] ?? $o['amount'] ?? 0)); ?></td>
                  <td><span class="badge bg-<?php echo ($o['status'] === 'processing' ? 'warning' : ($o['status'] === 'completed' ? 'success' : 'secondary')); ?>"><?php echo htmlspecialchars($o['status'] ?? 'pending'); ?></span></td>
                  <td><?php echo htmlspecialchars($o['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-3">Orders (storage file)</h5>
        <?php if (empty($fileOrders)): ?>
          <div class="alert alert-secondary">No orders saved in storage/orders.log.</div>
        <?php else: ?>
          <table class="table table-sm">
            <thead><tr><th>#</th><th>Customer / Email</th><th>Total</th><th>Created</th></tr></thead>
            <tbody>
            <?php foreach ($fileOrders as $i => $fo): ?>
              <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php echo htmlspecialchars(($fo['customer_name'] ?? $fo['name'] ?? '—') . ' / ' . ($fo['email'] ?? '')); ?></td>
                <td><?php echo htmlspecialchars(number_format((float)($fo['total'] ?? 0),2)); ?></td>
                <td><?php echo htmlspecialchars($fo['created_at'] ?? $fo['created'] ?? '—'); ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card mb-3">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h5 class="card-title mb-0">Active carts</h5>
          <div class="small text-muted"><?php echo count($carts); ?> active cart<?php echo count($carts) !== 1 ? 's' : ''; ?></div>
        </div>
        <div>
          <a class="btn btn-sm btn-outline-primary me-1" href="cart.php?action=export_carts"><i class="fa-solid fa-file-export"></i> Export</a>
          <a class="btn btn-sm btn-danger" href="cart.php?action=clear_carts" onclick="return confirm('Clear all active carts?')"><i class="fa-solid fa-trash"></i></a>
        </div>
      </div>

      <div class="list-group list-group-flush">
        <?php if (empty($carts)): ?>
          <div class="p-3 text-center text-muted">No active carts found in storage/carts.log.</div>
        <?php else: ?>
          <?php foreach ($carts as $i => $c): ?>
            <div class="list-group-item d-flex align-items-start gap-3">
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                  <div>
                    <strong><?php echo htmlspecialchars($c['user'] ?? ($c['session_id'] ?? 'guest')); ?></strong>
                    <div class="small text-muted"><?php echo intval(is_array($c['items']) ? count($c['items']) : 0); ?> item(s) • <?php echo htmlspecialchars($c['updated_at'] ?? $c['created_at'] ?? ''); ?></div>
                  </div>
                  <div class="text-end">
                    <div class="fw-semibold"><?php echo htmlspecialchars(isset($c['total']) ? formatPrice($c['total']) : ''); ?></div>
                    <div class="small text-muted">Session: <?php echo htmlspecialchars($c['session_id'] ?? ''); ?></div>
                  </div>
                </div>
                <div class="mt-2 small text-muted truncate" style="max-height:3.6rem;overflow:hidden;">
                  <?php
                    if (!empty($c['items']) && is_array($c['items'])) {
                      $preview = [];
                      foreach ($c['items'] as $it) $preview[] = ($it['name'] ?? $it['title'] ?? 'item') . ' ×' . ($it['qty'] ?? 1);
                      echo htmlspecialchars(implode(' — ', $preview));
                    }
                  ?>
                </div>
              </div>
              <div class="btn-group-vertical">
                <a class="btn btn-sm btn-outline-primary" href="cart.php?view_cart=<?php echo $i; ?>">View</a>
                <a class="btn btn-sm btn-danger" href="cart.php?action=delete_cart&id=<?php echo $i; ?>" onclick="return confirm('Delete this cart?')">Delete</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($viewCart): ?>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Cart details</h5>
          <div class="mb-2"><strong>User / Session:</strong> <?php echo htmlspecialchars($viewCart['user'] ?? $viewCart['session_id'] ?? 'guest'); ?></div>
          <div class="mb-2"><strong>Updated:</strong> <?php echo htmlspecialchars($viewCart['updated_at'] ?? $viewCart['created_at'] ?? ''); ?></div>
          <div class="mb-2"><strong>Total:</strong> <?php echo isset($viewCart['total']) ? formatPrice($viewCart['total']) : ''; ?></div>

          <h6 class="mt-3">Items</h6>
          <ul class="list-group mb-3">
            <?php foreach (($viewCart['items'] ?? []) as $it): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-semibold"><?php echo htmlspecialchars($it['name'] ?? $it['title'] ?? 'Item'); ?></div>
                  <div class="small text-muted"><?php echo htmlspecialchars($it['sku'] ?? ''); ?></div>
                </div>
                <div class="text-end">
                  <div><?php echo intval($it['qty'] ?? 1); ?> × <?php echo formatPrice($it['price'] ?? ($it['unit_price'] ?? 0)); ?></div>
                  <div class="fw-semibold"><?php echo formatPrice((($it['price'] ?? ($it['unit_price'] ?? 0)) * ($it['qty'] ?? 1))); ?></div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>

          <a class="btn btn-outline-secondary" href="cart.php">Back</a>
          <a class="btn btn-danger" href="cart.php?action=delete_cart&id=<?php echo $viewCartIndex; ?>" onclick="return confirm('Delete this cart?')">Delete cart</a>
        </div>
      </div>
    <?php endif; ?>

  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>