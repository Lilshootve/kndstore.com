<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
?>

<?php echo generateHeader('Política de Privacidad', 'Política de Privacidad de KND Store - Protección de datos y privacidad'); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient">Política de</span><br>
                    <span class="text-gradient">Privacidad</span>
                </h1>
                <p class="hero-subtitle">
                    Protección de datos y privacidad en KND Store
                </p>
                <div class="mt-3">
                    <span class="badge bg-primary fs-6">
                        <i class="fas fa-shield-alt me-2"></i>
                        Última actualización: <?php echo date('F Y'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Privacy Content -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                
                <!-- Introducción -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Introducción y Datos que Recopilamos
                    </h2>
                    <p class="text-white mb-3">
                        En <strong>KND Store</strong> (<strong>Knowledge 'N Development</strong>), valoramos profundamente tu privacidad y nos comprometemos a proteger la información que compartes con nosotros. Esta política de privacidad explica cómo recopilamos, utilizamos, almacenamos y protegemos tus datos personales cuando utilizas nuestro sitio web <strong>kndstore.com</strong> y nuestros servicios digitales.
                    </p>
                    <p class="text-white mb-3">
                        Al utilizar nuestros servicios, aceptas las prácticas descritas en esta política. Si no estás de acuerdo con alguna parte de esta política, te recomendamos no utilizar nuestra plataforma.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-database me-2"></i>
                        Información que Recopilamos
                    </h3>
                    <p class="text-white mb-3">
                        Recopilamos únicamente la información necesaria para proporcionar y mejorar nuestros servicios:
                    </p>
                    <ul class="text-white mb-3">
                        <li><strong>Datos de identificación:</strong> Nombre completo, alias o nombre de usuario.</li>
                        <li><strong>Información de contacto:</strong> Dirección de correo electrónico, número de teléfono (incluyendo WhatsApp), y nombre de usuario de Discord u otras plataformas de comunicación.</li>
                        <li><strong>Datos de transacción:</strong> Historial de compras, servicios solicitados, métodos de pago utilizados (sin almacenar información financiera sensible).</li>
                        <li><strong>Datos técnicos:</strong> Dirección IP, tipo de navegador, sistema operativo, dispositivo utilizado, y datos de navegación (páginas visitadas, tiempo de permanencia, etc.).</li>
                        <li><strong>Datos de comunicación:</strong> Mensajes, consultas, y cualquier otra información que nos proporciones al contactarnos o solicitar soporte.</li>
                    </ul>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-lock me-2"></i>
                        <strong>Importante:</strong> <strong>KND Store</strong> <strong>NO</strong> almacena información de tarjetas de crédito, números de cuenta bancaria, o datos financieros sensibles. Todos los pagos se procesan a través de plataformas externas seguras.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Cómo Usamos los Datos -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-cogs me-2"></i>
                        Cómo Usamos y Procesamos Datos
                    </h2>
                    <p class="text-white mb-3">
                        Utilizamos la información recopilada para los siguientes propósitos:
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Prestación de Servicios
                    </h3>
                    <ul class="text-white mb-3">
                        <li>Procesar y completar tus pedidos y solicitudes de servicios.</li>
                        <li>Entregar productos digitales (claves, archivos, servicios remotos) a través de los canales acordados.</li>
                        <li><strong>Coordinación de delivery:</strong> Para productos físicos (apparel), utilizamos tus datos de contacto (WhatsApp, email) únicamente para coordinar la entrega, método de pago y dirección de envío.</li>
                        <li><strong>Servicios de diseño personalizado:</strong> Utilizamos la información del brief (estilo, colores, referencias) para crear el diseño solicitado y comunicarnos contigo durante el proceso.</li>
                        <li>Comunicarnos contigo sobre el estado de tus pedidos, actualizaciones de servicios, y soporte técnico.</li>
                        <li>Gestionar tu cuenta y preferencias de usuario (si aplica).</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-chart-line me-2"></i>
                        Mejora de Servicios
                    </h3>
                    <ul class="text-white mb-3">
                        <li>Analizar el uso del sitio web para mejorar la experiencia del usuario.</li>
                        <li>Desarrollar nuevos servicios y funcionalidades.</li>
                        <li>Realizar investigaciones y análisis internos para optimizar nuestros procesos.</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-bullhorn me-2"></i>
                        Comunicación
                    </h3>
                    <ul class="text-white mb-3">
                        <li>Enviar notificaciones importantes sobre cambios en nuestros términos, políticas o servicios.</li>
                        <li>Responder a tus consultas, solicitudes y comentarios.</li>
                        <li>Proporcionar información sobre promociones, ofertas especiales o nuevos servicios (solo con tu consentimiento explícito).</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-shield-alt me-2"></i>
                        Seguridad y Cumplimiento Legal
                    </h3>
                    <ul class="text-white mb-3">
                        <li>Detectar, prevenir y abordar fraudes, abusos y actividades ilegales.</li>
                        <li>Cumplir con obligaciones legales y regulatorias.</li>
                        <li>Proteger los derechos, propiedad y seguridad de <strong>KND Store</strong>, nuestros usuarios y terceros.</li>
                    </ul>
                    <div class="alert alert-success bg-dark border-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Compromiso:</strong> <strong>KND Store</strong> <strong>NUNCA</strong> vende, alquila o comparte tu información personal con terceros para fines comerciales o de marketing sin tu consentimiento explícito.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Pagos y Seguridad -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-credit-card me-2"></i>
                        Pagos y Seguridad Financiera
                    </h2>
                    <p class="text-white mb-3">
                        <strong>Procesamiento de Pagos:</strong> Todos los pagos realizados a través de <strong>kndstore.com</strong> se procesan mediante plataformas externas seguras y certificadas, incluyendo:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Zinli</li>
                        <li>Binance Pay</li>
                        <li>PayPal</li>
                        <li>Proveedores de criptomonedas</li>
                        <li>Procesadores de transferencias bancarias</li>
                        <li>Otros proveedores de servicios de pago autorizados</li>
                    </ul>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> no tiene acceso ni almacena información de tarjetas de crédito, números de cuenta, o datos financieros sensibles. Toda la información de pago es manejada directamente por los proveedores de servicios de pago, quienes están sujetos a estrictos estándares de seguridad y cumplimiento (PCI DSS, etc.).
                    </p>
                    <div class="alert alert-warning bg-dark border-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Recomendación:</strong> Siempre verifica que estás utilizando métodos de pago oficiales y seguros. <strong>KND Store</strong> nunca solicitará información financiera sensible por correo electrónico, WhatsApp o Discord de forma no autorizada.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Cookies -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-cookie-bite me-2"></i>
                        Cookies y Tecnologías Similares
                    </h2>
                    <p class="text-white mb-3">
                        <strong>¿Qué son las cookies?</strong> Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas nuestro sitio web. Nos ayudan a mejorar tu experiencia de navegación y a entender cómo utilizas nuestros servicios.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-list me-2"></i>
                        Tipos de Cookies que Utilizamos
                    </h3>
                    <ul class="text-white mb-3">
                        <li><strong>Cookies esenciales:</strong> Necesarias para el funcionamiento básico del sitio (autenticación, seguridad, preferencias de sesión).</li>
                        <li><strong>Cookies de rendimiento:</strong> Nos ayudan a entender cómo los visitantes interactúan con nuestro sitio (páginas más visitadas, tiempo de permanencia, etc.).</li>
                        <li><strong>Cookies de funcionalidad:</strong> Permiten que el sitio recuerde tus preferencias y personalice tu experiencia.</li>
                        <li><strong>Cookies de análisis:</strong> Utilizadas por herramientas de análisis como Google Analytics para recopilar información agregada y anónima sobre el uso del sitio.</li>
                    </ul>
                    <p class="text-white mb-3">
                        <strong>Gestión de Cookies:</strong> Puedes controlar y gestionar las cookies a través de la configuración de tu navegador. Sin embargo, ten en cuenta que desactivar ciertas cookies puede afectar la funcionalidad del sitio.
                    </p>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Privacidad:</strong> Las cookies que utilizamos no contienen información personal identificable y se utilizan únicamente para mejorar nuestros servicios.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Herramientas Externas -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-plug me-2"></i>
                        Uso de Herramientas Externas
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> utiliza servicios de terceros para mejorar la funcionalidad y el análisis de nuestro sitio web. Estos servicios pueden recopilar información sobre tu uso de nuestro sitio:
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-chart-bar me-2"></i>
                        Google Analytics
                    </h3>
                    <p class="text-white mb-3">
                        Utilizamos Google Analytics para analizar el tráfico del sitio y entender cómo los usuarios interactúan con nuestro contenido. Google Analytics utiliza cookies y puede recopilar información como tu dirección IP, tipo de navegador, y páginas visitadas. Esta información es procesada por Google según su propia política de privacidad.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-server me-2"></i>
                        Servicios de Hosting y CDN
                    </h3>
                    <p class="text-white mb-3">
                        Nuestro sitio web está alojado en servidores de terceros que pueden recopilar información técnica (direcciones IP, logs de acceso) necesaria para el funcionamiento del servicio.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-shield-alt me-2"></i>
                        Servicios de Seguridad
                    </h3>
                    <p class="text-white mb-3">
                        Utilizamos servicios de seguridad y protección contra fraudes para proteger nuestro sitio y nuestros usuarios de actividades maliciosas.
                    </p>
                    <div class="alert alert-warning bg-dark border-warning">
                        <i class="fas fa-external-link-alt me-2"></i>
                        <strong>Enlaces Externos:</strong> Nuestro sitio puede contener enlaces a sitios web de terceros. No somos responsables de las prácticas de privacidad de estos sitios externos. Te recomendamos revisar las políticas de privacidad de cualquier sitio que visites.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Protección de Datos -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-user-shield me-2"></i>
                        Protección de Datos del Usuario
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> implementa medidas técnicas y organizativas apropiadas para proteger tus datos personales contra acceso no autorizado, alteración, divulgación o destrucción. Estas medidas incluyen:
                    </p>
                    <ul class="text-white mb-3">
                        <li><strong>Cifrado:</strong> Utilizamos protocolos de cifrado (HTTPS/SSL) para proteger la transmisión de datos entre tu navegador y nuestros servidores.</li>
                        <li><strong>Control de acceso:</strong> Limitamos el acceso a tus datos personales únicamente a empleados y proveedores de servicios autorizados que necesitan esta información para realizar sus funciones.</li>
                        <li><strong>Seguridad de servidores:</strong> Nuestros servidores están protegidos con firewalls, sistemas de detección de intrusiones y otras medidas de seguridad.</li>
                        <li><strong>Actualizaciones regulares:</strong> Mantenemos nuestros sistemas y software actualizados con los últimos parches de seguridad.</li>
                    </ul>
                    <p class="text-white mb-3">
                        Sin embargo, es importante entender que <strong>ningún método de transmisión por Internet o almacenamiento electrónico es 100% seguro</strong>. Aunque nos esforzamos por proteger tus datos, no podemos garantizar seguridad absoluta.
                    </p>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-key me-2"></i>
                        <strong>Tu Responsabilidad:</strong> Te recomendamos mantener la confidencialidad de cualquier información de acceso que utilices en nuestra plataforma y notificarnos inmediatamente si sospechas de cualquier acceso no autorizado.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Derechos del Usuario -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-user-check me-2"></i>
                        Derechos del Usuario
                    </h2>
                    <p class="text-white mb-3">
                        De acuerdo con las leyes de protección de datos aplicables (incluyendo el GDPR para usuarios de la Unión Europea), tienes los siguientes derechos respecto a tus datos personales:
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-eye me-2"></i>
                        Derecho de Acceso
                    </h3>
                    <p class="text-white mb-3">
                        Tienes derecho a solicitar una copia de los datos personales que tenemos sobre ti, incluyendo información sobre cómo los utilizamos y con quién los compartimos.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-edit me-2"></i>
                        Derecho de Rectificación
                    </h3>
                    <p class="text-white mb-3">
                        Puedes solicitar que corrijamos cualquier información inexacta o incompleta que tengamos sobre ti.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-trash-alt me-2"></i>
                        Derecho de Eliminación
                    </h3>
                    <p class="text-white mb-3">
                        Puedes solicitar que eliminemos tus datos personales, sujeto a ciertas excepciones legales (por ejemplo, cuando necesitemos conservar información para cumplir con obligaciones legales o resolver disputas).
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-ban me-2"></i>
                        Derecho de Oposición
                    </h3>
                    <p class="text-white mb-3">
                        Tienes derecho a oponerte al procesamiento de tus datos personales para ciertos fines, como marketing directo.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-download me-2"></i>
                        Derecho de Portabilidad
                    </h3>
                    <p class="text-white mb-3">
                        En algunos casos, puedes solicitar que transfiramos tus datos personales a otro proveedor de servicios en un formato estructurado y de uso común.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-hand-paper me-2"></i>
                        Derecho de Retirar Consentimiento
                    </h3>
                    <p class="text-white mb-3">
                        Si has dado tu consentimiento para el procesamiento de tus datos, tienes derecho a retirarlo en cualquier momento.
                    </p>
                    <div class="alert alert-success bg-dark border-success">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>Ejercer tus Derechos:</strong> Para ejercer cualquiera de estos derechos, contáctanos en <a href="mailto:support@kndstore.com" class="text-primary text-decoration-none">support@kndstore.com</a>. Responderemos a tu solicitud dentro de un plazo razonable, generalmente dentro de <strong>30 días</strong>.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Retención de Datos -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-archive me-2"></i>
                        Retención de Datos
                    </h2>
                    <p class="text-white mb-3">
                        Conservamos tus datos personales solo durante el tiempo necesario para cumplir con los propósitos para los que fueron recopilados, incluyendo:
                    </p>
                    <ul class="text-white mb-3">
                        <li><strong>Datos de transacción:</strong> Conservamos información de compras y transacciones durante el tiempo necesario para cumplir con obligaciones legales, contables y fiscales (generalmente entre 5 y 7 años, según la legislación aplicable).</li>
                        <li><strong>Datos de contacto:</strong> Mantenemos información de contacto mientras mantengas una relación activa con <strong>KND Store</strong> o hasta que solicites su eliminación.</li>
                        <li><strong>Datos de navegación:</strong> Los datos de análisis y cookies se conservan según los períodos establecidos por las herramientas de análisis utilizadas (generalmente entre 14 y 26 meses).</li>
                    </ul>
                    <p class="text-white mb-3">
                        Una vez que los datos ya no sean necesarios, los eliminaremos de forma segura o los anonimizaremos de manera que no puedan ser asociados contigo.
                    </p>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Menores de Edad -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-child me-2"></i>
                        Menores de Edad
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> no está dirigido a menores de 18 años. No recopilamos intencionalmente información personal de menores de edad sin el consentimiento de sus padres o tutores legales.
                    </p>
                    <p class="text-white mb-3">
                        Si descubrimos que hemos recopilado información personal de un menor sin el consentimiento apropiado, tomaremos medidas para eliminar esa información de nuestros sistemas lo antes posible.
                    </p>
                    <div class="alert alert-warning bg-dark border-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Responsabilidad de los Padres:</strong> Si eres padre o tutor y crees que tu hijo menor de edad nos ha proporcionado información personal, contáctanos inmediatamente para que podamos tomar las medidas apropiadas.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Transferencia Internacional -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-globe me-2"></i>
                        Transferencia Internacional de Datos
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> opera a nivel internacional y puede transferir, almacenar y procesar tus datos personales en servidores ubicados fuera de tu país de residencia. Esto puede incluir transferencias a países que pueden tener leyes de protección de datos diferentes a las de tu jurisdicción.
                    </p>
                    <p class="text-white mb-3">
                        Al utilizar nuestros servicios, consientes la transferencia de tus datos a estos países. Nos comprometemos a implementar medidas apropiadas para proteger tus datos durante estas transferencias, incluyendo:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Utilizar cláusulas contractuales estándar aprobadas por autoridades de protección de datos.</li>
                        <li>Asegurar que nuestros proveedores de servicios cumplan con estándares de protección de datos adecuados.</li>
                        <li>Implementar medidas de seguridad técnicas y organizativas apropiadas.</li>
                    </ul>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Protección:</strong> Independientemente de dónde se procesen tus datos, mantenemos los mismos estándares de protección y seguridad descritos en esta política.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Cambios en la Política -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-sync-alt me-2"></i>
                        Cambios en Esta Política
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> se reserva el derecho de actualizar o modificar esta política de privacidad en cualquier momento para reflejar cambios en nuestras prácticas, servicios, o requisitos legales.
                    </p>
                    <p class="text-white mb-3">
                        Cuando realicemos cambios significativos a esta política, te notificaremos mediante:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Publicación de una notificación destacada en nuestro sitio web.</li>
                        <li>Envío de un correo electrónico a la dirección que tengamos registrada (si aplica).</li>
                        <li>Actualización de la fecha de "Última actualización" en la parte superior de esta página.</li>
                    </ul>
                    <p class="text-white mb-3">
                        Te recomendamos revisar periódicamente esta política para estar informado sobre cómo protegemos tu información. El uso continuado de nuestros servicios después de cualquier modificación constituye tu aceptación de la política actualizada.
                    </p>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-calendar me-2"></i>
                        <strong>Fecha de Vigencia:</strong> Esta política entra en vigor a partir de la fecha de su publicación y permanece en vigor hasta que sea reemplazada por una versión actualizada.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Contacto -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-envelope me-2"></i>
                        Contacto
                    </h2>
                    <p class="text-white mb-3">
                        Si tienes preguntas, inquietudes o solicitudes relacionadas con esta política de privacidad o el manejo de tus datos personales, puedes contactarnos a través de:
                    </p>
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-dark border-primary h-100">
                                <div class="card-body">
                                    <h5 class="text-white mb-3">
                                        <i class="fas fa-envelope me-2"></i>
                                        Correo Electrónico
                                    </h5>
                                    <p class="text-white mb-0">
                                        <a href="mailto:support@kndstore.com" class="text-primary text-decoration-none">
                                            support@kndstore.com
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-dark border-primary h-100">
                                <div class="card-body">
                                    <h5 class="text-white mb-3">
                                        <i class="fab fa-discord me-2"></i>
                                        Discord
                                    </h5>
                                    <p class="text-white mb-0">
                                        <strong>knd_store</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-white mt-4 mb-0">
                        Nos comprometemos a responder todas las consultas relacionadas con privacidad en un plazo razonable, generalmente dentro de <strong>30 días</strong> hábiles.
                    </p>
                </div>

                <!-- Footer -->
                <div class="text-center mt-5 pt-4" style="border-top: 1px solid rgba(138, 43, 226, 0.3);">
                    <p class="text-white mb-2">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>KND Store</strong> - Knowledge 'N Development
                    </p>
                    <p class="text-white mb-0" style="font-size: 0.9rem;">
                        Última actualización: <?php echo date('F Y'); ?>
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<?php 
echo generateFooter();
echo generateScripts();
?>
