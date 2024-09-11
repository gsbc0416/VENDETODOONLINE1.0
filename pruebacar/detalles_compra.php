<?php

require 'config.php'; // Incluye la conexión a la base de datos


// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

// Obtener el ID de la compra desde la sesión o la URL
$compra_id = $_GET['compra_id'] ?? null;
if (!$compra_id) {
    die('ID de compra no especificado.');
}

// Obtener los datos del usuario
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Usuario no encontrado.');
}

// Obtener los detalles de la compra
$stmt = $pdo->prepare("SELECT * FROM compras WHERE id = ?");
$stmt->execute([$compra_id]);
$compra = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$compra) {
    die('Compra no encontrada.');
}

// Obtener los productos de la compra
$stmt = $pdo->prepare("SELECT * FROM productos_compras WHERE compra_id = ?");
$stmt->execute([$compra_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Compra</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 20px;
        }
        .summary-card {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            margin-top: 50px;
            text-align: center;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>Detalles de la Compra</h1>
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a href="index.php" class="nav-link">Inicio</a></li>
                    <li class="nav-item"><a href="productos.php" class="nav-link">Productos</a></li>
                    <li class="nav-item"><a href="carrito.php" class="nav-link">Carrito</a></li>
                    <li class="nav-item"><a href="profile.php" class="nav-link">Perfil</a></li>
                    <li class="nav-item"><a href="logout.php" class="nav-link">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="summary-card">
                    <h3>Detalles de la Compra</h3>
                    
                    <p><strong>Nombre del Cliente:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Dirección de Envío:</strong> <?php echo htmlspecialchars($compra['direccion']); ?></p>
                    <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($compra['metodo_pago']); ?></p>
                    <p><strong>Código de Descuento:</strong> <?php echo htmlspecialchars($compra['descuento']); ?></p>
                    <p><strong>Total de la Compra:</strong> $<?php echo number_format($compra['total'], 2); ?></p>
                    <p><strong>Fecha de Compra:</strong> <?php echo htmlspecialchars($compra['fecha']); ?></p>

                    <h4>Productos en la Compra</h4>
                    <ul class="list-group mb-4">
                        <?php foreach ($productos as $producto): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="product-image" alt="Imagen del producto">
                                <div>
                                    <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                    <p>$<?php echo number_format($producto['precio'], 2); ?> x <?php echo htmlspecialchars($producto['cantidad']); ?></p>
                                </div>
                                <span class="badge bg-primary rounded-pill">$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="index.php" class="btn btn-custom w-100">Volver a la Página Principal</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Tienda en Línea. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
