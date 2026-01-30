-- Crear tabla finance_bank_debts (deuda/capital por banco)
-- Ejecutar en la base de datos 'cofrupa' si la migración no se ha corrido.

CREATE TABLE IF NOT EXISTS `finance_bank_debts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company` enum('cofrupa','luis_gonzalez','comercializadora') NOT NULL COMMENT 'Empresa',
  `bank` varchar(255) NOT NULL COMMENT 'Banco',
  `amount_usd` decimal(14,2) NOT NULL COMMENT 'Monto (US$)',
  `due_date` date DEFAULT NULL COMMENT 'Vencimiento',
  `type` enum('compra','venta','general') NOT NULL DEFAULT 'general' COMMENT 'Uso: compra, venta o general',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_bank_debts_company_bank_index` (`company`,`bank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
