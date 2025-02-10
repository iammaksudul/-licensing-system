<?php
// Database credentials
$host = 'localhost'; // Database host
$dbname = 'licensing_system'; // Database name
$username = 'root'; // Database username (change this for production)
$password = ''; // Database password (change this for production)

// Establishing the PDO connection
try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If there is an error with the connection, display it
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
