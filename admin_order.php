<?php
session_start();
require 'db.php'; // Include your database connection file

// Simple authentication check for admin access
// You might want a more robust admin authentication system
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // For demonstration, let's assume 'admin' is a special user or a flag is set in their session
    // In a real application, you'd check user roles from the database.
    // For now, if the user ID is 1 (as an example admin ID), grant access.
    // Replace this with your actual admin role check.
    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 1) { // Example: User ID 1 is admin
        header('Location: login.php'); // Redirect to login page if not authorized
        exit();
    }
    // If user_id is 1, set is_admin to true for this session
    $_SESSION['is_admin'] = true;
}

$orders = [];
$error_message = '';

try {
    // Fetch all orders from the database, ordered by most recent
    $stmt = $pdo->prepare("SELECT * FROM orders ORDER BY order_date DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = 'Failed to load orders: ' . $e->getMessage();
    error_log('Admin orders loading error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - All Orders</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        /* General styling for admin page */
        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }
        .admin-container {
            max-width: 1200px;
            margin: 40px auto;
            background: var(--heading-background);
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--box-shadow);
        }
        h1 {
            color: var(--text-color);
            text-align: center;
            margin-bottom: 30px;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: var(--search-background); /* Lighter background for table */
            border-radius: 8px;
            overflow: hidden; /* Ensures rounded corners apply to content */
        }
        .order-table th, .order-table td {
            padding: 12px 15px;
            border: 1px solid var(--light-border);
            text-align: left;
            color: var(--text-color);
        }
        .order-table th {
            background-color: var(--dark-color); /* Darker header background */
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }
        .order-table tr:nth-child(even) {
            background-color: var(--background); /* Alternate row background */
        }
        .order-table tr:hover {
            background-color: var(--hover-background); /* Hover effect */
        }
        .order-items-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .order-items-list li {
            margin-bottom: 5px;
            font-size: 0.9em;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 20px;
        }
        .no-orders {
            text-align: center;
            margin-top: 20px;
            color: var(--text-color);
        }
        .logout-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            width: fit-content;
        }
        .logout-button:hover {
            background: var(--dark-color);
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .admin-container {
                margin: 20px;
                padding: 15px;
            }
            .order-table, .order-table thead, .order-table tbody, .order-table th, .order-table td, .order-table tr {
                display: block;
            }
            .order-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            .order-table tr {
                margin-bottom: 15px;
                border: 1px solid var(--light-border);
                border-radius: 8px;
                overflow: hidden;
            }
            .order-table td {
                border: none;
                border-bottom: 1px solid var(--light-border);
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            .order-table td:before {
                position: absolute;
                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: var(--text-color);
            }
            /* Label the data */
            .order-table td:nth-of-type(1):before { content: "Order ID:"; }
            .order-table td:nth-of-type(2):before { content: "User ID:"; }
            .order-table td:nth-of-type(3):before { content: "Customer Name:"; }
            .order-table td:nth-of-type(4):before { content: "Email:"; }
            .order-table td:nth-of-type(5):before { content: "Address:"; }
            .order-table td:nth-of-type(6):before { content: "City:"; }
            .order-table td:nth-of-type(7):before { content: "State:"; }
            .order-table td:nth-of-type(8):before { content: "Country:"; }
            .order-table td:nth-of-type(9):before { content: "ZIP:"; }
            .order-table td:nth-of-type(10):before { content: "Phone:"; }
            .order-table td:nth-of-type(11):before { content: "Payment Method:"; }
            .order-table td:nth-of-type(12):before { content: "Last 4 Digits:"; }
            .order-table td:nth-of-type(13):before { content: "UPI ID:"; }
            .order-table td:nth-of-type(14):before { content: "Total Amount:"; }
            .order-table td:nth-of-type(15):before { content: "Order Date:"; }
            .order-table td:nth-of-type(16):before { content: "Items:"; }

            /* Ensure items list is still readable */
            .order-items-list {
                padding-left: 0;
            }
            .order-items-list li {
                white-space: normal;
                word-wrap: break-word;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>All Customer Orders</h1>
        <a href="logout.php" class="logout-button">Logout</a>

        <?php if ($error_message): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php elseif (empty($orders)): ?>
            <p class="no-orders">No orders found.</p>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>ZIP</th>
                            <th>Phone</th>
                            <th>Payment Method</th>
                            <th>Card Last 4</th>
                            <th>UPI ID</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Order Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['Email']); ?></td>
                                <td><?php echo htmlspecialchars($order['Address']); ?></td>
                                <td><?php htmlspecialchars($order['City']); ?></td>
                                <td><?php htmlspecialchars($order['state']); ?></td>
                                <td><?php htmlspecialchars($order['country']); ?></td>
                                <td><?php echo htmlspecialchars($order['postal_code']); ?></td>
                                <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                <td><?php echo htmlspecialchars($order['card_number_last4'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($order['upi_id'] ?: 'N/A'); ?></td>
                                <td>₹<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td>
                                    <ul class="order-items-list">
                                        <?php
                                        // Fetch order items for the current order
                                        $order_id = $order['order_id'];
                                        $stmt_items = $pdo->prepare("SELECT book_title, price FROM order_items WHERE order_id = ?");
                                        $stmt_items->execute([$order_id]);
                                        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
                                        if (!empty($items)) {
                                            foreach ($items as $item) {
                                                echo '<li>' . htmlspecialchars($item['book_title']) . ' (₹' . htmlspecialchars(number_format($item['price'], 2)) . ')</li>';
                                            }
                                        } else {
                                            echo '<li>No items found</li>';
                                        }
                                        ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
