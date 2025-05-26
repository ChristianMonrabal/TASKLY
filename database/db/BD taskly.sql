-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 25-05-2025 a las 11:57:28
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taskly`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendario`
--

DROP TABLE IF EXISTS `calendario`;
CREATE TABLE IF NOT EXISTS `calendario` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `trabajo` bigint UNSIGNED NOT NULL,
  `cliente` bigint UNSIGNED NOT NULL,
  `trabajador` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendario_trabajo_foreign` (`trabajo`),
  KEY `calendario_cliente_foreign` (`cliente`),
  KEY `calendario_trabajador_foreign` (`trabajador`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `calendario`
--

INSERT INTO `calendario` (`id`, `titulo`, `descripcion`, `trabajo`, `cliente`, `trabajador`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 'El grifo pierde agua', 'El grifo pierde agua', 1, 5, 6, '2025-05-26', '09:30:00', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 'Instalación de enchufes', 'Instalación de enchufes', 2, 5, 6, '2025-05-28', '15:00:00', '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `visible` varchar(2) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Sí',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `visible`, `created_at`, `updated_at`) VALUES
(1, 'Electricidad', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(2, 'Fontanería', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(3, 'Carpintería', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(4, 'Pintura', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(5, 'Mantenimiento', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(6, 'Reparación de electrodomésticos', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(7, 'Cerrajería', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(8, 'Mudanzas', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(9, 'Reformas', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(10, 'Desatascos', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(11, 'Climatización', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(12, 'Jardinería', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(13, 'Limpieza', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(14, 'Construcción', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(15, 'Pavimentación', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(16, 'Albañilería', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(17, 'Decoración', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(18, 'Reparación de ordenadores', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(19, 'Reparación de móviles', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(20, 'Fotografía', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(21, 'Diseño gráfico', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(22, 'Marketing digital', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(23, 'Asesoría legal', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(24, 'Contabilidad', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(25, 'Clases particulares', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(26, 'Cuidado de mascotas', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(27, 'Cuidado de personas mayores', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(28, 'Cuidado de niños', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(29, 'Entrenamiento personal', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(30, 'Cuidado del hogar', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(31, 'Cuidado del jardín', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(32, 'Cuidado del coche', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(33, 'Cuidado de la salud', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(34, 'Cuidado de la belleza', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(35, 'Cuidado de la imagen personal', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(36, 'Cuidado de la ropa', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(37, 'Cuidado de la casa', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(38, 'Cuidado de la familia', 'Sí', '2025-05-25 09:49:12', '2025-05-25 09:49:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_tipo_trabajo`
--

