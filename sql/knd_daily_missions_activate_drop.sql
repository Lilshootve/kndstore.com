-- Activate the Drop Hunter mission now that KND Drop Chamber exists
UPDATE `knd_daily_missions` SET `is_active` = 1 WHERE `code` = 'make_drop_1' AND `is_active` = 0;
