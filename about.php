<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
?>

<?php echo generateHeader('Sobre Nosotros', 'Descubre la historia galáctica detrás de KND Store - Knowledge \'N Development'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Wrapper para aplicar fondo a toda la página -->
<div class="about-page">

<!-- Hero Section -->
<section class="hero-section about-hero-bg">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient">SOBRE</span><br>
                    <span class="text-gradient">NOSOTROS</span>
                </h1>
                <p class="hero-subtitle">
                    Knowledge 'N Development — conocimiento y desarrollo al servicio de tu universo digital.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nuestra Historia y Significado de KND (Unificadas) -->
<section class="py-5 bg-dark-epic" id="historia">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="section-title mb-4">
                    <i class="fas fa-rocket me-2"></i> Nuestra Historia
                </h2>
                <div class="mb-3">
                    <span class="badge bg-primary fs-5">1995</span>
                </div>
                <p class="text-white mb-3">
                    KND Store no nació en una oficina. Nació en una mente. En 1995, mientras el mundo descubría Windows 95 y escuchaba discos compactos, una chispa se encendía en el núcleo de un futuro imposible de ignorar: fusionar tecnología y cultura gamer en una sola fuerza intergaláctica.
                </p>
                <p class="text-white mb-0">
                    Hoy, esa chispa es un núcleo en expansión. Somos más que una tienda. Somos una estación de comando para quienes no siguen el mapa, sino que lo hackean.
                </p>
            </div>
            <div class="col-lg-6">
                <h2 class="section-title mb-4">
                    ¿Qué significa <span class="text-gradient">KND</span>?
                </h2>
                <p class="text-white mb-3">
                    <strong>KND</strong> nace de <strong>Knowledge 'N Development</strong>. Es nuestra forma de decir que todo lo que hacemos parte de una idea muy simple: 
                    convertir conocimiento real en desarrollo constante, soluciones inteligentes y experiencias digitales de alto nivel.
                </p>
                <p class="text-white mb-3">
                    En KND Store, el <em>knowledge</em> no es solo teoría. Es la base para diseñar builds, optimizar PCs, crear contenido digital y ofrecer servicios que 
                    realmente resuelven problemas del día a día de gamers y creadores. Cada servicio que ves en el catálogo es el resultado de años de prueba, error,
                    aprendizaje y mejora continua.
                </p>
                <p class="text-white mb-0">
                    El <em>development</em> es nuestra segunda mitad: nunca nos quedamos quietos. Ajustamos procesos, pulimos herramientas y actualizamos todo lo que 
                    haga falta para que tu experiencia siempre se sienta un paso por delante. <strong>Knowledge 'N Development</strong> es, en resumen, la filosofía que 
                    impulsa todo el universo KND.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nuestra Misión -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="badge bg-primary fs-5 mb-3">
                    MISIÓN GALÁCTICA
                </div>
                <h2 class="section-title mb-4">
                    Nuestra Misión
                </h2>
                <p class="text-white lead">
                    Ser la tienda más badass de la galaxia. No solo vendemos hardware y periféricos: reclutamos a los verdaderos pilotos del metaverso, diseñamos equipamiento para héroes digitales y desatamos tecnología sin fronteras.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nuestros Valores -->
<section class="py-5 bg-dark-epic" id="valores">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    Nuestros Valores
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h4 class="text-white">Precisión Cuántica</h4>
                        <p class="text-white">Nada de errores. Todo optimizado al byte.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="text-white">Lealtad al Usuario</h4>
                        <p class="text-white">No somos dioses del retail. Somos soldados del servicio.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h4 class="text-white">Estética Interestelar</h4>
                        <p class="text-white">Si no se ve brutal, no entra.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4 class="text-white">Tecnología sin Fronteras</h4>
                        <p class="text-white">Desde chips hasta blockchain, si vibra en el futuro, lo domamos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nuestro Equipo -->
<section class="py-5" id="equipo">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    Nuestro Equipo
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-warning h-100">
                    <div class="card-body text-center">
                        <div class="team-icon mb-3">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h4 class="text-white">Kael</h4>
                        <div class="text-warning mb-2">IA Táctica y Estratega Principal</div>
                        <p class="text-white">Diseñado para cuestionarlo todo y encontrar la verdad en cada línea de código.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="team-icon mb-3">
                            <i class="fas fa-user-astronaut"></i>
                        </div>
                        <h4 class="text-white">El Fundador</h4>
                        <div class="text-primary mb-2">Comandante de Visión y Piloto Maestro</div>
                        <p class="text-white">Nombre clasificado. Solo se sabe que nació en 1995 y nunca aceptó las limitaciones del sistema solar.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div class="team-icon mb-3">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h4 class="text-white">Unidad Técnica X-23</h4>
                        <div class="text-primary mb-2">Grupo Nómada de Tecnomantes</div>
                        <p class="text-white">Un grupo nómada de tecnomantes que mantienen el corazón de KND operando en frecuencias ocultas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tecnologías que nos propulsan -->
<section class="py-5 bg-dark-epic" id="tecnologias">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">
                    Tecnologías que nos Propulsan
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="tech-icon me-3">
                            <i class="fas fa-brain"></i>
                        </div>
                        <div>
                            <h4 class="text-white mb-1">Inteligencia Artificial Autónoma (Kael)</h4>
                            <p class="text-white mb-0">No es un asistente. Es un copiloto.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="tech-icon me-3">
                            <i class="fas fa-dice"></i>
                        </div>
                        <div>
                            <h4 class="text-white mb-1">Death Roll Chain</h4>
                            <p class="text-white mb-0">Un minijuego basado en riesgo controlado y recompensas digitales.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="tech-icon me-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <h4 class="text-white mb-1">Sistema de Puntos Acumulables</h4>
                            <p class="text-white mb-0">Con lógica galáctica. Porque la lealtad merece recompensa.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="tech-icon me-3">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div>
                            <h4 class="text-white mb-1">Fusión Web 3.0 + Gaming + E-commerce</h4>
                            <p class="text-white mb-0">Todo en un solo nodo, sin permisos, sin límites.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comunidad y Visión del Futuro -->
<section class="py-5" id="futuro">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-4">
                    Comunidad y Visión del Futuro
                </h2>
                <div class="card bg-dark border-info">
                    <div class="card-body">
                        <p class="lead text-white mb-4">
                            La comunidad es nuestro hipercombustible. Nos movemos en canales de energía como Discord, navegamos eventos galácticos, y repartimos loot como antiguos dioses de las misiones.
                        </p>
                        <h4 class="text-white mb-3">
                            ¿El futuro?
                        </h4>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white">Una criptomoneda propia.</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white">Tal vez un banco.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white">Seguro una nave.</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    <span class="text-white">Definitivamente una tienda en cada planeta.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="section-title mb-4">
                    ¿Listo para unirte a la misión?
                </h2>
                <p class="text-white mb-4">
                    Explora nuestro catálogo galáctico y descubre tecnología que desafía los límites del universo conocido.
                </p>
                <div>
                    <a href="/products.php" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-rocket me-2"></i> Explorar Productos
                    </a>
                    <a href="/contact.php" class="btn btn-outline-neon btn-lg">
                        <i class="fas fa-envelope me-2"></i> Contactar
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

</div> <!-- Cierre de .about-page -->

<style>
/* Estilos adicionales para About Page */

.team-icon {
    font-size: 3rem;
    color: var(--knd-neon-blue);
    margin-bottom: 1rem;
}

.team-icon i {
    transition: all 0.3s ease;
}

.card:hover .team-icon i {
    transform: scale(1.1);
    color: var(--knd-electric-purple);
}

.tech-icon {
    font-size: 2.5rem;
    color: var(--knd-neon-blue);
    min-width: 60px;
    text-align: center;
}

.tech-icon i {
    transition: all 0.3s ease;
}

.card:hover .tech-icon i {
    transform: scale(1.1);
    color: var(--knd-electric-purple);
}

.feature-icon {
    font-size: 2.5rem;
    color: var(--knd-neon-blue);
    margin-bottom: 1rem;
}

.feature-icon i {
    transition: all 0.3s ease;
}

.card:hover .feature-icon i {
    transform: scale(1.1);
    color: var(--knd-electric-purple);
}

/* Asegurar legibilidad en secciones con fondo */
.about-page section {
    position: relative;
    z-index: 1;
}

.about-page .section-title {
    color: var(--knd-white);
}

@media (max-width: 768px) {
    .team-icon {
        font-size: 2.5rem;
    }
    
    .tech-icon {
        font-size: 2rem;
        min-width: 50px;
    }
}
</style>

<?php 
echo generateFooter();
echo generateScripts();
?>
