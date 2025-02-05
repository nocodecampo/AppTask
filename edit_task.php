<?php
session_start();
include 'db.php';

if (!isset($_SESSION['users_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['tasks_id'])) {
    header("Location: tasks.php");
    exit();
}

$tarea_id = $_GET['tasks_id'];
$user_id = $_SESSION['users_id'];

// Obtener la tarea
$stmt = $conn->prepare("SELECT * FROM tasks WHERE tasks_id = :tasks_id AND users_id = :users_id");
$stmt->bindParam(':tasks_id', $tarea_id, PDO::PARAM_INT);
$stmt->bindParam(':users_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$tarea = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tarea) {
    header("Location: tasks.php");
    exit();
}

// Actualizar tarea
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST["titulo"]);
    $descripcion = trim($_POST["descripcion"]);
    $estado = $_POST["estado"];

    $stmt = $conn->prepare("UPDATE tasks SET titulo = :titulo, descripcion = :descripcion, estado = :estado WHERE tasks_id = :tasks_id AND users_id = :users_id");
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':tasks_id', $tarea_id, PDO::PARAM_INT);
    $stmt->bindParam(':users_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: tasks.php");
    exit();
}
?>

<form action="" method="POST">
    <input type="text" name="titulo" value="<?= htmlspecialchars($tarea['titulo']); ?>" required>
    <textarea name="descripcion"><?= htmlspecialchars($tarea['descripcion']); ?></textarea>
    <select name="estado">
        <option value="en proceso" <?= $tarea['estado'] == 'en proceso' ? 'selected' : ''; ?>>En proceso</option>
        <option value="terminada" <?= $tarea['estado'] == 'terminada' ? 'selected' : ''; ?>>Terminada</option>
    </select>
    <button type="submit">Actualizar Tarea</button>
</form>