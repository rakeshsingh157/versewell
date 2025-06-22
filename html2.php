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
    <title>Genres</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" />
    <style>
      
        /* Message Modal Styling */
        #message-modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--background); /* Use background variable for modal */
            border: 2px solid var(--orange);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--box-shadow); /* Use box-shadow variable */
            z-index: 1002; /* Higher than login modal */
            text-align: center;
            max-width: 400px;
            width: 90%;
            color: var(--text-color); /* Ensure text color changes with theme */
        }

        #message-modal h3 {
            color: var(--text-color); /* Ensure text color changes with theme */
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        #message-modal p {
            color: var(--text-color); /* Ensure text color changes with theme */
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
            background: var(--dark-color); /* Adjusted for theme consistency */
        }

        /* Responsive adjustments */
        @media (max-width: 450px) {
            
            #message-modal {
                padding: 20px;
            }
            #message-modal h3 {
                font-size: 1.5rem;
            }
            #message-modal p {
                font-size: 1rem;
            }
        }

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

        /* Swiper Navigation Button Styling (add or modify as needed) */
        /* These styles make the buttons visible and positioned correctly */
        .swiper-button-next,
        .swiper-button-prev {
            color: var(--orange); /* Or any color that contrasts with your background */
            background-color: var(--background); /* Match your slider background */
            padding: 10px;
            border-radius: 50%; /* Make them round */
            box-shadow: var(--box-shadow); /* Add a subtle shadow */
            transition: background-color 0.3s, color 0.3s;
            top: 50%; /* Center vertically */
            transform: translateY(-50%);
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background-color: var(--dark-color); /* Darker on hover */
            color: #fff;
        }

        /* Ensure they are positioned outside if possible or clearly visible */
        .swiper-button-next {
            right: 10px; /* Adjust as needed */
        }

        .swiper-button-prev {
            left: 10px; /* Adjust as needed */
        }
    </style>
</head>

<body>
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
                <a href="wishlist.php" class="fas fa-heart"></a>
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
<center>
    <div class="nje">
        <h1>Types of Books</h1>
    </div>
