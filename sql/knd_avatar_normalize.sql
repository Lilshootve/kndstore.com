-- KND Avatar - Normalize slots and asset_path
-- Run after knd_avatar_items has data.
-- 1) Slot normalization: map alternate values to canonical (bg, top, bottom, shoes, hair, accessory, frame)
--    Note: ENUM restricts values; run this only if you've altered ENUM to allow legacy values first.
-- 2) Asset path normalization: ensure asset_path = /assets/avatars/{slot}/{filename}.svg

-- Fix asset_path: ensure it starts with /assets/avatars/ and uses correct slot folder.
-- Extracts filename from current path and rebuilds as /assets/avatars/{slot}/{filename}
UPDATE knd_avatar_items
SET asset_path = CONCAT('/assets/avatars/', slot, '/', SUBSTRING_INDEX(asset_path, '/', -1))
WHERE asset_path NOT LIKE CONCAT('/assets/avatars/', slot, '/%')
  AND slot IN ('hair', 'top', 'bottom', 'shoes', 'accessory', 'bg', 'frame');
