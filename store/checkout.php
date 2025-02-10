<?php
session_start();
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

require '../vendor/autoload.php'; // PayPal SDK

// PayPal Configuration (Replace with your actual credentials)
$clientId = 'YOUR_PAYPAL_CLIENT_ID';
$secret = 'YOUR_PAYPAL_SECRET';
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential($clientId, $secret)
);

// Calculate total amount
$totalAmount = 0;
foreach ($_SESSION['cart'] as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}

// Create PayPal payment
$payment = new \PayPal\Api\Payment();
$payment->setIntent('sale')
    ->setPayer(new \PayPal\Api\Payer(['payment_method' => 'paypal']))
    ->setTransactions([
        new \PayPal\Api\Transaction([
            'amount' => new \PayPal\Api\Amount([
                'total' => $totalAmount,
                'currency' => 'USD'
            ]),
            'description' => 'Purchase from Licensing Store'
        ])
    ])
    ->setRedirectUrls(new \PayPal\Api\RedirectUrls([
        'return_url' => 'http://yourwebsite.com/store/success.php',
        'cancel_url' => 'http://yourwebsite.com/store/cancel.php'
    ]));

try {
    // Create the payment
    $payment->create($apiContext);
    $approvalUrl = $payment->getApprovalLink(); // Redirect to PayPal for approval
    header("Location: " . $approvalUrl);
    exit();
} catch (Exception $e) {
    die($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="index.php">Licensing Store</a>
    </nav>

    <div class="container mt-4">
        <h2>Checkout</h2>
        <h4>Order Summary</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Total: $<?php echo number_format($totalAmount, 2); ?></h3>

        <form method="POST">
            <!-- Submit to PayPal for payment -->
            <button type="submit" class="btn btn-success">Proceed to PayPal</button>
        </form>
    </div>

</body>
</html>