</center>
    <section class="featured" id="featured">
        <h1 class="heading"> <span>Horror</span> </h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=It&author=Stephen%20King&price=1503&image=it.jpg&description=A%20terrifying%20horror%20novel%20by%20Stephen%20King." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="It"
                           data-price="1503"
                           data-img="it.jpg"></a>
                        <a href="book.php?title=It&author=Stephen%20King&price=1503&image=it.jpg&description=A%20terrifying%20horror%20novel%20by%20Stephen%20King." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="it.jpg" alt="It">
                    </div>
                    <div class="content">
                        <h3>It</h3>
                        <div class="price">₹1,503 <span>₹1,747</span></div>
                        <a href="#" class="btn add-to-cart" data-title="It" data-price="1503" data-img="it.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Mexican%20Gothic&author=Silvia%20Moreno-Garcia&price=1084.365&image=mexican_gothic.jpg&description=A%20gothic%20horror%20novel%20set%20in%201950s%20Mexico." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Mexican Gothic"
                           data-price="1084.365"
                           data-img="mexican_gothic.jpg"></a>
                        <a href="book.php?title=Mexican%20Gothic&author=Silvia%20Moreno-Garcia&price=1084.365&image=mexican_gothic.jpg&description=A%20gothic%20horror%20novel%20set%20in%201950s%20Mexico." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="mexican_gothic.jpg" alt="Mexican Gothic">
                    </div>
                    <div class="content">
                        <h3>Mexican Gothic</h3>
                        <div class="price">₹1,084.365 <span>₹1,586.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Mexican Gothic" data-price="1084.365" data-img="mexican_gothic.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=White%20is%20for%20Witching&author=Helen%20Oyeyemi&price=1167.365&image=white_is_for_witching.jpg&description=A%20haunting%20and%20stylishly%20written%20ghost%20story." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="White is for Witching"
                           data-price="1167.365"
                           data-img="white_is_for_witching.jpg"></a>
                        <a href="book.php?title=White%20is%20for%20Witching&author=Helen%20Oyeyemi&price=1167.365&image=white_is_for%20witching.jpg&description=A%20haunting%20and%20stylishly%20written%20ghost%20story." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="white_is_for_witching.jpg" alt="White is for Witching">
                    </div>
                    <div class="content">
                        <h3>White is for Witching</h3>
                        <div class="price">₹1,167.365 <span>₹1,664.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="White is for Witching" data-price="1167.365" data-img="white_is_for_witching.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=A%20Head%20Full%20of%20Ghosts&author=Paul%20Tremblay&price=1419.365&image=a_head_full_of_ghosts.jpg&description=A%20meta-horror%20novel%20that%20explores%20the%20nature%20of%20reality." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="A Head Full of Ghosts"
                           data-price="1419.365"
                           data-img="a_head_full_of_ghosts.jpg"></a>
                        <a href="book.php?title=A%20Head%20Full%20of%20Ghosts&author=Paul%20Tremblay&price=1419.365&image=a_head_full_of_ghosts.jpg&description=A%20meta-horror%20novel%20that%20explores%20the%20nature%20of%20reality." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="a_head_full_of_ghosts.jpg" alt="A Head Full of Ghosts">
                    </div>
                    <div class="content">
                        <h3>A Head Full of Ghosts</h3>
                        <div class="price">₹1,419.365 <span>₹1,832.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="A Head Full of Ghosts" data-price="1419.365" data-img="a_head_full_of_ghosts.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Bird%20Box&author=Josh%20Malerman&price=1335.365&image=bird_box.jpg&description=A%20post-apocalyptic%20horror%20novel%20about%20creatures%20that%20drive%20people%20to%20insanity%20when%20seen." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Bird Box"
                           data-price="1335.365"
                           data-img="bird_box.jpg"></a>
                        <a href="book.php?title=Bird%20Box&author=Josh%20Malerman&price=1335.365&image=bird_box.jpg&description=A%20post-apocalyptic%20horror%20novel%20about%20creatures%20that%20drive%20people%20to%20insanity%20when%20seen." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="bird_box.jpg" alt="Bird Box">
                    </div>
                    <div class="content">
                        <h3>Bird Box</h3>
                        <div class="price">₹1,335.365 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Bird Box" data-price="1335.365" data-img="bird_box.jpg">add to cart</a>
                    </div>
                </div>
            </div>

            <!-- Swiper Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section class="featured" id="featured">
        <h1 class="heading"> <span>Adventure</span> </h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Hunger%20Games&author=Suzanne%20Collins&price=1251.365&image=the_hunger_games.jpg&description=A%20dystopian%20adventure%20novel%20where%20teenagers%20fight%20to%20the%20death." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Hunger Games"
                           data-price="1251.365"
                           data-img="the_hunger_games.jpg"></a>
                        <a href="book.php?title=The%20Hunger%20Games&author=Suzanne%20Collins&price=1251.365&image=the_hunger_games.jpg&description=A%20dystopian%20adventure%20novel%20where%20teenagers%20fight%20to%20the%20death." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_hunger_games.jpg" alt="The Hunger Games">
                    </div>
                    <div class="content">
                        <h3>The Hunger Games</h3>
                        <div class="price">₹1,251.365 <span>₹1,586.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Hunger Games" data-price="1251.365" data-img="the_hunger_games.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Fallen%20Stars&author=Jonathan%20French&price=1586.365&image=the_fallen_stars.jpg&description=An%20epic%20fantasy%20adventure%20with%20intriguing%20characters." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Fallen Stars"
                           data-price="1586.365"
                           data-img="the_fallen_stars.jpg"></a>
                        <a href="book.php?title=The%20Fallen%20Stars&author=Jonathan%20French&price=1586.365&image=the_fallen_stars.jpg&description=An%20epic%20fantasy%20adventure%20with%20intriguing%20characters." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_fallen_stars.jpg" alt="The Fallen Stars">
                    </div>
                    <div class="content">
                        <h3>The Fallen Stars</h3>
                        <div class="price">₹1,586.365 <span>₹2,335.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Fallen Stars" data-price="1586.365" data-img="the_fallen_stars.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Into%20the%20Wild&author=Jon%20Krakauer&price=1753.365&image=into%20the%20wild%2C%20aventur.jpg&description=A%20non-fiction%20account%20of%20a%20young%20man's%20adventure%20into%20the%20Alaskan%20wilderness." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Into the Wild"
                           data-price="1753.365"
                           data-img="into%20the%20wild%2C%20aventur.jpg"></a>
                        <a href="book.php?title=Into%20the%20Wild&author=Jon%20Krakauer&price=1753.365&image=into%20the%20wild%2C%20aventur.jpg&description=A%20non-fiction%20account%20of%20a%20young%20man's%20adventure%20into%20the%20Alaskan%20wilderness." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="into the wild, aventur.jpg" alt="Into the Wild">
                    </div>
                    <div class="content">
                        <h3>Into the Wild</h3>
                        <div class="price">₹1,753.365 <span>₹2,173.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Into the Wild" data-price="1753.365" data-img="into%20the%20wild%2C%20aventur.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Criminal%20Mischief&author=Stuart%20Woods&price=2505.00&image=criminal_mischief.jpg&description=A%20thrilling%20adventure%20and%20mystery%20novel." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Criminal Mischief"
                           data-price="2505.00"
                           data-img="criminal_mischief.jpg"></a>
                        <a href="book.php?title=Criminal%20Mischief&author=Stuart%20Woods&price=2505.00&image=criminal_mischief.jpg&description=A%20thrilling%20adventure%20and%20mystery%20novel." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="criminal_mischief.jpg" alt="Criminal Mischief">
                    </div>
                    <div class="content">
                        <h3>Criminal Mischief</h3>
                        <div class="price">₹2,505.00 <span>₹2,835.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Criminal Mischief" data-price="2505.00" data-img="criminal_mischief.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Catching%20Fire&author=Suzanne%20Collins&price=1084.365&image=catchingfire.jpg&description=The%20second%20book%20in%20The%20Hunger%20Games%20trilogy." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Catching Fire"
                           data-price="1084.365"
                           data-img="catchingfire.jpg"></a>
                        <a href="book.php?title=Catching%20Fire&author=Suzanne%20Collins&price=1084.365&image=catchingfire.jpg&description=The%20second%20book%20in%20The%20Hunger%20Games%20trilogy." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="catchingfire.jpg" alt="Catching Fire">
                    </div>
                    <div class="content">
                        <h3>Catching Fire</h3>
                        <div class="price">₹1,084.365 <span>₹1,419.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Catching Fire" data-price="1084.365" data-img="catchingfire.jpg">add to cart</a>
                    </div>
                </div>
            </div>
            <!-- Swiper Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section class="featured" id="featured">
        <h1 class="heading"> <span>Action</span> </h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Icebreaker&author=Hannah%20Grace&price=1419.365&image=icebreaker.jpg&description=An%20action-packed%20thriller%20with%20elements%20of%20romance." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Icebreaker"
                           data-price="1419.365"
                           data-img="icebreaker.jpg"></a>
                        <a href="book.php?title=Icebreaker&author=Hannah%20Grace&price=1419.365&image=icebreaker.jpg&description=An%20action-packed%20thriller%20with%20elements%20of%20romance." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="icebreaker.jpg" alt="Icebreaker">
                    </div>
                    <div class="content">
                        <h3>Icebreaker</h3>
                        <div class="price">₹1,419.365 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Icebreaker" data-price="1419.365" data-img="icebreaker.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Agenda%2021&author=Dustin%20Thomason%20and%20Mark%20Rushton&price=1335.365&image=agend21.jpg&description=A%20dystopian%20action%20thriller%20about%20a%20government's%20plan%20for%20global%20control." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Agenda 21"
                           data-price="1335.365"
                           data-img="agend21.jpg"></a>
                        <a href="book.php?title=Agenda%2021&author=Dustin%20Thomason%20and%20Mark%20Rushton&price=1335.365&image=agend21.jpg&description=A%20dystopian%20action%20thriller%20about%20a%20government's%20plan%20for%20global%20control." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="agend21.jpg" alt="Agenda 21">
                    </div>
                    <div class="content">
                        <h3>Agenda 21</h3>
                        <div class="price">₹1,335.365 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Agenda 21" data-price="1335.365" data-img="agend21.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Divergent&author=Veronica%20Roth&price=1669.365&image=divergent.jpg&description=A%20dystopian%20action%20series%20where%20society%20is%20divided%20into%20factions." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Divergent"
                           data-price="1669.365"
                           data-img="divergent.jpg"></a>
                        <a href="https://drive.google.com/file/d/1_SvTav9UPeMgqMh09lz4juAVCNfGjq21/view?usp=sharing" class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="divergent.jpg" alt="Divergent">
                    </div>
                    <div class="content">
                        <h3>Divergent</h3>
                        <div class="price">₹1,669.365 <span>₹2,173.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Divergent" data-price="1669.365" data-img="divergent.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Sentinel&author=Lee%20Child&price=1419.365&image=the_sentinel.jpg&description=A%20latest%20Jack%20Reacher%20action-thriller%20novel." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Sentinel"
                           data-price="1419.365"
                           data-img="the_sentinel.jpg"></a>
                        <a href="book.php?title=The%20Sentinel&author=Lee%20Child&price=1419.365&image=the_sentinel.jpg&description=A%20latest%20Jack%20Reacher%20action-thriller%20novel." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_sentinel.jpg" alt="The Sentinel">
                    </div>
                    <div class="content">
                        <h3>The Sentinel</h3>
                        <div class="price">₹1,419.365 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Sentinel" data-price="1419.365" data-img="the_sentinel.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Mockingjay&author=Suzanne%20Collins&price=1419.365&image=mockingjay.jpg&description=The%20final%20book%20in%20The%20Hunger%20Games%20trilogy." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Mockingjay"
                           data-price="1419.365"
                           data-img="mockingjay.jpg"></a>
                        <a href="book.php?title=Mockingjay&author=Suzanne%20Collins&price=1419.365&image=mockingjay.jpg&description=The%20final%20book%20in%20The%20Hunger%20Games%20trilogy." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="mockingjay.jpg" alt="Mockingjay">
                    </div>
                    <div class="content">
                        <h3>Mockingjay</h3>
                        <div class="price">₹1,419.365 <span>₹1,669.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Mockingjay" data-price="1419.365" data-img="mockingjay.jpg">add to cart</a>
                    </div>
                </div>
            </div>
            <!-- Swiper Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section class="featured" id="featured">
        <h1 class="heading"> <span>Romantic</span> </h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=They%20Both%20Die%20at%20the%20End&author=Adam%20Silver&price=1251.365&image=they%20both%20die_.jpg&description=A%20heartbreaking%20romantic%20novel%20about%20two%20boys%20who%20find%20love%20on%20their%20last%20day%20alive." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="They Both Die at the End"
                           data-price="1251.365"
                           data-img="they_both_die_.jpg"></a>
                        <a href="book.php?title=They%20Both%20Die%20at%20the%20End&author=Adam%20Silver&price=1251.365&image=they%20both%20die_.jpg&description=A%20heartbreaking%20romantic%20novel%20about%20two%20boys%20who%20find%20love%20on%20their%20last%20day%20alive." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="they_both_die_.jpg" alt="They Both Die at the End">
                    </div>
                    <div class="content">
                        <h3>They Both Die at the End</h3>
                        <div class="price">₹1,251.365 <span>₹1,586.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="They Both Die at the End" data-price="1251.365" data-img="they_both_die_.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Red%20White%20%26%20Royal%20Blue&author=Casey%20McQuiston&price=1419.365&image=redwithe.jpg&description=A%20romantic%20comedy%20about%20the%20First%20Son%20of%20the%20United%20States%20and%20a%20British%20prince." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Red White & Royal Blue"
                           data-price="1419.365"
                           data-img="redwithe.jpg"></a>
                        <a href="book.php?title=Red%20White%20%26%20Royal%20Blue&author=Casey%20McQuiston&price=1419.365&image=redwithe.jpg&description=A%20romantic%20comedy%20about%20the%20First%20Son%20of%20the%20United%20States%20and%20a%20British%20prince." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="redwithe.jpg" alt="Red White & Royal Blue">
                    </div>
                    <div class="content">
                        <h3>Red White & Royal Blue</h3>
                        <div class="price">₹1,419.365 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Red White & Royal Blue" data-price="1419.365" data-img="redwithe.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Ugly%20Love&author=Colleen%20Hoover&price=1836.365&image=ugly_love.jpg&description=A%20captivating%20romantic%20novel%20about%20love%2C%20heartbreak%2C%20and%20the%20truth." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Ugly Love"
                           data-price="1836.365"
                           data-img="ugly_love.jpg"></a>
                        <a href="book.php?title=Ugly%20Love&author=Colleen%20Hoover&price=1836.365&image=ugly_love.jpg&description=A%20captivating%20romantic%20novel%20about%20love%2C%20heartbreak%2C%20and%20the%20truth." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="ugly_love.jpg" alt="Ugly Love">
                    </div>
                    <div class="content">
                        <h3>Ugly Love</h3>
                        <div class="price">₹1,836.365 <span>₹2,253.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Ugly Love" data-price="1836.365" data-img="ugly_love.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Wish%20You%20Were%20Here&author=Jodi%20Picoult&price=1670.00&image=wish_you_were_here.jpg&description=A%20moving%20romantic%20story%20of%20love%20and%20loss." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Wish You Were Here"
                           data-price="1670.00"
                           data-img="wish_you_were_here.jpg"></a>
                        <a href="book.php?title=Wish%20You%20Were%20Here&author=Jodi%20Picoult&price=1670.00&image=wish_you_were_here.jpg&description=A%20moving%20romantic%20story%20of%20love%20and%20loss." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="wish_you_were_here.jpg" alt="Wish You Were Here">
                    </div>
                    <div class="content">
                        <h3>Wish You Were Here</h3>
                        <div class="price">₹1,670.00 <span>₹2,086.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Wish You Were Here" data-price="1670.00" data-img="wish_you_were_here.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Notebook&author=Nicholas%20Sparks&price=1251.365&image=the_notebook.jpg&description=A%20classic%20romantic%20novel%20about%20an%20unforgettable%20love%20story." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Notebook"
                           data-price="1251.365"
                           data-img="the_notebook.jpg"></a>
                        <a href="book.php?title=The%20Notebook&author=Nicholas%20Sparks&price=1251.365&image=the_notebook.jpg&description=A%20classic%20romantic%20novel%20about%20an%20unforgettable%20love%20story." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_notebook.jpg" alt="The Notebook">
                    </div>
                    <div class="content">
                        <h3>The Notebook</h3>
                        <div class="price">₹1,251.365 <span>₹1,669.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Notebook" data-price="1251.365" data-img="the_notebook.jpg">add to cart</a>
                    </div>
                </div>
            </div>
            <!-- Swiper Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section class="featured" id="featured">
        <h1 class="heading"> <span>Classic</span> </h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=One%20Hundred%20Years%20of%20Solitude&author=Gabriel%20Garcia%20Marquez&price=1503.165&image=one_hundret_years.jpg&description=A%20masterpiece%20of%20magical%20realism%20and%20a%20classic%20of%20world%20literature." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="One Hundred Years of Solitude"
                           data-price="1503.165"
                           data-img="one_hundret_years.jpg"></a>
                        <a href="book.php?title=One%20Hundred%20Years%20of%20Solitude&author=Gabriel%20Garcia%20Marquez&price=1503.165&image=one_hundret_years.jpg&description=A%20masterpiece%20of%20magical%20realism%20and%20a%20classic%20of%20world%20literature." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="one_hundret_years.jpg" alt="One Hundred Years of Solitude">
                    </div>
                    <div class="content">
                        <h3>One Hundred Years of Solitude</h3>
                        <div class="price">₹1,503.165 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="One Hundred Years of Solitude" data-price="1503.165" data-img="one_hundret_years.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Great%20Gatsby&author=F.%20Scott%20Fitzgerald&price=1251.365&image=great_gatsby.jpg&description=A%20classic%20American%20novel%20exploring%20the%20American%20Dream." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Great Gatsby"
                           data-price="1251.365"
                           data-img="great_gatsby.jpg"></a>
                        <a href="book.php?title=The%20Great%20Gatsby&author=F.%20Scott%20Fitzgerald&price=1251.365&image=great_gatsby.jpg&description=A%20classic%20American%20novel%20exploring%20the%20American%20Dream." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="great_gatsby.jpg" alt="The Great Gatsby">
                    </div>
                    <div class="content">
                        <h3>The Great Gatsby</h3>
                        <div class="price">₹1,251.365 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Great Gatsby" data-price="1251.365" data-img="great_gatsby.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Hamlet&author=William%20Shakespeare&price=1586.365&image=hamletii.jpg&description=A%20tragic%20play%20by%20William%20Shakespeare%20and%20a%20timeless%20classic." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Hamlet"
                           data-price="1586.365"
                           data-img="hamletii.jpg"></a>
                        <a href="book.php?title=Hamlet&author=William%20Shakespeare&price=1586.365&image=hamletii.jpg&description=A%20tragic%20play%20by%20William%20Shakespeare%20and%20a%20timeless%20classic." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="hamletii.jpg" alt="Hamlet">
                    </div>
                    <div class="content">
                        <h3>Hamlet</h3>
                        <div class="price">₹1,586.365 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Hamlet" data-price="1586.365" data-img="hamletii.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Divine%20Comedy&author=Dante%20Alighieri&price=1670.00&image=the_divine_comedy.jpg&description=An%20epic%20poem%20by%20Dante%20Alighieri%20and%20a%20literary%20masterpiece." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Divine Comedy"
                           data-price="1670.00"
                           data-img="the_divine_comedy.jpg"></a>
                        <a href="book.php?title=The%20Divine%20Comedy&author=Dante%20Alighieri&price=1670.00&image=the_divine_comedy.jpg&description=An%20epic%20poem%20by%20Dante%20Alighieri%20and%20a%20literary%20masterpiece." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_divine_comedy.jpg" alt="The Divine Comedy">
                    </div>
                    <div class="content">
                        <h3>The Divine Comedy</h3>
                        <div class="price">₹1,670.00 <span>₹1,997.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Divine Comedy" data-price="1670.00" data-img="the_divine_comedy.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Iliad&author=Homer&price=1670.00&image=iliad.jpg&description=An%20ancient%20Greek%20epic%20poem%20and%20a%20foundational%20work%20of%20Western%20literature." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Iliad"
                           data-price="1670.00"
                           data-img="iliad.jpg"></a>
                        <a href="book.php?title=The%20Iliad&author=Homer&price=1670.00&image=iliad.jpg&description=An%20ancient%20Greek%20epic%20poem%20and%20a%20foundational%20work%20of%20Western%20literature." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="iliad.jpg" alt="The Iliad">
                    </div>
                    <div class="content">
                        <h3>The Iliad</h3>
                        <div class="price">₹1,670.00 <span>₹2,086.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Iliad" data-price="1670.00" data-img="iliad.jpg">add to cart</a>
                    </div>
                </div>
            </div>
            <!-- Swiper Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section class="featured" id="featured">
        <h1 class="heading"> <span>Fantasy</span> </h1>
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Final%20Empire&author=Brandon%20Sanderson&price=1167.365&image=the_final_empire.jpg&description=The%20first%20novel%20in%20the%20Mistborn%20fantasy%20series." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Final Empire"
                           data-price="1167.365"
                           data-img="the_final_empire.jpg"></a>
                        <a href="book.php?title=The%20Final%20Empire&author=Brandon%20Sanderson&price=1167.365&image=the_final_empire.jpg&description=The%20first%20novel%20in%20the%20Mistborn%20fantasy%20series." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_final_empire.jpg" alt="The Final Empire">
                    </div>
                    <div class="content">
                        <h3>The Final Empire</h3>
                        <div class="price">₹1,167.365 <span>₹1,419.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Final Empire" data-price="1167.365" data-img="the_final_empire.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Name%20of%20the%20Wind&author=Patrick%20Rothfuss&price=1419.365&image=the_name_of_the_wind.jpg&description=The%20first%20book%20in%20The%20Kingkiller%20Chronicle%20fantasy%20series." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Name of the Wind"
                           data-price="1419.365"
                           data-img="the_name_of_the_wind.jpg"></a>
                        <a href="book.php?title=The%20Name%20of%20the%20Wind&author=Patrick%20Rothfuss&price=1419.365&image=the_name_of_the_wind.jpg&description=A%20first%20book%20in%20The%20Kingkiller%20Chronicle%20fantasy%20series." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_name_of_the_wind.jpg" alt="The Name of the Wind">
                    </div>
                    <div class="content">
                        <h3>The Name of the Wind</h3>
                        <div class="price">₹1,419.365 <span>₹1,670.00</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Name of the Wind" data-price="1419.365" data-img="the_name_of_the_wind.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=Harry%20Potter%20and%20the%20Sorcerer's%20Stone&author=J.K.%20Rowling&price=1503.165&image=harry_poter.jpg&description=The%20first%20book%20in%20the%20beloved%20Harry%20Potter%20fantasy%20series." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="Harry Potter"
                           data-price="1503.165"
                           data-img="harry_poter.jpg"></a>
                        <a href="book.php?title=Harry%20Potter%20and%20the%20Sorcerer's%20Stone&author=J.K.%20Rowling&price=1503.165&image=harry_poter.jpg&description=The%20first%20book%20in%20the%20beloved%20Harry%20Potter%20fantasy%20series." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="harry_poter.jpg" alt="Harry Potter">
                    </div>
                    <div class="content">
                        <h3>Harry Potter</h3>
                        <div class="price">₹1,503.165 <span>₹1,747.335</span></div>
                        <a href="#" class="btn add-to-cart" data-title="Harry Potter" data-price="1503.165" data-img="harry_poter.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Lord%20of%20the%20Rings&author=J.R.R.%20Tolkien&price=1003.165&image=the_lord_of_the_rings.jpg&description=A%20classic%20high%20fantasy%20novel%20series%20set%20in%20Middle-earth." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Lord of the Rings"
                           data-price="1003.165"
                           data-img="the_lord_of_the_rings.jpg"></a>
                        <a href="book.php?title=The%20Lord%20of%20the%20Rings&author=J.R.R.%20Tolkien&price=1003.165&image=the_lord_of_the_rings.jpg&description=A%20classic%20high%20fantasy%20novel%20series%20set%20in%20Middle-earth." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_lord_of_the_rings.jpg" alt="The Lord of the Rings">
                    </div>
                    <div class="content">
                        <h3>The Lord of the Rings</h3>
                        <div class="price">₹1,003.165 <span>₹1,335.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Lord of the Rings" data-price="1003.165" data-img="the_lord_of_the_rings.jpg">add to cart</a>
                    </div>
                </div>
                <div class="swiper-slide box">
                    <div class="icons">
                        <a href="book.php?title=The%20Witcher&author=Andrzej%20Sapkowski&price=1250.00&image=the_witcher.jpg&description=A%20fantasy%20series%20following%20the%20adventures%20of%20Geralt%20of%20Rivia." class="fas fa-search"></a>
                        <!-- MODIFIED: Added add-to-wishlist class and data attributes -->
                        <a href="#" class="fas fa-heart add-to-wishlist"
                           data-title="The Witcher"
                           data-price="1250.00"
                           data-img="the_witcher.jpg"></a>
                        <a href="book.php?title=The%20Witcher&author=Andrzej%20Sapkowski&price=1250.00&image=the_witcher.jpg&description=A%20fantasy%20series%20following%20the%20adventures%20of%20Geralt%20of%20Rivia." class="fas fa-eye"></a>
                    </div>
                    <div class="image">
                        <img src="the_witcher.jpg" alt="The Witcher">
                    </div>
                    <div class="content">
                        <h3>The Witcher</h3>
                        <div class="price">₹1,250.00 <span>₹1,499.365</span></div>
                        <a href="#" class="btn add-to-cart" data-title="The Witcher" data-price="1250.00" data-img="the_witcher.jpg">add to cart</a>
                    </div>
                </div>
            </div>
            <!-- Swiper Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <!-- Custom Message Modal HTML -->
    <div id="message-modal">
        <h3 id="message-modal-title">Notification</h3>
        <p id="message-modal-content">Book added to cart successfully!</p>
        <button class="close-btn" onclick="closeMessageModal()">Close</button>
    </div>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script src="javascript.js"></script>

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



        // Swiper initialization (from your original file - MODIFIED TO INCLUDE NAVIGATION)
        // Ensure this script block runs AFTER the Swiper CSS and JS are loaded.
        var swiper = new Swiper(".featured-slider", {
            spaceBetween: 10,
            loop: true,
            centeredSlides: true,
            autoplay: {
                delay: 9500,
                disableOnInteraction: false,
            },
            // ADDED NAVIGATION PARAMETER
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                450: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
            },
        });

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
                // This ensures that prices like "₹1,503.00" become "1503.00"
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

        // NEW: Add to Wishlist functionality with AJAX
        document.querySelectorAll('.add-to-wishlist').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault(); // Prevent the default anchor link behavior

                const title = this.getAttribute('data-title');
                const priceRaw = this.getAttribute('data-price');
                const img = this.getAttribute('data-img');

                // Clean the price: remove any character that is not a digit or a dot.
                const price = priceRaw.replace(/[^0-9.]/g, ''); 

                // Create FormData object to send data.
                const formData = new FormData();
                formData.append('title', title);
                formData.append('price', price);
                formData.append('img', img);

                try {
                    // Send an asynchronous POST request to add_to_wishlist.php
                    const response = await fetch('add_to_wishlist.php', {
                        method: 'POST', // HTTP method
                        body: formData // The data to send
                    });

                    // Check if the HTTP response was successful (status code 200-299)
                    if (!response.ok) {
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
                    console.error('Error adding to wishlist:', error);
                    displayMessageModal('An unexpected error occurred while adding to wishlist. Please try again.', false);
                }
            });
        });


        // Event listener for the main cart button (fas fa-shopping-cart) in the header.
        // Currently, it displays a generic message. You can expand this later to fetch and display
        // actual cart contents from the database.
        document.getElementById('cart-btn').onclick = function(e) {
            e.preventDefault(); // Prevent default link behavior
            displayMessageModal("Your cart details would appear here if implemented fully.", true);
        };


        // SEARCH FUNCTIONALITY (from your original file, kept as is)
        document.getElementById('search-box').addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
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

        // Login form display (from your original file, kept as is)
        document.querySelector('#login-btn').onclick = () => {
            document.querySelector('.login-form-container').classList.toggle('active');
        }

        document.querySelector('#close-login-btn').onclick = () => {
            document.querySelector('.login-form-container').classList.remove('active');
        }

        
    </script>

</body>

</html>
