<?php
// KND Store - Header común

// Función para obtener el título de la página
function getPageTitle($title = '') {
    $baseTitle = 'KND Store - Tu Tienda Galáctica';
    return $title ? "$title - $baseTitle" : $baseTitle;
}

// Función para obtener la URL base
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    return "$protocol://$host$path";
}

// Función para verificar si es la página actual
function isCurrentPage($page) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return $currentPage === $page;
}

// Función para generar meta tags
function generateMetaTags($title = '', $description = '', $keywords = '') {
    $defaultDescription = 'KND Store (Knowledge ‘N Development): tu tienda galáctica de servicios digitales, gaming y tecnología de vanguardia, impulsada por conocimiento y desarrollo constante.';
    $defaultKeywords = 'KND Store, tienda galáctica, productos únicos, tecnología, vanguardia';
    
    $meta = '';
    $meta .= '<meta charset="UTF-8">' . "\n";
    $meta .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">' . "\n";
    $meta .= '<meta name="description" content="' . ($description ?: $defaultDescription) . '">' . "\n";
    $meta .= '<meta name="keywords" content="' . ($keywords ?: $defaultKeywords) . '">' . "\n";
    $meta .= '<meta name="author" content="KND Store">' . "\n";
    $meta .= '<meta name="robots" content="index, follow">' . "\n";
    
    // Open Graph tags
    $meta .= '<meta property="og:title" content="' . getPageTitle($title) . '">' . "\n";
    $meta .= '<meta property="og:description" content="' . ($description ?: $defaultDescription) . '">' . "\n";
    $meta .= '<meta property="og:type" content="website">' . "\n";
    $meta .= '<meta property="og:url" content="' . getBaseUrl() . '">' . "\n";
    $meta .= '<meta property="og:site_name" content="KND Store">' . "\n";
    
    return $meta;
}

