<?php
require 'config.php'; // Incluye la conexión a la base de datos

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php'); // Redirige al usuario a la página de inicio de sesión si no está logueado
    exit();
}

$user_id = $_SESSION['user_id']; // Obtén el ID del usuario logueado

// Obtener los productos desde la base de datos
$stmt = $pdo->prepare("SELECT * FROM productos");
$stmt->execute(); // Ejecuta la consulta
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtén todos los productos como un array asociativo

// Inicializar el carrito si no existe en la sesión
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = []; // Crea un carrito vacío
}

// Manejar la solicitud para agregar productos al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']); // Obtén el ID del producto
    $cantidad = intval($_POST['cantidad']); // Obtén la cantidad deseada
    
    // Obtener el producto desde la base de datos
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]); // Ejecuta la consulta para obtener el producto por ID
    $producto = $stmt->fetch(PDO::FETCH_ASSOC); // Obtén el producto como un array asociativo
    
    if ($producto && $cantidad > 0) {
        // Agregar el producto al carrito
        $_SESSION['carrito'][] = [
            'id' => $producto['id'],
            'nombre' => $producto['nombre'],
            'imagen' => $producto['imagen'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad
        ];
        
        // Responder con el número total de productos en el carrito
        $response = [
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'total_items' => count($_SESSION['carrito'])
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Cantidad no válida o producto no encontrado'
        ];
    }
    
    echo json_encode($response); // Envía la respuesta en formato JSON
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        
        .header {
            background-color: #007bff; /* Color de fondo del header */
            color: white;
            padding: 15px 0; /* Espaciado superior e inferior */
        }
        
        .header h1 {
            margin: 0;
            font-size: 230x; /* Tamaño de fuente del título */
        }
        
        .header .nav-link {
            color: white;
            transition: color 0.3s; /* Transición suave del color del enlace */
        }
        
        .header .nav-link:hover {
            color: #e0e0e0; /* Color del enlace al pasar el cursor */
        }
        
        .product {
            background-color: #ffffff; /* Color de fondo del producto */
            border: 1px solid #e0e0e0; /* Borde del producto */
            border-radius: 8px; /* Esquinas redondeadas */
            padding: 15px;
            margin-bottom: 20px; /* Espaciado inferior entre productos */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra del producto */
            text-align: center; /* Centra el texto */
        }
        
        .product-image {
            width: 100px; /* Ancho de la imagen del producto */
            height: 100px; /* Alto de la imagen del producto */
            object-fit: cover; /* Ajusta la imagen al contenedor */
            margin-bottom: 10px; /* Espaciado inferior de la imagen */
        }
        
        #btn-carrito {
            background-color: #28a745; /* Color de fondo del botón del carrito */
            color: white;
            border-radius: 50%; /* Botón circular */
            width: 60px; /* Ancho del botón */
            height: 60px; /* Alto del botón */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px; /* Tamaño de la fuente del ícono */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Sombra del botón */
            position: fixed; /* Fija el botón en la parte inferior derecha */
            bottom: 20px;
            right: 20px;
            text-decoration: none; /* Quita el subrayado del enlace */
        }
        
        #btn-carrito:hover {
            background-color: #218838; /* Color de fondo al pasar el cursor */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada al pasar el cursor */
        }
        
        #carrito-cantidad {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #dc3545; /* Color de fondo del contador de cantidad */
            color: white;
            border-radius: 50%; /* Contador circular */
            padding: 2px 6px;
            font-size: 14px; /* Tamaño de fuente del contador */
        }
        
        footer {
            background-color: #007bff; /* Color de fondo del footer */
            color: white;
            padding: 20px 0; /* Espaciado superior e inferior */
            text-align: center;
            margin-top: 50px; /* Espaciado superior del footer */
        }
        
        footer a {
            color: #e0e0e0; /* Color de los enlaces del footer */
            text-decoration: none; /* Quita el subrayado de los enlaces */
            transition: color 0.3s; /* Transición suave del color del enlace */
        }
        
        footer a:hover {
            color: #ffffff; /* Color de los enlaces al pasar el cursor */
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>Productos</h1>
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
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4">
                    <div class="product">
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen del producto" class="product-image">
                        <p>$<?php echo number_format($producto['precio'], 2); ?></p>
                        <input type="number" id="quantity-<?php echo $producto['id']; ?>" value="1" min="1" class="form-control mb-2">
                        <button class="btn btn-success add-to-cart" data-id="<?php echo $producto['id']; ?>">
                            <i class="fas fa-cart-plus"></i> Agregar al Carrito
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Botón del Carrito Flotante -->
    <a href="carrito.php" id="btn-carrito">
        <i class="fas fa-shopping-cart"></i>
        <span id="carrito-cantidad">0</span>
    </a>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Tienda en Línea. Todos los derechos reservados.</p>
        <p><a href="contacto.php">Contáctanos</a> | <a href="terminos.php">Términos y Condiciones</a></p>
    </footer>

    <!-- Bootstrap JS y jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Script para manejar el agregar al carrito -->
    <script>
        $(document).ready(function() {
            // Manejador del formulario de agregar al carrito
            $('.add-to-cart').on('click', function(e) {
                e.preventDefault();
                
                var productId = $(this).data('id'); // Obtén el ID del producto
                var quantity = $('#quantity-' + productId).val(); // Obtén la cantidad deseada

                $.ajax({
                    url: 'productos.php',
                    type: 'POST',
                    data: { id: productId, cantidad: quantity },
                    success: function(response) {
                        var data = JSON.parse(response); // Analiza la respuesta JSON
                        if (data.success) {
                            $('#carrito-cantidad').text(data.total_items); // Actualiza el contador de productos en el carrito
                            alert(data.message); // Muestra un mensaje de éxito
                        } else {
                            alert(data.message); // Muestra un mensaje de error
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
