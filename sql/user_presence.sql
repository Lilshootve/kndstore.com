-- KND Store - User presence tracking for lobby
-- Run AFTER users.sql

CREATE TABLE IF NOT EXISTS `user_presence` (
  `user_id` BIGINT NOT NULL,
  `last_seen` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `idx_last_seen` (`last_seen`),
  CONSTRAINT `fk_presence_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
