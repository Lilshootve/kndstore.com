# AGENTS.md

## Cursor Cloud specific instructions

### Overview

KND Store is a PHP web application (no framework) with a MySQL/MariaDB database, served by Apache. It features a gaming arena (KND Arena) with several game modes, an AI tools hub, user auth, i18n (ES/EN), and an admin panel.

### Services

| Service | How to start | Port |
|---------|-------------|------|
| MariaDB | `sudo mariadbd --user=mysql --datadir=/var/lib/mysql --pid-file=/run/mysqld/mysqld.pid --socket=/run/mysqld/mysqld.sock &` | 3306 |
| Apache (HTTP+HTTPS) | `sudo apachectl start` | 80, 443 |

### Key caveats

- **`includes/config.php` is not versioned.** It must be created manually with DB credentials, `getDBConnection()`, `startPerformanceTimer()`, `endPerformanceTimer()`, `setCacheHeaders()`, and `isLoggedIn()` functions. See `README.md` for the skeleton. It must load `includes/functions-i18n.php` (not `includes/i18n.php`) for proper translations.
- **Do NOT include `includes/json.php` or `includes/csrf.php` from `config.php`** — the file `includes/deathroll_1v1.php` re-declares `json_success`/`json_error` without `function_exists` guards, so loading `json.php` globally causes fatal errors. API endpoints load these as needed.
- **HTTPS is required:** The root `.htaccess` redirects HTTP to HTTPS. Apache needs a self-signed cert for local dev: `sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/localhost.key -out /etc/ssl/certs/localhost.crt -subj "/CN=localhost"`.
- **SQL import order matters:** `knd_drop_seasons.sql` must be imported before `knd_drop_configs.sql` and `knd_drops.sql` due to foreign key constraints. `apparel_services_migration.sql` references a `products` table that doesn't exist in the repo schemas (safe to skip).
- **PHP constant warning:** `knd_profile.php` re-defines `XP_MAX_LEVEL`, causing a non-fatal warning banner. This is cosmetic and does not break functionality.

### Lint / Test / Build

- **Lint:** `find /workspace -name "*.php" -not -path "*/vendor/*" | xargs -n1 php -l` — all 147+ files should pass.
- **No automated test suite** exists in this project. Functional testing is done by interacting with the site (register, login, play games, etc.).
- **No build step** — PHP is interpreted; just serve the repo root via Apache.

### Optional services (not required for core functionality)

- **comfy-router** (Python/FastAPI) — GPU image generation router in `/workspace/comfy-router/`. Requires a ComfyUI backend.
- **gpu-server** (Python/FastAPI) — 3D mesh generation in `/workspace/gpu-server/`. Requires NVIDIA GPU.
- Both are optional AI features that most dev workflows do not need.
