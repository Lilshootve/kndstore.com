CREATE TABLE IF NOT EXISTS `knd_user_drop_rewards` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `drop_id` BIGINT NOT NULL,
  `reward_item_id` INT NULL,
  `rarity` ENUM('common','special','rare','epic','legendary') NOT NULL,
  `was_duplicate` TINYINT(1) NOT NULL DEFAULT 0,
  `fragments_awarded` INT NOT NULL DEFAULT 0,
  `pity_boost` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  KEY `idx_user_created` (`user_id`, `created_at`),
  KEY `idx_drop` (`drop_id`),
  KEY `idx_user_drop` (`user_id`, `drop_id`),
  KEY `idx_item` (`reward_item_id`),
  KEY `idx_rarity` (`rarity`),

  CONSTRAINT `fk_udr_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,

  CONSTRAINT `fk_udr_drop`
    FOREIGN KEY (`drop_id`) REFERENCES `knd_drops` (`id`) ON DELETE CASCADE,

  CONSTRAINT `fk_udr_item`
    FOREIGN KEY (`reward_item_id`) REFERENCES `knd_avatar_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;