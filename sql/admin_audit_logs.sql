-- Admin audit logs - auditoría de acciones admin
-- Ejecutar en la base de datos del proyecto

CREATE TABLE IF NOT EXISTS `admin_audit_logs` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `admin_ip` VARCHAR(64) NOT NULL DEFAULT '',
  `admin_user` VARCHAR(64) NULL DEFAULT 'admin',
  `action` VARCHAR(80) NOT NULL,
  `meta_json` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
