-- KND User Badges - User badge unlocks
-- Tracks which badges each user has unlocked
-- Run after knd_badges.sql

CREATE TABLE IF NOT EXISTS `knd_user_badges` (
  `user_id` BIGINT NOT NULL,
  `badge_id` INT NOT NULL,
  `unlocked_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `badge_id`),
  CONSTRAINT `fk_ub_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ub_badge` FOREIGN KEY (`badge_id`) REFERENCES `knd_badges` (`id`) ON DELETE CASCADE,
  KEY `idx_unlocked` (`unlocked_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
