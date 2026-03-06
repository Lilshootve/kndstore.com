-- KND Badges - Seed initial badge definitions
-- Run after knd_badges.sql

INSERT INTO `knd_badges` (`code`, `name`, `description`, `icon_path`, `unlock_type`, `unlock_threshold`) VALUES
('GENERATOR_10', 'Generator Novice', 'Generated 10 images', '/assets/badges/gen_10.svg', 'generator', 10),
('GENERATOR_100', 'Generator Master', 'Generated 100 images', '/assets/badges/gen_100.svg', 'generator', 100),
('GENERATOR_500', 'Generator Legend', 'Generated 500 images', '/assets/badges/gen_500.svg', 'generator', 500),
('DROP_10', 'Drop Explorer', 'Opened 10 capsules', '/assets/badges/drop_10.svg', 'drop', 10),
('DROP_50', 'Drop Veteran', 'Opened 50 capsules', '/assets/badges/drop_50.svg', 'drop', 50),
('DROP_200', 'Drop Master', 'Opened 200 capsules', '/assets/badges/drop_200.svg', 'drop', 200),
('COLLECTOR_10', 'Collector', 'Collected 10 unique avatars', '/assets/badges/col_10.svg', 'collector', 10),
('COLLECTOR_25', 'Master Collector', 'Collected 25 unique avatars', '/assets/badges/col_25.svg', 'collector', 25),
('COLLECTOR_50', 'Ultimate Collector', 'Collected 50 unique avatars', '/assets/badges/col_50.svg', 'collector', 50),
('LEGENDARY_PULL', 'Legendary!', 'Pulled a legendary avatar', '/assets/badges/legendary.svg', 'legendary_pull', 1),
('LEVEL_10', 'Level 10', 'Reached level 10', '/assets/badges/lvl_10.svg', 'level', 10),
('LEVEL_20', 'Level 20', 'Reached level 20', '/assets/badges/lvl_20.svg', 'level', 20),
('LEVEL_30', 'Level 30', 'Reached max level', '/assets/badges/lvl_30.svg', 'level', 30)
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `description` = VALUES(`description`),
  `icon_path` = VALUES(`icon_path`),
  `unlock_type` = VALUES(`unlock_type`),
  `unlock_threshold` = VALUES(`unlock_threshold`);
