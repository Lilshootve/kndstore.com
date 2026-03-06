-- KND User Fragments - Avatar fragment balance system
-- Stores user's total fragment balance (single currency)
-- Fragments are earned from duplicate avatar drops

CREATE TABLE IF NOT EXISTS `knd_user_fragments` (
  `user_id` BIGINT NOT NULL,
  `amount` INT NOT NULL DEFAULT 0,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_uf_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
