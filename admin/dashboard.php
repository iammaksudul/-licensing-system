<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

include '../config/db.php';

// Fetch total users
$stmt = $pdo->query("SELECT COUNT(*) AS total_users FROM users WHERE role = 'client'");
$total_users = $stmt->fetch()['total_users'];

// Fetch total sales
$stmt = $pdo->query("SELECT SUM(price) AS total_sales FROM orders INNER JOIN products ON orders.product_id = products.id WHERE orders.payment_status = 'paid'");
$total_sales = $stmt->fetch()['total_sales'] ?: 0;

// Fetch active licenses
$stmt = $pdo->query("SELECT COUNT(*) AS active_licenses FROM licenses WHERE status = 'active'");
$active_licenses = $stmt->fetch()['active_licenses'];

// Fetch pending orders
$stmt = $pdo->query("SELECT COUNT(*) AS pending_orders FROM orders WHERE order_status = 'pending'");
$pending_orders = $stmt->fetch()['pending_orders'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Dashboard</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h3><?php echo $total_users; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <h3>$<?php echo number_format($total_sales, 2); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow">
                    <div class="card-body">
                        <h5 class="card-title">Active Licenses</h5>
                        <h3><?php echo $active_licenses; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow">
                    <div class="card-body">
                        <h5 class="card-title">Pending Orders</h5>
                        <h3><?php echo $pending_orders; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mt-4">Recent Orders</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT orders.id, users.name, products.name AS product_name, orders.order_status, orders.payment_status
                                     FROM orders 
                                     INNER JOIN users ON orders.user_id = users.id
                                     INNER JOIN products ON orders.product_id = products.id
                                     ORDER BY orders.created_at DESC LIMIT 5");

                while ($row = $stmt->fetch()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['product_name']}</td>
                        <td><span class='badge bg-".($row['order_status'] == 'pending' ? "warning" : "success")."'>{$row['order_status']}</span></td>
                        <td><span class='badge bg-".($row['payment_status'] == 'paid' ? "success" : "danger")."'>{$row['payment_status']}</span></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
