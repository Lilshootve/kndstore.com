-- Add admin_id to admin_audit_logs for RBAC
-- Run after admin_users exists

ALTER TABLE `admin_audit_logs`
  ADD COLUMN `admin_id` INT UNSIGNED NULL DEFAULT NULL AFTER `id`,
  ADD KEY `idx_admin_id` (`admin_id`);
