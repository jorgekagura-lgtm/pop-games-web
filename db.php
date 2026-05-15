<?php
$host = trim(getenv('DB_HOST'));
$db   = trim(getenv('DB_NAME'));
$user = trim(getenv('DB_USER'));
$pass = trim(getenv('DB_PASS'));

// Esto nos ayudará a ver si Render está cargando bien las variables
if (empty($host)) {
    die("Error: El Host de la base de datos está vacío en las variables de entorno.");
}

try {
    // Intentamos la conexión
    $dsn = "mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Si llega aquí, es que ha funcionado
} catch (PDOException $e) {
    // Imprime el error detallado
    die("Fallo de conexión en el servidor: " . $e->getMessage() . " | Host intentado: " . $host);
}
?>
