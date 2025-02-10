<?php
$host = 'localhost';  // Database host
$dbname = 'licensing_system';  // Database name
$username = 'root';  // Database username (replace with your credentials)
$password = '';  // Database password (replace with your credentials)

try {
    // Create a PDO instance and establish the connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection error
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
