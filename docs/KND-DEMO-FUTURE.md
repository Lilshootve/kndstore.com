# KND Demo Future — Documento de Diseño

## Resumen

Demos aisladas de propuesta visual premium para KND: Home demo y Labs Workspace demo, sin modificar producción.

---

## 1. Archivos creados

| Archivo | Descripción |
|---------|-------------|
| `home-demo.php` | Home demo comercial y premium |
| `labs-workspace-demo.php` | Labs Text→Image demo tipo workspace (3 columnas) |
| `assets/css/knd-demo-future.css` | CSS compartido del sistema visual demo |
| `assets/js/knd-demo-future.js` | JS mínimo (chips, botón Generate) |

---

## 2. Qué incluye cada demo

### Home Demo (`home-demo.php`)

- **Hero**: headline, subtítulo, CTAs (Explore Labs, View Store), prompt bar simulado
- **Ecosystem**: 4 cards (Labs, Store, Arena, Support & Custom)
- **Recent Creations**: grid de showcase cards con placeholders
- **How It Works**: 3 pasos (Choose, Create, Launch)
- **Why KND**: 4 mini-paneles (Fast delivery, Premium support, Smart workflows, Creative + technical)
- **CTA final**: bloque central con botones
- **Footer demo**: enlaces básicos, copy "Demo prototype"

### Labs Workspace Demo (`labs-workspace-demo.php`)

- **Breadcrumb**: Home / Labs / Text → Image
- **Layout 3 zonas**:
  - **Izquierda (Settings)**: prompt, negative prompt, presets/chips (Game, Realistic, Anime, Cyberpunk), aspect ratio, quality, model, advanced collapsible
  - **Centro (Canvas)**: área principal vacía + CTA Generate destacado
  - **Derecha (Info)**: créditos KP, botón Add Credits, historial con badges (Done, Processing)
- **Galería**: Recent Creations (4 cards placeholder)

---

## 3. Componentes reutilizables (CSS)

| Clase | Uso |
|-------|-----|
| `.knd-demo-shell` | Contenedor principal, variables y fondo |
| `.knd-demo-hero` | Hero principal |
| `.knd-panel` | Panel oscuro con borde suave |
| `.knd-panel--active` | Panel activo/resaltado |
| `.knd-btn-primary` | Botón CTA principal |
| `.knd-btn-secondary` | Botón secundario |
| `.knd-input`, `.knd-textarea`, `.knd-select` | Formularios |
| `.knd-label` | Etiquetas de formulario |
| `.knd-chip`, `.knd-chip.is-active` | Chips / presets |
| `.knd-badge`, `--success`, `--warning`, `--danger` | Badges de estado |
| `.knd-section-title` | Títulos de sección |
| `.knd-muted` | Texto secundario |
| `.knd-divider` | Separador |
| `.knd-card-grid` | Grid de cards |
| `.knd-showcase-card` | Card de galería |
| `.knd-eco-card` | Card de ecosistema |
| `.knd-prompt-bar` | Barra de prompt (hero) |
| `.knd-workspace` | Layout 3 columnas |
| `.knd-canvas`, `.knd-canvas__empty` | Canvas/resultado |
| `.knd-demo-footer` | Footer demo |

---

## 4. Qué puede escalar al resto del sitio

- **Paleta**: variables `--knd-bg`, `--knd-cyan`, etc. pueden usarse en otras páginas
- **Paneles y cards**: `.knd-panel`, `.knd-eco-card`, `.knd-showcase-card` para otras secciones
- **Botones y inputs**: `.knd-btn-primary`, `.knd-input`, etc. como alternativa al neón actual
- **Layout workspace**: `knd-workspace` puede reutilizarse en otras herramientas (Upscale, Consistency, etc.)
- **Footer**: `.knd-demo-footer` como base de footer simplificado

---

## 5. Decisiones de diseño

- **65% SaaS, 25% futurista, 10% gaming**: prioridad a claridad profesional; acentos cyan/violeta discretos
- **Glow solo en CTA y estados activos**: se evita el exceso de neón
- **Fondos con profundidad**: gradientes sutiles en hero y canvas
- **Espaciado generoso**: padding y gap amplios para sensación premium
- **Jerarquía clara**: títulos en mayúsculas con letter-spacing, secciones bien separadas
- **Canvas como protagonista**: la zona central es el foco en Labs
- **Responsive**: workspace pasa a 2 columnas (tablet) y 1 columna (móvil)
