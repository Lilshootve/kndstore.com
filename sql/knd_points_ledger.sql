-- KND Support Credits - Points ledger table
-- Run AFTER knd_support_payments.sql

CREATE TABLE IF NOT EXISTS `points_ledger` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `source_type` ENUM('support_payment','redemption','adjustment') NOT NULL,
  `source_id` BIGINT NOT NULL,
  `entry_type` ENUM('earn','spend','reversal') NOT NULL,
  `status` ENUM('pending','available','locked','spent','expired') NOT NULL,
  `points` INT NOT NULL,
  `available_at` DATETIME NULL DEFAULT NULL,
  `expires_at` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_source` (`source_type`, `source_id`),
  KEY `idx_available_at` (`available_at`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `fk_pl_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
