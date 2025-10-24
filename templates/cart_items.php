<?php
session_start();

// Check if the cart session exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to display cart items
function displayCartItems($cart) {
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<table>";
    echo "<tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Action</th></tr>";

    foreach ($cart as $item) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
        echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
        echo "<td>$" . number_format($item['price'], 2) . "</td>";
        echo "<td><a href='cart.php?action=remove&id=" . htmlspecialchars($item['id']) . "'>Remove</a></td>";
        echo "</tr>";
    }

    echo "</table>";
}

// Call the function to display cart items
displayCartItems($_SESSION['cart']);
?>