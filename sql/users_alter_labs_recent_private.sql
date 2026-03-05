-- Labs: user preference for Recent Jobs visibility
-- 0 = public (default), 1 = private (only my jobs)
-- Run after users table exists. Ignore error if column exists.
ALTER TABLE `users` ADD COLUMN `labs_recent_private` TINYINT(1) NOT NULL DEFAULT 0;
