<?php
require '../config.php'; // Incluye config.php para gestión de sesiones

// Verifica si el usuario está autenticado y es un administrador
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Aquí podrías incluir la lógica para obtener datos de los informes desde la base de datos
// Por ejemplo, obtén datos para gráficos y tablas
// $reportData = obtenerDatosDeInforme(); // Función ficticia para obtener datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes</title>
    <!-- Incluye Bootstrap para estilos y diseño responsivo -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Incluye los iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- Incluye Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.1.1/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background: url('../img/background.jpg') no-repeat center center fixed; 
            background-size: cover;
            margin: 0;
            color: #fff;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="my-4">Informes</h1>
                <!-- Tarjeta de informes generales -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Resumen de Ventas</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de informes de usuarios -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Usuarios Registrados</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de tabla de datos -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Datos de Ventas</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenarán los datos de ventas -->
                                <?php
                                // Ejemplo de datos, debes reemplazarlo con datos reales de tu base de datos
                                // foreach ($ventas as $venta) {
                                //     echo "<tr>
                                //         <td>{$venta['fecha']}</td>
                                //         <td>{$venta['producto']}</td>
                                //         <td>{$venta['cantidad']}</td>
                                //         <td>{$venta['total']}</td>
                                //     </tr>";
                                // }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluye los scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Datos ficticios para los gráficos
        const salesData = {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
            datasets: [{
                label: 'Ventas Mensuales',
                data: [30, 50, 40, 60, 70, 90],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        const usersData = {
            labels: ['Activos', 'Inactivos'],
            datasets: [{
                label: 'Usuarios',
                data: [120, 30],
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        };

        // Crear el gráfico de ventas
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: salesData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Crear el gráfico de usuarios
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'pie',
            data: usersData,
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
