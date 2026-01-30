-- Tabla de contactos por planta (plant_contacts).
-- Ejecutar en la base de datos cofrupa si la tabla no existe.

CREATE TABLE IF NOT EXISTS `plant_contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plant_id` bigint unsigned NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `order` int NOT NULL DEFAULT 0 COMMENT 'Orden de los contactos',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plant_contacts_plant_id_foreign` (`plant_id`),
  CONSTRAINT `plant_contacts_plant_id_foreign` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
