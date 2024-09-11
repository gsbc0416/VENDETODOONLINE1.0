<?php
// db.php - Conexión segura a la base de datos usando PDO

$host = 'localhost';
$dbname = 'tienda_online';
$user = 'root';
$pass = '0826';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT => true,
    ]);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>

