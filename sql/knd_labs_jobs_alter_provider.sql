-- Add provider column to knd_labs_jobs (local|runpod)
ALTER TABLE knd_labs_jobs ADD COLUMN provider VARCHAR(16) DEFAULT NULL;
