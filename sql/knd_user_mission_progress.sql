CREATE TABLE IF NOT EXISTS `knd_user_mission_progress` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `mission_id` BIGINT NOT NULL,
  `progress_date` DATE NOT NULL,
  `progress` INT NOT NULL DEFAULT 0,
  `completed_date` DATE NULL DEFAULT NULL,
  `claimed_date` DATE NULL DEFAULT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_mission_date` (`user_id`, `mission_id`, `progress_date`),
  KEY `idx_user_date` (`user_id`, `progress_date`),
  CONSTRAINT `fk_ump_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ump_mission` FOREIGN KEY (`mission_id`) REFERENCES `knd_daily_missions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
