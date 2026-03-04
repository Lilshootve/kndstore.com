-- KND Avatar v1 - Seed demo items (placeholder asset paths)
-- Run after knd_avatar_items.sql

INSERT INTO `knd_avatar_items` (`code`, `slot`, `name`, `rarity`, `price_kp`, `asset_path`, `is_active`) VALUES
('hair_01', 'hair', 'Street Cut', 'common', 50, '/assets/avatars/hair/hair_01.svg', 1),
('hair_02', 'hair', 'Tech Fade', 'rare', 120, '/assets/avatars/hair/hair_02.svg', 1),
('top_01', 'top', 'Neon Hoodie', 'common', 80, '/assets/avatars/top/top_01.svg', 1),
('top_02', 'top', 'Cyber Jacket', 'epic', 250, '/assets/avatars/top/top_02.svg', 1),
('bottom_01', 'bottom', 'Cargo Pants', 'common', 60, '/assets/avatars/bottom/bottom_01.svg', 1),
('shoes_01', 'shoes', 'Runner Pro', 'common', 70, '/assets/avatars/shoes/shoes_01.svg', 1),
('shoes_02', 'shoes', 'Legend Boots', 'legendary', 500, '/assets/avatars/shoes/shoes_02.svg', 1),
('acc_01', 'accessory', 'Visor Goggles', 'rare', 150, '/assets/avatars/accessory/acc_01.svg', 1),
('bg_01', 'bg', 'Neon Grid', 'common', 40, '/assets/avatars/bg/bg_01.svg', 1),
('frame_01', 'frame', 'Epic Border', 'epic', 200, '/assets/avatars/frame/frame_01.svg', 1)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `price_kp` = VALUES(`price_kp`), `asset_path` = VALUES(`asset_path`);
