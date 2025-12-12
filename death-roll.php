<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
?>

<?php echo generateHeader('Death Roll', 'Death Roll - Minijuego galáctico de riesgo y recompensas digitales en KND Store'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5 deathroll-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    <span class="text-gradient">Death Roll</span><br>
                    <span class="hero-subtitle-mini">Riesgo, suerte y loot digital</span>
                </h1>
                <p class="hero-subtitle">
                    Un minijuego galáctico donde apuestas fortuna y terminas con
                    <strong>loot digital, claves, avatares, wallpapers</strong> y más.  
                    Todo manejado por <strong>KND (Knowledge 'N Development)</strong>, con reglas claras y diversión controlada.
                </p>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <a href="/producto.php?slug=death-roll-crate" class="btn btn-primary btn-lg">
                        <i class="fas fa-dice me-2"></i> Comprar Death Roll Crate
                    </a>
                    <button class="btn btn-outline-neon btn-lg" onclick="copyDiscordServer()">
                        <i class="fab fa-discord me-2"></i> Jugar en Discord
                    </button>
                </div>
                <p class="mt-3 small text-white" style="opacity: 0.7;">
                    * El juego y las recompensas se gestionan por <strong>Discord / WhatsApp</strong> de forma manual, 
                    con registro de resultados y capturas.
                </p>
            </div>
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="deathroll-card-glass">
                    <h3 class="mb-3">
                        <i class="fas fa-dice-d20 me-2"></i> Cómo funciona
                    </h3>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Eliges tu Death Roll Crate.</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Jugamos la ronda en Discord.</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Registramos los rolls y el resultado.</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i> Recibes tu loot digital.</li>
                    </ul>
                    <p class="small text-warning mb-0">
                        No es gambling con dinero dentro del sitio.  
                        Tú compras un servicio digital (Death Roll Crate) y el minijuego decide el tipo de recompensa.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: ¿Qué es Death Roll? -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    ¿Qué es <span class="text-gradient">Death Roll</span>?
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    Death Roll es un <strong>minijuego de riesgo controlado</strong> donde el resultado se traduce en 
                    <strong>recompensas digitales</strong> gestionadas por KND Store: claves, contenido exclusivo, 
                    avatares, wallpapers, descuentos y más.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="knd-feature-card h-100">
                    <h4><i class="fas fa-dice-six me-2"></i> RNG con estilo</h4>
                    <p class="text-white">
                        Usamos tiradas visibles en Discord para que veas el resultado en tiempo real. 
                        Nada escondido, todo transparente y público en el canal correspondiente.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="knd-feature-card h-100">
                    <h4><i class="fas fa-gift me-2"></i> Loot digital</h4>
                    <p class="text-white">
                        Cada Death Roll Crate garantiza una recompensa digital.  
                        Lo que cambia es la <strong>rareza y el valor</strong> de lo que recibes.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="knd-feature-card h-100">
                    <h4><i class="fas fa-shield-alt me-2"></i> Control y registro</h4>
                    <p class="text-white">
                        Todas las rondas quedan documentadas (mensajes, capturas) para mantener 
                        <strong>historial y transparencia</strong> en cada sesión.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: Cómo se juega -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    <i class="fas fa-route me-2"></i> Cómo se juega
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    Simple, directo y con sabor a casino galáctico, pero controlado por 
                    <strong>Knowledge 'N Development</strong>.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="knd-step-card">
                    <span class="knd-step-number">1</span>
                    <h5>Compras tu Crate</h5>
                    <p class="text-white">Adquieres la <strong>Death Roll Crate</strong> desde el catálogo o el botón de esta página.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-step-card">
                    <span class="knd-step-number">2</span>
                    <h5>Entramos al canal</h5>
                    <p class="text-white">Te unes al canal de Discord indicado o se coordina por WhatsApp si lo prefieres.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-step-card">
                    <span class="knd-step-number">3</span>
                    <h5>Se ejecuta el Death Roll</h5>
                    <p class="text-white">Se hacen las tiradas según las reglas explicadas y se define el resultado.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-step-card">
                    <span class="knd-step-number">4</span>
                    <h5>Recibes tu loot</h5>
                    <p class="text-white">Te entregamos tu recompensa digital según el resultado: desde contenido estándar hasta loot épico.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: Tipos de recompensas -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    <i class="fas fa-treasure-chest me-2"></i> Recompensas posibles
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    No es un "todo o nada". Siempre recibes algo, pero los resultados altos desbloquean 
                    <strong>recompensas más raras y personalizadas</strong>.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="knd-loot-card knd-loot-common">
                    <h5>Loot estándar</h5>
                    <ul class="mb-0 text-white">
                        <li>Wallpapers digitales</li>
                        <li>Avatares base</li>
                        <li>Descuentos menores</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="knd-loot-card knd-loot-rare">
                    <h5>Loot raro</h5>
                    <ul class="mb-0 text-white">
                        <li>Avatares personalizados</li>
                        <li>Icon packs edición KND</li>
                        <li>Descuentos relevantes para servicios</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="knd-loot-card knd-loot-legendary">
                    <h5>Loot épico</h5>
                    <ul class="mb-0 text-white">
                        <li>Diseños a medida (avatar + wallpaper)</li>
                        <li>Créditos extra para futuros pedidos</li>
                        <li>Slots prioritarios para soporte/asesoría</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: Disclaimer y reglas -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="knd-disclaimer-card">
                    <h3 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i> Reglas y Disclaimer</h3>
                    <ul class="text-white">
                        <li>Death Roll es un <strong>minijuego recreativo</strong> asociado a un servicio digital (Crate).</li>
                        <li>No es un casino online ni un sistema de apuestas con dinero dentro del sitio.</li>
                        <li>El valor económico de las recompensas puede variar según el resultado de la ronda.</li>
                        <li>Las reglas detalladas pueden actualizarse y se comunicarán en el canal oficial de Discord.</li>
                        <li>Al participar, aceptas los <a href="/terms.php" class="text-primary text-decoration-none">Términos y Condiciones</a> de KND Store.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA final -->
<section class="py-5 bg-dark-epic">
    <div class="container text-center">
        <h2 class="section-title mb-3">¿Listo para lanzar el dado?</h2>
        <p class="text-white mb-4" style="font-size: 1.1rem;">
            Empieza comprando tu <strong>Death Roll Crate</strong> o únete al servidor para ver cómo se juega en vivo.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="/producto.php?slug=death-roll-crate" class="btn btn-primary btn-lg">
                <i class="fas fa-dice me-2"></i> Comprar Death Roll Crate
            </a>
            <button class="btn btn-outline-neon btn-lg" onclick="copyDiscordServer()">
                <i class="fab fa-discord me-2"></i> Unirme al servidor
            </button>
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

