<?php
session_start();
include 'db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['users_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['users_id'];
$mensaje = "";

// Obtener datos actuales del usuario
$stmt = $conn->prepare("SELECT nombre, apellidos FROM users WHERE users_id = :users_id");
$stmt->bindParam(':users_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Procesar la actualización del perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);

    if (!empty($nombre) && !empty($apellidos)) {
        try {
            $stmt = $conn->prepare("UPDATE users SET nombre = :nombre, apellidos = :apellidos WHERE users_id = :users_id");
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':users_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $mensaje = "Perfil actualizado correctamente.";
            // Actualizar sesión con los nuevos datos
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellidos'] = $apellidos;
        } catch (PDOException $e) {
            $mensaje = "Error al actualizar: " . $e->getMessage();
        }
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil | AppTask</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <div class="logo-container">
            <a href="index.php" class="logo-link"><img src="imgs/logo.jpg" alt="Apptask" class="logo"></a>
            <div class="app-name">AppTask</div>
        </div>
    </header>
    <main>
        <section class="main-section">
            <div class="profile-container">
                <h2>Editar Perfil</h2>
                <?php if (!empty($mensaje)) echo "<p class='message'>$mensaje</p>"; ?>

                <form action="edit_profile.php" method="POST">
                    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']); ?>" required>
                    <input type="text" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']); ?>" required>
                    <button type="submit">Guardar Cambios</button>
                </form>

                <a href="tasks.php">Volver a Tareas</a>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 AppTask. Todos los derechos reservados.</p>
    </footer>
</body>

</html>