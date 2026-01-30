-- Tabla para la vitácora de eventos de la campana (atendidos / pendientes).
-- Ejecutar en la base de datos cofrupa si la migración falla.

CREATE TABLE IF NOT EXISTS `notification_attendance_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `event_type` varchar(80) NOT NULL COMMENT 'pending_purchases, incomplete_suppliers, etc.',
  `event_label` varchar(255) NOT NULL COMMENT 'Etiqueta para mostrar',
  `count_snapshot` int unsigned DEFAULT NULL COMMENT 'Cantidad al momento de marcar como atendido',
  `attended_at` timestamp NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_attendance_log_user_id_event_type_index` (`user_id`,`event_type`),
  KEY `notification_attendance_log_attended_at_index` (`attended_at`),
  CONSTRAINT `notification_attendance_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
