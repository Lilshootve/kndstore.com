CREATE TABLE IF NOT EXISTS `knd_drops` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `season_id` BIGINT NOT NULL,
  `entry_kp` INT NOT NULL,
  `rarity` ENUM('common','rare','epic','legendary') NOT NULL,
  `reward_kp` INT NOT NULL,
  `config_id` BIGINT NOT NULL,
  `xp_awarded` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_created` (`user_id`, `created_at`),
  KEY `idx_season_created` (`season_id`, `created_at`),
  CONSTRAINT `fk_kd_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_kd_season` FOREIGN KEY (`season_id`) REFERENCES `knd_drop_seasons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_kd_config` FOREIGN KEY (`config_id`) REFERENCES `knd_drop_configs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
