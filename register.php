<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el usuario ya existe
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "Este correo electrónico ya está registrado.";
    } else {
        // Insertar nuevo usuario
        $query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
        if (mysqli_query($conn, $query)) {
            echo "Registro exitoso. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
        } else {
            echo "Error al registrarse.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
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
