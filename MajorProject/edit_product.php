<?php
include 'db_config.php';

$id = $_GET['id']; 

$sql = "SELECT * FROM products WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); 
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Product</h2>
    <form method="POST" action="update_product.php">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>"> 
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
        <label>Description:</label>
        <textarea name="description" required><?php echo $product['description']; ?></textarea><br>
        <label>Price:</label>
        <input type="number" name="price" value="<?php echo $product['price']; ?>" required><br> ₹
        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required><br>
        <button type="submit">Update Product</button>
    </form>
</body>
</html>
