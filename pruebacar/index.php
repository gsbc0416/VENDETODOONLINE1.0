<?php
require 'config.php'; // Incluye config.php que define $pdo y gestiona sesiones

// Verificar si el usuario está logueado
$userLoggedIn = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - VENDETODOONLINE</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40; /* Color de fondo de la barra de navegación */
        }
        .navbar-brand, .nav-link {
            color: #fff !important; /* Color de texto blanco para la navegación */
        }
        .hero {
            background: url('img/hero-bg.jpg') no-repeat center center;
            background-size: cover;
            color: #343a40; /* color de la letra */
            padding: 10px 0;/* espaciado ancho*/
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            font-weight: 700; /* Negrita para el título principal */
        }
        .hero p {
            font-size: 1.25rem;
            font-weight: 400; /* Peso normal para el texto */
        }
        .hero .btn {
            background-color: #28a745;
            color: #e3e3e3;
            font-weight: bold;
            border-radius: 30px; /* Bordes redondeados del botón */
        }
        .hero .btn:hover {
            background-color: #218838;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: #fff;
        }
        .product-card:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .carousel-item img {
            height: 400px;
            object-fit: cover;
        }
        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
        }
        .footer a {
            color: #fff;
        }
        .footer a:hover {
            color: #28a745;
        }
        .container {
            max-width: 1140px;
        }
        .about-us img {
            max-width: 100%;
            height: auto;
        }
        /* Estilo para el carrusel */
        .carousel-caption {
            bottom: 20px; /* Posición del texto en el carrusel */
        }
        .carousel-caption h5 {
            font-size: 1.5rem;
            font-weight: 700; /* Negrita para el título del carrusel */
        }
        .carousel-caption p {
            font-size: 1rem;
            font-weight: 400; /* Peso normal para el texto del carrusel */
        }
    </style>
