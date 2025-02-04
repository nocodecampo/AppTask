<?php
include 'config.php';

try {
    // Crear la conexión con PDO
    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DB_DATABASE . ";charset=utf8", DB_USER, DB_PASS);
    
    // Establecer el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Mostrar error y detener la ejecución si la conexión falla
    die("Error de conexión: " . $e->getMessage());
}
?>  