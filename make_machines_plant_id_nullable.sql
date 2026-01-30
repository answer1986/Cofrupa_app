-- Permitir que machines.plant_id sea NULL (máquinas sin planta asignada).
-- Ejecutar si al crear una máquina aparece: Field 'plant_id' doesn't have a default value

ALTER TABLE machines MODIFY COLUMN plant_id BIGINT UNSIGNED NULL;
