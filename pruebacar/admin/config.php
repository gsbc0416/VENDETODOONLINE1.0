<?php
session_start(); // Asegúrate de que esta línea solo esté aquí una vez



$dsn = 'mysql:host=localhost;dbname=tienda_online';
$username = 'root';
$password = '0826';

// Configuración de PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Error de conexión: ' . htmlspecialchars($e->getMessage()));
}

// Función para proteger campos de entrada de scripts maliciosos
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirigir si no está logueado
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /auth/login.php");
        exit();
    }
}

// Mostrar errores en entorno de desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
