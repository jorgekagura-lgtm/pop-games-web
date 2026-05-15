<?php
// Limpiamos cualquier espacio en blanco que pueda venir de las variables
$host = trim(getenv('DB_HOST'));
$db   = trim(getenv('DB_NAME'));
$user = trim(getenv('DB_USER'));
$pass = trim(getenv('DB_PASS'));
$port = '3306';

try {
    // Verificamos que las variables no estén vacías
    if (!$host || !$db) {
        throw new Exception("Las variables de entorno no se han cargado correctamente.");
    }

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
