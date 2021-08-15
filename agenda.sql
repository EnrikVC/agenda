-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 15-08-2021 a las 04:44:16
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agenda`
--
CREATE DATABASE IF NOT EXISTS `agenda` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agenda`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `address`, `email`, `created_at`, `updated_at`) VALUES
(3, 'John Doe Updated', 'aaaaaaaaaaaaaaaaaaaaaaa', 'john.doe@gmail.com', '2021-08-13 03:20:41', '2021-08-15 09:04:26'),
(4, 'Jane Doe', 'aaaaaaaaaaaaaaaaaaaaaaa', 'jane.doe@gmail.com', '2021-08-13 03:59:17', '2021-08-13 03:59:17'),
(5, 'Juan Pérez', 'aaaaaaaaaaaaaaaaaaaaaaa', 'juan.perez@limatransvial.com', '2021-08-13 06:23:29', '2021-08-13 06:23:29'),
(6, 'Juana Gómez', NULL, NULL, '2021-08-14 08:15:43', '2021-08-14 08:15:43'),
(8, 'borrar', NULL, NULL, '2021-08-15 09:26:36', '2021-08-15 09:26:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phones`
--

DROP TABLE IF EXISTS `phones`;
CREATE TABLE IF NOT EXISTS `phones` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contact_id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(9) NOT NULL,
  `type` char(1) DEFAULT '',
  `isdefault` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_phone_contact` (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `phones`
--

INSERT INTO `phones` (`id`, `contact_id`, `number`, `type`, `isdefault`, `created_at`, `updated_at`) VALUES
(3, 4, '5478547', 'C', 1, '2021-08-13 03:59:17', '2021-08-13 03:59:17'),
(4, 5, '958623562', 'T', 1, '2021-08-13 06:23:29', '2021-08-13 06:23:29'),
(5, 6, '989898989', 'M', 1, '2021-08-14 08:15:43', '2021-08-14 08:15:43'),
(7, 3, '5784587', 'C', 0, '2021-08-14 08:45:36', '2021-08-15 01:33:06'),
(8, 3, '756756756', 'T', 0, '2021-08-14 09:00:54', '2021-08-15 01:33:06'),
(9, 3, '967867867', 'M', 1, '2021-08-14 09:02:08', '2021-08-15 01:33:06'),
(10, 3, '5784589', 'C', 0, '2021-08-14 09:02:58', '2021-08-15 01:33:06'),
(11, 3, '988756756', 'M', 0, '2021-08-14 09:10:48', '2021-08-15 01:33:06');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `phones`
--
ALTER TABLE `phones`
  ADD CONSTRAINT `phones_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
