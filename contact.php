<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];
$success = '';
// CSRF token
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(16));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // basic CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = 'Invalid form submission.';
    } else {
        $name = validateInput($_POST['name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $phone = validateInput($_POST['phone'] ?? '');
        $subject = validateInput($_POST['subject'] ?? 'General inquiry');
        $message = trim($_POST['message'] ?? '');

        if ($name === '') $errors[] = 'Please enter your name.';
        if (!$email) $errors[] = 'Please enter a valid email address.';
        if ($message === '') $errors[] = 'Please enter a message.';

        if (empty($errors)) {
            $now = date('Y-m-d H:i:s');
            $saved = false;

            // Try to save to DB if $pdo exists
            if (isset($pdo) && ($pdo instanceof PDO)) {
                try {
                    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS contacts (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(191),
                        email VARCHAR(191),
                        phone VARCHAR(50),
                        subject VARCHAR(191),
                        message TEXT,
                        created_at DATETIME
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
                    $stmt->execute();

                    $ins = $pdo->prepare("INSERT INTO contacts (name,email,phone,subject,message,created_at) VALUES (:name,:email,:phone,:subject,:message,:created_at)");
                    $ins->execute([
                        ':name' => $name,
                        ':email' => $email,
                        ':phone' => $phone,
                        ':subject' => $subject,
                        ':message' => $message,
                        ':created_at' => $now
                    ]);
                    $saved = true;
                } catch (PDOException $e) {
                    // fall through to file save
                    $saved = false;
                }
            }

            // fallback: append to log file
            if (!$saved) {
                $logDir = __DIR__ . '/storage';
                if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
                $line = json_encode([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'subject' => $subject,
                    'message' => $message,
                    'created_at' => $now
                ], JSON_UNESCAPED_UNICODE);
                file_put_contents($logDir . '/contacts.log', $line . PHP_EOL, FILE_APPEND | LOCK_EX);
                $saved = true;
            }

            // attempt email notification (may not work on local server but harmless)
            $notifyTo = 'support@menswear.example';
            $mailSubject = "[Website Contact] " . $subject;
            $mailBody = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\n\nMessage:\n{$message}\n\nSubmitted: {$now}";
            $headers = "From: {$name} <{$email}>\r\nReply-To: {$email}\r\n";
            @mail($notifyTo, $mailSubject, $mailBody, $headers);

            if ($saved) {
                $success = 'Thanks — your message has been received. We will reply within 1-2 business days.';
                // regenerate token to avoid double-submit
                $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
                // clear POST values to avoid re-populating
                $_POST = [];
            } else {
                $errors[] = 'Could not save your message. Try again later.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Contact Us — Menswear</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .contact-hero { background: linear-gradient(90deg, rgba(11,18,32,0.85), rgba(11,18,32,0.5)), url('assets/images/contact-hero.jpg') center/cover no-repeat; color: #fff; padding: 64px 0; border-radius:12px; }
    .contact-card { border-radius:12px; box-shadow:0 12px 30px rgba(11,18,32,0.06); }
    .muted-small { color:#6b7280; font-size:.95rem; }
  </style>
</head>
<body class="bg-light text-dark">

<?php include 'includes/header.php'; ?>

<main class="py-5">
  <div class="container">
    <div class="contact-hero mb-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="display-6 fw-bold">Contact Us</h1>
            <p class="lead mb-0">Questions about orders, products, or collaborations? Drop us a message — we’re happy to help.</p>
          </div>
          <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="products.php" class="btn btn-outline-light btn-sm">Browse Products</a>
            <a href="checkout.php" class="btn btn-warning btn-sm text-dark ms-2">Checkout</a>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card contact-card p-4">
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
          <?php endif; ?>

          <form method="post" action="contact.php" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full name</label>
                <input name="name" type="text" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                <div class="invalid-feedback">Please enter your name.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <div class="invalid-feedback">Please enter a valid email.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Phone (optional)</label>
                <input name="phone" type="tel" class="form-control" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Subject</label>
                <input name="subject" type="text" class="form-control" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
              </div>

              <div class="col-12">
                <label class="form-label">Message</label>
                <textarea name="message" rows="6" class="form-control" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                <div class="invalid-feedback">Please enter your message.</div>
              </div>

              <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-dark"><i class="fa-solid fa-paper-plane me-2"></i>Send Message</button>
                <button type="reset" class="btn btn-outline-secondary">Reset</button>
              </div>
            </div>
          </form>
        </div>

        <div class="mt-3 muted-small">
          <strong>Response time:</strong> We usually reply within 1-2 business days.<br>
          <strong>For urgent order issues:</strong> please include your order number in the message.
        </div>
      </div>

      <div class="col-lg-5">
        <div class="card contact-card p-3 mb-3">
          <h6 class="fw-bold mb-2">Contact details</h6>
          <p class="mb-1"><i class="fa-solid fa-envelope me-2"></i><a class="text-decoration-none" href="mailto:support@menswear.example">support@menswear.example</a></p>
          <p class="mb-1"><i class="fa-solid fa-phone me-2"></i>+237 673 456 789</p>
          <p class="mb-0"><i class="fa-solid fa-location-dot me-2"></i>123 Menswear St., City, Country</p>
        </div>

        <div class="card contact-card overflow-hidden">
          <div style="min-height:200px;">
            <!-- replace src with your store location or remove if undesired -->
            <iframe
              src="https://www.google.com/maps?q=Yaounde&output=embed"
              style="border:0;width:100%;height:220px;"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>

        <div class="mt-3 text-center">
          <a href="#" class="social-icon me-2"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="social-icon me-2"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="social-icon"><i class="fa-brands fa-twitter"></i></a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Bootstrap validation
  (function () {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>
</body>
</html>
