CREATE TABLE IF NOT EXISTS `knd_daily_missions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(32) NOT NULL,
  `title` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `target` INT NOT NULL,
  `reward_kp` INT NOT NULL,
  `reward_xp` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `knd_daily_missions` (`code`, `title`, `description`, `target`, `reward_kp`, `reward_xp`, `is_active`) VALUES
('play_lastroll_3',   'LastRoll Warrior',    'Play 3 LastRoll matches',     3, 40, 5, 1),
('win_lastroll_1',    'LastRoll Champion',   'Win 1 LastRoll match',        1, 25, 5, 1),
('play_insight_5',    'Insight Addict',      'Play 5 KND Insight rounds',   5, 30, 5, 1),
('make_drop_1',       'Drop Hunter',         'Make 1 Drop',                 1, 20, 5, 0);
