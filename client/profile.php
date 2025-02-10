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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $client['password_hash'];

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password_hash = ? WHERE id = ?");
    $stmt->execute([$new_name, $new_email, $new_password, $user_id]);

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Profile | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="dashboard.php">Client Dashboard</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

    <div class="container mt-4">
        <h2>Update Your Profile</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($client['name']); ?>" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="New Password">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

</body>
</html>
