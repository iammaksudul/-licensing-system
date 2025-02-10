<?php
session_start();
include '../config/db.php';

header("Content-Type: application/json");

// Ensure user is logged in and is an admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

// Fetch all clients
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT id, name, email, created_at FROM users WHERE role = 'client' ORDER BY created_at DESC");
    $clients = $stmt->fetchAll();

    echo json_encode(["status" => "success", "clients" => $clients]);
    exit();
}

// Update client details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'], $_POST['name'], $_POST['email'])) {
    $client_id = $_POST['client_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update client information in the database
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'client'");
    $stmt->execute([$name, $email, $client_id]);

    echo json_encode(["status" => "success", "message" => "Client details updated successfully."]);
    exit();
}

// Delete client (admin operation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $client_id = $_POST['delete_id'];

    // Delete client from the database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'client'");
    $stmt->execute([$client_id]);

    echo json_encode(["status" => "success", "message" => "Client deleted successfully."]);
    exit();
}
?>
