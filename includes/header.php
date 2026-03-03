<?php
// Header común de KND Store

require_once __DIR__ . '/config.php';

function generateHeader($title = 'KND Store - Tienda Galáctica', $description = 'Tienda digital de servicios y productos tecnológicos con temática gamer y cósmica', $extraHead = '') {
    $current_page = basename($_SERVER['PHP_SELF']);

    $favicon = generateFaviconLinks();

    $header = '<!DOCTYPE html>' . "\n";
    $header .= '<html lang="en" data-bs-theme="dark">' . "\n";
    $header .= '<head>' . "\n";
    $header .= '    <meta charset="UTF-8">' . "\n";
    $header .= '    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">' . "\n";
    $header .= '    <title>' . htmlspecialchars($title) . '</title>' . "\n";
    $header .= '    <meta name="description" content="' . htmlspecialchars($description) . '">' . "\n";
    $header .= '    <meta name="robots" content="index, follow">' . "\n";
    $header .= '    <meta name="theme-color" content="#259cae">' . "\n";
    $header .= '    <meta name="author" content="KND Store">' . "\n";
    $header .= '    <meta name="keywords" content="knd, store, gaming, technology, digital services, apparel, streetwear, ecommerce">' . "\n";
    if ($extraHead) {
        $header .= $extraHead;
    }
    $header .= $favicon;
    $header .= '    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">' . "\n";
    $header .= '    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css?v=652">' . "\n";
    $header .= '    <link rel="stylesheet" href="/assets/css/style.css?v=' . @filemtime(__DIR__ . '/../assets/css/style.css') . '">' . "\n";
    $header .= '    <link rel="stylesheet" href="/assets/css/mobile-optimization.css">' . "\n";
    $header .= '    <link rel="manifest" href="/assets/images/site.webmanifest">' . "\n";
    $header .= '</head>' . "\n";
    $header .= '<body>' . "\n";

    return $header;
}

