<?php
// wishlist.php
// This page displays the user's wishlist items fetched from the database.

session_start(); // Start the session to access user_id
require 'db.php'; // Include the database connection file

$wishlist_items = []; // Initialize an empty array for wishlist items
$error_message = ''; // Initialize an empty error message

if (!isset($_SESSION['user_id'])) {
    $error_message = 'Please log in to view your wishlist.';
} else {
    $user_id = $_SESSION['user_id'];
    try {
        // Prepare SQL to fetch wishlist items for the logged-in user
        $stmt = $pdo->prepare("SELECT wishlist_id, book_title, price, image_url FROM wishlist WHERE user_id = ? ORDER BY added_at DESC");
        $stmt->execute([$user_id]);
        $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = 'Failed to load wishlist items: ' . $e->getMessage();
        error_log('Wishlist loading error: ' . $e->getMessage()); // Log error for debugging
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist | Versewell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Link to your existing style.css if you have one that applies universally -->
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        /* Existing CSS from your original files or general layout - converted to use variables */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background); /* Use CSS variable */
            color: var(--text-color); /* Use CSS variable */
        }
        .wishlist-container {
            max-width: 800px;
            margin: 40px auto;
            background: var(--heading-background); /* Use CSS variable */
            border-radius: 8px;
            box-shadow: var(--box-shadow); /* Use CSS variable */
            padding: 2rem;
        }
        .wishlist-container h2 {
            text-align: center;
            color: var(--dark-color); /* Use CSS variable */
            margin-bottom: 2rem;
        }
        .wishlist-list {
            list-style: none;
            padding: 0;
        }
        .wishlist-list li {
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--light-border); /* Use CSS variable */
            padding: 1rem 0;
        }
        .wishlist-list img {
            width: 60px;
            height: 90px;
            object-fit: cover;
            margin-right: 1rem;
            border-radius: 4px;
            border: 1px solid var(--light-border); /* Use CSS variable */
        }
        .wishlist-title {
            flex: 1;
            font-size: 1.2rem;
            color: var(--black); /* Use CSS variable */
        }
        .wishlist-price {
            color: var(--orange); /* Use CSS variable */
            font-weight: bold;
            margin-right: 1rem;
        }
        .remove-btn {
            background: var(--orange); /* Use CSS variable */
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 0.3rem 0.7rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .remove-btn:hover {
            background-color: var(--dark-color); /* Use CSS variable */
        }
        .empty-wishlist {
            text-align: center;
            color: var(--light-color); /* Use CSS variable */
            margin: 2rem 0;
        }
        .back-button {
            margin-bottom: 1.5rem;
            background: var(--orange); /* Use CSS variable */
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
            background: var(--dark-color); /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <div class="wishlist-container">
        <button onclick="window.location.href='html.php'" class="back-button">
            &#8592; Back to Main Page
        </button>
        <h2>Your Wishlist</h2>

        <?php if ($error_message): ?>
            <div class="empty-wishlist" style="display: block; color: red;"><?php echo htmlspecialchars($error_message); ?></div>
        <?php else: ?>
            <ul class="wishlist-list" id="wishlist-list">
                <?php if (empty($wishlist_items)): ?>
                    <li class="empty-wishlist" id="empty-wishlist" style="display: block;">Your wishlist is empty.</li>
                <?php else: ?>
                    <?php foreach ($wishlist_items as $item): ?>
                        <li>
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['book_title']); ?>">
                            <span class="wishlist-title"><?php echo htmlspecialchars($item['book_title']); ?></span>
                            <span class="wishlist-price">₹<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></span>
                            <button class="remove-btn" data-wishlist-id="<?php echo htmlspecialchars($item['wishlist_id']); ?>">Remove</button>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
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
                const wishlistId = this.dataset.wishlistId; // Get the wishlist_id from the data attribute

                if (confirm('Are you sure you want to remove this item from your wishlist?')) {
                    try {
                        const formData = new FormData();
                        formData.append('wishlist_id', wishlistId);

                        const response = await fetch('remove_from_wishlist.php', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            displayMessageModal(result.message, true);
                            // Reload the page to reflect the updated wishlist state
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
