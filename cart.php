<?php
session_start(); // Start the session to access user_id
require 'db.php'; // Include the database connection file

$cart_items = []; // Initialize an empty array for cart items
$error_message = ''; // Initialize an empty error message

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    try {
        // Prepare SQL to fetch cart items for the logged-in user
        // Using ORDER BY added to sort items by when they were added
        $stmt = $pdo->prepare("SELECT cart_id, book_title, price, image_url FROM cart WHERE user_id = ? ORDER BY added_at DESC");
        $stmt->execute([$user_id]);
        $cart_items = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error_message = 'Failed to load cart items: ' . $e->getMessage();
        error_log('Cart loading error: ' . $e->getMessage()); // Log error for debugging
    }
} else {
    $error_message = 'Please log in to view your cart.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | Versewell</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        /* Existing CSS from your original file - converted to use variables */
        body { 
            background: var(--background); 
            color: var(--text-color);
        }
        .cart-container {
            max-width: 600px;
            margin: 40px auto;
            background: var(--heading-background); /* Use theme variable */
            border-radius: 8px;
            box-shadow: var(--box-shadow); /* Use theme variable */
            padding: 2rem;
        }
        .cart-container h2 {
            text-align: center;
            color: var(--dark-color); /* Use theme variable */
            margin-bottom: 2rem;
        }
        .cart-list {
            list-style: none;
            padding: 0;
        }
        .cart-list li {
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--light-border); /* Use theme variable */
            padding: 1rem 0;
        }
        .cart-list img {
            width: 60px;
            height: 90px;
            object-fit: cover;
            margin-right: 1rem;
            border-radius: 4px;
            border: 1px solid var(--light-border); /* Use theme variable */
        }
        .cart-title {
            flex: 1;
            font-size: 1.2rem;
            color: var(--black); /* Use theme variable */
        }
        .cart-price {
            color: var(--orange); /* Use theme variable */
            font-weight: bold;
            margin-right: 1rem;
        }
        .remove-btn {
            background: var(--orange); /* Use theme variable */
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.3rem 0.7rem;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Added transition */
        }
        .remove-btn:hover { /* Added hover effect */
            background-color: var(--dark-color); /* Use theme variable */
        }
        .empty-cart {
            text-align: center;
            color: var(--light-color); /* Use theme variable */
            margin: 2rem 0;
        }
        .checkout-btn {
            display: block;
            width: 100%;
            background: var(--dark-color); /* Use theme variable */
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 1rem;
            font-size: 1.1rem;
            margin-top: 2rem;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Added transition */
        }
        .checkout-btn:hover { /* Added hover effect */
            background-color: var(--orange); /* Use theme variable */
        }
        .back-button {
            margin-bottom: 1.5rem;
            background: var(--orange); /* Use theme variable */
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.7rem 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: var(--dark-color); /* Darker orange on hover */
        }

        /* Custom Message Modal Styling (minimal, to avoid conflicts) */
        #message-modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--background); /* Use background variable for modal */
            border: 2px solid var(--orange); /* Border color from your existing theme */
            padding: 20px;
            border-radius: 8px; /* Match existing border-radius */
            box-shadow: var(--box-shadow); /* Use box-shadow variable */
            z-index: 1000;
            text-align: center;
            max-width: 300px;
            width: 90%;
            color: var(--text-color); /* Ensure text color changes with theme */
        }
        #message-modal h3 {
            color: var(--text-color); /* Dark color */
            margin-bottom: 10px;
        }
        #message-modal p {
            color: var(--text-color); /* Slightly lighter text */
            margin-bottom: 15px;
        }
        #message-modal .close-btn {
            background: var(--orange); /* Theme orange color */
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #message-modal .close-btn:hover {
            background: var(--dark-color); /* Darker orange on hover */
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <button onclick="window.location.href='html.php'" class="back-button">
            &#8592; Back to Main Page
        </button>
        <h2>Your Cart</h2>

        <?php if ($error_message): ?>
            <div class="empty-cart" style="display: block; color: red;"><?php echo htmlspecialchars($error_message); ?></div>
        <?php else: ?>
            <ul class="cart-list" id="cart-list">
                <?php if (empty($cart_items)): ?>
                    <li class="empty-cart" id="empty-cart" style="display: block;">Your cart is empty.</li>
                <?php else: ?>
                    <?php foreach ($cart_items as $item): ?>
                        <li>
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['book_title']); ?>">
                            <span class="cart-title"><?php echo htmlspecialchars($item['book_title']); ?></span>
                            <span class="cart-price">₹<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></span>
                            <button class="remove-btn" data-cart-id="<?php echo htmlspecialchars($item['cart_id']); ?>">Remove</button>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="empty-cart" id="empty-cart" style="display: <?php echo empty($cart_items) ? 'block' : 'none'; ?>;">Your cart is empty.</div>
        <?php endif; ?>

        <button id="proceed-checkout" class="checkout-btn" style="display: <?php echo empty($cart_items) || $error_message ? 'none' : 'block'; ?>;">Proceed to Checkout</button>
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

        // Handle remove button clicks
        document.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const cartId = this.dataset.cartId; // Get the cart_id from the data attribute

                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    try {
                        const formData = new FormData();
                        formData.append('cart_id', cartId);

                        const response = await fetch('remove_from_cart.php', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            displayMessageModal(result.message, true);
                            // Reload the page to reflect the updated cart state
                            location.reload(); 
                        } else {
                            displayMessageModal(result.message, false);
                        }
                    } catch (error) {
                        console.error('Error removing item:', error);
                        displayMessageModal('An error occurred while removing the item. Please try again.', false);
                    }
                }
            });
        });

        document.getElementById('proceed-checkout').addEventListener('click', function() {
            window.location.href = 'checkout.php';
        });

        // Initial check for empty cart on page load (PHP handles this now, but JS can also double-check)
        document.addEventListener('DOMContentLoaded', () => {
            const cartList = document.getElementById('cart-list');
            const emptyCartMessage = document.getElementById('empty-cart');
            const proceedCheckoutBtn = document.getElementById('proceed-checkout');

            // If the PHP rendered list is empty (no <li> elements except the placeholder), show empty message
            const hasItems = cartList.querySelectorAll('li:not(.empty-cart)').length > 0;
            if (!hasItems) {
                emptyCartMessage.style.display = 'block';
                proceedCheckoutBtn.style.display = 'none';
            } else {
                emptyCartMessage.style.display = 'none';
                proceedCheckoutBtn.style.display = 'block';
            }

            // Dark Mode Functionality
            const htmlElement = document.documentElement; // This is the <html> tag
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                htmlElement.setAttribute('data-theme', savedTheme);
            } else {
                htmlElement.setAttribute('data-theme', 'light');
            }
        });
    </script>
</body>
</html>
