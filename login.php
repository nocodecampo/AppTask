<?php
session_start();
include 'db.php';

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        try {
            // Buscar el usuario en la base de datos
            $stmt = $conn->prepare("SELECT users_id, username, password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el usuario existe y la contraseña es correcta
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['users_id'] = $user['users_id'];
                $_SESSION['username'] = $user['username'];
                header("Location: tasks.php"); // Redirigir a la página de tareas
                exit();
            } else {
                $mensaje = "Usuario o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $mensaje = "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        $mensaje = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AppTask</title>
    <link rel="stylesheet" href="styles.css"> <!-- Agrega tu CSS si tienes -->
</head>

<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if (!empty($mensaje)) echo "<p class='error'>$mensaje</p>"; ?>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
        <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
</body>

</html>