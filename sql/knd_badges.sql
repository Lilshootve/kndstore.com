-- KND Badges - Badge definitions catalog
-- Badges unlock from milestones (not random drops)
-- Examples: GENERATOR_100, DROP_50, COLLECTOR_25, LEGENDARY_PULL, LEVEL_10

CREATE TABLE IF NOT EXISTS `knd_badges` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(64) NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `description` TEXT NULL,
  `icon_path` VARCHAR(255) NOT NULL,
  `unlock_type` ENUM('generator','drop','collector','legendary_pull','level','manual') NOT NULL,
  `unlock_threshold` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  KEY `idx_unlock_type` (`unlock_type`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
