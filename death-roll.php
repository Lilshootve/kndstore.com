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
                    <span class="hero-subtitle-mini">Riesgo controlado, suerte y loot digital</span>
                </h1>
                <p class="hero-subtitle">
                    Un minijuego galáctico donde la suerte y el riesgo controlado determinan tu 
                    <strong>loot digital: claves, avatares, wallpapers</strong> y más.  
                    Todo manejado por <strong>KND (Knowledge 'N Development)</strong>, con reglas claras y diversión controlada.
                </p>
                <div class="mt-4 d-flex flex-wrap gap-3">
                    <a href="#crates" class="btn btn-primary btn-lg">
                        <i class="fas fa-dice me-2"></i> Ver Crates
                    </a>
                    <a href="#rules" class="btn btn-outline-neon btn-lg">
                        <i class="fas fa-scroll me-2"></i> Reglas
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
                        Servicio digital con experiencia de minijuego; no es apuestas dentro del sitio.  
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
                    Simple, directo y con sabor a minijuego galáctico, controlado por 
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
                    <p class="text-white">Te entregamos tu recompensa digital según el resultado: desde Common hasta Legendary.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: Crates & Tiers -->
<section id="crates" class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    <i class="fas fa-box me-2"></i> Crates & Tiers
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    Elige tu nivel de experiencia. Cada crate garantiza un tier mínimo, 
                    pero puedes <strong>upgradear</strong> si la suerte está de tu lado.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <!-- Starter Crate -->
            <div class="col-md-4">
                <div class="knd-crate-card knd-crate-starter">
                    <div class="crate-header">
                        <h4><i class="fas fa-box-open me-2"></i> Starter Crate</h4>
                        <div class="crate-price">$2.50</div>
                    </div>
                    <div class="crate-tier">
                        <span class="tier-badge tier-common">Tier Garantizado: Common</span>
                    </div>
                    <div class="crate-upgrade">
                        <i class="fas fa-arrow-up me-2"></i>
                        <strong>Si ganas:</strong> Upgrade a Rare
                    </div>
                    <div class="crate-rewards">
                        <h6 class="mb-2">Ejemplos de recompensas:</h6>
                        <ul class="mb-0 text-white">
                            <li>Wallpaper Pack exclusivo Death Roll</li>
                            <li>Avatar + Banner Death Roll (edición sesión)</li>
                            <li>Cupón $2 para próxima compra (expira 7 días)</li>
                        </ul>
                    </div>
                    <a href="/producto.php?slug=death-roll-crate" class="btn btn-outline-light btn-sm w-100 mt-3">
                        <i class="fas fa-shopping-cart me-2"></i> Comprar Starter
                    </a>
                </div>
            </div>
            <!-- Pro Crate -->
            <div class="col-md-4">
                <div class="knd-crate-card knd-crate-pro">
                    <div class="crate-header">
                        <h4><i class="fas fa-box me-2"></i> Pro Crate</h4>
                        <div class="crate-price">$7.50</div>
                    </div>
                    <div class="crate-tier">
                        <span class="tier-badge tier-rare">Tier Garantizado: Rare</span>
                    </div>
                    <div class="crate-upgrade">
                        <i class="fas fa-arrow-up me-2"></i>
                        <strong>Si ganas:</strong> Upgrade a Epic
                    </div>
                    <div class="crate-rewards">
                        <h6 class="mb-2">Ejemplos de recompensas:</h6>
                        <ul class="mb-0 text-white">
                            <li>Avatar personalizado (elige estilo: neon / anime / sci-fi / minimal)</li>
                        </ul>
                    </div>
                    <a href="/producto.php?slug=death-roll-crate" class="btn btn-primary btn-sm w-100 mt-3">
                        <i class="fas fa-shopping-cart me-2"></i> Comprar Pro
                    </a>
                </div>
            </div>
            <!-- Titan Crate -->
            <div class="col-md-4">
                <div class="knd-crate-card knd-crate-titan">
                    <div class="crate-header">
                        <h4><i class="fas fa-gem me-2"></i> Titan Crate</h4>
                        <div class="crate-price">$15.00</div>
                    </div>
                    <div class="crate-tier">
                        <span class="tier-badge tier-epic">Tier Garantizado: Epic</span>
                    </div>
                    <div class="crate-upgrade">
                        <i class="fas fa-arrow-up me-2"></i>
                        <strong>Si ganas:</strong> Epic + bonus digital
                    </div>
                    <div class="crate-rewards">
                        <h6 class="mb-2">Ejemplos de recompensas:</h6>
                        <ul class="mb-0 text-white">
                            <li>Epic Bundle: Avatar + Wallpaper personalizado (matching)</li>
                            <li>Créditos KND $5</li>
                            <li>Prioridad de entrega</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning bg-dark border-warning p-2 mt-2 mb-0" style="font-size: 0.85rem;">
                        <i class="fas fa-star me-1"></i>
                        <strong>Legendary:</strong> Solo en eventos/promociones limitadas
                    </div>
                    <a href="/producto.php?slug=death-roll-crate" class="btn btn-primary btn-sm w-100 mt-3">
                        <i class="fas fa-shopping-cart me-2"></i> Comprar Titan
                    </a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-8 mx-auto">
                <div class="knd-upgrade-info">
                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i> Cómo se determina el upgrade</h5>
                    <ul class="text-white mb-0">
                        <li>Se ejecuta el Death Roll visible en Discord</li>
                        <li>Resultado documentado (logs/capturas)</li>
                        <li>Upgrade según reglas del canal oficial</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: Recompensas por Rareza -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    <i class="fas fa-treasure-chest me-2"></i> Recompensas por Rareza
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    Siempre recibes una recompensa digital; lo que cambia es la rareza/valor percibido.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="knd-loot-card knd-loot-common">
                    <h5>Common</h5>
                    <p class="small text-white mb-2" style="opacity: 0.8;">Siempre ganas algo (costo ~$0)</p>
                    <p class="small text-white mb-2"><strong>Incluye 1 (rotativo):</strong></p>
                    <ul class="mb-0 text-white">
                        <li>Wallpaper Pack exclusivo Death Roll (no vendible aparte)</li>
                        <li>Avatar + Banner Death Roll (edición sesión)</li>
                        <li>Cupón $2 para próxima compra (expira 7 días)</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-loot-card knd-loot-rare">
                    <h5>Rare</h5>
                    <p class="small text-white mb-2" style="opacity: 0.8;">Vale la pena (costo bajo)</p>
                    <ul class="mb-0 text-white">
                        <li>Avatar personalizado (elige estilo: neon / anime / sci-fi / minimal)</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-loot-card knd-loot-epic">
                    <h5>Epic</h5>
                    <p class="small text-white mb-2" style="opacity: 0.8;">Premium (costo medido)</p>
                    <p class="small text-white mb-2"><strong>Epic Bundle fijo:</strong></p>
                    <ul class="mb-0 text-white">
                        <li>Avatar personalizado</li>
                        <li>Wallpaper personalizado (matching)</li>
                        <li>Créditos KND $5</li>
                        <li>Prioridad de entrega</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-loot-card knd-loot-legendary">
                    <h5>Legendary</h5>
                    <p class="small text-warning mb-2"><strong>Solo en eventos / promociones limitadas</strong></p>
                    <p class="small text-white mb-2">Cap recomendado: 2-3 al mes</p>
                    <p class="small text-white mb-2">Si se alcanza cap:</p>
                    <ul class="mb-0 text-white">
                        <li>Legendary = Epic Bundle + bonus digital</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: Entrega & Reglas Operativas -->
<section id="rules" class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    <i class="fas fa-clock me-2"></i> Entrega & Reglas Operativas
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    Tiempos de entrega, confirmaciones y procedimientos operativos para garantizar 
                    una experiencia fluida y transparente.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="knd-operational-card">
                    <h5><i class="fas fa-shipping-fast me-2"></i> Tiempos de Entrega (SLA)</h5>
                    <ul class="text-white mb-0">
                        <li><strong>Entrega típica:</strong> 5-30 min en horario activo</li>
                        <li><strong>Máximo:</strong> 24 horas desde la confirmación del resultado</li>
                        <li><strong>Canales:</strong> Discord / WhatsApp</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="knd-operational-card">
                    <h5><i class="fas fa-check-circle me-2"></i> Confirmación y Transparencia</h5>
                    <ul class="text-white mb-0">
                        <li><strong>Confirmación:</strong> Puede requerir confirmación/captura si aplica (ej. pagos externos)</li>
                        <li><strong>Transparencia:</strong> Historial y registro por sesión</li>
                        <li><strong>Disputas:</strong> Contacta dentro de 48 horas con capturas</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-8 mx-auto">
                <div class="knd-disclaimer-card">
                    <p class="text-white mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Disclaimer legal:</strong> Servicio digital con experiencia de minijuego; no es apuestas dentro del sitio.
                    </p>
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
                        <li>No es un sistema de apuestas con dinero dentro del sitio.</li>
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
            <a href="#crates" class="btn btn-primary btn-lg">
                <i class="fas fa-dice me-2"></i> Ver Crates Disponibles
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

<style>
/* Estilos adicionales para Death Roll Page */

/* Upgrade Info Card */
.knd-upgrade-info {
    background: rgba(26, 26, 46, 0.7);
    border: 1px solid rgba(37, 156, 174, 0.3);
    border-radius: 15px;
    padding: 1.5rem;
    backdrop-filter: blur(5px);
}

.knd-upgrade-info h5 {
    color: var(--knd-white);
    font-family: 'Orbitron', sans-serif;
    font-size: 1.1rem;
}

.knd-upgrade-info h5 i {
    color: var(--knd-neon-blue);
}

.knd-upgrade-info ul {
    list-style: none;
    padding-left: 0;
}

.knd-upgrade-info ul li {
    padding: 0.5rem 0;
    padding-left: 1.5rem;
    position: relative;
    color: rgba(255, 255, 255, 0.9);
}

.knd-upgrade-info ul li::before {
    content: '→';
    position: absolute;
    left: 0;
    color: var(--knd-neon-blue);
    font-weight: 700;
}

/* Crates Cards */
.knd-crate-card {
    background: rgba(26, 26, 46, 0.9);
    border-radius: 15px;
    padding: 2rem;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}

.knd-crate-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, currentColor, transparent);
}

