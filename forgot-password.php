<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $message = 'Please enter your email address.';
    } else {
        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Generate a temporary password (8 characters)
                $temp_password = bin2hex(random_bytes(4)); // 8-character temporary password
                $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
                $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));
                
                // Update user with temporary password
                $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_expires = ? WHERE email = ?");
                $stmt->execute([$hashed_password, $expires, $email]);
                
                // Store in session to show on next page
                $_SESSION['temp_password'] = $temp_password;
                $_SESSION['reset_email'] = $email;
                
                header("Location: show-temp-password.php");
                exit();
            } else {
                // Generic message for security
                $message = "If an account exists with this email, a temporary password has been generated.";
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - VerseWell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        body {
            /* These styles are now controlled by style.css variables for dark mode */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: var(--background); /* Use CSS variable */
            color: var(--text-color); /* Use CSS variable */
        }
        .container {
            background: var(--heading-background); /* Use CSS variable */
            border-radius: 8px;
            box-shadow: var(--box-shadow); /* Use CSS variable */
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: var(--text-color); /* Use CSS variable */
            margin-bottom: 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: var(--orange); /* Use CSS variable */
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--text-color); /* Use CSS variable */
        }
        input {
            width: 100%;
            padding: 10px;
            border: var(--border); /* Use CSS variable */
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            background: var(--search-background); /* Use CSS variable */
            color: var(--text-color); /* Use CSS variable */
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: var(--orange); /* Use CSS variable */
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: var(--dark-color); /* Use CSS variable */
        }
        .message {
            margin-bottom: 15px;
            text-align: center;
            color: var(--light-color); /* Use CSS variable for messages */
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: var(--orange); /* Use CSS variable */
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <i class="fas fa-book"></i> VerseWell
        </div>
        <h1>Forgot Password</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <form action="forgot-password.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <button type="submit">Generate Temporary Password</button>
        </form>
        
        <div class="login-link">
            Remember your password? <a href="login.php">Sign in</a>
        </div>
    </div>

    <script>
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
