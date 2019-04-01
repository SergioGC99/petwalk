-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2019 a las 20:25:01
-- Versión del servidor: 10.1.32-MariaDB
-- Versión de PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `petwalk`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `idowner` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `idwalker` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `notification`
--

INSERT INTO `notification` (`id`, `idowner`, `description`, `idwalker`, `status`) VALUES
(1, 1, 'Hi, I need a walk for my puppy', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `phonenumber` varchar(20) NOT NULL,
  `state` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `postalcode` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `owner`
--

INSERT INTO `owner` (`id`, `email`, `password`, `name`, `lastname`, `phonenumber`, `state`, `city`, `postalcode`, `image`) VALUES
(1, 'prueba', '12354', 'Carlos', 'Yair', '6642528128', 'Baja California', 'Tijuana', 22160, ''),
(2, 'sergio@gmail.com', '123', 'Sergio', 'Gonzalez', '6645998007', 'Baja California', 'Tijuana', 123, 'sergio.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pet`
--

CREATE TABLE `pet` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `breed` varchar(15) NOT NULL,
  `neutered` varchar(10) NOT NULL,
  `Birth` date NOT NULL,
  `height` decimal(10,0) NOT NULL,
  `weight` decimal(10,0) NOT NULL,
  `idowner` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pet`
--

INSERT INTO `pet` (`id`, `name`, `gender`, `breed`, `neutered`, `Birth`, `height`, `weight`, `idowner`, `image`) VALUES
(2, 'sdgsege', 'macho', 'yes', 'yes', '2019-03-11', '1', '5', 1, ''),
(3, 'Caramelo', 'male', 'Pastor', 'No', '2019-01-15', '2', '8', 2, 'caramelo.jpg'),
(4, 'Federico', 'male', 'Mix', 'No', '2019-01-15', '2', '7', 2, 'x.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `walker`
--

CREATE TABLE `walker` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `phonenumber` varchar(20) NOT NULL,
  `ocupation` varchar(11) NOT NULL,
  `age` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `state` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `postalcode` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `walker`
--

INSERT INTO `walker` (`id`, `email`, `password`, `name`, `lastname`, `phonenumber`, `ocupation`, `age`, `rating`, `state`, `city`, `postalcode`, `image`) VALUES
(1, 'prueba2', '12345', 'pedrito', 'juarez', '662355226', 'doctor', 25, 4, 'Baja California', 'Tijuana', 22160, ''),
(2, 'juan@gmail.com', '123', 'Angel', 'GG', '1232435', 'Studen', 19, 5, 'Baja California', 'Tijuana', 1234, 'x.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `walker_pet`
--

CREATE TABLE `walker_pet` (
  `id` int(11) NOT NULL,
  `idwalker` int(11) NOT NULL,
  `idpet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `walker_pet`
--

INSERT INTO `walker_pet` (`id`, `idwalker`, `idpet`) VALUES
(1, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `walk_detail`
--

CREATE TABLE `walk_detail` (
  `id` int(11) NOT NULL,
  `idpet` int(11) NOT NULL,
  `idnotification` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `payment` decimal(10,0) NOT NULL,
  `distance` double NOT NULL,
  `date` datetime NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `walk_detail`
--

INSERT INTO `walk_detail` (`id`, `idpet`, `idnotification`, `status`, `payment`, `distance`, `date`, `time`) VALUES
(1, 2, 1, 1, '10', 2000, '2019-03-19 00:00:00', '01:00:00'),
(2, 4, 1, 1, '100', 1, '2019-03-07 00:00:00', '01:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idowner` (`idowner`),
  ADD KEY `idwalker` (`idwalker`);

--
-- Indices de la tabla `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idowner` (`idowner`);

--
-- Indices de la tabla `walker`
--
ALTER TABLE `walker`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `walker_pet`
--
ALTER TABLE `walker_pet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idpet` (`idpet`),
  ADD KEY `idwalker` (`idwalker`);

--
-- Indices de la tabla `walk_detail`
--
ALTER TABLE `walk_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idpet` (`idpet`),
  ADD KEY `idnotification` (`idnotification`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pet`
--
ALTER TABLE `pet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `walker`
--
ALTER TABLE `walker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `walker_pet`
--
ALTER TABLE `walker_pet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `walk_detail`
--
ALTER TABLE `walk_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`idowner`) REFERENCES `owner` (`id`),
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`idwalker`) REFERENCES `walker` (`id`);

--
-- Filtros para la tabla `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_ibfk_1` FOREIGN KEY (`idowner`) REFERENCES `owner` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `walker_pet`
--
ALTER TABLE `walker_pet`
  ADD CONSTRAINT `walker_pet_ibfk_1` FOREIGN KEY (`idpet`) REFERENCES `pet` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `walker_pet_ibfk_2` FOREIGN KEY (`idwalker`) REFERENCES `walker` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `walk_detail`
--
ALTER TABLE `walk_detail`
  ADD CONSTRAINT `walk_detail_ibfk_1` FOREIGN KEY (`idpet`) REFERENCES `pet` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `walk_detail_ibfk_2` FOREIGN KEY (`idnotification`) REFERENCES `notification` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
