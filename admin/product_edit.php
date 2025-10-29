<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/admin_auth.php';
require_admin();
session_start();
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: products.php'); exit; }

$errors = [];
$success = '';

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) { header('Location: products.php'); exit; }
} catch (PDOException $e) {
    $product = null;
    $errors[] = 'DB error.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = str_replace(',', '', trim($_POST['price'] ?? ''));
    $category = trim($_POST['category'] ?? '');

    if ($name === '') $errors[] = 'Name is required.';
    if ($price === '' || !is_numeric($price)) $errors[] = 'Valid price is required.';

    // image replace
    $imagePath = $product['image'];
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
                // remove old image if exists (and inside assets/uploads)
                if (!empty($imagePath) && strpos($imagePath, 'assets/uploads/') === 0) {
                    @unlink(__DIR__ . '/../' . $imagePath);
                }
                $imagePath = 'assets/uploads/' . $fn;
            }
        }
    }

    if (empty($errors)) {
        try {
            $upd = $pdo->prepare("UPDATE products SET name=:n,description=:d,price=:p,category=:c,image=:i WHERE id=:id");
            $upd->execute([
                ':n' => $name,
                ':d' => $description,
                ':p' => (float)$price,
                ':c' => $category,
                ':i' => $imagePath,
                ':id' => $id
            ]);
            $success = 'Product updated.';
            // refresh product
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $errors[] = 'DB error: ' . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<h2>Edit Product</h2>

<?php if ($errors): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul></div>
<?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

<form method="post" action="product_edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data" class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Category</label>
    <input name="category" class="form-control" value="<?php echo htmlspecialchars($product['category'] ?? ''); ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
  </div>
  <div class="col-md-4">
    <label class="form-label">Price</label>
    <input name="price" class="form-control" value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Replace Image</label>
    <input name="image" type="file" class="form-control">
    <?php if (!empty($product['image'])): ?>
      <div class="mt-2"><img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="" style="height:64px;border-radius:6px;"></div>
    <?php endif; ?>
  </div>
  <div class="col-12">
    <button class="btn btn-dark" type="submit">Save Changes</button>
    <a href="products.php" class="btn btn-outline-secondary">Back to list</a>
  </div>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>