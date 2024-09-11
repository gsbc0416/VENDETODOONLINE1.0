<?php

require 'config.php'; // Incluye la conexión a la base de datos


if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Usuario no encontrado.');
}

// Obtener el carrito de la sesión o inicializarlo si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar funcionalidad para eliminar productos del carrito (usado por AJAX)
if (isset($_POST['remove'])) {
    $id = intval($_POST['remove']);
    foreach ($_SESSION['carrito'] as $key => $producto) {
        if ($producto['id'] == $id) {
            unset($_SESSION['carrito'][$key]);
            break;
        }
    }
    // Recalcular el total
    $total = 0;
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
    echo json_encode(['success' => true, 'total' => number_format($total, 2)]);
    exit();
}

// Recalcular el total del carrito
$total = 0;
$productos = [];
foreach ($_SESSION['carrito'] as $producto) {
    if (is_array($producto) && isset($producto['id'], $producto['nombre'], $producto['imagen'], $producto['precio'], $producto['cantidad'])) {
        if (isset($productos[$producto['id']])) {
            $productos[$producto['id']]['cantidad'] += $producto['cantidad'];
        } else {
            $productos[$producto['id']] = $producto;
        }
        $total += $producto['precio'] * $producto['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Animate.css para animaciones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- jQuery para AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        .nav-link {
            color: white !important;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
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
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>Carrito de Compras</h1>
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
            <!-- Carrito de Compras -->
            <div class="col-md-8">
                <h2 class="mb-4">Resumen del Carrito</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Imagen</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="carrito-contenido">
                            <?php if (!empty($productos)): ?>
                                <?php foreach ($productos as $producto): ?>
                                    <tr class="producto-row animate__animated">
                                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                        <td><img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="product-image"></td>
                                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                                        <td>$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?></td>
                                        <td>
                                            <button data-id="<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm eliminar-producto">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tu carrito está vacío.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Resumen de la Compra -->
            <div class="col-md-4">
                <div class="summary-card">
                    <h3>Total de la Compra</h3>
                    <p class="fs-4" id="total-compra">$<?php echo number_format($total, 2); ?></p>

                    <form action="finalizar_compra.php" method="POST">
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección de Envío</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" required>
                        </div>

                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago</label>
                            <select class="form-select" name="metodo_pago" id="metodo_pago" required>
                                <option value="tarjeta">Tarjeta de Crédito</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="descuento" class="form-label">Código de Descuento (opcional)</label>
                            <input type="text" class="form-control" name="descuento" id="descuento">
                        </div>

                        <input type="hidden" name="total" value="<?php echo number_format($total, 2); ?>">

                        <button type="submit" class="btn btn-custom btn-lg w-100">Finalizar Compra</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Tienda en Línea. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery para AJAX -->
    <script>
        $(document).ready(function() {
            // Evento para eliminar producto sin recargar
            $('.eliminar-producto').on('click', function() {
                var idProducto = $(this).data('id');
                var row = $(this).closest('.producto-row');

                $.ajax({
                    url: 'carrito.php',
                    type: 'POST',
                    data: { remove: idProducto },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            row.remove();
                            $('#total-compra').text('$' + data.total);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
