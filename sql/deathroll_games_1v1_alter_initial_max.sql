-- KND Store - Add initial_max column to deathroll_games_1v1
-- Run AFTER deathroll_games_1v1.sql and deathroll_games_1v1_alter_v2.sql

ALTER TABLE `deathroll_games_1v1`
  ADD COLUMN `initial_max` INT NOT NULL DEFAULT 1000 AFTER `current_max`;

-- Backfill: set initial_max = 1000 for existing rows (already the default)
UPDATE `deathroll_games_1v1` SET `initial_max` = 1000 WHERE `initial_max` = 0;
