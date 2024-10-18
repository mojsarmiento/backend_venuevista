<?php
require 'vendor/autoload.php'; // Load Stripe PHP library

// Set your Stripe secret key (use the secret key starting with sk_test_)
\Stripe\Stripe::setApiKey('sk_test_51Og4l4HmrYAdyFeAtb627FlmE7VlCoPoFrceHfXgeXuCi30RY746k6N1ipxbsnofMZvSlXae0WZl3hdfLDcATMPq00TJxR7wI7'); // Replace with your actual Stripe secret key

header('Content-Type: application/json');

// Optional: Allow CORS if your Flutter app is hosted on a different origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

try {
    // Retrieve the amount and currency from the request
    $input = json_decode(file_get_contents("php://input"), true);
    $amount = $input['amount'] ?? null; // Expecting amount in cents
    $currency = $input['currency'] ?? 'php'; // Default to PHP if not provided

    // Validate the amount
    if ($amount === null || $amount <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid amount']);
        exit;
    }

    // Create a PaymentIntent with the specified amount and currency
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => $currency,
        'payment_method_types' => ['card'],
    ]);

    // Respond with the client secret
    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret,
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle Stripe API errors
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    // Handle other errors
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
