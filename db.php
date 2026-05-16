<?php
// Credenciales internas de Render MySQL
$host = 'mysql-7fbb'; // Usamos el "Internal Database URL" simplificado o el valor de MySQL Host
$db   = 'popgames_db';
$user = 'jorge';
$pass = 'Uunf62DbeQeBwVpGptgT0X1K9p1N4v4m'; // Tu contraseña de la captura
$port = '3306';

try {
    // Intentamos la conexión PDO
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Si falla internamente, probamos con el Host largo externo por si acaso
    try {
        $host_ext = 'dpg-cu76b65u9vis73cm4t00-a.frankfurt-postgres.render.com'; // Dirección externa si se requiere
        $dsn_ext = "mysql:host=$host_ext;port=$port;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn_ext, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $err) {
        die("Error de conexión definitivo en Render: " . $err->getMessage());
    }
}
?>
