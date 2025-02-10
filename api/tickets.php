<?php
session_start();
include '../config/db.php';

header("Content-Type: application/json");

// Ensure user is logged in
if (!isset($_SESSION['client_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

$user_id = isset($_SESSION['client_logged_in']) ? $_SESSION['client_id'] : null; // For clients

// Create new ticket (clients only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description']) && $user_id) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Insert new ticket into the database
    $stmt = $pdo->prepare("INSERT INTO tickets (user_id, title, description, status) VALUES (?, ?, ?, 'open')");
    $stmt->execute([$user_id, $title, $description]);

    echo json_encode(["status" => "success", "message" => "Ticket submitted successfully."]);
    exit();
}

// Update ticket status (admins only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['status']) && isset($_SESSION['admin_logged_in'])) {
    $ticket_id = $_POST['ticket_id'];
    $status = $_POST['status'];

    // Validate status change
    if (!in_array($status, ['open', 'closed'])) {
        echo json_encode(["status" => "error", "message" => "Invalid status update."]);
        exit();
    }

    // Update ticket status in the database
    $stmt = $pdo->prepare("UPDATE tickets SET status = ? WHERE id = ?");
    $stmt->execute([$status, $ticket_id]);

    echo json_encode(["status" => "success", "message" => "Ticket status updated successfully."]);
    exit();
}

// Fetch tickets for clients
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $tickets = $stmt->fetchAll();

    echo json_encode(["status" => "success", "tickets" => $tickets]);
    exit();
}

// Fetch all tickets (admins only)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['admin_logged_in'])) {
    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY created_at DESC");
    $tickets = $stmt->fetchAll();

    echo json_encode(["status" => "success", "tickets" => $tickets]);
    exit();
}
?>
