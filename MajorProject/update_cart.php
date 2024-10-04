<?php
session_start();
include 'db_config.php';

// Check if the cart exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Update quantities based on form submission
if (isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $key => $quantity) {
        $quantity = intval($quantity);
        if ($quantity < 1) {
            $quantity = 1; // Ensure minimum quantity is 1
        }
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
        }
    }
}

// Redirect back to cart.php after updating
header("Location: cart.php");
exit();
