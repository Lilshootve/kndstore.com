-- KND Support Credits - Rewards catalog table

CREATE TABLE IF NOT EXISTS `rewards_catalog` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(120) NOT NULL,
  `description` TEXT NULL,
  `category` VARCHAR(32) NOT NULL COMMENT 'knd_service, beauty_selfcare',
  `points_cost` INT NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `stock` INT NULL DEFAULT NULL COMMENT 'NULL = unlimited',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