DROP TABLE IF EXISTS `categorias_tipo_trabajo`;
CREATE TABLE IF NOT EXISTS `categorias_tipo_trabajo` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trabajo_id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categorias_tipo_trabajo_trabajo_id_foreign` (`trabajo_id`),
  KEY `categorias_tipo_trabajo_categoria_id_foreign` (`categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_tipo_trabajo`
--

INSERT INTO `categorias_tipo_trabajo` (`id`, `trabajo_id`, `categoria_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 2, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 3, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 3, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(5, 4, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(6, 5, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(7, 5, 17, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(8, 6, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(9, 6, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(10, 7, 17, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(11, 7, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(12, 8, 13, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(13, 8, 30, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(14, 9, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(15, 9, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(16, 10, 12, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(17, 10, 31, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(18, 11, 17, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(19, 11, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(20, 11, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chats`
--

DROP TABLE IF EXISTS `chats`;
CREATE TABLE IF NOT EXISTS `chats` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trabajo_id` bigint UNSIGNED NOT NULL,
  `emisor` bigint UNSIGNED NOT NULL,
  `receptor` bigint UNSIGNED NOT NULL,
  `contenido` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `leido` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chats_trabajo_id_foreign` (`trabajo_id`),
  KEY `chats_emisor_foreign` (`emisor`),
  KEY `chats_receptor_foreign` (`receptor`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `chats`
--

INSERT INTO `chats` (`id`, `trabajo_id`, `emisor`, `receptor`, `contenido`, `leido`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 6, 'Hola Alex, ¿puedes hacer el trabajo de instalación de enchufes?', 0, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 1, 5, 6, 'Hola Juan Carlos, ¿estás disponible para instalar los enchufes?', 0, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 2, 5, 6, 'Hola Daniel, ¿cuándo podrías comenzar con la reparación de la puerta?', 0, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 2, 5, 6, 'Hola Julio César, ¿estás libre para reparar la puerta?', 0, '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_bancarios`
--

DROP TABLE IF EXISTS `datos_bancarios`;
CREATE TABLE IF NOT EXISTS `datos_bancarios` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `titular` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `iban` varchar(34) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nombre_banco` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `stripe_account_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nif_fiscal` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `direccion_fiscal` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `codigo_postal_fiscal` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ciudad_fiscal` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `datos_bancarios_iban_unique` (`iban`),
  KEY `datos_bancarios_usuario_id_foreign` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `datos_bancarios`
--

INSERT INTO `datos_bancarios` (`id`, `usuario_id`, `titular`, `iban`, `nombre_banco`, `stripe_account_id`, `nif_fiscal`, `direccion_fiscal`, `codigo_postal_fiscal`, `ciudad_fiscal`, `created_at`, `updated_at`) VALUES
(1, 2, 'Christian', 'ES000000005233659374030670', 'Banco Ejemplo', 'acct_1RLTsZIxgDk5hYr7', NULL, NULL, NULL, NULL, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 3, 'Alex', 'ES000000007183713660024755', 'Banco Ejemplo', 'acct_1RLmSdIJN9D9qNg6', NULL, NULL, NULL, NULL, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 4, 'Daniel', 'ES000000007354711805433134', 'Banco Ejemplo', 'acct_1RLmMlIE2Y4Yj6ja', NULL, NULL, NULL, NULL, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 5, 'Cliente', 'ES000000003961569628881933', 'Banco Ejemplo', NULL, NULL, NULL, NULL, NULL, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(5, 6, 'Trabajador', 'ES000000002462969994611388', 'Banco Ejemplo', NULL, NULL, NULL, NULL, NULL, '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

DROP TABLE IF EXISTS `estados`;
CREATE TABLE IF NOT EXISTS `estados` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo_estado` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `nombre`, `tipo_estado`, `created_at`, `updated_at`) VALUES
(1, 'Pendiente', 'trabajos', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 'En Progreso', 'trabajos', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 'Completado', 'trabajos', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 'Cancelado', 'trabajos', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(5, 'Pendiente', 'pagos', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(6, 'pagado', 'pagos', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(7, 'Reembolsado', 'pagos', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(8, 'Rechazado', 'pagos', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(9, 'Pendiente', 'postulaciones', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(10, 'Aceptada', 'postulaciones', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(11, 'Rechazada', 'postulaciones', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(12, 'Baja', 'reporte_gravedad', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(13, 'Media', 'reporte_gravedad', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(14, 'Alta', 'reporte_gravedad', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(15, 'Espera', 'reporte_estado', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(16, 'Abierto', 'reporte_estado', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(17, 'Cerrado', 'reporte_estado', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(18, 'finalizado', 'postulaciones', '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habilidades`
--

DROP TABLE IF EXISTS `habilidades`;
CREATE TABLE IF NOT EXISTS `habilidades` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trabajador_id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `habilidades_trabajador_id_foreign` (`trabajador_id`),
  KEY `habilidades_categoria_id_foreign` (`categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `habilidades`
--

INSERT INTO `habilidades` (`id`, `trabajador_id`, `categoria_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 3, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 4, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 5, 4, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(5, 6, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `img_trabajos`
--

DROP TABLE IF EXISTS `img_trabajos`;
CREATE TABLE IF NOT EXISTS `img_trabajos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruta_imagen` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `trabajo_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `img_trabajos_trabajo_id_foreign` (`trabajo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `img_trabajos`
--

INSERT INTO `img_trabajos` (`id`, `ruta_imagen`, `descripcion`, `trabajo_id`, `created_at`, `updated_at`) VALUES
(1, 'electricidad.png', 'electricidad', 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 'fontaneria.png', 'fontaneria', 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 'carpinteria.png', 'carpinteria', 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 'fontaneria.png', 'fontaneria', 4, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(5, 'electricidad.png', 'electricidad', 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(6, 'carpinteria.png', 'carpinteria', 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(7, 'decoracion.png', 'colocación de cortinas', 7, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(8, 'limpieza.png', 'limpieza de garaje', 8, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(9, 'persiana.png', 'reparación de persiana', 9, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(10, 'jardineria.png', 'mantenimiento de jardín', 10, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(11, 'bricolaje.png', 'instalación de espejo', 11, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(12, 'aire.png', 'aire acondicionado', 12, '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb3_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logros`
--

DROP TABLE IF EXISTS `logros`;
CREATE TABLE IF NOT EXISTS `logros` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci,
  `foto_logro` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `logros`
--

INSERT INTO `logros` (`id`, `nombre`, `descripcion`, `foto_logro`, `created_at`, `updated_at`) VALUES
(1, 'Primer trabajo', 'Has completado tu primer trabajo exitosamente.', 'Insignia1.png', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(2, 'Valoración perfecta', 'Recibiste 5 valoraciones con 5 estrellas.', 'Insignia2.png', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(3, '10 trabajos completados', 'Has completado 10 trabajos exitosamente.', 'Insignia3.png', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(4, '50 trabajos completados', 'Has completado 50 trabajos exitosamente.', 'Insignia4.png', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(5, '100 trabajos completados', 'Has completado 100 trabajos exitosamente.', 'Insignia5.png', '2025-05-25 09:49:12', '2025-05-25 09:49:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logros_completos`
--

DROP TABLE IF EXISTS `logros_completos`;
CREATE TABLE IF NOT EXISTS `logros_completos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `logro_id` bigint UNSIGNED NOT NULL,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logros_completos_logro_id_foreign` (`logro_id`),
  KEY `logros_completos_usuario_id_foreign` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `logros_completos`
--

INSERT INTO `logros_completos` (`id`, `logro_id`, `usuario_id`, `created_at`, `updated_at`) VALUES
(1, 1, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 2, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 3, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 4, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(5, 5, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

DROP TABLE IF EXISTS `metodos_pago`;
CREATE TABLE IF NOT EXISTS `metodos_pago` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `metodos_pago_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Tarjeta de crédito', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(2, 'PayPal', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(3, 'Transferencia bancaria', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(4, 'Efectivo', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(5, 'Bizum', '2025-05-25 09:49:12', '2025-05-25 09:49:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_roles_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2023_04_10_000003_create_datos_bancarios_table', 1),
(6, '2023_04_10_000005_create_estados_table', 1),
(7, '2023_04_10_000007_create_metodos_pago_table', 1),
(8, '2023_04_10_000008_create_categorias_table', 1),
(9, '2023_04_10_000009_create_trabajos_table', 1),
(10, '2023_04_10_000010_create_categorias_tipo_trabajo_table', 1),
(11, '2023_04_10_000011_create_postulaciones_table', 1),
(12, '2023_04_10_000012_create_chats_table', 1),
(13, '2023_04_10_000013_create_valoraciones_table', 1),
(14, '2023_04_10_000014_create_pagos_table', 1),
(15, '2023_04_10_000015_create_habilidades_table', 1),
(16, '2023_04_10_000016_create_notificaciones_table', 1),
(17, '2023_04_10_000017_create_logros_table', 1),
(18, '2023_04_10_000018_create_logros_completos_table', 1),
(19, '2023_04_10_000019_create_img_trabajos_table', 1),
(20, '2025_04_22_161532_add_alta_responsabilidad_to_trabajos_table', 1),
(21, '2025_04_23_153343_create_calendario_table', 1),
(22, '2025_04_29_131948_add_visible_to_categorias_table', 1),
(23, '2025_04_29_134947_change_visible_to_string_in_categorias_table', 1),
(24, '2025_05_09_143632_add_tipo_to_notificaciones_table', 1),
(25, '2025_05_12_202028_add_stripe_account_id_to_datos_bancarios_table', 1),
(26, '2025_05_14_014126_create_reportes_table', 1),
(27, '2025_05_14_155055_add_trabajo_id_to_notificaciones_table', 1),
(28, '2025_05_16_152117_add_activo_to_users_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `mensaje` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `trabajo_id` bigint UNSIGNED DEFAULT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'general',
  PRIMARY KEY (`id`),
  KEY `notificaciones_usuario_id_foreign` (`usuario_id`),
  KEY `notificaciones_trabajo_id_foreign` (`trabajo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

DROP TABLE IF EXISTS `pagos`;
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `postulacion_id` bigint UNSIGNED NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `estado_id` bigint UNSIGNED NOT NULL,
  `metodo_id` bigint UNSIGNED NOT NULL,
  `fecha_pago` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_postulacion_id_foreign` (`postulacion_id`),
  KEY `pagos_estado_id_foreign` (`estado_id`),
  KEY `pagos_metodo_id_foreign` (`metodo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `postulacion_id`, `cantidad`, `estado_id`, `metodo_id`, `fecha_pago`, `created_at`, `updated_at`) VALUES
(1, 1, 79.00, 6, 3, '2025-05-11 09:49:14', '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulaciones`
--

DROP TABLE IF EXISTS `postulaciones`;
CREATE TABLE IF NOT EXISTS `postulaciones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trabajo_id` bigint UNSIGNED NOT NULL,
  `trabajador_id` bigint UNSIGNED NOT NULL,
  `estado_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trabajo_trabajador_unico` (`trabajo_id`,`trabajador_id`),
  KEY `postulaciones_trabajador_id_foreign` (`trabajador_id`),
  KEY `postulaciones_estado_id_foreign` (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `postulaciones`
--

INSERT INTO `postulaciones` (`id`, `trabajo_id`, `trabajador_id`, `estado_id`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 9, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 2, 6, 9, '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

DROP TABLE IF EXISTS `reportes`;
CREATE TABLE IF NOT EXISTS `reportes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `motivo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `id_usuario` bigint UNSIGNED NOT NULL,
  `gravedad` bigint UNSIGNED NOT NULL,
  `estado` bigint UNSIGNED NOT NULL,
  `reportado_Por` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reportes_id_usuario_foreign` (`id_usuario`),
  KEY `reportes_gravedad_foreign` (`gravedad`),
  KEY `reportes_estado_foreign` (`estado`),
  KEY `reportes_reportado_por_foreign` (`reportado_Por`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `motivo`, `id_usuario`, `gravedad`, `estado`, `reportado_Por`, `created_at`, `updated_at`) VALUES
(1, 'T1g5xQs52JgwozodYnpvby41citHNrv11yLEZkyy', 5, 14, 15, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 'r3Te8qgAVLv8WW0HOirUzQP9IJhOIztri4NLgCeU', 6, 12, 15, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 'rLJfrSGniCULWaVnvTQZESqBdfmcf1NSqpQpBbfR', 4, 13, 17, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 'LIAS0v3jKka7PbPSOt1SwQkNLbGiuEWe7K4VZluU', 1, 14, 15, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(5, 'Jq5DGZo2cCNiBMpZjgfzM0rMI44H2tix6a5zVwiB', 3, 14, 17, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(6, 'aTtUR4CnkJEfAhMCr7OcTv6T3OSZL1ogU2dr6MA6', 6, 13, 15, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(7, '9S3g2qFH5tVWWdcVdifJeOvZiZ0eVHQLGvqjCmP4', 1, 13, 16, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(8, 'DpzTKPsiz8BxXgfEVrfQmRkgxwvUdQOui6KblV1p', 4, 12, 16, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(9, 'WCAAIhR7BTXXNYpJO5LJWWmMGU3UycnSvuaZLlAT', 3, 14, 15, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(10, 'aTY6puEHrgqWixg30zhk1491UCxvx7Ofn1N4HY68', 3, 14, 15, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(11, '4i6wujGWYvF3XWpIVabE0PwxBSbKRDlHCyeumGMS', 2, 13, 17, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(12, 'O2U3U7ySy0lc2SIP9VLopGHpKZCIwcuDXkcJwoS2', 4, 13, 15, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(13, 'mix1vQwgitdmN7FZKiMvxGH9rUXS07OIOAgGycUR', 5, 14, 15, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(14, '2XjrvQnAbc7StGkaMkUINFvxJCKgbLfdSr5hByWp', 1, 14, 17, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(15, 'FgrQl5LIn634IXH68AnFTuWbUdWB2v1h0eL8XpFN', 3, 12, 15, 4, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(16, 'P1jqEV8RaWcuLw9yAZLPdaSvgtWLxOvtQQ3JgFLj', 4, 12, 17, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(17, '4a7qwffs5LQFkw37PLXoedxHIq7mEnlLYpjoNgY5', 5, 14, 17, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(18, 'MGzztZivb5u80iHfFKUSXgDOXtgbrUP22zDfgCBK', 2, 13, 16, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(19, 'DZg1Jp8DgcGI6BLlhO7mMn7tTUqPoCdTgogEcBXF', 1, 13, 16, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(20, 'T9mjnDNlpMRcb3nNiZjytVx3LetscXomgyh5siY1', 1, 13, 16, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(21, 'SZtRju84OTX3eXOWroz43cem13g2DfJI73PgjTkC', 3, 14, 16, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(22, 'JSV4IkONq2hew23618YB4ygwVbplzaKBydQCYcwr', 5, 13, 17, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(23, '91q2bv7Q2ZfnhXE8BwBaAbLCQxhMNwt0OZIs92kn', 6, 12, 16, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(24, 'OGkmj4Tt80vafU97fZh5gWXArdkBvvSN0j7Lj3va', 5, 13, 15, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(25, '3XycD98zUfI3IDNuKMyoSZGecuu1XF1XXQuU2RvC', 6, 14, 15, 4, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(26, '774SXuRx2o5KEqmB9fFRYiCmhTE00VZrGnXnleiV', 2, 12, 16, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(27, 'XIdSk2hdbJBwUQojptjhV8Fag2CKBAknAbLBszmQ', 3, 12, 17, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(28, '0EFq9pa9SllaJCx2WAfXHB4G8EbIfRu9VlEeoioJ', 4, 14, 15, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(29, 'gHCvVToTruLGRkBtgmLsanHIiEtPkxl42uMGFZtz', 2, 13, 17, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(30, 'yTCe0OUIJuzMyADHdqB0GRGmKqi5NKhaO8nmx546', 2, 12, 16, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(31, 'bxZSQ1n4AUwWrjGNYtko84DlekGkwv5kbsJgzd8t', 5, 14, 16, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(32, 'KnYYe3wgnTFQVMREJgOIwOvd2mH8LzbebeYkwdy3', 1, 14, 17, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(33, 'rVhaGzFRkINPrYh9tZmiRABrxT2GCX2PFCtWpxtt', 5, 14, 15, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(34, 'qsbDyZEZXbsa3NSbijf0R7eR6ZCVvDbHL0qGrwFr', 3, 13, 15, 5, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(35, 'g6fJKManCAvr29T7aEga504ItQMX8wsPd3yZDgFR', 6, 12, 16, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(36, '9WP35WAAalSCbIQkl6EpgGez9rvOfNO6Ae82HgpU', 5, 14, 17, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(37, 'XE48s2QJPcsMdE5Ve2Lkv8WlxwxqYnZC7Or5Ixhb', 4, 13, 17, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(38, 'pzfzpfj0ZSmv0ogLvVQ0f1EvuDhyzNfYYpsGnk0y', 3, 12, 15, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(39, '3cVedLMXdVcgKksusRodhT3wyg4wOTBIL5P6JpWJ', 2, 13, 17, 1, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(40, 'GVoxVog1D1bzn4u6QnPpjUaQgscK9Nzd95XjxNN9', 6, 14, 16, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(41, 'vDukRKEyheWr69RRG3wIq0CzBxr6mTVZo0NqptCO', 3, 14, 16, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(42, 'UdzZmWGhZnnEtTINGXXXA79DY29iLQro0C8EGZP5', 5, 14, 16, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(43, 'N19k7MnBgU54r6gyTgFt4aVbPBRfphxjgwzWkp0Q', 3, 14, 17, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(44, 'OSWlaa9F7CpmkHvWfNS79Kx7i8sUbVkdAq6z0Sez', 1, 13, 16, 3, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(45, 'FfCMv498o5U9T3XY2T5shfM5rrI9MeUaXcdZJmNQ', 3, 12, 16, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(46, 'cOm3AS3UFkVq8OrT7MA8Otd2tsR7tXVy9hM6cEu4', 6, 12, 15, 4, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(47, 'T5QXLUpIpA396NbmFblPsKJbzCFt4rKbtucSvm9X', 5, 12, 17, 2, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(48, 'mKSKJxk9tfy9sgde8KMXKU7NsJcOsf9GUx4rSiaQ', 5, 14, 16, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(49, 'l0rQ7WPPWbuRpCkcDRtNxREkPE3s6DqjldNQVSaY', 4, 14, 16, 6, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(50, 'KZB7TfZj6UKlbN2DSUoV6gENH18a7BiwuujmYluR', 1, 12, 16, 4, '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', '2025-05-25 09:49:12', '2025-05-25 09:49:12'),
(2, 'Cliente', '2025-05-25 09:49:12', '2025-05-25 09:49:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb3_unicode_ci,
  `payload` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dhCxQ3YjjTsmUxJTyFOsA0S58kBKPCdldkmLrzFr', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWmFUalhWTHJZZGxRNVhXNE1zbW9BQ3hGem1hdmhVcG1VT1Q5NlNKRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTczOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcmVzdGFydC1wYXNzd29yZD9lbWFpbD1jaHJpc3RpYW5tb25yYWJhbGRvbmlzJTQwZ21haWwuY29tJmV4cGlyZXM9MTc0ODE3NzQ4MiZzaWduYXR1cmU9MGI0YTNjNjVjYzdjNWU4NDVjODhmZmE1YWMwYmExZWRjM2Q1ZDM1Y2FmYWU2YTM2MTliNWQ5MzVjYTU4YTk2OSI7fX0=', 1748173888),
('RNCzOLEBEymS4QuFHho27QgC9EBv5xVBJf7uVXYd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSlZHWXRjRnFlWXFDRnlxdElSZjhjMHF1bzducVVsV0FuSE1jRk16MCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTczOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcmVzdGFydC1wYXNzd29yZD9lbWFpbD1jaHJpc3RpYW5tb25yYWJhbGRvbmlzJTQwZ21haWwuY29tJmV4cGlyZXM9MTc0ODE3NzQ4MiZzaWduYXR1cmU9MGI0YTNjNjVjYzdjNWU4NDVjODhmZmE1YWMwYmExZWRjM2Q1ZDM1Y2FmYWU2YTM2MTliNWQ5MzVjYTU4YTk2OSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1748173889);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajos`
--

DROP TABLE IF EXISTS `trabajos`;
CREATE TABLE IF NOT EXISTS `trabajos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci,
  `precio` decimal(10,2) NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ciudad` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `latitud` decimal(10,7) DEFAULT NULL,
  `longitud` decimal(10,7) DEFAULT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `estado_id` bigint UNSIGNED NOT NULL,
  `fecha_limite` date DEFAULT NULL,
  `alta_responsabilidad` varchar(2) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trabajos_cliente_id_foreign` (`cliente_id`),
  KEY `trabajos_estado_id_foreign` (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `trabajos`
--

INSERT INTO `trabajos` (`id`, `titulo`, `descripcion`, `precio`, `direccion`, `codigo_postal`, `ciudad`, `latitud`, `longitud`, `cliente_id`, `estado_id`, `fecha_limite`, `alta_responsabilidad`, `created_at`, `updated_at`) VALUES
(1, 'El grifo pierde agua', 'Ayer empezo a perderme agua el grifo de la cocina, necesito que venga un fontanero a repararlo.', 10.00, '08905', NULL, NULL, NULL, NULL, 5, 1, '2025-06-03', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 'Instalación de enchufes', 'Necesito un electricista para instalar 3 enchufes en mi casa.', 10.00, '08905', NULL, NULL, NULL, NULL, 5, 1, '2025-06-11', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(3, 'Reparación de puerta', 'La puerta de la habitación no cierra bien, necesito un carpintero para repararla.', 10.00, '08906', NULL, NULL, NULL, NULL, 5, 1, '2025-06-15', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(4, 'Instalación de grifo', 'Necesito un fontanero para instalar un grifo en la cocina.', 10.00, '08906', NULL, NULL, NULL, NULL, 5, 1, '2025-06-01', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(5, 'Instalación de lámpara', 'Necesito un electricista para instalar una lámpara en el salón.', 10.00, '08904', NULL, NULL, NULL, NULL, 5, 1, '2025-06-23', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(6, 'Reparación de ventana', 'La ventana del salón no cierra bien, necesito un carpintero para repararla.', 10.00, '08904', NULL, NULL, NULL, NULL, 5, 1, '2025-06-17', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(7, 'Colocación de cortinas', 'Necesito ayuda para instalar cortinas en el salón y dormitorio.', 18.00, '08903', NULL, NULL, NULL, NULL, 5, 1, '2025-06-18', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(8, 'Limpieza de garaje', 'Busco alguien que pueda limpiar mi garaje de 20m².', 25.00, '08903', NULL, NULL, NULL, NULL, 5, 1, '2025-05-26', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(9, 'Reparación de persiana', 'La persiana del dormitorio está atascada, necesito repararla.', 14.00, '08907', NULL, NULL, NULL, NULL, 5, 1, '2025-06-05', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(10, 'Mantenimiento de jardín', 'Cortar césped y podar setos en un jardín pequeño.', 35.00, '08907', NULL, NULL, NULL, NULL, 5, 1, '2025-06-07', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(11, 'Instalación de espejo grande', 'Quiero colgar un espejo grande en el recibidor, se necesita taladro y nivel.', 22.00, '08905', NULL, NULL, NULL, NULL, 5, 1, '2025-06-06', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(12, 'Instalación de aire acondicionado', 'Montaje y puesta en marcha de equipo split de 3000 frigorías.', 350.00, '08905', NULL, NULL, NULL, NULL, 5, 3, '2025-05-10', 'No', '2025-05-25 09:49:14', '2025-05-25 09:49:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `activo` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'si',
  `telefono` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `google_id` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `foto_perfil` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb3_unicode_ci,
  `dni` varchar(9) COLLATE utf8mb3_unicode_ci NOT NULL,
  `rol_id` bigint UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_dni_unique` (`dni`),
  KEY `users_rol_id_foreign` (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellidos`, `email`, `email_verified_at`, `password`, `activo`, `telefono`, `codigo_postal`, `google_id`, `fecha_nacimiento`, `foto_perfil`, `descripcion`, `dni`, `rol_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Administrador', 'admin@taskly.com', NULL, '$2y$12$s6AJw7xfFnuB0gv5AwAL3O3JaXOAlYRT8AFZzREPs8voxhPczFewS', 'si', '630099700', '08001', NULL, '2000-01-01', 'perfil_default.png', 'Administrador del sistema', '12345678A', 1, NULL, '2025-05-25 09:49:13', '2025-05-25 09:49:13'),
(2, 'Christian', 'Monrabal Donis', 'Christian@taskly.com', NULL, '$2y$12$3RkXovNsz5EdeWqcbBrEU.FjGLJGo4AufzcnFOTQWpBdOBcI8zg6S', 'si', '746687398', '08810', NULL, '2000-01-01', 'perfil_default.png', 'Adicto al fentanilo.', '25482103N', 2, NULL, '2025-05-25 09:49:13', '2025-05-25 09:50:08'),
(3, 'Alex', 'Ventura Reynés', 'Alex@taskly.com', NULL, '$2y$12$G6mt7hAvi8qz2qePeLm9re96yDGTOpPubo3t0w8/P97cmkQBRcXLm', 'si', '676493142', '08810', NULL, '2000-01-01', 'perfil_default.png', 'Soy Alex, me encanta resolver problemas prácticos y ayudar a los demás.', '96328481L', 2, NULL, '2025-05-25 09:49:13', '2025-05-25 09:49:13'),
(4, 'Daniel', 'Becerra Vidaume', 'Daniel@taskly.com', NULL, '$2y$12$G4cpyzjZd.QsG74UW2y/oubeyAlqqemjslk8YlHSrvJaDvf0GSLsy', 'si', '666667917', '08810', NULL, '2000-01-01', 'perfil_default.png', 'Técnico de confianza. Me especializo en arreglos del hogar y mantenimiento.', '52980798E', 2, NULL, '2025-05-25 09:49:13', '2025-05-25 09:49:13'),
(5, 'Cliente', 'Cliente', 'cliente@taskly.com', NULL, '$2y$12$NeXM9UEUEbqLWBR1JJLne.UsnCUJablbqOR20GLldfIJmVK2O15om', 'si', '600000001', '08001', NULL, '1990-05-20', 'perfil_default.png', 'Cliente habitual en busca de profesionales confiables.', 'X8017683K', 2, NULL, '2025-05-25 09:49:13', '2025-05-25 09:49:13'),
(6, 'Trabajador', 'Ejemplar', 'trabajador@taskly.com', NULL, '$2y$12$sJXnLwVLlhP4pMnwXA7rquSiOzdVCwXUpE6Vb8Rnqcq9cP/NVo/by', 'si', '600000002', '08002', NULL, '1985-10-10', 'perfil_default.png', 'Profesional con años de experiencia en distintos sectores.', 'Y1267159B', 2, NULL, '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(7, 'Christian Monrabal', '', 'christianmonrabaldonis@gmail.com', NULL, '$2y$12$342QdSbIVIa1T6Mt4kFeBe/91UrbU3ujNoDLeafzR0SnYsLGy3bLm', 'si', NULL, NULL, '107680800964625662700', NULL, NULL, NULL, '', 2, 'EFoxNc83lvlXpnQk2WXo9CsZaV0h5nfmPRAt9eVv6pWmEfps9cjmejp6GJyN', '2025-05-25 09:50:57', '2025-05-25 09:50:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

DROP TABLE IF EXISTS `valoraciones`;
CREATE TABLE IF NOT EXISTS `valoraciones` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `trabajo_id` bigint UNSIGNED NOT NULL,
  `trabajador_id` bigint UNSIGNED NOT NULL,
  `puntuacion` tinyint NOT NULL,
  `img_valoracion` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `comentario` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valoraciones_trabajo_id_foreign` (`trabajo_id`),
  KEY `valoraciones_trabajador_id_foreign` (`trabajador_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `valoraciones`
--

INSERT INTO `valoraciones` (`id`, `trabajo_id`, `trabajador_id`, `puntuacion`, `img_valoracion`, `comentario`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 2, 'persiana.png', 'Llegó puntual y realizó el trabajo de forma correcta.', '2025-05-25 09:49:14', '2025-05-25 09:49:14'),
(2, 2, 6, 3, 'persiana.png', 'Todo perfecto, muy contento con el resultado.', '2025-05-25 09:49:14', '2025-05-25 09:49:14');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calendario`
--
ALTER TABLE `calendario`
  ADD CONSTRAINT `calendario_cliente_foreign` FOREIGN KEY (`cliente`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `calendario_trabajador_foreign` FOREIGN KEY (`trabajador`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `calendario_trabajo_foreign` FOREIGN KEY (`trabajo`) REFERENCES `trabajos` (`id`);

--
-- Filtros para la tabla `categorias_tipo_trabajo`
--
ALTER TABLE `categorias_tipo_trabajo`
  ADD CONSTRAINT `categorias_tipo_trabajo_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `categorias_tipo_trabajo_trabajo_id_foreign` FOREIGN KEY (`trabajo_id`) REFERENCES `trabajos` (`id`);

--
-- Filtros para la tabla `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_emisor_foreign` FOREIGN KEY (`emisor`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `chats_receptor_foreign` FOREIGN KEY (`receptor`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `chats_trabajo_id_foreign` FOREIGN KEY (`trabajo_id`) REFERENCES `trabajos` (`id`);

--
-- Filtros para la tabla `datos_bancarios`
--
ALTER TABLE `datos_bancarios`
  ADD CONSTRAINT `datos_bancarios_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `habilidades`
--
ALTER TABLE `habilidades`
  ADD CONSTRAINT `habilidades_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `habilidades_trabajador_id_foreign` FOREIGN KEY (`trabajador_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `img_trabajos`
--
ALTER TABLE `img_trabajos`
  ADD CONSTRAINT `img_trabajos_trabajo_id_foreign` FOREIGN KEY (`trabajo_id`) REFERENCES `trabajos` (`id`);

--
-- Filtros para la tabla `logros_completos`
--
ALTER TABLE `logros_completos`
  ADD CONSTRAINT `logros_completos_logro_id_foreign` FOREIGN KEY (`logro_id`) REFERENCES `logros` (`id`),
  ADD CONSTRAINT `logros_completos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_trabajo_id_foreign` FOREIGN KEY (`trabajo_id`) REFERENCES `trabajos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificaciones_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`),
  ADD CONSTRAINT `pagos_metodo_id_foreign` FOREIGN KEY (`metodo_id`) REFERENCES `metodos_pago` (`id`),
  ADD CONSTRAINT `pagos_postulacion_id_foreign` FOREIGN KEY (`postulacion_id`) REFERENCES `postulaciones` (`id`);

--
-- Filtros para la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD CONSTRAINT `postulaciones_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`),
  ADD CONSTRAINT `postulaciones_trabajador_id_foreign` FOREIGN KEY (`trabajador_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `postulaciones_trabajo_id_foreign` FOREIGN KEY (`trabajo_id`) REFERENCES `trabajos` (`id`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_estado_foreign` FOREIGN KEY (`estado`) REFERENCES `estados` (`id`),
  ADD CONSTRAINT `reportes_gravedad_foreign` FOREIGN KEY (`gravedad`) REFERENCES `estados` (`id`),
  ADD CONSTRAINT `reportes_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reportes_reportado_por_foreign` FOREIGN KEY (`reportado_Por`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `trabajos`
--
ALTER TABLE `trabajos`
  ADD CONSTRAINT `trabajos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `trabajos_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_trabajador_id_foreign` FOREIGN KEY (`trabajador_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `valoraciones_trabajo_id_foreign` FOREIGN KEY (`trabajo_id`) REFERENCES `trabajos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
