-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-04-2026 a las 00:29:50
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
(2, 'Miller Six Pack', 'Es una cerveza tipo American Lager, famosa por su proceso de filtrado en frío cuatro veces, lo que le otorga un sabor suave, fresco y un color dorado brillante.', 13575.25, 30, '1777411676_27f942963b56248616cd.jpg', 473, 4.20, 1, 2, 4),
(3, 'Chandon Aperitiff', 'Es un espumoso bitter, infusionado con naranjas amargas y otros ingredientes secretos, que se bebe con hielo como un verdadero aperitivo. Fácil de tomar, versátil, fresco y con un característico sabor', 16999.25, 38, '1776970890_542349fb3ca1a7b32410.webp', 750, 12.00, 1, 3, 8),
(4, 'Bombay Shappire Sunset', 'Ideal para cócteles refrescantes o para disfrutar solo, Bombay Sunset London Dry se destaca por su versatilidad. La combinación de notas cítricas y un toque de especias crea una experiencia sensorial ', 42562.28, 25, '1776971074_d0eaf129ba29801e7d0f.webp', 700, 43.00, 1, 4, 10),
(5, 'Luigi Bosca Cabernet Sauvignon', 'Aromas de frutas rojas y negras, violetas y chocolate. De gran estructura y cuerpo, un vino de carácter y exquisita elegancia.', 16500.00, 35, '1776971203_335786c02d6f78be44d2.webp', 750, 14.50, 1, 6, 13),
(6, 'Gancia Americano', 'Aperitivo italiano de origen argentino, conocido por su sabor herbal, cítrico y ligeramente amargo. Es una mezcla de vino blanco, alcohol, extractos de hierbas aromáticas y azúcar, lo que le confiere ', 4600.55, 100, '1777407978_c3560bbeb93717d2866b.webp', 950, 15.00, 1, 1, 2),
(7, 'Quilmes Clásica - Six Pack', 'La cerveza de los argentinos por excelencia. Es una típica cerveza lager perfectamente equilibrada, transparente dorada, buen nivel de espuma y sabor.', 7996.56, 115, '1777408413_40aa7d12a4deba5e2f0a.jpg', 473, 4.90, 1, 2, 7),
(9, 'Campari', 'Aperitivo de origen italiano, conocido por su característico color rojo intenso y su sabor amargo. Popularmente usado en la preparación de cócteles como el Negroni y el Campari Spritz.', 8300.55, 100, '1777410617_eab9821edaf7291e88e3.jpg', 750, 28.50, 1, 1, 18),
(10, 'Heineken - Six Pack', 'Siente y disfruta en todo lugar un verdadero toque de clase con una lata fría de Heineken, el sabor refrescante que se mantiene a salvo de la luz y el aire.\r\n', 13500.00, 99, '1777410711_60f913efc2755b0fe2aa.jpg', 473, 5.00, 1, 2, 19),
(11, 'Quilmes Stout - Six Pack', 'Cerveza negra, de cuerpo y espuma cremosa. Su sabor dulce surge de las notas de chocolate y café provenientes del golpe de fuego que recibe la mata al momento de ser tostadas.', 9800.99, 75, '1777411804_dd8ec53b240e2e12ef76.jpg', 473, 4.80, 1, 2, 7),
(12, 'Chandon Extra Brut', 'Es el gran clásico de Chandon. Las mejores uvas de Chardonnay y Pinot Noir permiten crear un espumoso fresco, frutado, elegante, cremoso y equilibrado. Se destaca por su fineza y precisión.', 17550.35, 90, '1777412064_c8cd9aa877173f52638b.jpg', 750, 12.90, 1, 3, 8),
(13, 'Suter Extra Dulce', 'Producido en la prestigiosa región vitivinícola de Mendoza, Argentina, este espumante combina frescura, dulzura y aromas frutales, convirtiéndolo en el acompañante ideal para cualquier celebración o b', 5500.00, 120, '1777412302_34cdf2da4918211c2d35.webp', 750, 11.30, 1, 3, 20),
(14, 'Nieto Senetiner Brut Nature', 'De ligero tono asalmonado, con burbujas pequeñas, que terminan en una corona fina y delicada de color blanca. De aromas complejos y frutados, con notas de frambuesa, praliné y pan tostado, fiel a la e', 11350.25, 85, '1777412409_c1ec29abbb8df8b0b54e.webp', 750, 11.80, 1, 3, 9),
(15, 'Aconcagua Blue Edition', 'Se destaca por su composición de 7 botánicos que incluyen Bayas de Enebro, Semillas de Coriandro, Raíz de Angelica, Raíz de Regaliz, Almendra, Cassia y Cáscara de Limón, brindando un sabor único y dis', 13999.99, 50, '1777412584_d69e8122cb66ab599fdb.webp', 750, 37.00, 1, 4, 21),
(16, 'Johnnie Walker Blue Label', 'Johnnie Walker Blue Label es más que un whisky; es una obra maestra que celebra la artesanía y la herencia de una de las marcas más icónicas del mundo. Como diría el querido Coco Basile: \"es un elixir', 523845.99, 20, '1777412861_029e2ce5f513e016dd5e.jpg', 750, 40.00, 1, 5, 11),
(17, 'Luigi Bosca Malbec', 'Es un tinto de color rojo violáceo brillante. Sus aromas son intensos y amables, con notas que recuerdan a frutas rojas, y tonos algo florales y espe­ciados. En boca es generoso, fluido y expresivo, c', 18300.00, 87, '1777412922_a20b937271359ac9a80c.jpeg', 750, 14.00, 1, 6, 13),
(18, 'Absolut Clasic', 'Se caracteriza por ser elaborado exclusivamente con ingredientes naturales y no incluye azúcar añadido. Se destaca por su pureza, ofreciendo un sabor rico, con cuerpo y complejo, pero a la vez suave y', 15000.00, 45, '1777413014_85fe4a9bb752fd555317.jpg', 500, 40.00, 1, 7, 14),
(19, 'Jose Cuervo Silver', 'Jose Cuervo Silver es el epítome de la suavidad. Los maestros destiladores de La Rojeña han creado esta mezcla única y equilibrada para destacar los tonos de agave, caramelo y hierbas frescas en su pe', 43286.55, 20, '1777413146_7cd2706c8ba07f29a95f.jpg', 750, 38.00, 1, 8, 22),
(20, 'Beefeater London Dry Clásico', 'London Dry Gin por antonomasia hecho con un intenso toque de enebro y fuertes notas cítricas, es una auténtica London Dry para quienes disfrutan del sabor auténtico de la ginebra.', 32390.25, 88, '1777413395_59981622f1905d5701f1.jpg', 1000, 47.00, 1, 4, 23),
(21, 'Bombay Sapphire', 'Es una ginebra Premium, tipo London Dry, obtenida por triple destilación del alcohol de grano, cuyo vapor atraviesa una cesta de cobre perforada, situada en su cuello, extrayendo los aromas y aceites ', 28990.25, 65, '1777413487_398d4dec316c2c4ed87d.jpg', 750, 47.00, 1, 4, 10),
(22, 'Jack Daniels Old N7', 'Es un whiskey de Tennessee de la destilería estadounidense Jack Daniel\'s, conocido por su proceso de suavización con carbón vegetal y su sabor único. Se caracteriza por su color ámbar, aroma a caramel', 55850.00, 36, '1777413588_42ca7ba16d03d11474b7.jpg', 700, 40.00, 1, 5, 12),
(23, '100 Pipers Deluxe', 'Es un whisky escocés mezclado con notas ahumadas, producido por Pernod Ricard. La compañía dice que es el \"séptimo whisky escocés más grande del mundo\", 100 Pipers es una mezcla de entre 25 y 30 whisk', 12500.00, 98, '1777413660_cc33202d0699ece2f7f1.jpg', 750, 42.80, 1, 5, 24),
(24, 'Macallan Double Cask 18', 'Es la joya de la destilería. Distinguido, sofisticado, lujo hecho whisky. Double Cask se refiere a los dos tipos de roble usados para su añejamiento. Se trata de barricas que previamente contuvieron j', 1138425.22, 10, '1777413748_6bf4e75245f92686ff47.webp', 700, 43.00, 1, 5, 25),
(25, 'Rutini Malbec', 'Elaborado con uvas seleccionadas de los mejores viñedos, este Malbec presenta un color rojo intenso y profundo. En nariz, ofrece aromas complejos donde se entrelazan frutas rojas maduras, notas especi', 24550.00, 75, '1777413851_9e03c82b27810e69e9e8.webp', 750, 13.80, 1, 6, 26),
(26, 'Cordero con Piel de Lobo Malbec', 'Es un Malbec joven muy bien elaborado, diferente por su aroma y sabor a frutos maduros. Con una acidez justa deja en boca un picor agradable con un final reforzado por su paso por madera.', 4850.00, 140, '1777413929_174f4d4acee09c05be8b.jpg', 750, 13.60, 1, 6, 27),
(27, 'Trumpeter Malbec', 'De un impactante color violeta. Nariz frutal destacando ciruelas, cerezas y notas florales que nos recuerdan a las violetas. Posee gran cuerpo y su vivaz estructura acentúa sus taninos intensos que se', 6200.00, 45, '1777414053_847bce3d3b13a952bd4f.webp', 750, 13.00, 1, 6, 28),
(28, 'Skyy Citrus', 'Se caracteriza por su sabor refrescante y afrutado, con notas predominantes de cítricos, y un acabado limpio. Está hecho con un vodka de alta calidad, destilado cuatro veces y filtrado tres veces, lo ', 8960.35, 88, '1777414370_0ecf69eebc98b5e7a76d.jpg', 750, 29.00, 1, 7, 29),
(29, 'Smirnoff Clasico', 'Este galardonado vodka tiene un sabor robusto con un acabado seco para una máxima suavidad y claridad. Triplemente destilado y filtrado 10 veces, es perfecto con hielo o en tu cóctel favorito.', 7500.00, 120, '1777414516_2dd86d4fb1f081b05441.jpg', 700, 37.50, 1, 7, 30),
(30, 'Don Julio Reposado', 'Añejado 8 meses (4 veces el estándar de la industria), con aroma acogedor y suaves notas cítricas de limón junto con estratos de especias y toques de fruta madura con hueso.', 120375.57, 32, '1777414631_7db933b8263e9795ba2e.webp', 750, 38.00, 1, 8, 31),
(31, 'El Bandido Negro Gold', 'Es un tequila joven con su color dorado, que se obtiene del jarabe de caramelo. Se caracteriza por su suavidad y una agradable profundidad de sabor, con toques de agave en el aroma.', 37488.30, 20, '1777414908_b5e052fdd8fb8ef9378d.webp', 700, 38.00, 1, 8, 32);

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
(14, 'Absolut'),
(15, 'Amargo Obrero'),
(16, 'Brahma'),
(17, '1890'),
(18, 'Campari'),
(19, 'Heineken'),
(20, 'Suter'),
(21, 'Aconcagua'),
(22, 'Jose Cuervo'),
(23, 'Beefeater'),
(24, '100 Pipers'),
(25, 'Macallan'),
(26, 'Rutini'),
(27, 'Cordero con piel de lobo'),
(28, 'Trumpeter'),
(29, 'Skyy'),
(30, 'Smirnoff'),
(31, 'Don Julio'),
(32, 'El Bandido Negro');

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
(1, 'Juan Roman', 'juanrr@hotmail.com', '3794112342', 'Comentario de prueba', 1);

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
  MODIFY `id_bebida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
  MODIFY `id_marca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
