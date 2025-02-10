<?php
include '../config/db.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['license_key'])) {
    $license_key = $_POST['license_key'];

    // Fetch license details from the database
    $stmt = $pdo->prepare("SELECT * FROM licenses WHERE license_key = ? AND status = 'active'");
    $stmt->execute([$license_key]);
    $license = $stmt->fetch();

    if ($license) {
        // Return success if license is valid and active
        echo json_encode(["status" => "valid", "message" => "License is valid."]);
    } else {
        // Return error if license is invalid or expired
        echo json_encode(["status" => "invalid", "message" => "Invalid or expired license."]);
    }
}
?>
