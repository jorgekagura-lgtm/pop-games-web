<?php
// Credenciales nativas internas de Render MySQL
$host = 'mysql-7fbb'; 
$db   = 'popgames_db';
$user = 'jorge';
$pass = 'Uunf62DbeQeBwVpGptgT0X1K9p1N4v4m';
$port = '3306';

try {
    // Intentamos la conexión directa y limpia a MySQL
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error crítico de conexión en Render: " . $e->getMessage());
}
?>
