<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['id'];

// Verify order belongs to user
$stmt = $pdo->prepare("SELECT o.* FROM orders o
                      WHERE o.order_id = ? AND o.user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: index.php");
    exit();
}

// Get order items
$stmt = $pdo->prepare("SELECT oi.*, b.title, b.image_url 
                      FROM order_items oi
                      JOIN books b ON oi.book_id = b.book_id
                      WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation - Versewell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .success-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .success-icon {
            color: #4CAF50;
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .success-title {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #333;
        }
        
        .order-details {
            margin: 30px 0;
            text-align: left;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 6px;
        }
        
        .order-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item img {
            width: 60px;
            height: 90px;
            object-fit: cover;
            margin-right: 15px;
        }
        
        .order-summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-weight: bold;
        }
        
        .btn {
            display: inline-block;
            background: #4a6fa5;
            color: #fff;
            padding: 12px 25px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 20px;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #3a5a80;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 class="success-title">Order Confirmed!</h1>
        <p>Thank you for your purchase. Your order has been placed successfully.</p>
        <p>Order ID: <strong>#<?php echo $order_id; ?></strong></p>
        
        <div class="order-details">
            <h3>Order Summary</h3>
            
            <?php foreach ($items as $item): ?>
                <div class="order-item">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <div style="flex: 1;">
                        <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <p>Price: ₹<?php echo number_format($item['unit_price'], 2); ?></p>
                    </div>
                    <div style="font-weight: bold;">
                        ₹<?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="order-summary">
                <p>Total: ₹<?php echo number_format($order['total_amount'], 2); ?></p>
                <p>Payment Method: <?php echo htmlspecialchars(ucfirst($order['payment_method'])); ?></p>
            </div>
        </div>
        
        <p>We've sent a confirmation email to <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        
        <a href="index.php" class="btn">Continue Shopping</a>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>