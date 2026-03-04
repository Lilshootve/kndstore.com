# KND Avatar v1 - SQL Execution Order

Run these SQL files in order:

1. `knd_avatar_items.sql` - Items catalog
2. `knd_user_avatar_inventory.sql` - User inventory (requires users)
3. `knd_user_avatar.sql` - User loadout (requires users)
4. `points_ledger_add_avatar_shop.sql` - Add 'avatar_shop' to points_ledger source_type enum
5. `knd_avatar_seed.sql` - Demo items (10 placeholder items)
