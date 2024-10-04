<?php
include 'db_config.php';
$products = [
    [
        'name' => 'LEGO Classic Set',
        'description' => 'A collection of colorful LEGO bricks for creative play',
        'price' => 2499,
        'quantity' => 100,
        'image' => 'lego.jpg' 
    ],
    [
        'name' => 'Barbie Doll',
        'description' => 'A stylish Barbie doll with accessories',
        'price' => 1599,
        'quantity' => 50,
        'image' => 'barbie.jpg'
    ],
    [
        'name' => 'Hot Wheels Car',
        'description' => 'A fast and fun die-cast Hot Wheels car',
        'price' => 399,
        'quantity' => 200,
        'image' => 'car.jpg'
    ],
    [
        'name' => 'Nerf Blaster',
        'description' => 'A foam dart blaster for action-packed play',
        'price' => 1999,
        'quantity' => 75,
        'image' => 'blaster.jpg'
    ],
    [
        'name' => 'Rubik\'s Cube',
        'description' => 'A 3x3 puzzle cube for brain teasers',
        'price' => 499,
        'quantity' => 150,
        'image' => 'rubiks.jpg'
    ],
    [
        'name' => 'Teddy Bear',
        'description' => 'Big Size Brown Teddy Bear',
        'price' => 800,
        'quantity' => 100,
        'image' => 'bear.jpg'
    ],
];

$sql = "INSERT INTO products (name, description, price, quantity, image) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

foreach ($products as $product) {
    $stmt->bind_param("ssdis", $product['name'], $product['description'], $product['price'], $product['quantity'], $product['image']);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo "Products added successfully!";
?>
