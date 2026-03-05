-- Add output_path for local upscale files (relative to storage)
ALTER TABLE knd_labs_jobs ADD COLUMN output_path VARCHAR(512) DEFAULT NULL;
ALTER TABLE knd_labs_jobs ADD COLUMN input_path VARCHAR(512) DEFAULT NULL;
