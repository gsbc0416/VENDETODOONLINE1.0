<?php
include  'config.php'; // Incluir configuración de la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verificar si se ha enviado el formulario
    $nombre = $_POST['nombre']; // Obtener el nombre del formulario
    $email = $_POST['email'];   // Obtener el email del formulario
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT); // Encriptar la contraseña

    // Preparar la consulta para insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $contraseña); // Vincular parámetros

    if ($stmt->execute()) {
        echo "Registro exitoso."; // Mensaje de éxito
    } else {
        echo "Error: " . $stmt->error; // Mensaje de error
    }
    $stmt->close(); // Cerrar la declaración
}
$conn->close(); // Cerrar la conexión
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h1>Registro de Usuario</h1>
    <form method="post" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="contraseña">Contraseña
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required><br>
        <input type="submit" value="Registrar">
    </form>
</body>
</html>
