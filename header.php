<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerseWell - Your Bookstore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .user-info {
            display: flex;
            align-items: center;
            margin-right: 15px;
            color: #fff;
        }
        .user-info i {
            margin-right: 8px;
            font-size: 1.2rem;
        }
        .header-1 .icons {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- header section starts  -->
    <header class="header">
        <div class="header-1">
            <a href="index.php" class="logo"> <i class="fas fa-book"></i> VerseWell </a>

            <form action="search.php" method="GET" class="search-form">
                <input type="search" name="query" placeholder="Search books..." id="search-box">
                <button type="submit" class="fas fa-search"></button>
            </form>

            <div class="icons">
                <div id="search-btn" class="fas fa-search"></div>
                <a href="wishlist.php" class="fas fa-heart"></a>
                <a href="cart.php" class="fas fa-shopping-cart" id="cart-btn"></a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?></span>
                    </div>
                    <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                <?php else: ?>
                    <a href="login.php" id="login-btn" class="fas fa-user"></a>
                <?php endif; ?>
            </div>
        </div>

        <div class="header-2">
            <nav class="navbar">
                <a href="index.php">home</a>
                <a href="featured.php">featured</a>
                <a href="blogs.php">blogs</a>
                <a href="genres.php">genres</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="active">my account</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <!-- header section ends -->

    <!-- bottom navbar  -->
    <nav class="bottom-navbar">
        <a href="index.php" class="fas fa-home"></a>
        <a href="featured.php" class="fas fa-list"></a>
        <a href="genres.php" class="fas fa-tags"></a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php" class="fas fa-user"></a>
        <?php endif; ?>
    </nav>

    <!-- login form container (hidden by default) -->
    <?php if(!isset($_SESSION['user_id'])): ?>
    <div class="login-form-container">
        <div id="close-login-btn" class="fas fa-times"></div>
        <form action="login.php" method="post">
            <h3>sign in</h3>
            <span>username</span>
            <input type="text" name="username" class="box" placeholder="enter your username" required>
            <span>password</span>
            <input type="password" name="password" class="box" placeholder="enter your password" required>
            <div class="checkbox">
                <input type="checkbox" name="remember-me" id="remember-me">
                <label for="remember-me"> remember me</label>
            </div>
            <input type="submit" value="sign in" class="btn">
            <p>forget password ? <a href="forgot-password.php">click here</a></p>
            <p>don't have an account ? <a href="signup.php">create one</a></p>
        </form>
    </div>
    <?php endif; ?>

    <script>
        // Toggle login form
        document.getElementById('search-btn').onclick = () => {
            document.querySelector('.search-form').classList.toggle('active');
        }

        document.getElementById('login-btn')?.onclick = () => {
            document.querySelector('.login-form-container').classList.toggle('active');
        }

        document.getElementById('close-login-btn')?.onclick = () => {
            document.querySelector('.login-form-container').classList.remove('active');
        }

        // Cart indicator
        document.addEventListener('DOMContentLoaded', () => {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length > 0) {
                const cartBtn = document.getElementById('cart-btn');
                const indicator = document.createElement('span');
                indicator.style.position = 'absolute';
                indicator.style.top = '-5px';
                indicator.style.right = '-5px';
                indicator.style.backgroundColor = '#e74c3c';
                indicator.style.color = 'white';
                indicator.style.borderRadius = '50%';
                indicator.style.width = '18px';
                indicator.style.height = '18px';
                indicator.style.display = 'flex';
                indicator.style.justifyContent = 'center';
                indicator.style.alignItems = 'center';
                indicator.style.fontSize = '12px';
                indicator.textContent = cart.length;
                cartBtn.style.position = 'relative';
                cartBtn.appendChild(indicator);
            }
        });
    </script>