CREATE TABLE IF NOT EXISTS `knd_season_stats` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `season_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `xp_earned` BIGINT NOT NULL DEFAULT 0,
  `matches_played` INT NOT NULL DEFAULT 0,
  `wins` INT NOT NULL DEFAULT 0,
  `losses` INT NOT NULL DEFAULT 0,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_season_user` (`season_id`, `user_id`),
  KEY `idx_season_xp` (`season_id`, `xp_earned` DESC),
  CONSTRAINT `fk_kss_season` FOREIGN KEY (`season_id`) REFERENCES `knd_seasons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_kss_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
