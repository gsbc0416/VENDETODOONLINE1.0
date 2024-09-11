<?php
require '../config.php'; // Incluye config.php que gestiona sesiones

// Verifica si el usuario está autenticado y es un administrador
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Opcional: consulta para obtener estadísticas o datos necesarios
// $stmt = $pdo->query("SELECT COUNT(*) AS total_users FROM usuarios");
// $total_users = $stmt->fetch()['total_users'];

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
            background-color: #f8f9fa;
            color: #212529;
        }
        /* Estilo de la barra lateral */
        .sidebar {
            background-color: #343a40;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.1rem;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar .active {
            background-color: #007bff;
        }
        /* Estilo del contenido principal */
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
        }
        .card {
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #343a40;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Barra lateral -->
    <div class="sidebar">
        <h4 class="text-center">Admin Dashboard</h4>
        <a href="admin_dashboard.php" class="active">Inicio</a>
        <a href="manage_users.php">Gestión de Usuarios</a>
        <a href="manage_products.php">Gestión de Productos</a>
        <a href="reports.php">Informes</a>
        <a href="../logout.php">Cerrar sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
            <a href="../logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>

        <!-- Estadísticas -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Total de Usuarios</div>
                    <div class="card-body">
                        <h5 class="card-title">200</h5>
                        <p class="card-text">Número total de usuarios registrados en el sistema.</p>
                        <a href="manage_users.php" class="btn btn-custom">Ver Usuarios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Total de Productos</div>
                    <div class="card-body">
                        <h5 class="card-title">150</h5>
                        <p class="card-text">Número total de productos disponibles en el catálogo.</p>
                        <a href="manage_products.php" class="btn btn-custom">Ver Productos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Últimos Informes</div>
                    <div class="card-body">
                        <h5 class="card-title">Informe de Ventas</h5>
                        <p class="card-text">Accede a los últimos informes de ventas y análisis.</p>
                        <a href="reports.php" class="btn btn-custom">Ver Informes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido adicional -->
        <div class="row">
            <!-- Puedes añadir más secciones aquí -->
        </div>
    </div>

    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 VENDETODOONLINE. Todos los derechos reservados.</p>
    </footer>

    <!-- Incluye Bootstrap y JS adicionales -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
