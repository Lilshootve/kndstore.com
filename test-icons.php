<?php
// Configuración de sesión ANTES de cargar config.php
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
    session_start();
} else {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'includes/footer.php';
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Iconos - KND Store</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #000;
            color: #fff;
            padding: 50px;
        }
        .icon-test {
            margin: 20px;
            padding: 20px;
            border: 2px solid #00bfff;
            border-radius: 10px;
        }
        .icon-test i {
            font-size: 2rem;
            color: #00bfff;
            margin-right: 10px;
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            background: #1a1a1a;
            border-radius: 10px;
        }
        .result {
            margin-top: 10px;
            padding: 10px;
            background: #2a2a2a;
            border-radius: 5px;
        }
        .success { color: #00ff00; }
        .error { color: #ff0000; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">🔍 Test de Iconos - Diagnóstico</h1>
        
        <!-- Prueba 1: Font Awesome Directo -->
        <div class="test-section">
            <h2>Prueba 1: Font Awesome Directo</h2>
            <div class="icon-test">
                <i class="fas fa-rocket"></i> Rocket
                <br>
                <i class="fas fa-gamepad"></i> Gamepad
                <br>
                <i class="fas fa-headset"></i> Headset
                <br>
                <i class="fas fa-code"></i> Code
                <br>
                <i class="fab fa-discord"></i> Discord
            </div>
            <div class="result" id="result1"></div>
        </div>
        
        <!-- Prueba 2: Símbolos Unicode -->
        <div class="test-section">
            <h2>Prueba 2: Símbolos Unicode</h2>
            <div class="icon-test">
                <span style="font-size: 2rem; color: #00bfff;">🚀</span> Emoji Rocket
                <br>
                <span style="font-size: 2rem; color: #00bfff;">▸</span> Triángulo
                <br>
                <span style="font-size: 2rem; color: #00bfff;">●</span> Círculo
                <br>
                <span style="font-size: 2rem; color: #00bfff;">◊</span> Diamante
                <br>
                <span style="font-size: 2rem; color: #00bfff;">◈</span> Estrella
            </div>
            <div class="result" id="result2"></div>
        </div>
        
        <!-- Prueba 3: Texto Simple -->
        <div class="test-section">
            <h2>Prueba 3: Texto Simple</h2>
            <div class="icon-test">
                <span style="font-size: 2rem; color: #00bfff;">[R]</span> Rocket
                <br>
                <span style="font-size: 2rem; color: #00bfff;">[G]</span> Game
                <br>
                <span style="font-size: 2rem; color: #00bfff;">[H]</span> Headset
                <br>
                <span style="font-size: 2rem; color: #00bfff;">[C]</span> Code
                <br>
                <span style="font-size: 2rem; color: #00bfff;">[D]</span> Discord
            </div>
            <div class="result" id="result3"></div>
        </div>
        
        <!-- Prueba 4: Detección de Soporte -->
        <div class="test-section">
            <h2>Prueba 4: Detección de Soporte</h2>
            <div class="result" id="detection-results">
                <div id="fa-detection"></div>
                <div id="emoji-detection"></div>
                <div id="unicode-detection"></div>
            </div>
        </div>
        
        <!-- Prueba 5: CSS Personalizado -->
        <div class="test-section">
            <h2>Prueba 5: Iconos con CSS Personalizado</h2>
            <div class="icon-test">
                <i class="fa-icon fa-icon-rocket"></i> Rocket Custom
                <br>
                <i class="fa-icon fa-icon-game"></i> Game Custom
            </div>
            <div class="result" id="result5"></div>
        </div>
        
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Diagnóstico JavaScript -->
    <script>
        console.log('🔍 Iniciando diagnóstico de iconos...');
        
        // Detectar Font Awesome
        function detectFontAwesome() {
            const testElement = document.createElement('i');
            testElement.className = 'fas fa-rocket';
            testElement.style.position = 'absolute';
            testElement.style.left = '-9999px';
            document.body.appendChild(testElement);
            
            setTimeout(() => {
                const computedStyle = window.getComputedStyle(testElement, '::before');
                const content = computedStyle.getPropertyValue('content');
                const hasContent = content && content !== 'none' && content !== 'normal' && content !== '';
                
                document.getElementById('fa-detection').innerHTML = 
                    '<strong>Font Awesome:</strong> ' + (hasContent ? '<span class="success">✓ Funcionando</span>' : '<span class="error">✗ No funciona</span>');
                
                document.body.removeChild(testElement);
            }, 500);
        }
        
        // Detectar emojis
        function detectEmoji() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            ctx.textBaseline = 'top';
            ctx.font = '32px Arial';
            ctx.fillText('🚀', 0, 0);
            const data = ctx.getImageData(16, 16, 1, 1).data;
            const works = data[0] !== 0 || data[1] !== 0 || data[2] !== 0;
            
            document.getElementById('emoji-detection').innerHTML = 
                '<strong>Emojis:</strong> ' + (works ? '<span class="success">✓ Soportados</span>' : '<span class="error">✗ No soportados</span>');
        }
        
        // Detectar Unicode
        function detectUnicode() {
            const works = '▸◊●◈'.split('').every(char => {
                const testDiv = document.createElement('div');
                testDiv.textContent = char;
                testDiv.style.position = 'absolute';
                testDiv.style.left = '-9999px';
                document.body.appendChild(testDiv);
                const width = testDiv.offsetWidth;
                document.body.removeChild(testDiv);
                return width > 0;
            });
            
            document.getElementById('unicode-detection').innerHTML = 
                '<strong>Símbolos Unicode:</strong> ' + (works ? '<span class="success">✓ Funcionan</span>' : '<span class="error">✗ No funcionan</span>');
        }
        
        // Ejecutar diagnóstico
        setTimeout(() => {
            detectFontAwesome();
            detectEmoji();
            detectUnicode();
        }, 1000);
        
        // Mostrar resultados en consola
        console.log('Navegador:', navigator.userAgent);
        console.log('Viewport:', window.innerWidth + 'x' + window.innerHeight);
        
        // CSS para iconos personalizados
        const style = document.createElement('style');
        style.textContent = `
            .fa-icon {
                font-family: monospace;
                font-size: 2rem;
                color: #00bfff;
            }
            .fa-icon-rocket::before { content: '[R]'; }
            .fa-icon-game::before { content: '[G]'; }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>

