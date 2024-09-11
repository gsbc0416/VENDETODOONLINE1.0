<?php
require '../includes/db.php';
require '../config.php';

$errors = [];
$success = '';

// Obtener el token desde la URL
$token = sanitize($_GET['token'] ?? '');

if (!$token) {
    die("Token inválido.");
}

// Verificar si el token es válido y no ha expirado
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE token_recuperacion = :token AND token_expiracion > NOW()");
$stmt->execute([':token' => $token]);
$usuario = $stmt->fetch();

if (!$usuario) {
    die("Token inválido o expirado.");
}

// Procesar la nueva contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = sanitize($_POST['password']);
    $password_confirm = sanitize($_POST['password_confirm']);

    // Validar las contraseñas
    if (strlen($password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres.";
    }
    if ($password !== $password_confirm) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (empty($errors)) {
        // Cifrar la nueva contraseña
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Actualizar la contraseña en la base de datos y eliminar el token
        $stmt = $pdo->prepare("UPDATE usuarios SET password = :password, token_recuperacion = NULL, token_expiracion = NULL WHERE id = :id");
        if ($stmt->execute([':password' => $password_hashed, ':id' => $usuario['id']])) {
            $success = "Tu contraseña ha sido restablecida con éxito.";
        } else {
            $errors[] = "Hubo un problema al restablecer la contraseña.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Restablecer Contraseña</h2>

    <?php if (!empty($errors)) : ?>
        <div class="errors">
            <?php foreach ($errors as $error) : ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php elseif ($success) : ?>
        <div class="success">
            <p><?php echo $success; ?></p>
            <a href="login.php">Iniciar Sesión</a>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="password">Nueva Contraseña:</label>
        <input type="password" name="password" required>

        <label for="password_confirm">Confirmar Contraseña:</label>
        <input type="password" name="password_confirm" required>

        <input type="submit" value="Restablecer Contraseña">
    </form>
</body>
</html>
