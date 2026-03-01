-- KND Store - Application rate limiting table
-- Run in any order (no FK dependencies)

CREATE TABLE IF NOT EXISTS `app_rate_limits` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `key_hash` CHAR(64) NOT NULL,
  `hits` INT NOT NULL DEFAULT 1,
  `window_start` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key_hash` (`key_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
