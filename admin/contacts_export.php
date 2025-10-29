<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin_auth.php';
require_admin();

$rows = [];

// DB rows
try {
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
    $dbRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($dbRows as $r) $rows[] = $r;
} catch (Exception $e) {
    // ignore
}

// File rows
$logFile = __DIR__ . '/../storage/contacts.log';
if (is_file($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $ln) {
        $r = json_decode($ln, true);
        if (is_array($r)) $rows[] = $r;
    }
}

$filename = 'contacts_export_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$out = fopen('php://output', 'w');
fputcsv($out, ['name','email','phone','subject','message','created_at']);
foreach ($rows as $r) {
    fputcsv($out, [
        $r['name'] ?? '',
        $r['email'] ?? '',
        $r['phone'] ?? '',
        $r['subject'] ?? '',
        $r['message'] ?? '',
        $r['created_at'] ?? ''
    ]);
}
fclose($out);
exit;