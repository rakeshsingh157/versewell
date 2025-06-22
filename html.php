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
    <title>versewell</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- font awesome cdn link  -->
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
<div id="login-btn" ></div>
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
                <!-- Updated wishlist icon to use data attributes and a class for JS handling -->
                <a href="wishlist.php" class="fas fa-heart" id="wishlist-icon"></a> 
                <!-- Updated cart-btn to point to cart.php for full cart view -->
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
                <a href="#home">home</a>
                <a href="other.php">featured</a>
                <a href="html3.php">blogs</a>
                <a href='html2.php' alt='Broken links'>genres</a>
            </nav>
        </div>

    </header>

    <!-- header section ends -->

    <!-- bottom navbar  -->

    <nav class="bottom-navbar">
        <a href="#home" class="fas fa-home"></a>
        <a href="#featured" class="fas fa-list"></a>
    </nav>

    <!-- login form  -->

    <div class="login-form-container">

        <div id="close-login-btn" class="fas fa-times"></div>

        

    </div>

    <!-- home section starts  -->

    <section class="home" id="home">

        <div class="row">

            <div class="content">
                <!-- Removed inline style 'color:white;' to use CSS variables for dark mode -->
                <h3>Up to 50% discount</h3> 
                <a href="#" class="btn">Buy now</a>
                <!-- Removed inline style 'color:white; font-size:20px;' to use CSS variables for dark mode -->
                <p> Buy used or new books, online or in store, only at versewell in the beautiful city of Shkodra!</p>
            </div>

            <div class="swiper books-slider">
                <div class="swiper-wrapper">
                    <a href="book.php?title=Matrix&author=Lauren%20Groff&price=1335&image=matrix.jpg&description=A%20bold%20and%20timely%20novel" class="swiper-slide"><img src="matrix.jpg" alt=""></a>
                    <a href="book.php?title=The%20Wish&author=Nicholas%20Sparks&price=1500&image=the_wish.jpg&description=A%20heartwarming%20holiday%20story" class="swiper-slide"><img src="the_wish.jpg" alt=""></a>
                    <a href="book.php?title=Reckless&author=Lauren%20Roberts&price=1600&image=reckless.jpg&description=A%20powerful%20fantasy%20romance" class="swiper-slide"><img src="reckless.jpg" alt=""></a>
                    <a href="book.php?title=The%20Maid&author=Nita%20Prose&price=1450&image=the_maid.jpg&description=A%20clue-like%20murder%20mystery" class="swiper-slide"><img src="the_maid.jpg" alt=""></a>
                    <a href="book.php?title=Shadow%20and%20Bone&author=Leigh%20Bardugo&price=2010&image=shadow_and_bone.jpg&description=A%20rich%20fantasy%20world" class="swiper-slide"><img src="shadow_and_bone.jpg" alt=""></a>
                    <a href="book.php?title=Wish%20You%20Were%20Here&author=Jodi%20Picoult&price=1700&image=wish_you_were_here.jpg&description=A%20story%20of%20resilience" class="swiper-slide"><img src="wish_you_were_here.jpg" alt=""></a>
                </div>
                <img src="image/stand.png" class="stand" alt="">
            </div>

        </div>

    </section>

    <!-- home section ends -->

    <!-- featured section starts  -->

    <section class="featured" id="featured">

    <h1 class="heading"> <span>featured books</span> </h1>

    <div class="swiper featured-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide box">
                <div class="icons">
                    <a href="book.php?title=It%20Ends%20With%20Us&author=Colleen%20Hoover&price=1335&image=itendswithus.jpg&description=A%20brave%20and%20heartbreaking%20novel" class="fas fa-search"></a>
                    <a href="#" class="fas fa-heart add-to-wishlist" data-title="It Ends With Us" data-price="1335" data-img="itendswithus.jpg"></a>
                    <a href="book.php?title=It%20Ends%20With%20Us&author=Colleen%20Hoover&price=1335&image=itendswithus.jpg&description=A%20brave%20and%20heartbreaking%20novel" class="fas fa-eye"></a>
                </div>
                <div class="image">
                    <img src="itendswithus.jpg" alt="">
                </div>
                <div class="content">
                    <h3>It Ends With Us</h3>
                    <div class="price">₹1,335 <span>₹1,753</span></div>
                    <a href="#" class="btn add-to-cart" data-title="It Ends With Us" data-price="1335" data-img="itendswithus.jpg">add to cart</a>
                </div>
            </div>

            <div class="swiper-slide box">
                <div class="icons">
                    <a href="book.php?title=The%20Alchemist&author=Paulo%20Coelho&price=1420&image=thealchemist.jpg&description=A%20magical%20story%20about%20following%20your%20dreams" class="fas fa-search"></a>
                    <a href="#" class="fas fa-heart add-to-wishlist" data-title="The Alchemist" data-price="1420" data-img="thealchemist.jpg"></a>
                    <a href="book.php?title=The%20Alchemist&author=Paulo%20Coelho&price=1420&image=thealchemist.jpg&description=A%20magical%20story%20about%20following%20your%20dreams" class="fas fa-eye"></a>
                </div>
                <div class="image">
                    <img src="thealchemist.jpg" alt="">
                </div>
                <div class="content">
                    <h3>The Alchemist</h3>
                    <div class="price">₹1,420 <span>₹1,753</span></div>
                    <a href="#" class="btn add-to-cart" data-title="The Alchemist" data-price="1420" data-img="thealchemist.jpg">add to cart</a>
                </div>
            </div>





            <div class="swiper-slide box">
                <div class="icons">
                    <a href="book.php?title=Matrix&author=Paulo%20Coelho&price=1335&image=matrix.jpg&description=A%20magical%20story%20about%20following%20your%20dreams" class="fas fa-search"></a>
                    <a href="#" class="fas fa-heart add-to-wishlist" data-title="Matrix" data-price="1335" data-img="matrix.jpg"></a>
                    <a href="book.php?title=Matrix&author=Paulo%20Coelho&price=1335&image=matrix.jpg&description=A%20magical%20story%20about%20following%20your%20dreams" class="fas fa-eye"></a>
                </div>
                <div class="image">
                    <img src="matrix.jpg" alt="">
                </div>
                <div class="content">
                    <h3>Matrix</h3>
                    <div class="price">₹1,335 <span>₹1,753</span></div>
                    <a href="#" class="btn add-to-cart" data-title="Matrix" data-price="1335" data-img="matrix.jpg">add to cart</a>
                </div>
            </div>








             <div class="swiper-slide box">
                <div class="icons">
                    <a href="book.php?title=one%20of%20us%20is%20lying&author=Paulo%20Coelho&price=1669&image=one_of_us_is_lying.jpg&description=A%20magical%20story%20about%20following%20your%20dreams" class="fas fa-search"></a>
                    <a href="#" class="fas fa-heart add-to-wishlist" data-title="One of Us Is Lying" data-price="1669" data-img="one_of_us_is_lying.jpg"></a>
                    <a href="book.php?title=one%20of%20us%20is%20lying&author=Paulo%20Coelho&price=1669&image=one_of_us_is_lying.jpg&description=A%20magical%20story%20about%20following%20your%20dreams" class="fas fa-eye"></a>
                </div>
                <div class="image">
                    <img src="one_of_us_is_lying.jpg" alt="">
                </div>
                <div class="content">
                    <h3>one of us is lying</h3>
                    <div class="price">₹1,669 <span>₹1,753</span></div>
                    <a href="#" class="btn add-to-cart" data-title="One of Us Is Lying" data-price="1669" data-img="one_of_us_is_lying.jpg">add to cart</a>
                </div>
            </div>



                        <div class="swiper-slide box">
                <div class="icons">
                    <a href="book.php?title=Th%20%20Da%20Vinci%20Code&author=Paulo%20Coelho&price=1168&image=the_davinci_code.jpg&description=A%20magical%20story%20about%20following%20your%20dreams" class="fas fa-search"></a>
                    <a href="#" class="fas fa-heart add-to-wishlist" data-title="The Da Vinci Code" data-price="1168" data-img="the_davinci_code.jpg"></a>
                    <a href="book.php?title=Th%20%20Da%20Vinci%20Code&author=Paulo%20Coelho&price=1168&image=the_davinci_code.jpg&description=A%20magical%20story%20about%20following%20your%20dreams" class="fas fa-eye"></a>
                </div>
                <div class="image">
                    <img src="the_davinci_code.jpg" alt="">
                </div>
                <div class="content">
                    <h3>The Da Vinci Code</h3>
                    <div class="price">₹1,168 <span>₹1,504</span></div>
                    <a href="#" class="btn add-to-cart" data-title="One of Us Is Lying" data-price="1669" data-img="one_of_us_is_lying.jpg">add to cart</a>
                </div>
            </div>



            <div class="swiper-slide box">
    <div class="icons">
        <a href="book.php?title=Normal%20People&author=Sally%20Rooney&price=1594&image=normal_people.jpg&description=A%20beautifully%20written%20love%20story" class="fas fa-search"></a>
        <a href="#" class="fas fa-heart add-to-wishlist" data-title="Normal People" data-price="1594" data-img="normal_people.jpg"></a>
        <a href="book.php?title=Normal%20People&author=Sally%20Rooney&price=1594&image=normal_people.jpg&description=A%20beautifully%20written%20love%20story" class="fas fa-eye"></a>
    </div>
    <div class="image">
        <img src="normal_people.jpg" alt="">
    </div>
    <div class="content">
        <h3>Normal People</h3>
        <div class="price">₹1,594 <span>₹2,084</span></div>
        <a href="#" class="btn add-to-cart" data-title="Normal People" data-price="1594" data-img="normal_people.jpg">add to cart</a>
    </div>
