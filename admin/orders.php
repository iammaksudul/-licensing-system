<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

include '../config/db.php';

// Fetch all orders
$stmt = $pdo->query("SELECT orders.id, users.name AS client_name, products.name AS product_name, orders.order_status, orders.payment_status
                     FROM orders
                     INNER JOIN users ON orders.user_id = users.id
                     INNER JOIN products ON orders.product_id = products.id
                     ORDER BY orders.created_at DESC");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Manage Orders</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>Order Status</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($order['order_status'] == 'pending' ? 'warning' : 'success'); ?>">
                                <?php echo $order['order_status']; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo ($order['payment_status'] == 'paid' ? 'success' : 'danger'); ?>">
                                <?php echo $order['payment_status']; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm approve-btn" data-id="<?php echo $order['id']; ?>">Approve</button>
                            <button class="btn btn-danger btn-sm reject-btn" data-id="<?php echo $order['id']; ?>">Reject</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $(".approve-btn").click(function () {
                let orderId = $(this).data("id");
                updateOrderStatus(orderId, "approved");
            });

            $(".reject-btn").click(function () {
                let orderId = $(this).data("id");
                updateOrderStatus(orderId, "rejected");
            });

            function updateOrderStatus(orderId, status) {
                $.post("../api/orders.php", { order_id: orderId, status: status }, function (response) {
                    location.reload();
                });
            }
        });
    </script>
</body>
</html>