// Función para generar los enlaces CSS y JS comunes
function generateCommonAssets() {
    $assets = '';
    
    // Google Fonts con preload
    $assets .= '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@400;600&display=swap" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    $assets .= '<noscript><link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@400;600&display=swap" rel="stylesheet"></noscript>' . "\n";
    
    // Bootstrap CSS con preload
    $assets .= '<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    $assets .= '<noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></noscript>' . "\n";
    
    // Detectar si estamos en producción (Hostinger)
    $isProduction = (strpos($_SERVER['HTTP_HOST'], 'kndstore.com') !== false || 
                    strpos($_SERVER['HTTP_HOST'], 'hstgr.io') !== false);
    
    if ($isProduction) {
        // En producción, usar solo CDN más confiable y fallbacks locales
        $assets .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">' . "\n";
        
        // Script de fallback robusto para producción
        $assets .= '<script>
            // Fallback robusto para producción (Hostinger)
            (function() {
                // NO aplicar fallback inmediatamente - solo después de verificar
                
                // Los fallbacks se manejan automáticamente con CSS cuando la clase fontawesome-fallback está activa
                
                function checkFontAwesome() {
                    if (!document.body) {
                        // Esperar a que el body esté disponible
                        if (document.readyState === "loading") {
                            document.addEventListener("DOMContentLoaded", checkFontAwesome);
                        } else {
                            window.addEventListener("load", checkFontAwesome);
                        }
                        return;
                    }
                    
                    const testElement = document.createElement("i");
                    testElement.className = "fas fa-rocket";
                    testElement.style.position = "absolute";
                    testElement.style.left = "-9999px";
                    testElement.style.fontSize = "1px";
                    document.body.appendChild(testElement);
                    
                    setTimeout(() => {
                        if (!document.body) return;
                        
                        const computedStyle = window.getComputedStyle(testElement, "::before");
                        const content = computedStyle.getPropertyValue("content");
                        
                        if (document.body && testElement.parentNode === document.body) {
                        document.body.removeChild(testElement);
                        }
                        
                        // Verificar si realmente está cargado
                        const isLoaded = content && content !== "none" && content !== "normal" && content !== "" && content.length > 0;
                        
                        if (!isLoaded) {
                            console.warn("Font Awesome no detectado, aplicando fallbacks...");
                            if (document.body && !document.body.classList.contains("fontawesome-fallback")) {
                            document.body.classList.add("fontawesome-fallback");
                            }
                        } else {
                            console.log("Font Awesome detectado correctamente");
                            // Asegurar que NO esté la clase de fallback si Font Awesome funciona
                            if (document.body && document.body.classList.contains("fontawesome-fallback")) {
                                document.body.classList.remove("fontawesome-fallback");
                            }
                        }
                    }, 1500);
                }
                
                // Deshabilitado temporalmente - causa error de sintaxis
                // const emojiSupported = true;
                const emojiSupported = false;
                
                // Función para obtener texto de fallback usando símbolos más compatibles
                function getFallbackText(iconName) {
                    // Si los emojis NO son soportados, usar símbolos ASCII/Unicode simples
                    const symbolFallbacks = {
                        "rocket": "▸", "gamepad": "◉", "headset": "◐", "code": "◊",
                        "microchip": "◈", "search": "●", "eye": "○", "envelope": "✉",
                        "phone": "⊕", "clock": "○", "palette": "◉", "magic": "✧",
                        "brain": "◊", "credit-card": "⊞", "coins": "○", "tools": "◈",
                        "shopping-cart": "◊", "user-astronaut": "☢", "crown": "◊",
                        "home": "▸", "info-circle": "ℹ", "shipping-fast": "▷",
                        "shield-alt": "◊", "check-circle": "✓", "cogs": "◊",
                        "globe": "○", "paper-plane": "▹", "exclamation-triangle": "⚠",
                        "undo": "◄", "copyright": "©", "file-contract": "▭",
                        "database": "◊", "lock": "⊞", "cookie-bite": "●",
                        "share-alt": "▹", "user-shield": "▭", "user-check": "✓",
                        "edit": "◊", "satellite": "◉", "broadcast-tower": "▴",
                        "bullseye": "●", "comments": "▭", "robot": "◊", "dice": "◊",
                        "crystal-ball": "○", "question-circle": "?", "vial": "◊",
                        "list": "▭", "download": "▾", "arrow-left": "◄",
                        "sign-in-alt": "►", "user-plus": "+", "star": "*",
                        "crosshairs": "+", "users": "◊", "user-secret": "☢",
                        "network-wired": "◊", "university": "▭", "space-shuttle": "▸",
                        "store": "◊", "bars": "☰", "times": "✕", "chevron-down": "▼",
                        "chevron-up": "▲", "chevron-left": "◄", "chevron-right": "►",
                        "angle-down": "▼", "angle-up": "▲", "angle-left": "◄", "angle-right": "►",
                        "caret-down": "▼", "caret-up": "▲", "caret-left": "◄", "caret-right": "►",
                        "play": "►", "pause": "⏸", "stop": "■", "forward": "⏩", "backward": "⏪",
                        "volume-up": "♪", "volume-down": "♫", "volume-off": "⊘", "volume-mute": "⊘",
                        "music": "♪", "video": "▶", "camera": "◊", "image": "◊", "picture": "◊",
                        "file": "▭", "folder": "◊", "folder-open": "◊", "archive": "◊",
                        "download-alt": "▾", "upload": "▴", "cloud": "◊", "cloud-upload": "▴",
                        "cloud-download": "▾", "wifi": "◉", "signal": "◉", "battery-full": "◊",
                        "battery-half": "◊", "battery-empty": "◊", "power-off": "⊘", "plug": "⊚",
                        "lightbulb": "○", "fire": "◊", "heart": "♥", "thumbs-up": "▲", "thumbs-down": "▼",
                        "smile": "○", "frown": "▼", "meh": "─", "surprise": "○", "angry": "▼",
                        "sun": "○", "moon": "◐", "cloud-sun": "◊", "cloud-moon": "◐",
                        "rain": "▾", "snow": "◊", "wind": "◊", "tornado": "◊",
                        "leaf": "◊", "tree": "◊", "flower": "◊", "seedling": "◊",
                        "car": "◊", "bus": "◊", "train": "◊", "plane": "▸", "ship": "◊",
                        "bicycle": "◊", "motorcycle": "◊", "truck": "◊", "ambulance": "◊",
                        "fire-truck": "◊", "police-car": "◊", "taxi": "◊", "helicopter": "◊",
                        // Iconos específicos de redes sociales
                        "discord": "◊", "twitter": "◊", "instagram": "◊", "youtube": "◊",
                        // Iconos de pagos
                        "cc-paypal": "$", "cc-stripe": "$", "bitcoin": "₿", "cc-visa": "$"
                    };
                    
                    // Si los emojis SÍ son soportados, usar los fallbacks originales con emojis
                    const emojiFallbacks = {
                        "rocket": "🚀", "gamepad": "🎮", "headset": "🎧", "code": "💻",
                        "microchip": "🔧", "search": "🔍", "eye": "👁", "envelope": "✉",
                        "phone": "📞", "clock": "⏰", "palette": "🎨", "magic": "✨",
                        "brain": "🧠", "credit-card": "💳", "coins": "🪙", "tools": "🔧",
                        "shopping-cart": "🛒", "user-astronaut": "👨‍🚀", "crown": "👑",
                        "home": "🏠", "info-circle": "ℹ", "shipping-fast": "🚚",
                        "shield-alt": "🛡", "check-circle": "✓", "cogs": "⚙",
                        "globe": "🌍", "paper-plane": "✈", "exclamation-triangle": "⚠",
                        "undo": "↩", "copyright": "©", "file-contract": "📄",
                        "database": "🗄", "lock": "🔒", "cookie-bite": "🍪",
                        "share-alt": "📤", "user-shield": "🛡", "user-check": "✓",
                        "edit": "✏", "satellite": "🛰", "broadcast-tower": "📡",
                        "bullseye": "🎯", "comments": "💬", "robot": "🤖", "dice": "🎲",
                        "crystal-ball": "🔮", "question-circle": "?", "vial": "🧪",
                        "list": "📋", "download": "⬇", "arrow-left": "←",
                        "sign-in-alt": "→", "user-plus": "+", "star": "⭐",
                        "crosshairs": "🎯", "users": "👥", "user-secret": "🕵",
                        "network-wired": "🌐", "university": "🏛", "space-shuttle": "🚀",
                        "store": "🏪", "bars": "☰", "times": "✕", "chevron-down": "▼",
                        "chevron-up": "▲", "chevron-left": "◄", "chevron-right": "►",
                        "angle-down": "▼", "angle-up": "▲", "angle-left": "◄", "angle-right": "►",
                        "caret-down": "▼", "caret-up": "▲", "caret-left": "◄", "caret-right": "►",
                        "play": "►", "pause": "⏸", "stop": "■", "forward": "⏩", "backward": "⏪",
                        "volume-up": "🔊", "volume-down": "🔉", "volume-off": "🔇", "volume-mute": "🔇",
                        "music": "🎵", "video": "📹", "camera": "📷", "image": "🖼", "picture": "🖼",
                        "file": "📄", "folder": "📁", "folder-open": "📂", "archive": "📦",
                        "download-alt": "⬇", "upload": "⬆", "cloud": "☁", "cloud-upload": "⬆☁",
                        "cloud-download": "⬇☁", "wifi": "📶", "signal": "📶", "battery-full": "🔋",
                        "battery-half": "🔋", "battery-empty": "🔋", "power-off": "⏻", "plug": "🔌",
                        "lightbulb": "💡", "fire": "🔥", "heart": "♥", "thumbs-up": "👍", "thumbs-down": "👎",
                        "smile": "😊", "frown": "😞", "meh": "😐", "surprise": "😲", "angry": "😠",
                        "sun": "☀", "moon": "🌙", "cloud-sun": "⛅", "cloud-moon": "☁🌙",
                        "rain": "🌧", "snow": "❄", "wind": "💨", "tornado": "🌪",
                        "leaf": "🍃", "tree": "🌳", "flower": "🌸", "seedling": "🌱",
                        "car": "🚗", "bus": "🚌", "train": "🚂", "plane": "✈", "ship": "🚢",
                        "bicycle": "🚲", "motorcycle": "🏍", "truck": "🚚", "ambulance": "🚑",
                        "fire-truck": "🚒", "police-car": "🚔", "taxi": "🚕", "helicopter": "🚁",
                        // Iconos específicos de redes sociales
                        "discord": "💬", "twitter": "🐦", "instagram": "📷", "youtube": "📺",
                        // Iconos de pagos
                        "cc-paypal": "💳", "cc-stripe": "💳", "bitcoin": "₿", "cc-visa": "💳"
                    };
                    
                    // Usar emojis si son soportados, sino usar símbolos
                    if (emojiSupported) {
                        return emojiFallbacks[iconName] || symbolFallbacks[iconName] || "□";
                    } else {
                        return symbolFallbacks[iconName] || "■";
                    }
                }
                
                // Función para identificar si un icono es de método de pago
                function isPaymentMethod(iconName) {
                    const paymentMethods = ["cc-paypal", "cc-stripe", "bitcoin", "cc-visa"];
                    return paymentMethods.includes(iconName);
                }
                
                // Función para aplicar fallbacks visuales
                function applyFallbacks() {
                    const icons = document.querySelectorAll(".fas, .fab, .far");
                    let fallbackCount = 0;
                    
                    icons.forEach(icon => {
                        const iconName = icon.className.match(/fa-(\\w+)/);
                        if (iconName) {
                            const fallbackText = getFallbackText(iconName[1]);
                            const isPaymentIcon = isPaymentMethod(iconName[1]);
                            
                            icon.textContent = fallbackText;
                            icon.style.fontFamily = "monospace";
                            icon.style.fontSize = "1.2em";
                            // Solo aplicar color azul si NO es un icono de pago
                            if (!isPaymentIcon) {
                                icon.style.color = "#259cae";
                            }
                            fallbackCount++;
                        }
                    });
                    
                    console.log(`Fallbacks aplicados a ${fallbackCount} iconos en producción`);
                    return fallbackCount;
                }
                
                // Inicializar verificación cuando el DOM esté listo
                if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", checkFontAwesome);
                } else {
                    checkFontAwesome();
                }
                
                // También verificar cuando la ventana se carga completamente
                window.addEventListener("load", function() {
                    if (document.body) {
                        checkFontAwesome();
                    }
                });
                
                // Verificar una vez más después de un delay para asegurar
                setTimeout(function() {
                    if (document.body) {
                        checkFontAwesome();
                    }
                }, 2000);
            })();
        </script>' . "\n";
    } else {
        // En desarrollo local, usar solo un CDN (igual que producción)
        $assets .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">' . "\n";
        
        // Script de fallback para desarrollo (igual que producción)
        $assets .= '<script>
            // Fallback para desarrollo local
            (function() {
                function checkFontAwesome() {
                    if (!document.body) {
                        // Esperar a que el body esté disponible
                        if (document.readyState === "loading") {
                            document.addEventListener("DOMContentLoaded", checkFontAwesome);
                        } else {
                            window.addEventListener("load", checkFontAwesome);
                        }
                        return;
                    }
                    
                    const testElement = document.createElement("i");
                    testElement.className = "fas fa-rocket";
                    testElement.style.position = "absolute";
                    testElement.style.left = "-9999px";
                    testElement.style.fontSize = "1px";
                    document.body.appendChild(testElement);
                    
                    setTimeout(() => {
                        if (!document.body) return;
                        
                        const computedStyle = window.getComputedStyle(testElement, "::before");
                        const content = computedStyle.getPropertyValue("content");
                        
                        if (document.body && testElement.parentNode === document.body) {
                        document.body.removeChild(testElement);
                        }
                        
                        // Verificar si realmente está cargado
                        const isLoaded = content && content !== "none" && content !== "normal" && content !== "" && content.length > 0;
                        
                        if (!isLoaded) {
                            console.warn("Font Awesome no detectado, aplicando fallbacks...");
                            if (document.body && !document.body.classList.contains("fontawesome-fallback")) {
                            document.body.classList.add("fontawesome-fallback");
                            }
                        } else {
                            console.log("Font Awesome detectado correctamente");
                            // Asegurar que NO esté la clase de fallback si Font Awesome funciona
                            if (document.body && document.body.classList.contains("fontawesome-fallback")) {
                                document.body.classList.remove("fontawesome-fallback");
                            }
                        }
                    }, 1500);
                }
                
                // Los fallbacks se manejan completamente con CSS cuando la clase fontawesome-fallback está activa
                    // Si los emojis NO son soportados, usar símbolos ASCII/Unicode simples
                    const symbolFallbacks = {
                        "rocket": "▸", "gamepad": "◉", "headset": "◐", "code": "◊",
                        "microchip": "◈", "search": "●", "eye": "○", "envelope": "✉",
                        "phone": "⊕", "clock": "○", "palette": "◉", "magic": "✧",
                        "brain": "◊", "credit-card": "⊞", "coins": "○", "tools": "◈",
                        "shopping-cart": "◊", "user-astronaut": "☢", "crown": "◊",
                        "home": "▸", "info-circle": "ℹ", "shipping-fast": "▷",
                        "shield-alt": "◊", "check-circle": "✓", "cogs": "◊",
                        "globe": "○", "paper-plane": "▹", "exclamation-triangle": "⚠",
                        "undo": "◄", "copyright": "©", "file-contract": "▭",
                        "database": "◊", "lock": "⊞", "cookie-bite": "●",
                        "share-alt": "▹", "user-shield": "▭", "user-check": "✓",
                        "edit": "◊", "satellite": "◉", "broadcast-tower": "▴",
                        "bullseye": "●", "comments": "▭", "robot": "◊", "dice": "◊",
                        "crystal-ball": "○", "question-circle": "?", "vial": "◊",
                        "list": "▭", "download": "▾", "arrow-left": "◄",
                        "sign-in-alt": "►", "user-plus": "+", "star": "*",
                        "crosshairs": "+", "users": "◊", "user-secret": "☢",
                        "network-wired": "◊", "university": "▭", "space-shuttle": "▸",
                        "store": "◊", "bars": "☰", "times": "✕", "chevron-down": "▼",
                        "chevron-up": "▲", "chevron-left": "◄", "chevron-right": "►",
                        "angle-down": "▼", "angle-up": "▲", "angle-left": "◄", "angle-right": "►",
                        "caret-down": "▼", "caret-up": "▲", "caret-left": "◄", "caret-right": "►",
                        "play": "►", "pause": "⏸", "stop": "■", "forward": "⏩", "backward": "⏪",
                        "volume-up": "♪", "volume-down": "♫", "volume-off": "⊘", "volume-mute": "⊘",
                        "music": "♪", "video": "▶", "camera": "◊", "image": "◊", "picture": "◊",
                        "file": "▭", "folder": "◊", "folder-open": "◊", "archive": "◊",
                        "download-alt": "▾", "upload": "▴", "cloud": "◊", "cloud-upload": "▴",
                        "cloud-download": "▾", "wifi": "◉", "signal": "◉", "battery-full": "◊",
                        "battery-half": "◊", "battery-empty": "◊", "power-off": "⊘", "plug": "⊚",
                        "lightbulb": "○", "fire": "◊", "heart": "♥", "thumbs-up": "▲", "thumbs-down": "▼",
                        "smile": "○", "frown": "▼", "meh": "─", "surprise": "○", "angry": "▼",
                        "sun": "○", "moon": "◐", "cloud-sun": "◊", "cloud-moon": "◐",
                        "rain": "▾", "snow": "◊", "wind": "◊", "tornado": "◊",
                        "leaf": "◊", "tree": "◊", "flower": "◊", "seedling": "◊",
                        "car": "◊", "bus": "◊", "train": "◊", "plane": "▸", "ship": "◊",
                        "bicycle": "◊", "motorcycle": "◊", "truck": "◊", "ambulance": "◊",
                        "fire-truck": "◊", "police-car": "◊", "taxi": "◊", "helicopter": "◊",
                        // Iconos específicos de redes sociales
                        "discord": "◊", "twitter": "◊", "instagram": "◊", "youtube": "◊",
                        // Iconos de pagos
                        "cc-paypal": "$", "cc-stripe": "$", "bitcoin": "₿", "cc-visa": "$"
                    };
                    
                    // Si los emojis SÍ son soportados, usar los fallbacks originales con emojis
                    const emojiFallbacks = {
                        "rocket": "🚀", "gamepad": "🎮", "headset": "🎧", "code": "💻",
                        "microchip": "🔧", "search": "🔍", "eye": "👁", "envelope": "✉",
                        "phone": "📞", "clock": "⏰", "palette": "🎨", "magic": "✨",
                        "brain": "🧠", "credit-card": "💳", "coins": "🪙", "tools": "🔧",
                        "shopping-cart": "🛒", "user-astronaut": "👨‍🚀", "crown": "👑",
                        "home": "🏠", "info-circle": "ℹ", "shipping-fast": "🚚",
                        "shield-alt": "🛡", "check-circle": "✓", "cogs": "⚙",
                        "globe": "🌍", "paper-plane": "✈", "exclamation-triangle": "⚠",
                        "undo": "↩", "copyright": "©", "file-contract": "📄",
                        "database": "🗄", "lock": "🔒", "cookie-bite": "🍪",
                        "share-alt": "📤", "user-shield": "🛡", "user-check": "✓",
                        "edit": "✏", "satellite": "🛰", "broadcast-tower": "📡",
                        "bullseye": "🎯", "comments": "💬", "robot": "🤖", "dice": "🎲",
                        "crystal-ball": "🔮", "question-circle": "?", "vial": "🧪",
                        "list": "📋", "download": "⬇", "arrow-left": "←",
                        "sign-in-alt": "→", "user-plus": "+", "star": "⭐",
                        "crosshairs": "🎯", "users": "👥", "user-secret": "🕵",
                        "network-wired": "🌐", "university": "🏛", "space-shuttle": "🚀",
                        "store": "🏪", "bars": "☰", "times": "✕", "chevron-down": "▼",
                        "chevron-up": "▲", "chevron-left": "◄", "chevron-right": "►",
                        "angle-down": "▼", "angle-up": "▲", "angle-left": "◄", "angle-right": "►",
                        "caret-down": "▼", "caret-up": "▲", "caret-left": "◄", "caret-right": "►",
                        "play": "►", "pause": "⏸", "stop": "■", "forward": "⏩", "backward": "⏪",
                        "volume-up": "🔊", "volume-down": "🔉", "volume-off": "🔇", "volume-mute": "🔇",
                        "music": "🎵", "video": "📹", "camera": "📷", "image": "🖼", "picture": "🖼",
                        "file": "📄", "folder": "📁", "folder-open": "📂", "archive": "📦",
                        "download-alt": "⬇", "upload": "⬆", "cloud": "☁", "cloud-upload": "⬆☁",
                        "cloud-download": "⬇☁", "wifi": "📶", "signal": "📶", "battery-full": "🔋",
                        "battery-half": "🔋", "battery-empty": "🔋", "power-off": "⏻", "plug": "🔌",
                        "lightbulb": "💡", "fire": "🔥", "heart": "♥", "thumbs-up": "👍", "thumbs-down": "👎",
                        "smile": "😊", "frown": "😞", "meh": "😐", "surprise": "😲", "angry": "😠",
                        "sun": "☀", "moon": "🌙", "cloud-sun": "⛅", "cloud-moon": "☁🌙",
                        "rain": "🌧", "snow": "❄", "wind": "💨", "tornado": "🌪",
                        "leaf": "🍃", "tree": "🌳", "flower": "🌸", "seedling": "🌱",
                        "car": "🚗", "bus": "🚌", "train": "🚂", "plane": "✈", "ship": "🚢",
                        "bicycle": "🚲", "motorcycle": "🏍", "truck": "🚚", "ambulance": "🚑",
                        "fire-truck": "🚒", "police-car": "🚔", "taxi": "🚕", "helicopter": "🚁",
                        // Iconos específicos de redes sociales
                        "discord": "💬", "twitter": "🐦", "instagram": "📷", "youtube": "📺",
                        // Iconos de pagos
                        "cc-paypal": "💳", "cc-stripe": "💳", "bitcoin": "₿", "cc-visa": "💳"
                    };
                    
                    // Usar emojis si son soportados, sino usar símbolos
                    if (emojiSupported) {
                        return emojiFallbacks[iconName] || symbolFallbacks[iconName] || "□";
                    } else {
                        return symbolFallbacks[iconName] || "■";
                    }
                }
                
                // Inicializar verificación cuando el DOM esté listo
                if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", checkFontAwesome);
                } else {
                    checkFontAwesome();
                }
                
                // También verificar cuando la ventana se carga completamente
                window.addEventListener("load", function() {
                    if (document.body) {
                        checkFontAwesome();
                    }
                });
                
                // Verificar una vez más después de un delay para asegurar
                setTimeout(function() {
                    if (document.body) {
                        checkFontAwesome();
                    }
                }, 2000);
            })();
        </script>' . "\n";
    }
    
    // Custom CSS con cache headers
    $assets .= '<link rel="stylesheet" href="assets/css/style.css">' . "\n";
    
    // Font Awesome Fix CSS
    $assets .= '<link rel="stylesheet" href="assets/css/font-awesome-fix.css">' . "\n";
    
    // Mobile Optimization CSS
    $assets .= '<link rel="stylesheet" href="assets/css/mobile-optimization.css">' . "\n";
    
    return $assets;
}

