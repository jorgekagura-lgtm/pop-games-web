<?php
// Conexión limpia a la base de datos MySQL de Clever Cloud
$host = 'b3q2bwopggb014rebljf-mysql.services.clever-cloud.com'; 
$db   = 'b3q2bwopggb014rebljf';
$user = 'usvodfulqfnmchzk';
$pass = 'W1TC6p5syYHXPgXdLi5k';
$port = '3306';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a Clever Cloud: " . $e->getMessage());
}
?>
