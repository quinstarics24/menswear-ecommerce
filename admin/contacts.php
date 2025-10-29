<?php

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin_auth.php';
require_admin();

$contacts = [];

// Try DB first
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'contacts'");
    if ($stmt->fetchColumn()) {
        $rows = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 1000")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $r) {
            $r['source'] = 'db';
            $contacts[] = $r;
        }
    }
} catch (Exception $e) {
    // ignore DB issues
}

// If no DB contacts, fallback to storage log
$logFile = __DIR__ . '/../storage/contacts.log';
if (empty($contacts) && is_file($logFile)) {
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_reverse($lines); // newest first
    foreach ($lines as $i => $ln) {
        $row = json_decode($ln, true);
        if (!is_array($row)) continue;
        $row['source'] = 'file';
        $row['_line_index'] = $i; // for deletion/viewing
        $contacts[] = $row;
    }
}
?>
<h2 class="mb-3">Contact messages</h2>

<div class="mb-3 d-flex gap-2">
  <a href="contacts_export.php" class="btn btn-sm btn-outline-secondary">Export CSV</a>
  <a href="contact_delete.php?purge=1" class="btn btn-sm btn-danger" onclick="return confirm('Purge all file-based contacts?')">Purge file log</a>
</div>

<?php if (empty($contacts)): ?>
  <div class="alert alert-info">No contact messages found.</div>
<?php else: ?>
  <table class="table table-striped table-hover">
    <thead>
      <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Subject</th><th>Message</th><th>When</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($contacts as $idx => $c): ?>
        <tr>
          <td><?php echo $idx+1; ?></td>
          <td><?php echo htmlspecialchars($c['name'] ?? ''); ?></td>
          <td><?php echo htmlspecialchars($c['email'] ?? ''); ?></td>
          <td><?php echo htmlspecialchars($c['phone'] ?? ''); ?></td>
          <td><?php echo htmlspecialchars($c['subject'] ?? ''); ?></td>
          <td><?php echo nl2br(htmlspecialchars(mb_strimwidth($c['message'] ?? '', 0, 80, '...'))); ?></td>
          <td><?php echo htmlspecialchars($c['created_at'] ?? ''); ?></td>
          <td class="text-end">
            <?php if ($c['source'] === 'db'): ?>
              <a href="contact_view.php?id=<?php echo (int)$c['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
              <a href="contact_delete.php?source=db&id=<?php echo (int)$c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
            <?php else: ?>
              <a href="contact_view.php?source=file&line=<?php echo (int)$c['_line_index']; ?>" class="btn btn-sm btn-outline-primary">View</a>
              <a href="contact_delete.php?source=file&line=<?php echo (int)$c['_line_index']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>