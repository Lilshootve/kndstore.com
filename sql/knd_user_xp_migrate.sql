-- Migrate existing user_xp data into knd_user_xp (run after knd_user_xp exists)
INSERT INTO knd_user_xp (user_id, xp, level, updated_at)
SELECT ux.user_id, ux.xp,
  GREATEST(1, FLOOR(SQRT(ux.xp / 100)) + 1),
  ux.updated_at
FROM user_xp ux
ON DUPLICATE KEY UPDATE
  xp = GREATEST(knd_user_xp.xp, VALUES(xp)),
  level = LEAST(30, GREATEST(1, FLOOR(SQRT(GREATEST(knd_user_xp.xp, VALUES(xp)) / 100)) + 1)),
  updated_at = GREATEST(knd_user_xp.updated_at, VALUES(updated_at));
