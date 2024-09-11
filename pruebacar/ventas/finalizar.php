<?php
require '../includes/db.php';
require '../config.php';

requireLogin();

$id_usuario = $_SESSION['usuario_id'];

// Consultar el carrito
$stmt = $pdo->prepare("
    SELECT productos.precio, carrito.cantidad 
    FROM carrito 
    INNER JOIN productos ON carrito.id_producto = productos.id 
    WHERE carrito.id_usuario = :id_usuario
");
$stmt->execute([':id_usuario' => $id_usuario]);

$total = 0;
while ($row = $stmt->fetch()) {
    $total += $row['precio'] * $row['cantidad'];
}

// Insertar la venta
$stmt = $pdo->prepare("INSERT INTO ventas (id_usuario, total) VALUES (:id_usuario, :total)");
$stmt->execute([
    ':id_usuario' => $id_usuario,
    ':total' => $total
]);

// Vaciar el carrito
$stmt = $pdo->prepare("DELETE FROM carrito WHERE id_usuario = :id_usuario");
$stmt->execute([':id_usuario' => $id_usuario]);

echo "Compra realizada con éxito.";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa</title>
</head>
<body>
    <h2>Compra Exitosa</h2>
    <p>Gracias por tu compra. El total fue de $<?php echo number_format($total, 2); ?></p>
    <a href="../productos/index.php">Volver a la tienda</a>
</body>
</html>
