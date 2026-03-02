-- KND Support Credits - Reward redemptions table
-- Run AFTER knd_rewards_catalog.sql

CREATE TABLE IF NOT EXISTS `reward_redemptions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `reward_id` BIGINT NOT NULL,
  `points_spent` INT NOT NULL,
  `status` ENUM('requested','approved','fulfilled','rejected','cancelled') NOT NULL DEFAULT 'requested',
  `notes` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_reward_id` (`reward_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_rr_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rr_reward` FOREIGN KEY (`reward_id`) REFERENCES `rewards_catalog` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
