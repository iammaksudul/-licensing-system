<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

include '../config/db.php';

// Fetch all licenses
$stmt = $pdo->query("SELECT licenses.id, users.name AS client_name, products.name AS product_name, licenses.license_key, licenses.status
                     FROM licenses
                     INNER JOIN users ON licenses.user_id = users.id
                     INNER JOIN products ON licenses.product_id = products.id
                     ORDER BY licenses.issued_at DESC");
$licenses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Licenses | Licensing System</title>
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
        <h2 class="mb-4">Manage Licenses</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#issueLicenseModal">Issue License</button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Product</th>
                    <th>License Key</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($licenses as $license): ?>
                    <tr>
                        <td><?php echo $license['id']; ?></td>
                        <td><?php echo htmlspecialchars($license['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($license['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($license['license_key']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($license['status'] == 'active' ? 'success' : 'danger'); ?>">
                                <?php echo $license['status']; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm revoke-btn" data-id="<?php echo $license['id']; ?>">Revoke</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Issue License Modal -->
    <div class="modal fade" id="issueLicenseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Issue License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="issueLicenseForm">
                        <select class="form-control mb-2" name="user_id" required>
                            <option value="">Select Client</option>
                            <?php
                            $stmt = $pdo->query("SELECT id, name FROM users WHERE role = 'client'");
                            while ($client = $stmt->fetch()) {
                                echo "<option value='{$client['id']}'>{$client['name']}</option>";
                            }
                            ?>
                        </select>
                        <select class="form-control mb-2" name="product_id" required>
                            <option value="">Select Product</option>
                            <?php
                            $stmt = $pdo->query("SELECT id, name FROM products");
                            while ($product = $stmt->fetch()) {
                                echo "<option value='{$product['id']}'>{$product['name']}</option>";
                            }
                            ?>
                        </select>
                        <input type="text" class="form-control mb-2" name="license_key" placeholder="License Key" required>
                        <button type="submit" class="btn btn-primary w-100">Issue License</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#issueLicenseForm").submit(function (event) {
                event.preventDefault();
                $.post("../api/licenses.php", $(this).serialize(), function (response) {
                    location.reload();
                });
            });

            $(".revoke-btn").click(function () {
                let licenseId = $(this).data("id");
                $.post("../api/licenses.php", { revoke_id: licenseId }, function (response) {
                    location.reload();
                });
            });
        });
    </script>

</body>
</html>
