<?php
// remove_from_cart.php
// This script handles AJAX requests to remove items from the user's cart in the database.

session_start(); // Start the session to access user_id
require 'db.php'; // Include the database connection file

header('Content-Type: application/json'); // Set header to return JSON response

$response = [
    'success' => false,
    'message' => ''
];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the request method is POST and cart_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    try {
        // Prepare the SQL DELETE statement
        // Ensure the item belongs to the logged-in user for security
        $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");

        // Execute the statement
        $stmt->execute([$cart_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Item removed from cart successfully!';
        } else {
            $response['message'] = 'Failed to remove item or item not found in your cart.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
        error_log('Remove from cart error: ' . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>
