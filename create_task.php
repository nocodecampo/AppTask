<?php
session_start();
include('includes/db.php');

// Verificar si el usuario está logueado
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Obtener el ID del usuario logueado
$query = "SELECT id FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
$user_id = $user['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Insertar nueva tarea en la base de datos
    $query = "INSERT INTO tasks (user_id, title, description) VALUES ('$user_id', '$title', '$description')";
    if (mysqli_query($conn, $query)) {
        echo "Tarea creada exitosamente.";
    } else {
        echo "Error al crear la tarea.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tarea</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Crear Nueva Tarea</h2>
        <form method="POST">
            <label for="title">Título de la Tarea</label>
            <input type="text" name="title" required><br>

            <label for="description">Descripción</label>
            <textarea name="description" required></textarea><br>

            <button type="submit">Crear Tarea</button>
        </form>
    </div>
</body>
</html>
