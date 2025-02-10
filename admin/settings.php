<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

include '../config/db.php';

// Fetch current settings from the database
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update settings if form is submitted
    $paypal_client_id = $_POST['paypal_client_id'];
    $paypal_secret = $_POST['paypal_secret'];
    
    $stmt = $pdo->prepare("UPDATE settings SET paypal_client_id = ?, paypal_secret = ? WHERE id = ?");
    $stmt->execute([$paypal_client_id, $paypal_secret, $settings['id']]);

    // Refresh settings
    $settings['paypal_client_id'] = $paypal_client_id;
    $settings['paypal_secret'] = $paypal_secret;
    $message = "Settings updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <div class="container mt-4">
        <h2>System Settings</h2>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
                <input type="text" class="form-control" name="paypal_client_id" value="<?php echo htmlspecialchars($settings['paypal_client_id']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="paypal_secret" class="form-label">PayPal Secret</label>
                <input type="text" class="form-control" name="paypal_secret" value="<?php echo htmlspecialchars($settings['paypal_secret']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
