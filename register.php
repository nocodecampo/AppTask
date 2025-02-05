<?php
session_start();
include 'db.php';

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);

    // Validar campos vacíos
    if (empty($username) || empty($password) || empty($confirm_password) || empty($nombre) || empty($apellidos)) {
        $mensaje = "Todos los campos son obligatorios.";
    } elseif ($password !== $confirm_password) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        try {
            // Verificar si el usuario ya existe
            $stmt = $conn->prepare("SELECT users_id FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->fetch()) {
                $mensaje = "El nombre de usuario ya está en uso.";
            } else {
                // Encriptar la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insertar usuario en la base de datos
                $stmt = $conn->prepare("INSERT INTO users (username, password, nombre, apellidos) VALUES (:username, :password, :nombre, :apellidos)");
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    header("Location: login.php"); // Redirigir al login tras registro exitoso
                    exit();
                } else {
                    $mensaje = "Error al registrar usuario.";
                }
            }
        } catch (PDOException $e) {
            $mensaje = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | AppTask</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    
    <div class="app-name">AppTask</div>
    <div class="register-container">
        <h2>Registro de Usuario</h2>
        <?php if (!empty($mensaje)) echo "<p class='error'>$mensaje</p>"; ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
    <footer>
        <p>&copy; 2025 AppTask. Todos los derechos reservados.</p>
    </footer>
</body>

</html>