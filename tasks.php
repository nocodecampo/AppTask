<?php
session_start();
include 'db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['users_id'])) {
    header("Location: login.php");
    exit();
}

$users_id = $_SESSION['users_id'];
$mensaje = "";

// 🔹 CREAR TAREA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["crear_tarea"])) {
    $titulo = trim($_POST["titulo"]);
    $descripcion = trim($_POST["descripcion"]);

    if (!empty($titulo)) {
        try {
            $stmt = $conn->prepare("INSERT INTO tasks (users_id, titulo, descripcion, estado) VALUES (:users_id, :titulo, :descripcion, 'en proceso')");
            $stmt->bindParam(':users_id', $users_id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->execute();
            $mensaje = "Tarea creada exitosamente.";
        } catch (PDOException $e) {
            $mensaje = "Error al crear la tarea: " . $e->getMessage();
        }
    } else {
        $mensaje = "El título de la tarea es obligatorio.";
    }
}

// ELIMINAR TAREA
if (isset($_GET["eliminar"])) {
    $tarea_id = $_GET["eliminar"];
    try {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE tasks_id = :tasks_id AND users_id = :users_id");
        $stmt->bindParam(':tasks_id', $tarea_id, PDO::PARAM_INT);
        $stmt->bindParam(':users_id', $users_id, PDO::PARAM_INT);
        $stmt->execute();
        $mensaje = "Tarea eliminada.";
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar la tarea: " . $e->getMessage();
    }
}

// OBTENER TODAS LAS TAREAS DEL USUARIO
try {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE users_id = :users_id ORDER BY fecha_creacion DESC");
    $stmt->bindParam(':users_id', $users_id, PDO::PARAM_INT);
    $stmt->execute();
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener tareas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas | AppTask</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="dashboard-container">
        <h2>👋 Bienvenido/a, <?= htmlspecialchars($_SESSION['username']); ?></h2>
        <a href="logout.php" class="logout">Cerrar sesión</a>

        <h3>Crear Nueva Tarea</h3>
        <?php if (!empty($mensaje)) echo "<p class='message'>$mensaje</p>"; ?>
        <form action="tasks.php" method="POST">
            <input type="text" name="titulo" placeholder="Título de la tarea" required>
            <textarea name="descripcion" placeholder="Descripción (opcional)"></textarea>
            <button type="submit" name="crear_tarea">Añadir Tarea</button>
        </form>

        <h3>📋 Mis Tareas</h3>
        <table>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($tareas as $tarea): ?>
                <tr>
                    <td><?= htmlspecialchars($tarea['titulo']); ?></td>
                    <td><?= htmlspecialchars($tarea['descripcion']); ?></td>
                    <td><?= htmlspecialchars($tarea['estado']); ?></td>
                    <td>
                        <a href="edit_task.php?id=<?= $tarea['tasks_id']; ?>">Editar</a>
                        <a href="tasks.php?eliminar=<?= $tarea['tasks_id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>