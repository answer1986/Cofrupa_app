-- Añadir columnas de detalle a process_orders (si no existen).
-- Ejecutar en la base de datos cofrupa si aparece: Unknown column 'raw_material' in 'field list'
-- Si alguna columna ya existe, MySQL dará "Duplicate column"; puedes comentar esa línea.

-- 1) Detalle de orden (raw_material, product, type, caliber, etc.)
ALTER TABLE `process_orders`
    ADD COLUMN `raw_material` VARCHAR(255) NULL COMMENT 'Materia Prima' AFTER `product_description`,
    ADD COLUMN `product` VARCHAR(255) NULL AFTER `raw_material`,
    ADD COLUMN `type` VARCHAR(255) NULL AFTER `product`,
    ADD COLUMN `caliber` VARCHAR(255) NULL AFTER `type`,
    ADD COLUMN `quality` VARCHAR(255) NULL AFTER `caliber`,
    ADD COLUMN `labeling` VARCHAR(255) NULL AFTER `quality`,
    ADD COLUMN `packaging` VARCHAR(255) NULL AFTER `labeling`,
    ADD COLUMN `potassium_sorbate` VARCHAR(255) NULL AFTER `packaging`,
    ADD COLUMN `humidity` VARCHAR(255) NULL AFTER `potassium_sorbate`,
    ADD COLUMN `stone_percentage` VARCHAR(255) NULL AFTER `humidity`,
    ADD COLUMN `oil` VARCHAR(255) NULL AFTER `stone_percentage`,
    ADD COLUMN `damage` VARCHAR(255) NULL AFTER `oil`,
    ADD COLUMN `plant_print` VARCHAR(255) NULL AFTER `damage`,
    ADD COLUMN `destination` VARCHAR(255) NULL AFTER `plant_print`,
    ADD COLUMN `loading_date` VARCHAR(255) NULL AFTER `destination`,
    ADD COLUMN `sag` TINYINT(1) NOT NULL DEFAULT 0 AFTER `loading_date`;

-- 2) Kilos enviados / producidos
ALTER TABLE `process_orders`
    ADD COLUMN `kilos_sent` DECIMAL(10,2) NULL AFTER `quantity`,
    ADD COLUMN `kilos_produced` DECIMAL(10,2) NULL AFTER `kilos_sent`;

-- 3) Envío (patente, fecha, hora)
ALTER TABLE `process_orders`
    ADD COLUMN `vehicle_plate` VARCHAR(20) NULL AFTER `sag`,
    ADD COLUMN `shipment_date` DATE NULL AFTER `vehicle_plate`,
    ADD COLUMN `shipment_time` TIME NULL AFTER `shipment_date`;
