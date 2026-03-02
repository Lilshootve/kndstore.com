-- Add password reset columns to users table

ALTER TABLE `users`
  ADD COLUMN `password_reset_code` VARCHAR(8) NULL DEFAULT NULL AFTER `email_verify_expires`,
  ADD COLUMN `password_reset_expires` DATETIME NULL DEFAULT NULL AFTER `password_reset_code`;
