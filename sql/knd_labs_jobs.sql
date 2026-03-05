-- KND Labs jobs for ComfyUI integration
CREATE TABLE IF NOT EXISTS knd_labs_jobs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  tool VARCHAR(32) NOT NULL,
  prompt TEXT,
  negative_prompt TEXT,
  comfy_prompt_id VARCHAR(64) DEFAULT NULL,
  status VARCHAR(24) NOT NULL DEFAULT 'pending',
  image_url VARCHAR(512) DEFAULT NULL,
  error_message TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY idx_user_status (user_id, status),
  KEY idx_comfy_prompt (comfy_prompt_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
