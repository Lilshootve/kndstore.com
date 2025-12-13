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
                        <i class="fas fa-dice me-2"></i> Ver Crates Disponibles
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
                    Elige tu nivel de riesgo y recompensa. Cada crate garantiza un tier mínimo, 
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
                        <div class="crate-price">$2.00</div>
                    </div>
                    <div class="crate-tier">
                        <span class="tier-badge tier-common">Tier Garantizado: Common</span>
                    </div>
                    <div class="crate-upgrade">
                        <i class="fas fa-arrow-up me-2"></i>
                        <strong>Posible upgrade:</strong> Rare (30% chance)
                    </div>
                    <div class="crate-rewards">
                        <h6 class="mb-2">Ejemplos de recompensas:</h6>
                        <ul class="mb-0 text-white">
                            <li>Wallpapers digitales estándar</li>
                            <li>Avatares base</li>
                            <li>Descuentos menores (5-10%)</li>
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
                        <div class="crate-price">$5.00</div>
                    </div>
                    <div class="crate-tier">
                        <span class="tier-badge tier-rare">Tier Garantizado: Rare</span>
                    </div>
                    <div class="crate-upgrade">
                        <i class="fas fa-arrow-up me-2"></i>
                        <strong>Posible upgrade:</strong> Epic (25% chance)
                    </div>
                    <div class="crate-rewards">
                        <h6 class="mb-2">Ejemplos de recompensas:</h6>
                        <ul class="mb-0 text-white">
                            <li>Avatares personalizados</li>
                            <li>Icon packs edición KND</li>
                            <li>Descuentos relevantes (15-20%)</li>
                            <li>Claves de juegos menores</li>
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
                        <div class="crate-price">$10.00</div>
                    </div>
                    <div class="crate-tier">
                        <span class="tier-badge tier-epic">Tier Garantizado: Epic</span>
                    </div>
                    <div class="crate-upgrade">
                        <i class="fas fa-arrow-up me-2"></i>
                        <strong>Posible upgrade:</strong> Legendary (20% chance)
                    </div>
                    <div class="crate-rewards">
                        <h6 class="mb-2">Ejemplos de recompensas:</h6>
                        <ul class="mb-0 text-white">
                            <li>Diseños a medida (avatar + wallpaper)</li>
                            <li>Créditos extra para futuros pedidos</li>
                            <li>Slots prioritarios para soporte</li>
                            <li>Claves de juegos premium</li>
                        </ul>
                    </div>
                    <a href="/producto.php?slug=death-roll-crate" class="btn btn-primary btn-sm w-100 mt-3">
                        <i class="fas fa-shopping-cart me-2"></i> Comprar Titan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: Tipos de recompensas -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2 class="section-title mb-3">
                    <i class="fas fa-treasure-chest me-2"></i> Recompensas por Rareza
                </h2>
                <p class="text-white mb-3" style="font-size: 1.1rem;">
                    No es un "todo o nada". Siempre recibes algo, pero los resultados altos desbloquean 
                    <strong>recompensas más raras y personalizadas</strong>.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="knd-loot-card knd-loot-common">
                    <h5>Common</h5>
                    <ul class="mb-0 text-white">
                        <li>Wallpapers digitales</li>
                        <li>Avatares base</li>
                        <li>Descuentos menores</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-loot-card knd-loot-rare">
                    <h5>Rare</h5>
                    <ul class="mb-0 text-white">
                        <li>Avatares personalizados</li>
                        <li>Icon packs edición KND</li>
                        <li>Descuentos relevantes</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-loot-card knd-loot-epic">
                    <h5>Epic</h5>
                    <ul class="mb-0 text-white">
                        <li>Diseños a medida</li>
                        <li>Créditos extra</li>
                        <li>Slots prioritarios</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="knd-loot-card knd-loot-legendary">
                    <h5>Legendary</h5>
                    <ul class="mb-0 text-white">
                        <li>Pack completo personalizado</li>
                        <li>Créditos premium</li>
                        <li>Acceso VIP a servicios</li>
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
                        <li><strong>Horario activo (Lun-Vie 9:00-21:00):</strong> Entrega en 5-30 minutos después de confirmar el resultado del Death Roll.</li>
                        <li><strong>Horario extendido (Sáb-Dom 10:00-20:00):</strong> Entrega en 1-2 horas.</li>
                        <li><strong>Fuera de horario:</strong> Entrega máxima de 24 horas desde la confirmación del resultado.</li>
                        <li><strong>Casos especiales:</strong> Si el loot requiere diseño personalizado, el tiempo puede extenderse hasta 48 horas (se notifica previamente).</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="knd-operational-card">
                    <h5><i class="fas fa-check-circle me-2"></i> Confirmación y Capturas</h5>
                    <ul class="text-white mb-0">
                        <li><strong>Confirmación requerida:</strong> Debes confirmar la recepción del loot digital para cerrar el proceso.</li>
                        <li><strong>Capturas de pantalla:</strong> Se solicita captura del resultado del Death Roll y de la entrega del loot para registro y transparencia.</li>
                        <li><strong>Disputas:</strong> Si hay algún problema con la entrega, contacta dentro de las primeras 48 horas con las capturas correspondientes.</li>
                        <li><strong>Historial:</strong> Todas las rondas quedan documentadas en el canal de Discord para consulta posterior.</li>
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
    box-shadow: 0 8px 20px rgba(200, 200, 200, 0.2);
    transform: translateY(-5px);
}

.knd-crate-pro {
    border: 1px solid rgba(0, 191, 255, 0.6);
}

.knd-crate-pro::before {
    color: var(--knd-neon-blue);
}

.knd-crate-pro:hover {
    border-color: var(--knd-neon-blue);
    box-shadow: 0 8px 20px rgba(0, 191, 255, 0.3);
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
    box-shadow: 0 8px 20px rgba(138, 43, 226, 0.4);
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
}

.tier-common {
    background: rgba(200, 200, 200, 0.2);
    color: rgba(200, 200, 200, 0.9);
    border: 1px solid rgba(200, 200, 200, 0.5);
}

.tier-rare {
    background: rgba(0, 191, 255, 0.2);
    color: var(--knd-neon-blue);
    border: 1px solid rgba(0, 191, 255, 0.5);
}

.tier-epic {
    background: rgba(138, 43, 226, 0.2);
    color: var(--knd-electric-purple);
    border: 1px solid rgba(138, 43, 226, 0.5);
}

.tier-legendary {
    background: rgba(255, 215, 0, 0.2);
    color: #ffd700;
    border: 1px solid rgba(255, 215, 0, 0.5);
}

.crate-upgrade {
    background: rgba(0, 191, 255, 0.1);
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
}

.crate-rewards ul li::before {
    content: '▶';
    position: absolute;
    left: 0;
    color: var(--knd-neon-blue);
    font-size: 0.8rem;
}

/* Loot Epic (nuevo) */
.knd-loot-epic {
    border: 1px solid rgba(138, 43, 226, 0.6);
}

.knd-loot-epic::before {
    color: var(--knd-electric-purple);
}

.knd-loot-epic:hover {
    border-color: var(--knd-electric-purple);
    box-shadow: 0 8px 20px rgba(138, 43, 226, 0.4);
    transform: translateY(-5px);
}

/* Operational Cards */
.knd-operational-card {
    background: rgba(26, 26, 46, 0.9);
    border: 1px solid rgba(0, 191, 255, 0.3);
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

/* Responsive para crates */
@media (max-width: 768px) {
    .knd-crate-card {
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