// Función para generar el favicon
function generateFavicon() {
    $favicon = '';
    
    // Favicon básico con ruta web correcta
    $favicon .= '<link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">' . "\n";
    
    // Favicon SVG
    $favicon .= '<link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">' . "\n";
    
    // Favicon PNG
    $favicon .= '<link rel="icon" type="image/png" sizes="96x96" href="/assets/images/favicon-96x96.png">' . "\n";
    
    // Apple Touch Icon
    $favicon .= '<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">' . "\n";
    
    // Web App Manifest
    $favicon .= '<link rel="manifest" href="/assets/images/site.webmanifest">' . "\n";
    
    // Meta tags para PWA
    $favicon .= '<meta name="theme-color" content="#259cae">' . "\n";
    $favicon .= '<meta name="msapplication-TileColor" content="#259cae">' . "\n";
    
    return $favicon;
}

// Función para generar el header completo
function generateHeader($title = '', $description = '', $keywords = '') {
    $header = '<!DOCTYPE html>' . "\n";
    $header .= '<html lang="es" data-bs-theme="dark">' . "\n";
    $header .= '<head>' . "\n";
    $header .= generateMetaTags($title, $description, $keywords);
    $header .= generateFavicon();
    $header .= generateCommonAssets();
    $header .= '<title>' . getPageTitle($title) . '</title>' . "\n";
    $header .= '</head>' . "\n";
    $header .= '<body>' . "\n";
    
    return $header;
}

