<?php
// add_to_wishlist.php
// This script handles AJAX requests to add books to the user's wishlist in the database.

session_start(); // Start the session to access user_id
require 'db.php'; // Include the database connection file

header('Content-Type: application/json'); // Set header to return JSON response

$response = [
    'success' => false,
    'message' => ''
];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Please log in to add items to your wishlist.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the POST request
    $book_title = $_POST['title'] ?? '';
    $price_raw = $_POST['price'] ?? '';
    $image_url = $_POST['img'] ?? '';

    // Sanitize the price: remove any non-numeric characters except dot
    $price = preg_replace('/[^\d.]/', '', $price_raw);
    $price = (float)$price; // Cast to float

    // Validate input
    if (empty($book_title) || $price <= 0 || empty($image_url)) {
        $response['message'] = 'Missing or invalid book information.';
        echo json_encode($response);
        exit();
    }

    try {
        // Check if the book is already in the wishlist for this user
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ? AND book_title = ?");
        $check_stmt->execute([$user_id, $book_title]);
        if ($check_stmt->fetchColumn() > 0) {
            $response['message'] = 'This book is already in your wishlist!';
            echo json_encode($response);
            exit();
        }

        // Prepare the SQL INSERT statement
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, book_title, price, image_url) VALUES (?, ?, ?, ?)");

        // Execute the statement
        $stmt->execute([$user_id, $book_title, $price, $image_url]);

        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Book added to wishlist successfully!';
        } else {
            $response['message'] = 'Failed to add book to wishlist.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
        error_log('Wishlist insertion error: ' . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