</div>

<div class="swiper-slide box">
    <div class="icons">
        <a href="book.php?title=Shadow%20and%20Bone&author=Leigh%20Bardugo&price=2010&image=shadow_and_bone.jpg&description=A%20dark%20and%20magical%20fantasy%20adventure" class="fas fa-search"></a>
        <a href="#" class="fas fa-heart add-to-wishlist" data-title="Shadow and Bone" data-price="2010" data-img="shadow_and_bone.jpg"></a>
        <a href="book.php?title=Shadow%20and%20Bone&author=Leigh%20Bardugo&price=2010&image=shadow_and_bone.jpg&description=A%20dark%20and%20magical%20fantasy%20adventure" class="fas fa-eye"></a>
    </div>
    <div class="image">
        <img src="shadow_and_bone.jpg" alt="">
    </div>
    <div class="content">
        <h3>Shadow and Bone</h3>
        <div class="price">₹2,010 <span>₹2,259</span></div>
        <a href="#" class="btn add-to-cart" data-title="Shadow and Bone" data-price="2010" data-img="shadow_and_bone.jpg">add to cart</a>
    </div>
</div>

<div class="swiper-slide box">
    <div class="icons">
        <a href="book.php?title=What%20If%3F&author=Randall%20Munroe&price=1594&image=what_if.jpg&description=Scientific%20answers%20to%20absurd%20hypothetical%20questions" class="fas fa-search"></a>
        <a href="#" class="fas fa-heart add-to-wishlist" data-title="What If?" data-price="1594" data-img="what_if.jpg"></a>
        <a href="book.php?title=What%20If%3F&author=Randall%20Munroe&price=1594&image=what_if.jpg&description=Scientific%20answers%20to%20absurd%20hypothetical%20questions" class="fas fa-eye"></a>
    </div>
    <div class="image">
        <img src="what_if.jpg" alt="">
    </div>
    <div class="content">
        <h3>What If?</h3>
        <div class="price">₹1,594 <span>₹1,926</span></div>
        <a href="#" class="btn add-to-cart" data-title="What If?" data-price="1594" data-img="what_if.jpg">add to cart</a>
    </div>
