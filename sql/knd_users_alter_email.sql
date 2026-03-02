-- Add email and verification columns to users table

ALTER TABLE `users`
  ADD COLUMN `email` VARCHAR(255) NULL DEFAULT NULL AFTER `username`,
  ADD COLUMN `email_verified` TINYINT(1) NOT NULL DEFAULT 0 AFTER `email`,
  ADD COLUMN `email_verify_code` VARCHAR(8) NULL DEFAULT NULL AFTER `email_verified`,
  ADD COLUMN `email_verify_expires` DATETIME NULL DEFAULT NULL AFTER `email_verify_code`;

ALTER TABLE `users`
  ADD UNIQUE KEY `uk_email` (`email`);
