-- KND Labs 3D Lab jobs table
-- Dedicated pipeline for text/image → GLB (separate ComfyUI from text2img/upscale)
-- Run AFTER users.sql and points_ledger exist

CREATE TABLE IF NOT EXISTS `knd_labs_3d_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `public_id` CHAR(36) NOT NULL,
  `mode` ENUM('text','image','text_image','recent') NOT NULL DEFAULT 'text',
  `prompt` TEXT,
  `negative_prompt` TEXT,
  `category` VARCHAR(64) NOT NULL DEFAULT 'Stylized Asset',
  `style` VARCHAR(64) NOT NULL DEFAULT 'Stylized',
  `quality` VARCHAR(32) NOT NULL DEFAULT 'Standard',
  `policy_mode` VARCHAR(24) NOT NULL DEFAULT 'safe',
  `input_image_path` VARCHAR(512) DEFAULT NULL,
  `source_recent_job_id` BIGINT UNSIGNED DEFAULT NULL,
  `source_recent_type` VARCHAR(32) DEFAULT NULL,
  `advanced_params_json` TEXT DEFAULT NULL,
  `kp_cost` INT UNSIGNED NOT NULL DEFAULT 30,
  `status` ENUM('queued','processing','completed','failed','refunded') NOT NULL DEFAULT 'queued',
  `glb_path` VARCHAR(512) DEFAULT NULL,
  `preview_path` VARCHAR(512) DEFAULT NULL,
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
  CONSTRAINT `fk_labs_3d_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
