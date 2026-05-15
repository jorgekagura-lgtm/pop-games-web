<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - GameDB</title>
    <link rel="stylesheet" href="login.css"> </head>
<body>
    <div class="login-box">
        <h2>Crear Cuenta</h2>
        <form action="procesar_registro.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre Completo" required>
            <input type="text" name="apellido" placeholder="Apellido Completo" required>
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
            <input type="text" name="usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            
            <input type="submit" value="Registrarse">
        </form>
        <p style="text-align: center; margin-top: 10px;">
            <a href="login.php" style="color: white; font-size: 0.8em;">¿Ya tienes cuenta? Inicia sesión</a>
        </p>
    </div>
</body>
</html>