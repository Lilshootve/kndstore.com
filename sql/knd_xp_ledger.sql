-- Optional audit log for XP grants (run if you want audit trail)
CREATE TABLE IF NOT EXISTS `knd_xp_ledger` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT NOT NULL,
  `xp_delta` INT NOT NULL,
  `source` VARCHAR(64) NOT NULL,
  `ref_type` VARCHAR(64) NULL,
  `ref_id` BIGINT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_xpl_user` (`user_id`),
  INDEX `idx_xpl_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
