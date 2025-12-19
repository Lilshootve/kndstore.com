<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Capturar errores fatales
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "<br><strong>ERROR FATAL:</strong> " . $error['message'] . "<br>";
        echo "Archivo: " . $error['file'] . "<br>";
        echo "Línea: " . $error['line'] . "<br>";
    }
});

echo "Test 1: PHP funciona<br>";

try {
    require_once __DIR__ . '/includes/session.php';
    echo "Test 2: session.php cargado<br>";
} catch (Exception $e) {
    echo "Error en session.php: " . $e->getMessage() . "<br>";
    die();
}

try {
    require_once __DIR__ . '/includes/config.php';
    echo "Test 3: config.php cargado<br>";
    echo "Test 3.1: Verificando si function_exists está disponible<br>";
    if (function_exists('function_exists')) {
        echo "function_exists() está disponible<br>";
    }
    echo "Test 3.2: Verificando si podemos evaluar !function_exists('t')<br>";
    $test_exists = !function_exists('t');
    echo "Resultado de !function_exists('t'): " . ($test_exists ? 'true' : 'false') . "<br>";
} catch (Exception $e) {
    echo "Error en config.php: " . $e->getMessage() . "<br>";
    die();
} catch (Error $e) {
    echo "Error fatal en config.php: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Línea: " . $e->getLine() . "<br>";
    die();
}

try {
    echo "Test 4: Verificando si función t() existe<br>";
    if (function_exists('t')) {
        echo "Función t() existe<br>";
        echo "Test 4.1: Probando función t() con array()<br>";
        $test = t('test.key', array(), 'Test funciona');
        echo "Resultado: " . $test . "<br>";
    } else {
        echo "ERROR: Función t() NO existe<br>";
        die();
    }
} catch (Exception $e) {
    echo "Error en t(): " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
    die();
} catch (Error $e) {
    echo "Error fatal en t(): " . $e->getMessage() . "<br>";
    echo "Stack trace: " . $e->getTraceAsString() . "<br>";
    die();
}

try {
    require_once __DIR__ . '/includes/header.php';
    echo "Test 5: header.php cargado<br>";
} catch (Exception $e) {
    echo "Error en header.php: " . $e->getMessage() . "<br>";
    die();
}

echo "Todos los tests pasaron!<br>";
?>
