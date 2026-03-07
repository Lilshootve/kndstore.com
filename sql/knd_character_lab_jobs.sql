-- KND Character Lab jobs table
-- Full pipeline: concept image -> 3D (Hunyuan3D primary, TripoSR/InstantMesh fallback)
-- Run AFTER users.sql and points_ledger exist

CREATE TABLE IF NOT EXISTS `knd_character_lab_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `public_id` CHAR(36) NOT NULL,
  `mode` ENUM('text','image','text_image','recent_image') NOT NULL DEFAULT 'text',
  `prompt_raw` TEXT,
  `prompt_sanitized` TEXT,
  `category` VARCHAR(64) DEFAULT 'human',
  `policy_mode` VARCHAR(24) NOT NULL DEFAULT 'safe',
  `input_image_path` VARCHAR(512) DEFAULT NULL,
  `source_recent_job_id` BIGINT UNSIGNED DEFAULT NULL,
  `engine_image` VARCHAR(64) DEFAULT NULL,
  `engine_3d` VARCHAR(64) DEFAULT NULL,
  `kp_cost` INT UNSIGNED NOT NULL DEFAULT 0,
  `status` ENUM('queued','image_generating','image_ready','mesh_generating','mesh_ready','partial_success','failed','refunded') NOT NULL DEFAULT 'queued',
  `concept_image_path` VARCHAR(512) DEFAULT NULL,
  `mesh_glb_path` VARCHAR(512) DEFAULT NULL,
  `preview_thumb_path` VARCHAR(512) DEFAULT NULL,
  `meta_json` LONGTEXT DEFAULT NULL,
  `error_message` TEXT DEFAULT NULL,
  `processing_started_at` DATETIME DEFAULT NULL,
  `completed_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_public_id` (`public_id`),
  KEY `idx_user_created` (`user_id`, `created_at`),
  KEY `idx_status_created` (`status`, `created_at`),
  KEY `idx_source_recent` (`source_recent_job_id`),
  CONSTRAINT `fk_character_lab_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
