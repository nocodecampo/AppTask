<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

echo "¡Bienvenido " . $_SESSION['email'] . "!<br>";
echo "<a href='logout.php'>Cerrar sesión</a>";
?>
