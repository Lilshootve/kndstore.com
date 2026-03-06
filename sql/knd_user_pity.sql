-- KND User Pity - Pity counter for drop system
-- Tracks drops since last rare+ item to implement pity mechanic
-- Counter increments on common/special drops, resets on rare/epic/legendary

CREATE TABLE IF NOT EXISTS `knd_user_pity` (
  `user_id` BIGINT NOT NULL,
  `drops_since_rare` INT NOT NULL DEFAULT 0,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_up_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
