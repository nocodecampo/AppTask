<?php
session_start();
include 'db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['users_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar que el parámetro tasks_id esté presente
if (!isset($_GET['tasks_id'])) {
    header("Location: tasks.php");
    exit();
}

$tarea_id = $_GET['tasks_id'];
$user_id = $_SESSION['users_id'];

// Obtener la tarea del usuario
$stmt = $conn->prepare("SELECT * FROM tasks WHERE tasks_id = :tasks_id AND users_id = :users_id");
$stmt->bindParam(':tasks_id', $tarea_id, PDO::PARAM_INT);
$stmt->bindParam(':users_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$tarea = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra la tarea o no pertenece al usuario, redirigir al listado de tareas
if (!$tarea) {
    header("Location: tasks.php");
    exit();
}

// Actualizar tarea
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $descripcion = trim($_POST["descripcion"]);
    $estado = $_POST["estado"];

    // Actualizar tarea en la base de datos
    $stmt = $conn->prepare("UPDATE tasks SET titulo = :titulo, descripcion = :descripcion, estado = :estado WHERE tasks_id = :tasks_id AND users_id = :users_id");
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':tasks_id', $tarea_id, PDO::PARAM_INT);
    $stmt->bindParam(':users_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirigir después de la actualización
    header("Location: tasks.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea | AppTask</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>👋 Editar Tarea</h2>

        <form action="edit_task.php?tasks_id=<?= $tarea['tasks_id']; ?>" method="POST">
            <input type="text" name="titulo" value="<?= htmlspecialchars($tarea['titulo']); ?>" required>
            <textarea name="descripcion"><?= htmlspecialchars($tarea['descripcion']); ?></textarea>
            <select name="estado">
                <option value="en proceso" <?= $tarea['estado'] == 'en proceso' ? 'selected' : ''; ?>>En proceso</option>
                <option value="terminada" <?= $tarea['estado'] == 'terminada' ? 'selected' : ''; ?>>Terminada</option>
            </select>
            <button type="submit">Actualizar Tarea</button>
        </form>
    </div>
</body>
</html>
