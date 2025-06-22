<?php
// Get book details from URL parameters
$title = isset($_GET['title']) ? urldecode($_GET['title']) : 'Unknown Book';
$author = isset($_GET['author']) ? urldecode($_GET['author']) : 'Unknown Author';
$price = isset($_GET['price']) ? urldecode($_GET['price']) : 'N/A';
$image = isset($_GET['image']) ? urldecode($_GET['image']) : 'default.jpg';
$description = isset($_GET['description']) ? urldecode($_GET['description']) : 'No description available.';

// Include db.php for database connection, if needed for other PHP logic here, though not directly for current snippet
require_once 'db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - VerseWell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: var(--background); /* Use CSS variable */
            color: var(--text-color); /* Use CSS variable */
        }
        .book-container {
            max-width: 1000px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            background: var(--heading-background); /* Use CSS variable */
            padding: 2rem;
            border-radius: .5rem;
            box-shadow: var(--box-shadow); /* Use CSS variable */
        }
        .book-image {
            flex: 1 1 300px;
        }
        .book-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
            max-height: 500px;
        }
        .book-info {
            flex: 1 1 500px;
        }
        .book-info h1 {
            font-size: 2.5rem;
            color: var(--text-color); /* Use CSS variable */
            margin-top: 0;
        }
        .book-info .author {
            font-size: 1.5rem;
            color: var(--light-color); /* Use CSS variable */
            padding: 1rem 0;
        }
        .book-info .price {
            font-size: 2rem;
            color: var(--orange); /* Use CSS variable */
            padding: 1rem 0;
        }
        .book-info .description {
            font-size: 1.2rem;
            color: var(--text-color); /* Use CSS variable */
            padding: 1rem 0;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            margin-top: 1rem;
            padding: 1rem 3rem;
            background: var(--orange); /* Use CSS variable */
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: .5rem;
            text-decoration: none;
        }
        .btn:hover {
            background: var(--dark-color); /* Use CSS variable */
        }
        /* New style for the back button */
        .back-btn {
            background: #6c757d; /* A neutral grey color */
            margin-right: 1rem; /* Space between buttons */
        }
        .back-btn:hover {
            background: #5a6268; /* Darker grey on hover */
        }

        /* Custom Message Modal Styling */
        #message-modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--background); /* Use background variable for modal */
            border: 2px solid var(--orange);
            padding: 20px;
            border-radius: 8px; /* Match existing border-radius style */
            box-shadow: var(--box-shadow); /* Use box-shadow variable */
            z-index: 1000;
            text-align: center;
            max-width: 300px;
            width: 90%; /* Responsive width */
            color: var(--text-color); /* Ensure text color changes with theme */
        }
        #message-modal h3 {
            color: var(--text-color); /* Use text-color variable */
            margin-bottom: 10px;
        }
        #message-modal p {
            color: var(--text-color); /* Use text-color variable */
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
    <div class="book-container">
        <div class="book-image">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>">
        </div>
        <div class="book-info">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <p class="author">by <?php echo htmlspecialchars($author); ?></p>
            <div class="price"><?php echo htmlspecialchars($price); ?></div>
            <p class="description"><?php echo htmlspecialchars($description); ?></p>
            <!-- Back Button -->
            <button class="btn back-btn" onclick="history.back()">Back</button>
            <a href="#" class="btn add-to-cart" 
               data-title="<?php echo htmlspecialchars($title); ?>" 
               data-price="<?php echo htmlspecialchars($price); ?>" 
               data-img="<?php echo htmlspecialchars($image); ?>">
                Add to Cart
            </a>
        </div>
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

        // Add to cart functionality (now using AJAX to database)
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault(); // Prevent the default anchor link behavior

                const title = this.getAttribute('data-title');
                const priceRaw = this.getAttribute('data-price');
                const img = this.getAttribute('data-img');

                // Clean the price: remove any character that is not a digit or a dot.
                // This ensures that prices like "₹1,503" become "1503" or "₹1,084.365" become "1084.365"
                const price = priceRaw.replace(/[^0-9.]/g, ''); 

                // Create FormData object to send data. This is similar to a form submission.
                const formData = new FormData();
                formData.append('title', title);
                formData.append('price', price);
                formData.append('img', img);

                try {
                    // Send an asynchronous POST request to add_to_cart.php
                    const response = await fetch('add_to_cart.php', {
                        method: 'POST', // HTTP method
                        body: formData // The data to send
                    });

                    // Check if the HTTP response was successful (status code 200-299)
                    if (!response.ok) {
                        // If not successful, throw an error to be caught by the catch block
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    // Parse the JSON response from the PHP script
                    const result = await response.json(); 

                    // Display a message to the user based on the success status from the PHP response
                    if (result.success) {
                        displayMessageModal(result.message, true); // Show success message
                    } else {
                        displayMessageModal(result.message, false); // Show error message from PHP
                    }
                } catch (error) {
                    // Catch any network errors or errors thrown from the fetch response check
                    console.error('Error adding to cart:', error);
                    // Display a generic error message to the user
                    displayMessageModal('An unexpected error occurred while adding to cart. Please try again.', false);
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
