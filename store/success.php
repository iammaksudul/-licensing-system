<?php
session_start();
require '../vendor/autoload.php'; // PayPal SDK

// PayPal Configuration (Use your actual credentials here)
$clientId = 'YOUR_PAYPAL_CLIENT_ID';
$secret = 'YOUR_PAYPAL_SECRET';
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential($clientId, $secret)
);

// Check if the cart is empty or not
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Process the PayPal payment confirmation
if (isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
    $paymentId = $_GET['paymentId'];
    $payerId = $_GET['PayerID'];

    // Retrieve the payment from PayPal
    $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
    
    // Execute the payment
    $execution = new \PayPal\Api\PaymentExecution();
    $execution->setPayerId($payerId);

    try {
        // Execute the payment
        $payment->execute($execution, $apiContext);

        // Clear the cart after successful payment
        $_SESSION['cart'] = [];

        // Optional: Save order details to your database (Order ID, Product info, User info)

        // Display success message
        $message = "Payment Successful! Your order is now complete.";
    } catch (Exception $e) {
        // If there was an error, show failure message
        $message = "Payment failed! Please try again.";
    }
} else {
    // In case paymentId or PayerID is not present in the URL
    $message = "Payment failed! Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success | Licensing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="index.php">Licensing Store</a>
    </nav>

    <div class="container mt-4">
        <?php if ($message): ?>
            <h2 class="text-success"><?php echo $message; ?></h2>
        <?php endif; ?>
        <p>Your order has been successfully placed. A confirmation email has been sent to you with all the details of your purchase.</p>
        <a href="index.php" class="btn btn-primary">Back to Store</a>
    </div>

</body>
</html>