function generateNavigation() {
    $current_page = basename($_SERVER['PHP_SELF']);

    $nav = '<nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top">' . "\n";
    $nav .= '    <div class="container">' . "\n";
    $nav .= '        <a class="navbar-brand d-flex align-items-center" href="/index.php">' . "\n";
    $nav .= '            <img src="/assets/images/logo.png" alt="KND Store" height="100" class="me-2">' . "\n";
    $nav .= '        </a>' . "\n";
    $nav .= '        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">' . "\n";
    $nav .= '            <span class="navbar-toggler-icon"></span>' . "\n";
    $nav .= '        </button>' . "\n";
    $nav .= '        <div class="collapse navbar-collapse" id="navbarNav">' . "\n";
    $nav .= '            <ul class="navbar-nav ms-auto">' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'index.php' ? ' active' : '') . '" href="/index.php">' . t('nav.home') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'products.php' ? ' active' : '') . '" href="/products.php">' . t('nav.services') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'apparel.php' ? ' active' : '') . '" href="/apparel.php">' . t('nav.apparel') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'about.php' ? ' active' : '') . '" href="/about.php">' . t('nav.about') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'contact.php' ? ' active' : '') . '" href="/contact.php">' . t('nav.contact') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    // KND Arena hub link
    $arenaActive = in_array($current_page, ['knd-arena.php', 'death-roll-lobby.php', 'death-roll-game.php', 'above-under.php', 'leaderboard.php']);
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($arenaActive ? ' active' : '') . '" href="/knd-arena.php"><i class="fas fa-gamepad me-1"></i>' . t('nav.arena', 'KND Arena') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    // My Account dropdown
    $drLoggedIn = !empty($_SESSION['dr_user_id']);
    $drUsername = $drLoggedIn ? htmlspecialchars($_SESSION['dr_username'] ?? '') : '';
    $scActive = in_array($current_page, ['support-credits.php', 'rewards.php']);
    $accountActive = in_array($current_page, ['order.php', 'track-order.php', 'auth.php', 'support-credits.php', 'rewards.php', 'my-profile.php']);

    // Credits badge (cached 60s) + Level badge (cached 60s)
    $creditsBadgeHtml = '';
    $levelBadgeHtml = '';
    if ($drLoggedIn) {
        $scCacheTTL = 60;
        $scCache = $_SESSION['sc_badge_cache'] ?? null;
        $scAvailable = 0;
        if ($scCache && isset($scCache['ts']) && (time() - $scCache['ts']) < $scCacheTTL) {
            $scAvailable = (int) $scCache['available'];
        } else {
            try {
                $scIncPath = __DIR__ . '/support_credits.php';
                if (file_exists($scIncPath)) {
                    require_once $scIncPath;
                    $scPdo = getDBConnection();
                    if ($scPdo) {
                        $scUid = (int) $_SESSION['dr_user_id'];
                        release_available_points_if_due($scPdo, $scUid);
                        expire_points_if_due($scPdo, $scUid);
                        $scAvailable = get_available_points($scPdo, $scUid);
                    }
                }
            } catch (\Throwable $e) {
                $scAvailable = 0;
            }
            $_SESSION['sc_badge_cache'] = ['ts' => time(), 'available' => $scAvailable];
        }
        $creditsBadgeHtml = '<a href="/support-credits.php" class="sc-nav-badge" title="' . htmlspecialchars(t('nav.credits_badge_tooltip', 'Available KND Points')) . '">'
            . '<i class="fas fa-coins"></i> ' . ($scAvailable > 0 ? number_format($scAvailable) : '0')
            . '</a>';

        // Level badge (cached 60s via get_xp_badge_data)
        try {
            $profilePath = __DIR__ . '/knd_profile.php';
            if (file_exists($profilePath)) {
                require_once $profilePath;
                $xpPdo = getDBConnection();
                if ($xpPdo) {
                    $xb = get_xp_badge_data($xpPdo, (int) $_SESSION['dr_user_id']);
                    $tip = t('nav.level', 'Level') . ': ' . $xb['level'] . '/30 · XP: ' . number_format($xb['xp']);
                    if ($xb['is_max']) {
                        $tip .= ' · ' . t('profile.max_level', 'MAX LEVEL');
                    } else {
                        $tip .= ' · ' . t('profile.next', 'XP to next level') . ': ' . number_format($xb['next_in']) . ' · ' . t('nav.xp_progress', 'Progress') . ': ' . $xb['pct'] . '%';
                    }
                    $levelBadgeHtml = '<a href="/my-profile.php" class="lvl-badge" title="' . htmlspecialchars($tip) . '">Lv ' . $xb['level'] . '</a>';
                }
            }
        } catch (\Throwable $e) {
            $levelBadgeHtml = '';
        }
    }

    $nav .= '                <li class="nav-item knd-dropdown">' . "\n";
    $nav .= '                    <a id="ordersDropdownToggle" class="nav-link knd-dropdown-toggle' . ($accountActive ? ' active' : '') . '" href="javascript:void(0)" role="button" aria-expanded="false">' . "\n";
    $nav .= '                        <i class="fas fa-user-circle me-1"></i>' . "\n";
    if ($drLoggedIn) {
        $nav .= '                        ' . $drUsername . "\n";
    } else {
        $nav .= '                        ' . t('nav.my_account', 'My Account') . "\n";
    }
    $nav .= '                        <span id="order-count" class="badge rounded-pill bg-primary ms-1" style="display:none; min-width: 20px; justify-content: center; align-items: center;"></span>' . "\n";
    if ($levelBadgeHtml) {
        $nav .= '                        ' . $levelBadgeHtml . "\n";
    }
    if ($creditsBadgeHtml) {
        $nav .= '                        ' . $creditsBadgeHtml . "\n";
    }
    $nav .= '                        <i class="fas fa-chevron-down knd-dropdown-arrow ms-1"></i>' . "\n";
    $nav .= '                    </a>' . "\n";
    $nav .= '                    <div id="ordersDropdownMenu" class="knd-dropdown-menu">' . "\n";
    $nav .= '                        <span class="knd-dropdown-hint">' . t('nav.orders_hint', 'Orders & account') . '</span>' . "\n";
    $nav .= '                        <a class="knd-dropdown-item' . ($current_page == 'order.php' ? ' active' : '') . '" href="/order.php"><i class="fas fa-cart-shopping me-2"></i>' . t('nav.my_orders', 'My Orders') . '</a>' . "\n";
    $nav .= '                        <a class="knd-dropdown-item' . ($current_page == 'track-order.php' ? ' active' : '') . '" href="/track-order.php"><i class="fas fa-magnifying-glass me-2"></i>' . t('nav.track_order', 'Track Order') . '</a>' . "\n";
    $nav .= '                        <a class="knd-dropdown-item" href="/contact.php"><i class="fas fa-headset me-2"></i>' . t('nav.support', 'Support') . '</a>' . "\n";
    $nav .= '                        <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 6px 0;"></div>' . "\n";
    if ($drLoggedIn) {
        $nav .= '                        <a class="knd-dropdown-item' . ($current_page == 'my-profile.php' ? ' active' : '') . '" href="/my-profile.php"><i class="fas fa-user-shield me-2"></i>' . t('nav.profile', 'My Profile') . '</a>' . "\n";
        $nav .= '                        <a class="knd-dropdown-item' . ($current_page == 'support-credits.php' ? ' active' : '') . '" href="/support-credits.php"><i class="fas fa-coins me-2"></i>' . t('nav.credits', 'Credits') . '</a>' . "\n";
        $nav .= '                        <a class="knd-dropdown-item' . ($current_page == 'rewards.php' ? ' active' : '') . '" href="/rewards.php"><i class="fas fa-gift me-2"></i>' . t('nav.rewards', 'Rewards') . '</a>' . "\n";
        $nav .= '                        <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 6px 0;"></div>' . "\n";
    }
    if ($drLoggedIn) {
        $nav .= '                        <a class="knd-dropdown-item" href="/logout.php"><i class="fas fa-sign-out-alt me-2"></i>' . t('nav.logout', 'Logout') . '</a>' . "\n";
    } else {
        $nav .= '                        <a class="knd-dropdown-item' . ($current_page == 'auth.php' ? ' active' : '') . '" href="/auth.php"><i class="fas fa-sign-in-alt me-2"></i>' . t('nav.login', 'Login') . ' / ' . t('nav.register', 'Register') . '</a>' . "\n";
    }
    $nav .= '                    </div>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '            </ul>' . "\n";
    $nav .= '        </div>' . "\n";
    $nav .= '    </div>' . "\n";
    $nav .= '</nav>' . "\n";

    return $nav;
}

