CREATE TABLE IF NOT EXISTS `above_under_rolls` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `choice` ENUM('under','above') NOT NULL,
  `rolled_value` TINYINT NOT NULL,
  `is_win` TINYINT(1) NOT NULL DEFAULT 0,
  `entry_points` INT NOT NULL DEFAULT 200,
  `payout_points` INT NOT NULL DEFAULT 0,
  `xp_awarded` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_aur_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
