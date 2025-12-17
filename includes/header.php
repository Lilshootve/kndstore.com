<?php
// Header común de KND Store

require_once __DIR__ . '/config.php';

function generateHeader($title = null, $description = null) {
    $current_page = basename($_SERVER['PHP_SELF']);

    // Usar traducciones por defecto si no se pasan parámetros
    if ($title === null) {
        $title = t('meta.default_title');
    }
    if ($description === null) {
        $description = t('meta.default_description');
    }

    $favicon = generateFaviconLinks();
    $lang = current_lang();

    $header = '<!DOCTYPE html>' . "\n";
    $header .= '<html lang="' . htmlspecialchars($lang) . '" data-bs-theme="dark">' . "\n";
    $header .= '<head>' . "\n";
    $header .= '    <meta charset="UTF-8">' . "\n";
    $header .= '    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">' . "\n";
    $header .= '    <title>' . htmlspecialchars($title) . '</title>' . "\n";
    $header .= '    <meta name="description" content="' . htmlspecialchars($description) . '">' . "\n";
    $header .= '    <meta name="robots" content="index, follow">' . "\n";
    $header .= '    <meta name="theme-color" content="#259cae">' . "\n";
    $header .= '    <meta name="author" content="KND Store">' . "\n";
    $header .= '    <meta name="keywords" content="knd, store, tienda, gamer, tecnología, servicios digitales, apparel, gaming">' . "\n";
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
    $current_lang = current_lang();

    // Helper para construir URL con lang preservando otros query params
    $buildLangUrl = function($lang) use ($current_page) {
        $params = $_GET;
        $params['lang'] = $lang;
        $query = http_build_query($params);
        return '/' . $current_page . '?' . $query;
    };

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
    $nav .= '                    <a class="nav-link' . ($current_page == 'products.php' ? ' active' : '') . '" href="/products.php">' . t('nav.catalog') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'about.php' ? ' active' : '') . '" href="/about.php">' . t('nav.about') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'contact.php' ? ' active' : '') . '" href="/contact.php">' . t('nav.contact') . '</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'order.php' ? ' active' : '') . '" href="/order.php">' . "\n";
    $nav .= '                        <i class="fas fa-shopping-cart me-1"></i>' . "\n";
    $nav .= '                        ' . t('nav.order') . "\n";
    $nav .= '                        <span id="order-count" class="badge rounded-pill bg-primary ms-1" style="display:none; min-width: 20px; justify-content: center; align-items: center;"></span>' . "\n";
    $nav .= '                    </a>' . "\n";
    $nav .= '                </li>' . "\n";
    // Selector de idioma ES/EN
    $nav .= '                <li class="nav-item ms-3 d-flex align-items-center">' . "\n";
    $nav .= '                    <div class="btn-group" role="group">' . "\n";
    $nav .= '                        <a href="' . htmlspecialchars($buildLangUrl('es')) . '" class="btn btn-sm ' . ($current_lang === 'es' ? 'btn-neon-primary' : 'btn-outline-light') . '">' . t('nav.language.es') . '</a>' . "\n";
    $nav .= '                        <a href="' . htmlspecialchars($buildLangUrl('en')) . '" class="btn btn-sm ' . ($current_lang === 'en' ? 'btn-neon-primary' : 'btn-outline-light') . '">' . t('nav.language.en') . '</a>' . "\n";
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
    $panel .= '' . "\n";
    $panel .= '<div class="color-panel-overlay" id="colorPanelOverlay"></div>' . "\n";
    $panel .= '' . "\n";
    $panel .= '<div class="color-panel-sidebar" id="colorPanelSidebar">' . "\n";
    $panel .= '    <div class="color-panel-header">' . "\n";
    $panel .= '        <h3><i class="fas fa-magic me-2"></i>' . t('color_panel.title') . '</h3>' . "\n";
    $panel .= '    </div>' . "\n";
    $panel .= '    <div class="color-panel-content">' . "\n";
    $panel .= '        <div class="color-theme active" data-theme="galactic-blue">' . "\n";
    $panel .= '            <h4>' . t('color_panel.theme.galactic_blue') . '</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #259cae;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ae2565;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #16213e;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>' . t('color_panel.theme.galactic_blue_desc') . '</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="cyber-green">' . "\n";
    $panel .= '            <h4>' . t('color_panel.theme.cyber_green') . '</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #66bf5a;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #70c4e1;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #69bab0;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>' . t('color_panel.theme.cyber_green_desc') . '</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="nature-green">' . "\n";
    $panel .= '            <h4>' . t('color_panel.theme.nature_green') . '</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #c1eeaf;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #6ba166;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #145926;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>' . t('color_panel.theme.nature_green_desc') . '</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="fire-red">' . "\n";
    $panel .= '            <h4>' . t('color_panel.theme.fire_red') . '</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #b43b6a;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #e67635;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #bfce17;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>' . t('color_panel.theme.fire_red_desc') . '</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="golden-sun">' . "\n";
    $panel .= '            <h4>' . t('color_panel.theme.golden_sun') . '</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ffea00;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #bed322;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #321f22;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>' . t('color_panel.theme.golden_sun_desc') . '</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="neon-pink">' . "\n";
    $panel .= '            <h4>' . t('color_panel.theme.neon_pink') . '</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #dca1e3;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ffc3a8;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #e6ffc9;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>' . t('color_panel.theme.neon_pink_desc') . '</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="ice-blue">' . "\n";
    $panel .= '            <h4>' . t('color_panel.theme.ice_blue') . '</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #07eef2;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #24d2db;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #000000;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>' . t('color_panel.theme.ice_blue_desc') . '</p>' . "\n";
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
