<?php
// db.php - Database connection
$host = 'database-1.cav0my0c6v1m.us-east-1.rds.amazonaws.com';
$dbname = 'versewell';
$username = 'admin'; // Change to your database username
$password = 'DBpicshot'; // Change to your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>