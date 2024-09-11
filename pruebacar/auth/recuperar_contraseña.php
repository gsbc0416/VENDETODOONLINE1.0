<?php
// Conexión a la base de datos
include '../config.php';

// Función para enviar el correo de restablecimiento
function enviarCorreoRestablecimiento($email, $token) {
    $resetLink = "http://localhost/tienda_online/auth/recuperar_contraseña.php?token=" . $token;
    $subject = "Recupera tu contraseña";
    $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $resetLink;
    $headers = "From: no-reply@tusitio.com";

    return mail($email, $subject, $message, $headers);
}

// Función para sanitizar entradas
 
// Verificar si el formulario de envío del enlace fue enviado
if (isset($_POST['email'])) {
    $email = sanitize($_POST['email']);

    // Verificar si el correo existe en la base de datos
    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() > 0) {
        // Generar token de restablecimiento
        $token = bin2hex(random_bytes(50));

        // Guardar token en la base de datos con fecha de expiración
        $query = "UPDATE usuarios SET token_recuperacion = :token, token_expiracion = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['token' => $token, 'email' => $email]);

        // Enviar el correo de restablecimiento
        if (enviarCorreoRestablecimiento($email, $token)) {
            echo "Se ha enviado un enlace de restablecimiento a tu correo.";
        } else {
            echo "Error al enviar el correo.";
        }
    } else {
        echo "El correo electrónico no está registrado.";
    }
}

// Verificar si el token ha sido proporcionado para restablecer la contraseña
if (isset($_GET['token'])) {
    $token = sanitize($_GET['token']);

    // Verificar si el token es válido
    $query = "SELECT * FROM usuarios WHERE token_recuperacion = :token AND token_expiracion > NOW()";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['token' => $token]);

    if ($stmt->rowCount() > 0) {
        // Mostrar el formulario de nueva contraseña
        echo '
        <form action="recuperar_contraseña.php" method="POST">
            <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
            <label for="password">Nueva Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Actualizar Contraseña</button>
        </form>
        ';
    } else {
        echo "El token ha expirado o no es válido.";
    }
}

// Verificar si el formulario de restablecimiento fue enviado
if (isset($_POST['token']) && isset($_POST['password'])) {
    $token = sanitize($_POST['token']);
    $new_password = password_hash(sanitize($_POST['password']), PASSWORD_DEFAULT);

    // Verificar token y actualizar la contraseña
    $query = "SELECT * FROM usuarios WHERE token_recuperacion = :token AND token_expiracion > NOW()";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['token' => $token]);

    if ($stmt->rowCount() > 0) {
        // Actualizar contraseña y eliminar token
        $query = "UPDATE usuarios SET password = :password, token_recuperacion = NULL, token_expiracion = NULL WHERE token_recuperacion = :token";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute(['password' => $new_password, 'token' => $token])) {
            echo "Tu contraseña ha sido actualizada.";
        } else {
            echo "Error al actualizar la contraseña.";
        }
    } else {
        echo "El token ha expirado o no es válido.";
    }
}

?>

<!-- Formulario de solicitud de enlace de recuperación -->
<h2>Recuperar Contraseña</h2>
<form action="recuperar_contraseña.php" method="POST">
    <label for="email">Correo Electrónico:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Enviar Enlace</button>
</form>
