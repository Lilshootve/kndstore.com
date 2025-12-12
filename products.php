<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/products-data.php';

// Filtros
$categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Filtrar productos
$productos_filtrados = [];
foreach ($PRODUCTS as $slug => $producto) {
    $pasa_filtro = true;

    // Filtro por categoría
    if ($categoria_filtro && $producto['categoria'] !== $categoria_filtro) {
        $pasa_filtro = false;
    }

    // Filtro por búsqueda
    if ($busqueda) {
        $texto_busqueda = mb_strtolower($busqueda, 'UTF-8');
        $texto_producto = mb_strtolower(strip_tags($producto['nombre'] . ' ' . $producto['descripcion']), 'UTF-8');
        if (mb_strpos($texto_producto, $texto_busqueda) === false) {
            $pasa_filtro = false;
        }
    }

    if ($pasa_filtro) {
        $producto['slug'] = $slug;
        $productos_filtrados[] = $producto;
    }
}
?>

<?php echo generateHeader('Catálogo', 'Catálogo y Servicios - KND Store - Tecnología, Gaming, Software y más'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient">Nuestro</span><br>
                    <span class="text-gradient">Catálogo</span>
                </h1>
                <p class="hero-subtitle">
                    Servicios digitales y tecnología galáctica para tu nave
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Categorías Destacadas -->
<section class="categories-section py-4 bg-dark-epic">
    <div class="container">
        <h2 class="section-title text-center mb-4">Categorías Destacadas</h2>
        <div class="row">
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=tecnologia" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h5>Tecnología</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=gaming" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h5>Gaming</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=accesorios" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5>Accesorios</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=software" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h5>Software</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=hardware" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h5>Hardware</h5>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Filtros y Búsqueda -->
<section class="filters-section py-4 bg-dark-epic">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form method="GET" action="/products.php" class="search-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>" 
                                   class="form-control search-input" placeholder="Buscar en el catálogo...">
                        </div>
                        <div class="col-md-4">
                            <select name="categoria" class="form-select">
                                <option value="">Todas las categorías</option>
                                <option value="tecnologia" <?php echo $categoria_filtro === 'tecnologia' ? 'selected' : ''; ?>>Tecnología</option>
                                <option value="gaming" <?php echo $categoria_filtro === 'gaming' ? 'selected' : ''; ?>>Gaming</option>
                                <option value="accesorios" <?php echo $categoria_filtro === 'accesorios' ? 'selected' : ''; ?>>Accesorios</option>
                                <option value="software" <?php echo $categoria_filtro === 'software' ? 'selected' : ''; ?>>Software</option>
                                <option value="hardware" <?php echo $categoria_filtro === 'hardware' ? 'selected' : ''; ?>>Hardware</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Productos -->
<section class="products-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title text-center mb-5">
                    <?php if ($categoria_filtro): ?>
                        <?php echo ucfirst($categoria_filtro); ?>
                    <?php elseif ($busqueda): ?>
                        Resultados para "<?php echo htmlspecialchars($busqueda); ?>"
                    <?php else: ?>
                        Todo el Catálogo
                    <?php endif; ?>
                </h2>
            </div>
        </div>
        
        <?php if (empty($productos_filtrados)): ?>
            <div class="row">
                <div class="col-12 text-center">
                    <div class="no-results">
                        <i class="fas fa-search fa-3x mb-3" style="color: var(--knd-neon-blue);"></i>
                        <h3>No se encontraron servicios</h3>
                        <p>Intenta con otros términos de búsqueda o categorías</p>
                        <a href="/products.php" class="btn btn-primary">Ver todo el catálogo</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($productos_filtrados as $producto): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <?php if (in_array($producto['nombre'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])): ?>
                                <div class="product-offer-badge">Oferta</div>
                            <?php endif; ?>
                            <div class="product-image">
                                <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                     onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDMwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjMmMyYzJjIi8+CjxyZWN0IHg9IjEwIiB5PSIxMCIgd2lkdGg9IjI4MCIgaGVpZ2h0PSIxODAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwYmZmZiIgc3Ryb2tlLXdpZHRoPSIyIi8+CjxjaXJjbGUgY3g9IjE1MCIgY3k9IjEwMCIgcj0iMzAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwYmZmZiIgc3Ryb2tlLXdpZHRoPSIzIi8+CjxwYXRoIGQ9Ik0xMzAgODAgTDE3MCA4MCBNMTMwIDEyMCBMMTcwIDEyMCIgc3Ryb2tlPSIjMDBiZmZmIiBzdHJva2Utd2lkdGg9IjIiLz4KPHN2ZyB4PSIxMjAiIHk9IjE0MCIgd2lkdGg9IjYwIiBoZWlnaHQ9IjIwIj4KICA8dGV4dCBmaWxsPSIjMDBiZmZmIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTIiIHRleHQtYW5jaG9yPSJtaWRkbGUiPk5vIEltYWdlbjwvdGV4dD4KPC9zdmc+'; this.style.background='#2c2c2c';">
                                <div class="product-overlay">
                                    <a href="/producto.php?slug=<?php echo $producto['slug']; ?>" class="btn btn-primary">
                                        <i class="fas fa-eye me-2"></i>Ver detalles
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h5 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                <div class="product-footer">
                                    <?php if (in_array($producto['nombre'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])): ?>
                                        <span class="product-price">
                                            <span class="product-price-original">$<?php echo number_format($producto['precio'], 2); ?></span>
                                            <span class="product-price-offer">$2.50</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="product-price">$<?php echo number_format($producto['precio'], 2); ?></span>
                                    <?php endif; ?>
                                    <a href="/producto.php?slug=<?php echo $producto['slug']; ?>" class="btn btn-outline-neon btn-sm btn-details">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
echo generateFooter();
echo generateScripts();
?> 