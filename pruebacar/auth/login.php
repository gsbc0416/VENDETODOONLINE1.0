<?php
require '../config.php'; // Incluye el archivo de configuración para la conexión a la base de datos y gestión de sesiones

// Función para sanitizar entradas del formulario


// Verifica si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitiza las entradas del formulario para evitar ataques de inyección
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    try {
        // Prepara la consulta SQL para obtener el usuario basado en el nombre de usuario
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene los datos del usuario como un array asociativo

        // Verifica si el usuario existe y si la contraseña es correcta
        if ($user && password_verify($password, $user['password'])) {
            // Si el inicio de sesión es exitoso, guarda la información del usuario en la sesión
            session_start(); // Inicia la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];

            // Verifica el rol del usuario
            if ($user['role'] === 'admin') {
                // Redirige al administrador a la página de formularios
                header("Location: ../admin/form.php");
            } else {
                // Redirige al cliente a la página principal
                header("Location: ../index.php");
            }
            exit(); // Asegura que no se ejecute más código después de la redirección
        } else {
            // Si el inicio de sesión falla, establece un mensaje de error
            $error = "Nombre de usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        // Captura cualquier error en la consulta SQL y establece un mensaje de error
        $error = "Error al autenticar: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Incluye Bootstrap para estilos y diseño responsivo -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Incluye los iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Estilos personalizados del formulario */
        body {
            background: url('../img/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }
        .login-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-group {
            position: relative;
            margin-bottom: 1rem;
        }
        .form-group input {
            width: 100%;
            padding-left: 2.5rem;
        }
        .form-group i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .footer {
            text-align: center;
            margin-top: 1rem;
        }
        .footer a {
            color: #007bff;
        }
        .footer a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Encabezado de bienvenida -->
        <h2>Bienvenido a VENDETODOONLINE</h2>
        <!-- Muestra el mensaje de error si existe -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <!-- Formulario de inicio de sesión -->
        <form action="login.php" method="post">
            <!-- Campo de nombre de usuario -->
            <div class="form-group">
                <i class="bi bi-person"></i>
                <input type="text" id="username" name="username" class="form-control" placeholder="Nombre de usuario" required>
            </div>
            <!-- Campo de contraseña -->
            <div class="form-group">
                <i class="bi bi-lock"></i>
                <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <!-- Botón de envío -->
            <button type="submit" class="btn btn-primary w-100 mb-2">Iniciar Sesión</button>
            <!-- Botón de registro -->
            <a href="register.php" class="btn btn-secondary w-100 mb-2">Registrarse</a>
            <!-- Enlace para recuperar la contraseña -->
            <div class="footer mt-3">
                <a href="recuperar_contraseña.php">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

    <!-- Incluye Bootstrap Icons para los iconos en el formulario -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
