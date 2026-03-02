-- KND Store - ALTER deathroll_games_1v1 for GC + turn timer
-- Run AFTER deathroll_games_1v1.sql has been applied

ALTER TABLE `deathroll_games_1v1`
  ADD COLUMN `last_activity_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `updated_at`,
  ADD COLUMN `turn_started_at` DATETIME NULL AFTER `last_activity_at`,
  ADD COLUMN `finished_reason` VARCHAR(24) NULL AFTER `turn_started_at`;

-- Backfill existing rows
UPDATE `deathroll_games_1v1` SET `last_activity_at` = `updated_at` WHERE `last_activity_at` = '0000-00-00 00:00:00' OR `last_activity_at` IS NULL;

-- Index for GC queries
ALTER TABLE `deathroll_games_1v1` ADD KEY `idx_status_activity` (`status`, `last_activity_at`);
