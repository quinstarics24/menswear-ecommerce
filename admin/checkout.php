<?php

session_start();

+ require_once __DIR__ . '/../includes/db.php';
+ require_once __DIR__ . '/../includes/functions.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { header('Location: cart.php'); exit; }

$customer_name = trim($_POST['name'] ?? 'Guest');
$email = trim($_POST['email'] ?? '');
$total = 0.0;
$items = [];

foreach ($cart as $productId => $qty) {
    $p = getProductById((int)$productId);
    if (!$p) continue;
    $items[] = ['id'=>$p['id'],'name'=>$p['name'],'price'=>$p['price'],'qty'=>$qty];
    $total += ($p['price'] * $qty);
}

$now = date('Y-m-d H:i:s');
$saved = false;

try {
    $pdo->beginTransaction();
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(191),
        email VARCHAR(191),
        total DECIMAL(10,2),
        status VARCHAR(50) DEFAULT 'pending',
        items TEXT,
        created_at DATETIME
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name,email,total,status,items,created_at) VALUES (:name,:email,:total,:status,:items,:created)");
    $stmt->execute([
        ':name' => $customer_name,
        ':email' => $email,
        ':total' => number_format($total,2,'.',''),
        ':status' => 'pending',
        ':items' => json_encode($items, JSON_UNESCAPED_UNICODE),
        ':created' => $now
    ]);
    $pdo->commit();
    $saved = true;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    @file_put_contents(__DIR__ . '/storage/orders_error.log', date('c') . " - ORDER DB ERROR: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    $saved = false;
}

if (!$saved) {
    $logDir = __DIR__ . '/storage';
    if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
    $data = [
        'customer_name' => $customer_name,
        'email' => $email,
        'total' => number_format($total,2,'.',''),
        'items' => $items,
        'created_at' => $now
    ];
    file_put_contents($logDir . '/orders.log', json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
    $saved = true;
}

if ($saved) {
    unset($_SESSION['cart']);
    header('Location: thankyou.php'); exit;
} else {
    echo "Unable to save order. Try again later.";
    exit;
}