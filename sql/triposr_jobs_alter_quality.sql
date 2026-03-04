-- Add quality column to triposr_jobs (InstantMesh V2)
-- Run after triposr_jobs.sql exists

ALTER TABLE `triposr_jobs`
ADD COLUMN `quality` ENUM('fast','balanced','high') NOT NULL DEFAULT 'balanced'
AFTER `input_path`;
