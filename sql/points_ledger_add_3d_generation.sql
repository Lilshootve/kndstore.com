-- Add '3d_generation' and '3d_generation_refund' to points_ledger source_type
-- Run after points_ledger exists (and points_ledger_add_avatar_shop.sql if used)

ALTER TABLE `points_ledger` MODIFY COLUMN `source_type`
  ENUM('support_payment','redemption','adjustment','avatar_shop','3d_generation','3d_generation_refund') NOT NULL;
