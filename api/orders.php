<?php
session_start();
include '../config/db.php';

header("Content-Type: application/json");

// Ensure user is logged in
if (!isset($_SESSION['client_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION['client_id'];

// Handle order creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $status = 'pending'; // Default status for new orders

    // Insert new order into the database
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, order_status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $product_id, $quantity, $status]);

    echo json_encode(["status" => "success", "message" => "Order placed successfully."]);
    exit();
}

// Fetch all orders for the logged-in user
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT orders.id, products.name AS product_name, orders.quantity, orders.order_status
                           FROM orders
                           INNER JOIN products ON orders.product_id = products.id
                           WHERE orders.user_id = ?");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();

    echo json_encode(["status" => "success", "orders" => $orders]);
    exit();
}
?>
