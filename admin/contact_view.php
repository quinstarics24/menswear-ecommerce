<?php

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin_auth.php';
require_admin();
// load PHPMailer if installed via composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: contacts.php'); exit; }

try {
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $c = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$c) { header('Location: contacts.php'); exit; }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">DB error.</div>';
    require_once __DIR__ . '/footer.php';
    exit;
}

$replyError = '';
$replySuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $replyTo = $c['email'];
    $subject = trim($_POST['reply_subject'] ?? ('Re: ' . ($c['subject'] ?? '')));
    $body = trim($_POST['reply_message'] ?? '');

    if ($body === '') {
        $replyError = 'Message cannot be empty.';
    } else {
        // Prefer PHPMailer SMTP
        if (class_exists('\PHPMailer\PHPMailer\PHPMailer')) {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = SMTP_HOST ?? 'localhost';
                $mail->SMTPAuth = !empty(SMTP_USER);
                if (!empty(SMTP_USER)) {
                    $mail->Username = SMTP_USER;
                    $mail->Password = SMTP_PASS;
                }
                $mail->SMTPSecure = SMTP_SECURE ?? '';
                $mail->Port = SMTP_PORT ?? 25;

                $fromAddr = SMTP_FROM ?? 'no-reply@localhost';
                $fromName = SMTP_FROM_NAME ?? 'Menswear Support';

                $mail->setFrom($fromAddr, $fromName);
                $mail->addAddress($replyTo);
                $mail->addReplyTo($fromAddr, $fromName);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->isHTML(false);

                $mail->send();
                $replySuccess = 'Reply sent via SMTP.';
            } catch (Exception $ex) {
                $replyError = 'SMTP send failed: ' . $mail->ErrorInfo;
            }
        } else {
            // fallback to mail()
            $headers = "From: Menswear Support <" . (SMTP_FROM ?? 'no-reply@localhost') . ">\r\n";
            $headers .= "Reply-To: " . (SMTP_FROM ?? 'no-reply@localhost') . "\r\n";
            if (@mail($replyTo, $subject, $body, $headers)) {
                $replySuccess = 'Reply sent using mail().';
            } else {
                $replyError = 'mail() failed. Install PHPMailer and configure SMTP for reliable sending.';
            }
        }
    }
}
?>

<h2>Message #<?php echo (int)$id; ?></h2>

<div class="card mb-3">
  <div class="card-body">
    <h5 class="mb-1"><?php echo htmlspecialchars($c['name']); ?></h5>
    <p class="small text-muted mb-2"><?php echo htmlspecialchars($c['email']); ?><?php if (!empty($c['phone'])) echo ' • '.htmlspecialchars($c['phone']); ?></p>
    <p class="mb-2"><strong>Subject:</strong> <?php echo htmlspecialchars($c['subject']); ?></p>
    <p class="mb-3"><?php echo nl2br(htmlspecialchars($c['message'])); ?></p>
    <p class="small text-muted">Received: <?php echo htmlspecialchars($c['created_at']); ?></p>
  </div>

  <div class="card-footer">
    <?php if ($replyError): ?><div class="alert alert-danger"><?php echo htmlspecialchars($replyError); ?></div><?php endif; ?>
    <?php if ($replySuccess): ?><div class="alert alert-success"><?php echo htmlspecialchars($replySuccess); ?></div><?php endif; ?>

    <form method="post" action="contact_view.php?id=<?php echo (int)$id; ?>">
      <div class="mb-2">
        <label class="form-label">To</label>
        <input class="form-control" value="<?php echo htmlspecialchars($c['email']); ?>" disabled>
      </div>

      <div class="mb-2">
        <label class="form-label">Subject</label>
        <input name="reply_subject" class="form-control" value="<?php echo htmlspecialchars('Re: ' . ($c['subject'] ?? '')); ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="reply_message" rows="6" class="form-control" required><?php echo htmlspecialchars("\n\n--- Original message ---\n" . ($c['message'] ?? '')); ?></textarea>
      </div>

      <button class="btn btn-dark" type="submit">Send Reply</button>
      <a class="btn btn-outline-secondary" href="mailto:<?php echo rawurlencode($c['email']); ?>?subject=<?php echo rawurlencode('Re: ' . ($c['subject'] ?? '')); ?>">Open in mail client</a>
      <a class="btn btn-outline-danger float-end" href="contact_delete.php?id=<?php echo (int)$c['id']; ?>" onclick="return confirm('Delete this message?')">Delete</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
