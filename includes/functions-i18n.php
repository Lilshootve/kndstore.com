<?php
// Funciones stub de i18n - definidas en archivo separado para asegurar que se carguen

function t($key = '', $vars = array(), $fallback = '') {
    $key = (string)$key;
    if (!is_array($vars)) {
        $vars = array();
    }
    $fallback = (string)$fallback;
    
    if ($fallback !== '') {
        $value = $fallback;
    } elseif ($key !== '') {
        $parts = explode('.', $key);
        $value = end($parts);
        $value = ucfirst(str_replace('_', ' ', $value));
    } else {
        $value = '';
    }
    
    if (!empty($vars)) {
        foreach ($vars as $name => $rawVal) {
            $safeVal = htmlspecialchars((string) $rawVal, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $value = str_replace('{' . $name . '}', $safeVal, $value);
        }
    }
    
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function t_html($key = '', $vars = array(), $fallback = '') {
    $key = (string)$key;
    if (!is_array($vars)) {
        $vars = array();
    }
    $fallback = (string)$fallback;
    
    if ($fallback !== '') {
        $value = $fallback;
    } elseif ($key !== '') {
        $parts = explode('.', $key);
        $value = end($parts);
        $value = ucfirst(str_replace('_', ' ', $value));
    } else {
        $value = '';
    }
    
    if (!empty($vars)) {
        foreach ($vars as $name => $rawVal) {
            $safeVal = htmlspecialchars((string) $rawVal, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $value = str_replace('{' . $name . '}', $safeVal, $value);
        }
    }
    
    return $value;
}

function current_lang() {
    return 'es';
}
