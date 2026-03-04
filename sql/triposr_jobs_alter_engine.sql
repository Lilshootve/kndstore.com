-- Add engine column to triposr_jobs (InstantMesh migration)
-- Table name triposr_jobs kept for backward compatibility; engine is now InstantMesh.
-- Run after triposr_jobs.sql and triposr_jobs_alter_quality.sql exist.
-- If column already exists, ignore "Duplicate column name" error.

ALTER TABLE `triposr_jobs`
ADD COLUMN `engine` VARCHAR(32) NOT NULL DEFAULT 'instantmesh'
AFTER `quality`;