// Función para generar el panel de personalización de colores
function generateColorPanel() {
    $panel = '<!-- Panel de Personalización de Colores -->' . "\n";
    $panel .= '<div class="color-panel-toggle" id="colorPanelToggle">' . "\n";
    $panel .= '    <i class="fas fa-palette"></i>' . "\n";
    $panel .= '</div>' . "\n";
    $panel .= '<div class="color-panel-overlay" id="colorPanelOverlay"></div>' . "\n";
    $panel .= '<div class="color-panel-sidebar" id="colorPanelSidebar">' . "\n";
    $panel .= '    <div class="color-panel-header">' . "\n";
    $panel .= '        <h3><i class="fas fa-magic me-2"></i>Customize Colors</h3>' . "\n";
    $panel .= '    </div>' . "\n";
    $panel .= '    <div class="color-panel-content">' . "\n";
    $panel .= '        <div class="color-theme active" data-theme="galactic-blue">' . "\n";
    $panel .= '            <h4>Galactic Blue</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #259cae;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ae2565;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #16213e;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Classic neon blue with electric purple.</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        <div class="color-theme" data-theme="cyber-green">' . "\n";
    $panel .= '            <h4>Cyber Green</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #66bf5a;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #70c4e1;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #69bab0;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Organic tech with a hacker-futurist vibe.</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        <div class="color-theme" data-theme="nature-green">' . "\n";
    $panel .= '            <h4>Nature Green</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #c1eeaf;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #6ba166;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #145926;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Organic green, balanced and calm.</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        <div class="color-theme" data-theme="fire-red">' . "\n";
    $panel .= '            <h4>Solar Fire</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #b43b6a;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #e67635;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #bfce17;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Intense energy with solar and neon accents.</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        <div class="color-theme" data-theme="golden-sun">' . "\n";
    $panel .= '            <h4>Golden Sun</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ffea00;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #bed322;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #321f22;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Cosmic elegance, solar luxury, deep contrast.</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        <div class="color-theme" data-theme="neon-pink">' . "\n";
    $panel .= '            <h4>Neon Pearl</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #dca1e3;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ffc3a8;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #e6ffc9;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Soft tones, elegant futurism, premium look.</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        <div class="color-theme" data-theme="ice-blue">' . "\n";
    $panel .= '            <h4>Ice Blue</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #07eef2;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #24d2db;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #000000;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Cyan blue for a glacial feel.</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '    </div>' . "\n";
    $panel .= '</div>' . "\n";

    return $panel;
}

// Función para generar los favicons y meta tags relacionados
function generateFaviconLinks() {
    $favicon  = '<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">' . "\n";
    $favicon .= '<link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-96x96.png">' . "\n";
    $favicon .= '<link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-96x96.png">' . "\n";
    $favicon .= '<link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">' . "\n";
    $favicon .= '<link rel="shortcut icon" href="/assets/images/favicon.ico">' . "\n";
    $favicon .= '<meta name="msapplication-TileColor" content="#259cae">' . "\n";
    $favicon .= '<meta name="msapplication-config" content="/assets/images/browserconfig.xml">' . "\n";
    $favicon .= '<meta name="theme-color" content="#259cae">' . "\n";

    return $favicon;
}

?>
