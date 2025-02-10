<?php
// Include environment variables and autoload
require_once 'vendor/autoload.php';
require_once 'config/db.php';
require_once 'config/config.php';

// Entry point for the application
echo "<h1>Welcome to the Licensing System</h1>";

// Example routing logic
if ($_SERVER['REQUEST_URI'] == '/login') {
    include 'login.php';
} elseif ($_SERVER['REQUEST_URI'] == '/dashboard') {
    include 'dashboard.php';
} else {
    include 'home.php';
}
?>
