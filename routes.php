<?php
// Route Manager

// Example route function
function route($url, $callback) {
    if ($_SERVER['REQUEST_URI'] == $url) {
        call_user_func($callback);
    }
}

// Define routes
route('/home', function() {
    include 'home.php';
});

route('/login', function() {
    include 'login.php';
});

route('/dashboard', function() {
    include 'dashboard.php';
});
?>
