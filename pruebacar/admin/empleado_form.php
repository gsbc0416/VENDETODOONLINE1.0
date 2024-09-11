<?php
require '../config.php'; // Incluye el archivo de configuración para la conexión a la base de datos

// Verifica si el administrador ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php'); // Redirige al login si no está autenticado
    exit;
}

// Obtiene el ID del usuario (administrador) desde la sesión
$user_id = $_SESSION['user_id'];

try {
    // Prepara la consulta para obtener el nombre de usuario y la última entrada desde la base de datos
    $stmt = $pdo->prepare("SELECT username, last_login FROM usuarios WHERE username = ?");
    $stmt->execute([$user_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si se encontró el administrador en la base de datos
    if ($admin) {
        // Asigna los valores a las variables, escapando caracteres especiales para evitar problemas de seguridad
        $nombre_usuario = htmlspecialchars($admin['username']);  // Nombre del administrador
        $fecha_ultima_entrada = htmlspecialchars($admin['last_login']);  // Última entrada del administrador
    } else {
        $nombre_usuario = 'Desconocido';
        $fecha_ultima_entrada = 'N/A';
    }
} catch (PDOException $e) {
    // Manejo de errores en caso de problemas con la base de datos
    $nombre_usuario = 'Error';
    $fecha_ultima_entrada = 'Error';
}

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    registrarEmpleado($pdo);  // Llama a la función para registrar el empleado
}

// Función para registrar un nuevo empleado
function registrarEmpleado($pdo) {
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    try {
        // Verifica si el empleado ya está registrado en la base de datos
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE nombre = ?");
        $stmt->execute([$nombre]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['mensaje'] = '<div class="alert alert-danger">Empleado ya registrado.</div>'; // Mensaje de error si el empleado ya está registrado
            header('Location: empleado_form.php'); // Redirige al formulario
            exit;
        }

        // Inserta el nuevo empleado en la base de datos
        $stmt = $pdo->prepare("INSERT INTO empleados (nombre, rol, direccion, telefono, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$nombre, $rol, $direccion, $telefono]);

        // Mensaje de éxito con la fecha y hora actual
        $_SESSION['mensaje'] = '<div class="alert alert-success">Empleado registrado exitosamente el ' . date('d-m-Y H:i:s') . '.</div>';
        header('Location: empleado_form.php'); // Redirige al formulario
        exit;
    } catch (PDOException $e) {
        // Manejo de errores en caso de problemas con la base de datos
        $_SESSION['mensaje'] = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        header('Location: empleado_form.php'); // Redirige al formulario
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empleado</title>
    <!-- Enlaces a Bootstrap y Font Awesome -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Estilos generales del cuerpo de la página */
        body {
            background: url('../img/form-background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            font-family: 'Roboto', sans-serif;
        }
        /* Estilo para el contenedor del formulario */
        .container {
            background: rgba(255, 255, 255, 0.85);
            padding: 1rem;
            border-radius: 16px;
            box-shadow: 0 0 90px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        /* Estilo para el título */
        h1 {
            color: #007bff;
            margin-bottom: 1.5rem;
            font-weight: bold;
            text-align: center;
        }
        /* Estilo para los campos del formulario */
        .form-control {
            border-radius: 170px;
            border: 1px solid #ced4da;
            padding: 1rem;
            font-size: 20px;
        }
        /* Estilo para los campos del formulario cuando están enfocados */
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }
        /* Estilo para el texto del grupo de entrada (con íconos) */
        .input-group-text {
            background-color: #f1f1f1;
            color: black;
            border-radius: 0;
            font-size: 25px;
        }
        /* Estilo para el botón principal */
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
        }
        /* Estilo para el botón principal cuando se pasa el mouse por encima */
        .btn-primary:hover {
            background-color: #0056b3;
        }
        /* Estilo para el botón secundario personalizado */
        .btn-secondary-custom {
            background-color: #6c757d;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-size: 1.13rem;
            font-weight: bold;
        }
        /* Estilo para el botón secundario cuando se pasa el mouse por encima */
        .btn-secondary-custom:hover {
            background-color: #5a6268;
        }
        /* Estilo para la barra de navegación */
        .navbar-custom {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 1rem;
        }
        /* Estilo para los enlaces de la barra de navegación */
        .navbar-custom a {
            color: #fff;
            font-size: 1.1rem;
            margin-left: 1rem;
        }
        /* Estilo para los mensajes de alerta */
        .alert {
            border-radius: 10px;
            margin-top: 1.5rem;
        }
        /* Estilo para la información del administrador */
        .admin-info {
            margin-bottom: 1rem;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 0.5rem;
            border-radius: 10px;
        }
        /* para trabajar los campos */
        .mb-3 {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <a class="navbar-brand" href="../index.php"><i class="fas fa-home"></i> Página Principal</a>
        <a class="navbar-brand ms-auto" href="../auth/login.php"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
    </nav>
    <div class="container">
        <!-- Información del administrador -->
        <div class="admin-info">
            <p><strong>Administrador:</strong> <?php echo htmlspecialchars($nombre_usuario); ?></p>
            <p><strong>Última entrada:</strong> <?php echo htmlspecialchars($fecha_ultima_entrada); ?></p>
        </div>
        
        <h1>Registrar Empleado</h1>
        
        <!-- Mostrar el mensaje de éxito o error -->
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
        }
        ?>

        <form action="empleado_form.php" method="POST">
            <!-- Campo para el nombre del empleado -->
            <div class="mb-3">
                <label for="nombre" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese nombre" required>
                </div>
            </div>
            <!-- Campo para el rol del empleado -->
            <div class="mb-3">
                <label for="rol" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                    <input type="text" class="form-control" id="rol" name="rol" placeholder="Ingrese rol" required>
                </div>
            </div>
            <!-- Campo para la dirección del empleado -->
            <div class="mb-3">
                <label for="direccion" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingrese dirección" required>
                </div>
            </div>
            <!-- Campo para el teléfono del empleado -->
            <div class="mb-3">
                <label for="telefono" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese teléfono" required>
                </div>
            </div>
            <!-- Botones para enviar el formulario o volver a los formularios -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="../admin/form.php" class="btn btn-secondary-custom">Volver a Formularios</a>
            </div>
        </form>
    </div>
    <!-- Scripts necesarios para Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>
