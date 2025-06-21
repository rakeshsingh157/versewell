<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $action = $_POST['action'] ?? '';
    $quantity = max(1, intval($_POST['quantity'] ?? 1));
    
    // Get current cart
    $stmt = $pdo->prepare("SELECT ci.quantity 
                          FROM cart_items ci
                          JOIN carts c ON ci.cart_id = c.cart_id
                          WHERE c.user_id = ? AND ci.book_id = ?");
    $stmt->execute([$_SESSION['user_id'], $book_id]);
    $current = $stmt->fetch();
    
    if ($action == 'increase') {
        $new_quantity = ($current['quantity'] ?? 0) + 1;
    } 
    elseif ($action == 'decrease') {
        $new_quantity = max(1, ($current['quantity'] ?? 0) - 1);
    } 
    else {
        $new_quantity = $quantity;
    }
    
    // Update cart
    $stmt = $pdo->prepare("UPDATE cart_items ci
                          JOIN carts c ON ci.cart_id = c.cart_id
                          SET ci.quantity = ?
                          WHERE c.user_id = ? AND ci.book_id = ?");
    $stmt->execute([$new_quantity, $_SESSION['user_id'], $book_id]);
}

header("Location: cart.php");
exit();