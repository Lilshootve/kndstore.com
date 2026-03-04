-- KND Store - TripoSR 3D generation jobs
-- Run AFTER users.sql

CREATE TABLE IF NOT EXISTS `triposr_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `job_uuid` CHAR(36) NOT NULL,
  `input_path` VARCHAR(512) NOT NULL,
  `output_path` VARCHAR(512) DEFAULT NULL,
  `status` ENUM('pending', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'pending',
  `error_message` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_job_uuid` (`job_uuid`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_triposr_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