// Función para generar la navegación
function generateNavigation() {
    // Obtener la página actual
    $current_page = basename($_SERVER['PHP_SELF']);
    
    $nav = '<nav class="navbar navbar-expand-lg navbar-dark fixed-top">' . "\n";
    $nav .= '    <div class="container">' . "\n";
    $nav .= '        <a class="navbar-brand logo-container" href="/index.php">' . "\n";
    $nav .= '            <img src="/assets/images/knd-logo.png" alt="KND Store" class="navbar-logo" height="60">' . "\n";
    $nav .= '            <div class="logo-glow"></div>' . "\n";
    $nav .= '        </a>' . "\n";
    $nav .= '        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">' . "\n";
    $nav .= '            <span class="navbar-toggler-icon"></span>' . "\n";
    $nav .= '        </button>' . "\n";
    $nav .= '        <div class="collapse navbar-collapse" id="navbarNav">' . "\n";
    $nav .= '            <ul class="navbar-nav ms-auto">' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'index.php' ? ' active' : '') . '" href="/index.php">Inicio</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'products.php' ? ' active' : '') . '" href="/products.php">Catálogo</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'about.php' ? ' active' : '') . '" href="/about.php">Sobre Nosotros</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'contact.php' ? ' active' : '') . '" href="/contact.php">Contacto</a>' . "\n";
    $nav .= '                </li>' . "\n";
    $nav .= '                <li class="nav-item">' . "\n";
    $nav .= '                    <a class="nav-link' . ($current_page == 'order.php' ? ' active' : '') . '" href="/order.php">' . "\n";
    $nav .= '                        <i class="fas fa-shopping-cart me-1"></i>' . "\n";
    $nav .= '                        Pedido' . "\n";
    $nav .= '                        <span id="order-count" class="badge rounded-pill bg-primary ms-1" style="display:none; min-width: 20px; justify-content: center; align-items: center;"></span>' . "\n";
    $nav .= '                    </a>' . "\n";
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
    $panel .= '        <h3><i class="fas fa-magic me-2"></i>Personalizar Colores</h3>' . "\n";
    $panel .= '    </div>' . "\n";
    $panel .= '    <div class="color-panel-content">' . "\n";
    $panel .= '        <div class="color-theme active" data-theme="galactic-blue">' . "\n";
    $panel .= '            <h4>Galáctico Azul</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #259cae;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ae2565;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #16213e;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>El clásico azul neón con morado eléctrico</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="cyber-green">' . "\n";
    $panel .= '            <h4>Cíber Verde</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #00ff00;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #32cd32;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #006400;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Verde neón para una vibra más hacker</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="fire-red">' . "\n";
    $panel .= '            <h4>Fuego Rojo</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ff0000;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ff4500;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #8b0000;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Rojo intenso para energía máxima</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="golden-sun">' . "\n";
    $panel .= '            <h4>Sol Dorado</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ffd700;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ffa500;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #daa520;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Dorado y naranja para elegancia cósmica</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="neon-pink">' . "\n";
    $panel .= '            <h4>Neón Rosa</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ff69b4;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #ff1493;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #c71585;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Rosa vibrante para un toque futurista</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '        ' . "\n";
    $panel .= '        <div class="color-theme" data-theme="ice-blue">' . "\n";
    $panel .= '            <h4>Hielo Azul</h4>' . "\n";
    $panel .= '            <div class="color-preview">' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #00ffff;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #87ceeb;"></div>' . "\n";
    $panel .= '                <div class="color-swatch" style="background: #4682b4;"></div>' . "\n";
    $panel .= '            </div>' . "\n";
    $panel .= '            <p>Azul cian para una sensación glacial</p>' . "\n";
    $panel .= '        </div>' . "\n";
    $panel .= '    </div>' . "\n";
    $panel .= '</div>' . "\n";

    return $panel;
}
?> 