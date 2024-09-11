<<?php
require '../includes/db.php';
require '../config.php';

// Verificar si el usuario está logueado
requireLogin();

$id_producto = (int)$_GET['id'];
$id_usuario = $_SESSION['usuario_id'];

// Verificar si el producto ya está en el carrito
$stmt = $pdo->prepare("SELECT * FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
$stmt->execute([
    ':id_usuario' => $id_usuario,
    ':id_producto' => $id_producto
]);

// Si ya está en el carrito, actualizar la cantidad
if ($stmt->rowCount() > 0) {
    $stmt = $pdo->prepare("UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
} else {
    // Si no, agregarlo
    $stmt = $pdo->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, 1)");
}

$stmt->execute([
    ':id_usuario' => $id_usuario,
    ':id_producto' => $id_producto
]);

header("Location: ../productos/index.php");
exit();
