<?php
$host = 'localhost';
$db   = 'mlm_system';
$user = 'root';
$pass = ''; // Enter your MySQL password here
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Helper function to get dynamic settings from DB
function getSetting($pdo, $key) {
    $stmt = $pdo->prepare("SELECT meta_value FROM settings WHERE meta_key = ?");
    $stmt->execute([$key]);
    return $stmt->fetchColumn();
}
?>