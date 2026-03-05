-- Add cost_kp and quality columns to knd_labs_jobs
ALTER TABLE knd_labs_jobs ADD COLUMN cost_kp INT UNSIGNED DEFAULT 0;
ALTER TABLE knd_labs_jobs ADD COLUMN quality VARCHAR(32) DEFAULT NULL;
