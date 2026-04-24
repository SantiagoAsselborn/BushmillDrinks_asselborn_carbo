-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-04-2026 a las 02:12:05
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bushmilldrinks`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bebida`
--

CREATE TABLE `bebida` (
  `id_bebida` int(11) NOT NULL,
  `nombre_bebida` varchar(100) NOT NULL,
  `descripcion_bebida` varchar(200) DEFAULT NULL,
  `precio_bebida` decimal(10,2) NOT NULL,
  `stock_bebida` int(11) NOT NULL,
  `imagen_bebida` varchar(300) DEFAULT NULL,
  `volumen_bebida` int(11) DEFAULT NULL,
  `grado_bebida` decimal(4,2) DEFAULT NULL,
  `estado_bebida` tinyint(1) NOT NULL DEFAULT 1,
  `id_categoria` int(11) NOT NULL,
  `id_marca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bebida`
--

INSERT INTO `bebida` (`id_bebida`, `nombre_bebida`, `descripcion_bebida`, `precio_bebida`, `stock_bebida`, `imagen_bebida`, `volumen_bebida`, `grado_bebida`, `estado_bebida`, `id_categoria`, `id_marca`) VALUES
(1, 'Fernet Branca', 'El fernet es una bebida alcohólica amarga del tipo amaro, elaborada a partir de hierbas como mirra, ruibarbo, manzanilla, cardamomo y azafrán.', 15500.00, 47, '1776958019_88eadd3d5d193315359f.jpg', 750, 39.00, 1, 1, 3),
(2, 'Miller Six Pack', 'Es una cerveza tipo American Lager, famosa por su proceso de filtrado en frío cuatro veces, lo que le otorga un sabor suave, fresco y un color dorado brillante. ', 13575.25, 30, '1776970660_f9d52f88317422966a67.jpeg', 473, 4.20, 1, 2, 4),
(3, 'Chandon Aperitiff', 'Es un espumoso bitter, infusionado con naranjas amargas y otros ingredientes secretos, que se bebe con hielo como un verdadero aperitivo. Fácil de tomar, versátil, fresco y con un característico sabor', 16999.25, 38, '1776970890_542349fb3ca1a7b32410.webp', 750, 12.00, 1, 3, 8),
(4, 'Bombay Shappire Sunset', 'Ideal para cócteles refrescantes o para disfrutar solo, Bombay Sunset London Dry se destaca por su versatilidad. La combinación de notas cítricas y un toque de especias crea una experiencia sensorial ', 42562.28, 25, '1776971074_d0eaf129ba29801e7d0f.webp', 700, 43.00, 1, 4, 10),
(5, 'Luigi Bosca Cabernet Sauvignon', 'Aromas de frutas rojas y negras, violetas y chocolate. De gran estructura y cuerpo, un vino de carácter y exquisita elegancia.', 16500.00, 35, '1776971203_335786c02d6f78be44d2.webp', 750, 14.50, 1, 6, 13);

--
-- Disparadores `bebida`
--
DELIMITER $$
CREATE TRIGGER `trg_bebida_validaciones_insert` BEFORE INSERT ON `bebida` FOR EACH ROW BEGIN
  IF NEW.precio_bebida < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Precio invalido';
  END IF;

  IF NEW.stock_bebida < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Stock invalido';
  END IF;

  IF NEW.grado_bebida < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Grado invalido';
  END IF;

  IF NEW.estado_bebida NOT IN (0,1) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Estado invalido';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_bebida_validaciones_update` BEFORE UPDATE ON `bebida` FOR EACH ROW BEGIN
  IF NEW.precio_bebida < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Precio invalido';
  END IF;

  IF NEW.stock_bebida < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Stock invalido';
  END IF;

  IF NEW.grado_bebida < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Grado invalido';
  END IF;

  IF NEW.estado_bebida NOT IN (0,1) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Estado invalido';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'APERITIVOS'),
(2, 'CERVEZAS'),
(3, 'ESPUMANTES'),
(4, 'GINS'),
(5, 'WHISKYS'),
(6, 'VINOS'),
(7, 'VODKAS'),
(8, 'TEKILAS'),
(9, 'SIN ALCOHOL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `id_ciudad` int(11) NOT NULL,
  `nombre_ciudad` varchar(100) NOT NULL,
  `id_provincia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ciudad`
--

INSERT INTO `ciudad` (`id_ciudad`, `nombre_ciudad`, `id_provincia`) VALUES
(1, 'Bella Vista', 7),
(2, 'Beron de Astrada', 7),
(3, 'Capital', 7),
(4, 'Concepcion', 7),
(5, 'Curuzu Cuatia', 7),
(6, 'Empedrado', 7),
(7, 'Esquina', 7),
(8, 'General Alvear', 7),
(9, 'General Paz', 7),
(10, 'Goya', 7),
(11, 'Itati', 7),
(12, 'Ituzaingo', 7),
(13, 'Lavalle', 7),
(14, 'Mburucuya', 7),
(15, 'Mercedes', 7),
(16, 'Monte Caseros', 7),
(17, 'Paso de los Libres', 7),
(18, 'Saladas', 7),
(19, 'San Cosme', 7),
(20, 'San Luis del Palmar', 7),
(21, 'San Martin', 7),
(22, 'San Miguel', 7),
(23, 'San Roque', 7),
(24, 'Santo Tome', 7),
(25, 'Sauce', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_envio`
--

CREATE TABLE `detalle_envio` (
  `id_envio` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  `costo_envio` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_envio`
--

INSERT INTO `detalle_envio` (`id_envio`, `id_venta`, `id_direccion`, `costo_envio`) VALUES
(1, 1, 1, 0.00),
(2, 2, 2, 0.00),
(3, 3, 3, 0.00),
(4, 4, 4, 0.00),
(5, 5, 5, 0.00),
(6, 6, 6, 0.00),
(7, 7, 7, 0.00);

--
-- Disparadores `detalle_envio`
--
DELIMITER $$
CREATE TRIGGER `trg_envio_costo` BEFORE INSERT ON `detalle_envio` FOR EACH ROW BEGIN
  IF NEW.costo_envio < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Costo de envio invalido';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id_detalle` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_bebida` int(11) NOT NULL,
  `detalle_cantidad` int(11) NOT NULL,
  `detalle_precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id_detalle`, `id_venta`, `id_bebida`, `detalle_cantidad`, `detalle_precio`) VALUES
(1, 1, 1, 2, 15500.00),
(2, 2, 1, 2, 15500.00),
(3, 3, 1, 1, 15500.00),
(4, 4, 1, 1, 15500.00),
(5, 5, 1, 1, 15500.00),
(6, 6, 1, 2, 15500.00),
(7, 6, 3, 1, 16999.25),
(8, 7, 1, 1, 15500.00),
(9, 7, 3, 1, 16999.25);

--
-- Disparadores `detalle_ventas`
--
DELIMITER $$
CREATE TRIGGER `trg_detalle_validaciones` BEFORE INSERT ON `detalle_ventas` FOR EACH ROW BEGIN
  IF NEW.detalle_cantidad <= 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Cantidad invalida';
  END IF;

  IF NEW.detalle_precio < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Precio invalido';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL,
  `calle` varchar(100) NOT NULL,
  `altura` int(11) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_ciudad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`id_direccion`, `calle`, `altura`, `codigo_postal`, `id_usuario`, `id_ciudad`) VALUES
(1, 'Av. 3 de Abril', 1650, '3400', 2, 3),
(2, 'Av. 3 de Abril', 1650, '3400', 2, 3),
(3, 'Av. 3 de Abril', 1650, '3400', 2, 3),
(4, 'Av. 3 de Abril', 1650, '3400', 2, 3),
(5, 'Av. 3 de Abril', 1650, '3400', 2, 3),
(6, 'Av. 3 de Abril', 1650, '3400', 2, 3),
(7, 'Av. 3 de Abril', 1650, '3400', 2, 3);

--
-- Disparadores `direccion`
--
DELIMITER $$
CREATE TRIGGER `trg_direccion_altura` BEFORE INSERT ON `direccion` FOR EACH ROW BEGIN
  IF NEW.altura <= 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Altura invalida';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `id_marca` int(11) NOT NULL,
  `nombre_marca` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id_marca`, `nombre_marca`) VALUES
(1, 'Cynar'),
(2, 'Gancia'),
(3, 'Branca'),
(4, 'Miller'),
(5, 'Andes'),
(6, 'Martini'),
(7, 'Quilmes'),
(8, 'Chandon'),
(9, 'Nieto Senetiner'),
(10, 'Bombay'),
(11, 'Johnnie Walker'),
(12, 'Jack Daniels'),
(13, 'Luigi Bosca'),
(14, 'Absolut');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id_mensaje` int(11) NOT NULL,
  `nombre_mensaje` varchar(100) NOT NULL,
  `mail_mensaje` varchar(100) NOT NULL,
  `telefono_mensaje` varchar(20) DEFAULT NULL,
  `consulta_mensaje` text NOT NULL,
  `estado_mensaje` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`id_mensaje`, `nombre_mensaje`, `mail_mensaje`, `telefono_mensaje`, `consulta_mensaje`, `estado_mensaje`) VALUES
