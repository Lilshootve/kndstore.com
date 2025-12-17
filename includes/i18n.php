<?php
// KND Store - Sistema i18n ES/EN sin frameworks
//
// Responsable de:
// - Detectar el idioma actual (es/en) sin cambiar rutas.
// - Gestionar cookie knd_lang (1 año).
// - Cargar el diccionario desde includes/lang/{lang}.php.
// - Exponer helpers: current_lang(), t(), t_html().
//
// Cómo añadir nuevas claves:
// 1) Agrega la entrada en includes/lang/es.php y includes/lang/en.php
//    usando la misma clave en ambos archivos (ej: 'nav.home').
// 2) Usa t('nav.home') en tus plantillas PHP.
// 3) Para strings con HTML controlado (ej: textos legales), usa t_html().

if (!defined('KND_I18N_LOADED')) {
    define('KND_I18N_LOADED', true);

    /**
     * Idiomas soportados por el sitio.
     *
     * @var string[]
     */
    $KND_SUPPORTED_LANGS = ['es', 'en'];

    /**
     * Detectar idioma desde Accept-Language (sólo es/en).
     */
    function knd_detect_lang_from_header(array $supported): ?string
    {
        if (empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return null;
        }

        $header = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        // Ejemplos: "es-ES,es;q=0.9,en;q=0.8"
        $parts = explode(',', $header);
        foreach ($parts as $part) {
            $sub = trim($part);
            if ($sub === '') {
                continue;
            }

            // Quitar parámetros ;q=...
            $sub = explode(';', $sub)[0];
            $primary = substr($sub, 0, 2);

            if (in_array($primary, $supported, true)) {
                return $primary;
            }
        }

        return null;
    }

    /**
     * Resolver idioma actual siguiendo la prioridad:
     * 1) ?lang=es|en
     * 2) Cookie knd_lang
     * 3) Accept-Language
     * 4) Default: es
     */
    function knd_resolve_lang(array $supported): string
    {
        // 1) Query string
        if (isset($_GET['lang'])) {
            $param = strtolower((string) $_GET['lang']);
            if (in_array($param, $supported, true)) {
                return $param;
            }
        }

        // 2) Cookie
        if (isset($_COOKIE['knd_lang'])) {
            $cookieLang = strtolower((string) $_COOKIE['knd_lang']);
            if (in_array($cookieLang, $supported, true)) {
                return $cookieLang;
            }
        }

        // 3) Accept-Language
        $headerLang = knd_detect_lang_from_header($supported);
        if ($headerLang !== null) {
            return $headerLang;
        }

        // 4) Default
        return 'es';
    }

    /**
     * Cargar diccionario del idioma dado desde includes/lang/{lang}.php.
     */
    function knd_load_dictionary(string $lang): array
    {
        $file = __DIR__ . '/lang/' . $lang . '.php';
        if (file_exists($file)) {
            $data = include $file;
            if (is_array($data)) {
                return $data;
            }
        }
        return [];
    }

    // Resolver idioma actual y configurar cookie
    $CURRENT_LANG = knd_resolve_lang($KND_SUPPORTED_LANGS);

    // Guardar cookie de idioma (1 año) si es necesario
    if (!headers_sent()) {
        $cookieLang = isset($_COOKIE['knd_lang']) ? strtolower((string) $_COOKIE['knd_lang']) : null;
        if ($cookieLang !== $CURRENT_LANG) {
            // Cookie accesible en todo el dominio
            setcookie('knd_lang', $CURRENT_LANG, time() + 365 * 24 * 60 * 60, '/');
            $_COOKIE['knd_lang'] = $CURRENT_LANG;
        }
    }

    // Cargar diccionario global
    $I18N = knd_load_dictionary($CURRENT_LANG);

    /**
     * Idioma actual (es/en).
     */
    function current_lang(): string
    {
        global $CURRENT_LANG;
        return $CURRENT_LANG ?: 'es';
    }

    /**
     * Obtener traducción para una clave con interpolación segura.
     *
     * @param string $key
     * @param array<string, scalar> $vars
     * @param string $fallback
     * @return string
     */
    function t(string $key, array $vars = [], string $fallback = ''): string
    {
        global $I18N;
        // $isLocal viene de config.php; puede no existir todavía si se usa t()
        // antes de cargar config, por eso lo leemos de $GLOBALS.
        $isLocal = $GLOBALS['isLocal'] ?? false;

        $value = isset($I18N[$key]) ? (string) $I18N[$key] : null;

        if ($value === null) {
            $value = $fallback !== '' ? $fallback : $key;
            if ($isLocal) {
                // Log sólo en entorno local/desarrollo
                error_log('[i18n] Missing translation: ' . $key . ' for lang=' . current_lang());
            }
        }

        // Interpolación simple {var}
        if (!empty($vars)) {
            foreach ($vars as $name => $rawVal) {
                $safeVal = htmlspecialchars((string) $rawVal, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $value = str_replace('{' . $name . '}', $safeVal, $value);
            }
        }

        // Escapamos resultado completo para HTML
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Versión que permite HTML en la cadena de traducción.
     * Úsala sólo con contenido de confianza (por ejemplo, textos legales).
     */
    function t_html(string $key, array $vars = [], string $fallback = ''): string
    {
        global $I18N;
        $isLocal = $GLOBALS['isLocal'] ?? false;

        $value = isset($I18N[$key]) ? (string) $I18N[$key] : null;

        if ($value === null) {
            $value = $fallback !== '' ? $fallback : $key;
            if ($isLocal) {
                error_log('[i18n] Missing HTML translation: ' . $key . ' for lang=' . current_lang());
            }
        }

        if (!empty($vars)) {
            foreach ($vars as $name => $rawVal) {
                $safeVal = htmlspecialchars((string) $rawVal, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $value = str_replace('{' . $name . '}', $safeVal, $value);
            }
        }

        return $value;
    }
}

