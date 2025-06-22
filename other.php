<?php
session_start();

// Include db.php for database connection
require_once 'db.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['user_id'])) {
    $userFirstName = htmlspecialchars($_SESSION['first_name']);
    $userName = htmlspecialchars($_SESSION['username']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Books - Versewell</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <style>
        /* General Body and Container Styling */
        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: var(--heading-background);
            border-radius: 8px;
            box-shadow: var(--box-shadow);
        }

        h1 {
            text-align: center;
            color: var(--text-color);
            margin-bottom: 30px;
        }

        /* Search Bar Styling */
        .search-container-main {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .search-container-main input[type="search"] {
            width: 70%;
            max-width: 500px;
            padding: 12px 20px;
            border: var(--border);
            border-radius: 50px;
            background: var(--search-background);
            color: var(--text-color);
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .search-container-main input[type="search"]:focus {
            border-color: var(--orange);
        }

        /* Books Grid Styling */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }

        .book-card {
            background: var(--background);
            border: var(--border);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: transform 0.2s ease;
            display: flex;
            flex-direction: column;
        }

        .book-card:hover {
            transform: translateY(-5px);
        }

        .book-card img {
            width: 100%;
            height: 250px;
            object-fit: contain; /* Use contain to fit the image without cropping */
            border-bottom: var(--border);
            padding: 10px;
            background-color: var(--search-background); /* Lighter background for images */
        }

        .book-card .content {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-card h3 {
            font-size: 1.2rem;
            color: var(--orange);
            margin-bottom: 10px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .book-card .authors {
            font-size: 0.9rem;
            color: var(--light-color);
            margin-bottom: 10px;
        }

        .book-card .price {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--text-color);
            margin-bottom: 15px;
        }

        .book-card .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 1rem;
            transition: background-color 0.3s;
            margin-top: 5px;
        }

        .book-card .btn.add-to-wishlist { /* Specific style for wishlist button */
            background: var(--light-color); /* A slightly different color for distinction */
            color: var(--background);
        }

        .book-card .btn.add-to-wishlist:hover {
            background: var(--dark-color); /* Darker on hover */
            color: #fff;
        }

        .book-card .btn:hover {
            background: var(--dark-color);
        }

        /* Loading Indicator */
        #loading-indicator {
            text-align: center;
            padding: 20px;
            font-size: 1.2rem;
            color: var(--light-color);
            display: none; /* Hidden by default */
        }

        /* Back to Top Button */
        #back-to-top {
            display: none; /* Hidden by default */
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--orange);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: var(--box-shadow);
            transition: background-color 0.3s;
            z-index: 999;
        }

        #back-to-top:hover {
            background: var(--dark-color);
        }

        /* Book Detail Overlay/Modal */
        #book-detail-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1001;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #book-detail-content {
            background: var(--background);
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            width: 90%;
            box-shadow: var(--box-shadow);
            position: relative;
            display: flex;
            flex-wrap: wrap; /* Allows content to wrap on smaller screens */
            gap: 20px;
            max-height: 90vh; /* Limit height for scrollability */
            overflow-y: auto; /* Enable vertical scrolling if content overflows */
        }

        #close-detail-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--text-color);
            cursor: pointer;
            z-index: 10; /* Ensure it's above other content */
        }
        #close-detail-btn:hover {
            color: var(--orange);
        }

        .detail-image {
            flex: 1 1 250px; /* Flex-grow, flex-shrink, basis */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align image to top */
        }

        .detail-image img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: 5px;
            background-color: var(--search-background);
            padding: 10px;
        }

        .detail-info {
            flex: 2 1 400px; /* Takes more space, can shrink */
            color: var(--text-color);
            display: flex;
            flex-direction: column;
        }

        #detail-title {
            font-size: 2rem;
            color: var(--orange);
            margin-bottom: 10px;
        }

        #detail-authors {
            font-size: 1.1rem;
            color: var(--light-color);
            margin-bottom: 15px;
        }

        #detail-description {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1; /* Allows description to take available space */
        }

        .detail-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text-color);
            margin-bottom: 20px;
        }

        .add-to-cart-detail, .add-to-wishlist-detail, .view-pdf-detail { /* Added .add-to-wishlist-detail */
            display: block;
            width: 100%;
            padding: 12px 20px;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 1.1rem;
            transition: background-color 0.3s;
            margin-bottom: 15px; /* Spacing between buttons */
        }
        .add-to-wishlist-detail { /* Specific style for wishlist button in detail view */
            background: var(--light-color);
            color: var(--background);
        }
        .add-to-cart-detail:hover, .add-to-wishlist-detail:hover, .view-pdf-detail:hover { /* Updated hover */
            background: var(--dark-color);
            color: #fff; /* Ensure text color changes on hover */
        }
        .view-pdf-detail:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: var(--light-color); /* Grey out when disabled */
        }

        .detail-navigation {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 15px;
            width: 100%; /* Ensure buttons span full width */
        }

        .detail-navigation .btn {
            flex: 1; /* Share space equally */
            padding: 10px 15px;
            background: var(--light-border);
            color: var(--text-color);
            border: var(--border);
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s, color 0.3s;
        }

        .detail-navigation .btn:hover {
            background: var(--hover-background);
            color: var(--orange);
        }
        .detail-navigation .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Message Modal Styling (from existing html2.php) */
        #message-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--background);
            border: 2px solid var(--orange);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--box-shadow);
            z-index: 1002;
            text-align: center;
            max-width: 400px;
            width: 90%;
            color: var(--text-color);
        }

        #message-modal h3 {
            color: var(--text-color);
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        #message-modal p {
            color: var(--text-color);
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        #message-modal .close-btn {
            background: var(--orange);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        #message-modal .close-btn:hover {
            background: var(--dark-color);
        }
        /* Responsive adjustments for book detail modal */
        @media (max-width: 768px) {
            #book-detail-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .detail-image {
                flex: none; /* Reset flex for image */
                margin-bottom: 20px;
            }
            .detail-info {
                flex: none; /* Reset flex for info */
                align-items: center;
            }
            #detail-description {
                text-align: left; /* Keep description left-aligned */
            }
        }

        @media (max-width: 450px) {
            .search-container-main input[type="search"] {
                width: 90%;
            }
            .books-grid {
                grid-template-columns: 1fr; /* Single column on very small screens */
            }
            .book-card img {
                height: 200px; /* Adjust image height */
            }
            #message-modal {
                padding: 20px;
            }
            #message-modal h3 {
                font-size: 1.5rem;
            }
            #message-modal p {
                font-size: 1.0rem;
            }
        }

        .theme-toggle-button {
            background: none;
            border: none;
            font-size: 2.5rem; /* Match other icons */
            margin-left: 1.5rem;
            color: white; /* Inherit from theme */
            cursor: pointer;
            transition: color 0.2s linear;
        }

        .theme-toggle-button:hover {
            color: var(--orange); /* Match other icon hover effects */
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="header-1">
            <a href="html.php" class="logo"> <i class="fas fa-book"></i> versewell </a>
            <div class="search-container">
                <form action="" class="search-form">
                    <input type="search" name="search" placeholder="Search here..." id="header-search-box" autocomplete="off">
                    <label for="header-search-box" class="fas fa-search"></label>
                </form>
                <div class="search-results" id="search-results"></div>
            </div>

            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <a href="wishlist.php" class="fas fa-heart"></a>
                <a href="cart.php" class="fas fa-shopping-cart" id="cart-btn"></a> 
                <a href="profile.php"><div class="fas fa-user"></div></a>
                <a href="my acc.html" title="My Account"><i class=""></i></a>
                <button class="theme-toggle-button" id="theme-toggle">
                    <i class="fas fa-moon"></i> 
                </button>
                <div class="userinfo">
                    <?php if (isset($userFirstName) && isset($userName)): ?>
                        <p>Welcome,<br> <span><?php echo $userFirstName; ?></span></p>
                    <?php else: ?>
                        <p>Welcome, Guest</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="header-2">
            <nav class="navbar">
                <a href="html.php">home</a>
                <a href="html2.php">genres</a> <!-- Link to your existing genres page -->
                <a href="html4.php">reviews</a>
                <a href="html3.php">blogs</a>
            </nav>
        </div>
    </header>

    <div class="login-form-container">
        <div id="close-login-btn" class="fas fa-times"></div>
        <form action="">
            <h3>sign in</h3>
            <span>username</span>
            <input type="email" name="" class="box" placeholder="enter your email" id="">
            <span>password</span>
            <input type="password" name="" class="box" placeholder="enter your password" id="">
            <div class="checkbox">
                <input type="checkbox" name="" id="remember-me">
                <label for="remember-me"> remember me</label>
            </div>
            <input type="submit" value="sign in" class="btn">
            <p>forget password ? <a href="#">click here</a></p>
            <p>don't have an account ? <a href="#">create one</a></p>
        </form>
    </div>

    <div class="container">
        <h1>Explore More Books</h1>

        <div class="search-container-main">
            <input type="search" id="book-search-input" placeholder="Search for any book...">
        </div>

        <div class="books-grid" id="books-container">
            <!-- Books will be loaded here by JavaScript -->
        </div>

        <div id="loading-indicator">Loading more books...</div>
    </div>

    <!-- Book Detail Overlay/Modal -->
    <div id="book-detail-overlay" style="display:none;">
        <div id="book-detail-content">
            <button id="close-detail-btn" class="fas fa-times"></button>
            <div class="detail-image">
                <img id="detail-img" src="" alt="Book Cover">
            </div>
            <div class="detail-info">
                <h2 id="detail-title"></h2>
                <p id="detail-authors"></p>
                <p id="detail-description"></p>
                <div class="detail-price" id="detail-price"></div>
                <button class="btn add-to-cart-detail">Add to Cart</button>
                <button class="btn add-to-wishlist-detail">Add to Wishlist</button> <!-- NEW Wishlist Button in detail view -->
                <button class="btn view-pdf-detail" id="view-pdf-detail">View PDF</button> <!-- PDF Button -->
                <div class="detail-navigation">
                    <button id="prev-book-btn" class="btn">Previous Book</button>
                    <button id="next-book-btn" class="btn">Next Book</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="back-to-top" title="Go to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Custom Message Modal HTML (reused from other pages) -->
    <div id="message-modal">
        <h3 id="message-modal-title">Notification</h3>
        <p id="message-modal-content"></p>
        <button class="close-btn" onclick="closeMessageModal()">Close</button>
    </div>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script src="javascript.js"></script> <!-- Assuming this contains general site JS -->

    <script>
        // --- Helper Functions (reused from your existing code) ---
        function displayMessageModal(message, isSuccess = true) {
            const modal = document.getElementById('message-modal');
            const modalTitle = document.getElementById('message-modal-title');
            const modalContent = document.getElementById('message-modal-content');

            modalTitle.innerText = isSuccess ? "Success" : "Error";
            modalContent.innerText = message;
            modal.style.display = 'block';
        }

        function closeMessageModal() {
            document.getElementById('message-modal').style.display = 'none';
        }

        // --- Dark Mode Functionality (reused from your existing code) ---
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement;

            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                htmlElement.setAttribute('data-theme', savedTheme);
                if (savedTheme === 'dark') {
                    themeToggleBtn.querySelector('i').classList.replace('fa-moon', 'fa-sun');
                } else {
                    themeToggleBtn.querySelector('i').classList.replace('fa-sun', 'fa-moon');
                }
            } else {
                htmlElement.setAttribute('data-theme', 'light');
            }

            themeToggleBtn.addEventListener('click', () => {
                if (htmlElement.getAttribute('data-theme') === 'dark') {
                    htmlElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    themeToggleBtn.querySelector('i').classList.replace('fa-sun', 'fa-moon');
                } else {
                    htmlElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeToggleBtn.querySelector('i').classList.replace('fa-moon', 'fa-sun');
                }
            });
        });

        // --- Google Books API Integration ---
        const booksContainer = document.getElementById('books-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const bookSearchInput = document.getElementById('book-search-input');
        const bookDetailOverlay = document.getElementById('book-detail-overlay');
        const closeDetailBtn = document.getElementById('close-detail-btn');
        const detailImg = document.getElementById('detail-img');
        const detailTitle = document.getElementById('detail-title');
        const detailAuthors = document.getElementById('detail-authors');
        const detailDescription = document.getElementById('detail-description');
        const detailPrice = document.getElementById('detail-price');
        const prevBookBtn = document.getElementById('prev-book-btn');
        const nextBookBtn = document.getElementById('next-book-btn');
        const addToCartDetailBtn = document.querySelector('.add-to-cart-detail');
        const addToWishlistDetailBtn = document.querySelector('.add-to-wishlist-detail'); // Get Wishlist button in detail
        const viewPdfDetailBtn = document.getElementById('view-pdf-detail'); // PDF button

        let startIndex = 0;
        const maxResults = 20; // Number of books to fetch per request
        let currentQuery = 'fiction'; // Default search query
        let isLoading = false;
        let allFetchedBooks = []; // Stores all books fetched so far for detail navigation
        let currentBookIndex = -1; // Index of the book currently shown in detail view

        // Function to mock a price since Google Books API doesn't provide it
        function generateMockPrice() {
            return (Math.random() * (2500 - 500) + 500).toFixed(2);
        }

        // Function to fetch books from Google Books API
        async function fetchBooks() {
            if (isLoading) return;
            isLoading = true;
            loadingIndicator.style.display = 'block';

            // Special handling for the dictionary as requested
            let apiUrl;
            if (currentQuery.toLowerCase().includes('japanese-english dictionary') || currentQuery.toLowerCase().includes('james curtis hepburn')) {
                // Directly search for the specific book or a close match
                apiUrl = `https://www.googleapis.com/books/v1/volumes?q=japanese-english+dictionary+james+curtis+hepburn&startIndex=${startIndex}&maxResults=${maxResults}`;
            } else {
                apiUrl = `https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(currentQuery)}&startIndex=${startIndex}&maxResults=${maxResults}`;
            }

            try {
                const response = await fetch(apiUrl);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                // Process fetched data
                if (data.items && data.items.length > 0) {
                    const newBooks = data.items.map(item => {
                        const volumeInfo = item.volumeInfo;
                        const book = {
                            title: volumeInfo.title || 'No Title Available',
                            authors: volumeInfo.authors ? volumeInfo.authors.join(', ') : 'Unknown Author',
                            description: volumeInfo.description || 'No description available.',
                            image: volumeInfo.imageLinks ? volumeInfo.imageLinks.thumbnail : 'https://placehold.co/128x192/cccccc/333333?text=No+Cover',
                            price: parseFloat(generateMockPrice()), // Mocked price
                            // Construct PDF link using item.id for books.google.co.in format
                            pdfLink: item.id ? `https://books.google.co.in/books?id=${item.id}` : null
                        };
                        return book;
                    });
                    
                    allFetchedBooks = allFetchedBooks.concat(newBooks);
                    renderBooks(newBooks, allFetchedBooks.length - newBooks.length); // Pass starting index for new books
                } else if (startIndex === 0) {
                    // Only show message if no books found for initial search
                    booksContainer.innerHTML = '<p style="text-align: center; color: var(--light-color);">No books found for your search.</p>';
                }

            } catch (error) {
                console.error('Error fetching books:', error);
                displayMessageModal('Failed to load books. Please try again later.', false);
                if (startIndex === 0) {
                    booksContainer.innerHTML = '<p style="text-align: center; color: red;">Failed to load books. Please check your internet connection or try again later.</p>';
                }
            } finally {
                isLoading = false;
                loadingIndicator.style.display = 'none';
            }
        }

        // Function to render books in the grid
        function renderBooks(books, startingIndex) {
            books.forEach((book, relativeIndex) => {
                const globalIndex = startingIndex + relativeIndex;
                const bookCard = document.createElement('div');
                bookCard.classList.add('book-card');
                bookCard.setAttribute('data-index', globalIndex); // Store global index for detail view

                bookCard.innerHTML = `
                    <img src="${book.image}" alt="${book.title}">
                    <div class="content">
                        <h3>${book.title}</h3>
                        <p class="authors">${book.authors}</p>
                        <div class="price">₹${book.price.toFixed(2)}</div>
                        <button class="btn add-to-cart"
                                data-title="${book.title}"
                                data-price="${book.price}"
                                data-img="${book.image}">Add to Cart</button>
                        <button class="btn add-to-wishlist"
                                data-title="${book.title}"
                                data-price="${book.price}"
                                data-img="${book.image}">Add to Wishlist</button> <!-- NEW Wishlist Button -->
                        <button class="btn view-details" data-index="${globalIndex}">View Details</button>
                    </div>
                `;
                booksContainer.appendChild(bookCard);
            });

            // Re-attach listeners for newly added buttons
            attachAddToCartListeners();
            attachAddToWishlistListeners(); // Attach wishlist listeners
            attachViewDetailsListeners();
        }

        // --- Event Listeners ---

        // Infinite Scroll
        window.addEventListener('scroll', () => {
            // Check if user has scrolled to the bottom of the page
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 && !isLoading) {
                startIndex += maxResults; // Increment for next batch
                fetchBooks();
            }
        });

        // Search functionality
        let searchTimeout;
        bookSearchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const newQuery = bookSearchInput.value.trim();
                if (newQuery !== currentQuery) {
                    currentQuery = newQuery || 'fiction'; // Use 'fiction' if search box is empty
                    startIndex = 0; // Reset for new search
                    booksContainer.innerHTML = ''; // Clear previous results
                    allFetchedBooks = []; // Clear stored books
                    fetchBooks();
                }
            }, 500); // Debounce time
        });

        // Back to Top button functionality
        const backToTopButton = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) { // Show button after scrolling 300px
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Add to Cart functionality (delegated for dynamically added buttons)
        function attachAddToCartListeners() {
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                // Remove existing listener to prevent duplicates
                btn.removeEventListener('click', handleAddToCart);
                // Add new listener
                btn.addEventListener('click', handleAddToCart);
            });
            // Attach listener for the detail view's Add to Cart button
            addToCartDetailBtn.removeEventListener('click', handleAddToCartDetail);
            addToCartDetailBtn.addEventListener('click', handleAddToCartDetail);
        }

        async function handleAddToCart(e) {
            e.preventDefault();
            const title = this.getAttribute('data-title');
            const priceRaw = this.getAttribute('data-price');
            const img = this.getAttribute('data-img');
            const price = parseFloat(priceRaw.replace(/[^0-9.]/g, ''));

            const formData = new FormData();
            formData.append('title', title);
            formData.append('price', price);
            formData.append('img', img);

            try {
                const response = await fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const result = await response.json();
                displayMessageModal(result.message, result.success);
            } catch (error) {
                console.error('Error adding to cart:', error);
                displayMessageModal('An unexpected error occurred while adding to cart. Please try again.', false);
            }
        }

        async function handleAddToCartDetail(e) {
            e.preventDefault();
            if (currentBookIndex === -1 || !allFetchedBooks[currentBookIndex]) return;

            const book = allFetchedBooks[currentBookIndex];
            const formData = new FormData();
            formData.append('title', book.title);
            formData.append('price', book.price);
            formData.append('img', book.image);

            try {
                const response = await fetch('add_to_cart.php', {
                    method: 'POST',
                    body: formData
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const result = await response.json();
                displayMessageModal(result.message, result.success);
            } catch (error) {
                console.error('Error adding to cart from detail view:', error);
                displayMessageModal('An unexpected error occurred while adding to cart. Please try again.', false);
            }
        }

        // Add to Wishlist functionality
        function attachAddToWishlistListeners() {
            document.querySelectorAll('.add-to-wishlist').forEach(btn => {
                btn.removeEventListener('click', handleAddToWishlist); // Prevent duplicates
                btn.addEventListener('click', handleAddToWishlist);
            });
            // Attach listener for the detail view's Add to Wishlist button
            addToWishlistDetailBtn.removeEventListener('click', handleAddToWishlistDetail);
            addToWishlistDetailBtn.addEventListener('click', handleAddToWishlistDetail);
        }

        async function handleAddToWishlist(e) {
            e.preventDefault();
            const title = this.getAttribute('data-title');
            const priceRaw = this.getAttribute('data-price');
            const img = this.getAttribute('data-img');
            const price = parseFloat(priceRaw.replace(/[^0-9.]/g, ''));

            const formData = new FormData();
            formData.append('title', title);
            formData.append('price', price);
            formData.append('img', img);

            try {
                const response = await fetch('add_to_wishlist.php', {
                    method: 'POST',
                    body: formData
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const result = await response.json();
                displayMessageModal(result.message, result.success);
            } catch (error) {
                console.error('Error adding to wishlist:', error);
                displayMessageModal('An unexpected error occurred while adding to wishlist. Please try again.', false);
            }
        }

        async function handleAddToWishlistDetail(e) {
            e.preventDefault();
            if (currentBookIndex === -1 || !allFetchedBooks[currentBookIndex]) return;

            const book = allFetchedBooks[currentBookIndex];
            const formData = new FormData();
            formData.append('title', book.title);
            formData.append('price', book.price);
            formData.append('img', book.image);

            try {
                const response = await fetch('add_to_wishlist.php', {
                    method: 'POST',
                    body: formData
                });
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const result = await response.json();
                displayMessageModal(result.message, result.success);
            } catch (error) {
                console.error('Error adding to wishlist from detail view:', error);
                displayMessageModal('An unexpected error occurred while adding to wishlist. Please try again.', false);
            }
        }


        // View Details functionality
        function attachViewDetailsListeners() {
            document.querySelectorAll('.view-details').forEach(btn => {
                btn.removeEventListener('click', handleViewDetails); // Prevent duplicates
                btn.addEventListener('click', handleViewDetails);
            });
        }

        function handleViewDetails(e) {
            const index = parseInt(this.getAttribute('data-index'));
            showBookDetails(index);
        }

        function showBookDetails(index) {
            if (index < 0 || index >= allFetchedBooks.length) {
                console.warn('Invalid book index for detail view:', index);
                return;
            }

            currentBookIndex = index;
            const book = allFetchedBooks[currentBookIndex];

            detailImg.src = book.image;
            detailImg.alt = book.title;
            detailTitle.innerText = book.title;
            detailAuthors.innerText = `By: ${book.authors}`;
            detailDescription.innerText = book.description;
            detailPrice.innerText = `Price: ₹${book.price.toFixed(2)}`;

            // Handle PDF button visibility and functionality
            if (book.pdfLink) {
                viewPdfDetailBtn.style.display = 'block';
                viewPdfDetailBtn.disabled = false;
                viewPdfDetailBtn.onclick = () => window.open(book.pdfLink, '_blank');
            } else {
                viewPdfDetailBtn.style.display = 'block'; // Keep button visible but disabled
                viewPdfDetailBtn.disabled = true;
                viewPdfDetailBtn.onclick = null; // Remove handler
            }

            // Update navigation button states
            prevBookBtn.disabled = (currentBookIndex === 0);
            nextBookBtn.disabled = (currentBookIndex === allFetchedBooks.length - 1);

            bookDetailOverlay.style.display = 'flex'; // Show the modal
        }

        // Close detail view
        closeDetailBtn.addEventListener('click', () => {
            bookDetailOverlay.style.display = 'none';
        });

        // Previous/Next book navigation within detail view
        prevBookBtn.addEventListener('click', () => {
            if (currentBookIndex > 0) {
                showBookDetails(currentBookIndex - 1);
            }
        });

        nextBookBtn.addEventListener('click', () => {
            if (currentBookIndex < allFetchedBooks.length - 1) {
                showBookDetails(currentBookIndex + 1);
            }
        });

        // Initial fetch when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            fetchBooks();
        });

        // Login form display (from your original file, kept as is)
        document.querySelector('#search-btn').onclick = () => {
            document.querySelector('.search-form').classList.toggle('active');
        }

        document.querySelector('#header-search-box').addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            // This is the header search, it should probably affect the main book list search
            // For now, it will only hide/show elements in the header if you had them there.
            // If you want this search to also filter the main books-grid, you'd integrate it with book-search-input logic.
        });

        document.querySelector('#login-btn').onclick = () => {
            document.querySelector('.login-form-container').classList.toggle('active');
        }

        document.querySelector('#close-login-btn').onclick = () => {
            document.querySelector('.login-form-container').classList.remove('active');
        }
    </script>
</body>
</html>
