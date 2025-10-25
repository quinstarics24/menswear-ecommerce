<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$perPage = 24;
$page = max(1, intval($_GET['page'] ?? 1));
$search = trim((string)($_GET['q'] ?? ''));
$category = trim((string)($_GET['category'] ?? ''));
$offset = ($page - 1) * $perPage;

$categories = getCategories();
$total = getProductCount($search, $category);
$products = getAllProducts($perPage, $offset, $search, $category);
$totalPages = max(1, (int)ceil($total / $perPage));
$isAdmin = !empty($_SESSION['is_admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Menswear Products</title>

  <!-- Fonts / UI -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light text-dark">

<?php include 'includes/header.php'; ?>

<main class="py-5">
  <div class="container">
    <div class="d-flex align-items-center mb-4 gap-3 flex-wrap">
      <h1 class="me-auto mb-0">Products</h1>

      <form class="d-flex align-items-center gap-2" method="get" action="products.php">
        <input name="q" value="<?php echo htmlspecialchars($search); ?>" class="form-control form-control-sm" placeholder="Search products..." />
        <select name="category" class="form-select form-select-sm">
          <option value="">All categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat); ?>" <?php if ($cat === $category) echo 'selected'; ?>><?php echo htmlspecialchars(ucfirst($cat)); ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>

      <?php if ($isAdmin): ?>
        <a href="admin_add_product.php" class="btn btn-warning btn-sm ms-3">+ Add Product</a>
      <?php endif; ?>
    </div>

    <?php if (empty($products)): ?>
      <div class="alert alert-info">No products found. <?php if ($isAdmin) echo 'Use the Add Product button to create items.'; ?></div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($products as $p): ?>
          <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
            <div class="card product-card h-100 shadow-sm">
              <?php $img = !empty($p['image']) ? $p['image'] : 'assets/images/placeholder.jpg'; ?>
              <img src="<?php echo htmlspecialchars($img); ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($p['name']); ?>">

              <div class="card-body d-flex flex-column">
                <h6 class="mb-1"><?php echo htmlspecialchars($p['name']); ?></h6>
                <div class="small text-muted mb-2"><?php echo htmlspecialchars(mb_strimwidth($p['description'] ?? '', 0, 80, '...')); ?></div>
                <div class="mb-2"><small class="text-muted"><?php echo !empty($p['category']) ? htmlspecialchars(ucfirst($p['category'])) : ''; ?></small></div>

                <div class="mt-auto d-flex align-items-center">
                  <div class="price me-auto fw-bold"><?php echo formatPrice($p['price']); ?></div>
                  <form method="post" action="cart.php" class="d-flex align-items-center">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm me-2" style="width:72px;">
                    <button type="submit" class="btn btn-dark btn-sm"><i class="fa-solid fa-cart-plus"></i></button>
                  </form>
                </div>

                <div class="mt-2">
                  <button type="button" class="btn btn-outline-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $p['id']; ?>">
                    <i class="fa-solid fa-eye me-1"></i> View Details
                  </button>
                </div>

                <?php if ($isAdmin): ?>
                  <div class="mt-2 d-flex gap-2">
                    <a href="admin_edit_product.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-secondary btn-sm w-100">Edit</a>
                    <a href="admin_delete_product.php?id=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm w-100" onclick="return confirm('Delete product?')">Delete</a>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Modal for Product Details -->
          <div class="modal fade" id="productModal<?php echo $p['id']; ?>" tabindex="-1" aria-labelledby="productModalLabel<?php echo $p['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="productModalLabel<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                  <div class="col-md-6">
                    <img src="<?php echo htmlspecialchars($img); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($p['name']); ?>">
                  </div>
                  <div class="col-md-6">
                    <h4 class="text-muted"><?php echo formatPrice($p['price']); ?></h4>
                    <p><?php echo htmlspecialchars($p['description']); ?></p>
                    <small class="text-muted"><?php echo !empty($p['category']) ? 'Category: '.htmlspecialchars(ucfirst($p['category'])) : ''; ?></small>
                  </div>
                </div>
                <div class="modal-footer">
                  <form method="post" action="cart.php" class="d-flex align-items-center w-100">
                    <input type="hidden" name="add_to_cart" value="1">
                    <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                    <input type="number" name="quantity" value="1" min="1" class="form-control me-2" style="width:80px;">
                    <button type="submit" class="btn btn-dark w-100"><i class="fa-solid fa-cart-plus me-1"></i> Add to Cart</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <nav class="mt-4" aria-label="Products pages">
          <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
              <a class="page-link" href="?q=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $page-1; ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                <a class="page-link" href="?q=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
              <a class="page-link" href="?q=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&page=<?php echo $page+1; ?>">Next</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>

    <?php endif; ?>
  </div>
</main>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ once: true, duration: 700 });
</script>
</body>
</html>
