<?php
include '../config/db.php';

// Get license key from the request (GET or POST)
$license_key = $_GET['license_key'] ?? $_POST['license_key'] ?? null;

if ($license_key) {
    // Validate license key in the database
    $stmt = $pdo->prepare("SELECT * FROM licenses WHERE license_key = ? AND status = 'active'");
    $stmt->execute([$license_key]);
    $license = $stmt->fetch();

    if ($license) {
        echo json_encode(["status" => "valid", "message" => "License is valid."]);
    } else {
        echo json_encode(["status" => "invalid", "message" => "Invalid or expired license."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No license key provided."]);
}
?>
