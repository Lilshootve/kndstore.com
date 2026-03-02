-- KND Store - Rematch offers for Death Roll 1v1
-- Run AFTER deathroll_games_1v1.sql and users.sql

CREATE TABLE IF NOT EXISTS `deathroll_rematch_offers` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `game_id` BIGINT NOT NULL,
  `offered_by_user_id` BIGINT NOT NULL,
  `offered_to_user_id` BIGINT NOT NULL,
  `new_game_code` CHAR(8) DEFAULT NULL,
  `status` ENUM('pending','accepted','declined','expired') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_game_id` (`game_id`),
  KEY `idx_status` (`status`),
  KEY `idx_offered_to` (`offered_to_user_id`),
  CONSTRAINT `fk_rematch_game` FOREIGN KEY (`game_id`) REFERENCES `deathroll_games_1v1` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rematch_offered_by` FOREIGN KEY (`offered_by_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_rematch_offered_to` FOREIGN KEY (`offered_to_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
