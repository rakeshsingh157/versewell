<?php
session_start(); // Start the session to access user_id and other session variables
require 'db.php'; // Include the database connection file

$cart_items = []; // Initialize an empty array for cart items
$total_amount = 0; // Initialize total amount
$error_message = ''; // Initialize error message

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $error_message = 'Please log in to proceed to checkout.';
} else {
    $user_id = $_SESSION['user_id'];
    try {
        // Fetch cart items for the logged-in user from the database
        $stmt = $pdo->prepare("SELECT cart_id, book_title, price, image_url FROM cart WHERE user_id = ? ORDER BY added_at DESC");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate total amount
        foreach ($cart_items as $item) {
            $total_amount += (float)$item['price'];
        }

        if (empty($cart_items)) {
            $error_message = 'Your cart is empty. Please add items before checking out.';
        }

    } catch (PDOException $e) {
        $error_message = 'Failed to load cart items: ' . $e->getMessage();
        error_log('Checkout cart loading error: ' . $e->getMessage()); // Log the error
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Versewell</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        /* Existing CSS from your original file - converted to use variables */
        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }
        .checkout-container {
            max-width: 600px;
            margin: 40px auto;
            background: var(--heading-background);
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--box-shadow);
        }
        .checkout-section { margin-bottom: 30px; }
        .checkout-section h2 {
            margin-bottom: 15px;
            color: var(--text-color);
        }
        .cart-summary {
            border: var(--border);
            padding: 15px;
            border-radius: 6px;
        }
        .cart-item { display: flex; align-items: center; margin-bottom: 10px; }
        .cart-item img {
            width: 50px;
            height: 70px;
            object-fit: cover;
            margin-right: 15px;
            border: 1px solid var(--light-border); /* Use light-border */
        }
        .cart-item-title {
            flex: 1;
            color: var(--text-color);
        }
        .cart-item-price {
            font-weight: bold;
            color: var(--orange); /* Price color */
        }
        .form-group { margin-bottom: 15px;  text-transform: lowercase; }
        label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-color);
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: var(--border);
            border-radius: 4px;
            background: var(--search-background); /* Input background */
            color: var(--text-color); /* Input text color */
        }
        .checkout-btn {
            background: var(--orange);
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .checkout-btn:hover {
            background: var(--dark-color);
        }
        .back-to-cart-btn {
            margin-bottom:20px;
            background: var(--light-border); /* A neutral background */
            color: var(--text-color);
            border:var(--border);
            padding:8px 18px;
            border-radius:4px;
            cursor:pointer;
            font-size:15px;
            transition: background-color 0.3s ease;
        }
        .back-to-cart-btn:hover {
            background: var(--hover-background);
        }

        /* Custom Message Modal Styling (minimal, to avoid conflicts with existing CSS) */
        #message-modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--background);
            border: 2px solid var(--orange);
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--box-shadow);
            z-index: 1000;
            text-align: center;
            max-width: 300px;
            width: 90%;
            color: var(--text-color);
        }
        #message-modal h3 {
            color: var(--text-color);
            margin-bottom: 10px;
        }
        #message-modal p {
            color: var(--text-color);
            margin-bottom: 15px;
        }
        #message-modal .close-btn {
            background: var(--orange);
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #message-modal .close-btn:hover {
            background: var(--dark-color);
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <button onclick="window.location.href='cart.php'" class="back-to-cart-btn">
            &larr; Back to Cart
        </button>
        <h1>Checkout</h1>

        <div class="checkout-section">
            <h2>Order Summary</h2>
            <div class="cart-summary" id="cart-summary">
                <?php if ($error_message): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
                <?php elseif (empty($cart_items)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['book_title']); ?>">
                            <div class="cart-item-title"><?php echo htmlspecialchars($item['book_title']); ?></div>
                            <div class="cart-item-price">₹<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></div>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div style="text-align:right;font-weight:bold;">Total: ₹<?php echo htmlspecialchars(number_format($total_amount, 2)); ?></div>
                <?php endif; ?>
            </div>
        </div>

        <form id="checkout-form">
            <div class="checkout-section">
                <h2>Shipping Details</h2>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required
                           value="<?php echo isset($_SESSION['first_name']) && isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) : ''; ?>">
                </div>
                <div class="form-group" style=" text-transform: lowercase;">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required
                           value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required
                           value="<?php echo isset($_SESSION['address']) ? htmlspecialchars($_SESSION['address']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required
                           value="<?php echo isset($_SESSION['city']) ? htmlspecialchars($_SESSION['city']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" required
                           value="<?php echo isset($_SESSION['state']) ? htmlspecialchars($_SESSION['state']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" required
                           value="<?php echo isset($_SESSION['country']) ? htmlspecialchars($_SESSION['country']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="zip">ZIP Code</label>
                    <input type="text" id="zip" name="zip" required
                           value="<?php echo isset($_SESSION['postal_code']) ? htmlspecialchars($_SESSION['postal_code']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required
                           value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''; ?>">
                </div>
            </div>

            <div class="checkout-section">
                <h2>Payment Method</h2>
                <div class="form-group">
                    <select id="payment-method" name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="cod">Cash on Delivery</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="upi">UPI</option>
                        <option value="netbanking">Net Banking</option>
                    </select>
                </div>
                <div id="payment-details"></div>
            </div>

            <button type="submit" class="checkout-btn" <?php echo empty($cart_items) || $error_message ? 'disabled' : ''; ?>>Place Order</button>
        </form>
    </div>

    <!-- Custom Message Modal HTML -->
    <div id="message-modal">
        <h3 id="message-modal-title">Notification</h3>
        <p id="message-modal-content"></p>
        <button class="close-btn" onclick="closeMessageModal()">Close</button>
    </div>

    <script>
        // Function to display the custom message modal
        function displayMessageModal(message, isSuccess = true) {
            const modal = document.getElementById('message-modal');
            const modalTitle = document.getElementById('message-modal-title');
            const modalContent = document.getElementById('message-modal-content');

            modalTitle.innerText = isSuccess ? "Success" : "Error";
            modalContent.innerText = message;
            modal.style.display = 'block'; // Show the modal
        }

        // Function to close the custom message modal
        function closeMessageModal() {
            document.getElementById('message-modal').style.display = 'none'; // Hide the modal
        }

        // Show payment details fields based on method
        document.getElementById('payment-method').addEventListener('change', function() {
            const details = document.getElementById('payment-details');
            if (this.value === 'card') {
                details.innerHTML = `
                    <div class="form-group">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" name="card_number" required pattern="[0-9]{13,16}" title="Enter a valid card number (13-16 digits)">
                    </div>
                    <div class="form-group">
                        <label for="card-expiry">Expiry Date</label>
                        <input type="text" id="card-expiry" name="card_expiry" placeholder="MM/YY" required pattern="(0[1-9]|1[0-2])\\/[0-9]{2}" title="Enter expiry in MM/YY format">
                    </div>
                    <div class="form-group">
                        <label for="card-cvv">CVV</label>
                        <input type="password" id="card-cvv" name="card_cvv" required pattern="[0-9]{3,4}" title="Enter a 3 or 4 digit CVV">
                    </div>
                `;
            } else if (this.value === 'upi') {
                details.innerHTML = `
                    <div class="form-group">
                        <label for="upi-id">UPI ID</label>
                        <input type="text" id="upi-id" name="upi_id" required pattern="[a-zA-Z0-9.\\-]+@[a-zA-Z0-9.\\-]+" title="Enter a valid UPI ID (e.g., example@bank)">
                    </div>
                `;
            } else {
                details.innerHTML = '';
            }
        });

        // Handle form submission via AJAX
        document.getElementById('checkout-form').addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevent default form submission

            const form = e.target;
            const formData = new FormData(form); // Get all form data

            try {
                // Send the form data to process_order.php
                const response = await fetch('process_order.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    displayMessageModal(result.message, true);
                    // Redirect to home page after a short delay to allow user to see message
                    setTimeout(() => {
                        window.location.href = "html.php"; // Assuming html.php is your main page
                    }, 2000);
                } else {
                    displayMessageModal(result.message, false);
                }
            } catch (error) {
                console.error('Error processing order:', error);
                displayMessageModal('An unexpected error occurred. Please try again.', false);
            }
        });

        // Dark Mode Functionality
        document.addEventListener('DOMContentLoaded', () => {
            const htmlElement = document.documentElement; // This is the <html> tag

            // Check for saved theme preference in local storage
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                htmlElement.setAttribute('data-theme', savedTheme);
            } else {
                // Default to light theme if no preference is saved
                htmlElement.setAttribute('data-theme', 'light');
            }
        });
    </script>
</body>
</html>
