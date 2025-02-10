<?php
session_start();
include '../config/db.php';

// Check if a product ID is provided
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit();
}

// Handle "Add to Cart" action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add product to cart
    $quantity = $_POST['quantity'] ?: 1;

    $cart_item = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
    ];

    $_SESSION['cart'][] = $cart_item;
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="index.php">Licensing Store</a>
        <a href="cart.php" class="btn btn-outline-light">Cart <span id="cart-count"><?php echo count($_SESSION['cart']); ?></span></a>
    </nav>

    <div class="container mt-4">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <div class="row">
            <div class="col-md-6">
                <img src="https://via.placeholder.com/400" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-6">
                <h4>$<?php echo number_format($product['price'], 2); ?></h4>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <form method="POST">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" id="quantity" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
