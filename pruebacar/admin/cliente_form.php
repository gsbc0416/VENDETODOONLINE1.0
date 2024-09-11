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
        $nombre_usuario = htmlspecialchars($admin['username']);  // Nombre del administrador
        $fecha_ultima_entrada = htmlspecialchars($admin['last_login']);  // Última entrada del administrador
    } else {
        $nombre_usuario = 'Desconocido';
        $fecha_ultima_entrada = 'N/A';
    }
} catch (PDOException $e) {
    $nombre_usuario = 'Error';
    $fecha_ultima_entrada = 'Error';
}

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    registrarCliente($pdo);  // Llama a la función para registrar el cliente
}

// Función para registrar un nuevo cliente
function registrarCliente($pdo) {
    $nombre = $_POST['nombre'];
    $tipo_cliente = $_POST['tipo_cliente'];
    $cliente_id = $_POST['cliente_id'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    try {
        // Verifica si el cliente_id ya está registrado en la base de datos
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM regclientes WHERE cliente_id = ?");
        $stmt->execute([$cliente_id]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['mensaje'] = '<div class="alert alert-danger">El cliente con cédula ' . htmlspecialchars($cliente_id) . ' ya está registrado.</div>'; // Mensaje de error si el cliente_id ya está registrado
            header('Location: cliente_form.php'); // Redirige al formulario
            exit;
        }

        // Inserta el nuevo cliente en la base de datos
        $stmt = $pdo->prepare("INSERT INTO regclientes (nombre, tipo_cliente, cliente_id, direccion, telefono, fecha_registro) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$nombre, $tipo_cliente, $cliente_id, $direccion, $telefono]);

        // Mensaje de éxito con la fecha y hora actual
        $_SESSION['mensaje'] = '<div class="alert alert-success">Cliente registrado exitosamente el ' . date('d-m-Y H:i:s') . '.</div>';
        header('Location: cliente_form.php'); // Redirige al formulario
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        header('Location: cliente_form.php'); // Redirige al formulario
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cliente</title>
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
            margin-top: 0rem;
            max-width: 500px;/*le da el ancho al formulario */
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
            font-size: 20px;
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
            background-color: #007bff;
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
        /* Estilo para el pie de página */
        footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 1rem; /* Ajusta el padding para la altura del pie de página */
            text-align: center;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
            z-index: 1000; /* Asegura que el pie de página esté por encima de otros elementos */
        }
        footer p {
            margin: 0; /* Elimina márgenes por defecto de los párrafos para una alineación más precisa */
        }
        footer a {
            color: #fff; /* Color blanco para enlaces */
            text-decoration: none; /* Elimina el subrayado de los enlaces */
        }
        footer a:hover {
            text-decoration: underline; /* Subrayado en el paso del ratón para los enlaces */
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
            <p><strong>Última Entrada:</strong> <?php echo htmlspecialchars($fecha_ultima_entrada); ?></p>
        </div>

        <!-- Mensaje de estado -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <!-- Formulario de registro de clientes -->
        <h1>Registrar Cliente</h1>
        <form action="cliente_form.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre Completo" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="tipo_cliente" class="form-label">Tipo de Cliente</label>
                <select id="tipo_cliente" name="tipo_cliente" class="form-control" required>
                    <option value="mayorista">Mayorista</option>
                    <option value="detallista">Al Detal</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control" id="cliente_id" name="cliente_id" placeholder="cedula" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion Completa" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Telefono" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Cliente</button>
            <a href="../admin/form.php" class="btn btn-secondary-custom">Volver </a>
        </form>
    </div>

    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
 <!-- Pie de página -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> VENDETODOONLINE. Todos los derechos reservados.</p>
        <p>Dirección: Valledupar, Colombia | Teléfono: (57) 311-xxxxxxxx</p>
        <p><a href="mailto:contacto@tuempresa.com" style="color: #fff;">contacto@tuempresa.com</a></p>
    </footer>
</html>
