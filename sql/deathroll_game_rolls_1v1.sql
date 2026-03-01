-- KND Store - Death Roll 1v1 roll history
-- Run AFTER deathroll_games_1v1.sql

CREATE TABLE IF NOT EXISTS `deathroll_game_rolls_1v1` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `game_id` BIGINT NOT NULL,
  `user_id` BIGINT NOT NULL,
  `max_value` INT NOT NULL,
  `roll_value` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_game_created` (`game_id`, `created_at`),
  CONSTRAINT `fk_roll_game` FOREIGN KEY (`game_id`) REFERENCES `deathroll_games_1v1` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_roll_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
