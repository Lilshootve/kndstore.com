-- Extend triposr_jobs for AI tools (text2img, upscale, character lab)
-- Same table used for img23d (InstantMesh) and new AI job types.
-- Run after triposr_jobs.sql and triposr_jobs_alter_quality.sql

ALTER TABLE `triposr_jobs`
  ADD COLUMN `job_type` VARCHAR(32) NOT NULL DEFAULT 'img23d' AFTER `job_uuid`,
  ADD COLUMN `provider` VARCHAR(32) NULL AFTER `job_type`,
  ADD COLUMN `cost_kp` INT NOT NULL DEFAULT 0 AFTER `provider`,
  ADD COLUMN `payload_json` TEXT NULL AFTER `cost_kp`,
  ADD COLUMN `result_json` TEXT NULL AFTER `error_message`;

-- input_path: for img23d/upscale holds path; for text2img/character use ''
-- output_path: holds relative path to result file (image or model)

-- Index for filtering by job_type
ALTER TABLE `triposr_jobs` ADD KEY `idx_job_type` (`job_type`);
