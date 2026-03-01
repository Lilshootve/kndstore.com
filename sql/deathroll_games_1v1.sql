-- KND Store - Death Roll 1v1 games table
-- Run AFTER users.sql

CREATE TABLE IF NOT EXISTS `deathroll_games_1v1` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `code` CHAR(8) NOT NULL,
  `visibility` ENUM('public','private') NOT NULL DEFAULT 'public',
  `status` ENUM('waiting','playing','finished') NOT NULL DEFAULT 'waiting',
  `created_by_user_id` BIGINT NOT NULL,
  `player1_user_id` BIGINT NOT NULL,
  `player2_user_id` BIGINT DEFAULT NULL,
  `current_max` INT NOT NULL DEFAULT 1000,
  `turn_user_id` BIGINT DEFAULT NULL,
  `winner_user_id` BIGINT DEFAULT NULL,
  `loser_user_id` BIGINT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  KEY `idx_status_vis_updated` (`status`, `visibility`, `updated_at`),
  KEY `idx_player1` (`player1_user_id`),
  KEY `idx_player2` (`player2_user_id`),
  CONSTRAINT `fk_game_creator` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_game_player1` FOREIGN KEY (`player1_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_game_player2` FOREIGN KEY (`player2_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_game_turn` FOREIGN KEY (`turn_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_game_winner` FOREIGN KEY (`winner_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_game_loser` FOREIGN KEY (`loser_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
