<?php
require '../config.php'; // Incluye el archivo de configuración para la conexión a la base de datos

// Verifica si el administrador ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php'); // Redirige al login si no está autenticado
    exit;
}

// Obtiene el ID del usuario (administrador) desde la sesión
$user_id = $_SESSION['user_id'];

// Obtiene el nombre de usuario y la fecha de la última entrada
$stmt = $pdo->prepare("SELECT username, last_login FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $usuario['username'];
$fecha_ultima_entrada = $usuario['last_login'];

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    registrarVenta($pdo);  // Llama a la función para registrar la venta
}

// Función para registrar una nueva venta
function registrarVenta($pdo) {
    $producto_id = $_POST['producto_id'];
    $cliente_id = $_POST['cliente_id'];
    $cantidad = $_POST['cantidad'];
    $fecha_venta = $_POST['fecha_venta'];
    
    
    try {
        // Verifica si el producto_id existe en regproductos
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM regproductos WHERE producto_id = ?");
        $stmt->execute([$producto_id]);
        if ($stmt->fetchColumn() == 0) {
            $_SESSION['mensaje'] = '<div class="alert alert-danger">El producto no existe.</div>';
            header('Location: venta_form.php'); // Redirige al formulario
            exit;
        }

        // Verifica si el cliente_id existe en regclientes
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM regclientes WHERE cliente_id = ?");
        $stmt->execute([$cliente_id]);
        if ($stmt->fetchColumn() == 0) {
            $_SESSION['mensaje'] = '<div class="alert alert-danger">El cliente no existe.</div>';
            header('Location: venta_form.php'); // Redirige al formulario
            exit;
        }

        // Verifica si ya existe una venta con el mismo producto_id, cliente_id y fecha_venta
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM regventas WHERE producto_id = ? AND cliente_id = ? ");
        $stmt->execute([$producto_id, $cliente_id]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['mensaje'] = '<div class="alert alert-danger">Ya existe una venta con estos datos.</div>';
            header('Location: venta_form.php'); // Redirige al formulario
            exit;
        }

        // Inserta la nueva venta en la base de datos
        $stmt = $pdo->prepare("INSERT INTO regventas (producto_id, cliente_id, cantidad, fecha_venta) VALUES (?, ?, ?, ?)");
        $stmt->execute([$producto_id, $cliente_id, $cantidad, $fecha_venta]);

        // Mensaje de éxito con la fecha y hora actual
        $_SESSION['mensaje'] = '<div class="alert alert-success">Venta registrada exitosamente el ' . date('d-m-Y H:i:s') . '.</div>';
        header('Location: venta_form.php'); // Redirige al formulario
        exit;
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        header('Location: venta_form.php'); // Redirige al formulario
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta</title>
    <!-- Enlaces a Bootstrap y Font Awesome -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet">
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
        /* Estilo para el pie de página */
        footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 0rem; /* Ajusta el padding para la altura del pie de página */
            text-align: center;
            position: ; /* Fijo en la parte inferior */
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;: 400; /* Asegura que el pie de página esté por encima de otros elementos */
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
            <p><strong>Administrador:</strong> <?php echo htmlspecialchars($username); ?></p>
            <p><strong>Última entrada:</strong> <?php echo htmlspecialchars($fecha_ultima_entrada); ?></p>
        </div>
        
        <h1>Registrar Venta</h1>
        
        <!-- Mostrar el mensaje de éxito o error -->
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
        }
        ?>

        <form action="venta_form.php" method="POST">
            <!-- Campo para el ID del producto -->
            <div class="mb-3">
                <label for="producto_id" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-cube"></i></span>
                    <input type="number" class="form-control" id="producto_id" name="producto_id" placeholder="Ingrese ID del producto" required>
                </div>
            </div>
            <!-- Campo para el ID del cliente -->
            <div class="mb-3">
                <label for="cliente_id" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="number" class="form-control" id="cliente_id" name="cliente_id" placeholder="Ingrese ID del cliente" required>
                </div>
            </div>
            <!-- Campo para la cantidad de producto -->
            <div class="mb-3">
                <label for="cantidad" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-sort-amount-down"></i></span>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Ingrese cantidad de producto" required>
                </div>
            </div>
            <!-- Campo para la fecha de la venta -->
            <div class="mb-3">
                <label for="fecha" class="form-label"></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="text" class="form-control flatpickr-input" id="fecha" name="fecha_venta" placeholder="Seleccione la fecha de la venta" required>
                </div>
            </div>
            <!-- Botones para enviar el formulario o volver a los formularios -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="../admin/form.php" class="btn btn-secondary-custom">Volver a Formularios</a>
            </div>
        </form>
    </div>

    <!-- Scripts necesarios para Bootstrap y Flatpickr -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script>
        // Inicializa el selector de calendario (flatpickr)
        flatpickr("#fecha", {
            dateFormat: "Y-m-d"
        });
    </script>
</body>
        <!-- Pie de página -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> VENDETODOONLINE. Todos los derechos reservados.</p>
        <p>Dirección: Valledupar, Colombia | Teléfono: (57) 311-xxxxxxxx</p>
        <p><a href="mailto:contacto@tuempresa.com" style="color: #fff;">contacto@tuempresa.com</a></p>
    </footer>
</html>
