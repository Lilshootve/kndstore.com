-- KND Avatar Items - Add 'special' rarity tier
-- Migration: Add 'special' to rarity enum for avatar drop system redesign

ALTER TABLE `knd_avatar_items` 
MODIFY `rarity` ENUM('common','special','rare','epic','legendary') NOT NULL DEFAULT 'common';
