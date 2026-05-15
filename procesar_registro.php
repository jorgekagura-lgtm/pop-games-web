<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $user = trim($_POST['usuario']);
    $pass = $_POST['password'];

    // 1. Encriptar la contraseña (Muy importante para seguridad profesional)
    $pass_encriptada = password_hash($pass, PASSWORD_DEFAULT);

    try {
        // 2. Insertar en la base de datos
        $sql = "INSERT INTO usuarios (username, password, nombre_completo, apellido_completo, correo) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user, $pass_encriptada, $nombre, $apellido, $correo]);

        echo "<script>
                alert('Registro exitoso. Ahora puedes iniciar sesión.');
                window.location.href = 'login.php';
              </script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Error de duplicado (usuario o correo ya existen)
            echo "<script>alert('El usuario o el correo ya están registrados.'); window.history.back();</script>";
        } else {
            echo "Error al registrar: " . $e->getMessage();
        }
    }
}