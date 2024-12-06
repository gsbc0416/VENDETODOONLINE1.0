-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 26-11-2024 a las 02:09:20
-- Versión del servidor: 5.6.51-log
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_online`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE IF NOT EXISTS `carrito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

DROP TABLE IF EXISTS `compras`;
CREATE TABLE IF NOT EXISTS `compras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(40) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `descuento` varchar(50) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `user_id`, `direccion`, `metodo_pago`, `descuento`, `total`, `fecha`) VALUES
(36, 11, 'las manielitas', 'tarjeta', '', '39.99', '2024-09-12 18:55:17'),
(37, 11, '1 de mayo cra 23 # 20.45', 'tarjeta', '', '39.99', '2024-09-12 18:59:39'),
(38, 11, 'VE SIMON BOLIVAR', 'tarjeta', '', '39.99', '2024-09-12 19:01:52'),
(39, 11, 'las manielitas', 'tarjeta', '', '279.93', '2024-09-12 19:06:46'),
(40, 11, 'calle 35 # 24 -65 casa blanca rejas negras', 'tarjeta', '', '39.99', '2024-09-12 19:12:48'),
(46, 11, 'calle 35 # 24 -65 casa blanca rejas negras', 'tarjeta', '', '1590000.00', '2024-09-12 19:25:56'),
(47, 11, 'calle 35 # 24 -65 casa blanca rejas negras', 'tarjeta', '', '39.99', '2024-09-12 19:26:23'),
(50, 11, 'las manielitas', 'tarjeta', '', '39.99', '2024-09-12 19:30:00'),
(51, 11, 'calle 35 # 24 -65 casa blanca rejas negras', 'tarjeta', '', '39.99', '2024-09-12 19:52:31'),
(52, 11, 'calle 35 # 24 -65 casa blanca rejas negras', 'tarjeta', '', '279.93', '2024-09-12 20:03:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compra`
--

DROP TABLE IF EXISTS `detalles_compra`;
CREATE TABLE IF NOT EXISTS `detalles_compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compra_id` int(11) DEFAULT NULL,
  `nombre_cliente` varchar(255) DEFAULT NULL,
  `producto_nombre` varchar(255) DEFAULT NULL,
  `producto_imagen` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detalles_compra`
--

INSERT INTO `detalles_compra` (`id`, `compra_id`, `nombre_cliente`, `producto_nombre`, `producto_imagen`, `precio`, `cantidad`, `subtotal`) VALUES
(1, 1, NULL, 'pc gamer 64 bits win 11 profesional 1 tera dss', 'producto1.jpg', '29.99', 1, '29.99');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

DROP TABLE IF EXISTS `empleados`;
CREATE TABLE IF NOT EXISTS `empleados` (
  `nombre` varchar(255) NOT NULL,
  `cedula_emp` int(40) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cedula_emp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`nombre`, `cedula_emp`, `rol`, `direccion`, `telefono`, `fecha_registro`) VALUES
('fe', 1234567, 'cajera', 'las manielitas', '3161111111', '2024-10-19 11:29:50'),
('davis vanega', 15170708, 'vendedor', 'VE SIMON BOLIVAR', '31345666666', '2024-10-19 12:02:02'),
('Yailet', 1065607281, 'bodequero', 'CL 35 24-65', '3161111111', '2024-10-19 11:39:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `categoria` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `producto_id`, `nombre`, `descripcion`, `categoria`, `precio`, `imagen`, `fecha_agregado`) VALUES
(4, 0, 'Producto 4', 'pc windows 11 pro, d.d 1 tera , ram de 16gb torre ganer y pantalla 16\" ful hd', 'computadores', '1590000.00', 'producto4.jpg', '2024-09-07 00:34:43'),
(11, 0, 'Producto 4', NULL, '', '39.99', 'producto4.jpg', '2024-09-07 00:34:43'),
(12, 0, 'Producto 1', 'pc windows 11 pro, d.d 1 tera , ram de 16gb torre ganer y pantalla 16\" ful hd', '', '39.99', 'producto1.jpg', '2024-09-07 00:34:43'),
(13, 0, 'Producto 3', 'pc windows 11 pro, d.d 1 tera , ram de 16gb torre ganer y pantalla 16\" ful hd', '', '39.99', 'producto3.jpg', '2024-09-07 00:34:43'),
(14, 0, 'Producto 4', 'pc windows 11 pro, d.d 1 tera , ram de 16gb torre ganer y pantalla 16\" ful hd', '', '39.99', 'producto4.jpg', '2024-09-07 00:34:43'),
(15, 0, 'Producto 5', 'pc windows 11 pro, d.d 1 tera , ram de 16gb torre ganer y pantalla 16\" ful hd', '', '39.99', 'producto5.jpg', '2024-09-07 00:34:43'),
(16, 0, 'Producto 6', 'pc windows 11 pro, d.d 1 tera , ram de 16gb torre ganer y pantalla 16\" ful hd', '', '39.99', 'producto6.jpg', '2024-09-07 00:34:43'),
(17, 0, 'Producto 7', 'pc windows 11 pro, d.d 1 tera , ram de 16gb torre ganer y pantalla 16\" ful hd', '', '39.99', 'producto7.jpg', '2024-09-07 00:34:43'),
(18, 0, 'Producto 8', 'pc windows 11 pro, d.d 1 tera , ram de 16gb torre ganer y pantalla 16\" ful hd', '', '39.99', 'producto9.jpg', '2024-09-07 00:34:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_compras`
--

