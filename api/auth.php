<?php
include '../config/db.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details from the database
    $stmt = $pdo->prepare("SELECT id, password_hash, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify user credentials
    if ($user && password_verify($password, $user['password_hash'])) {
        // Return success response
        echo json_encode(["status" => "success", "user_id" => $user['id'], "role" => $user['role']]);
    } else {
        // Return error if authentication fails
        echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    }
}
?>
