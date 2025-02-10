<?php
include '../config/db.php';

header("Content-Type: application/json");

// Fetch all products
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT id, name, description, price FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll();

    echo json_encode(["status" => "success", "products" => $products]);
    exit();
}

// Admin: Add a new product (Only accessible by admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['description'], $_POST['price'])) {
    // Ensure admin role
    session_start();
    if ($_SESSION['role'] !== 'admin') {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit();
    }

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Insert new product into the database
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $price]);

    echo json_encode(["status" => "success", "message" => "Product added successfully."]);
    exit();
}
?>
