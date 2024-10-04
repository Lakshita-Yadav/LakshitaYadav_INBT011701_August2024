<?php
include 'db_config.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Product deleted successfully!";
} else {
    echo "Error deleting record: " . $stmt->error;
}

$stmt->close();
header("Location: index.php");
exit();

$conn->close();
?>
