<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Handle form submissions
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update profile info
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        $country = trim($_POST['country']);
        $postal_code = trim($_POST['postal_code']);

        try {
            $stmt = $pdo->prepare("UPDATE users SET 
                first_name = ?, last_name = ?, email = ?, phone = ?,
                address = ?, city = ?, state = ?, country = ?, postal_code = ?,
                updated_at = NOW() WHERE user_id = ?");
            
            $stmt->execute([
                $first_name, $last_name, $email, $phone,
                $address, $city, $state, $country, $postal_code,
                $_SESSION['user_id']
            ]);
            
            // Update session
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
            
            $success = 'Profile updated successfully!';
            $user = array_merge($user, [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'postal_code' => $postal_code
            ]);
            
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    } 
    elseif (isset($_POST['change_password'])) {
        // Change password
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'All password fields are required';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } elseif (!password_verify($current_password, $user['password'])) {
            $error = 'Current password is incorrect';
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                $stmt->execute([$hashed_password, $_SESSION['user_id']]);
                $success = 'Password changed successfully!';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - VerseWell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary:  #b6862c;
            --primary-dark: #4e2e0e;
            --secondary: #f8f9fa;
            --danger: #e74c3c;
            --success: #27ae60;
            --warning: #f39c12;
            --info: #3498db;
            --light: #f5f5f5;
            --dark: #333;
            --white: #fff;
            --gray: #ddd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        /* Header Styles */
        .header {
            background-color: var(--primary);
            padding: 1rem 5%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-1 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .logo {
            color: var(--white);
            font-size: 1.8rem;
            font-weight: bold;
            text-decoration: none;
        }
        
        .logo i {
            margin-right: 0.5rem;
        }
        
        .search-form {
            display: flex;
            align-items: center;
            background: var(--white);
            border-radius: 4px;
            padding: 0.5rem;
            width: 40%;
        }
        
        .search-form input {
            border: none;
            outline: none;
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
        }
        
        .search-form button {
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 1rem;
        }
        
        .icons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .icons a, .icons div {
            color: var(--white);
            font-size: 1.2rem;
            cursor: pointer;
            position: relative;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .user-info i {
            font-size: 1.2rem;
        }
        
        .header-2 {
            background-color: var(--primary-dark);
            padding: 0.8rem 0;
        }
        
        .navbar {
            display: flex;
            justify-content: center;
            gap: 2rem;
        }
        
        .navbar a {
            color: var(--white);
            text-decoration: none;
            text-transform: capitalize;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .navbar a:hover {
            opacity: 0.8;
        }
        
        .bottom-navbar {
            display: none;
            justify-content: space-around;
            background: var(--primary);
            padding: 1rem;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .bottom-navbar a {
            color: var(--white);
            font-size: 1.2rem;
        }
        
        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .profile-header h1 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        /* Tabs */
        .tab-container {
            margin-bottom: 2rem;
        }
        
        .tab-buttons {
            display: flex;
            border-bottom: 1px solid var(--gray);
        }
        
        .tab-btn {
            padding: 0.8rem 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab-btn.active {
            border-bottom: 3px solid var(--primary);
            color: var(--primary);
            font-weight: bold;
        }
        
        .tab-content {
            display: none;
            padding: 1.5rem 0;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Profile Sections */
        .profile-section {
            background: var(--white);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .profile-section h2 {
            color: var(--primary);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--gray);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--gray);
            border-radius: 4px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            outline: none;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            background-color: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
        }
        
        /* Messages */
        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        
        .error {
            background-color: #fdecea;
            color: var(--danger);
        }
        
        .success {
            background-color: #e8f5e9;
            color: var(--success);
        }
        
        /* Order History */
        .order-history {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .order-history th,
        .order-history td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid var(--gray);
        }
        
        .order-history th {
            background-color: var(--secondary);
            font-weight: 500;
        }
        
        .order-history tr:hover {
            background-color: var(--light);
        }
        
        .status-pending { color: var(--warning); }
        .status-processing { color: var(--info); }
        .status-shipped { color: var(--success); }
        .status-delivered { color: var(--success); font-weight: bold; }
        .status-cancelled { color: var(--danger); }
        
        /* Footer */
        .footer {
            background-color: var(--dark);
            color: var(--white);
            padding: 3rem 5% 1.5rem;
        }
        
        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .box h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: var(--white);
        }
        
        .box a {
            display: block;
            color: var(--gray);
            margin-bottom: 0.8rem;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .box a:hover {
            color: var(--white);
        }
        
        .box a i {
            margin-right: 0.5rem;
        }
        
        .share {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .share a {
            color: var(--white);
            font-size: 1.5rem;
            transition: opacity 0.3s;
        }
        
        .share a:hover {
            opacity: 0.8;
        }
        
        .credit {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .credit span {
            color: var(--primary);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-1 {
                flex-direction: column;
                gap: 1rem;
            }
            
            .search-form {
                width: 100%;
            }
            
            .navbar {
                display: none;
            }
            
            .bottom-navbar {
                display: flex;
            }
            
            .box-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    
        
        <div class="header-2">
            
        </div>
    </header>
    
    <!-- Bottom Navigation (Mobile) -->
    <nav class="bottom-navbar">
        <a href="index.php" class="fas fa-home"></a>
        <a href="featured.php" class="fas fa-list"></a>
        <a href="genres.php" class="fas fa-tags"></a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php" class="fas fa-user"></a>
        <?php endif; ?>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="profile-header">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['first_name'].'+'.$user['last_name']); ?>&background=4a6fa5&color=fff&size=120" 
                 alt="Profile Image" class="profile-avatar">
            <h1><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            <p>Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
        </div>

        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="tab-container">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="openTab(event, 'profile-tab')">Profile</button>
                <button class="tab-btn" onclick="openTab(event, 'password-tab')">Password</button>
               
            </div>
        </div>

        <div id="profile-tab" class="tab-content active">
            <form method="post">
                <div class="profile-section">
                    <h2>Personal Information</h2>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                </div>
                
                <div class="profile-section">
                    <h2>Address Information</h2>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="state">State/Province</label>
                        <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="postal_code">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>">
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn">Update Profile</button>
                </div>
            </form>
        </div>

        <div id="password-tab" class="tab-content">
            <div class="profile-section">
                <h2>Change Password</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" name="change_password" class="btn">Change Password</button>
                </form>
            </div>
        </div>

       
    </section>

    <script>
        // Tab functionality
        function openTab(evt, tabName) {
            const tabcontent = document.getElementsByClassName("tab-content");
            for (let i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            
            const tabbuttons = document.getElementsByClassName("tab-btn");
            for (let i = 0; i < tabbuttons.length; i++) {
                tabbuttons[i].classList.remove("active");
            }
            
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
        
        // Search button toggle
        document.getElementById('search-btn').onclick = () => {
            document.querySelector('.search-form').classList.toggle('active');
        }
        
        // Cart item count indicator
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
</body>
</html>