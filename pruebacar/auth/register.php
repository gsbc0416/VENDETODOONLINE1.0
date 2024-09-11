<?php
require '../includes/db.php'; // Incluye el archivo de configuración para la base de datos.
require '../config.php'; // Incluye el archivo de configuración para la sesión y otras configuraciones.



$errors = []; // Array para almacenar mensajes de error.
$success = ''; // Variable para almacenar el mensaje de éxito.

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica si el formulario ha sido enviado.
    // Sanitiza y valida los datos del formulario.
    $username = sanitize($_POST['username']);
    $email = filter_var(sanitize($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = sanitize($_POST['password']);
    $password_confirm = sanitize($_POST['password_confirm']);

    // Validaciones de los datos del formulario.
    if (!$email) {
        $errors[] = "El email no es válido.";
    }
    if (strlen($password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres.";
    }
    if ($password !== $password_confirm) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    // Si no hay errores, procede a registrar al usuario.
    if (empty($errors)) {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT); // Encripta la contraseña.

        // Consulta preparada para evitar SQL Injection.
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, email, password, fecha_registro) VALUES (:username, :email, :password, NOW())");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hashed);

        if ($stmt->execute()) {
            // Obtiene la fecha de registro del usuario recién creado.
            $user_id = $pdo->lastInsertId(); // Obtiene el ID del último usuario insertado.
            $stmt = $pdo->prepare("SELECT fecha_registro FROM usuarios WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            // Almacena el mensaje de éxito.
            $success = "Registro exitoso. Te registraste el " . date('d M Y, H:i:s', strtotime($user['fecha_registro'])) . ". Redirigiendo al inicio de sesión...";
            
            // Redirige después de 5 segundos.
            echo "<meta http-equiv='refresh' content='5;url=login.php'>";
        } else {
            $errors[] = "Error al registrar el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Incluye Bootstrap CSS para estilos modernos y Bootstrap Icons para los iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            background: url('../img/background.jpg') no-repeat center center fixed; /* Imagen de fondo para el body */
            background-size: cover; /* Ajusta la imagen de fondo para cubrir el área */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #fff; /* Color del texto en blanco */
        }
        .container {
            background: rgba(0, 0, 0, 0.7); /* Fondo semitransparente para el contenedor del formulario */
            padding: 2rem;
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 0 10px rgba(0,0,0,0.3); /* Sombra para el contenedor */
            max-width: 400px;
            width: 100%;
        }
        .container h2 {
            margin-bottom: 1.5rem; /* Espaciado inferior para el encabezado */
            text-align: center; /* Centra el texto del encabezado */
        }
        .form-group {
            position: relative;
            margin-bottom: 1rem; /* Espaciado inferior entre los campos del formulario */
        }
        .form-group input {
            width: 100%;
            padding-left: 2.5rem; /* Espaciado izquierdo para el campo de entrada */
        }
        .form-group i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%); /* Centra verticalmente el icono dentro del campo de entrada */
            color: #6c757d; /* Color del icono */
        }
        .btn-primary {
            background-color: #007bff; /* Color de fondo del botón */
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Color de fondo del botón al pasar el mouse */
        }
        .alert {
            margin-bottom: 1rem; /* Espaciado inferior para los mensajes de alerta */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>

        <!-- Mostrar mensaje de éxito -->
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar errores -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de registro -->
        <form method="POST" action="">
            <!-- Campo para el username con icono -->
            <div class="form-group">
                <i class="bi bi-person"></i>
                <input type="text" name="username" class="form-control" placeholder="Nombre de usuario" required>
            </div>

            <!-- Campo para el email con icono -->
            <div class="form-group">
                <i class="bi bi-envelope"></i>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <!-- Campo para la contraseña con icono -->
            <div class="form-group">
                <i class="bi bi-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>

            <!-- Campo para confirmar la contraseña con icono -->
            <div class="form-group">
                <i class="bi bi-lock"></i>
                <input type="password" name="password_confirm" class="form-control" placeholder="Confirmar Contraseña" required>
            </div>

            <!-- Botón para enviar el formulario -->
            <button type="submit" class="btn btn-primary w-100">Registrar</button>

            <!-- Enlace para iniciar sesión si el usuario ya tiene una cuenta -->
            <div class="text-center mt-3">
                <p>¿Ya tienes una cuenta? <a href="login.php" class="text-white">Inicia sesión</a></p>
            </div>
        </form>
    </div>

    <!-- Incluye Bootstrap JS para funcionalidades interactivas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
