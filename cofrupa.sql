-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 22-01-2026 a las 14:34:38
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
-- Estructura de tabla para la tabla `accounting_records`
--

CREATE TABLE `accounting_records` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `contract_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `closing_date` date DEFAULT NULL,
  `product_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_per_kg` decimal(10,2) DEFAULT NULL,
  `quantity_kg` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `currency` enum('USD','CLP') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CLP',
  `exchange_rate` decimal(10,4) DEFAULT NULL,
  `advance_payment` decimal(12,2) DEFAULT NULL,
  `remaining_amount` decimal(12,2) DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_due_date` date DEFAULT NULL,
  `actual_payment_date` date DEFAULT NULL,
  `payment_status` enum('pending','partial','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(5, 'WOOD-005', 'wood', 'internal', 60.00, 1000.00, NULL, 'available', NULL, NULL, '2025-12-11', NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-12 01:54:59'),
(6, 'WOOD-006', 'wood', 'internal', 60.00, 1000.00, NULL, 'available', NULL, NULL, '2025-12-11', NULL, 'Bin de madera para ciruelas', '2025-12-05 20:59:05', '2025-12-12 01:54:59'),
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
-- Estructura de tabla para la tabla `brokers`
--

CREATE TABLE `brokers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission_percentage` decimal(5,2) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `brokers`
--

