<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
} catch (Exception $e) {
    echo "Error en config.php: " . $e->getMessage() . "<br>";
    die();
}

try {
    echo "Test 4: Probando función t()<br>";
    $test = t('test.key', [], 'Test funciona');
    echo "Resultado: " . $test . "<br>";
} catch (Exception $e) {
    echo "Error en t(): " . $e->getMessage() . "<br>";
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
