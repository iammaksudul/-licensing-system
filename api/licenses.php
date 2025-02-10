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

// Issue License
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Generate a unique license key
    $license_key = strtoupper(uniqid("LIC-", true));

    // Insert new license into the database
    $stmt = $pdo->prepare("INSERT INTO licenses (user_id, product_id, license_key, status) VALUES (?, ?, ?, 'active')");
    $stmt->execute([$user_id, $product_id, $license_key]);

    echo json_encode(["status" => "success", "license_key" => $license_key, "message" => "License issued successfully."]);
    exit();
}

// Fetch all licenses for the logged-in user
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT licenses.id, products.name AS product_name, licenses.license_key, licenses.status
                           FROM licenses
                           INNER JOIN products ON licenses.product_id = products.id
                           WHERE licenses.user_id = ?");
    $stmt->execute([$user_id]);
    $licenses = $stmt->fetchAll();

    echo json_encode(["status" => "success", "licenses" => $licenses]);
    exit();
}
?>