.knd-crate-starter {
    border: 1px solid rgba(200, 200, 200, 0.5);
}

.knd-crate-starter::before {
    color: rgba(200, 200, 200, 0.8);
}

.knd-crate-starter:hover {
    border-color: rgba(200, 200, 200, 0.8);
    box-shadow: 0 8px 20px rgba(200, 200, 200, 0.2), 0 0 15px rgba(200, 200, 200, 0.1);
    transform: translateY(-5px);
}

.knd-crate-pro {
    border: 1px solid rgba(37, 156, 174, 0.6);
}

.knd-crate-pro::before {
    color: var(--knd-neon-blue);
}

.knd-crate-pro:hover {
    border-color: var(--knd-neon-blue);
    box-shadow: 0 8px 20px rgba(37, 156, 174, 0.3), 0 0 20px rgba(37, 156, 174, 0.2);
    transform: translateY(-5px);
}

.knd-crate-titan {
    border: 1px solid rgba(138, 43, 226, 0.6);
}

.knd-crate-titan::before {
    color: var(--knd-electric-purple);
}

.knd-crate-titan:hover {
    border-color: var(--knd-electric-purple);
    box-shadow: 0 8px 20px rgba(138, 43, 226, 0.4), 0 0 25px rgba(138, 43, 226, 0.3);
    transform: translateY(-5px);
}

