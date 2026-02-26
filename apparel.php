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

$coreSlugs = [
    'hoodie-knd-style',
    'tshirt-knd-oversize',
    'hoodie-knd-black-edition',
];

$dropSlugs = [
    'hoodie-anime-style',
    'hoodie-dark-eyes-style',
];

function getProductsBySlugs(array $products, array $slugs, array &$missingSlugs = []): array {
    $result = [];
    foreach ($slugs as $slug) {
        if (isset($products[$slug])) {
            $result[$slug] = $products[$slug];
        } else {
            $missingSlugs[] = $slug;
        }
    }
    return $result;
}

function renderApparelCard(string $slug, array $product, string $badgeLabel, string $badgeClass, string $cardClass = ''): string {
    $cardClass = trim('product-card apparel-card ' . $cardClass);
    $allImages = [];
    $mainImage = $product['imagen'];

    if (isset($product['gallery']) && !empty($product['gallery'])) {
        $allImages = array_values($product['gallery']);
        $mainImage = $allImages[0];
    } elseif (isset($product['variants']) && !empty($product['variants'])) {
        foreach ($product['variants'] as $variant) {
            if (isset($variant['imagen']) && !in_array($variant['imagen'], $allImages, true)) {
                $allImages[] = $variant['imagen'];
            }
        }
        if (!empty($allImages)) {
            $mainImage = $allImages[0];
        } else {
            $firstVariant = reset($product['variants']);
            $mainImage = $firstVariant['imagen'];
            $allImages = [$mainImage];
        }
    } else {
        $allImages = [$mainImage];
    }

    $imageUrl = rawurlencode($mainImage);
    $imageUrl = str_replace('%2F', '/', $imageUrl);

    ob_start();
    ?>
    <div class="<?php echo htmlspecialchars($cardClass); ?>">
        <span class="apparel-badge <?php echo htmlspecialchars($badgeClass); ?>">
            <?php echo htmlspecialchars($badgeLabel); ?>
        </span>
        <div class="product-image position-relative" style="cursor: pointer;"
             onclick="openImageLightbox(<?php echo htmlspecialchars(json_encode($allImages), ENT_QUOTES | JSON_HEX_APOS | JSON_HEX_QUOT); ?>)">
            <img src="/<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>"
                 onerror="this.onerror=null; this.src='/<?php echo htmlspecialchars($product['imagen']); ?>';">
            <div class="image-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; pointer-events: none;">
                <i class="fas fa-expand fa-2x text-white"></i>
            </div>
        </div>
        <div class="product-info">
            <h3><?php echo htmlspecialchars($product['nombre']); ?></h3>
            <p><?php echo strip_tags($product['descripcion']); ?></p>
            <div class="product-footer">
                <span class="product-price">
                    $<?php echo number_format($product['precio'], 2); ?>
                    <small class="text-muted d-block"><?php echo t('product.label.plus_delivery'); ?></small>
                </span>
                <div class="d-flex gap-2 mt-2">
                    <a href="/producto.php?slug=<?php echo $slug; ?>" class="btn btn-outline-neon btn-sm btn-details">
                        <?php echo t('btn.view_details'); ?>
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
    <?php
    return ob_get_clean();
}

$missingCore = [];
$missingDrops = [];
$coreProducts = getProductsBySlugs($apparelProducts, $coreSlugs, $missingCore);
$dropProducts = getProductsBySlugs($apparelProducts, $dropSlugs, $missingDrops);

foreach (array_merge($missingCore, $missingDrops) as $missingSlug) {
    error_log('Apparel missing product slug: ' . $missingSlug);
}

$assignedSlugs = array_flip(array_merge($coreSlugs, $dropSlugs));
$otherProducts = array_diff_key($apparelProducts, $assignedSlugs);

echo generateHeader(
    'KND Apparel — Knowledge and Development',
    'KND Apparel — Knowledge and Development. Streetwear esencial y drops limitados.'
);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section hero-apparel-bg">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    <span class="text-gradient">KND Apparel — Knowledge and Development</span>
                </h1>
                <p class="hero-subtitle">
                    Diseñado para quienes crean, evolucionan y construyen.
                </p>
                <p class="hero-subtitle hero-subtitle-secondary">
                    Colecciones curadas: CORE (esencial) + DROPS (limitados).
                </p>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <a href="#core" class="btn btn-primary btn-lg" data-scroll>
                        <i class="fas fa-tshirt me-2"></i> VER COLECCIÓN
                    </a>
                    <a href="#custom-design" class="btn btn-outline-neon btn-lg" data-scroll>
                        <i class="fas fa-palette me-2"></i> DISEÑO PERSONALIZADO
                    </a>
                </div>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0 text-center">
                <div class="hero-image"></div>
            </div>
        </div>
    </div>
