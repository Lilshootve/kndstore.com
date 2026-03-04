-- KND Avatar v1 - User inventory (owned items)
-- Run after knd_avatar_items.sql, requires users table

CREATE TABLE IF NOT EXISTS `knd_user_avatar_inventory` (
  `user_id` BIGINT NOT NULL,
  `item_id` INT NOT NULL,
  `acquired_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `item_id`),
  CONSTRAINT `fk_uai_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_uai_item` FOREIGN KEY (`item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