</div>

<div class="swiper-slide box">
    <div class="icons">
        <a href="book.php?title=Brothers&author=Yu%20Hua&price=1423&image=brothers.jpg&description=A%20satirical%20look%20at%20modern%20Chinese%20life" class="fas fa-search"></a>
        <a href="#" class="fas fa-heart add-to-wishlist" data-title="Brothers" data-price="1423" data-img="brothers.jpg"></a>
        <a href="book.php?title=Brothers&author=Yu%20Hua&price=1423&image=brothers.jpg&description=A%20satirical%20look%20at%20modern%20Chinese%20life" class="fas fa-eye"></a>
    </div>
    <div class="image">
        <img src="brothers.jpg" alt="">
    </div>
    <div class="content">
        <h3>Brothers</h3>
        <div class="price">₹1,423 <span>₹1,753</span></div>
        <a href="#" class="btn add-to-cart" data-title="Brothers" data-price="1423" data-img="brothers.jpg">add to cart</a>
    </div>
</div>

<div class="swiper-slide box">
    <div class="icons">
        <a href="book.php?title=The%20Dutch%20House&author=Ann%20Patchett&price=1839&image=the_dutch_house.jpg&description=A%20rich%20story%20about%20sibling%20bond%20and%20home" class="fas fa-search"></a>
        <a href="#" class="fas fa-heart add-to-wishlist" data-title="The Dutch House" data-price="1839" data-img="the_dutch_house.jpg"></a>
        <a href="book.php?title=The%20Dutch%20House&author=Ann%20Patchett&price=1839&image=the_dutch_house.jpg&description=A%20rich%20story%20about%20sibling%20bond%20and%20home" class="fas fa-eye"></a>
    </div>
    <div class="image">
        <img src="the_dutch_house.jpg" alt="">
    </div>
    <div class="content">
        <h3>The Dutch House</h3>
        <div class="price">₹1,839 <span>₹2,173</span></div>
        <a href="#" class="btn add-to-cart" data-title="The Dutch House" data-price="1839" data-img="the_dutch_house.jpg">add to cart</a>
    </div>
