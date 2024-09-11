<?php

require 'config.php'; // Incluye la conexión a la base de datos

// Verificar si el usuario está logueado
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener la información del usuario
$stmt = $pdo->prepare("SELECT username, email FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Usuario no encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $direccion = $_POST['direccion'];
    $metodo_pago = $_POST['metodo_pago'];
    $descuento = $_POST['descuento'] ?? '';

    try {
        // Iniciar una transacción
        $pdo->beginTransaction();

        // Calcular el total de la compra
        $total = 0;
        foreach ($_SESSION['carrito'] as $producto) {
            if (is_array($producto) && isset($producto['precio'], $producto['cantidad'])) {
                $total += $producto['precio'] * $producto['cantidad'];
            }
        }

        // Registrar la compra en la base de datos
        $stmt = $pdo->prepare("INSERT INTO compras (user_id, direccion, metodo_pago, descuento, total, fecha) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $direccion, $metodo_pago, $descuento, $total]);
        $compra_id = $pdo->lastInsertId(); // Obtener el ID de la compra recién creada

        // Registrar los productos en la tabla productos_compras
        foreach ($_SESSION['carrito'] as $producto) {
            if (is_array($producto) && isset($producto['id'], $producto['nombre'], $producto['imagen'], $producto['precio'], $producto['cantidad'])) {
                $stmt = $pdo->prepare("INSERT INTO productos_compras (compra_id, producto_id, nombre, imagen, precio, cantidad) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$compra_id, $producto['id'], $producto['nombre'], $producto['imagen'], $producto['precio'], $producto['cantidad']]);
            }
        }

        // Limpiar el carrito después de la compra
        $_SESSION['carrito'] = [];

        // Confirmar la transacción
        $pdo->commit();

        // Redirigir al usuario a la página de resumen de compra
        header('Location: resumen_compra.php');
        exit();

    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        die('Error al procesar la compra: ' . htmlspecialchars($e->getMessage()));
    }
}
?>
