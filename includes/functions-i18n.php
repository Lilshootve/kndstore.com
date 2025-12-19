<?php
// KND Store - i18n fallback (rescate)
// Esto evita fatal errors si faltan archivos de idioma.
// Luego lo mejoramos bien.

if (!function_exists('t')) {
    function t(string $key, $fallback = null, array $vars = []): string {
        // Detectar si $fallback es un array (compatibilidad con llamadas antiguas)
        if (is_array($fallback)) {
            // Si tiene claves 'es' o 'en', es un diccionario por idioma
            if (isset($fallback['es']) || isset($fallback['en'])) {
                $lang = current_lang();
                $text = $fallback[$lang] ?? $fallback['es'] ?? $fallback['en'] ?? $key;
            } else {
                // Si no tiene 'es'/'en, entonces es $vars y el fallback es null
                $vars = $fallback;
                $text = $key; // Usar la clave como texto base
            }
        } else {
            // $fallback es string/null normal
            $text = $fallback ?? $key;
        }

        // Reemplazo simple: {name} => valor
        foreach ($vars as $k => $v) {
            $text = str_replace('{' . $k . '}', (string)$v, $text);
        }
        
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('t_html')) {
    function t_html(string $key, $fallback = null, array $vars = []): string {
        // Detectar si $fallback es un array (compatibilidad con llamadas antiguas)
        if (is_array($fallback)) {
            // Si tiene claves 'es' o 'en', es un diccionario por idioma
            if (isset($fallback['es']) || isset($fallback['en'])) {
                $lang = current_lang();
                $text = $fallback[$lang] ?? $fallback['es'] ?? $fallback['en'] ?? $key;
            } else {
                // Si no tiene 'es'/'en, entonces es $vars y el fallback es null
                $vars = $fallback;
                $text = $key; // Usar la clave como texto base
            }
        } else {
            // $fallback es string/null normal
            $text = $fallback ?? $key;
        }

        // Reemplazo simple: {name} => valor
        foreach ($vars as $k => $v) {
            $text = str_replace('{' . $k . '}', htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), $text);
        }
        
        return $text; // Sin htmlspecialchars final para permitir HTML
    }
}

if (!function_exists('current_lang')) {
    function current_lang(): string {
        return 'es';
    }
}

if (!function_exists('__')) {
    function __(string $key, $fallback = null, array $vars = []): string {
        return t($key, $fallback, $vars);
    }
}