</head>
<body>
    <!-- Cabecera del sitio -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="index.php">VENDETODOONLINE</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost/vendetodoonline/formulario/index2.php">Empleados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="productos.php">Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="carrito.php">
                                Carrito <span id="cart-count" class="badge bg-danger">0</span>
                            </a>
                        </li>
                        <?php if ($userLoggedIn): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="profile.php">Hola, <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="auth/login.php">Iniciar Sesión</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="auth/register.php">Registrarse</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Sección principal -->
    <main>
        <!-- Sección de Hero -->
        <section class="hero">
            <div class="container">
                <h1>Bienvenido a Nuestra Tienda en Línea</h1>
                <p>Explora nuestras ofertas exclusivas y encuentra los mejores productos a precios increíbles.</p>
                <a href="productos.php" class="btn btn-primary">Ver Ofertas</a>
            </div>
        </section>

        <!-- Carrusel de Productos Destacados -->
        <section class="featured-products my-5">
            <div class="container">
                <h2 class="text-center mb-4">Productos Destacados</h2>
                <div id="featured-products-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        if (isset($pdo)) {
                            try {
                                $stmt = $pdo->query("SELECT id, nombre, precio, imagen FROM productos ORDER BY fecha_agregado DESC LIMIT 10");
                                $isFirst = true;
                                while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<div class="carousel-item ' . ($isFirst ? 'active' : '') . '">';
                                    echo '<div class="d-flex justify-content-center">';
                                    echo '<div class="product-card mx-2">';
                                    echo '<img src="img/' . htmlspecialchars($producto['imagen']) . '" class="d-block w-100" alt="' . htmlspecialchars($producto['nombre']) . '">';
                                    echo '<div class="card-body text-center">';
                                    echo '<h5 class="card-title">' . htmlspecialchars($producto['nombre']) . '</h5>';
                                    echo '<p class="card-text">Precio: $' . htmlspecialchars($producto['precio']) . '</p>';
                                    echo '<a href="productos.php?id=' . htmlspecialchars($producto['id']) . '" class="btn btn-primary">Ver Más</a>';
                                    echo '</div></div></div></div>';
                                    $isFirst = false;
                                }
                            } catch (PDOException $e) {
                                echo '<p>Error al recuperar productos: ' . htmlspecialchars($e->getMessage()) . '</p>';
                            }
                        } else {
                            echo '<p>Error: La conexión a la base de datos no está disponible.</p>';
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#featured-products-carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#featured-products-carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Carrusel de Imágenes -->
        <section class="image-carousel my-5">
            <div class="container">
                <h2 class="text-center mb-4">Nuestros Productos</h2>
                <div id="image-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="img/15.jpg" class="d-block w-100" alt="Imagen 1">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Produto Destacado 1</h5>
                                <p>Descripción de la imagen destacada 1.</p>
                            </div>
                        </div>
                        <!-- aqui van las imagenes que quieran q aparezcan en la pagina -->
                        <div class="carousel-item">
                            <img src="img/10.jpg" class="d-block w-100" alt="Imagen 2">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Produto Destacado 2</h5>
                                <p>Descripción de la imagen destacada 2.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="img/11.jpg" class="d-block w-100" alt="Imagen 3">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Produto Destacado 3</h5>
                                <p>Descripción de la imagen destacada 3.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="img/12.jpg" class="d-block w-100" alt="Imagen 3">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Produto Destacado 3</h5>
                                <p>Descripción de la imagen destacada 3.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="img/14.jpg" class="d-block w-100" alt="Imagen 3">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Produto Destacado 3</h5>
                                <p>Descripción de la imagen destacada 3.</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#image-carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#image-carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Productos Más Vendidos -->
        <section class="top-selling-products my-5">
            <div class="container">
                <h2 class="text-center mb-4">Productos Más Vendidos</h2>
                <div class="row">
                    <?php
                    if (isset($pdo)) {
                        try {
                            $stmt = $pdo->query("
                                SELECT p.id, p.nombre, p.precio, p.imagen, SUM(cp.cantidad) as total_vendido
                                FROM productos p
                                JOIN productos_compras cp ON p.id = cp.producto_id
                                GROUP BY p.id
                                ORDER BY total_vendido DESC
                                LIMIT 6
                            ");
                            while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<div class="col-md-4 mb-4 d-flex align-items-stretch">';
                                echo '<div class="product-card card">';
                                echo '<img src="img/' . htmlspecialchars($producto['imagen']) . '" class="card-img-top" alt="' . htmlspecialchars($producto['nombre']) . '">';
                                echo '<div class="card-body text-center">';
                                echo '<h5 class="card-title">' . htmlspecialchars($producto['nombre']) . '</h5>';
                                echo '<p class="card-text">Precio: $' . htmlspecialchars($producto['precio']) . '</p>';
                                echo '<a href="productos.php?id=' . htmlspecialchars($producto['id']) . '" class="btn btn-primary">Ver Más</a>';
                                echo '</div></div></div>';
                            }
                        } catch (PDOException $e) {
                            echo '<p>Error al recuperar productos: ' . htmlspecialchars($e->getMessage()) . '</p>';
                        }
                    } else {
                        echo '<p>Error: La conexión a la base de datos no está disponible.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Productos Comprados Recientemente -->
        <section class="recently-purchased-products my-5">
            <div class="container">
                <h2 class="text-center mb-4">Productos Comprados Recientemente</h2>
                <div class="row">
                    <?php
                    if (isset($pdo)) {
                        try {
                            $stmt = $pdo->query("
                                SELECT p.id, p.nombre, p.precio, p.imagen
                                FROM productos p
                                JOIN productos_compras pc ON p.id = pc.producto_id
                                WHERE pc.fecha_compra > NOW() - INTERVAL 1 MONTH
                                GROUP BY p.id
                                ORDER BY pc.fecha_compra DESC
                                LIMIT 5
                            ");
                            while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<div class="col-md-4 mb-4">';
                                echo '<div class="product-card">';
                                echo '<img src="img/' . htmlspecialchars($producto['imagen']) . '" class="d-block w-100" alt="' . htmlspecialchars($producto['nombre']) . '">';
                                echo '<div class="card-body text-center">';
                                echo '<h5 class="card-title">' . htmlspecialchars($producto['nombre']) . '</h5>';
                                echo '<p class="card-text">Precio: $' . htmlspecialchars($producto['precio']) . '</p>';
                                echo '<a href="productos.php?id=' . htmlspecialchars($producto['id']) . '" class="btn btn-primary">Ver Más</a>';
                                echo '</div></div></div>';
                            }
                        } catch (PDOException $e) {
                            echo '<p>Error al recuperar productos: ' . htmlspecialchars($e->getMessage()) . '</p>';
                        }
                    } else {
                        echo '<p>Error: La conexión a la base de datos no está disponible.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Sección Sobre Nosotros -->
        <section class="about-us my-5">
            <div class="container text-center">
                <h2>Sobre Nosotros</h2>
                <p>Somos una tienda en línea dedicada a ofrecer productos de alta calidad con un excelente servicio al cliente. Nuestro objetivo es hacer que tu experiencia de compra sea fácil y agradable.</p>
                <img src="img/about-us.jpg" class="img-fluid" alt="Sobre Nosotros">
            </div>
        </section>
    </main>

    <!-- Pie de página -->
    <footer class="footer text-center mt-5">
        <div class="container">
            <p>&copy; 2024 Tienda en Línea. Todos los derechos reservados.</p>
            <p><a href="contacto.php">Contacto</a> | <a href="privacidad.php">Política de Privacidad</a></p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
