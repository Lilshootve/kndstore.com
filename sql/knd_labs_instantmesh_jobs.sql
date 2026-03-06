-- KND Labs - InstantMesh jobs table
-- Run AFTER users.sql

CREATE TABLE IF NOT EXISTS `knd_labs_instantmesh_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `public_id` CHAR(36) NOT NULL,
  `status` ENUM('queued','processing','completed','failed') NOT NULL DEFAULT 'queued',
  `source_image_path` VARCHAR(255) NOT NULL,
  `preview_image_path` VARCHAR(255) DEFAULT NULL,
  `output_glb_path` VARCHAR(255) DEFAULT NULL,
  `output_obj_path` VARCHAR(255) DEFAULT NULL,
  `remove_bg` TINYINT(1) NOT NULL DEFAULT 1,
  `seed` INT DEFAULT 42,
  `output_format` ENUM('glb','obj','both') NOT NULL DEFAULT 'glb',
  `credits_cost` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `error_message` TEXT DEFAULT NULL,
  `processing_started_at` DATETIME DEFAULT NULL,
  `completed_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_public_id` (`public_id`),
  KEY `idx_user_created` (`user_id`, `created_at`),
  KEY `idx_status_created` (`status`, `created_at`),
  CONSTRAINT `fk_instantmesh_jobs_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
