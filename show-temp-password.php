<?php
session_start();

if (!isset($_SESSION['temp_password']) || !isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit();
}

$temp_password = $_SESSION['temp_password'];
$email = $_SESSION['reset_email'];

// Clear the session variables
unset($_SESSION['temp_password']);
unset($_SESSION['reset_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temporary Password - VerseWell</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Same styles as before */
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
            max-width: 500px;
            text-align: center;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #4a6fa5;
        }
        .temp-password {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            word-break: break-all;
        }
        .note {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4a6fa5;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #3a5a80;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <i class="fas fa-book"></i> VerseWell
        </div>
        <h1>Temporary Password Generated</h1>
        
        <p>For account: <strong><?php echo htmlspecialchars($email); ?></strong></p>
        
        <div class="temp-password">
            Your temporary password: <?php echo htmlspecialchars($temp_password); ?>
        </div>
        
        <p class="note">
            This password is valid for 1 hour. Please change it after logging in.
        </p>
        
        <a href="login.php" class="btn">Go to Login Page</a>
    </div>
</body>
</html>