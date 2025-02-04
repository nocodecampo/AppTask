<?php
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Verificar si el usuario ya existe
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Este correo electrónico ya está registrado.";
        } else {
            // Insertar el nuevo usuario
            $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                echo "Registro exitoso. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
            } else {
                echo "Error al registrarse.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrarse</h2>
        <form method="POST">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" required><br>

            <label for="password">Contraseña</label>
            <input type="password" name="password" required><br>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