(1, 'Juan Roman', 'juanrr@hotmail.com', '3794112342', 'Comentario de prueba', 0);

--
-- Disparadores `mensaje`
--
DELIMITER $$
CREATE TRIGGER `trg_mensaje_estado` BEFORE INSERT ON `mensaje` FOR EACH ROW BEGIN
  IF NEW.estado_mensaje NOT IN (0,1) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Estado mensaje invalido';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id_perfil` int(11) NOT NULL,
  `descripcion_perfil` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `descripcion_perfil`) VALUES
(1, 'admin'),
(2, 'clientes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `id_promocion` int(11) NOT NULL,
  `id_bebida` int(11) NOT NULL,
  `tipo_promocion` varchar(50) NOT NULL,
  `valor_promocion` decimal(10,2) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `estado_promocion` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `promocion`
--
DELIMITER $$
CREATE TRIGGER `trg_promocion_validaciones` BEFORE INSERT ON `promocion` FOR EACH ROW BEGIN
  IF NEW.valor_promocion < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Valor de promocion invalido';
  END IF;

  IF NEW.estado_promocion NOT IN (0,1) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Estado promocion invalido';
  END IF;

  IF NEW.fecha_fin < NEW.fecha_inicio THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Fechas invalidas';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE `provincia` (
  `id_provincia` int(11) NOT NULL,
  `nombre_provincia` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`id_provincia`, `nombre_provincia`) VALUES
