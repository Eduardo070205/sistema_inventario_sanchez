<?php



$host = 'localhost';
$dbName = 'bd_materiales_sanchez';
$user = 'root';
$password = 'Eduardo10';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    die('Conexión fallida: ' . $e->getMessage());
}

return $pdo;
