-- KND Support Credits - Add risk_flag to users table

ALTER TABLE `users`
  ADD COLUMN `risk_flag` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password_hash`;
