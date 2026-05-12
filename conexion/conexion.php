<?php



$host = 'bnjl2t3noxnxdjhsej2s-mysql.services.clever-cloud.com';
$dbName = 'bnjl2t3noxnxdjhsej2s';
$user = 'uaw4cd6yx9pstbm0';
$password = 'J0BJDbgy4mqMCT0KDLJI';
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
