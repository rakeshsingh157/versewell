
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
    <title>Blogs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        /* Styles for the dark mode toggle button */
        .theme-toggle-button {
            background: none;
            border: none;
            font-size: 2.5rem; /* Match other icons */
            margin-left: 1.5rem;
            color: var(--text-color); /* Inherit from theme */
            cursor: pointer;
            transition: color 0.2s linear;
        }

        .theme-toggle-button:hover {
            color: var(--orange); /* Match other icon hover effects */
        }
        /* Moved from inline style */
        .blogs .blogs-slider .box .content div {
            padding-left: 2cm;
            padding-right: 2cm;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <!-- header section starts  -->

    <header class="header">

        <div class="header-1">

            <a href="#" class="logo"> <i class="fas fa-book"></i> versewell </a>

            <div class="search-container">
                <form action="" class="search-form">
                    <input type="search" name="search" placeholder="Search here..." id="search-box" autocomplete="off">
                    <label for="search-box" class="fas fa-search"></label>
                </form>
                <div class="search-results" id="search-results"></div>
            </div>

            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <a href="#" class="fas fa-heart"></a>
                <!-- Updated cart-btn to point to cart.html for full cart view -->
                <a href="cart.php" class="fas fa-shopping-cart" id="cart-btn"></a> 
                <a href="profile.php"><div  class="fas fa-user"></div></a>
                <a href="my acc.html" title="My Account"><i class=""></i></a>
                <!-- Dark Mode Toggle Button -->
                <button class="theme-toggle-button" id="theme-toggle">
                    <i class="fas fa-moon"></i> 
                </button>
                <div class="userinfo">
                    
                
                    <?php if (isset($userFirstName) && isset($userName)): ?>
                        <p>Welcome,<br> <span><?php echo $userFirstName; ?></span></p>
                        
                    <?php else: ?>
                        <p>Welcome, Guest</p>
                    <?php endif; ?></div>
            </div>

        </div>

        <div class="header-2">
            <nav class="navbar">
                <a href="html.php">home</a>
                <a href="html4.php">reviews</a>
                <a href='html2.php'>genres</a>

            </nav>
        </div>

    </header>

    <!-- header section ends -->
    <section class="blogs" id="blogs">

        <h1 class="heading"> <span>Our Blogs</span> </h1>

        <div class="swiper blogs-slider">

            <div class="swiper-wrapper">

                <div class="swiper-slide box">
                   <div class="image">
                        <img src="image/blog-1.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>The most beautiful books to read by Ava Jules</h3>
                        <p>Ava is a girl obsessed with books!</p>
                        <a href="#" class="btn">read more</a>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <div class="image">
                        <img src="image/blog-2.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>The art of reading</h3>
                        <p>What to read when you try something new!</p>
                        <a href="#" class="btn">read more</a>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <div class="image">
                        <img src="image/blog-3.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>Throwing words!</h3>
                        <p>A blog for all of you who are stuck and have no inspiration for the next word! </p>
                        <a href="#" class="btn">read more</a>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <div class="image">
                        <img src="image/blog-4.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>Gift ideas for the New Year holidays</h3>
                        <p>The time for gifts has come click "read more" for ideas!</p>
                        <a href="#" class="btn">read more</a>
                    </div>
                </div>

                <div class="swiper-slide box">
                    <div class="image">
                        <img src="lexolibra.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>10 best books to read this year-end</h3>
                        <p>The best choices from different collections!</p>
                        <a href="#" class="btn">read more</a>
                    </div>
                </div>

            </div>

        </div>

    </section>

    <script>
        // Dark Mode Functionality
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement; // This is the <html> tag

            // Check for saved theme preference in local storage
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                htmlElement.setAttribute('data-theme', savedTheme);
                // Update the icon based on the loaded theme
                if (savedTheme === 'dark') {
                    themeToggleBtn.querySelector('i').classList.replace('fa-moon', 'fa-sun');
                } else {
                    themeToggleBtn.querySelector('i').classList.replace('fa-sun', 'fa-moon');
                }
            } else {
                // Default to light theme if no preference is saved
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
        // SEARCH FUNCTIONALITY
        document.getElementById('search-box').addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            // Assuming you have blog boxes structured similarly to book boxes
            const boxes = document.querySelectorAll('.swiper-slide.box');
            boxes.forEach(box => {
                const title = box.querySelector('.content h3').innerText.toLowerCase();
                if (title.includes(query)) {
                    box.style.display = '';
                } else {
                    box.style.display = 'none';
                }
            });
            if (query === '') {
                boxes.forEach(box => box.style.display = '');
            }
        });
    </script>
</body>

</html>
