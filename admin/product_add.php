<?php

// Process before output
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin_auth.php';
require_admin();
session_start();
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = str_replace(',', '', trim($_POST['price'] ?? ''));
    $category = trim($_POST['category'] ?? '');

    if ($name === '') $errors[] = 'Name is required.';
    if ($price === '' || !is_numeric($price)) $errors[] = 'Valid price is required.';

    // handle image
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if (!in_array($ext, $allowed)) $errors[] = 'Invalid image type.';
        else {
            $uploadDir = __DIR__ . '/../assets/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $fn = 'p_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = $uploadDir . $fn;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $errors[] = 'Failed to move uploaded image.';
            } else {
                $imagePath = 'assets/uploads/' . $fn;
            }
        }
    }

    if (empty($errors)) {
        try {
            // ensure table exists (simple)
            $pdo->exec("CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(191),
                description TEXT,
                price DECIMAL(10,2),
                category VARCHAR(100),
                image VARCHAR(255),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt = $pdo->prepare("INSERT INTO products (name,description,price,category,image,created_at) VALUES (:n,:d,:p,:c,:i,NOW())");
            $stmt->execute([
                ':n' => $name,
                ':d' => $description,
                ':p' => (float)$price,
                ':c' => $category,
                ':i' => $imagePath
            ]);
            $success = 'Product added successfully.';
            // clear POST
            $_POST = [];
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
require_once __DIR__ . '/header.php';
?>

<h2>Add Product</h2>

<?php if ($errors): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul></div>
<?php endif; ?>
<?php if ($success): ?>
  <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form method="post" action="product_add.php" enctype="multipart/form-data" class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Category</label>
    <input name="category" class="form-control" value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
  </div>
  <div class="col-md-4">
    <label class="form-label">Price</label>
    <input name="price" class="form-control" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Image</label>
    <input name="image" type="file" class="form-control">
  </div>
  <div class="col-12">
    <button class="btn btn-dark" type="submit">Add Product</button>
    <a href="products.php" class="btn btn-outline-secondary">Back to list</a>
  </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>