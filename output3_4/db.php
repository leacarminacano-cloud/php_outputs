<?php
// Database connection (XAMPP/WAMP defaults) - adjust if your setup differs.
$host = 'localhost';
$name = 'php_exercises';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $ex) {
    die('Database connection failed. Did you import database.sql? Details: ' . htmlspecialchars($ex->getMessage()));
}
