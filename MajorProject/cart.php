<?php
session_start();
include 'db_config.php';

// Initialize the cart in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Fetch product details from the database
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Check if the product is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] === $product['id']) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }
        
        // If not found, add a new item to the cart
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $remove_key = intval($_POST['remove_item']);
    if (isset($_SESSION['cart'][$remove_key])) {
        unset($_SESSION['cart'][$remove_key]);
    }
}

// Calculate total amount
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    if (is_array($item) && isset($item['price'], $item['quantity'])) {
        $total_amount += floatval($item['price']) * intval($item['quantity']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-amount {
            font-weight: bold;
            margin-top: 10px;
        }
        .checkout-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        
        .remove-button:hover, .checkout-button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Shopping Cart</h1>
        <a href="index.php" class="nav-button">Continue Shopping</a>
    </header>

    <div class="cart-container">
        <form method="POST" action="update_cart.php"> <!-- Changed action to update_cart.php -->
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $key; ?>]" value="<?php echo intval($item['quantity']); ?>" min="1" style="width: 60px;">
                                </td>
                                <td>₹<?php echo number_format($item['price'] * intval($item['quantity']), 2); ?></td>
                                <td>
                                    <form method="POST" action="cart.php" style=" display:inline;">
                                        <input type="hidden" name="remove_item" value="<?php echo $key; ?>">
                                        <button type="submit" class="remove-button">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Your cart is empty.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="total-amount">
                Total: ₹<?php echo number_format($total_amount, 2); ?>
            </div>
            <button type="submit" class="checkout-button">Update Cart</button>
            <a href="checkout.php" class="checkout-button">Checkout</a>
        </form>
    </div>
</body>
</html>
