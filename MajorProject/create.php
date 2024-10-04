<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE toys_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$conn->select_db("toys_db");

$sql = "CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL
    image VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'products' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>