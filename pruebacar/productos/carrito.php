<?php
require '../includes/db.php';
require '../config.php';

requireLogin();

$id_usuario = $_SESSION['usuario_id'];

// Consultar el carrito del usuario
$stmt = $pdo->prepare("
    SELECT productos.nombre, productos.precio, carrito.cantidad 
    FROM carrito 
    INNER JOIN productos ON carrito.id_producto = productos.id 
    WHERE carrito.id_usuario = :id_usuario
");
$stmt->execute([':id_usuario' => $id_usuario]);
$productos_carrito = $stmt->fetchAll();

$total = 0;
?>

<!-- HTML para mostrar los productos en el carrito -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Carrito de Compras</h2>

    <?php if ($productos_carrito) : ?>
        <ul>
            <?php foreach ($productos_carrito as $producto) : 
                $subtotal = $producto['precio'] * $producto['cantidad'];
                $total += $subtotal;
            ?>
            <li>
                <h3><?php echo $producto['nombre']; ?></h3>
                <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
                <p>Cantidad: <?php echo $producto['cantidad']; ?></p>
                <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
        <h2>Total: $<?php echo number_format($total, 2); ?></h2>
        <a href="../ventas/finalizar.php">Finalizar Compra</a>
    <?php else : ?>
        <p>Tu carrito está vacío.</p>
    <?php endif; ?>
</body>
</html>
