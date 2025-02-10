<?php
session_start();
if (!isset($_SESSION['client_logged_in'])) {
    header("Location: index.php");
    exit();
}

include '../config/db.php';

$user_id = $_SESSION['client_id'];

// Fetch client details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$client = $stmt->fetch();

// Fetch client orders
$stmt = $pdo->prepare("SELECT orders.id, products.name AS product_name, orders.order_status, orders.payment_status
                       FROM orders
                       INNER JOIN products ON orders.product_id = products.id
                       WHERE orders.user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();

// Fetch client licenses
$stmt = $pdo->prepare("SELECT licenses.id, products.name AS product_name, licenses.license_key, licenses.status
                       FROM licenses
                       INNER JOIN products ON licenses.product_id = products.id
                       WHERE licenses.user_id = ?");
$stmt->execute([$user_id]);
$licenses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="dashboard.php">Client Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <div class="container mt-4">
        <h2>Welcome, <?php echo htmlspecialchars($client['name']); ?></h2>

        <h4 class="mt-4">Your Orders</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Order Status</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td><?php echo $order['order_status']; ?></td>
                        <td><?php echo $order['payment_status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4 class="mt-4">Your Licenses</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>License ID</th>
                    <th>Product</th>
                    <th>License Key</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($licenses as $license): ?>
                    <tr>
                        <td><?php echo $license['id']; ?></td>
                        <td><?php echo htmlspecialchars($license['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($license['license_key']); ?></td>
                        <td><?php echo $license['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