</section>

<div class="apparel-anchor-nav">
    <div class="container">
        <div class="apparel-anchor-pills">
            <a href="#core" class="apparel-pill" data-scroll>CORE</a>
            <a href="#drops" class="apparel-pill" data-scroll>DROPS</a>
            <a href="#how" class="apparel-pill" data-scroll>CÓMO FUNCIONA</a>
            <a href="#faq" class="apparel-pill" data-scroll>FAQ</a>
        </div>
    </div>
</div>

<!-- CORE Collection -->
<section class="py-5 bg-dark-epic apparel-section" id="core">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            CORE COLLECTION
        </h2>
        <p class="apparel-section-subtitle text-center">
            Línea esencial. Minimalismo, identidad y mentalidad.
        </p>
        <div class="row">
            <?php foreach ($coreProducts as $slug => $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="reveal-on-scroll">
                        <?php echo renderApparelCard($slug, $product, 'CORE', 'apparel-badge-core'); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="apparel-divider"></div>

<!-- Limited Drops -->
<?php if (!empty($dropProducts)): ?>
<section class="py-5 apparel-section" id="drops">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            LIMITED DROPS
        </h2>
        <p class="apparel-section-subtitle text-center">
            Piezas experimentales y ediciones limitadas. Cuando se van, se fueron.
        </p>
        <div class="row">
            <?php foreach ($dropProducts as $slug => $product): ?>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="reveal-on-scroll">
                        <?php echo renderApparelCard($slug, $product, 'DROP', 'apparel-badge-drop', 'product-card-limited'); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($otherProducts)): ?>
<section class="py-5 bg-dark-epic apparel-section" id="otros">
    <div class="container">
        <h2 class="section-title text-center mb-5">OTROS</h2>
        <p class="apparel-section-subtitle text-center">
            Piezas extra disponibles por tiempo limitado o temporadas especiales.
        </p>
        <div class="row">
            <?php foreach ($otherProducts as $slug => $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="reveal-on-scroll">
                        <?php echo renderApparelCard($slug, $product, 'OTRO', 'apparel-badge-core'); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Custom Design -->
<section class="py-5 bg-dark-epic apparel-section" id="custom-design">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h2 class="section-title mb-4">Custom Design</h2>
                <p class="apparel-section-subtitle">
                    Diseños personalizados con identidad propia. Creamos piezas únicas alineadas a tu visión y tu comunidad.
                </p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="/custom-design.php" class="btn btn-outline-neon btn-lg">
                    <i class="fas fa-palette me-2"></i> Start Custom Design
                </a>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-5 bg-dark-epic apparel-section" id="how">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-question-circle me-2"></i> <?php echo t('apparel.section.how_it_works.title'); ?>
        </h2>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-shopping-cart fa-3x text-primary"></i>
                        </div>
                        <h4 class="text-white mb-3">1. Elige tu línea</h4>
                        <p class="text-white-50">CORE (esencial) o DROPS (limitado).</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-box fa-3x text-primary"></i>
                        </div>
                        <h4 class="text-white mb-3">2. Talla y estilo</h4>
                        <p class="text-white-50">Fit claro, sin sorpresas.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-truck fa-3x text-primary"></i>
                        </div>
                        <h4 class="text-white mb-3">3. Checkout y listo</h4>
                        <p class="text-white-50">Confirmación inmediata.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-clock fa-3x text-primary"></i>
                        </div>
                        <h4 class="text-white mb-3">4. Producción y despacho</h4>
                        <p class="text-white-50">Tiempos transparentes según disponibilidad.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info mt-4 text-center" style="background: rgba(37, 156, 174, 0.1); border-color: var(--knd-neon-blue);">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Nota importante:</strong> Te confirmamos tiempos y tracking por tus canales activos.
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-5 apparel-section" id="faq">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-question-circle me-2"></i> <?php echo t('apparel.section.faq.title'); ?>
        </h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                ¿CORE se agota?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Puede, pero se repone. Es la línea estable.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ¿DROPS vuelven?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Normalmente no. Son cápsulas limitadas.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                ¿Cómo elijo mi talla?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Usa la guía; si estás entre dos, sube una.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                ¿Puedo pedir un diseño personalizado?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Sí, en “Custom Design”.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                ¿Hacen envíos internacionales?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Política pendiente de confirmar. Ajusta aquí tu política real.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-dark border-primary mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                ¿Qué pasa si no me queda?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-white-50">
                                Política pendiente de confirmar. Ajusta aquí tu política real.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hero-subtitle-secondary {
    color: rgba(255, 255, 255, 0.75);
    font-size: 1rem;
}

.apparel-anchor-nav {
    position: sticky;
    top: 90px;
    z-index: 50;
    padding: 1rem 0;
    background: rgba(8, 8, 20, 0.7);
    backdrop-filter: blur(12px);
    border-top: 1px solid rgba(37, 156, 174, 0.2);
    border-bottom: 1px solid rgba(174, 37, 101, 0.2);
}

.apparel-anchor-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
}

