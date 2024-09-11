<?php
require '../config.php'; // Incluye config.php que gestiona sesiones



if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Consultar usuarios de la base de datos
$stmt = $pdo->query("SELECT id, username, email, role FROM usuarios");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <!-- Incluye Bootstrap para estilos y diseño responsivo -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Incluye los iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
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
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <!-- Barra lateral -->
    <div class="sidebar">
        <h4 class="text-center">Admin Dashboard</h4>
        <a href="admin_dashboard.php">Inicio</a>
        <a href="manage_users.php" class="active">Gestión de Usuarios</a>
        <a href="manage_products.php">Gestión de Productos</a>
        <a href="reports.php">Informes</a>
        <a href="../logout.php">Cerrar sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Usuarios</h1>
            <a href="add_user.php" class="btn btn-custom"><i class="bi bi-person-plus"></i> Añadir Usuario</a>
        </div>

        <!-- Filtro y búsqueda -->
        <div class="mb-4">
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Buscar usuarios" aria-label="Search">
                <button class="btn btn-custom" type="submit"><i class="bi bi-search"></i> Buscar</button>
            </form>
        </div>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-header">Usuarios Registrados</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</a>
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pie de página -->
    <footer class="footer mt-auto py-3">
        <div class="container">
            <p class="text-center mb-0">&copy; 2024 VENDETODOONLINE. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Incluye Bootstrap y JS adicionales -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
