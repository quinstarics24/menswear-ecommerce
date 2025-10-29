<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php'
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin_auth.php';
require_admin();
session_start();
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: products.php'); exit; }

try {
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // delete image if stored in assets/uploads
        if (!empty($row['image']) && strpos($row['image'], 'assets/uploads/') === 0) {
            @unlink(__DIR__ . '/../' . $row['image']);
        }
        $del = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $del->execute([':id' => $id]);
    }
} catch (PDOException $e) {
    // ignore for now
}
header('Location: products.php'); exit;