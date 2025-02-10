<?php
session_start();
include '../config/db.php';

header("Content-Type: application/json");

// Ensure user is an admin
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

// Fetch total sales (sum of order prices)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['report']) && $_GET['report'] === 'total_sales') {
    $stmt = $pdo->query("SELECT SUM(price * quantity) AS total_sales FROM orders INNER JOIN products ON orders.product_id = products.id WHERE orders.payment_status = 'paid'");
    $total_sales = $stmt->fetch()['total_sales'];

    echo json_encode(["status" => "success", "total_sales" => number_format($total_sales, 2)]);
    exit();
}

// Fetch active users count
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['report']) && $_GET['report'] === 'active_users') {
    $stmt = $pdo->query("SELECT COUNT(*) AS active_users FROM users WHERE role = 'client' AND status = 'active'");
    $active_users = $stmt->fetch()['active_users'];

    echo json_encode(["status" => "success", "active_users" => $active_users]);
    exit();
}

// Fetch product performance (number of orders per product)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['report']) && $_GET['report'] === 'product_performance') {
    $stmt = $pdo->query("SELECT products.name AS product_name, COUNT(orders.id) AS orders_count
                         FROM orders
                         INNER JOIN products ON orders.product_id = products.id
                         WHERE orders.payment_status = 'paid'
                         GROUP BY products.name
                         ORDER BY orders_count DESC");
    $product_performance = $stmt->fetchAll();

    echo json_encode(["status" => "success", "product_performance" => $product_performance]);
    exit();
}
?>
