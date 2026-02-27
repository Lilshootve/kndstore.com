<?php
// Header común de KND Store

require_once __DIR__ . '/config.php';

function generateHeader($title = 'KND Store - Tienda Galáctica', $description = 'Tienda digital de servicios y productos tecnológicos con temática gamer y cósmica') {
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
    $header .= $favicon;
    $header .= '    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">' . "\n";
    $header .= '    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">' . "\n";
    $header .= '    <link rel="stylesheet" href="/assets/css/style.css">' . "\n";
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
    $nav .= '                    <a class="nav-link' . ($current_page == 'creative.php' ? ' active' : '') . '" href="/creative.php">' . t('nav.creative') . '</a>' . "\n";
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
    $ordersActive = in_array($current_page, ['order.php', 'track-order.php']);
    $nav .= '                <li class="nav-item knd-dropdown">' . "\n";
    $nav .= '                    <a class="nav-link knd-dropdown-toggle' . ($ordersActive ? ' active' : '') . '" href="javascript:void(0)" role="button" aria-expanded="false">' . "\n";
    $nav .= '                        <i class="fas fa-shopping-cart me-1"></i>' . "\n";
    $nav .= '                        ' . t('nav.orders') . "\n";
    $nav .= '                        <span id="order-count" class="badge rounded-pill bg-primary ms-1" style="display:none; min-width: 20px; justify-content: center; align-items: center;"></span>' . "\n";
    $nav .= '                        <i class="fas fa-chevron-down knd-dropdown-arrow ms-1"></i>' . "\n";
    $nav .= '                    </a>' . "\n";
    $nav .= '                    <div class="knd-dropdown-menu">' . "\n";
    $nav .= '                        <span class="knd-dropdown-hint">View &amp; track purchases</span>' . "\n";
    $nav .= '                        <a class="knd-dropdown-item' . ($current_page == 'order.php' ? ' active' : '') . '" href="/order.php"><i class="fas fa-cart-shopping me-2"></i>My Orders</a>' . "\n";
    $nav .= '                        <a class="knd-dropdown-item' . ($current_page == 'track-order.php' ? ' active' : '') . '" href="/track-order.php"><i class="fas fa-magnifying-glass me-2"></i>Track Order</a>' . "\n";
    $nav .= '                        <a class="knd-dropdown-item" href="/contact.php"><i class="fas fa-headset me-2"></i>Support</a>' . "\n";
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
