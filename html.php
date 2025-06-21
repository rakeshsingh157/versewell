<?php

session_start();

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
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
            display: none;
        }
        .search-result-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .search-result-item:hover {
            background-color: #f5f5f5;
        }
        .search-result-item img {
            width: 40px;
            height: 60px;
            object-fit: cover;
            margin-right: 15px;
        }
        .search-result-info {
            flex-grow: 1;
        }
        .search-result-info h4 {
            margin: 0;
            color: #333;
        }
        .search-result-info p {
            margin: 5px 0 0;
            color: #666;
        }
        .no-results {
            padding: 15px;
            color: #666;
            text-align: center;
        }
        .search-container {
            position: relative;
        }
        .search-result-item a {
            display: flex;
            align-items: center;
            width: 100%;
            text-decoration: none;
            color: inherit;
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
                <a href="#" class="fas fa-heart"></a>
                <a href="cart.html" class="fas fa-shopping-cart" id="cart-btn"></a>
                <a href="profile.php"><div  class="fas fa-user"></div></a>
                <a href="my acc.html" title="My Account"><i class=""></i></a>
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
                <a href="#featured">featured</a>
                <a href="html3.html">blogs</a>
                <a href='html2.html' alt='Broken links'>genres</a>
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
                <h3 style="color:white;">Up to 50% discount</h3>
                <a href="#" class="btn">Buy now</a>
                <p style="color:white; font-size:20px;"> Buy used or new books, online or in store, only at versewell in the beautiful city of Shkodra!</p>
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
                    <a href="#" class="fas fa-heart"></a>
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
                    <a href="#" class="fas fa-heart"></a>
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
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <!-- custom js file link  -->
    <script src="javascript.js"></script>
    <script>
        // Add to Cart functionality
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const title = this.getAttribute('data-title');
                const price = this.getAttribute('data-price');
                const img = this.getAttribute('data-img');
                // Get existing cart or start with empty array
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                // Add new book to cart
                cart.push({title, price, img});
                // Save updated cart
                localStorage.setItem('cart', JSON.stringify(cart));
                // Optional: Show a message to user
                alert('Book added to cart!');
            });
        });

        // Real-time search functionality
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
                    description: 'A brave and heartbreaking novel about love and strength'
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
                // Add all other books with the same structure
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
                // Continue with all other books...
            ];

            searchBox.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                if (searchTerm === '') {
                    searchResults.style.display = 'none';
                    return;
                }
                
                const filteredBooks = books.filter(book => 
                    book.title.toLowerCase().includes(searchTerm)
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
                    const bookUrl = `book.php?title=${encodeURIComponent(book.title)}&author=${encodeURIComponent(book.author)}&price=${encodeURIComponent(book.price)}&image=${encodeURIComponent(book.image)}&description=${encodeURIComponent(book.description)}`;
                    
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
    </script>

</body>

</html>