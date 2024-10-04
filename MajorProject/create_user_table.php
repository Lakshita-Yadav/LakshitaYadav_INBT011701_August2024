<?php
include 'db_config.php'; 

// Drop the table if it exists
$sql = "DROP TABLE IF EXISTS users";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' dropped successfully.<br>";
} else {
    echo "Error dropping table: " . $conn->error . "<br>";
}

// Create the new users table
$sql = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    raw_password VARCHAR(255) NOT NULL,
    hashed_password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>

