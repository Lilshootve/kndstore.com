-- KND Labs: Queue system columns and indices
-- Run after knd_labs_jobs exists. Ignore errors if column/index already exists.

ALTER TABLE knd_labs_jobs MODIFY COLUMN status VARCHAR(24) NOT NULL DEFAULT 'queued';

ALTER TABLE knd_labs_jobs ADD COLUMN priority INT DEFAULT 100;
ALTER TABLE knd_labs_jobs ADD COLUMN locked_at DATETIME NULL;
ALTER TABLE knd_labs_jobs ADD COLUMN locked_by VARCHAR(64) NULL;
ALTER TABLE knd_labs_jobs ADD COLUMN attempts INT DEFAULT 0;
ALTER TABLE knd_labs_jobs ADD COLUMN started_at DATETIME NULL;
ALTER TABLE knd_labs_jobs ADD COLUMN finished_at DATETIME NULL;
ALTER TABLE knd_labs_jobs ADD COLUMN payload_json LONGTEXT NULL;

ALTER TABLE knd_labs_jobs ADD INDEX idx_queue (status, priority, created_at);
