<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, quantity=? WHERE id=?");
    $stmt->bind_param("ssdii", $name, $description, $price, $quantity, $id);

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    header("Location: index.php");
    exit();
}

$conn->close();
?>