</div>









            <!-- More featured books... (similar structure for all books) -->

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

    </div>

</section>

<!-- featured section ends -->

<!-- newsletter section starts -->
<section class="newsletter">

    <form action="">
        <h3>subscribe for latest updates</h3>
        <input type="email" name="" placeholder="enter your email" id="" class="box">
        <input type="submit" value="subscribe" class="btn">
    </form>

</section>

<!-- newsletter section ends -->

<!-- deal section starts  -->

<section class="deal">

        <div class="content">
            <h3>offer of the day</h3>
            <h1>up to 50% discount</h1>
            <p>Browse exclusive books today! Hurry up - the day offer expires at midnight!</p>
            <a href="#" class="btn">shop now</a>
        </div>

        <div class="image">
            <img src="image/deal-img.jpeg" alt="">
        </div>

    </section>

    <!-- deal section ends -->
    <!-- footer section starts  -->

    <section class="footer">

    <div class="box-container">
        <!-- Removed Our locations and contact info boxes -->
    </div>

    <div class="share">
        <a href="https://www.facebook.com/" class="fab fa-facebook-f"></a>
        <a href="#" class="fab fa-twitter"></a>
        <a href="#" class="fab fa-instagram"></a>
        <a href="#" class="fab fa-linkedin"></a>
        <a href="#" class="fab fa-pinterest"></a>
    </div>

    <div class="credit"> Created by <span>VERSEWELL</span> | all rights reserved! </div>

</section>

