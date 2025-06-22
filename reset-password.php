<?php
require 'db.php';

$error = '';
$success = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token is valid and not expired
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $error = 'Invalid or expired token. Please request a new password reset.';
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
} else {
    $error = 'No token provided.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error)) {
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if (empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        try {
            // Hash new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Update password and clear reset token
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
            $stmt->execute([$hashed_password, $token]);
            
            $success = 'Password updated successfully. You can now <a href="login.php">login</a> with your new password.';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - VerseWell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        /* Same styles as forgot-password.php - converted to use variables */
        body {
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
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: var(--dark-color); /* Use CSS variable */
        }
        .error {
            color: #e74c3c; /* Keep specific error color */
            margin-bottom: 15px;
            text-align: center;
        }
        .success {
            color: #2ecc71; /* Keep specific success color */
            margin-bottom: 15px;
            text-align: center;
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
        <h1>Reset Password</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php else: ?>
            <form action="reset-password.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
                
                <button type="submit">Reset Password</button>
            </form>
        <?php endif; ?>
        
        <div class="login-link">
            <a href="login.php">Back to Login</a>
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
