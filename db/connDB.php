<?php

$host = "127.0.0.1";
$db = "actuli";
$user = "root";
$password = "root";
$port = "3306:3306";
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
$option = [
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new \PDO($dsn, $user, $password, $option);
} catch (\PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
}
