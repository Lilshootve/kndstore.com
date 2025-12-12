# 📦 Análisis de Definición de Productos/Servicios - KND Store

## 📍 Archivos y Variables donde se definen los productos

### **1. `products.php` - Array principal por categorías**
- **Variable:** `$productos` (línea 20)
- **Estructura:** Array multidimensional organizado por categorías
- **Formato:** `$productos['categoria'] = [array de productos]`
- **Categorías:** `tecnologia`, `gaming`, `accesorios`, `software`, `hardware`
- **Uso:** Página de catálogo completo con filtros y búsqueda

### **2. `producto.php` - Array indexado por slug**
- **Variable:** `$productos` (línea 23)
- **Estructura:** Array asociativo indexado por slug del producto
- **Formato:** `$productos['slug'] = [datos del producto]`
- **Uso:** Página de detalle individual de cada producto
- **Diferencia:** Contiene descripciones más detalladas con HTML (`<br>`, listas)

### **3. `index.php` - Array de productos destacados**
- **Variable:** `$featuredProducts` (línea 106)
- **Estructura:** Array simple con solo 4 productos destacados
- **Formato:** Array indexado numéricamente
- **Uso:** Sección "Productos Destacados" en la página de inicio
- **Diferencia:** Usa claves en inglés (`name`, `description`, `price`, `image`, `url`) en lugar de español

### **4. `includes/config.php` - Funciones de base de datos (NO UTILIZADAS)**
- **Funciones:** 
  - `getFeaturedProducts($limit)` (línea 141)
  - `getProductsByCategory($categoryId, $limit)` (línea 165)
  - `searchProducts($query, $limit)` (línea 177)
- **Estructura:** Consultas SQL a tabla `products` en base de datos
- **Estado:** ⚠️ **Definidas pero NO se están utilizando** - El proyecto usa arrays hardcodeados en lugar de BD

---

## 📋 Ejemplo Completo de un Producto

### **Ejemplo desde `products.php` (estructura por categorías):**

```php
'tecnologia' => [
    [
        'id' => 1,
        'nombre' => 'Formateo y limpieza de PC (Remoto)',
        'descripcion' => 'Recupera el rendimiento de tu PC desde la comodidad de tu nave.',
        'precio' => 10.00,
        'imagen' => 'assets/images/productos/formateo-limpieza-pc-remoto.png',
        'categoria' => 'tecnologia',
        'url' => '/producto/formateo-limpieza-pc',
        'slug' => 'formateo-limpieza-pc'
    ],
    // ... más productos
]
```

### **Ejemplo desde `producto.php` (estructura por slug):**

```php
'formateo-limpieza-pc' => [
    'id' => 1,
    'nombre' => 'Formateo y limpieza de PC (Remoto)',
    'descripcion' => 'Recupera el rendimiento de tu PC desde la comodidad de tu nave.<br><br>Incluye:<br>• Formateo completo del sistema<br>• Instalación de Windows limpio<br>• Instalación de drivers actualizados<br>• Optimización de rendimiento<br>• Limpieza de archivos temporales<br>• Configuración de seguridad básica',
    'precio' => 10.00,
    'imagen' => 'assets/images/productos/formateo-limpieza-pc-remoto.png',
    'categoria' => 'tecnologia',
    'slug' => 'formateo-limpieza-pc'
]
```

### **Ejemplo desde `index.php` (productos destacados):**

```php
$featuredProducts = [
    [
        'name' => 'Formateo y limpieza de PC (Remoto)',
        'description' => 'Recupera el rendimiento de tu PC desde la comodidad de tu nave.',
        'price' => 10.00,
        'image' => 'assets/images/productos/formateo-limpieza-pc-remoto.png',
        'url' => '/producto/formateo-limpieza-pc'
    ],
    // ... más productos destacados
];
```

---

## ⚠️ Fuentes de Verdad Múplicadas (Duplicación de Datos)

### **Problema Identificado: DUPLICACIÓN CRÍTICA**

Los productos están definidos en **3 lugares diferentes** con estructuras similares pero no idénticas:

#### **1. `products.php` vs `producto.php`**
- ✅ **Mismos productos** (15 productos totales)
- ❌ **Estructura diferente:**
  - `products.php`: Organizado por categorías → arrays anidados
  - `producto.php`: Indexado por slug → array asociativo plano
- ❌ **Descripciones diferentes:**
  - `products.php`: Descripciones cortas (1 línea)
  - `producto.php`: Descripciones largas con HTML y listas detalladas
- ⚠️ **Riesgo:** Si se actualiza un producto en un archivo, hay que actualizarlo manualmente en el otro

#### **2. `index.php` - Productos destacados**
- ✅ **Subconjunto:** Solo 4 productos de los 15 totales
- ❌ **Estructura diferente:** Claves en inglés (`name`, `price`) vs español (`nombre`, `precio`)
- ❌ **Campos faltantes:** No incluye `id`, `categoria`, `slug`
- ⚠️ **Riesgo:** Desincronización si se cambian precios o nombres

#### **3. `includes/config.php` - Funciones de BD**
- ⚠️ **No se utilizan:** Las funciones están definidas pero nunca se llaman
- ⚠️ **Confusión:** Sugiere que debería haber una BD, pero el proyecto usa arrays hardcodeados
- ⚠️ **Riesgo:** Código muerto que puede causar confusión

---

## 📊 Resumen de Estructuras

| Archivo | Variable | Estructura | Productos | Estado |
|---------|---------|------------|-----------|--------|
| `products.php` | `$productos` | Por categorías | 15 | ✅ Activo |
| `producto.php` | `$productos` | Por slug | 15 | ✅ Activo |
| `index.php` | `$featuredProducts` | Array simple | 4 | ✅ Activo |
| `includes/config.php` | Funciones SQL | Base de datos | N/A | ❌ No usado |

---

## 🔧 Recomendaciones

1. **Centralizar datos:** Crear un único archivo `includes/products.php` con todos los productos
2. **Eliminar duplicación:** Usar funciones para generar las diferentes estructuras desde una fuente única
3. **Unificar estructura:** Decidir si usar claves en español o inglés de forma consistente
4. **Evaluar BD:** Decidir si migrar a base de datos o eliminar las funciones no utilizadas

