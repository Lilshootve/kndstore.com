-- Add character_lab_spend and character_lab_refund to points_ledger source_type
-- Run after points_ledger_add_ai_jobs.sql

ALTER TABLE `points_ledger` MODIFY COLUMN `source_type`
  ENUM('support_payment','redemption','adjustment','avatar_shop','3d_generation','3d_generation_refund','ai_job_spend','ai_job_refund','ai_job_complete','character_lab_spend','character_lab_refund') NOT NULL;