INSERT INTO `brokers` (`id`, `name`, `commission_percentage`, `email`, `phone`, `address`, `notes`, `created_at`, `updated_at`, `tax_id`, `bank_name`, `bank_account_type`, `bank_account_number`) VALUES
(1, 'Andes Broker', 3.00, 'inv.riquelme@me.com', '983747856', 'rua du vivir 8943', 'Copacabana', '2025-12-29 13:46:05', '2025-12-29 13:46:05', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `broker_payments`
--

CREATE TABLE `broker_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `broker_id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint UNSIGNED DEFAULT NULL,
  `document_type` enum('original','release') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

CREATE TABLE `clients` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('constant','new') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clients`
--

INSERT INTO `clients` (`id`, `name`, `type`, `email`, `phone`, `address`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'xi lia', 'new', 'arv00316@hotmail.com', '+56963725358', 'la opera 970', 'test', '2025-12-29 13:50:17', '2025-12-29 13:50:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_date` date DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `broker_id` bigint UNSIGNED DEFAULT NULL,
  `stock_committed` decimal(15,2) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `broker_commission_percentage` decimal(5,2) DEFAULT NULL,
  `destination_bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_port` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consignee_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_variations` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','active','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Producto o tipo de tarifa (ej: EX50-60)',
  `booking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de Booking',
  `vessel_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del buque',
  `etd_date` date DEFAULT NULL COMMENT 'ETD - Fecha de salida estimada',
  `etd_week` int DEFAULT NULL COMMENT 'Semana ETD',
  `eta_date` date DEFAULT NULL COMMENT 'ETA - Fecha de llegada estimada',
  `eta_week` int DEFAULT NULL COMMENT 'Semana ETA',
  `container_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de contenedor o referencia',
  `transit_weeks` int DEFAULT NULL COMMENT 'Número de semanas de tránsito',
  `freight_amount` decimal(15,2) DEFAULT NULL COMMENT 'Peso, tarifa o monto',
  `payment_status` enum('pending','paid','partial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Estado del pago',
  `consignee_address` text COLLATE utf8mb4_unicode_ci,
  `consignee_chinese_address` text COLLATE utf8mb4_unicode_ci,
  `consignee_tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'TAX ID / USCI',
  `consignee_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_address` text COLLATE utf8mb4_unicode_ci,
  `notify_chinese_address` text COLLATE utf8mb4_unicode_ci,
  `notify_tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'TAX ID / USCI',
  `notify_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_1_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_1_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_2_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_2_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'COFRUPA Export SPA',
  `seller_address` text COLLATE utf8mb4_unicode_ci,
  `seller_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_description` text COLLATE utf8mb4_unicode_ci COMMENT 'Product: Natural Condition Chilean prunes size 120/140 & 140+',
  `quality_specification` text COLLATE utf8mb4_unicode_ci COMMENT 'Quality: As per attached spec / Chilean protocol',
  `crop_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Crop: 2025',
  `packing` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Packing: 25 kg bags',
  `label_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Label: To be provided by buyer',
  `incoterm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Incoterm: CFR Main Chinese port',
  `payment_terms` text COLLATE utf8mb4_unicode_ci COMMENT 'Payment: 20% advance payment 2 weeks before ETD, 80% balance...',
  `required_documents` text COLLATE utf8mb4_unicode_ci COMMENT 'Documents: invoice, 3/3 OBL, pack list, cert of origin., phytosanitary',
  `customer_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'PO# / Referencia del Cliente',
  `port_of_charge` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Puerto de Embarque (Port of Charge)',
  `maturity_date` date DEFAULT NULL COMMENT 'Fecha de Vencimiento del Contrato',
  `transportation_details` text COLLATE utf8mb4_unicode_ci COMMENT 'Detalles del transporte: tipo de contenedor, cantidad, etc.',
  `shipment_schedule` text COLLATE utf8mb4_unicode_ci COMMENT 'Cronograma de embarque: "1 FCL AUGUST 2025 AND 1 FCL SEPTEMBER 2025"',
  `seller_tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'RUT / Tax ID del Vendedor',
  `seller_bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del Banco del Vendedor',
  `seller_bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de Cuenta Corriente',
  `seller_bank_swift` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SWIFT Code del Banco',
  `seller_bank_address` text COLLATE utf8mb4_unicode_ci COMMENT 'Dirección del Banco',
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipo de Pago: OUR, SHA, BEN',
  `contract_clause` text COLLATE utf8mb4_unicode_ci COMMENT 'Cláusula de arbitraje y penalización',
  `total_amount` decimal(15,2) DEFAULT NULL COMMENT 'Monto Total del Contrato',
  `unit_price_per_kg` decimal(15,2) DEFAULT NULL COMMENT 'Precio Unitario por kg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contracts`
--

INSERT INTO `contracts` (`id`, `contract_number`, `contract_date`, `client_id`, `broker_id`, `stock_committed`, `price`, `broker_commission_percentage`, `destination_bank`, `destination_port`, `consignee_name`, `contract_variations`, `status`, `created_at`, `updated_at`, `product_type`, `booking_number`, `vessel_name`, `etd_date`, `etd_week`, `eta_date`, `eta_week`, `container_number`, `transit_weeks`, `freight_amount`, `payment_status`, `consignee_address`, `consignee_chinese_address`, `consignee_tax_id`, `consignee_phone`, `notify_name`, `notify_address`, `notify_chinese_address`, `notify_tax_id`, `notify_phone`, `contact_person_1_name`, `contact_person_1_phone`, `contact_person_2_name`, `contact_person_2_phone`, `contact_email`, `seller_name`, `seller_address`, `seller_phone`, `product_description`, `quality_specification`, `crop_year`, `packing`, `label_info`, `incoterm`, `payment_terms`, `required_documents`, `customer_reference`, `port_of_charge`, `maturity_date`, `transportation_details`, `shipment_schedule`, `seller_tax_id`, `seller_bank_name`, `seller_bank_account_number`, `seller_bank_swift`, `seller_bank_address`, `payment_type`, `contract_clause`, `total_amount`, `unit_price_per_kg`) VALUES
(1, '313-240', NULL, 1, 1, 30000.00, 6.00, 3.00, 'Itau', 'xi loan', NULL, '\"test de contrato\"', 'draft', '2025-12-29 13:53:40', '2025-12-29 14:45:03', 'calibre 70-80', 'sngh08988\'', 'polar express', '2025-12-30', 4, '2025-12-17', 2, '88989hjdj', 3, 200000.00, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'COFRUPA Export SPA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '313240', '2025-12-29', 1, 1, 40000.00, 7.00, 3.00, 'itau', 'xun huấn', 'Dany', '\"ldlcdl\"', 'active', '2025-12-29 21:07:15', '2025-12-29 21:07:15', '80*90', '4565405d654', 'callao express', '2025-12-29', 52, '2025-12-31', 1, 'cmua456046', 3, 200000.00, 'pending', 'diagonal travesia', 'xhun huan', '22', '+86456456456', 'jj perez 8943', 'la opera', 'la operiña', '45', '+86456456456', 'Nany', '+86456456456', 'Dany', '+86456456456', 'inv.riquelme@me.com', 'COFRUPA Export SPA', 'Cam Lo Mackenna PC 7-A, Buin', '+56992395293', 'prunes natural', '8070', '2025', '25 kh', '000316', 'main chinise port', '20% to rest out delivery', 'gmo', '24534564', 'valparaiso 2', '2025-12-31', '1 contenedor', '31/12/2025', '76.505.934-8', 'BANCO SANTANDER', '5100166293', 'BSCHCLRM', 'Bandera 140, Santiago, Chile', 'OUR', 'kndkldkkld', 4000000.00, 6.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contract_documents`
--

CREATE TABLE `contract_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint UNSIGNED NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'transport, naviera, contrato, calidad, sac, envio, instructivo_embarque, instructivo_carga, post_despacho',
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre original del archivo',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta del archivo en storage',
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipo MIME del archivo',
  `file_size` bigint DEFAULT NULL COMMENT 'Tamaño en bytes',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notas adicionales',
  `uploaded_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contract_documents`
--

INSERT INTO `contract_documents` (`id`, `contract_id`, `document_type`, `document_name`, `file_path`, `file_type`, `file_size`, `notes`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'calidad', 'Quality_Certificate_313-240.pdf', 'contracts/313-240/Quality_Certificate_313-240.pdf', 'application/pdf', 20951, NULL, 1, '2025-12-29 21:15:16', '2025-12-29 21:26:45'),
(2, 1, 'phytosanitary', 'Phytosanitary_Certificate_313-240.pdf', 'contracts/313-240/Phytosanitary_Certificate_313-240.pdf', 'application/pdf', 5325, NULL, 1, '2025-12-29 21:16:04', '2025-12-29 21:16:04'),
(3, 1, 'bill_of_lading', 'Bill_of_Lading_313-240.pdf', 'contracts/313-240/Bill_of_Lading_313-240.pdf', 'application/pdf', 4585, NULL, 1, '2025-12-29 21:16:33', '2025-12-29 21:16:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contract_modifications`
--

CREATE TABLE `contract_modifications` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `field_changed` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contract_modifications`
--

INSERT INTO `contract_modifications` (`id`, `contract_id`, `user_id`, `field_changed`, `old_value`, `new_value`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'created', NULL, 'Nuevo contrato', 'Contrato creado', '2025-12-29 13:53:40', '2025-12-29 13:53:40'),
(2, 1, 1, 'Precio', '12.00', '6', 'Campo \'Precio\' modificado', '2025-12-29 14:45:03', '2025-12-29 14:45:03'),
(3, 1, 1, 'Puerto de Destino', 'xi lia', 'xi loan', 'Campo \'Puerto de Destino\' modificado', '2025-12-29 14:45:03', '2025-12-29 14:45:03'),
(4, 1, 1, 'Variaciones del Contrato', 'test revision', 'test de contrato', 'Campo \'Variaciones del Contrato\' modificado', '2025-12-29 14:45:03', '2025-12-29 14:45:03'),
(5, 2, 1, 'created', NULL, 'Nuevo contrato', 'Contrato creado', '2025-12-29 21:07:15', '2025-12-29 21:07:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `broker_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `stage` enum('client_contact','stock_offer','negotiation') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client_contact',
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attachments` json DEFAULT NULL COMMENT 'Array de rutas de archivos adjuntos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents`
--

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` enum('export_guide_plant','export_guide_transport','customs_loading','dvl_matrix','master_document') COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient` enum('plant','customs','transport','embarkation') COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','generated','sent','confirmed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `generated_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `documents`
--

INSERT INTO `documents` (`id`, `shipment_id`, `document_number`, `document_type`, `recipient`, `recipient_company`, `content`, `file_path`, `status`, `generated_at`, `sent_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'DOC-47JXNOYX', 'export_guide_transport', 'customs', 'SPS', NULL, NULL, 'sent', '2025-12-29 16:29:19', '2025-12-29 16:32:06', 'test', '2025-12-29 19:29:14', '2025-12-29 19:32:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exportations`
--

CREATE TABLE `exportations` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint UNSIGNED NOT NULL,
  `export_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('preparation','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preparation',
  `export_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exportation_documents`
--

CREATE TABLE `exportation_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `exportation_id` bigint UNSIGNED NOT NULL,
  `document_type` enum('v1','commercial_invoice','origin_certificate','phytosanitary','quality_certificate','packing_list','eur1','contract_specific') COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `status` enum('pending','uploaded','validated','approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `uploaded_at` datetime DEFAULT NULL,
  `validated_at` datetime DEFAULT NULL,
  `validation_notes` text COLLATE utf8mb4_unicode_ci,
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
(7, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', '2025-12-12 00:23:02', NULL, 1, NULL, '2025-12-12 00:23:02', '2025-12-12 00:23:02'),
(8, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-26 14:07:23', NULL, 1, NULL, '2025-12-26 14:07:23', '2025-12-26 14:07:23'),
(9, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-29 13:33:30', NULL, 1, NULL, '2025-12-29 13:33:30', '2025-12-29 13:33:30'),
(10, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-29 18:31:33', NULL, 1, NULL, '2025-12-29 18:31:33', '2025-12-29 18:31:33'),
(11, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-31 04:21:19', NULL, 1, NULL, '2025-12-31 04:21:19', '2025-12-31 04:21:19'),
(12, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2025-12-31 04:23:57', NULL, 1, NULL, '2025-12-31 04:23:57', '2025-12-31 04:23:57'),
(13, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-14 16:11:42', NULL, 1, NULL, '2026-01-14 16:11:42', '2026-01-14 16:11:42'),
(14, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-16 19:25:56', NULL, 1, NULL, '2026-01-16 19:25:56', '2026-01-16 19:25:56'),
(15, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-16 19:26:17', NULL, 1, NULL, '2026-01-16 19:26:17', '2026-01-16 19:26:17'),
(16, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-22 15:14:48', NULL, 1, NULL, '2026-01-22 15:14:48', '2026-01-22 15:14:48'),
(17, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-22 15:15:13', NULL, 1, NULL, '2026-01-22 15:15:13', '2026-01-22 15:15:13'),
(18, 1, 'admin@cofrupa.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', '2026-01-22 15:15:23', NULL, 1, NULL, '2026-01-22 15:15:23', '2026-01-22 15:15:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logistics_companies`
--

CREATE TABLE `logistics_companies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `logistics_companies`
--

INSERT INTO `logistics_companies` (`id`, `name`, `code`, `contact_name`, `contact_email`, `contact_phone`, `address`, `notes`, `is_active`, `created_at`, `updated_at`, `tax_id`, `bank_name`, `bank_account_type`, `bank_account_number`) VALUES
(1, 'Transportes pullwen', '77706', 'Alvarors', 'ventas@r3q.cl', '+56983747856', 'Diagonal Travesía 588', NULL, 1, '2025-12-29 18:36:21', '2025-12-29 18:36:21', NULL, NULL, NULL, NULL);

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
(27, '2025_12_11_222131_add_tarja_fields_to_processed_bins_table', 18),
(28, '2025_12_11_225245_add_damage_percentage_to_processed_bins_table', 19),
(29, '2025_12_26_115713_create_clients_table', 20),
(30, '2025_12_26_115725_create_brokers_table', 20),
(32, '2025_12_26_115730_create_conversations_table', 21),
(33, '2025_12_26_115735_create_broker_payments_table', 22),
(34, '2025_12_26_121414_create_contracts_table', 22),
(35, '2025_12_26_121710_create_contract_modifications_table', 22),
(36, '2025_12_26_121826_add_contract_foreign_key_to_broker_payments_table', 22),
(37, '2025_12_26_122355_create_shipments_table', 23),
(38, '2025_12_26_122358_create_shipment_stages_table', 23),
(39, '2025_12_26_122359_create_documents_table', 23),
(40, '2025_12_26_122401_create_exportations_table', 23),
(41, '2025_12_26_122402_create_exportation_documents_table', 23),
(42, '2025_12_26_122527_create_shipping_lines_table', 23),
(43, '2025_12_26_122900_add_shipping_line_foreign_key_to_shipments_table', 23),
(44, '2025_12_26_122358_create_shipment_stages_table', 23),
(45, '2025_12_29_112550_add_shipping_fields_to_contracts_table', 24),
(46, '2025_12_29_115129_create_logistics_companies_table', 25),
(47, '2025_12_29_115326_add_logistics_company_to_shipments_table', 26),
(48, '2025_12_29_120329_add_contract_details_fields_to_contracts_table', 27),
(49, '2025_12_29_154033_add_transport_email_to_shipments_table', 28),
(50, '2025_12_29_164715_add_missing_contract_fields_to_contracts_table', 29),
(51, '2025_12_29_170237_add_attachments_to_conversations_table', 30),
(52, '2025_12_29_170238_create_contract_documents_table', 30),
(53, '2025_12_29_210924_create_plants_table', 31),
(54, '2025_12_29_211147_create_process_orders_table', 31),
(55, '2025_12_29_211158_create_process_invoices_table', 31),
(56, '2025_12_29_211205_create_accounting_records_table', 31),
(57, '2025_12_29_212717_add_billing_fields_to_suppliers_table', 32),
(58, '2025_12_29_212721_add_billing_fields_to_shipping_lines_table', 32),
(59, '2025_12_29_212723_add_billing_fields_to_logistics_companies_table', 32),
(60, '2025_12_29_213537_add_billing_fields_to_plants_table', 33),
(61, '2025_12_29_214507_add_billing_fields_to_brokers_table', 34),
(62, '2025_12_29_215726_create_plant_production_orders_table', 35),
(63, '2025_12_29_220747_add_contract_id_to_process_orders_table', 36),
(64, '2025_12_29_222401_create_order_tarjas_table', 37),
(65, '2025_12_29_222403_add_stock_fields_to_processed_bins_table', 37),
(66, '2025_12_29_222405_add_process_order_id_to_plant_production_orders_table', 37),
(67, '2025_12_29_224834_add_discard_fields_to_plant_production_orders_table', 38),
(68, '2026_01_22_124055_add_supplier_bins_fields_to_purchases_table', 39),
(69, '2026_01_22_125229_add_currency_to_purchases_table', 40),
(70, '2026_01_22_125619_add_purchase_type_to_purchases_table', 41),
(71, '2026_01_22_130350_add_buyer_to_purchases_table', 42);

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
-- Estructura de tabla para la tabla `order_tarjas`
--

CREATE TABLE `order_tarjas` (
  `id` bigint UNSIGNED NOT NULL,
  `process_order_id` bigint UNSIGNED NOT NULL,
  `processed_bin_id` bigint UNSIGNED NOT NULL,
  `quantity_kg` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Estructura de tabla para la tabla `plants`
--

CREATE TABLE `plants` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plant_production_orders`
--

CREATE TABLE `plant_production_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint UNSIGNED DEFAULT NULL,
  `process_order_id` bigint UNSIGNED DEFAULT NULL,
  `plant_id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `output_caliber` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_quantity_kg` decimal(10,2) NOT NULL,
  `booking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vessel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `entry_time` time DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `completion_time` time DEFAULT NULL,
  `production_program` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sorbate_solution` decimal(5,2) DEFAULT NULL,
  `delay_hours` decimal(5,2) DEFAULT NULL,
  `delay_reason` text COLLATE utf8mb4_unicode_ci,
  `produced_kilos` decimal(10,2) DEFAULT NULL,
  `discard_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discard_reason` text COLLATE utf8mb4_unicode_ci,
  `discard_status` enum('pending','recovered','disposed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `discard_recovery_date` date DEFAULT NULL,
  `discard_notes` text COLLATE utf8mb4_unicode_ci,
  `output_quantity_kg` decimal(10,2) DEFAULT NULL,
  `nominal_kg_per_hour` decimal(8,2) DEFAULT NULL,
  `estimated_hours` decimal(5,2) DEFAULT NULL,
  `actual_hours` decimal(5,2) DEFAULT NULL,
  `day_of_week` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','in_progress','completed','delayed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `has_delay` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
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
  `damage_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Porcentaje de daño de la fruta',
  `current_bin_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_weight` decimal(8,2) DEFAULT NULL,
  `gross_weight` decimal(10,2) DEFAULT NULL,
  `bins_in_group` int NOT NULL DEFAULT '1',
  `wood_bins_count` int NOT NULL DEFAULT '0',
  `plastic_bins_count` int NOT NULL DEFAULT '0',
  `net_fruit_weight` decimal(10,2) DEFAULT NULL,
  `stock_status` enum('available','assigned','in_process','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `available_kg` decimal(10,2) DEFAULT NULL,
  `assigned_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `used_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

INSERT INTO `processed_bins` (`id`, `purchase_id`, `supplier_id`, `entry_date`, `vehicle_plate`, `processing_date`, `exit_date`, `destination`, `guide_number`, `original_bin_number`, `bin_type`, `trash_level`, `reception_total_weight`, `reception_weight_per_truck`, `reception_bins_count`, `reception_batch_id`, `tarja_number`, `lote`, `unidades_per_pound_avg`, `humidity`, `damage_percentage`, `current_bin_number`, `original_weight`, `gross_weight`, `bins_in_group`, `wood_bins_count`, `plastic_bins_count`, `net_fruit_weight`, `stock_status`, `available_kg`, `assigned_kg`, `used_kg`, `location`, `processed_weight`, `original_calibre`, `processed_calibre`, `qr_code`, `qr_generated_at`, `qr_updated_at`, `status`, `received_at`, `processed_at`, `processing_history`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'processed', '2025-12-11 16:17:32', NULL, '[{\"date\": \"2026-01-14 13:24:26\", \"action\": \"used_in_mixing\", \"target_bin\": \"0830\", \"weight_used\": \"1000.00\"}]', NULL, '2025-12-11 16:17:32', '2026-01-14 16:24:26'),
(2, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:17:53', NULL, NULL, NULL, '2025-12-11 16:17:53', '2025-12-11 16:17:53'),
(3, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:18:59', NULL, NULL, NULL, '2025-12-11 16:18:59', '2025-12-11 16:18:59'),
(4, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:19:41', NULL, NULL, NULL, '2025-12-11 16:19:41', '2025-12-11 16:19:41'),
(5, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:20:18', NULL, NULL, NULL, '2025-12-11 16:20:18', '2025-12-11 16:20:18'),
(6, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:20:23', NULL, NULL, NULL, '2025-12-11 16:20:23', '2025-12-11 16:20:23'),
(7, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:23:41', NULL, NULL, NULL, '2025-12-11 16:23:41', '2025-12-11 16:23:41'),
(8, NULL, 1, '2025-12-11', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-11 16:44:16', NULL, NULL, NULL, '2025-12-11 16:44:16', '2025-12-11 16:44:16'),
(9, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 3994.00, 25000.00, 2, 'REC-20251211221256-1', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 3994.00, 4000.00, 2, 0, 2, 3994.00, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-12 01:12:56', NULL, NULL, NULL, '2025-12-12 01:12:56', '2025-12-12 01:12:56'),
(10, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 3994.00, 25000.00, 2, 'REC-20251211221307-1', NULL, NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 3994.00, 4000.00, 2, 0, 2, 3994.00, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-12 01:13:07', NULL, NULL, NULL, '2025-12-12 01:13:07', '2025-12-12 01:13:07'),
(11, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 4994.00, 20000.00, 2, 'REC-20251211222935-1', 'TARJA-20251211-00011', '456879', 8.00, 10.00, NULL, 'PLASTIC-002, PLASTIC-003', 4910.00, 5000.00, 2, 0, 2, 4910.00, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-12 01:29:35', NULL, NULL, NULL, '2025-12-12 01:29:35', '2025-12-12 01:29:35'),
(12, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 4994.00, 20000.00, 2, 'REC-20251211223058-1', 'TARJA-20251211-00012', '456879', 8.00, 10.00, NULL, 'PLASTIC-002, PLASTIC-003', 4910.00, 5000.00, 2, 0, 2, 4910.00, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, NULL, NULL, NULL, 'received', '2025-12-12 01:30:58', NULL, NULL, NULL, '2025-12-12 01:30:58', '2025-12-12 01:30:58'),
(13, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'PLASTIC-002, PLASTIC-003', 'plastic', 'limpio', 4994.00, 20000.00, 2, 'REC-20251211223215-1', 'TARJA-20251211-00013', '456879', 8.00, 10.00, NULL, 'PLASTIC-002, PLASTIC-003', 4910.00, 5000.00, 2, 0, 2, 4910.00, 'available', NULL, 0.00, 0.00, NULL, NULL, '80-90', NULL, 'qrcodes/tarja_13_1765492335.svg', '2025-12-12 01:32:15', '2025-12-12 01:32:15', 'received', '2025-12-12 01:32:15', NULL, NULL, NULL, '2025-12-12 01:32:15', '2025-12-12 01:32:15'),
(14, NULL, 1, '2025-12-11', 'FVFS24', NULL, NULL, NULL, NULL, 'WOOD-005, WOOD-006', 'wood', 'limpio', 4988.00, 20000.00, 2, 'REC-20251211225459-1', 'TARJA-20251211-00014', '456789', 6.00, 10.00, 15.00, 'WOOD-005, WOOD-006', 4880.00, 5000.00, 2, 2, 0, 4880.00, 'available', NULL, 0.00, 0.00, NULL, NULL, '90-100', NULL, 'qrcodes/tarja_14_1765493699.svg', '2025-12-12 01:54:59', '2025-12-12 01:54:59', 'received', '2025-12-12 01:54:59', NULL, NULL, NULL, '2025-12-12 01:54:59', '2025-12-12 01:54:59'),
(15, NULL, 1, '2026-01-14', NULL, '2026-01-14', NULL, NULL, NULL, '0830', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0830', 1000.00, NULL, 1, 0, 0, NULL, 'available', NULL, 0.00, 0.00, NULL, 1000.00, '70-90', '70-90', 'qrcodes/tarja_15_1768397066.svg', '2026-01-14 16:24:26', '2026-01-14 16:24:26', 'processed', '2026-01-14 16:24:26', '2026-01-14 16:24:26', '[{\"date\": \"2026-01-14 13:24:26\", \"action\": \"created_from_mixing\", \"calibre\": \"70-90\", \"source_bins\": [\"PLASTIC-002\"], \"total_weight\": 1000}]', 'test', '2026-01-14 16:24:26', '2026-01-14 16:24:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `process_invoices`
--

CREATE TABLE `process_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `process_order_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` enum('USD','CLP') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CLP',
  `exchange_rate` decimal(10,4) DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `payment_date` date DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `process_orders`
--

CREATE TABLE `process_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `plant_id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED DEFAULT NULL,
  `contract_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `csg_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `production_days` int DEFAULT NULL,
  `order_date` date NOT NULL,
  `expected_completion_date` date DEFAULT NULL,
  `actual_completion_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `progress_percentage` int NOT NULL DEFAULT '0',
  `product_description` text COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `alert_sent` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `supplier_bins_count` int DEFAULT NULL,
  `supplier_bins_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` enum('CLP','USD') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CLP',
  `purchase_type` enum('fruta','pure_fruta','descarte') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fruta',
  `buyer` enum('LG','Cofrupa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cofrupa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `purchases`
--

INSERT INTO `purchases` (`id`, `supplier_id`, `bin_ids`, `purchase_order`, `bin_id`, `purchase_date`, `weight_purchased`, `calibre`, `units_per_pound`, `unit_price`, `total_amount`, `amount_paid`, `payment_due_date`, `amount_owed`, `payment_status`, `payment_date`, `notes`, `supplier_bins_count`, `supplier_bins_photo`, `currency`, `purchase_type`, `buyer`, `created_at`, `updated_at`) VALUES
(1, 1, '[\"12\", \"13\", \"5\", \"6\"]', '34567890', NULL, '2025-12-05', 2000.00, '90-100', 20, 120.00, 240000.00, 10000.00, '2025-12-31', 230000.00, 'partial', NULL, NULL, NULL, NULL, 'CLP', 'fruta', 'Cofrupa', '2025-12-06 01:39:09', '2025-12-06 01:39:09'),
(2, 1, NULL, '34567890', NULL, '2025-12-05', 2000.00, '90-100', 20, 120.00, 240000.00, 10000.00, '2025-12-31', 230000.00, 'partial', NULL, NULL, NULL, NULL, 'CLP', 'fruta', 'Cofrupa', '2025-12-06 02:11:33', '2025-12-06 02:11:33');

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
-- Estructura de tabla para la tabla `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint UNSIGNED NOT NULL,
  `shipment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_line_id` bigint UNSIGNED DEFAULT NULL,
  `scheduled_date` date NOT NULL,
  `actual_date` date DEFAULT NULL,
  `status` enum('scheduled','in_transit','at_customs','loaded','shipped','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `plant_pickup_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customs_loading_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_company_id` bigint UNSIGNED DEFAULT NULL,
  `transport_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_request_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_notes` text COLLATE utf8mb4_unicode_ci,
  `plant_pickup_scheduled` datetime DEFAULT NULL,
  `plant_pickup_actual` datetime DEFAULT NULL,
  `customs_loading_scheduled` datetime DEFAULT NULL,
  `customs_loading_actual` datetime DEFAULT NULL,
  `transport_departure_scheduled` datetime DEFAULT NULL,
  `transport_departure_actual` datetime DEFAULT NULL,
  `port_arrival_scheduled` datetime DEFAULT NULL,
  `port_arrival_actual` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shipments`
--

INSERT INTO `shipments` (`id`, `contract_id`, `shipment_number`, `shipping_line_id`, `scheduled_date`, `actual_date`, `status`, `plant_pickup_company`, `customs_loading_company`, `transport_company_id`, `transport_company`, `transport_contact`, `transport_phone`, `transport_email`, `transport_request_number`, `transport_notes`, `plant_pickup_scheduled`, `plant_pickup_actual`, `customs_loading_scheduled`, `customs_loading_actual`, `transport_departure_scheduled`, `transport_departure_actual`, `port_arrival_scheduled`, `port_arrival_actual`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'DESP-000001', 1, '2025-12-29', NULL, 'scheduled', 'SPS', 'SPS', 1, NULL, 'Alvaro', '+56983747856', 'alvaro.riquelme@r3q.cl', '55646540', NULL, '2025-12-29 13:28:00', NULL, '2025-12-30 13:28:00', NULL, '2025-12-31 13:28:00', NULL, NULL, NULL, 'feliz navidad', '2025-12-29 19:28:51', '2025-12-29 19:28:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipment_stages`
--

CREATE TABLE `shipment_stages` (
  `id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `stage_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stage_type` enum('plant_pickup','customs_loading','transport','port_arrival','custom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_time` datetime NOT NULL,
  `actual_time` datetime DEFAULT NULL,
  `status` enum('pending','in_progress','completed','delayed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipping_lines`
--

CREATE TABLE `shipping_lines` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `shipping_lines`
--

INSERT INTO `shipping_lines` (`id`, `name`, `code`, `contact_name`, `contact_email`, `contact_phone`, `notes`, `is_active`, `created_at`, `updated_at`, `tax_id`, `bank_name`, `bank_account_type`, `bank_account_number`) VALUES
(1, 'sudamericana de vapores', '92211', '343-611', 'ventas@r3q.cl', '+56963725358', 'test', 1, '2025-12-29 15:01:39', '2025-12-29 15:01:39', NULL, NULL, NULL, NULL);

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `business_name`, `csg_code`, `internal_code`, `location`, `phone`, `business_type`, `total_debt`, `total_paid`, `created_at`, `updated_at`, `tax_id`, `bank_name`, `bank_account_type`, `bank_account_number`) VALUES
(1, 'r3q', 'r3q.spa', '456', '0316', 'merced 836', '983747856', 'informatica', 460000.00, 20000.00, '2025-12-05 20:42:08', '2025-12-12 00:38:53', NULL, NULL, NULL, NULL);

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
-- Indices de la tabla `accounting_records`
--
ALTER TABLE `accounting_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounting_records_supplier_id_foreign` (`supplier_id`),
  ADD KEY `accounting_records_contract_id_foreign` (`contract_id`);

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
-- Indices de la tabla `brokers`
--
ALTER TABLE `brokers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `broker_payments`
--
ALTER TABLE `broker_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `broker_payments_broker_id_foreign` (`broker_id`);

--
-- Indices de la tabla `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contracts_client_id_foreign` (`client_id`),
  ADD KEY `contracts_broker_id_foreign` (`broker_id`);

--
-- Indices de la tabla `contract_documents`
--
ALTER TABLE `contract_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_documents_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `contract_documents_contract_id_document_type_index` (`contract_id`,`document_type`);

--
-- Indices de la tabla `contract_modifications`
--
ALTER TABLE `contract_modifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_modifications_contract_id_foreign` (`contract_id`),
  ADD KEY `contract_modifications_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_client_id_foreign` (`client_id`),
  ADD KEY `conversations_broker_id_foreign` (`broker_id`),
  ADD KEY `conversations_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documents_document_number_unique` (`document_number`),
  ADD KEY `documents_shipment_id_foreign` (`shipment_id`);

--
-- Indices de la tabla `exportations`
--
ALTER TABLE `exportations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exportations_export_number_unique` (`export_number`),
  ADD KEY `exportations_shipment_id_foreign` (`shipment_id`),
  ADD KEY `exportations_contract_id_foreign` (`contract_id`);

--
-- Indices de la tabla `exportation_documents`
--
ALTER TABLE `exportation_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exportation_documents_exportation_id_foreign` (`exportation_id`);

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
-- Indices de la tabla `logistics_companies`
--
ALTER TABLE `logistics_companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `logistics_companies_code_unique` (`code`);

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
-- Indices de la tabla `order_tarjas`
--
ALTER TABLE `order_tarjas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_tarjas_process_order_id_processed_bin_id_unique` (`process_order_id`,`processed_bin_id`),
  ADD KEY `order_tarjas_processed_bin_id_foreign` (`processed_bin_id`);

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
-- Indices de la tabla `plants`
--
ALTER TABLE `plants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plants_code_unique` (`code`);

--
-- Indices de la tabla `plant_production_orders`
--
ALTER TABLE `plant_production_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plant_production_orders_order_number_unique` (`order_number`),
  ADD KEY `plant_production_orders_contract_id_foreign` (`contract_id`),
  ADD KEY `plant_production_orders_plant_id_foreign` (`plant_id`),
  ADD KEY `plant_production_orders_process_order_id_foreign` (`process_order_id`);

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
-- Indices de la tabla `process_invoices`
--
ALTER TABLE `process_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `process_invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `process_invoices_process_order_id_foreign` (`process_order_id`);

--
-- Indices de la tabla `process_orders`
--
ALTER TABLE `process_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `process_orders_order_number_unique` (`order_number`),
  ADD KEY `process_orders_plant_id_foreign` (`plant_id`),
  ADD KEY `process_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `process_orders_contract_id_foreign` (`contract_id`);

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
-- Indices de la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipments_contract_id_foreign` (`contract_id`),
  ADD KEY `shipments_shipping_line_id_foreign` (`shipping_line_id`),
  ADD KEY `shipments_transport_company_id_foreign` (`transport_company_id`);

--
-- Indices de la tabla `shipment_stages`
--
ALTER TABLE `shipment_stages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipment_stages_shipment_id_foreign` (`shipment_id`);

--
-- Indices de la tabla `shipping_lines`
--
ALTER TABLE `shipping_lines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipping_lines_code_unique` (`code`);

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
-- AUTO_INCREMENT de la tabla `accounting_records`
--
ALTER TABLE `accounting_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `brokers`
--
ALTER TABLE `brokers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `broker_payments`
--
ALTER TABLE `broker_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `contract_documents`
--
ALTER TABLE `contract_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `contract_modifications`
--
ALTER TABLE `contract_modifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `exportations`
--
ALTER TABLE `exportations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `exportation_documents`
--
ALTER TABLE `exportation_documents`
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `logistics_companies`
--
ALTER TABLE `logistics_companies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `order_tarjas`
--
ALTER TABLE `order_tarjas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `plants`
--
ALTER TABLE `plants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plant_production_orders`
--
ALTER TABLE `plant_production_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `processed_bins`
--
ALTER TABLE `processed_bins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `process_invoices`
--
ALTER TABLE `process_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `process_orders`
--
ALTER TABLE `process_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `shipment_stages`
--
ALTER TABLE `shipment_stages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `shipping_lines`
--
ALTER TABLE `shipping_lines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Filtros para la tabla `accounting_records`
--
ALTER TABLE `accounting_records`
  ADD CONSTRAINT `accounting_records_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_records_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

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
-- Filtros para la tabla `broker_payments`
--
ALTER TABLE `broker_payments`
  ADD CONSTRAINT `broker_payments_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `brokers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `brokers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contracts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contract_documents`
--
ALTER TABLE `contract_documents`
  ADD CONSTRAINT `contract_documents_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contract_documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `contract_modifications`
--
ALTER TABLE `contract_modifications`
  ADD CONSTRAINT `contract_modifications_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contract_modifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_broker_id_foreign` FOREIGN KEY (`broker_id`) REFERENCES `brokers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `exportations`
--
ALTER TABLE `exportations`
  ADD CONSTRAINT `exportations_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exportations_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `exportation_documents`
--
ALTER TABLE `exportation_documents`
  ADD CONSTRAINT `exportation_documents_exportation_id_foreign` FOREIGN KEY (`exportation_id`) REFERENCES `exportations` (`id`) ON DELETE CASCADE;

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
-- Filtros para la tabla `order_tarjas`
--
ALTER TABLE `order_tarjas`
  ADD CONSTRAINT `order_tarjas_process_order_id_foreign` FOREIGN KEY (`process_order_id`) REFERENCES `process_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_tarjas_processed_bin_id_foreign` FOREIGN KEY (`processed_bin_id`) REFERENCES `processed_bins` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `plant_production_orders`
--
ALTER TABLE `plant_production_orders`
  ADD CONSTRAINT `plant_production_orders_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `plant_production_orders_plant_id_foreign` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plant_production_orders_process_order_id_foreign` FOREIGN KEY (`process_order_id`) REFERENCES `process_orders` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `processed_bins`
--
ALTER TABLE `processed_bins`
  ADD CONSTRAINT `processed_bins_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `processed_bins_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `process_invoices`
--
ALTER TABLE `process_invoices`
  ADD CONSTRAINT `process_invoices_process_order_id_foreign` FOREIGN KEY (`process_order_id`) REFERENCES `process_orders` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `process_orders`
--
ALTER TABLE `process_orders`
  ADD CONSTRAINT `process_orders_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `process_orders_plant_id_foreign` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

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

--
-- Filtros para la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_shipping_line_id_foreign` FOREIGN KEY (`shipping_line_id`) REFERENCES `shipping_lines` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `shipments_transport_company_id_foreign` FOREIGN KEY (`transport_company_id`) REFERENCES `logistics_companies` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `shipment_stages`
--
ALTER TABLE `shipment_stages`
  ADD CONSTRAINT `shipment_stages_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
