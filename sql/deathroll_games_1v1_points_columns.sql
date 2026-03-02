-- Add KND Points economy columns to deathroll_games_1v1

ALTER TABLE `deathroll_games_1v1`
  ADD COLUMN `entry_kp` INT NOT NULL DEFAULT 100,
  ADD COLUMN `payout_kp` INT NOT NULL DEFAULT 150,
  ADD COLUMN `house_kp` INT NOT NULL DEFAULT 50,
  ADD COLUMN `charged_at` DATETIME NULL DEFAULT NULL,
  ADD COLUMN `payout_at` DATETIME NULL DEFAULT NULL;
