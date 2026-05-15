<?php
// Ponemos los datos reales directamente para evitar el bloqueo de getenv en Docker
$host = 'b3q2bwopggb0l4rebljf-mysql.services.clever-cloud.com';
$db   = 'b3q2bwopggb0l4rebljf';
$user = 'usvodfulqfnmchzk';
$pass = 'W1TC6p5syYHXpgXdLi5k';
$port = '3306';

try {
    $dsn = "mysql:host=b3q2bwopggb0l4rebljf-mysql.services.clever-cloud.com;port=3306;dbname=b3q2bwopggb0l4rebljf;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Si quieres comprobar que conecta bien, puedes descomentar la siguiente línea:
    // echo "¡Conexión de servidor exitosa!";
    
} catch (PDOException $e) {
    die("Error crítico de conexión en la nube: " . $e->getMessage());
}
?>
