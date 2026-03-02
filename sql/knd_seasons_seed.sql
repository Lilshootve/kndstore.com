-- Seed active KND Arena season if none exists
INSERT INTO `knd_seasons` (`code`, `name`, `starts_at`, `ends_at`, `is_active`)
SELECT 'GENESIS_S1', 'KND Genesis Season', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 1
WHERE NOT EXISTS (SELECT 1 FROM knd_seasons WHERE is_active = 1 AND ends_at > NOW());
