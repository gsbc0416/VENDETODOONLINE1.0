<?php
require '../config.php'; // Asegúrate de que este archivo incluye la conexión a la base de datos



// Verifica si el usuario ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_formulario = $_POST['tipo_formulario'];
    
    switch ($tipo_formulario) {
        case 'empleado':
            header('Location: empleado_form.php');
            exit;
        case 'cliente':
            header('Location: cliente_form.php');
            exit;
        case 'venta':
            header('Location: venta_form.php');
            exit;
        case 'producto':
            header('Location: producto_form.php');
            exit;
        default:
            echo '<div class="alert alert-danger">Tipo de formulario no válido.</div>';
    }
}

// Obtiene el nombre del usuario desde la base de datos
if (isset($_SESSION['user_id'])) {  // Verifica si el usuario está autenticado
    $user_id = $_SESSION['user_id'];  // Obtiene el ID del usuario de la sesión
    try {
        // Prepara y ejecuta la consulta para obtener el username y last_login
        $stmt = $pdo->prepare("SELECT username, last_login FROM usuarios WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $username = htmlspecialchars($user['username']);  // Almacena el nombre de usuario
            $last_login = htmlspecialchars($user['last_login']);  // Almacena la última entrada
        } else {
            $username = 'Desconocido';
            $last_login = 'N/A';
        }
    } catch (PDOException $e) {
        $username = 'Error';
        $last_login = 'Error';
    }
} else {
    $username = 'No autenticado';
    $last_login = 'N/A';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Formularios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>

       /* Estilo para el fondo de la página */
    body {
        /* Establece una imagen de fondo que se repite en el centro y permanece fija mientras se desplaza la página */
        background: url('../img/admin-background.jpg') no-repeat center center fixed;
        /* Asegura que la imagen de fondo cubra todo el área disponible sin distorsionar su aspecto */
        background-size: cover;
        /* Cambia el color del texto a blanco para contrastar con el fondo oscuro */
        color: #fff;
    }

    /* Estilo para el contenedor principal del contenido */
    .container {
        /* Establece un fondo negro semitransparente para el contenedor */
        background: rgba(0, 0, 0, 0.7);
        /* Añade un relleno interno de 2 rem en todos los lados del contenedor */
        padding: 6rem;
        /* Redondea las esquinas del contenedor con un radio de 8 píxeles */
        border-radius: 8px;
        /* Añade una sombra sutil alrededor del contenedor para darle un efecto de profundidad */
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        /* Añade un margen superior de 1 rem para separar el contenedor del contenido superior */
        margin-top: 1rem;
    }

    /* Estilo para los botones personalizados */
    .btn-custom {
        /* Establece el color de fondo del botón en azul */
        background-color: #007bff;
        /* Elimina el borde predeterminado del botón */
        border: none;
    }
    .mb-4 {

    }

    /* Estilo para el estado de paso del ratón sobre los botones personalizados */
    .btn-custom:hover {
        /* Cambia el color de fondo a un azul más oscuro cuando se pasa el ratón sobre el botón */
        background-color: #0056b3;
        font-size: 1.5rem;/* cambia l pasar el cursor tamaño del texto */
    }

    /* Estilo para la barra de navegación personalizada */
    .navbar-custom {
        /* Establece el fondo de la barra de navegación en negro semitransparente */
        background-color: rgba(0, 0, 0, 0.7);
        /* Añade un relleno interno de 1 rem en todos los lados de la barra de navegación */
        padding: 1rem;
    }

    /* Estilo para los enlaces dentro de la barra de navegación personalizada */
    .navbar-custom a {
        /* Cambia el color del texto de los enlaces a blanco para contrastar con el fondo oscuro */
        color: #fff;
    }
    .form-group label {
            color: #007bff; /* Color del texto de las etiquetas */
            font-size: 2rem; /* Tamaño del texto */
            font-family: 'Georgia', serif; /* Tipo de letra de las etiquetas */
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #007bff; /* Color del borde */
            padding: 0.75rem;
            font-size: 1rem;/* tamaño del texto */
            font-family: 'Verdana', sans-serif; /* Tipo de letra de los campos de entrada */
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <a class="navbar-brand" href="../index.php"><i class="fas fa-home"></i> Página Principal</a>
        <a class="navbar-brand ms-auto" href="../auth/login.php"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
    </nav>
    <div class="container">
        <h1 class="text-center mb-4">Formulario de Administración</h1>
        <!-- Información del usuario -->
        <div class="mb-4">
            <p><strong>Usuario:</strong> <?php echo $username; ?></p>
            <p><strong>Última entrada:</strong> <?php echo $last_login; ?></p>
        </div>
        
        <!-- Formulario para seleccionar el tipo de registro -->
        <form action="form.php" method="POST">
            <div class="form-group">
                <label for="tipo_formulario">Selecciona el tipo de formulario</label>
                <select class="form-control" id="tipo_formulario" name="tipo_formulario" required>
                    <option value="" disabled selected>Selecciona un tipo</option>
                    <option value="empleado">Empleado</option>
                    <option value="cliente">Cliente</option>
                    <option value="producto">Producto</option>
                    <option value="venta">Venta</option>
                </select>
            </div>
            <button type="submit" class="btn btn-custom w-100 mt-3">Seleccionar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>
