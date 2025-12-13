<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/products-data.php';

// Obtener el slug del producto desde la URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Buscar el producto por slug en la fuente centralizada
$producto = $PRODUCTS[$slug] ?? null;

// Si no se encuentra el producto, redirigir a la página principal
if (!$producto) {
    header('Location: /products.php');
    exit();
}

// Generar mensaje de WhatsApp
$mensaje_whatsapp = urlencode("Hola, me interesa el servicio: " . $producto['nombre'] . " - $" . number_format($producto['precio'], 2));
$link_whatsapp = "https://wa.me/584246661334?text=" . $mensaje_whatsapp;

?>

<?php echo generateHeader($producto['nombre'], 'Detalles del servicio ' . $producto['nombre'] . ' - KND Store'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="/index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="/products.php">Catálogo</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($producto['nombre']); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Producto Detalle -->
<section class="product-detail-section py-5">
    <div class="container">
        <div class="row">
            <!-- Imagen del Producto -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-container">
                    <?php if (isset($producto['gallery']) && !empty($producto['gallery'])): ?>
                        <!-- Gallery para productos con múltiples imágenes -->
                        <div id="product-gallery">
                            <div class="main-image mb-3">
                                <img src="/<?php echo htmlspecialchars($producto['gallery']['front'] ?? $producto['imagen']); ?>" 
                                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                                     class="product-detail-image" id="main-product-image"
                                     onerror="this.onerror=null; this.src='/<?php echo htmlspecialchars($producto['imagen']); ?>';">
                            </div>
                            <div class="gallery-thumbnails d-flex gap-2">
                                <?php foreach ($producto['gallery'] as $view => $image): ?>
                                    <img src="/<?php echo htmlspecialchars($image); ?>" 
                                         alt="<?php echo htmlspecialchars($view); ?>" 
                                         class="gallery-thumb <?php echo $view === 'front' ? 'active' : ''; ?>"
                                         data-view="<?php echo htmlspecialchars($view); ?>"
                                         style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent;"
                                         onclick="changeMainImage('<?php echo htmlspecialchars($image); ?>', this)">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <img src="/<?php echo $producto['imagen']; ?>" 
                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                             class="product-detail-image" id="main-product-image">
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Información del Producto -->
            <div class="col-lg-6">
                <div class="product-info-container">
                    <h1 class="product-title">
                        <?php echo htmlspecialchars($producto['nombre']); ?>
                        <?php if (isset($producto['limited']) && $producto['limited']): ?>
                            <span class="badge bg-danger ms-2">LIMITED</span>
                        <?php endif; ?>
                    </h1>
                    
                    <div class="product-price-container">
                        <span class="product-price">$<?php echo number_format($producto['precio'], 2); ?></span>
                        <?php if (isset($producto['tipo']) && $producto['tipo'] === 'apparel'): ?>
                            <small class="text-muted d-block">+ Delivery</small>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-description">
                        <?php echo $producto['descripcion']; ?>
                    </div>
                    
                    <?php if (isset($producto['tipo']) && $producto['tipo'] === 'apparel' && isset($producto['variants'])): ?>
                        <!-- Variants para Apparel -->
                        <div class="product-variants mt-4">
                            <h5 class="mb-3">Selecciona tu variante:</h5>
                            
                            <!-- Selector de Color -->
                            <?php if (count($producto['variants']) > 1): ?>
                                <div class="mb-3">
                                    <label class="form-label">Color</label>
                                    <select id="variant-color" class="form-select">
                                        <option value="">Selecciona un color</option>
                                        <?php foreach ($producto['variants'] as $colorKey => $variant): ?>
                                            <option value="<?php echo htmlspecialchars($colorKey); ?>" 
                                                    data-image="<?php echo htmlspecialchars($variant['imagen']); ?>">
                                                <?php echo ucfirst($colorKey); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <?php 
                                $firstVariant = reset($producto['variants']);
                                $defaultColor = key($producto['variants']);
                                ?>
                                <input type="hidden" id="variant-color" value="<?php echo htmlspecialchars($defaultColor); ?>">
                            <?php endif; ?>
                            
                            <!-- Selector de Talla -->
                            <div class="mb-3">
                                <label class="form-label">Talla</label>
                                <select id="variant-size" class="form-select" required>
                                    <option value="">Selecciona una talla</option>
                                    <?php 
                                    $sizes = ['S', 'M', 'L', 'XL'];
                                    if (isset($producto['variants'])) {
                                        $firstVariant = reset($producto['variants']);
                                        if (isset($firstVariant['sizes'])) {
                                            $sizes = $firstVariant['sizes'];
                                        }
                                    }
                                    foreach ($sizes as $size): ?>
                                        <option value="<?php echo htmlspecialchars($size); ?>"><?php echo htmlspecialchars($size); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <?php if (isset($producto['tipo']) && $producto['tipo'] === 'apparel'): ?>
                                <div class="alert alert-info mt-3" style="background: rgba(0, 191, 255, 0.1); border-color: var(--knd-neon-blue);">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Delivery:</strong> Se coordina por WhatsApp/medios de contacto luego de la compra.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($producto['tipo']) && $producto['tipo'] === 'service'): ?>
                        <div class="alert alert-warning mt-3" style="background: rgba(255, 193, 7, 0.1); border-color: #ffc107;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Disclaimer:</strong> No aceptamos contenido protegido por derechos de autor o marcas registradas sin autorización del titular.
                        </div>
                        <div class="mt-3">
                            <a href="/custom-design.php" class="btn btn-outline-neon">
                                <i class="fas fa-palette me-2"></i> Completar Brief
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-actions mt-4">
                        <div class="row">
                            <?php if (isset($producto['tipo']) && $producto['tipo'] === 'apparel'): ?>
                                <div class="col-12 mb-3">
                                    <button 
                                        type="button"
                                        class="btn btn-primary btn-lg w-100 add-to-order"
                                        data-id="<?php echo (int)$producto['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-price="<?php echo number_format($producto['precio'], 2, '.', ''); ?>"
                                        data-type="apparel"
                                        id="add-apparel-btn"
                                    >
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Añadir al pedido
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6 mb-3">
                                    <button 
                                        type="button"
                                        class="btn btn-primary btn-lg w-100 add-to-order"
                                        data-id="<?php echo (int)$producto['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-price="<?php echo number_format($producto['precio'], 2, '.', ''); ?>"
                                        data-type="<?php echo isset($producto['tipo']) ? htmlspecialchars($producto['tipo']) : 'digital'; ?>"
                                    >
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Añadir al pedido
                                    </button>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-6 mb-3">
                                <a href="<?php echo $link_whatsapp; ?>" 
                                   class="btn btn-whatsapp btn-lg w-100" 
                                   target="_blank">
                                    <i class="fab fa-whatsapp me-2"></i>
                                    Solicitar por WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="product-meta mt-4">
                        <div class="category-badge">
                            <i class="fas fa-tag me-2"></i>
                            <?php echo ucfirst($producto['categoria']); ?>
                        </div>
                        <?php if (isset($producto['tipo'])): ?>
                            <div class="category-badge mt-2">
                                <i class="fas fa-layer-group me-2"></i>
                                <?php echo ucfirst($producto['tipo']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botón Volver -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="/products.php" class="btn btn-outline-neon btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver a la tienda
                </a>
            </div>
        </div>
    </div>
</section>

<script>
function copyDiscordServer() {
    navigator.clipboard.writeText('knd_store').then(function() {
        // Mostrar notificación
        const notification = document.createElement('div');
        notification.className = 'discord-notification';
        notification.innerHTML = '<i class="fab fa-discord me-2"></i>Servidor copiado: knd_store';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
        alert('Servidor Discord: knd_store');
    });
}

// Cambiar imagen principal en gallery
function changeMainImage(imageSrc, thumbElement) {
    document.getElementById('main-product-image').src = '/' + imageSrc;
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
        thumb.style.border = '2px solid transparent';
    });
    thumbElement.classList.add('active');
    thumbElement.style.border = '2px solid var(--knd-neon-blue)';
}

// Manejar cambio de color en variants
document.addEventListener('DOMContentLoaded', function() {
    const colorSelect = document.getElementById('variant-color');
    const mainImage = document.getElementById('main-product-image');
    
    if (colorSelect && mainImage) {
        colorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.dataset.image) {
                mainImage.src = '/' + selectedOption.dataset.image;
            }
        });
    }
    
    // Validar variants antes de agregar al pedido (apparel)
    const addApparelBtn = document.getElementById('add-apparel-btn');
    if (addApparelBtn) {
        addApparelBtn.addEventListener('click', function() {
            const color = document.getElementById('variant-color')?.value;
            const size = document.getElementById('variant-size')?.value;
            
            if (!size) {
                alert('Por favor selecciona una talla antes de agregar al pedido.');
                return;
            }
            
            // Agregar metadata de variants al botón
            this.dataset.variantColor = color || '';
            this.dataset.variantSize = size;
        });
    }
});
</script>

<?php 
echo generateFooter();
echo generateScripts();
?> 