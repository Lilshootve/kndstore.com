<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/products-data.php';

// Filtrar solo productos service
$serviceProducts = array_filter($PRODUCTS, function($product) {
    return isset($product['tipo']) && $product['tipo'] === 'service';
});

echo generateHeader('Custom Design Lab', 'Custom Design Lab - Servicios de diseño personalizado. KND Store: Digital Goods • Apparel • Custom Design Services');
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    <span class="text-gradient">Custom Design Lab</span><br>
                    <span class="hero-subtitle-mini">Diseño personalizado a tu medida</span>
                </h1>
                <p class="hero-subtitle">
                    Transforma tus ideas en diseños únicos. Servicios personalizables de diseño para T-Shirts, Hoodies y conceptos completos de outfit.
                </p>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0 text-center">
                <div class="hero-image">
                    <i class="fas fa-palette" style="font-size: 8rem; color: var(--knd-neon-blue); opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Plans -->
<section class="py-5 bg-dark-epic" id="plans">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-layer-group me-2"></i> Planes de Diseño
        </h2>
        <div class="row">
            <?php foreach ($serviceProducts as $slug => $product): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card bg-dark border-primary h-100 product-card">
                        <?php 
                        $displayImage = $product['imagen'];
                        $allImages = [$product['imagen']]; // Array para el lightbox
                        if (isset($product['gallery']) && !empty($product['gallery'])) {
                            $firstGallery = reset($product['gallery']);
                            $displayImage = $firstGallery;
                            $allImages = array_values($product['gallery']); // Todas las imágenes de la gallery
                        }
                        ?>
                        <div class="product-image" style="height: 250px; overflow: hidden; cursor: pointer; position: relative;" 
                             onclick="openImageLightbox(<?php echo htmlspecialchars(json_encode($allImages), ENT_QUOTES | JSON_HEX_APOS | JSON_HEX_QUOT); ?>)">
                            <?php 
                            // Usar rawurlencode para codificar correctamente la ruta (incluye espacios y #)
                            $imageUrl = rawurlencode($displayImage);
                            // Pero mantener las barras / sin codificar
                            $imageUrl = str_replace('%2F', '/', $imageUrl);
                            ?>
                            <img src="/<?php echo $imageUrl; ?>" 
                                 alt="<?php echo htmlspecialchars($product['nombre']); ?>"
                                 class="w-100"
                                 style="height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                 onerror="console.error('Error cargando imagen:', this.src); this.onerror=null; this.style.background='#2c2c2c'; this.style.display='flex'; this.style.alignItems='center'; this.style.justifyContent='center'; this.innerHTML='<i class=\'fas fa-image fa-3x text-muted\'></i>';">
                            <div class="image-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
                                <i class="fas fa-expand fa-2x text-white"></i>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="text-white mb-3"><?php echo htmlspecialchars($product['nombre']); ?></h4>
                            <div class="product-price mb-3">
                                $<?php echo number_format($product['precio'], 2); ?>
                            </div>
                            <div class="text-white-50 mb-4" style="min-height: 80px;">
                                <?php echo strip_tags($product['descripcion']); ?>
                            </div>
                            <a href="/producto.php?slug=<?php echo $slug; ?>" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-cart me-2"></i> Solicitar / Comprar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Brief Form Section -->
<section class="py-5" id="brief-form">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-file-alt me-2"></i> Completa tu Brief
        </h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card knd-card">
                    <div class="card-header">
                        <h4 class="mb-0">Formulario de Brief</h4>
                    </div>
                    <div class="card-body">
                        <form id="custom-design-brief-form">
                            <div class="mb-3">
                                <label class="form-label">Estilo deseado</label>
                                <input type="text" name="estilo" class="form-control" placeholder="Ej: Minimalista, Futurista, Anime, etc.">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Colores preferidos</label>
                                <input type="text" name="colores" class="form-control" placeholder="Ej: Magenta, Turquesa, Negro">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Texto/Nombre a incluir</label>
                                <input type="text" name="texto" class="form-control" placeholder="Texto o nombre que quieres en el diseño">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Referencias</label>
                                <textarea name="referencias" class="form-control" rows="4" placeholder="Describe referencias visuales, estilos que te gustan, o enlaces a imágenes de inspiración"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Detalles extra</label>
                                <textarea name="detalles" class="form-control" rows="3" placeholder="Cualquier detalle adicional que quieras especificar"></textarea>
                            </div>
                            <div class="alert alert-warning" style="background: rgba(255, 193, 7, 0.1); border-color: #ffc107;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Disclaimer:</strong> No aceptamos contenido protegido por derechos de autor o marcas registradas sin autorización del titular.
                            </div>
                            <button type="button" id="save-brief-btn" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i> Guardar Brief (se agregará a tu pedido)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-5 bg-dark-epic" id="how-it-works">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <i class="fas fa-cogs me-2"></i> Cómo funciona
        </h2>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-shopping-cart fa-3x text-primary"></i>
                        </div>
                        <h5 class="text-white mb-3">1. Selecciona el servicio</h5>
                        <p class="text-white-50 small">Elige el plan de diseño que necesitas.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-file-alt fa-3x text-primary"></i>
                        </div>
                        <h5 class="text-white mb-3">2. Completa el brief</h5>
                        <p class="text-white-50 small">Describe tu idea, estilo y referencias.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-palette fa-3x text-primary"></i>
                        </div>
                        <h5 class="text-white mb-3">3. Diseñamos</h5>
                        <p class="text-white-50 small">Creamos tu diseño personalizado.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-download fa-3x text-primary"></i>
                        </div>
                        <h5 class="text-white mb-3">4. Entrega digital</h5>
                        <p class="text-white-50 small">Recibes tus archivos editables y mockups.</p>
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
    box-shadow: 0 10px 50px rgba(0, 191, 255, 0.3);
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
    background: rgba(0, 191, 255, 0.7);
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

@keyframes zoomIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
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

// Guardar brief en localStorage cuando se hace clic en el botón
document.addEventListener('DOMContentLoaded', function() {
    const saveBriefBtn = document.getElementById('save-brief-btn');
    const briefForm = document.getElementById('custom-design-brief-form');
    
    if (saveBriefBtn && briefForm) {
        saveBriefBtn.addEventListener('click', function() {
            const formData = new FormData(briefForm);
            const brief = {
                estilo: formData.get('estilo') || '',
                colores: formData.get('colores') || '',
                texto: formData.get('texto') || '',
                referencias: formData.get('referencias') || '',
                detalles: formData.get('detalles') || ''
            };
            
            // Guardar en localStorage con una clave específica
            localStorage.setItem('knd_custom_design_brief', JSON.stringify(brief));
            
            // Mostrar notificación
            alert('Brief guardado. Cuando agregues un servicio de diseño a tu pedido, este brief se incluirá automáticamente.');
            
            // Limpiar formulario
            briefForm.reset();
        });
    }
});
</script>

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>

