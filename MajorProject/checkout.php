<?php
session_start();
include 'db_config.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_SESSION['cart'])) {
    $userId = $_SESSION['user_id'];
    $cart = $_SESSION['cart'];

    foreach ($cart as $item) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $userId, $item['id'], $item['quantity']);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['cart'] = [];
    $message = "Thank you for your purchase!";
}

$cartProducts = [];
$totalAmount = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_column($_SESSION['cart'], 'id'));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    
    while ($row = $result->fetch_assoc()) {
        $cartProducts[] = $row;
        
        // Find the corresponding item in the cart
        foreach ($_SESSION['cart'] as $item) {
            if ($item['id'] == $row['id']) { // Use == for loose comparison
                // Calculate total amount based on quantity
                $totalAmount += floatval($row['price']) * intval($item['quantity']);
                break; // Exit loop once the item is found
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .table-container { margin: 20px auto; width: 80%; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .feedback-message { color: green; font-weight: bold; }
        .checkout-button { background-color: #4CAF50; color: white; padding: 10px; border: none; cursor: pointer; }
        .checkout-button:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Checkout</h2>
        <form method="POST">
            <h3>Your Products</h3>
            <table>
                <thead>
                    <tr><th>Product</th><th>Price</th><th>Quantity</th></tr>
                </thead>
                <tbody>
                    <?php if ($cartProducts): ?>
                        <?php foreach ($cartProducts as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                <td>
                                    <?php
                                    foreach ($_SESSION['cart'] as $item) {
                                        if ($item['id'] === $product['id']) {
                                            echo intval($item['quantity']);
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3">Your cart is empty.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <h4>Total Amount: ₹<?php echo number_format($totalAmount, 2); ?></h4>
            <button type="submit" class="checkout-button">Confirm Purchase</button>
            <a href="index.php" class="nav-button">Home</a>
        </form>
        <?php if ($message): ?><p class="feedback-message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
    </div>
</body>
</html>