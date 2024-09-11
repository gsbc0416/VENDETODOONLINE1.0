<?php
echo 'Current directory: ' . __DIR__;
echo '<br>';
echo 'Config file exists: ' . (file_exists(dirname(__DIR__) . '/config.php') ? 'Yes' : 'No');

require 'config.php'; // Incluye config.php para la configuración de la base de datos y sesiones

// Verifica si el usuario está logueado y tiene permisos de administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); // Redirige al formulario de inicio de sesión si no está autorizado
    exit();
}

// Obtener estadísticas para el panel (ejemplo básico)
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total_users FROM usuarios");
    $total_users = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) as total_products FROM productos");
    $total_products = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) as total_orders FROM compras");
    $total_orders = $stmt->fetchColumn();
} catch (PDOException $e) {
    $error = "Error al obtener estadísticas: " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Incluye Bootstrap para estilos y diseño responsivo -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Incluye los iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Estilo general del cuerpo */
        body {
            background: url('../img/background.jpg') no-repeat center center fixed; /* Imagen de fondo */
            background-size: cover; /* Ajusta el tamaño de la imagen */
            color: #fff; /* Color de texto blanco para el contraste */
        }
        /* Estilo del contenedor principal del panel */
        .dashboard-container {
            padding: 2rem; /* Espaciado interno */
        }
        /* Estilo de los cuadros de estadísticas */
        .stat-box {
            background: rgba(0, 0, 0, 0.7); /* Fondo negro semitransparente */
            border-radius: 8px; /* Bordes redondeados */
            padding: 1.5rem; /* Espaciado interno */
            margin-bottom: 1rem; /* Espaciado inferior */
            text-align: center; /* Centra el texto */
            color: #fff; /* Color de texto blanco */
        }
        .stat-box h4 {
            margin-bottom: 0.5rem; /* Espaciado inferior */
        }
        .stat-box p {
            font-size: 1.5rem; /* Tamaño de fuente para los números */
        }
        /* Estilo del menú de navegación */
        .navbar {
            margin-bottom: 2rem; /* Espaciado inferior */
        }
    </style>
</head>
<body>
    <!-- Menú de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="manage_users.php">Gestionar Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_products.php">Gestionar Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">Informes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../auth/logout.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenedor principal del panel -->
    <div class="container dashboard-container">
        <h1 class="mb-4">Bienvenido, Admin</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-4">
                <div class="stat-box">
                    <h4>Usuarios Totales</h4>
                    <p><?php echo number_format($total_users); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <h4>Productos Totales</h4>
                    <p><?php echo number_format($total_products); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <h4>Pedidos Totales</h4>
                    <p><?php echo number_format($total_orders); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluye Bootstrap Bundle para funcionalidades de JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
