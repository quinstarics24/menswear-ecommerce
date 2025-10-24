<?php

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Ensure cart exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = max(1, intval($_POST['quantity'] ?? 1));

    if ($product_id > 0) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header('Location: cart.php');
    exit;
}

// Update quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    $quantities = $_POST['quantities'] ?? [];
    foreach ($quantities as $pid => $qty) {
        $pid = intval($pid);
        $qty = max(0, intval($qty));
        if ($pid > 0) {
            if ($qty === 0) {
                unset($_SESSION['cart'][$pid]);
            } else {
                $_SESSION['cart'][$pid] = $qty;
            }
        }
    }
    header('Location: cart.php');
    exit;
}

// Remove item via GET
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    if ($product_id > 0) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: cart.php');
    exit;
}

// Build cart items with product details
$cart_items = [];
$subtotal = 0.0;
if (!empty($_SESSION['cart'])) {
    $products = getProductsByIds(array_keys($_SESSION['cart']));
    foreach ($_SESSION['cart'] as $prodId => $qty) {
        if (!isset($products[$prodId])) {
            unset($_SESSION['cart'][$prodId]);
            continue;
        }
        $product = $products[$prodId];
        $line_total = ((float)$product['price']) * ((int)$qty);
        $subtotal += $line_total;
        $cart_items[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'image' => $product['image'],
            'price' => (float)$product['price'],
            'quantity' => (int)$qty,
            'line_total' => $line_total
        ];
    }
}
$total_display = formatPrice($subtotal);

include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Menswear</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-light text-dark">
<div class="container py-5">
    <h1 class="mb-4">Your Shopping Cart</h1>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Your cart is empty. <a href="index.php">Continue shopping</a></div>
    <?php else: ?>
        <form method="post" action="cart.php">
            <input type="hidden" name="update_cart" value="1">
            <div class="table-responsive">
                <table class="table align-middle bg-white shadow-sm rounded">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th style="width:110px;">Price</th>
                            <th style="width:120px;">Quantity</th>
                            <th style="width:120px;">Total</th>
                            <th style="width:90px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="" style="height:64px; width:64px; object-fit:cover; border-radius:8px; margin-right:12px;">
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo formatPrice($item['price']); ?></td>
                                <td>
                                    <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="0" class="form-control form-control-sm" style="width:96px;">
                                </td>
                                <td><?php echo formatPrice($item['line_total']); ?></td>
                                <td><a href="cart.php?remove=<?php echo $item['id']; ?>" class="text-danger" onclick="return confirm('Remove this item?')">Remove</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-outline-secondary">Update Cart</button>
                <a href="index.php" class="btn btn-light">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-dark ms-auto">Proceed to Checkout</a>
            </div>
        </form>

        <div class="mt-4 text-end">
            <h4>Subtotal: <?php echo $total_display; ?></h4>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
