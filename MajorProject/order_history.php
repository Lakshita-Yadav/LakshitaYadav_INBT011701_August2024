<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$sql = "SELECT o.id AS order_id, o.order_date, p.name AS product_name, p.price, o.quantity 
        FROM orders o 
        JOIN products p ON o.product_id = p.id 
        WHERE o.user_id = ? 
        ORDER BY o.order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Your Order History</h1>
            <nav class="nav-buttons" style="margin-left: auto;">
                <button onclick="logout()" class="nav-button">Logout</button>
                <a href="index.php" class="nav-button">Home</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Your Orders</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th class="order-id">Order ID</th>
                        <th class="product-name">Product Name</th>
                        <th class="price">Price</th>
                        <th class="quantity">Quantity</th>
                        <th class="order-date">Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="order-row">
                            <td class="order-id"><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td class="price">₹<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></td>
                            <td class="quantity"><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td class="order-date"><?php echo htmlspecialchars($row['order_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-orders">No orders found.</p>
        <?php endif; ?>
    </div>

    <script>
        function logout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