(1, 'Ciudad Autonoma de Buenos Aires'),
(2, 'Buenos Aires'),
(3, 'Catamarca'),
(4, 'Chaco'),
(5, 'Chubut'),
(6, 'Cordoba'),
(7, 'Corrientes'),
(8, 'Entre Rios'),
(9, 'Formosa'),
(10, 'Jujuy'),
(11, 'La Pampa'),
(12, 'La Rioja'),
(13, 'Mendoza'),
(14, 'Misiones'),
(15, 'Neuquen'),
(16, 'Rio Negro'),
(17, 'Salta'),
(18, 'San Juan'),
(19, 'San Luis'),
(20, 'Santa Cruz'),
(21, 'Santa Fe'),
(22, 'Santiago Del Estero'),
(23, 'Tierra Del Fuego'),
(24, 'Tucuman');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `apellido_usuario` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email_usuario` varchar(100) NOT NULL,
  `pass_usuario` varchar(255) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT 0,
  `id_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_usuario`, `apellido_usuario`, `usuario`, `email_usuario`, `pass_usuario`, `baja`, `id_perfil`) VALUES
(1, 'Santiago', 'Asselborn', 'santi1234', 'santiasselborn1@gmail.com', '$2y$10$dFbkv6/U1WU.0jS4NNFvpe5v2U0qPdeFkh/dXktFuxe34k1OJyURK', 0, 1),
(2, 'Pedro', 'Perez', 'pedrito123', 'pedroperez@gmail.com', '$2y$10$26o2XcmSj6PfmNW3TsEz3O1AL6oItp6qWGxk55wHOTMv./lCsJING', 0, 2);

--
-- Disparadores `usuario`
--
DELIMITER $$
CREATE TRIGGER `trg_usuario_baja_insert` BEFORE INSERT ON `usuario` FOR EACH ROW BEGIN
  IF NEW.baja NOT IN (0,1) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Valor invalido en baja';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_usuario_baja_update` BEFORE UPDATE ON `usuario` FOR EACH ROW BEGIN
  IF NEW.baja NOT IN (0,1) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Valor invalido en baja';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id_venta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_venta` datetime NOT NULL DEFAULT current_timestamp(),
  `total_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id_venta`, `id_usuario`, `fecha_venta`, `total_venta`) VALUES
