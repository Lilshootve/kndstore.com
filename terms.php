<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';
?>

<?php echo generateHeader(t('terms.meta.title'), t('terms.meta.description')); ?>

<!-- Particles Background -->
<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="hero-title">
                    <span class="text-gradient"><?php echo t('terms.hero.title_line1'); ?></span><br>
                    <span class="text-gradient"><?php echo t('terms.hero.title_line2'); ?></span>
                </h1>
                <p class="hero-subtitle">
                    <?php echo t('terms.hero.subtitle'); ?>
                </p>
                <div class="mt-3">
                    <span class="badge bg-primary fs-6">
                        <i class="fas fa-file-contract me-2"></i>
                        <?php echo t('terms.last_update.badge', null, ['month_year' => date('F Y')]); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Terms Content -->
<section class="py-5 bg-dark-epic">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                
                <!-- Introducción -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-rocket me-2"></i>
                        Introducción a KND Store
                    </h2>
                    <p class="text-white mb-3">
                        Bienvenido a <strong>KND Store</strong> (<strong>Knowledge 'N Development</strong>). Estos términos y condiciones rigen el uso de nuestro sitio web <strong>kndstore.com</strong> y todos los servicios digitales que ofrecemos.
                    </p>
                    <p class="text-white mb-3">
                        Al acceder y utilizar nuestros servicios, aceptas cumplir con estos términos. Si no estás de acuerdo con alguna parte de estos términos, te recomendamos no utilizar nuestra plataforma.
                    </p>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>KND Store</strong> se reserva el derecho de modificar estos términos en cualquier momento. Las actualizaciones serán publicadas en esta página con la fecha correspondiente.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Naturaleza Digital -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-microchip me-2"></i>
                        Naturaleza Digital de los Servicios
                    </h2>
                    <p class="text-white mb-3">
                        Todos los servicios ofrecidos por <strong>KND Store</strong> son <strong>100% digitales</strong>. Esto incluye, pero no se limita a:
                    </p>
                    <ul class="text-white mb-3">
                        <li><strong>Servicios técnicos remotos:</strong> Formateo, optimización, instalación de software y drivers, análisis de rendimiento.</li>
                        <li><strong>Activación de productos digitales:</strong> Claves de juegos, gift cards, suscripciones para plataformas como Steam, PSN, Xbox, Riot Games, Epic Games, etc.</li>
                        <li><strong>Contenido personalizado:</strong> Avatares, wallpapers, icon packs, y otros recursos digitales generados mediante inteligencia artificial o diseño manual.</li>
                        <li><strong>Asesorías y consultorías:</strong> Presupuestos para builds de PC, análisis de compatibilidad de hardware, simulaciones de builds.</li>
                        <li><strong>Servicios de configuración:</strong> Instalación de software, optimización de sistemas, configuración de seguridad.</li>
                    </ul>
                    <div class="alert alert-warning bg-dark border-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> KND Store ofrece servicios digitales, productos físicos (apparel) y servicios personalizables. Los servicios digitales se entregan de forma instantánea. Los productos físicos requieren coordinación de delivery.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Apparel (Productos Físicos) -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-tshirt me-2"></i>
                        Productos Apparel (Ropa)
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> ofrece productos físicos de ropa (hoodies, t-shirts) bajo las siguientes condiciones:
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-truck me-2"></i>
                        Delivery Coordinado
                    </h3>
                    <ul class="text-white mb-3">
                        <li>El <strong>delivery se coordina por WhatsApp o medios de contacto</strong> después de la compra.</li>
                        <li>Los tiempos de entrega son estimados y pueden variar según la ubicación y disponibilidad.</li>
                        <li>El cliente debe proporcionar datos de contacto precisos para la coordinación del delivery.</li>
                        <li><strong>KND Store</strong> no se hace responsable por retrasos causados por información incorrecta del cliente.</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-ruler me-2"></i>
                        Tallas y Cambios
                    </h3>
                    <ul class="text-white mb-3">
                        <li>Las tallas disponibles son: S, M, L, XL. Consulta la guía de tallas en cada producto.</li>
                        <li>Aceptamos <strong>cambios y devoluciones dentro de los primeros 7 días</strong> después de la entrega, siempre que el producto esté en su estado original (sin usar, con etiquetas).</li>
                        <li>Los costos de envío para cambios/devoluciones corren por cuenta del cliente, salvo error de <strong>KND Store</strong>.</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-shield-alt me-2"></i>
                        Responsabilidad por Datos de Contacto
                    </h3>
                    <p class="text-white mb-3">
                        El cliente es responsable de proporcionar información de contacto correcta y actualizada. <strong>KND Store</strong> utilizará estos datos únicamente para coordinar la entrega y comunicación relacionada con el pedido.
                    </p>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Custom Design Services -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-palette me-2"></i>
                        Servicios de Diseño Personalizado (Custom Design)
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> ofrece servicios de diseño personalizado para T-Shirts, Hoodies y conceptos completos de outfit.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-file-alt me-2"></i>
                        Alcance del Servicio
                    </h3>
                    <ul class="text-white mb-3">
                        <li>Diseño personalizado según las especificaciones del cliente (brief).</li>
                        <li>Entrega digital del diseño en formato editable y mockups profesionales.</li>
                        <li><strong>No incluye impresión física</strong> del producto (solo el diseño digital).</li>
                        <li>Revisión básica incluida (una ronda de modificaciones menores).</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-edit me-2"></i>
                        Revisiones y Modificaciones
                    </h3>
                    <ul class="text-white mb-3">
                        <li>Se incluye <strong>una revisión básica</strong> para ajustes menores (colores, texto, posición).</li>
                        <li>Cambios mayores o rediseños completos pueden requerir un cargo adicional, a coordinar con el cliente.</li>
                        <li>Los tiempos de entrega pueden variar según la complejidad del diseño.</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-box me-2"></i>
                        Entregables
                    </h3>
                    <ul class="text-white mb-3">
                        <li>Archivos editables del diseño (formato vectorial o raster según corresponda).</li>
                        <li>Mockups profesionales del diseño aplicado al producto.</li>
                        <li>Versiones en diferentes resoluciones si aplica.</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-copyright me-2"></i>
                        Política de Contenido
                    </h3>
                    <div class="alert alert-danger bg-dark border-danger">
                        <i class="fas fa-ban me-2"></i>
                        <strong>No aceptamos contenido protegido por derechos de autor o marcas registradas sin autorización del titular.</strong> El cliente es responsable de garantizar que el contenido proporcionado (referencias, texto, imágenes) no infrinja derechos de terceros. <strong>KND Store</strong> se reserva el derecho de rechazar proyectos que violen esta política.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Registro y Seguridad -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-user-shield me-2"></i>
                        Registro y Seguridad del Usuario
                    </h2>
                    <p class="text-white mb-3">
                        Para utilizar ciertos servicios de <strong>KND Store</strong>, es posible que necesites proporcionar información de contacto (nombre, correo electrónico, número de WhatsApp, etc.).
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-check-circle me-2"></i>
                        Requisitos de Edad
                    </h3>
                    <p class="text-white mb-3">
                        Debes ser <strong>mayor de 18 años</strong> o contar con autorización expresa de tus padres o tutores legales para utilizar nuestros servicios. Al realizar una compra, confirmas que cumples con este requisito.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-lock me-2"></i>
                        Seguridad de la Cuenta
                    </h3>
                    <p class="text-white mb-3">
                        Eres responsable de mantener la confidencialidad de cualquier información de acceso que utilices en nuestra plataforma. <strong>KND Store</strong> no se hace responsable por accesos no autorizados resultantes de la negligencia del usuario.
                    </p>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Proceso de Compra -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Proceso de Compra y Entrega Instantánea
                    </h2>
                    <p class="text-white mb-3">
                        El proceso de compra en <strong>KND Store</strong> funciona de la siguiente manera:
                    </p>
                    <ol class="text-white mb-3">
                        <li><strong>Selección de servicios:</strong> Navegas por nuestro catálogo y seleccionas los servicios que deseas contratar.</li>
                        <li><strong>Confirmación del pedido:</strong> Revisas tu pedido, proporcionas tus datos de contacto y seleccionas tu método de pago preferido.</li>
                        <li><strong>Procesamiento del pago:</strong> El pago se procesa a través de plataformas externas seguras (Zinli, Binance Pay, PayPal, Pago Móvil, transferencias bancarias, criptomonedas, etc.).</li>
                        <li><strong>Entrega instantánea:</strong> Una vez confirmado el pago, procedemos a entregar el servicio o producto digital de forma inmediata o en el plazo acordado (según el tipo de servicio).</li>
                    </ol>
                    <div class="alert alert-success bg-dark border-success">
                        <i class="fas fa-bolt me-2"></i>
                        <strong>Entrega Digital:</strong> Los productos digitales (claves, archivos, servicios remotos) se entregan por correo electrónico, WhatsApp, Discord u otra plataforma acordada, generalmente en un plazo de <strong>24 a 48 horas</strong> después de la confirmación del pago.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Uso Aceptable -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-balance-scale me-2"></i>
                        Uso Aceptable de la Plataforma
                    </h2>
                    <p class="text-white mb-3">
                        Al utilizar <strong>kndstore.com</strong>, te comprometes a:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Utilizar nuestros servicios únicamente para fines legales y legítimos.</li>
                        <li>No intentar acceder a áreas restringidas del sitio, interferir con el funcionamiento de la plataforma, o realizar actividades que puedan dañar nuestros sistemas.</li>
                        <li>No copiar, reproducir, distribuir o modificar el contenido del sitio sin autorización expresa.</li>
                        <li>No utilizar nuestros servicios para actividades fraudulentas, spam, o cualquier otra actividad ilegal.</li>
                        <li>Proporcionar información veraz y actualizada al realizar compras o solicitar servicios.</li>
                    </ul>
                    <div class="alert alert-danger bg-dark border-danger">
                        <i class="fas fa-ban me-2"></i>
                        <strong>Violación de Términos:</strong> El incumplimiento de estas normas puede resultar en la suspensión o terminación inmediata de tu acceso a nuestros servicios, sin derecho a reembolso.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Precios y Pagos -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-credit-card me-2"></i>
                        Precios, Pagos y Métodos Permitidos
                    </h2>
                    <p class="text-white mb-3">
                        <strong>Precios:</strong> Todos los precios mostrados en <strong>kndstore.com</strong> están expresados en dólares estadounidenses (USD) o su equivalente en la moneda local según el método de pago seleccionado.
                    </p>
                    <p class="text-white mb-3">
                        <strong>Métodos de pago aceptados:</strong>
                    </p>
                    <ul class="text-white mb-3">
                        <li>Zinli</li>
                        <li>Binance Pay</li>
                        <li>PayPal</li>
                        <li>Pago Móvil</li>
                        <li>Transferencias bancarias</li>
                        <li>Criptomonedas (Bitcoin, Ethereum, USDT, etc.)</li>
                        <li>Otros métodos acordados previamente</li>
                    </ul>
                    <p class="text-white mb-3">
                        <strong>Procesamiento de pagos:</strong> Los pagos se procesan a través de plataformas externas seguras. <strong>KND Store</strong> no almacena información de tarjetas de crédito ni datos financieros sensibles. Toda la información de pago es manejada por los proveedores de servicios de pago correspondientes.
                    </p>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Seguridad:</strong> Respetamos el precio confirmado al momento de tu compra, incluso si los precios cambian posteriormente en el sitio.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Política de Reembolsos -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-undo me-2"></i>
                        Política de Reembolsos
                    </h2>
                    <p class="text-white mb-3">
                        Debido a la <strong>naturaleza digital e instantánea</strong> de nuestros servicios, <strong>KND Store</strong> aplica la siguiente política de reembolsos:
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-times-circle me-2"></i>
                        Reembolsos NO Aplicables
                    </h3>
                    <p class="text-white mb-3">
                        <strong>NO</strong> ofrecemos reembolsos en los siguientes casos:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Una vez que el servicio ha sido entregado o completado.</li>
                        <li>Una vez que una clave de juego o gift card ha sido activada o entregada.</li>
                        <li>Cambio de opinión del cliente después de la compra.</li>
                        <li>Incompatibilidad del producto con el sistema del cliente (si el producto cumple con las especificaciones anunciadas).</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-check-circle me-2"></i>
                        Casos Excepcionales
                    </h3>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> evaluará reembolsos o compensaciones en casos excepcionales, tales como:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Error técnico de nuestra parte que impida la entrega del servicio.</li>
                        <li>Producto defectuoso o no funcional entregado por error nuestro.</li>
                        <li>Duplicación de pago por error del sistema.</li>
                    </ul>
                    <div class="alert alert-warning bg-dark border-warning">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Plazo de Reclamación:</strong> Cualquier solicitud de reembolso debe realizarse dentro de <strong>48 horas</strong> posteriores a la compra o entrega del servicio. Después de este plazo, no se considerarán solicitudes de reembolso.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Propiedad Intelectual -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-copyright me-2"></i>
                        Propiedad Intelectual y Derechos de Autor
                    </h2>
                    <p class="text-white mb-3">
                        Todo el contenido presente en <strong>kndstore.com</strong>, incluyendo pero no limitado a:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Logos, marcas, nombres comerciales y diseños gráficos.</li>
                        <li>Textos, descripciones de productos, guías y documentación.</li>
                        <li>Imágenes, ilustraciones, wallpapers y recursos visuales.</li>
                        <li>Código fuente, scripts, y funcionalidades del sitio web.</li>
                        <li>Contenido generado mediante inteligencia artificial o diseño manual.</li>
                    </ul>
                    <p class="text-white mb-3">
                        Es propiedad exclusiva de <strong>KND Store</strong> o de sus licenciantes, y está protegido por leyes de derechos de autor, marcas registradas y otras leyes de propiedad intelectual.
                    </p>
                    <div class="alert alert-danger bg-dark border-danger">
                        <i class="fas fa-ban me-2"></i>
                        <strong>Prohibido:</strong> Está estrictamente prohibido copiar, reproducir, distribuir, modificar, crear obras derivadas, o utilizar cualquier contenido de <strong>kndstore.com</strong> sin autorización expresa y por escrito de <strong>KND Store</strong>.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Licencias de Uso -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-key me-2"></i>
                        Licencias de Uso Digital
                    </h2>
                    <p class="text-white mb-3">
                        Al adquirir productos digitales de <strong>KND Store</strong>, obtienes una <strong>licencia de uso personal y no transferible</strong> para el contenido adquirido.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-user me-2"></i>
                        Uso Personal
                    </h3>
                    <p class="text-white mb-3">
                        Los productos digitales (wallpapers, avatares, icon packs, etc.) están destinados para <strong>uso personal únicamente</strong>. No puedes:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Revender, redistribuir o compartir los archivos con terceros.</li>
                        <li>Utilizar el contenido para fines comerciales sin autorización.</li>
                        <li>Modificar y reclamar autoría del contenido.</li>
                    </ul>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-gamepad me-2"></i>
                        Claves de Juegos y Gift Cards
                    </h3>
                    <p class="text-white mb-3">
                        Las claves de juegos y gift cards adquiridas son <strong>finales y no reembolsables</strong> una vez entregadas. El cliente es responsable de verificar la compatibilidad del producto con su plataforma antes de la activación.
                    </p>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Limitación de Responsabilidad -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Limitación de Responsabilidad
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> se esfuerza por proporcionar servicios de alta calidad, pero no garantiza que:
                    </p>
                    <ul class="text-white mb-3">
                        <li>El sitio web esté libre de errores, interrupciones o fallos técnicos.</li>
                        <li>Los servicios cumplan con todas las expectativas específicas del cliente.</li>
                        <li>Los productos digitales sean compatibles con todos los sistemas o dispositivos.</li>
                    </ul>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> no se hace responsable de:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Daños indirectos, incidentales o consecuentes derivados del uso de nuestros servicios.</li>
                        <li>Pérdida de datos, información o contenido del cliente resultante del uso de nuestros servicios técnicos.</li>
                        <li>Problemas derivados del uso indebido de productos o servicios por parte del cliente.</li>
                        <li>Interrupciones en el servicio causadas por factores externos (fallos de internet, servidores, proveedores de pago, etc.).</li>
                    </ul>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Responsabilidad Máxima:</strong> En ningún caso la responsabilidad total de <strong>KND Store</strong> excederá el monto pagado por el cliente por el servicio específico en cuestión.
                    </div>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Garantías -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-shield-alt me-2"></i>
                        Garantías y Disponibilidad del Servicio
                    </h2>
                    <p class="text-white mb-3">
                        <strong>Garantía de Servicio:</strong> <strong>KND Store</strong> se compromete a:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Entregar los servicios contratados en los plazos acordados o publicados.</li>
                        <li>Proporcionar soporte técnico básico durante el proceso de entrega del servicio.</li>
                        <li>Mantener la confidencialidad de la información del cliente.</li>
                    </ul>
                    <p class="text-white mb-3">
                        <strong>Disponibilidad:</strong> Nos esforzamos por mantener <strong>kndstore.com</strong> disponible 24/7, pero no garantizamos disponibilidad ininterrumpida. Podemos realizar mantenimientos programados o de emergencia que puedan afectar temporalmente el acceso al sitio.
                    </p>
                    <p class="text-white mb-3">
                        <strong>Sin Garantías Expresas:</strong> Excepto donde se indique expresamente, <strong>KND Store</strong> proporciona los servicios "tal cual" y "según disponibilidad", sin garantías expresas o implícitas de ningún tipo.
                    </p>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Suspensión -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-ban me-2"></i>
                        Suspensión o Terminación de Cuentas
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> se reserva el derecho de suspender o terminar el acceso a nuestros servicios, sin previo aviso y sin derecho a reembolso, en los siguientes casos:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Violación de estos términos y condiciones.</li>
                        <li>Actividades fraudulentas o sospechosas.</li>
                        <li>Uso indebido de la plataforma o intentos de acceder a áreas restringidas.</li>
                        <li>Comportamiento abusivo hacia nuestro equipo o otros usuarios.</li>
                        <li>Incumplimiento de obligaciones de pago.</li>
                    </ul>
                    <p class="text-white mb-3">
                        En caso de terminación, <strong>KND Store</strong> no estará obligado a proporcionar reembolsos por servicios ya entregados o en proceso.
                    </p>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Actualizaciones -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-sync-alt me-2"></i>
                        Actualizaciones y Modificaciones del Servicio
                    </h2>
                    <p class="text-white mb-3">
                        <strong>KND Store</strong> se reserva el derecho de:
                    </p>
                    <ul class="text-white mb-3">
                        <li>Modificar, actualizar o discontinuar cualquier servicio en cualquier momento.</li>
                        <li>Cambiar precios, características o disponibilidad de productos sin previo aviso.</li>
                        <li>Actualizar estos términos y condiciones, notificando los cambios mediante publicación en esta página.</li>
                    </ul>
                    <p class="text-white mb-3">
                        Es responsabilidad del usuario revisar periódicamente estos términos. El uso continuado de nuestros servicios después de cualquier modificación constituye la aceptación de los nuevos términos.
                    </p>
                </div>

                <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.3);">

                <!-- Legislación -->
                <div class="mb-5">
                    <h2 class="section-title mb-4">
                        <i class="fas fa-gavel me-2"></i>
                        Legislación Aplicable y Resolución de Disputas
                    </h2>
                    <p class="text-white mb-3">
                        <strong>Ley Aplicable:</strong> Estos términos se rigen por las leyes de la jurisdicción donde <strong>KND Store</strong> opera, sin tener en cuenta sus disposiciones sobre conflictos de leyes.
                    </p>
                    <h3 class="text-white mb-3 mt-4">
                        <i class="fas fa-handshake me-2"></i>
                        Resolución de Disputas
                    </h3>
                    <p class="text-white mb-3">
                        En caso de cualquier disputa relacionada con estos términos o nuestros servicios, las partes acuerdan intentar resolver el conflicto mediante <strong>negociación de buena fe</strong> antes de recurrir a procedimientos legales formales.
                    </p>
                    <p class="text-white mb-3">
                        Si no se puede llegar a un acuerdo, las disputas se resolverán mediante <strong>arbitraje vinculante</strong> o en los tribunales competentes de la jurisdicción correspondiente, según lo determine <strong>KND Store</strong>.
                    </p>
                    <div class="alert alert-info bg-dark border-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Contacto Previo:</strong> Antes de iniciar cualquier procedimiento legal, te recomendamos contactarnos a través de los canales oficiales para intentar resolver cualquier problema de manera amigable.
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
                        Si tienes preguntas, comentarios o necesitas asistencia relacionada con estos términos y condiciones, puedes contactarnos a través de:
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
                        Nos comprometemos a responder todas las consultas en un plazo razonable, generalmente dentro de <strong>24 a 48 horas</strong> hábiles.
                    </p>
                </div>

                <!-- Footer -->
                <div class="text-center mt-5 pt-4" style="border-top: 1px solid rgba(138, 43, 226, 0.3);">
                    <p class="text-white mb-2">
                        <i class="fas fa-rocket me-2"></i>
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

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>
