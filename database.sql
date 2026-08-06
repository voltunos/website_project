-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-08-2026 a las 02:39:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `blendburger`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `display` varchar(100) NOT NULL,
  `activo` int(1) NOT NULL DEFAULT 0,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `display`, `activo`, `imagen`) VALUES
(1, 'hamburguesas', 'Hamburguesas', 1, '62d273ea926d8b7b38e5b4750e8b42ce.png'),
(2, 'papas_fritas', 'Papas fritas', 1, 'a1f0c7f18cad87b226af52ac07243c9f.png'),
(3, 'bebidas', 'Bebidas', 1, '20382556c4627ed4f83378ba03a2d748.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `clave` varchar(100) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `adicional` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`clave`, `valor`, `adicional`) VALUES
('shipping_cost', '2000', 'Coste que se le aplicará a todos los pedidos como envío.'),
('alias', 'blendburger.mp', 'Alias que se utilizará para que los usuarios paguen mediante transferencia bancaria.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id_direccion` int(200) NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `calle` varchar(100) NOT NULL,
  `numero` varchar(30) NOT NULL,
  `adicional` varchar(100) NOT NULL,
  `activo` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id_direccion`, `id_usuario`, `calle`, `numero`, `adicional`, `activo`) VALUES
(1, 1, 'Calle', '1111', 'Lorem ipsum dolor sit amet', 1),
(2, 1, 'Calle', '2222', 'Lorem ipsum dolor sit amet', 1),
(3, 1, 'Calle', '3333', 'Lorem ipsum dolor sit amet', 1),
(4, 1, 'Calle', '4444', 'Lorem ipsum dolor sit amet', 1),
(5, 1, 'Calle', '5555', 'Lorem ipsum dolor sit amet', 1),
(8, 3, 'Las Heras', '3490', '', 1),
(9, 3, 'Av General López', '2656', 'El timbre no funciona, toque la puerta', 1),
(10, 3, 'Sarmiento', '3398', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `texto` varchar(100) NOT NULL,
  `compras` int(1) NOT NULL,
  `activo` int(1) NOT NULL DEFAULT 0,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nombre`, `texto`, `compras`, `activo`, `imagen`) VALUES
(1, 'activo', 'Abierto, envíos a Santo Tomé', 1, 1, '84ed6cc8939a1674a46fb203ab26dcee.png'),
(2, 'cerrado', 'Negocio cerrado', 0, 0, 'fe72700b6ff125824b1c0a1069ab4654.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(100) NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `envio` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `direccion` int(100) NOT NULL,
  `metodo_pago` varchar(100) NOT NULL,
  `adicional` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `external_reference` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_usuario`, `envio`, `total`, `fecha`, `direccion`, `metodo_pago`, `adicional`, `estado`, `external_reference`) VALUES
(7, 3, 2000.00, 4500.00, '2026-08-04', 8, 'efectivo', '', 'En proceso', ''),
(8, 3, 2000.00, 8900.00, '2026-08-04', 9, 'transferencia', 'Titular: Pablo Miguez | ', 'En proceso', ''),
(10, 3, 2000.00, 11300.00, '2026-08-05', 10, 'mercadopago', '', 'En proceso', '3|10|2000|1785966730370');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_items`
--

CREATE TABLE `pedidos_items` (
  `id_item` int(200) NOT NULL,
  `id_pedido` int(100) NOT NULL,
  `id_producto` int(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pedidos_items`
--

INSERT INTO `pedidos_items` (`id_item`, `id_pedido`, `id_producto`, `precio`, `cantidad`) VALUES
(16, 7, 1, 3500.00, 1),
(17, 7, 2, 1000.00, 1),
(18, 8, 4, 1200.00, 2),
(19, 8, 5, 4500.00, 1),
(20, 8, 6, 2000.00, 1),
(22, 10, 1, 3500.00, 2),
(23, 10, 2, 1000.00, 2),
(24, 10, 7, 2300.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `activo` int(1) NOT NULL DEFAULT 0,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `precio`, `descripcion`, `categoria`, `activo`, `imagen`) VALUES
(1, 'Hamburguesa básica', 3500.00, 'Lorem ipsum dolor sit amet consectetur adipiscing elit curabitur, mi posuere vulputate malesuada rutrum feugiat nibh id, nec habitant nisl massa rhoncus etiam consequat, dignissim fames suscipit vel morbi pulvinar curae. Tempus ornare dictumst facilisis libero cursus ultrices sagittis, nunc viverra varius cum enim congue nisl vulputate, sociis nam tortor nascetur imperdiet volutpat. Etiam mi scelerisque turpis laoreet in auctor eget pretium odio habitasse quis, vivamus inceptos aenean integer commodo hendrerit at libero natoque mus, euismod morbi sociis tempus montes proin et mauris nascetur nec.', 'hamburguesas', 1, 'd59adc5fbdbb4515bebd7f56d60aa257.png'),
(2, 'Papas fritas', 1000.00, 'Lorem ipsum dolor sit amet consectetur adipiscing elit curabitur, mi posuere vulputate malesuada rutrum feugiat nibh id, nec habitant nisl massa rhoncus etiam consequat, dignissim fames suscipit vel morbi pulvinar curae. Tempus ornare dictumst facilisis libero cursus ultrices sagittis, nunc viverra varius cum enim congue nisl vulputate, sociis nam tortor nascetur imperdiet volutpat. Etiam mi scelerisque turpis laoreet in auctor eget pretium odio habitasse quis, vivamus inceptos aenean integer commodo hendrerit at libero natoque mus, euismod morbi sociis tempus montes proin et mauris nascetur nec.', 'papas_fritas', 1, 'f04d1d60b58ed6d8a160f6e6761f0b6f.png'),
(3, 'Hamburguesa de bacon', 4000.00, 'Lorem ipsum dolor sit amet consectetur adipiscing elit curabitur, mi posuere vulputate malesuada rutrum feugiat nibh id, nec habitant nisl massa rhoncus etiam consequat, dignissim fames suscipit vel morbi pulvinar curae. Tempus ornare dictumst facilisis libero cursus ultrices sagittis, nunc viverra varius cum enim congue nisl vulputate, sociis nam tortor nascetur imperdiet volutpat. Etiam mi scelerisque turpis laoreet in auctor eget pretium odio habitasse quis, vivamus inceptos aenean integer commodo hendrerit at libero natoque mus, euismod morbi sociis tempus montes proin et mauris nascetur nec.', 'hamburguesas', 1, '95ae11c872e7248d93d6f4a8a96c6977.png'),
(4, 'Lata de pepsi', 1200.00, 'Lorem ipsum dolor sit amet consectetur adipiscing elit curabitur, mi posuere vulputate malesuada rutrum feugiat nibh id, nec habitant nisl massa rhoncus etiam consequat, dignissim fames suscipit vel morbi pulvinar curae. Tempus ornare dictumst facilisis libero cursus ultrices sagittis, nunc viverra varius cum enim congue nisl vulputate, sociis nam tortor nascetur imperdiet volutpat. Etiam mi scelerisque turpis laoreet in auctor eget pretium odio habitasse quis, vivamus inceptos aenean integer commodo hendrerit at libero natoque mus, euismod morbi sociis tempus montes proin et mauris nascetur nec.', 'bebidas', 1, '738669949a10a784c61815b18d38850c.png'),
(5, 'Hamburguesa doble con queso', 4500.00, 'Lorem ipsum dolor sit amet consectetur adipiscing elit curabitur, mi posuere vulputate malesuada rutrum feugiat nibh id, nec habitant nisl massa rhoncus etiam consequat, dignissim fames suscipit vel morbi pulvinar curae. Tempus ornare dictumst facilisis libero cursus ultrices sagittis, nunc viverra varius cum enim congue nisl vulputate, sociis nam tortor nascetur imperdiet volutpat. Etiam mi scelerisque turpis laoreet in auctor eget pretium odio habitasse quis, vivamus inceptos aenean integer commodo hendrerit at libero natoque mus, euismod morbi sociis tempus montes proin et mauris nascetur nec.', 'hamburguesas', 1, 'b55510228bdbf8f1c0da8da0e8f07329.png'),
(6, 'Papas fritas con cheddar', 2000.00, 'Lorem ipsum dolor sit amet consectetur adipiscing elit curabitur, mi posuere vulputate malesuada rutrum feugiat nibh id, nec habitant nisl massa rhoncus etiam consequat, dignissim fames suscipit vel morbi pulvinar curae. Tempus ornare dictumst facilisis libero cursus ultrices sagittis, nunc viverra varius cum enim congue nisl vulputate, sociis nam tortor nascetur imperdiet volutpat. Etiam mi scelerisque turpis laoreet in auctor eget pretium odio habitasse quis, vivamus inceptos aenean integer commodo hendrerit at libero natoque mus, euismod morbi sociis tempus montes proin et mauris nascetur nec.', 'papas_fritas', 1, 'e3a30f9a63964d002d946a2777d1a041.png'),
(7, 'Coca cola zero 2 litros', 2300.00, 'Lorem ipsum dolor sit amet consectetur adipiscing elit curabitur, mi posuere vulputate malesuada rutrum feugiat nibh id, nec habitant nisl massa rhoncus etiam consequat, dignissim fames suscipit vel morbi pulvinar curae. Tempus ornare dictumst facilisis libero cursus ultrices sagittis, nunc viverra varius cum enim congue nisl vulputate, sociis nam tortor nascetur imperdiet volutpat. Etiam mi scelerisque turpis laoreet in auctor eget pretium odio habitasse quis, vivamus inceptos aenean integer commodo hendrerit at libero natoque mus, euismod morbi sociis tempus montes proin et mauris nascetur nec.', 'bebidas', 1, '1da67b85ece1c05e3e26553a7ade4962.png'),
(8, 'Sprite 3 litros', 3000.00, 'Lorem ipsum dolor sit amet consectetur adipiscing elit curabitur, mi posuere vulputate malesuada rutrum feugiat nibh id, nec habitant nisl massa rhoncus etiam consequat, dignissim fames suscipit vel morbi pulvinar curae. Tempus ornare dictumst facilisis libero cursus ultrices sagittis, nunc viverra varius cum enim congue nisl vulputate, sociis nam tortor nascetur imperdiet volutpat. Etiam mi scelerisque turpis laoreet in auctor eget pretium odio habitasse quis, vivamus inceptos aenean integer commodo hendrerit at libero natoque mus, euismod morbi sociis tempus montes proin et mauris nascetur nec.', 'bebidas', 1, '8305102baaf19fe047614ed91940854e.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_blend`
--

CREATE TABLE `productos_blend` (
  `id_producto_blend` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_blend` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 0,
  `accion` varchar(50) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_usuario` int(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contra` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` bigint(10) NOT NULL,
  `rol` varchar(100) NOT NULL DEFAULT 'Cliente',
  `baneado` int(1) NOT NULL DEFAULT 0,
  `blendpoints` int(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_usuario`, `correo`, `contra`, `nombre`, `apellido`, `telefono`, `rol`, `baneado`, `blendpoints`, `created_at`, `imagen`) VALUES
(1, 'blendburger@gmail.com', '$2y$10$ycUa.tH3rbrTxI0tIbbrqeT82ia4jJRc37YUnYRbS0MWwjF3VuC7S', 'Blend', 'Burger', 3421111111, 'Dueño', 0, 1000, '2026-08-03', 'd09dad80893894128626e43c610b9efc.png'),
(2, 'admin@gmail.com', '$2y$10$0AIJHUckkmfS/Pa1S4BwOeDpXvIxHXgpzVsviy8bxVmnfmG.gEGc2', 'Administrador', 'N1', 3421111111, 'Administrador', 0, 0, '2026-08-03', ''),
(3, 'cliente@gmail.com', '$2y$10$HRM.lySK12RoioTx95s4ROjnlbC4uWBGu15M6kK8jKOeNRGUeHPBK', 'Cliente', 'de la página', 3421111111, 'Cliente', 0, 0, '2026-08-03', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id_direccion`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `pedidos_items`
--
ALTER TABLE `pedidos_items`
  ADD PRIMARY KEY (`id_item`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `productos_blend`
--
ALTER TABLE `productos_blend`
  ADD PRIMARY KEY (`id_producto_blend`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_direccion` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pedidos_items`
--
ALTER TABLE `pedidos_items`
  MODIFY `id_item` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos_blend`
--
ALTER TABLE `productos_blend`
  MODIFY `id_producto_blend` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_usuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
