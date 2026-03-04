-- KND Avatar v1 - Items catalog
-- Run before knd_user_avatar_inventory.sql

CREATE TABLE IF NOT EXISTS `knd_avatar_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(64) NOT NULL,
  `slot` ENUM('hair','top','bottom','shoes','accessory','bg','frame') NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `rarity` ENUM('common','rare','epic','legendary') NOT NULL DEFAULT 'common',
  `price_kp` INT NOT NULL,
  `asset_path` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  KEY `idx_slot` (`slot`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