<!-- footer section ends -->

    <!-- loader  -->

    <div class="loader-container">
        <img src="image/loader-img.gif" alt="">
    </div>

    <!-- Custom Message Modal HTML -->
    <div id="message-modal">
        <h3 id="message-modal-title">Notification</h3>
        <p id="message-modal-content"></p>
        <button class="close-btn" onclick="closeMessageModal()">Close</button>
    </div>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <!-- custom js file link  -->
    <script src="javascript.js"></script>
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

        // Add to Cart functionality with AJAX
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault(); // Prevent the default anchor link behavior

                const title = this.getAttribute('data-title');
                const priceRaw = this.getAttribute('data-price');
                const img = this.getAttribute('data-img');

                // Clean the price: remove any character that is not a digit or a dot.
                // This ensures that prices like "₹1,335" become "1335" or "₹1,084.365" become "1084.365"
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

        // Add to Wishlist functionality with AJAX
        document.querySelectorAll('.add-to-wishlist').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault(); // Prevent the default anchor link behavior

                const title = this.getAttribute('data-title');
                const priceRaw = this.getAttribute('data-price');
                const img = this.getAttribute('data-img');

                // Clean the price: remove any character that is not a digit or a dot.
                const price = priceRaw.replace(/[^0-9.]/g, ''); 

                // Create FormData object to send data
                const formData = new FormData();
                formData.append('title', title);
                formData.append('price', price);
                formData.append('img', img);

                try {
                    const response = await fetch('add_to_wishlist.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json(); 

                    if (result.success) {
                        displayMessageModal(result.message, true);
                    } else {
                        displayMessageModal(result.message, false);
                    }
                } catch (error) {
                    console.error('Error adding to wishlist:', error);
                    displayMessageModal('An unexpected error occurred while adding to wishlist. Please try again.', false);
                }
            });
        });


        // Real-time search functionality (from your original file, kept as is)
        document.addEventListener('DOMContentLoaded', function() {
            const searchBox = document.getElementById('search-box');
            const searchResults = document.getElementById('search-results');
            
            // Manually defined book data with all necessary details for book pages
            const books = [
                { 
                    title: 'It Ends With Us', 
                    price: '₹1,335', 
                    image: 'itendswithus.jpg',
                    author: 'Colleen Hoover',
                    description: 'A brave and heartbreaking novel'
                },
                { 
                    title: 'The Alchemist', 
                    price: '₹1,420', 
                    image: 'thealchemist.jpg',
                    author: 'Paulo Coelho',
                    description: 'A magical story about following your dreams'
                },
                { 
                    title: 'Matrix', 
                    price: '₹1,335', 
                    image: 'matrix.jpg',
                    author: 'Lauren Groff',
                    description: 'A bold and timely novel'
                },
                {
                    title: 'one of us is lying',
                    price: '₹1,669',
                    image: 'one_of_us_is_lying.jpg',
                    author: 'Karen M. McManus', // Corrected author
                    description: 'A high school murder mystery' // Example description
                },
                {
                    title: 'The Da Vinci Code',
                    price: '₹1,168',
                    image: 'the_davinci_code.jpg',
                    author: 'Dan Brown', // Corrected author
                    description: 'A thrilling mystery novel' // Example description
                },
                {
                    title: 'Normal People',
                    price: '₹1,594',
                    image: 'normal_people.jpg',
                    author: 'Sally Rooney',
                    description: 'A beautifully written love story'
                },
                {
                    title: 'Shadow and Bone',
                    price: '₹2,010',
                    image: 'shadow_and_bone.jpg',
                    author: 'Leigh Bardugo',
                    description: 'A dark and magical fantasy adventure'
                },
                {
                    title: 'What If?',
                    price: '₹1,594',
                    image: 'what_if.jpg',
                    author: 'Randall Munroe',
                    description: 'Scientific answers to absurd hypothetical questions'
                },
                {
                    title: 'Brothers',
                    price: '₹1,423',
                    image: 'brothers.jpg',
                    author: 'Yu Hua',
                    description: 'A satirical look at modern Chinese life'
                },
                {
                    title: 'The Dutch House',
                    price: '₹1,839',
                    image: 'the_dutch_house.jpg',
                    author: 'Ann Patchett',
                    description: 'A rich story about sibling bond and home'
                },
                {
                    title: 'The Wish',
                    price: '₹1,500',
                    image: 'the_wish.jpg',
                    author: 'Nicholas Sparks',
                    description: 'A heartwarming holiday story'
                },
                {
                    title: 'Reckless',
                    price: '₹1,600',
                    image: 'reckless.jpg',
                    author: 'Lauren Roberts',
                    description: 'A powerful fantasy romance'
                },
                {
                    title: 'The Maid',
                    price: '₹1,450',
                    image: 'the_maid.jpg',
                    author: 'Nita Prose',
                    description: 'A clue-like murder mystery'
                },
                {
                    title: 'Wish You Were Here',
                    price: '₹1,700',
                    image: 'wish_you_were_here.jpg',
                    author: 'Jodi Picoult',
                    description: 'A story of resilience'
                }
                // Add all other books with the same structure here if they are not already present
                // Make sure authors and descriptions are accurate if you want correct search results and book page details.
            ];

            searchBox.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                if (searchTerm === '') {
                    searchResults.style.display = 'none';
                    return;
                }
                
                const filteredBooks = books.filter(book => 
                    book.title.toLowerCase().includes(searchTerm) ||
                    book.author.toLowerCase().includes(searchTerm)
                );
                
                displayResults(filteredBooks);
            });
            
            function displayResults(results) {
                searchResults.innerHTML = '';
                
                if (results.length === 0) {
                    searchResults.innerHTML = '<div class="no-results">No books found</div>';
                    searchResults.style.display = 'block';
                    return;
                }
                
                results.forEach(book => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'search-result-item';
                    
                    // Create URL with all book parameters
                    const bookUrl = `book.php?title=${encodeURIComponent(book.title)}&author=${encodeURIComponent(book.author)}&price=${encodeURIComponent(book.price.replace(/[^0-9.]/g, ''))}&image=${encodeURIComponent(book.image)}&description=${encodeURIComponent(book.description)}`;
                    
                    resultItem.innerHTML = `
                        <a href="${bookUrl}">
                            <img src="${book.image}" alt="${book.title}">
                            <div class="search-result-info">
                                <h4>${book.title}</h4>
                                <p>${book.price}</p>
                            </div>
                        </a>
                    `;
                    
                    searchResults.appendChild(resultItem);
                });
                
                searchResults.style.display = 'block';
            }
            
            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchBox.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.style.display = 'none';
                }
            });
        });

        // Dark Mode Functionality (Duplicated for html.php, keep consistent)
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

    </script>

</body>

</html>
