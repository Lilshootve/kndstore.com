-- Add 'avatar_shop' to points_ledger source_type enum for avatar cosmetics purchases
-- Run after points_ledger exists

ALTER TABLE `points_ledger` MODIFY COLUMN `source_type`
  ENUM('support_payment','redemption','adjustment','avatar_shop') NOT NULL;
