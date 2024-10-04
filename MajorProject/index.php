<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toys Shop</title>
    <style>
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fdf6e3;
            color: #333;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            flex: 1;
            padding: 12px;
            margin-right: 10px;
            border: 2px solid #ffcc00;
            border-radius: 10px;
        }

        .search-container button {
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            background-color: #01a3a4;
            color: white;
            cursor: pointer;
            transition: background-color .3s ease;
        }

        .search-container button:hover {
            background-color: rgba(1, 163, 164, .7);
        }

        header {
            background-color: #ffcc00;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-button {
            background-color: #ff6b6b;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 18px;
            cursor: pointer;
            transition: background-color .3s ease;
        }

        .nav-button:hover {
            background-color: #ee5253;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .product-item {
            background-color: white;
            border: 2px solid #ffcc00;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .1);
            transition: transform .3s ease;
        }

        .product-item:hover {
            transform: scale(1.05);
        }

        .product-item img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
            cursor: pointer;
        }

        .product-item h3, .product-item p {
            margin: 10px 0;
        }

        .add-to-cart-button {
            padding: 10px 15px;
            background-color: #00d2d3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color .3s ease;
        }

        .add-to-cart-button:hover {
            background-color: #01a3a4;
        }

        footer {
            background-color: #ffcc00;
            color: white;
            padding: 20px;
            text-align: center;
            width: 100%;
            position: relative;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Toys Shop</h1>
            <nav class="nav-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button onclick="logout()" class="nav-button">Logout</button>
                <?php else: ?>
                    <a href="login.php" class="nav-button">Login</a>
                    <div class="register-message">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-buttons">
                <a href="cart.php" class="nav-button">Cart</a>
                <a href="order_history.php" class="nav-button">Order History</a>
                <a href="add_product.php" class="add-product-button">Add Product</a>
            </div>
        <?php endif; ?>
        
        <div class="search-container">
            <form method="GET" action="index.php">
                <input type="text" name="search" placeholder="Search for toys..." required>
                <button type="submit">Search</button>
            </form>
        </div>
        
        <div class="product-grid" id="product-list">
            <?php
            include 'db_config.php';

            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = "SELECT id, name, description, price, quantity, image FROM products WHERE name LIKE ? OR description LIKE ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error preparing the SQL statement: " . $conn->error);
            }

            $likeSearch = "%$search%";
            $stmt->bind_param("ss", $likeSearch, $likeSearch);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-item'>";
                    echo "<img src='" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["name"]) . "'>";
                    echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
                    echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                    echo "<p>Price: ₹" . number_format($row["price"], 2) . "</p>";
                    echo "<p>Available Quantity: " . htmlspecialchars($row["quantity"]) . "</p>";
            
                    if (isset($_SESSION['user_id'])) {
                        echo "<form method='POST' action='cart.php' style='display:inline;'>";
                        echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row["id"]) . "'>";
                        echo "<input type='hidden' name='product_name' value='" . htmlspecialchars($row["name"]) . "'>";
                        echo "<input type='hidden' name='product_price' value='" . htmlspecialchars($row["price"]) . "'>";
                        echo "<input type='hidden' name='product_quantity' value='1'>"; // Default quantity
                        echo "<button type='submit' class='add-to-cart-button'>Add to Cart</button>";
                        echo "</form>";
                        echo " <a href='edit_product.php?id=" . htmlspecialchars($row["id"]) . "'>Edit</a> | ";
                        echo "<a href='delete_product.php?id=" . htmlspecialchars($row["id"]) . "'>Delete</a>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>No products found.</p>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Toys Shop. All rights reserved.</p>
    </footer>

    <script>
        function logout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>
</html>
