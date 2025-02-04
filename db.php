<?php
include 'config.php';
// Configuración de la base de datos
$servername = HOST;
$username = DB_USER;
$password = DB_PASS;
$dbname = DB_DATABASE;

// Crear conexión
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// Establecer el modo de error de PDO a excepción
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);