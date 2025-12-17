<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/products-data.php';

// Filtros
$categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$tipo_filtro = isset($_GET['type']) ? $_GET['type'] : '';

// Filtrar productos
$productos_filtrados = [];
foreach ($PRODUCTS as $slug => $producto) {
        $pasa_filtro = true;
        
        // Filtro por tipo (digital/apparel/service)
        if ($tipo_filtro) {
            $producto_tipo = isset($producto['tipo']) ? $producto['tipo'] : 'digital';
            if ($producto_tipo !== $tipo_filtro) {
                $pasa_filtro = false;
            }
        }
        
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

// Ordenar productos: apparel primero, luego los demás
usort($productos_filtrados, function($a, $b) {
    $tipo_a = isset($a['tipo']) ? $a['tipo'] : 'digital';
    $tipo_b = isset($b['tipo']) ? $b['tipo'] : 'digital';
    
    // Si ambos son apparel o ninguno es apparel, mantener orden original
    if ($tipo_a === 'apparel' && $tipo_b !== 'apparel') {
        return -1; // apparel va primero
    }
    if ($tipo_a !== 'apparel' && $tipo_b === 'apparel') {
        return 1; // apparel va primero
    }
    return 0; // mantener orden original
});
?>

<?php echo generateHeader(t('products.meta.title'), t('products.meta.description')); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section hero-catalog-bg">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient"><?php echo t('products.hero.title_line1'); ?></span><br>
                    <span class="text-gradient"><?php echo t('products.hero.title_line2'); ?></span>
                </h1>
                <p class="hero-subtitle">
                    <?php echo t('products.hero.subtitle'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Categorías Destacadas -->
<section class="categories-section py-4 bg-dark-epic">
    <div class="container">
        <h2 class="section-title text-center mb-4"><?php echo t('products.categories.title'); ?></h2>
        <div class="row">
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=tecnologia" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                    <h5><?php echo t('products.categories.technology'); ?></h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=gaming" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h5><?php echo t('products.categories.gaming'); ?></h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=accesorios" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h5><?php echo t('products.categories.accessories'); ?></h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=software" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h5>Software</h5>
                </a>
            </div>
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <a href="/products.php?categoria=hardware" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <h5>Hardware</h5>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Filtros por Tipo -->
<section class="filters-section py-4 bg-dark-epic">
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <ul class="nav nav-pills justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?php echo !$tipo_filtro ? 'active' : ''; ?>" href="/products.php">
                            <i class="fas fa-th me-2"></i> <?php echo t('products.categories.all'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $tipo_filtro === 'digital' ? 'active' : ''; ?>" href="/products.php?type=digital">
                            <i class="fas fa-download me-2"></i> <?php echo t('products.filters.type.digital'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $tipo_filtro === 'apparel' ? 'active' : ''; ?>" href="/products.php?type=apparel">
                            <i class="fas fa-tshirt me-2"></i> <?php echo t('products.filters.type.apparel'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $tipo_filtro === 'service' ? 'active' : ''; ?>" href="/products.php?type=service">
                            <i class="fas fa-palette me-2"></i> <?php echo t('products.filters.type.services'); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form method="GET" action="/products.php" class="search-form">
                    <?php if ($tipo_filtro): ?>
                        <input type="hidden" name="type" value="<?php echo htmlspecialchars($tipo_filtro); ?>">
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>" 
                                   class="form-control search-input" placeholder="<?php echo t('products.filters.search_placeholder'); ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="categoria" class="form-select">
                                <option value=""><?php echo t('products.categories.all'); ?></option>
                                <option value="tecnologia" <?php echo $categoria_filtro === 'tecnologia' ? 'selected' : ''; ?>><?php echo t('products.categories.technology'); ?></option>
                                <option value="gaming" <?php echo $categoria_filtro === 'gaming' ? 'selected' : ''; ?>><?php echo t('products.categories.gaming'); ?></option>
                                <option value="accesorios" <?php echo $categoria_filtro === 'accesorios' ? 'selected' : ''; ?>><?php echo t('products.categories.accessories'); ?></option>
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
                        <?php echo t('products.results.title'); ?>: "<?php echo htmlspecialchars($busqueda); ?>"
                    <?php else: ?>
                        <?php echo t('products.results.title'); ?>
                    <?php endif; ?>
                </h2>
            </div>
        </div>
        
        <?php if (empty($productos_filtrados)): ?>
            <div class="row">
                <div class="col-12 text-center">
                    <div class="no-results">
                        <i class="fas fa-search fa-3x mb-3" style="color: var(--knd-neon-blue);"></i>
                        <h3><?php echo t('products.results.no_results'); ?></h3>
                        <p><?php echo t('products.results.try_again'); ?></p>
                        <a href="/products.php" class="btn btn-primary"><?php echo t('products.results.view_all'); ?></a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($productos_filtrados as $producto): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <?php if (in_array($producto['nombre'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])): ?>
                                <div class="product-offer-badge"><?php echo t('product.badge.offer'); ?></div>
                            <?php endif; ?>
                            <div class="product-image">
                                <?php 
                                $producto_tipo = isset($producto['tipo']) ? $producto['tipo'] : 'digital';
                                
                                if ($producto_tipo === 'service'): 
                                    // Placeholder visual para servicios (sin imágenes de ejemplo)
                                ?>
                                    <div class="service-placeholder" style="height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(37, 156, 174, 0.1) 0%, rgba(138, 43, 226, 0.1) 100%); border-bottom: 2px solid var(--knd-electric-purple);">
                                        <i class="fas fa-wand-magic-sparkles fa-4x text-primary mb-3" style="opacity: 0.6;"></i>
                                        <span class="text-white-50 small" style="font-size: 0.85rem;">Original Design Service</span>
                                    </div>
                                    <div class="product-overlay">
                                        <a href="/producto.php?slug=<?php echo $producto['slug']; ?>" class="btn btn-primary">
                                            <i class="fas fa-eye me-2"></i><?php echo t('btn.view_details'); ?>
                                        </a>
                                    </div>
                                <?php else: 
                                    // Imagen normal para productos digital/apparel
                                    $displayImage = $producto['imagen'];
                                    if (isset($producto['gallery']) && !empty($producto['gallery'])) {
                                        $firstGallery = reset($producto['gallery']);
                                        $displayImage = $firstGallery;
                                    }
                                ?>
                                    <img src="/<?php echo htmlspecialchars($displayImage); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                         onerror="this.onerror=null; this.src='/<?php echo htmlspecialchars($producto['imagen']); ?>';">
                                    <div class="product-overlay">
                                        <a href="/producto.php?slug=<?php echo $producto['slug']; ?>" class="btn btn-primary">
                                            <i class="fas fa-eye me-2"></i><?php echo t('btn.view_details'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h5 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="product-description"><?php echo $producto['descripcion']; ?></p>
                                <div class="product-footer">
                                    <?php if (in_array($producto['nombre'], ['Avatar gamer personalizado', 'Wallpaper personalizado IA'])): ?>
                                        <span class="product-price">
                                            <span class="product-price-original">$<?php echo number_format($producto['precio'], 2); ?></span>
                                            <span class="product-price-offer">$2.50</span>
                                        </span>
                                        <?php $precio_real = 2.50; ?>
                                    <?php else: ?>
                                        <span class="product-price">$<?php echo number_format($producto['precio'], 2); ?></span>
                                        <?php $precio_real = $producto['precio']; ?>
                                    <?php endif; ?>
                                    <div class="d-flex gap-2">
                                    <a href="/producto.php?slug=<?php echo $producto['slug']; ?>" class="btn btn-outline-neon btn-sm btn-details">
                                        <?php echo t('btn.view_details'); ?>
                                    </a>
                                        <button 
                                            type="button"
                                            class="btn btn-primary btn-sm add-to-order"
                                            data-id="<?php echo (int)$producto['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-price="<?php echo number_format($precio_real, 2, '.', ''); ?>"
                                            data-type="<?php echo isset($producto['tipo']) ? htmlspecialchars($producto['tipo']) : 'digital'; ?>"
                                        >
                                            <?php echo t('btn.add_to_order'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>

<?php
echo generateFooter();
echo generateScripts();
?> 