(1, 2, '2026-04-23 16:50:11', 0.00),
(2, 2, '2026-04-23 16:59:02', 31000.00),
(3, 2, '2026-04-23 17:04:33', 15500.00),
(4, 2, '2026-04-23 17:07:44', 15500.00),
(5, 2, '2026-04-23 17:09:52', 15500.00),
(6, 2, '2026-04-23 19:10:43', 47999.25),
(7, 2, '2026-04-23 23:56:47', 32499.25);

--
-- Disparadores `venta`
--
DELIMITER $$
CREATE TRIGGER `trg_venta_total` BEFORE INSERT ON `venta` FOR EACH ROW BEGIN
  IF NEW.total_venta < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Total de venta invalido';
  END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bebida`
--
ALTER TABLE `bebida`
  ADD PRIMARY KEY (`id_bebida`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_marca` (`id_marca`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`id_ciudad`),
  ADD KEY `id_provincia` (`id_provincia`);

--
-- Indices de la tabla `detalle_envio`
--
ALTER TABLE `detalle_envio`
  ADD PRIMARY KEY (`id_envio`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_direccion` (`id_direccion`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_bebida` (`id_bebida`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`id_direccion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_ciudad` (`id_ciudad`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id_mensaje`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`id_promocion`),
  ADD KEY `id_bebida` (`id_bebida`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id_provincia`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email_usuario` (`email_usuario`),
  ADD KEY `id_perfil` (`id_perfil`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bebida`
--
ALTER TABLE `bebida`
  MODIFY `id_bebida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  MODIFY `id_ciudad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `detalle_envio`
--
ALTER TABLE `detalle_envio`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `id_promocion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `id_provincia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bebida`
--
ALTER TABLE `bebida`
  ADD CONSTRAINT `bebida_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  ADD CONSTRAINT `bebida_ibfk_2` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`);

--
-- Filtros para la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD CONSTRAINT `ciudad_ibfk_1` FOREIGN KEY (`id_provincia`) REFERENCES `provincia` (`id_provincia`);

--
-- Filtros para la tabla `detalle_envio`
--
ALTER TABLE `detalle_envio`
  ADD CONSTRAINT `detalle_envio_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`),
  ADD CONSTRAINT `detalle_envio_ibfk_2` FOREIGN KEY (`id_direccion`) REFERENCES `direccion` (`id_direccion`);

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`),
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`id_bebida`) REFERENCES `bebida` (`id_bebida`);

--
-- Filtros para la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD CONSTRAINT `direccion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `direccion_ibfk_2` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudad` (`id_ciudad`);

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `promocion_ibfk_1` FOREIGN KEY (`id_bebida`) REFERENCES `bebida` (`id_bebida`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
