<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/products-data.php';

// Filtrar solo productos apparel
$apparelProducts = array_filter($PRODUCTS, function($product) {
    return isset($product['tipo']) && $product['tipo'] === 'apparel';
});

// Separar en KND Brand y Limited Drops
$kndBrandProducts = [];
$limitedDrops = [];

foreach ($apparelProducts as $slug => $product) {
    if (isset($product['limited']) && $product['limited'] === true) {
        $limitedDrops[$slug] = $product;
    } else {
        $kndBrandProducts[$slug] = $product;
    }
}

echo generateHeader('KND Apparel', 'Ropa galáctica oficial KND - Hoodies, T-Shirts y ediciones limitadas');
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    <span class="text-gradient">KND Apparel</span><br>
                    <span class="hero-subtitle-mini">Ropa galáctica oficial</span>
                </h1>
                <p class="hero-subtitle">
                    Viste con estilo cósmico. Hoodies, T-Shirts y ediciones limitadas diseñadas para tu universo digital.
                </p>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <a href="#knd-brand" class="btn btn-primary btn-lg">
                        <i class="fas fa-tshirt me-2"></i> Ver Colección
                    </a>
                    <a href="/custom-design.php" class="btn btn-outline-neon btn-lg">
                        <i class="fas fa-palette me-2"></i> Custom Design
                    </a>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0 text-center">
                <div class="hero-image">
                    <i class="fas fa-tshirt" style="font-size: 8rem; color: var(--knd-neon-blue); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KND Brand (Official) -->
<section class="py-5 bg-dark-epic" id="knd-brand">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-star me-2"></i> KND Brand (Official)
        </h2>
        <div class="row">
            <?php foreach ($kndBrandProducts as $slug => $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="product-card">
                        <?php if (isset($product['limited']) && $product['limited']): ?>
                            <div class="product-limited-badge">LIMITED</div>
                        <?php endif; ?>
                        <div class="product-image">
                            <?php 
                            $mainImage = $product['imagen'];
                            if (isset($product['variants']) && !empty($product['variants'])) {
                                $firstVariant = reset($product['variants']);
                                $mainImage = $firstVariant['imagen'];
                            }
                            ?>
                            <img src="/<?php echo htmlspecialchars($mainImage); ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['nombre']); ?></h3>
                            <p><?php echo strip_tags($product['descripcion']); ?></p>
                            <div class="product-footer">
                                <span class="product-price">
                                    $<?php echo number_format($product['precio'], 2); ?>
                                    <small class="text-muted d-block">+ Delivery</small>
                                </span>
                                <div class="d-flex gap-2 mt-2">
                                    <a href="/producto.php?slug=<?php echo $slug; ?>" class="btn btn-outline-neon btn-sm btn-details">
                                        Ver detalles
                                    </a>
                                    <button 
                                        type="button"
                                        class="btn btn-primary btn-sm add-to-order"
                                        data-id="<?php echo (int)$product['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($product['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-price="<?php echo number_format($product['precio'], 2, '.', ''); ?>"
                                        data-type="apparel"
                                    >
                                        Añadir al pedido
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Limited Drops -->
<?php if (!empty($limitedDrops)): ?>
<section class="py-5" id="limited-drops">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-gem me-2"></i> Limited Drops
        </h2>
        <div class="row">
            <?php foreach ($limitedDrops as $slug => $product): ?>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="product-card product-card-limited position-relative">
                        <div class="product-limited-badge">LIMITED</div>
                        <div class="product-image position-relative">
                            <?php 
                            $mainImage = $product['imagen'];
                            if (isset($product['gallery']) && isset($product['gallery']['front'])) {
                                $mainImage = $product['gallery']['front'];
                            } elseif (isset($product['variants']) && !empty($product['variants'])) {
                                $firstVariant = reset($product['variants']);
                                if (isset($firstVariant['imagen'])) {
                                    $mainImage = $firstVariant['imagen'];
                                }
                            }
                            ?>
                            <img src="/<?php echo htmlspecialchars($mainImage); ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>" 
                                 onerror="this.onerror=null; this.src='/<?php echo htmlspecialchars($product['imagen']); ?>';"
                                 style="width: 100%; height: 300px; object-fit: cover;">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['nombre']); ?></h3>
                            <p><?php echo strip_tags($product['descripcion']); ?></p>
                            <div class="product-footer">
                                <span class="product-price">
                                    $<?php echo number_format($product['precio'], 2); ?>
                                    <small class="text-muted d-block">+ Delivery</small>
                                </span>
                                <div class="d-flex gap-2 mt-2">
                                    <a href="/producto.php?slug=<?php echo $slug; ?>" class="btn btn-outline-neon btn-sm btn-details">
                                        Ver detalles
                                    </a>
                                    <button 
                                        type="button"
                                        class="btn btn-primary btn-sm add-to-order"
                                        data-id="<?php echo (int)$product['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($product['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-price="<?php echo number_format($product['precio'], 2, '.', ''); ?>"
                                        data-type="apparel"
                                    >
                                        Añadir al pedido
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- How it works -->
<section class="py-5 bg-dark-epic" id="how-it-works">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-question-circle me-2"></i> Cómo funciona
        </h2>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-shopping-cart fa-3x text-primary"></i>
                        </div>
                        <h4 class="text-white mb-3">1. Selecciona tu prenda</h4>
                        <p class="text-white-50">Elige tu hoodie o t-shirt favorita, talla y color.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-box fa-3x text-primary"></i>
                        </div>
                        <h4 class="text-white mb-3">2. Inventario pequeño</h4>
                        <p class="text-white-50">Manejamos inventario limitado para garantizar calidad.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-truck fa-3x text-primary"></i>
                        </div>
                        <h4 class="text-white mb-3">3. Delivery coordinado</h4>
                        <p class="text-white-50">Te contactamos por WhatsApp/medios para coordinar entrega y detalles.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info mt-4 text-center" style="background: rgba(0, 191, 255, 0.1); border-color: var(--knd-neon-blue);">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Nota importante:</strong> Delivery se coordina por WhatsApp/medios de contacto luego de la compra.
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-5" id="faq">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-question-circle me-2"></i> Preguntas Frecuentes
        </h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                ¿Qué tallas están disponibles?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Disponemos de tallas S, M, L y XL. Consulta la guía de tallas en cada producto para más detalles.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ¿Cómo funciona el delivery?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Después de realizar tu pedido, te contactaremos por WhatsApp o medios de contacto para coordinar la entrega, método de pago y dirección de envío.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                ¿Aceptan devoluciones?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Aceptamos cambios y devoluciones dentro de los primeros 7 días después de la entrega, siempre que el producto esté en su estado original. Contacta con nosotros por WhatsApp para coordinar.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>

