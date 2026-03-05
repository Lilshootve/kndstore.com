-- KND Store - Key-value settings table
CREATE TABLE IF NOT EXISTS knd_settings (
  `key` VARCHAR(64) PRIMARY KEY,
  `value` TEXT NOT NULL,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