.apparel-pill {
    padding: 0.5rem 1.25rem;
    border-radius: 999px;
    border: 1px solid rgba(37, 156, 174, 0.45);
    color: var(--knd-white);
    text-decoration: none;
    font-size: 0.85rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    transition: all 0.25s ease;
    box-shadow: 0 0 12px rgba(37, 156, 174, 0.2);
    background: rgba(8, 8, 20, 0.6);
}

.apparel-pill:hover {
    color: var(--knd-white);
    border-color: rgba(174, 37, 101, 0.6);
    box-shadow: 0 0 16px rgba(174, 37, 101, 0.35);
    transform: translateY(-2px);
}

.apparel-section-subtitle {
    max-width: 720px;
    margin: 0 auto 2.5rem;
    color: rgba(255, 255, 255, 0.75);
}

.apparel-section {
    scroll-margin-top: 120px;
}

.apparel-anchor-nav + .apparel-section {
    padding-top: 1.5rem;
}

.apparel-divider {
    height: 2px;
    margin: 0 auto;
    max-width: 820px;
    background: linear-gradient(90deg, transparent, rgba(37, 156, 174, 0.7), rgba(174, 37, 101, 0.7), transparent);
    box-shadow: 0 0 18px rgba(37, 156, 174, 0.4);
}

.apparel-card {
    position: relative;
    transition: transform 0.25s ease-out, box-shadow 0.25s ease-out, border-color 0.25s ease-out;
}

.apparel-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.4);
    border-color: rgba(37, 156, 174, 0.6);
}

.apparel-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    padding: 0.35rem 0.75rem;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    border-radius: 999px;
    text-transform: uppercase;
    z-index: 2;
}

.apparel-badge-core {
    background: rgba(0, 212, 255, 0.15);
    color: #7be8ff;
    border: 1px solid rgba(0, 212, 255, 0.6);
    box-shadow: 0 0 12px rgba(0, 212, 255, 0.35);
}

.apparel-badge-drop {
    background: rgba(174, 37, 101, 0.18);
    color: #ff9ad0;
    border: 1px solid rgba(174, 37, 101, 0.6);
    box-shadow: 0 0 12px rgba(174, 37, 101, 0.35);
}

.reveal-on-scroll {
    opacity: 0;
    transform: translateY(12px);
    transition: opacity 0.3s ease-out, transform 0.3s ease-out;
}

.reveal-on-scroll.is-visible {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 992px) {
    .apparel-anchor-nav {
        top: 80px;
    }
}

@media (max-width: 768px) {
    .apparel-anchor-nav {
        top: 70px;
        padding: 0.75rem 0;
    }
}

@media (prefers-reduced-motion: reduce) {
    .reveal-on-scroll,
    .apparel-card {
        transition: none;
        transform: none;
    }
}

/* Lightbox para imágenes */
.image-lightbox {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.95);
    overflow: auto;
}

.image-lightbox.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    margin: auto;
    animation: zoomIn 0.3s ease;
}

.lightbox-content img {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 10px 50px rgba(37, 156, 174, 0.3);
}

.lightbox-close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10000;
    transition: color 0.3s ease;
}

.lightbox-close:hover {
    color: var(--knd-neon-blue);
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #fff;
    font-size: 30px;
    cursor: pointer;
    padding: 15px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    transition: all 0.3s ease;
    z-index: 10000;
}

.lightbox-nav:hover {
    background: rgba(37, 156, 174, 0.7);
    color: #fff;
}

.lightbox-prev {
    left: 20px;
}

.lightbox-next {
    right: 20px;
}

.lightbox-counter {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    background: rgba(0, 0, 0, 0.7);
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 14px;
}

