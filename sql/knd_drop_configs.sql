CREATE TABLE IF NOT EXISTS `knd_drop_configs` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `season_id` BIGINT NOT NULL,
  `rarity` ENUM('common','rare','epic','legendary') NOT NULL,
  `reward_kp` INT NOT NULL,
  `weight` INT NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_season_active` (`season_id`, `is_active`),
  KEY `idx_rarity` (`rarity`),
  CONSTRAINT `fk_dc_season` FOREIGN KEY (`season_id`) REFERENCES `knd_drop_seasons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Season I configs (weights sum to 100 for easy reasoning, used as relative weights)
INSERT INTO `knd_drop_configs` (`season_id`, `rarity`, `reward_kp`, `weight`)
SELECT s.id, v.rarity, v.reward_kp, v.weight
FROM knd_drop_seasons s
CROSS JOIN (
  SELECT 'common'    AS rarity,   0 AS reward_kp, 28 AS weight UNION ALL
  SELECT 'common',                150,             17           UNION ALL
  SELECT 'rare',                  300,             15           UNION ALL
  SELECT 'rare',                  350,             15           UNION ALL
  SELECT 'epic',                  450,             10           UNION ALL
  SELECT 'epic',                  500,              8           UNION ALL
  SELECT 'legendary',             700,              7
) v
WHERE s.code = 'GENESIS_S1'
AND NOT EXISTS (SELECT 1 FROM knd_drop_configs dc WHERE dc.season_id = s.id);
