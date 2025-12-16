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
                    <img src="/assets/images/Pants Desing 001 Turquesa.png" 
                         alt="Custom Design Lab Hero" 
                         class="img-fluid"
                         style="max-height: 420px; object-fit: contain; filter: drop-shadow(0 0 40px rgba(37, 156, 174, 0.6));">
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
                        <!-- Service Visual Placeholder -->
                        <div class="service-placeholder" style="height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(37, 156, 174, 0.1) 0%, rgba(138, 43, 226, 0.1) 100%); border-bottom: 2px solid var(--knd-electric-purple);">
                            <i class="fas fa-wand-magic-sparkles fa-4x text-primary mb-3" style="opacity: 0.6;"></i>
                            <span class="text-white-50 small" style="font-size: 0.85rem;">Original Design Service</span>
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
                                <label class="form-label">Referencias de estilo (no personajes ni marcas)</label>
                                <textarea name="referencias" class="form-control" rows="4" placeholder="Describe referencias visuales de estilo, estilos que te gustan, o enlaces a imágenes de inspiración (sin personajes ni marcas protegidas)"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Detalles extra</label>
                                <textarea name="detalles" class="form-control" rows="3" placeholder="Cualquier detalle adicional que quieras especificar"></textarea>
                            </div>
                    <div class="alert alert-warning" style="background: rgba(255, 193, 7, 0.1); border-color: #ffc107;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Propiedad Intelectual & Producción:</strong>
                        Este servicio incluye <strong>diseño gráfico original</strong> y <strong>producción impresa bajo pedido</strong>.
                        No realizamos ni imprimimos diseños que reproduzcan personajes, marcas, logotipos o material protegido por derechos de autor sin autorización verificable del titular.
                    </div>
                    
                    <p class="text-white-50 small mb-0">
                        Si el brief contiene referencias no autorizadas, <strong>KND Store</strong> podrá ajustar la propuesta creativa o rechazar el pedido antes de producción,
                        sin obligación de replicar la referencia original.
                    </p>
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

<script>
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