.product-image:hover .image-overlay {
    opacity: 1 !important;
}

.product-image:hover img {
    transform: scale(1.05);
}

.product-image {
    transition: all 0.3s ease;
}
</style>

<!-- Lightbox Modal -->
<div id="image-lightbox" class="image-lightbox">
    <span class="lightbox-close" onclick="closeImageLightbox()">&times;</span>
    <span class="lightbox-nav lightbox-prev" onclick="changeLightboxImage(-1)">&#10094;</span>
    <span class="lightbox-nav lightbox-next" onclick="changeLightboxImage(1)">&#10095;</span>
    <div class="lightbox-content">
        <img id="lightbox-image" src="" alt="Imagen ampliada">
    </div>
    <div class="lightbox-counter">
        <span id="lightbox-counter-text">1 / 1</span>
    </div>
</div>

<script>
// Variables globales para el lightbox
let lightboxImages = [];
let currentLightboxIndex = 0;

// Abrir lightbox con imagen
function openImageLightbox(imagesArray) {
    if (!imagesArray || imagesArray.length === 0) {
        console.error('No hay imágenes para mostrar');
        return;
    }
    
    lightboxImages = imagesArray;
    currentLightboxIndex = 0;
    
    const lightbox = document.getElementById('image-lightbox');
    const lightboxImg = document.getElementById('lightbox-image');
    const counter = document.getElementById('lightbox-counter-text');
    
    // Codificar la ruta correctamente para URLs (manejar espacios y caracteres especiales)
    let firstImage = lightboxImages[0];
    // Usar encodeURIComponent pero mantener las barras /
    firstImage = firstImage.split('/').map(part => encodeURIComponent(part)).join('/');
    lightboxImg.src = '/' + firstImage;
    counter.textContent = `1 / ${lightboxImages.length}`;
    
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Mostrar/ocultar navegación según cantidad de imágenes
    const prevBtn = document.querySelector('.lightbox-prev');
    const nextBtn = document.querySelector('.lightbox-next');
    if (lightboxImages.length > 1) {
        prevBtn.style.display = 'block';
        nextBtn.style.display = 'block';
    } else {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
    }
}

// Cerrar lightbox
function closeImageLightbox() {
    const lightbox = document.getElementById('image-lightbox');
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
}

// Cambiar imagen en el lightbox
function changeLightboxImage(direction) {
    currentLightboxIndex += direction;
    
    if (currentLightboxIndex < 0) {
        currentLightboxIndex = lightboxImages.length - 1;
    } else if (currentLightboxIndex >= lightboxImages.length) {
        currentLightboxIndex = 0;
    }
    
    const lightboxImg = document.getElementById('lightbox-image');
    const counter = document.getElementById('lightbox-counter-text');
    
    // Codificar la ruta correctamente para URLs (manejar espacios y caracteres especiales)
    let imagePath = lightboxImages[currentLightboxIndex];
    // Usar encodeURIComponent pero mantener las barras /
    imagePath = imagePath.split('/').map(part => encodeURIComponent(part)).join('/');
    lightboxImg.src = '/' + imagePath;
    counter.textContent = `${currentLightboxIndex + 1} / ${lightboxImages.length}`;
}

// Cerrar con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageLightbox();
    } else if (e.key === 'ArrowLeft') {
        changeLightboxImage(-1);
    } else if (e.key === 'ArrowRight') {
        changeLightboxImage(1);
    }
});

// Cerrar al hacer clic fuera de la imagen
document.getElementById('image-lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageLightbox();
    }
});
</script>

<script>
document.querySelectorAll('[data-scroll]').forEach((link) => {
    link.addEventListener('click', (event) => {
        const targetId = link.getAttribute('href');
        if (!targetId || !targetId.startsWith('#')) {
            return;
        }
        const target = document.querySelector(targetId);
        if (!target) {
            return;
        }
        event.preventDefault();
        const nav = document.querySelector('.navbar');
        const pills = document.querySelector('.apparel-anchor-nav');
        const offset = (nav ? nav.offsetHeight : 0) + (pills ? pills.offsetHeight : 0) + 16;
        const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top, behavior: 'smooth' });
    });
});

const revealItems = document.querySelectorAll('.reveal-on-scroll');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (prefersReducedMotion) {
    revealItems.forEach((item) => item.classList.add('is-visible'));
} else if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    revealItems.forEach((item) => observer.observe(item));
} else {
    revealItems.forEach((item) => item.classList.add('is-visible'));
}
</script>

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>