DROP TABLE IF EXISTS `productos_compras`;
CREATE TABLE IF NOT EXISTS `productos_compras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compra_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compra_id` (`compra_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos_compras`
--

INSERT INTO `productos_compras` (`id`, `compra_id`, `producto_id`, `nombre`, `imagen`, `precio`, `cantidad`, `fecha`) VALUES
(63, 39, 18, 'Producto 8', 'producto9.jpg', '39.99', 1, '2024-09-12 19:51:43'),
(65, 50, 12, 'Producto 1', 'producto1.jpg', '39.99', 1, '2024-09-12 19:51:43'),
(66, 51, 16, 'Producto 6', 'producto6.jpg', '39.99', 1, '2024-09-12 19:52:31'),
(67, 52, 17, 'Producto 7', 'producto7.jpg', '39.99', 1, '2024-09-12 20:03:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regclientes`
--

DROP TABLE IF EXISTS `regclientes`;
CREATE TABLE IF NOT EXISTS `regclientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `tipo_cliente` varchar(40) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`cliente_id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `id_2` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `regclientes`
--

INSERT INTO `regclientes` (`id`, `nombre`, `tipo_cliente`, `cliente_id`, `direccion`, `telefono`, `fecha_registro`) VALUES
(1, 'felipe', 'mayorista', 0, 'CL 35 24-65', '3161111111', '2024-09-10 00:00:00'),
(11, 'Felipe Barroso', 'detallista', 15170708, '1 de mayo cra 23 # 20.45', '31345666666', '2024-09-11 15:13:09'),
(12, 'juan de las casas', 'mayorista', 1234567890, 'VE SIMON BOLIVAR', '31345666666', '2024-10-19 12:03:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regproductos`
--

DROP TABLE IF EXISTS `regproductos`;
CREATE TABLE IF NOT EXISTS `regproductos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `fecha_agregado` date NOT NULL,
  PRIMARY KEY (`producto_id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `id_2` (`id`),
  UNIQUE KEY `producto_id` (`producto_id`),
  UNIQUE KEY `id_4` (`id`),
  KEY `id_3` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `regproductos`
--

INSERT INTO `regproductos` (`id`, `producto_id`, `nombre`, `descripcion`, `precio`, `imagen`, `fecha_agregado`) VALUES
(2, 0, 'pc gamer', 'tore atx gamer', '133333.00', '7.jpg', '2024-09-10'),
(10, 1777777, 'portatil gamer con w11', 'portatil w11 pro 1 tera dd ,ram 16', '677777.00', '14.jpg', '2024-10-19'),
(9, 1234567890, 'portatil gamer', 'portatil w11 pro 1 tera dd ,ram 16', '100000.00', '8.jpg', '2024-09-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `regventas`
--

DROP TABLE IF EXISTS `regventas`;
CREATE TABLE IF NOT EXISTS `regventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_venta` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `regventas`
--

INSERT INTO `regventas` (`id`, `producto_id`, `cliente_id`, `cantidad`, `fecha_venta`) VALUES
(48, 0, 15170708, 1, '2024-09-11 00:00:00'),
(49, 1234567890, 15170708, 1, '2024-09-02 00:00:00'),
(50, 1234567890, 1234567890, 1, '2024-10-19 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` enum('cliente','admin') DEFAULT 'cliente',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `email`, `password`, `token_recuperacion`, `token_expiracion`, `fecha_registro`, `role`, `last_login`, `created_at`) VALUES
(1, 'Yailet', 'fbn7@hotmail.com', '$2y$10$8kF9/qoBj37tQxxn7NrVleR9X5WSOfXX13YxANz80AVY7.qHDb.T.', '4f125802981cef02399b8ed7dc2e31ea2e177ea9b3436c67056fbe5d50ca51bbd8f8bf356a48c07037e8a593318b4276f35b', '2024-09-10 17:13:50', '2024-09-07 13:41:14', 'cliente', NULL, '2024-09-09 18:40:22'),
(13, 'admin', 'y.castrov@hotmail.com', '$2y$10$GPSyIngOXxQ3bCHBqSDJSOtkEYEWC.TXugSKvcPGLwiXnp1VtCkf6', NULL, NULL, '2024-09-07 17:47:14', 'admin', '2024-09-11 11:26:17', '2024-09-09 18:40:22'),
(16, 'felipe barroso', 'delfirone@gmail.com', '$2y$10$10nUQ2Grl/xB4OUw01G0JOLbvOmarPJtH.NUcz21Xp7tmj04ex0Xy', NULL, NULL, '2024-10-19 11:58:56', 'cliente', NULL, '2024-10-19 16:58:56'),
(17, 'prueba', 'gsbcsoluciones@gmail.com', '$2y$10$XEIM90oPiXw22QOtm/.5Ae4T7PgF5hWbje93v4x/hSlPakzAnVup6', NULL, NULL, '2024-10-19 12:10:55', 'cliente', NULL, '2024-10-19 17:10:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `direccion` text,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `user_id`, `total`, `metodo_pago`, `direccion`, `fecha`) VALUES
(1, 1, '0.00', 'tarjeta', 'calle 35 # 24 -65 casa blanca rejas negras', '2024-09-06 20:29:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_productos`
--

DROP TABLE IF EXISTS `ventas_productos`;
CREATE TABLE IF NOT EXISTS `ventas_productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ventas_productos`
--

INSERT INTO `ventas_productos` (`id`, `venta_id`, `producto_id`, `cantidad`) VALUES
(1, 1, NULL, 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos_compras`
--
ALTER TABLE `productos_compras`
  ADD CONSTRAINT `productos_compras_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `productos_compras_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD CONSTRAINT `ventas_productos_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `ventas_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
