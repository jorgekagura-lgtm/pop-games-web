<?php
session_start();
require 'db.php'; // Conexión a pop_games

// Si ya está logueado, redirigir a index.php
if(isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Usamos trim para evitar espacios accidentales
    $user = trim($_POST['usuario'] ?? '');
    $pass = trim($_POST['clave'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$user]);
    $usuario_db = $stmt->fetch();

if ($usuario_db) {
    $hash_en_db = $usuario_db['password'];

    // 1. Intentamos verificar si es una contraseña encriptada (para nuevos registros)
    // 2. Si falla, verificamos si es texto plano (para tus usuarios antiguos)
    if (password_verify($pass, $hash_en_db) || $pass === $hash_en_db) {
        
        $_SESSION['usuario'] = $usuario_db['username'];
        header("Location: index.php");
        exit;
        
    } else {
        $error = "Contraseña incorrecta para el usuario $user.";
    }
} else {
    $error = "El usuario '$user' no existe en la base de datos.";
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - pop_games</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-box">
        <h2>Login pop_games</h2>

        <?php if($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

<form method="post">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <input type="submit" value="Entrar">
        </form>

        <div class="registro-link" style="text-align: center; margin-top: 15px;">
            <p style="color: #ccc; font-size: 0.9em;">¿No tienes cuenta?</p>
            <a href="registro.php" style="color: #ff9800; text-decoration: none; font-weight: bold; font-size: 0.9em;">Registrate aquí</a>
        </div>
    </div>
</body>
</html>