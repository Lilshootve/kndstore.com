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

echo generateHeader('KND Apparel', 'KND Apparel - Ropa galáctica oficial. Hoodies, T-Shirts y ediciones limitadas. Digital Goods • Apparel • Custom Design Services');
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
                        <?php 
                        // Recopilar todas las imágenes disponibles para el lightbox
                        $allImages = [];
                        $mainImage = $product['imagen'];
                        
                        // Si hay gallery, usar esas imágenes
                        if (isset($product['gallery']) && !empty($product['gallery'])) {
                            $allImages = array_values($product['gallery']);
                            $mainImage = $allImages[0];
                        } 
                        // Si hay variants, recopilar todas las imágenes de los variants
                        elseif (isset($product['variants']) && !empty($product['variants'])) {
                            foreach ($product['variants'] as $variant) {
                                if (isset($variant['imagen']) && !in_array($variant['imagen'], $allImages)) {
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
                        
                        // Codificar la ruta para URL
                        $imageUrl = rawurlencode($mainImage);
                        $imageUrl = str_replace('%2F', '/', $imageUrl);
                        ?>
                        <div class="product-image" style="cursor: pointer; position: relative;" 
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
                        <?php 
                        // Recopilar todas las imágenes disponibles para el lightbox
                        $allImages = [];
                        $mainImage = $product['imagen'];
                        
                        // Si hay gallery, usar esas imágenes
                        if (isset($product['gallery']) && !empty($product['gallery'])) {
                            $allImages = array_values($product['gallery']);
                            $mainImage = $allImages[0];
                        } 
                        // Si hay variants, recopilar todas las imágenes de los variants
                        elseif (isset($product['variants']) && !empty($product['variants'])) {
                            foreach ($product['variants'] as $variant) {
                                if (isset($variant['imagen']) && !in_array($variant['imagen'], $allImages)) {
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
                        
                        // Codificar la ruta para URL
                        $imageUrl = rawurlencode($mainImage);
                        $imageUrl = str_replace('%2F', '/', $imageUrl);
                        ?>
                        <div class="product-image position-relative" style="cursor: pointer;" 
                             onclick="openImageLightbox(<?php echo htmlspecialchars(json_encode($allImages), ENT_QUOTES | JSON_HEX_APOS | JSON_HEX_QUOT); ?>)">
                            <img src="/<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($product['nombre']); ?>" 
                                 onerror="this.onerror=null; this.src='/<?php echo htmlspecialchars($product['imagen']); ?>';"
                                 style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
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
        <div class="alert alert-info mt-4 text-center" style="background: rgba(37, 156, 174, 0.1); border-color: var(--knd-neon-blue);">
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

<style>
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

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>

