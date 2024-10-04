<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $image = $_FILES["image"]["name"]; 

    move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $image); 

    $sql = "INSERT INTO products (name, description, price, quantity, image) VALUES ('$name', '$description', $price, $quantity, '$image')";

    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully!"; 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; 
    }

    header("Location: index.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Add New Toy</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label>Name: </label>
        <input type="text" name="name" required><br>
        <label>Description: </label>
        <textarea name="description" required></textarea><br>
        <label>Price: </label>
        <input type="number" name="price" step="0.01" required><br> ₹
        <label>Quantity: </label>
        <input type="number" name="quantity" required><br>
        <label>Image: </label>
        <input type="file" name="image" required><br>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>
