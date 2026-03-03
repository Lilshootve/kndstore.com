-- Add columns to admin_users for Admin Users Module
-- Run after admin_users exists. MySQL 5.x: run each ALTER separately; ignore errors if column exists.

ALTER TABLE `admin_users` ADD COLUMN `force_password_reset` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `admin_users` ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;
ALTER TABLE `admin_users` ADD COLUMN `created_by_admin_id` INT UNSIGNED NULL DEFAULT NULL;
