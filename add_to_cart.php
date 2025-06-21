<?php
session_start();
require_once 'db_connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to add items to your cart.'
    ]);
    exit;
}

// Validate input
if (!isset($_POST['book_id']) || !isset($_POST['book_img']) || !isset($_POST['book_title']) || !isset($_POST['book_price'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request. Missing book information.',
        'received_data' => $_POST // Debugging output
    ]);
    exit;
}

$bookId = (int)$_POST['book_id'];
$bookImg = htmlspecialchars($_POST['book_img']);
$bookTitle = htmlspecialchars($_POST['book_title']);
$bookPrice = (float)$_POST['book_price'];
$userId = (int)$_SESSION['user_id'];

try {
    // Debug: Check if we have a valid database connection
    if (!$pdo) {
        throw new Exception("Database connection not established");
    }

    // Check if user has an active cart
    $stmt = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    if (!$stmt->execute([$userId])) {
        throw new Exception("Failed to execute cart query");
    }
    $cart = $stmt->fetch();

    if (!$cart) {
        // Create new cart if none exists
        $stmt = $pdo->prepare("INSERT INTO carts (user_id) VALUES (?)");
        if (!$stmt->execute([$userId])) {
            throw new Exception("Failed to create new cart");
        }
        $cartId = $pdo->lastInsertId();
    } else {
        $cartId = $cart['cart_id'];
    }

    // Check if book exists in database (validation)
    $stmt = $pdo->prepare("SELECT book_id FROM books WHERE book_id = ?");
    if (!$stmt->execute([$bookId])) {
        throw new Exception("Failed to verify book existence");
    }
    if (!$stmt->fetch()) {
        throw new Exception("Book does not exist in database");
    }

    // Check if book already exists in cart
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND book_id = ?");
    if (!$stmt->execute([$cartId, $bookId])) {
        throw new Exception("Failed to check for existing cart item");
    }
    $existingItem = $stmt->fetch();

    if ($existingItem) {
        // Update quantity if item exists
        $newQuantity = $existingItem['quantity'] + 1;
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
        if (!$stmt->execute([$newQuantity, $existingItem['cart_item_id']])) {
            throw new Exception("Failed to update cart item quantity");
        }
    } else {
        // Add new item to cart
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, book_id, book_img, quantity) VALUES (?, ?, ?, 1)");
        if (!$stmt->execute([$cartId, $bookId, $bookImg])) {
            throw new Exception("Failed to add new item to cart");
        }
    }

    // Get updated cart count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM cart_items WHERE cart_id = ?");
    if (!$stmt->execute([$cartId])) {
        throw new Exception("Failed to get updated cart count");
    }
    $count = $stmt->fetch()['count'];

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Item added to cart successfully!',
        'cart_count' => $count,
        'debug' => [
            'user_id' => $userId,
            'cart_id' => $cartId,
            'book_id' => $bookId
        ]
    ]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred.',
        'error_details' => $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
} catch (Exception $e) {
    error_log("Application error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_details' => 'Application error'
    ]);
}
?>