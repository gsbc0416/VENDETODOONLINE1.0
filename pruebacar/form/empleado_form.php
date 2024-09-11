<?php
require 'config.php'; // Incluye config.php que define $pdo y gestiona sesiones

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $rol = $_POST['rol'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    try {
        // Verificar si el empleado ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE nombre = ?");
        $stmt->execute([$nombre]);
        if ($stmt->fetchColumn() > 0) {
            echo '<script>document.getElementById("message").innerText = "Empleado ya registrado."; document.getElementById("message").className = "alert alert-danger";</script>';
            exit;
        }

        // Insertar nuevo empleado
        $stmt = $pdo->prepare("INSERT INTO empleados (nombre, rol, direccion, telefono, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$nombre, $rol, $direccion, $telefono]);
        
        echo '<script>document.getElementById("message").innerText = "Empleado registrado exitosamente."; document.getElementById("message").className = "alert alert-success";</script>';
    } catch (PDOException $e) {
        echo '<script>document.getElementById("message").innerText = "Error: ' . htmlspecialchars($e->getMessage()) . '"; document.getElementById("message").className = "alert alert-danger";</script>';
    }
}
?>



<form id="employee-form" action="register_employee.php" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="rol" class="form-label">Rol</label>
        <select class="form-select" id="rol" name="rol" required>
            <option value="" disabled selected>Seleccione un rol</option>
            <option value="manager">Manager</option>
            <option value="sales">Sales</option>
            <option value="warehouse">Warehouse</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="direccion" name="direccion" required>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" required>
    </div>
    <button type="submit" class="btn btn-primary">Registrar</button>
    <div id="message" class="mt-3"></div>
</form>
