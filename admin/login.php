<?php
session_start();
require_once __DIR__ . '/config.php';

$errors = [];

// initialize attempt tracking
if (!isset($_SESSION['admin_login_attempts'])) $_SESSION['admin_login_attempts'] = 0;
if (!isset($_SESSION['admin_last_attempt'])) $_SESSION['admin_last_attempt'] = 0;

$locked = false;
if ($_SESSION['admin_login_attempts'] >= ADMIN_MAX_ATTEMPTS) {
    $since = time() - (int)$_SESSION['admin_last_attempt'];
    if ($since < ADMIN_LOCKOUT_SECONDS) $locked = true;
    else {
        // reset after lockout period
        $_SESSION['admin_login_attempts'] = 0;
        $_SESSION['admin_last_attempt'] = 0;
        $locked = false;
    }
}

// simple CSRF token
if (empty($_SESSION['csrf_admin'])) $_SESSION['csrf_admin'] = bin2hex(random_bytes(16));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($locked) {
        $errors[] = 'Too many failed attempts. Try again later.';
    } elseif (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_admin'], $_POST['csrf_token'])) {
        $errors[] = 'Invalid form submission.';
    } else {
        $user = trim((string)($_POST['username'] ?? ''));
        $pass = $_POST['password'] ?? '';

        // verify credentials
        if ($user === ADMIN_USER && password_verify($pass, ADMIN_PASS_HASH)) {
            // success
            session_regenerate_id(true);
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_user'] = $user;
            // reset attempts
            $_SESSION['admin_login_attempts'] = 0;
            $_SESSION['admin_last_attempt'] = 0;
            header('Location: index.php'); exit;
        } else {
            $_SESSION['admin_login_attempts']++;
            $_SESSION['admin_last_attempt'] = time();
            $remaining = max(0, ADMIN_MAX_ATTEMPTS - $_SESSION['admin_login_attempts']);
            $errors[] = 'Invalid username or password.' . ($remaining ? " Attempts left: {$remaining}." : ' Account locked for a short time.');
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login — Menswear</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#f6f8fb}</style>
</head>
<body>
  <div class="container" style="max-width:480px;margin-top:8vh;">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-3">Admin sign in</h4>

        <?php if ($locked): ?>
          <div class="alert alert-warning">Too many failed attempts. Please wait and try again later.</div>
        <?php endif; ?>

        <?php if ($errors): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="post" action="login.php" novalidate>
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_admin']); ?>">

          <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" required autofocus value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-dark" type="submit" <?php if ($locked) echo 'disabled'; ?>>Sign in</button>
            <a href="../" class="btn btn-link">Back to store</a>
          </div>
        </form>

        <hr>
        <div class="small text-muted">
          Default username/password: <strong>admin / admin123</strong>. Change credentials in <code>admin/config.php</code>.
        </div>
      </div>
    </div>
  </div>
</body>
</html>