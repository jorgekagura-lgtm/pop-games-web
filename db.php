<?php
// Datos de Clever Cloud
$host = 'b3q2bwopggb0l4rebljf-mysql.services.clever-cloud.com';
$db   = 'b3q2bwopggb0l4rebljf';
$user = 'usvodfulqfnmchzk';
$pass = 'W1TC6p5syYHXPgXdLi5k'; // Haz clic en el candado para verla
$port = '3306';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Si llegas aquí, ¡la conexión funciona!
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>