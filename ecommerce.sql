-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-06-2025 a las 06:07:57
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
-- Base de datos: `ecommerce`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `address`
--

CREATE TABLE `address` (
  `id_address` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `street` varchar(255) NOT NULL,
  `number` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `address`
--

INSERT INTO `address` (`id_address`, `user_id`, `street`, `number`, `city`, `state`, `postal_code`, `country`, `description`) VALUES
(3, 1, 'moreno', 213213, 'capital', 'caba', '1234', 'argentina', 'asdasd'),
(4, 6, 'corrientes', 4455, 'capital', 'caba', '3333', 'argentina', 'sadsadsd'),
(5, 1, 'moreno', 1233, 'capital', 'caba', '3333', 'argentina', 'asdasd'),
(6, 1, 'moreno', 7898, 'capital', 'caba', '1234', 'argentina', 'asdasd'),
(7, 1, 'moreno', 1234, 'capital', 'caba', '1234', 'argentina', 'asdasd'),
(8, 6, 'moreno', 12312321, 'capital', 'caba', '1234', 'argentina', 'asdasd'),
(9, 6, 'corrientes', 4455, 'capital', 'caba', '3333', 'argentina', 'sadsadsd'),
(10, 1, 'moreno', 11111111, 'capital', 'caba', '1234', 'argentina', 'asdasd'),
(11, 1, 'moreno', 1, 'capital', 'caba', '1234', 'argentina', 'asdasd'),
(12, 7, 'santa fe', 123, 'capital', 'caba', '4444', 'argentina', 'eeeee'),
(13, 7, 'santa fe', 123, 'capital', 'caba', '4444', 'argentina', 'eeeee'),
(14, 7, 'santa fe', 123, 'capital', 'caba', '4444', 'argentina', 'eeeee'),
(15, 7, 'santa fe', 123, 'capital', 'caba', '4444', 'argentina', 'eeeee');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`user_id`, `role`) VALUES
(3, 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `total_items` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cart`
--

INSERT INTO `cart` (`id`, `created_at`, `total_items`, `user_id`) VALUES
(1, '2025-06-23 00:49:14', 0, 1),
(2, '2025-06-23 00:56:35', 0, 6),
(3, '2025-06-24 23:54:46', 0, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_item`
--

CREATE TABLE `cart_item` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cart_item`
--

INSERT INTO `cart_item` (`id`, `cart_id`, `product_id`, `quantity`) VALUES
(29, 1, 14, 1),
(30, 1, 4, 1),
(31, 1, 3, 1),
(41, 2, 3, 1),
(42, 2, 4, 1),
(43, 2, 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Processor', 'Processor Intel y AMD'),
(2, 'Graphic Card', 'Graphic Cards NVIDIA and AMD'),
(3, 'RAM Memory', 'RAM Memorys DDR3, DDR4, DDR5'),
(4, 'Motherboard', 'Motherboard Asus and Gigabyte'),
(5, 'Mouse', 'Mouse logitech, redragon,ect'),
(6, 'Keyboard', 'Keyboard logitech, redragon,ect'),
(7, 'Monitor', 'Monitor samsung, lg,ect'),
(8, 'Headphones', 'Headphones logitech, msi,ect'),
(9, 'Microphone', 'Microphone logitech, msi,ect'),
(10, 'Mouse pad', 'Mouse pad logitech, msi,ect'),
(11, 'Gaming chair', 'Gaming chair vertagear');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer`
--

CREATE TABLE `customer` (
  `user_id` int(11) NOT NULL,
  `registration_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `customer`
--

INSERT INTO `customer` (`user_id`, `registration_date`) VALUES
(1, '2025-06-22 17:14:45'),
(6, '2025-06-23 00:56:29'),
(7, '2025-06-24 23:54:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order`
--

CREATE TABLE `order` (
  `id_order` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `id_payment` int(11) DEFAULT NULL,
  `id_address` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `status` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `order`
--

INSERT INTO `order` (`id_order`, `user_id`, `id_payment`, `id_address`, `order_date`, `status`, `total`) VALUES
(2, 1, 3, 3, '2025-06-23 02:39:50', 'Processing', 1680.00),
(3, 6, 4, 4, '2025-06-23 02:41:19', 'Processing', 3480.00),
(4, 1, 5, 5, '2025-06-23 18:04:22', 'Processing', 80.00),
(5, 1, 9, 6, '2025-06-23 18:05:44', 'Completed', 80.00),
(6, 1, 8, 7, '2025-06-23 18:16:33', 'Completed', 3280.00),
(7, 6, 11, 8, '2025-06-24 22:53:01', 'Completed', 895.50),
(8, 6, NULL, 9, '2025-06-24 23:45:05', 'Processing', 895.50),
(9, 1, NULL, 10, '2025-06-24 23:46:36', 'Processing', 1991.39),
(10, 1, NULL, 11, '2025-06-24 23:51:28', 'Processing', 432.00),
(11, 7, 12, 12, '2025-06-24 23:58:31', 'Completed', 895.50),
(12, 7, 14, 13, '2025-06-25 00:05:30', 'Completed', 663.50),
(13, 7, 15, 14, '2025-06-25 00:08:13', 'Completed', 432.00),
(14, 7, 16, 15, '2025-06-25 00:33:39', 'Completed', 651.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_detail`
--

CREATE TABLE `order_detail` (
  `id_order_detail` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `order_detail`
--

INSERT INTO `order_detail` (`id_order_detail`, `id_order`, `product_id`, `quantity`, `unit_price`) VALUES
(1, 2, 3, 1, 80.00),
(2, 2, 4, 1, 1600.00),
(3, 3, 3, 1, 80.00),
(4, 3, 4, 2, 1600.00),
(5, 3, 6, 1, 200.00),
(6, 4, 3, 1, 80.00),
(7, 5, 3, 1, 80.00),
(8, 6, 3, 1, 80.00),
(9, 6, 4, 2, 1600.00),
(10, 7, 3, 1, 463.50),
(11, 7, 4, 1, 232.00),
(12, 7, 6, 1, 200.00),
(13, 8, 3, 1, 463.50),
(14, 8, 4, 1, 232.00),
(15, 8, 6, 1, 200.00),
(16, 9, 3, 2, 463.50),
(17, 9, 9, 1, 165.70),
(18, 9, 4, 1, 232.00),
(19, 9, 6, 1, 200.00),
(20, 9, 17, 1, 419.99),
(21, 9, 12, 1, 46.70),
(22, 10, 6, 1, 200.00),
(23, 10, 4, 1, 232.00),
(24, 11, 3, 1, 463.50),
(25, 11, 4, 1, 232.00),
(26, 11, 6, 1, 200.00),
(27, 12, 3, 1, 463.50),
(28, 12, 6, 1, 200.00),
(29, 13, 4, 1, 232.00),
(30, 13, 6, 1, 200.00),
(31, 14, 17, 1, 419.99),
(32, 14, 4, 1, 232.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment`
--

CREATE TABLE `payment` (
  `id_payment` int(11) NOT NULL,
  `payment_date` datetime NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `payment`
--

INSERT INTO `payment` (`id_payment`, `payment_date`, `method`, `status`) VALUES
(3, '2025-06-23 02:39:50', 'Credit Card', 'Paid'),
(4, '2025-06-23 02:41:19', 'Credit Card', 'Paid'),
(5, '2025-06-23 18:04:22', 'Credit Card', 'Paid'),
(6, '2025-06-23 18:05:44', 'Credit Card', 'Paid'),
(7, '2025-06-23 18:17:03', 'Debit Card', 'Paid'),
(8, '2025-06-23 18:18:14', 'Debit Card', 'Paid'),
(9, '2025-06-23 18:21:43', 'Credit Card', 'Paid'),
(10, '2025-06-24 22:53:11', 'Credit Card', 'Paid'),
(11, '2025-06-24 22:54:24', 'Credit Card', 'Paid'),
(12, '2025-06-25 00:04:14', 'Credit Card', 'Paid'),
(13, '2025-06-25 00:07:25', 'Credit Card', 'Paid'),
(14, '2025-06-25 00:07:34', 'Credit Card', 'Paid'),
(15, '2025-06-25 00:08:14', 'Credit Card', 'Paid'),
(16, '2025-06-25 00:34:49', 'Credit Card', 'Paid');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `price` float NOT NULL,
  `old_price` float DEFAULT NULL,
  `discount` varchar(32) DEFAULT NULL,
  `img` varchar(100) NOT NULL DEFAULT 'img.jpg',
  `img_hover` varchar(100) NOT NULL DEFAULT 'img_hover.jpg',
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`id`, `name`, `brand`, `price`, `old_price`, `discount`, `img`, `img_hover`, `category_id`) VALUES
(3, 'GeForce RTX 4060', 'Nvidia', 463.5, NULL, NULL, '../img/product-img/product2.jpg', '../img/product-img/product2.jpg', 2),
(4, 'Procesador AMD Ryzen 7 5700X 4.6GHz Turbo AM4 - No incluye Cooler', 'AMD', 232, NULL, NULL, '../img/product-img/product1.jpg', '../img/product-img/product1.jpg', 1),
(6, 'Memoria Ram DDR4 - 8Gb 3600 Mhz Kingston Fury Beast Rgb', 'Kingston', 200, NULL, NULL, '../img/product-img/ram-ddr4-8gb-kingston-fury.jpeg', '../img/product-img/ram-ddr4-8gb-kingston-fury.jpeg', 3),
(8, 'Mother ASUS PRIME B550M-A AC WIFI AM4', 'Asus', 128.35, NULL, NULL, '../img/product-img/Mother ASUS PRIME B550M-A AC WIFI AM4.jpg', '../img/product-img/Mother ASUS PRIME B550M-A AC WIFI AM4.jpg', 4),
(9, 'Procesador AMD Ryzen 5 5600G 4.4GHz Turbo + Wraith Stealth Cooler', 'AMD', 165.7, NULL, NULL, '../img/product-img/Procesador AMD Ryzen 5 5600G 4.4GHz Turbo + Wraith Stealth Cooler.jpg', '../img/product-img/Procesador AMD Ryzen 5 5600G 4.4GHz Turbo + Wraith Stealth Cooler.jpg', 1),
(11, 'Mouse Logitech G305 Wireless 2.4Ghz Lightspeed Hero Black 99g 250Hs ', 'Logitech', 39.65, NULL, NULL, '../img/product-img/Mouse Logitech G305 Wireless 2.4Ghz Lightspeed Hero Black 99g 250Hs.jpg', '../img/product-img/Mouse Logitech G305 Wireless 2.4Ghz Lightspeed Hero Black 99g 250Hs.jpg', 5),
(12, 'Teclado Mecanico Redragon Kumara K552 RGB Outemu Red', 'Redragon', 46.7, NULL, NULL, '../img/product-img/Teclado Mecanico Redragon Kumara K552 RGB Outemu Red.jpg', '../img/product-img/Teclado Mecanico Redragon Kumara K552 RGB Outemu Red.jpg', 6),
(13, 'Monitor Gamer Samsung Curvo D366 24\" FHD VA 100Hz ', 'Samsung', 188.05, NULL, NULL, '../img/product-img/Monitor Gamer Samsung Curvo.jpg', '../img/product-img/Monitor Gamer Samsung Curvo.jpg', 7),
(14, 'Auriculares Logitech G733 Wireless Lightspeed LightSync RGB Lila 29Hs ', 'Logitech', 261.25, NULL, NULL, '../img/product-img/Auriculares Logitech G733 Wireless Lightspeed LightSync RGB Lila 29Hs.jpg', '../img/product-img/Auriculares Logitech G733 Wireless Lightspeed LightSync RGB Lila 29Hs.jpg', 8),
(15, 'Microfono Redragon Fenris GM301 RGB ', 'Redragon', 87.65, NULL, NULL, '../img/product-img/Microfono Redragon Fenris GM301 RGB.jpg', '../img/product-img/Microfono Redragon Fenris GM301 RGB.jpg', 9),
(16, 'Mouse Pad Corsair MM300 Extended XL 930x300mm', 'Corsair', 40, NULL, NULL, '../img/product-img/Mouse Pad Corsair MM300 Extended XL 930x300mm.jpg', '../img/product-img/Mouse Pad Corsair MM300 Extended XL 930x300mm.jpg', 10),
(17, 'Silla Gamer Vertagear SL3800 HygennX Negro y Blanco Ergonomic (Peso MAX. 100kg)', 'Vertagear', 419.99, NULL, NULL, '../img/product-img/Silla Gamer.jpg', '../img/product-img/Silla Gamer.jpg', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `review`
--

INSERT INTO `review` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 3, NULL, 5, 'Excellent performance on high settings. No stutter at 1080p.', '2025-06-21 20:12:06'),
(2, 3, NULL, 4, 'Runs a bit hot, but delivers solid FPS in most games.', '2025-06-21 20:12:06'),
(3, 3, NULL, 5, 'Great value for the price. Quiet and reliable.', '2025-06-21 20:12:06'),
(4, 6, NULL, 5, 'Perfect match for my Ryzen build. No compatibility issues.', '2025-06-21 20:12:07'),
(5, 6, NULL, 4, 'Stable performance, though I wish it had RGB.', '2025-06-21 20:12:07'),
(6, 6, NULL, 5, 'Fast and affordable. Great for multitasking.', '2025-06-21 20:12:07'),
(7, 3, NULL, 5, 'Excellent performance on high settings. No stutter at 1080p.', '2025-06-21 20:19:13'),
(8, 3, NULL, 4, 'Runs a bit hot, but delivers solid FPS in most games.', '2025-06-21 20:19:13'),
(9, 3, NULL, 5, 'Great value for the price. Quiet and reliable.', '2025-06-21 20:19:13'),
(10, 3, NULL, 5, 'Excellent performance on high settings. No stutter at 1080p.', '2025-06-21 20:19:31'),
(11, 3, NULL, 4, 'Runs a bit hot, but delivers solid FPS in most games.', '2025-06-21 20:19:31'),
(12, 3, NULL, 5, 'Great value for the price. Quiet and reliable.', '2025-06-21 20:19:31'),
(13, 17, 7, 5, 'god producto', '2025-06-25 00:35:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `full_name`, `email`, `password`, `phone`) VALUES
(1, 'Lucas', 'lucas@s', '$2y$10$1BTIH3Q9ADd0FB.v/iSqf.3CyOHCkRtGtwpHz6X9E2Z4xN0jZ63kS', '1111111111'),
(3, 'Admin', 'admin@a', '$2y$10$1BTIH3Q9ADd0FB.v/iSqf.3CyOHCkRtGtwpHz6X9E2Z4xN0jZ63kS', NULL),
(6, 'Lautaro', 'Lautaro@c', '$2y$10$fo6iVoWK2OLYrA7gR9SHTOofnWLSnzzKiDWNRIf71VF3Hq6.gXymi', '11232323223'),
(7, 'prueba', 'prueba@p', '$2y$10$Bn2E3mTi2k24cfplVpYzUuQiIxu.j096qtFs5ai1cL14JvBtCyH6u', '23232323');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id_address`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indices de la tabla `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cart_user` (`user_id`);

--
-- Indices de la tabla `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`user_id`);

--
-- Indices de la tabla `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `id_payment` (`id_payment`),
  ADD KEY `id_address` (`id_address`);

--
-- Indices de la tabla `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id_order_detail`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id_payment`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`);

--
-- Indices de la tabla `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_review_user` (`user_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `address`
--
ALTER TABLE `address`
  MODIFY `id_address` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `order`
--
ALTER TABLE `order`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id_order_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `payment`
--
ALTER TABLE `payment`
  MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`id_payment`) REFERENCES `payment` (`id_payment`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `order_ibfk_3` FOREIGN KEY (`id_address`) REFERENCES `address` (`id_address`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `order` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
