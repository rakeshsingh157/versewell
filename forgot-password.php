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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #4a6fa5;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4a6fa5;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #3a5a80;
        }
        .message {
            margin-bottom: 15px;
            text-align: center;
            color: #4a6fa5;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #4a6fa5;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
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
</body>
</html>