.crate-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.crate-header h4 {
    color: var(--knd-white);
    font-family: 'Orbitron', sans-serif;
    font-size: 1.3rem;
    margin: 0;
}

.crate-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--knd-neon-blue);
    font-family: 'Orbitron', sans-serif;
}

.crate-tier {
    margin-bottom: 1rem;
}

.tier-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    font-family: 'Orbitron', sans-serif;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.tier-common {
    background: rgba(200, 200, 200, 0.2);
    color: rgba(200, 200, 200, 0.9);
    border: 1px solid rgba(200, 200, 200, 0.5);
}

.tier-rare {
    background: rgba(37, 156, 174, 0.2);
    color: var(--knd-neon-blue);
    border: 1px solid rgba(37, 156, 174, 0.5);
    box-shadow: 0 0 10px rgba(37, 156, 174, 0.3);
}

.tier-epic {
    background: rgba(138, 43, 226, 0.2);
    color: var(--knd-electric-purple);
    border: 1px solid rgba(138, 43, 226, 0.5);
    box-shadow: 0 0 10px rgba(138, 43, 226, 0.3);
}

.tier-legendary {
    background: rgba(255, 215, 0, 0.2);
    color: #ffd700;
    border: 1px solid rgba(255, 215, 0, 0.5);
    box-shadow: 0 0 15px rgba(255, 215, 0, 0.4);
}

