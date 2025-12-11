-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-12-2025 a las 22:49:47
-- Versión del servidor: 9.4.0
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cofrupa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bins`
--

CREATE TABLE `bins` (
  `id` bigint UNSIGNED NOT NULL,
  `bin_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('wood','plastic') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ownership_type` enum('supplier','internal','field') COLLATE utf8mb4_unicode_ci DEFAULT 'field',
  `weight_capacity` decimal(8,2) NOT NULL,
  `current_weight` decimal(8,2) NOT NULL DEFAULT '0.00',
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('available','in_use','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `damage_description` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bins`
--

INSERT INTO `bins` (`id`, `bin_number`, `type`, `ownership_type`, `weight_capacity`, `current_weight`, `supplier_id`, `status`, `photo_path`, `delivery_date`, `return_date`, `damage_description`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'WOOD-001', 'wood', 'internal', 60.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(2, 'WOOD-002', 'wood', 'internal', 60.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(3, 'WOOD-003', 'wood', 'internal', 60.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(4, 'WOOD-004', 'wood', 'internal', 60.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(5, 'WOOD-005', 'wood', 'internal', 60.00, 1000.00, 1, 'in_use', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-06 02:11:33'),
(6, 'WOOD-006', 'wood', 'internal', 60.00, 1000.00, 1, 'in_use', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-06 02:11:33'),
(7, 'WOOD-007', 'wood', 'internal', 60.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(8, 'WOOD-008', 'wood', 'internal', 60.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(9, 'WOOD-009', 'wood', 'internal', 60.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(10, 'WOOD-010', 'wood', 'internal', 60.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(11, 'PLASTIC-001', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(12, 'PLASTIC-002', 'plastic', 'internal', 45.00, 1000.00, NULL, 'available', NULL, NULL, '2025-12-11', NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-12 01:32:15'),
(13, 'PLASTIC-003', 'plastic', 'internal', 45.00, 1000.00, NULL, 'available', NULL, NULL, '2025-12-11', NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-12 01:32:15'),
(14, 'PLASTIC-004', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(15, 'PLASTIC-005', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(16, 'PLASTIC-006', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(17, 'PLASTIC-007', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(18, 'PLASTIC-008', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(19, 'PLASTIC-009', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(20, 'PLASTIC-010', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(21, 'PLASTIC-011', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(22, 'PLASTIC-012', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(23, 'PLASTIC-013', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(24, 'PLASTIC-014', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05'),
(25, 'PLASTIC-015', 'plastic', 'internal', 45.00, 0.00, NULL, 'available', NULL, NULL, NULL, NULL, 'Bin plástico para ciruelas', '2025-12-05 20:59:05', '2025-12-05 20:59:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bin_assignments`
--

CREATE TABLE `bin_assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `bin_id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `delivery_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `weight_delivered` decimal(8,2) NOT NULL DEFAULT '0.00',
  `weight_returned` decimal(8,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login_logs`
--

CREATE TABLE `login_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` timestamp NOT NULL,
  `logout_at` timestamp NULL DEFAULT NULL,
  `successful` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `email`, `ip_address`, `user_agent`, `login_at`, `logout_at`, `successful`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', '2025-12-05 03:02:33', NULL, 1, NULL, '2025-12-05 03:02:33', '2025-12-05 03:02:33'),
(2, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', '2025-12-05 03:18:41', NULL, 1, NULL, '2025-12-05 03:18:41', '2025-12-05 03:18:41'),
(3, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', '2025-12-05 19:14:45', NULL, 1, NULL, '2025-12-05 19:14:45', '2025-12-05 19:14:45'),
(4, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', '2025-12-06 01:36:32', NULL, 1, NULL, '2025-12-06 01:36:32', '2025-12-06 01:36:32'),
(5, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', '2025-12-11 14:43:31', NULL, 1, NULL, '2025-12-11 14:43:31', '2025-12-11 14:43:31'),
(6, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', '2025-12-11 15:40:49', NULL, 1, NULL, '2025-12-11 15:40:49', '2025-12-11 15:40:49'),
(7, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', '2025-12-12 00:23:02', NULL, 1, NULL, '2025-12-12 00:23:02', '2025-12-12 00:23:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_12_04_224727_add_google2fa_secret_to_users_table', 2),
(6, '2025_12_04_225012_create_permission_tables', 2),
(7, '2025_12_04_225218_create_producers_table', 2),
(8, '2025_12_04_235349_create_login_logs_table', 3),
(9, '2025_12_05_162234_create_suppliers_table', 4),
(10, '2025_12_05_162318_create_bins_table', 4),
(11, '2025_12_05_162400_create_purchases_table', 5),
(13, '2025_12_05_174544_add_purchase_order_to_purchases_table', 6),
(14, '2025_12_05_180938_add_bin_maintenance_fields_to_bins_table', 6),
(15, '2025_12_05_182112_create_bin_assignments_table', 7),
(16, '2025_12_05_183054_add_payment_due_date_to_purchases_table', 8),
(17, '2025_12_05_185310_add_bin_ids_to_purchases_table', 9),
(18, '2025_12_05_223035_make_bin_id_nullable_in_purchases_table', 10),
(19, '2025_12_05_225338_create_purchase_bins_table', 11),
(21, '2025_12_11_122646_create_processed_bins_table', 12),
(22, '2025_12_11_212630_add_csg_and_internal_code_to_suppliers_table', 13),
(23, '2025_12_11_213142_add_ownership_type_to_bins_table', 14),
(24, '2025_12_11_213451_update_ownership_type_to_include_field', 15),
(25, '2025_12_11_214318_add_reception_fields_to_processed_bins_table', 16),
(26, '2025_12_11_214919_add_weight_fields_to_processed_bins_table', 17),
(27, '2025_12_11_222131_add_tarja_fields_to_processed_bins_table', 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage users', 'web', '2025-12-05 02:57:51', '2025-12-05 02:57:51'),
(2, 'view calibration', 'web', '2025-12-05 02:57:51', '2025-12-05 02:57:51'),
(3, 'manage calibration', 'web', '2025-12-05 02:57:51', '2025-12-05 02:57:51'),
(4, 'view reports', 'web', '2025-12-05 02:57:51', '2025-12-05 02:57:51'),
(5, 'manage system', 'web', '2025-12-05 02:57:51', '2025-12-05 02:57:51'),
(6, 'manage processed bins', 'web', '2025-12-11 15:42:07', '2025-12-11 15:42:07'),
(7, 'scan qr codes', 'web', '2025-12-11 15:42:07', '2025-12-11 15:42:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `processed_bins`
--

CREATE TABLE `processed_bins` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED DEFAULT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `entry_date` date NOT NULL,
  `vehicle_plate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processing_date` date DEFAULT NULL,
  `exit_date` date DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guide_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_bin_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bin_type` enum('wood','plastic') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trash_level` enum('alto','mediano','bajo','limpio') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reception_total_weight` decimal(10,2) DEFAULT NULL,
  `reception_weight_per_truck` decimal(10,2) DEFAULT NULL,
  `reception_bins_count` int DEFAULT NULL,
  `reception_batch_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tarja_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lote` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unidades_per_pound_avg` decimal(8,2) DEFAULT NULL,
  `humidity` decimal(5,2) DEFAULT NULL,
  `current_bin_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_weight` decimal(8,2) DEFAULT NULL,
  `gross_weight` decimal(10,2) DEFAULT NULL,
  `bins_in_group` int NOT NULL DEFAULT '1',
  `wood_bins_count` int NOT NULL DEFAULT '0',
  `plastic_bins_count` int NOT NULL DEFAULT '0',
  `net_fruit_weight` decimal(10,2) DEFAULT NULL,
  `processed_weight` decimal(8,2) DEFAULT NULL,
  `original_calibre` enum('80-90','120-x','90-100','70-90','Grande 50-60','Mediana 40-50','Pequeña 30-40') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed_calibre` enum('80-90','120-x','90-100','70-90','Grande 50-60','Mediana 40-50','Pequeña 30-40') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code` text COLLATE utf8mb4_unicode_ci,
  `qr_generated_at` timestamp NULL DEFAULT NULL,
  `qr_updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('received','processed','shipped','delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `received_at` timestamp NOT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `processing_history` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `processed_bins`
--

INSERT INTO `processed_bins` (`id`, `purchase_id`, `supplier_id`, `entry_date`, `vehicle_plate`, `processing_date`, `exit_date`, `destination`, `guide_number`, `original_bin_number`, `bin_type`, `trash_level`, `reception_total_weight`, `reception_weight_per_truck`, `reception_bins_count`, `reception_batch_id`, `tarja_number`, `lote`, `unidades_per_pound_avg`, `humidity`, `current_bin_number`, `original_weight`, `gross_weight`, `bins_in_group`, `wood_bins_count`, `plastic_bins_count`, `net_fruit_weight`, `processed_weight`, `original_calibre`, `processed_calibre`, `qr_code`, `qr_generated_at`, `qr_updated_at`, `status`, `received_at`, `processed_at`, `processing_history`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:17:32', NULL, NULL, NULL, '2025-12-11 16:17:32', '2025-12-11 16:17:32'),
(2, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:17:53', NULL, NULL, NULL, '2025-12-11 16:17:53', '2025-12-11 16:17:53'),
(3, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:18:59', NULL, NULL, NULL, '2025-12-11 16:18:59', '2025-12-11 16:18:59'),
(4, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:19:41', NULL, NULL, NULL, '2025-12-11 16:19:41', '2025-12-11 16:19:41'),
(5, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:20:18', NULL, NULL, NULL, '2025-12-11 16:20:18', '2025-12-11 16:20:18'),
(6, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:20:23', NULL, NULL, NULL, '2025-12-11 16:20:23', '2025-12-11 16:20:23'),
(7, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:23:41', NULL, NULL, NULL, '2025-12-11 16:23:41', '2025-12-11 16:23:41'),
(8, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:44:16', NULL, NULL, NULL, '2025-12-11 16:44:16', '2025-12-11 16:44:16'),
(9, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 3994.00, 25000.00, 2, 'REC-20251211221256-1', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 3994.00, 4000.00, 2, 0, 2, 3994.00, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-12 01:12:56', NULL, NULL, NULL, '2025-12-12 01:12:56', '2025-12-12 01:12:56'),
(10, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 3994.00, 25000.00, 2, 'REC-20251211221307-1', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 3994.00, 4000.00, 2, 0, 2, 3994.00, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-12 01:13:07', NULL, NULL, NULL, '2025-12-12 01:13:07', '2025-12-12 01:13:07'),
(11, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 4994.00, 20000.00, 2, 'REC-20251211222935-1', 'TARJA-20251211-00011', '456879', 8.00, 10.00, 'PLASTIC-002, PLASTIC-003', 4910.00, 5000.00, 2, 0, 2, 4910.00, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-12 01:29:35', NULL, NULL, NULL, '2025-12-12 01:29:35', '2025-12-12 01:29:35'),
(12, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 4994.00, 20000.00, 2, 'REC-20251211223058-1', 'TARJA-20251211-00012', '456879', 8.00, 10.00, 'PLASTIC-002, PLASTIC-003', 4910.00, 5000.00, 2, 0, 2, 4910.00, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-12 01:30:58', NULL, NULL, NULL, '2025-12-12 01:30:58', '2025-12-12 01:30:58'),
(13, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 4994.00, 20000.00, 2, 'REC-20251211223215-1', 'TARJA-20251211-00013', '456879', 8.00, 10.00, 'PLASTIC-002, PLASTIC-003', 4910.00, 5000.00, 2, 0, 2, 4910.00, NULL, '80-90', NULL, 'qrcodes/tarja_13_1765492335.svg', '2025-12-12 01:32:15', '2025-12-12 01:32:15', 'received', '2025-12-12 01:32:15', NULL, NULL, NULL, '2025-12-12 01:32:15', '2025-12-12 01:32:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producers`
--

CREATE TABLE `producers` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `bin_ids` json DEFAULT NULL,
  `purchase_order` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bin_id` bigint UNSIGNED DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `weight_purchased` decimal(8,2) NOT NULL,
  `calibre` enum('80-90','120-x','90-100','70-90','Grande 50-60','Mediana 40-50','Pequeña 30-40') COLLATE utf8mb4_unicode_ci NOT NULL,
  `units_per_pound` int NOT NULL,
  `unit_price` decimal(8,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_due_date` date DEFAULT NULL,
  `amount_owed` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','partial','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `purchases`
--

INSERT INTO `purchases` (`id`, `supplier_id`, `bin_ids`, `purchase_order`, `bin_id`, `purchase_date`, `weight_purchased`, `calibre`, `units_per_pound`, `unit_price`, `total_amount`, `amount_paid`, `payment_due_date`, `amount_owed`, `payment_status`, `payment_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '[\"12\", \"13\", \"5\", \"6\"]', '34567890', NULL, '2025-12-05', 2000.00, '90-100', 20, 120.00, 240000.00, 10000.00, '2025-12-31', 230000.00, 'partial', NULL, NULL, '2025-12-06 01:39:09', '2025-12-06 01:39:09'),
(2, 1, NULL, '34567890', NULL, '2025-12-05', 2000.00, '90-100', 20, 120.00, 240000.00, 10000.00, '2025-12-31', 230000.00, 'partial', NULL, NULL, '2025-12-06 02:11:33', '2025-12-06 02:11:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_bins`
--

CREATE TABLE `purchase_bins` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `bin_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `purchase_bins`
--

INSERT INTO `purchase_bins` (`id`, `purchase_id`, `bin_id`, `created_at`, `updated_at`) VALUES
(1, 2, 5, NULL, NULL),
(2, 2, 6, NULL, NULL),
(3, 2, 12, NULL, NULL),
(4, 2, 13, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2025-12-05 02:45:21', '2025-12-05 02:45:21'),
(2, 'Admin', 'web', '2025-12-05 02:45:21', '2025-12-05 02:45:21'),
(3, 'Supervisor', 'web', '2025-12-05 02:45:21', '2025-12-05 02:45:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(6, 2),
(7, 2),
(2, 3),
(4, 3),
(7, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `csg_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `internal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_debt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `business_name`, `csg_code`, `internal_code`, `location`, `phone`, `business_type`, `total_debt`, `total_paid`, `created_at`, `updated_at`) VALUES
(1, 'r3q', 'r3q.spa', '456', '0316', 'merced 836', '983747856', 'informatica', 460000.00, 20000.00, '2025-12-05 20:42:08', '2025-12-12 00:38:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google2fa_secret` text COLLATE utf8mb4_unicode_ci,
  `google2fa_enable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `google2fa_secret`, `google2fa_enable`) VALUES
(1, 'Admin Cofrupa', 'admin@cofrupa.com', '2025-12-05 02:46:13', '$2y$10$RFFSLtKL37ryhOtPwYxKbuwOOeC3GfBqkwLtSqR2j9FrQNpo5BsUW', NULL, '2025-12-05 02:46:13', '2025-12-05 02:46:13', NULL, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bins`
--
ALTER TABLE `bins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bins_bin_number_unique` (`bin_number`),
  ADD KEY `bins_supplier_id_foreign` (`supplier_id`);

--
-- Indices de la tabla `bin_assignments`
--
ALTER TABLE `bin_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bin_assignments_supplier_id_foreign` (`supplier_id`),
  ADD KEY `bin_assignments_bin_id_supplier_id_index` (`bin_id`,`supplier_id`),
  ADD KEY `bin_assignments_delivery_date_index` (`delivery_date`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_logs_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `processed_bins`
--
ALTER TABLE `processed_bins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `processed_bins_tarja_number_unique` (`tarja_number`),
  ADD KEY `processed_bins_purchase_id_foreign` (`purchase_id`),
  ADD KEY `processed_bins_supplier_id_status_index` (`supplier_id`,`status`),
  ADD KEY `processed_bins_current_bin_number_index` (`current_bin_number`),
  ADD KEY `processed_bins_status_index` (`status`);

--
-- Indices de la tabla `producers`
--
ALTER TABLE `producers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchases_bin_id_foreign` (`bin_id`);

--
-- Indices de la tabla `purchase_bins`
--
ALTER TABLE `purchase_bins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_bins_purchase_id_bin_id_unique` (`purchase_id`,`bin_id`),
  ADD KEY `purchase_bins_bin_id_foreign` (`bin_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_internal_code_unique` (`internal_code`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bins`
--
ALTER TABLE `bins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `bin_assignments`
--
ALTER TABLE `bin_assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `processed_bins`
--
ALTER TABLE `processed_bins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `producers`
--
ALTER TABLE `producers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `purchase_bins`
--
ALTER TABLE `purchase_bins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bins`
--
ALTER TABLE `bins`
  ADD CONSTRAINT `bins_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `bin_assignments`
--
ALTER TABLE `bin_assignments`
  ADD CONSTRAINT `bin_assignments_bin_id_foreign` FOREIGN KEY (`bin_id`) REFERENCES `bins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bin_assignments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `processed_bins`
--
ALTER TABLE `processed_bins`
  ADD CONSTRAINT `processed_bins_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `processed_bins_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_bin_id_foreign` FOREIGN KEY (`bin_id`) REFERENCES `bins` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `purchase_bins`
--
ALTER TABLE `purchase_bins`
  ADD CONSTRAINT `purchase_bins_bin_id_foreign` FOREIGN KEY (`bin_id`) REFERENCES `bins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_bins_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
