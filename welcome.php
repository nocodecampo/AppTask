<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
echo "¡Bienvenido, " . $email . "!<br>";
echo "<a href='tasks.php'>Gestionar Tareas</a><br>";
echo "<a href='logout.php'>Cerrar sesión</a>";
?>