.crate-upgrade {
    background: rgba(37, 156, 174, 0.1);
    border-left: 3px solid var(--knd-neon-blue);
    padding: 0.75rem;
    margin-bottom: 1rem;
    border-radius: 5px;
    color: var(--knd-white);
    font-size: 0.9rem;
}

.crate-rewards h6 {
    color: var(--knd-white);
    font-family: 'Orbitron', sans-serif;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.crate-rewards ul {
    list-style: none;
    padding-left: 0;
}

.crate-rewards ul li {
    padding: 0.4rem 0;
    padding-left: 1.5rem;
    position: relative;
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
}

.crate-rewards ul li::before {
    content: '▶';
    position: absolute;
    left: 0;
    color: var(--knd-neon-blue);
    font-size: 0.8rem;
}

/* Loot Cards con glow mejorado */
.knd-loot-card {
    background: rgba(26, 26, 46, 0.9);
    border-radius: 15px;
    padding: 2rem;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}

.knd-loot-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, currentColor, transparent);
}

.knd-loot-common {
    border: 1px solid rgba(200, 200, 200, 0.5);
}

.knd-loot-common::before {
    color: rgba(200, 200, 200, 0.8);
}

.knd-loot-common:hover {
    border-color: rgba(200, 200, 200, 0.8);
    box-shadow: 0 8px 20px rgba(200, 200, 200, 0.2);
    transform: translateY(-5px);
}

.knd-loot-rare {
    border: 1px solid rgba(37, 156, 174, 0.6);
}

.knd-loot-rare::before {
    color: var(--knd-neon-blue);
}

.knd-loot-rare:hover {
    border-color: var(--knd-neon-blue);
    box-shadow: 0 8px 20px rgba(37, 156, 174, 0.3), 0 0 15px rgba(37, 156, 174, 0.2);
    transform: translateY(-5px);
}

.knd-loot-epic {
    border: 1px solid rgba(138, 43, 226, 0.6);
}

.knd-loot-epic::before {
    color: var(--knd-electric-purple);
}

.knd-loot-epic:hover {
    border-color: var(--knd-electric-purple);
    box-shadow: 0 8px 20px rgba(138, 43, 226, 0.4), 0 0 20px rgba(138, 43, 226, 0.3);
    transform: translateY(-5px);
}

.knd-loot-legendary {
    border: 1px solid rgba(255, 215, 0, 0.6);
}

.knd-loot-legendary::before {
    color: #ffd700;
}

.knd-loot-legendary:hover {
    border-color: #ffd700;
    box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4), 0 0 25px rgba(255, 215, 0, 0.3);
    transform: translateY(-5px);
}

.knd-loot-card h5 {
    color: var(--knd-white);
    font-family: 'Orbitron', sans-serif;
    font-size: 1.3rem;
    margin-bottom: 1rem;
}

.knd-loot-card ul {
    list-style: none;
    padding-left: 0;
}

.knd-loot-card ul li {
    padding: 0.4rem 0;
    padding-left: 1.5rem;
    position: relative;
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
}

.knd-loot-card ul li::before {
    content: '▶';
    position: absolute;
    left: 0;
    color: var(--knd-neon-blue);
    font-size: 0.8rem;
}

/* Operational Cards */
.knd-operational-card {
    background: rgba(26, 26, 46, 0.9);
    border: 1px solid rgba(37, 156, 174, 0.3);
    border-radius: 15px;
    padding: 2rem;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.knd-operational-card h5 {
    color: var(--knd-white);
    font-family: 'Orbitron', sans-serif;
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

.knd-operational-card h5 i {
    color: var(--knd-neon-blue);
}

.knd-operational-card ul {
    list-style: none;
    padding-left: 0;
}

.knd-operational-card ul li {
    padding: 0.75rem 0;
    padding-left: 1.5rem;
    position: relative;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
}

.knd-operational-card ul li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--knd-neon-blue);
    font-weight: 700;
}

/* Responsive */
@media (max-width: 768px) {
    .knd-crate-card,
    .knd-loot-card {
        margin-bottom: 1.5rem;
    }
    
    .crate-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .knd-operational-card {
        margin-bottom: 1.5rem;
    }
}
</style>

<?php 
echo generateFooter();
echo generateScripts();
?>
