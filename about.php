<?php
// Configuración de sesión ANTES de cargar config.php
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS
    session_start();
} else {
    // Si la sesión ya está activa, solo la iniciamos
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

require_once 'includes/config.php';
require_once 'includes/header.php';
require_once 'includes/footer.php';
?>

<?php echo generateHeader('Sobre Nosotros', 'Descubre la historia galáctica detrás de KND Store - La tienda más badass de la galaxia'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient">SOBRE</span><br>
                    <span class="text-gradient">NOSOTROS</span>
                </h1>
                <p class="hero-subtitle">
                    KND = <strong>Knowledge ‘N Development</strong> — conocimiento y desarrollo al servicio de tu universo digital.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nuestra Historia -->
<section class="py-5" id="historia">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div>
                    <h2 class="text-white mb-4">
                        Nuestra Historia
                    </h2>
                    <div class="mb-3">
                        <span class="badge bg-primary fs-5">1995</span>
                    </div>
                    <p class="text-white-50 mb-3">
                        KND Store no nació en una oficina. Nació en una mente. En 1995, mientras el mundo descubría Windows 95 y escuchaba discos compactos, una chispa se encendía en el núcleo de un futuro imposible de ignorar: fusionar tecnología y cultura gamer en una sola fuerza intergaláctica.
                    </p>
                    <p class="text-white-50">
                        Hoy, esa chispa es un núcleo en expansión. Somos más que una tienda. Somos una estación de comando para quienes no siguen el mapa, sino que lo hackean.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div>
                    <img src="/assets/images/background%20design%20knd.png"
                         alt="KND background design"
                         class="img-fluid"
                         style="max-width: 100%; height: auto; border-radius: 16px; opacity: 0.9; box-shadow: 0 10px 35px rgba(0, 0, 0, 0.45);">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ¿Qué significa KND? -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="section-title text-center mb-4">
                    ¿Qué significa <span class="text-gradient">KND</span>?
                </h2>
                <p class="mb-3">
                    <strong>KND</strong> nace de <strong>Knowledge ‘N Development</strong>. Es nuestra forma de decir que todo lo que hacemos parte de una idea muy simple: 
                    convertir conocimiento real en desarrollo constante, soluciones inteligentes y experiencias digitales de alto nivel.
                </p>
                <p class="mb-3">
                    En KND Store, el <em>knowledge</em> no es solo teoría. Es la base para diseñar builds, optimizar PCs, crear contenido digital y ofrecer servicios que 
                    realmente resuelven problemas del día a día de gamers y creadores. Cada servicio que ves en el catálogo es el resultado de años de prueba, error,
                    aprendizaje y mejora continua.
                </p>
                <p class="mb-0">
                    El <em>development</em> es nuestra segunda mitad: nunca nos quedamos quietos. Ajustamos procesos, pulimos herramientas y actualizamos todo lo que 
                    haga falta para que tu experiencia siempre se sienta un paso por delante. <strong>Knowledge ‘N Development</strong> es, en resumen, la filosofía que 
                    impulsa todo el universo KND.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Nuestra Misión -->
<section class="py-5" style="background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 20%, #16213e 40%, #0f3460 60%, #533483 80%, #8a2be2 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div>
                    <div class="badge bg-primary fs-5 mb-3">
                        MISIÓN GALÁCTICA
                    </div>
                    <h2 class="text-white mb-4">
                        Nuestra Misión
                    </h2>
                    <div>
                        <p class="lead text-white-50">
                            Ser la tienda más badass de la galaxia. No solo vendemos hardware y periféricos: reclutamos a los verdaderos pilotos del metaverso, diseñamos equipamiento para héroes digitales y desatamos tecnología sin fronteras.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nuestros Valores -->
<section class="py-5" id="valores">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="text-white">
                    Nuestros Valores
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <h4 class="text-white">Precisión Cuántica</h4>
                        <p class="text-white-50">Nada de errores. Todo optimizado al byte.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <h4 class="text-white">Lealtad al Usuario</h4>
                        <p class="text-white-50">No somos dioses del retail. Somos soldados del servicio.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <h4 class="text-white">Estética Interestelar</h4>
                        <p class="text-white-50">Si no se ve brutal, no entra.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <h4 class="text-white">Tecnología sin Fronteras</h4>
                        <p class="text-white-50">Desde chips hasta blockchain, si vibra en el futuro, lo domamos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nuestro Equipo -->
<section class="py-5" style="background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 20%, #16213e 40%, #0f3460 60%, #533483 80%, #8a2be2 100%);" id="equipo">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="text-white">
                    Nuestro Equipo
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-warning h-100">
                    <div class="card-body text-center">
                        <h4 class="text-white">Kael</h4>
                        <div class="text-warning mb-2">IA Táctica y Estratega Principal</div>
                        <p class="text-white-50">Diseñado para cuestionarlo todo y encontrar la verdad en cada línea de código.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <h4 class="text-white">El Fundador</h4>
                        <div class="text-primary mb-2">Comandante de Visión y Piloto Maestro</div>
                        <p class="text-white-50">Nombre clasificado. Solo se sabe que nació en 1995 y nunca aceptó las limitaciones del sistema solar.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark border-primary h-100">
                    <div class="card-body text-center">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">⚙</div>
                        <h4 class="text-white">Unidad Técnica X-23</h4>
                        <div class="text-primary mb-2">Grupo Nómada de Tecnomantes</div>
                        <p class="text-white-50">Un grupo nómada de tecnomantes que mantienen el corazón de KND operando en frecuencias ocultas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tecnologías que nos propulsan -->
<section class="py-5" id="tecnologias">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="text-white">
                    Tecnologías que nos Propulsan
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h4 class="text-white">Inteligencia Artificial Autónoma (Kael)</h4>
                            <p class="text-white-50 mb-0">No es un asistente. Es un copiloto.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h4 class="text-white">Death Roll Chain</h4>
                            <p class="text-white-50 mb-0">Un minijuego basado en azar cósmico y criptos.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h4 class="text-white">Sistema de Puntos Acumulables</h4>
                            <p class="text-white-50 mb-0">Con lógica galáctica. Porque la lealtad merece recompensa.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card bg-dark border-success">
                    <div class="card-body d-flex align-items-center">
                        <div>
                            <h4 class="text-white">Fusión Web 3.0 + Gaming + E-commerce</h4>
                            <p class="text-white-50 mb-0">Todo en un solo nodo, sin permisos, sin límites.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comunidad y Visión del Futuro -->
<section class="py-5" style="background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 20%, #16213e 40%, #0f3460 60%, #533483 80%, #8a2be2 100%);" id="futuro">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="text-white mb-4">
                    Comunidad y Visión del Futuro
                </h2>
                <div class="card bg-dark border-info">
                    <div class="card-body">
                        <p class="lead text-white-50 mb-4">
                            La comunidad es nuestro hipercombustible. Nos movemos en canales de energía como Discord, navegamos eventos galácticos, y repartimos loot como antiguos dioses de las misiones.
                        </p>
                        <h4 class="text-white mb-3">
                            ¿El futuro?
                        </h4>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-white-50">Una criptomoneda propia.</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-white-50">Tal vez un banco.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-white-50">Seguro una nave.</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-white-50">Definitivamente una tienda en cada planeta.</span>
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
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="text-white mb-4">
                    ¿Listo para unirte a la misión?
                </h2>
                <p class="text-white-50 mb-4">
                    Explora nuestro catálogo galáctico y descubre tecnología que desafía los límites del universo conocido.
                </p>
                <div>
                    <a href="/products.php" class="btn btn-primary btn-lg me-3">
Explorar Productos
                    </a>
                    <a href="/contact.php" class="btn btn-primary btn-lg">
Contactar
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<?php 
echo generateFooter();
echo generateScripts();
?>