<?php
include '../config/db.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/store-style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="index.php">Licensing Store</a>
        <a href="cart.php" class="btn btn-outline-light">Cart <span id="cart-count">0</span></a>
    </nav>

    <div class="container mt-4">
        <h2>Our Products</h2>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['category']); ?></p>
                            <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
                            <button class="btn btn-primary add-to-cart" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-price="<?php echo $product['price']; ?>">Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        let cart = [];

        $(document).ready(function () {
            $(".add-to-cart").click(function () {
                const product = {
                    id: $(this).data("id"),
                    name: $(this).data("name"),
                    price: $(this).data("price"),
                    quantity: 1
                };
                cart.push(product);
                updateCartCount();
            });

            function updateCartCount() {
                $("#cart-count").text(cart.length);
            }
        });
    </script>
</body>
</html>
