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
<?php echo renderAnnouncementBar(); ?>

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
                    <img src="<?php echo $producto['imagen']; ?>" 
                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                         class="product-detail-image">
                </div>
            </div>
            
            <!-- Información del Producto -->
            <div class="col-lg-6">
                <div class="product-info-container">
                    <h1 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                    
                    <div class="product-price-container">
                        <span class="product-price">$<?php echo number_format($producto['precio'], 2); ?></span>
                    </div>
                    
                    <div class="product-description">
                        <?php echo $producto['descripcion']; ?>
                    </div>
                    
                    <div class="product-actions mt-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="<?php echo $link_whatsapp; ?>" 
                                   class="btn btn-whatsapp btn-lg w-100" 
                                   target="_blank">
                                    <i class="fab fa-whatsapp me-2"></i>
                                    Solicitar por WhatsApp
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-discord btn-lg w-100" 
                                        onclick="copyDiscordServer()">
                                    <i class="fab fa-discord me-2"></i>
                                    Contactar por Discord
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="product-meta mt-4">
                        <div class="category-badge">
                            <i class="fas fa-tag me-2"></i>
                            <?php echo ucfirst($producto['categoria']); ?>
                        </div>
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
</script>

<?php 
echo generateFooter();
echo generateScripts();
?> 