<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/db.php';

$source = $_GET['source'] ?? 'db';

// Purge file log
if (isset($_GET['purge']) && $_GET['purge'] == '1') {
    $logFile = __DIR__ . '/../storage/contacts.log';
    if (is_file($logFile)) unlink($logFile);
    header('Location: contacts.php'); exit;
}

if ($source === 'db' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: contacts.php'); exit;
}

if ($source === 'file' && isset($_GET['line'])) {
    $lineIndex = (int)$_GET['line'];
    $logFile = __DIR__ . '/../storage/contacts.log';
    if (is_file($logFile)) {
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // current contacts.php reversed lines for view; map index back
        $lines = array_values($lines);
        $total = count($lines);
        // original file order vs reversed: reversed index 0 => last line
        $target = $total - 1 - $lineIndex;
        if (isset($lines[$target])) {
            unset($lines[$target]);
            // write back preserving order
            file_put_contents($logFile, implode(PHP_EOL, $lines) . (count($lines) ? PHP_EOL : ''));
        }
    }
    header('Location: contacts.php'); exit;
}

header('Location: contacts.php'); exit;