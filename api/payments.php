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

// Process Payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['payment_status'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status']; // Can be 'paid' or 'failed'

    // Check if the order exists and belongs to the logged-in client
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch();

    if ($order) {
        // Update the payment status for the order
        $stmt = $pdo->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
        $stmt->execute([$payment_status, $order_id]);

        echo json_encode(["status" => "success", "message" => "Payment status updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Order not found."]);
    }

    exit();
}

// Fetch payment history for the logged-in client
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT orders.id, products.name AS product_name, orders.quantity, orders.payment_status
                           FROM orders
                           INNER JOIN products ON orders.product_id = products.id
                           WHERE orders.user_id = ?");
    $stmt->execute([$user_id]);
    $payments = $stmt->fetchAll();

    echo json_encode(["status" => "success", "payments" => $payments]);
    exit();
}
?>
