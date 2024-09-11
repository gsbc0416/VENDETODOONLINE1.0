<?php
require '../config.php'; // Incluye la conexión a la base de datos

session_start(); // Iniciar la sesión

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../auth/login.php'); // Redirigir si no es administrador
    exit();
}

// Manejar la solicitud para insertar un producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = floatval($_POST['precio']);
    $imagen = $_FILES['imagen']['name'];
    $target_dir = "../img/";
    $target_file = $target_dir . basename($imagen);
    
    // Validaciones básicas
    if (empty($nombre) || $precio <= 0 || !in_array(pathinfo($target_file, PATHINFO_EXTENSION), ['jpg', 'png', 'jpeg'])) {
        $error_message = 'Por favor, completa todos los campos correctamente.';
    } else {
        // Mover archivo subido al directorio de imágenes
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, precio, imagen) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $precio, $imagen]);
            
            $success_message = 'Producto agregado exitosamente.';
        } else {
            $error_message = 'Error al subir la imagen.';
        }
    }
}

// Obtener el nombre del usuario y la última entrada
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, last_login FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = htmlspecialchars($user['username']);
$last_login = htmlspecialchars($user['last_login']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Producto - Administración</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px 0;
        }
        .container {
            margin-top: 20px;
        }
        .form-container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container h1 {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>Administración - Insertar Producto</h1>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a href="admin_dashboard.php" class="nav-link">Dashboard</a></li>
                    <li class="nav-item"><a href="admin_insertar_producto.php" class="nav-link">Insertar Producto</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="form-container">
            <h1>Agregar Nuevo Producto</h1>
            <div>
                <p><strong>Usuario:</strong> <?php echo $username; ?></p>
                <p><strong>Última entrada:</strong> <?php echo $last_login; ?></p>
            </div>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form action="admin_insertar_producto.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Producto</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen del Producto</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/jpeg, image/png" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </form>
        </div>
    </main>

    <!-- Bootstrap JS y jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
