-- KND Avatar v1 - User equip loadout and colors
-- Run after knd_avatar_items.sql, requires users table

CREATE TABLE IF NOT EXISTS `knd_user_avatar` (
  `user_id` BIGINT NOT NULL,
  `hair_item_id` INT NULL,
  `top_item_id` INT NULL,
  `bottom_item_id` INT NULL,
  `shoes_item_id` INT NULL,
  `accessory1_item_id` INT NULL,
  `bg_item_id` INT NULL,
  `frame_item_id` INT NULL,
  `colors_json` TEXT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_ua_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ua_hair` FOREIGN KEY (`hair_item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ua_top` FOREIGN KEY (`top_item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ua_bottom` FOREIGN KEY (`bottom_item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ua_shoes` FOREIGN KEY (`shoes_item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ua_accessory1` FOREIGN KEY (`accessory1_item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ua_bg` FOREIGN KEY (`bg_item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ua_frame` FOREIGN KEY (`frame_item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
