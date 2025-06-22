<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Authentication successful
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                
                // Update last login time
                $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                $stmt->execute([$user['user_id']]);
                
                // Redirect to home page
                header("Location: html.php");
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
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
    <title>Login - VerseWell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
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
        .error {
            color: #e74c3c; /* Keep specific error color, or map to theme's error color */
            margin-bottom: 15px;
            text-align: center;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .remember-me {
            display: flex;
            align-items: center;
        }
        .remember-me input {
            width: auto;
            margin-right: 5px;
        }
        .remember-me label {
            color: var(--light-color); /* Use CSS variable */
        }
        .forgot-password a {
            color: var(--orange); /* Use CSS variable */
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
        }
        .signup-link a {
            color: var(--orange); /* Use CSS variable */
            text-decoration: none;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <i class="fas fa-book"></i> VerseWell
        </div>
        <h1>Sign In</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember-me" name="remember-me">
                    <label for="remember-me">Remember me</label>
                </div>
                <div class="forgot-password">
                    <a href="forgot-password.php">Forgot password?</a>
                </div>
            </div>
            
            <button type="submit">Sign In</button>
        </form>
        
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Create one</a>
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
