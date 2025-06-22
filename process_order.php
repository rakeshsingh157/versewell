<?php
// process_order.php
// This script handles the checkout form submission, processes the order,
// saves it to the database, and clears the user's cart.

session_start(); // Start the session to access user_id
require 'db.php'; // Include the database connection file

header('Content-Type: application/json'); // Set header to return JSON response

$response = [
    'success' => false,
    'message' => ''
];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Please log in to place an order.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize shipping details - CORRECTED VARIABLE NAMES
    $full_name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? '')); // Corrected: was 'Email'
    $address = htmlspecialchars(trim($_POST['address'] ?? '')); // Corrected: was 'Address'
    $city = htmlspecialchars(trim($_POST['city'] ?? '')); // Corrected: was 'City'
    $state = htmlspecialchars(trim($_POST['state'] ?? ''));
    $country = htmlspecialchars(trim($_POST['country'] ?? ''));
    $postal_code = htmlspecialchars(trim($_POST['zip'] ?? '')); // Corrected: was 'zip'
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));

    // Collect and sanitize payment details
    $payment_method = htmlspecialchars(trim($_POST['payment_method'] ?? '')); // Corrected: was 'payment-method'
    $card_number_last4 = '';
    $upi_id = '';

    if ($payment_method === 'card') {
        $card_number = htmlspecialchars(trim($_POST['card_number'] ?? '')); // Corrected: was 'card-number'
        // Store only the last 4 digits for security
        $card_number_last4 = substr($card_number, -4);
    } elseif ($payment_method === 'upi') {
        $upi_id = htmlspecialchars(trim($_POST['upi_id'] ?? '')); // Corrected: was 'upi-id'
    }

    try {
        // Start a database transaction for atomicity
        $pdo->beginTransaction();

        // 1. Fetch current cart items for the user
        $stmt_cart = $pdo->prepare("SELECT cart_id, book_title, price, image_url FROM cart WHERE user_id = ?");
        $stmt_cart->execute([$user_id]);
        $cart_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cart_items)) {
            $response['message'] = 'Your cart is empty. Please add items before checking out.';
            $pdo->rollBack(); // Rollback if cart is empty
            echo json_encode($response);
            exit();
        }

        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += (float)$item['price'];
        }

        // 2. Insert into 'orders' table
        $stmt_order = $pdo->prepare("
            INSERT INTO orders (user_id, full_name, email, address, city, state, country, postal_code, phone, payment_method, card_number_last4, upi_id, total_amount)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt_order->execute([
            $user_id, $full_name, $email, $address, $city, $state, $country, $postal_code, $phone,
            $payment_method, $card_number_last4, $upi_id, $total_amount
        ]);
        $order_id = $pdo->lastInsertId(); // Get the ID of the newly inserted order

        // 3. Insert into 'order_items' table for each item in the cart
        $stmt_order_item = $pdo->prepare("
            INSERT INTO order_items (order_id, book_title, price, image_url)
            VALUES (?, ?, ?, ?)
        ");
        foreach ($cart_items as $item) {
            $stmt_order_item->execute([
                $order_id, $item['book_title'], (float)$item['price'], $item['image_url']
            ]);
        }

        // 4. Clear the user's cart after successful order placement
        $stmt_clear_cart = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt_clear_cart->execute([$user_id]);

        // Commit the transaction if all operations were successful
        $pdo->commit();

        $response['success'] = true;
        $response['message'] = 'Order placed successfully!';
        // Optionally, you might want to return the order_id for redirection to an order success page
        // $response['order_id'] = $order_id; 

    } catch (PDOException $e) {
        // Rollback the transaction on any error
        $pdo->rollBack();
        $response['message'] = 'Order processing failed: ' . $e->getMessage();
        error_log('Order processing PDO error: ' . $e->getMessage()); // Log detailed error
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
