-- Fix para las tablas finance_purchases y finance_bank_debts
-- Ejecutar en la base de datos 'cofrupa' si las tablas tienen problemas

-- 1. Fix finance_purchases: asegurar que id sea AUTO_INCREMENT y PRIMARY KEY
ALTER TABLE `finance_purchases` 
MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- 2. Añadir columnas bank y exchange_rate si no existen
-- (Si ya existen, estas líneas darán error pero puedes comentarlas)
ALTER TABLE `finance_purchases` 
ADD COLUMN IF NOT EXISTS `bank` varchar(255) DEFAULT NULL COMMENT 'Banco' AFTER `notes`,
ADD COLUMN IF NOT EXISTS `exchange_rate` decimal(12,2) DEFAULT NULL COMMENT 'Tipo de cambio' AFTER `bank`;

-- 3. Fix finance_bank_debts: asegurar que id sea AUTO_INCREMENT
ALTER TABLE `finance_bank_debts` 
MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- 4. Verificar las estructuras (descomentar si quieres ver los resultados)
-- DESCRIBE finance_purchases;
-- DESCRIBE finance_bank_debts;
