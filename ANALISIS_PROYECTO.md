# 📊 Análisis del Proyecto KND Store

## 🛠️ Tecnologías Utilizadas

**Backend:** PHP 7+ (sin framework, arquitectura MVC manual), MySQL/PDO para base de datos, sesiones PHP nativas, Apache con mod_rewrite y mod_headers. **Frontend:** HTML5, CSS3 (custom, sin preprocesadores), JavaScript vanilla (ES6+), Bootstrap 5.3.0, jQuery 3.6.0, Font Awesome 6.4.0, Particles.js 2.0.0. **Servidor:** Apache con .htaccess para configuración, compresión GZIP/Brotli, Content Security Policy (CSP), Service Worker (PWA). **Fuentes:** Google Fonts (Orbitron, Inter). **Herramientas:** Sin build tools (desarrollo directo), optimización manual de assets, cache headers personalizados.

## 📁 Árbol de Carpetas (3 niveles)

```
kndstore/
├── assets/
│   ├── css/
│   │   ├── font-awesome-fix.css
│   │   ├── mobile-optimization.css
│   │   └── style.css
│   ├── fonts/
│   ├── images/
│   │   ├── productos/
│   │   │   ├── activacion-juegos-giftcards.png
│   │   │   ├── analisis-rendimiento-pc.png
│   │   │   ├── asesoria-pc-gamer-presupuesto.png
│   │   │   └── [más productos...]
│   │   ├── apple-touch-icon.png
│   │   ├── favicon.ico
│   │   ├── knd-logo.png
│   │   └── [más imágenes...]
│   └── js/
│       ├── main.js
│       ├── mobile-optimization.js
│       ├── scroll-smooth.js
│       └── sw.js
├── includes/
│   ├── config.php
│   ├── config-local.php
│   ├── footer.php
│   ├── footer_base.php
│   ├── header.php
│   └── header_base.php
├── about.php
├── contact.php
├── faq.php
├── index.php
├── offline.html
├── privacy.php
├── producto.php
├── products.php
├── terms.php
├── test-icons.php
├── .htaccess
├── robots.txt
└── sitemap.xml
```

## 🚪 Puntos de Entrada del Sitio

### **Archivos PHP Principales (Páginas)**
- **`index.php`** - Página de inicio, punto de entrada principal del sitio
- **`products.php`** - Catálogo de productos con filtros y búsqueda
- **`producto.php`** - Página de detalle de producto individual
- **`about.php`** - Página "Sobre Nosotros"
- **`contact.php`** - Página de contacto
- **`faq.php`** - Preguntas frecuentes
- **`privacy.php`** - Política de privacidad
- **`terms.php`** - Términos y condiciones

### **Archivos de Configuración (Backend)**
- **`includes/config.php`** - Configuración principal (producción), conexión a BD, funciones globales
- **`includes/config-local.php`** - Configuración de desarrollo local
- **`includes/header.php`** - Generador de header HTML, carga de assets (CSS/JS), meta tags
- **`includes/footer.php`** - Generador de footer HTML, scripts finales, partículas

### **Archivos JavaScript Principales**
- **`assets/js/main.js`** - JavaScript principal de la aplicación (inicialización, efectos, panel de colores)
- **`assets/js/mobile-optimization.js`** - Optimizaciones específicas para dispositivos móviles
- **`assets/js/scroll-smooth.js`** - Navegación suave por secciones y scroll
- **`assets/js/sw.js`** - Service Worker para PWA y cache offline

### **Archivos de Configuración del Servidor**
- **`.htaccess`** - Configuración Apache (rewrites, headers de seguridad, CSP, compresión, cache)
- **`robots.txt`** - Configuración para crawlers
- **`sitemap.xml`** - Mapa del sitio para SEO

### **Archivos de Estilos Principales**
- **`assets/css/style.css`** - Estilos principales del sitio
- **`assets/css/mobile-optimization.css`** - Estilos responsive y móviles
- **`assets/css/font-awesome-fix.css`** - Fixes y fallbacks para Font Awesome

### **Archivos PWA**
- **`offline.html`** - Página mostrada cuando no hay conexión
- **`assets/images/site.webmanifest`** - Manifest para Progressive Web App

