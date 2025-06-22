<?php
// add_to_cart.php
// This script handles AJAX requests to add books to the user's cart in the database.

session_start(); // Start the session to access user_id
require 'db.php'; // Include the database connection file

header('Content-Type: application/json'); // Set header to return JSON response

$response = [
    'success' => false,
    'message' => ''
];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Please log in to add items to your cart.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the POST request
    $book_title = $_POST['title'] ?? '';
    $price = $_POST['price'] ?? '';
    $image_url = $_POST['img'] ?? '';

    // Validate and sanitize input
    // Ensure price is a valid number (e.g., remove currency symbols or commas if present)
    $price = preg_replace('/[^\d.]/', '', $price); // Remove all non-numeric characters except dot

    if (empty($book_title) || empty($price) || empty($image_url)) {
        $response['message'] = 'Missing required book information.';
        echo json_encode($response);
        exit();
    }

    // Convert price to float for database insertion if it's not already
    $price = (float)$price;

    try {
        // Prepare the SQL INSERT statement
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, book_title, price, image_url) VALUES (?, ?, ?, ?)");

        // Execute the statement with the collected data
        $stmt->execute([$user_id, $book_title, $price, $image_url]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Book added to cart successfully!';
        } else {
            $response['message'] = 'Failed to add book to cart. No rows affected.';
        }
    } catch (PDOException $e) {
        // Handle database errors
        $response['message'] = 'Database error: ' . $e->getMessage();
        error_log('Cart insertion error: ' . $e->getMessage()); // Log the error for debugging
    }
} else {
    // If not a POST request
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response); // Output the JSON response
?>
