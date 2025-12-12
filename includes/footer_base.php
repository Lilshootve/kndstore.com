    </main>

    <!-- Pie de página premium -->
    <footer class="py-5 position-relative">
        <div class="container position-relative z-2">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h3 class="mb-4">
                        <i class="fas fa-crown me-2"></i>
                        <span class="glow-text">KND STORE</span>
                    </h3>
                    <p class="mb-4" style="opacity: 0.8;">
                        Tu destino premium para servicios gaming. Ofrecemos entrega instantánea, soporte 24/7 y las mejores tarifas del mercado.
                    </p>
                    <div class="d-flex mt-4">
                        <a href="#" class="btn btn-outline-neon btn-icon me-3">
                            <i class="fab fa-discord"></i>
                        </a>
                        <a href="#" class="btn btn-outline-neon btn-icon me-3">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-neon btn-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
        <h5 class="mb-4">NAVEGACIÓN</h5>
        <ul class="list-unstyled">
          <li class="mb-3"><a href="/index.php" class="text-decoration-none" style="opacity: 0.8;">Inicio</a></li>
          <li class="mb-3"><a href="/products.php" class="text-decoration-none" style="opacity: 0.8;">Productos</a></li>
          <li class="mb-3"><a href="/about.php" class="text-decoration-none" style="opacity: 0.8;">Sobre Nosotros</a></li>
          <li class="mb-3"><a href="/contact.php" class="text-decoration-none" style="opacity: 0.8;">Contacto</a></li>
          <li class="mb-3"><a href="/faq.php" class="text-decoration-none" style="opacity: 0.8;">FAQ</a></li>
          <li class="mb-3"><a href="/privacy.php" class="text-decoration-none" style="opacity: 0.8;">Política de Privacidad</a></li>
          <li class="mb-3"><a href="/terms.php" class="text-decoration-none" style="opacity: 0.8;">Términos y Condiciones</a></li>
        </ul>
      </div>
                
                <div class="col-lg-3 col-md-4">
                    <h5 class="mb-4">CATEGORÍAS</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="/products.php?categoria=tecnologia" class="text-decoration-none" style="opacity: 0.8;">Servicios de Tecnología</a></li>
                        <li class="mb-3"><a href="/products.php?categoria=gaming" class="text-decoration-none" style="opacity: 0.8;">Servicios Gaming</a></li>
                        <li class="mb-3"><a href="/products.php?categoria=accesorios" class="text-decoration-none" style="opacity: 0.8;">Accesorios Digitales</a></li>
                        <li class="mb-3"><a href="/products.php?categoria=software" class="text-decoration-none" style="opacity: 0.8;">Software y Packs</a></li>
                        <li class="mb-3"><a href="/products.php?categoria=hardware" class="text-decoration-none" style="opacity: 0.8;">Asesoría Hardware</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-4">
                    <h5 class="mb-4">CONTACTO</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-envelope me-3" style="color: var(--neon-accents);"></i>
                            <span style="opacity: 0.8;">support@kndstore.com</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-headset me-3" style="color: var(--neon-accents);"></i>
                            <span style="opacity: 0.8;">Soporte 24/7</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fab fa-discord me-3" style="color: var(--neon-accents);"></i>
                            <span style="opacity: 0.8;">Discord: knd_store</span>
                        </li>
                    </ul>
                    
                    <h6 class="mt-4 mb-3">PAGOS SEGUROS</h6>
                    <div class="d-flex flex-wrap">
                        <div class="me-3 mb-3">
                            <i class="fab fa-cc-paypal fa-2x" style="color: var(--cosmic-lavender);"></i>
                        </div>
                        <div class="me-3 mb-3">
                            <i class="fab fa-cc-stripe fa-2x" style="color: var(--cosmic-lavender);"></i>
                        </div>
                        <div class="me-3 mb-3">
                            <i class="fab fa-bitcoin fa-2x" style="color: var(--cosmic-lavender);"></i>
                        </div>
                        <div class="me-3 mb-3">
                            <i class="fab fa-cc-visa fa-2x" style="color: var(--cosmic-lavender);"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-5" style="border-color: rgba(138, 43, 226, 0.2);">
            
            <div class="text-center pt-3">
                <p class="mb-0" style="opacity: 0.7;">
                    &copy; <?= date('Y') ?> KND STORE. Todos los derechos reservados.
                </p>
            </div>
        </div>
        
        <!-- Efecto de partículas -->
        <div class="position-absolute top-0 left-0 w-100 h-100" id="particles-js"></div>
    </footer>

    <!-- jQuery (requerido para AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert para notificaciones -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

    <!-- Custom JS y scripts de carrito -->
    <script>
        // Inicializar partículas
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                particles: {
                    number: { value: 60, density: { enable: true, value_area: 800 } },
                    color: { value: "#8a2be2" },
                    shape: { type: "circle" },
                    opacity: { value: 0.3, random: true },
                    size: { value: 3, random: true },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: "#6a0dad",
                        opacity: 0.2,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 2,
                        direction: "none",
                        random: true,
                        straight: false,
                        out_mode: "out",
                        bounce: false
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: true, mode: "repulse" },
                        onclick: { enable: true, mode: "push" },
                        resize: true
                    }
                },
                retina_detect: true
            });
        });
        
        // Actualizar contador del carrito
        function updateCartCount(count) {
            console.log('updateCartCount (antes)', $('#cart-count').text());
            if (parseInt(count) > 0) {
                $('#cart-count').text(count).show();
            } else {
                $('#cart-count').hide();
            }
            console.log('updateCartCount (después)', $('#cart-count').text());
        }
        
        // Efecto de desplazamiento suave
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <script>
    $(document).ready(function() {
        // Añadir al carrito
        $('body').on('click', '.add-to-cart', function() {
            const productId = $(this).data('id');
            const productName = $(this).data('name');
            const productPrice = $(this).data('price');
            
            $.post('cart-actions.php', {
                action: 'add',
                id: productId
            }, function(response) {
                if(response.success) {
                    // Actualizar contador del carrito
                    updateCartCount(response.count);
                    
                    // Mostrar notificación
                    Swal.fire({
                        icon: 'success',
                        title: '¡Añadido!',
                        text: productName + ' ha sido añadido al carrito',
                        showConfirmButton: false,
                        timer: 1500,
                        position: 'top-end'
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }, 'json');
        });
        
        // Actualizar cantidad (en carrito.php)
        $('body').on('click', '.update-qty', function() {
            const productId = $(this).data('id');
            const action = $(this).data('action');
            const row = $(this).closest('tr');
            let currentQty = parseInt(row.find('.qty-value').text());
            
            if(action === 'increase') {
                currentQty++;
            } else if(action === 'decrease' && currentQty > 1) {
                currentQty--;
            }
            
            $.post('cart-actions.php', {
                action: 'update',
                id: productId,
                qty: currentQty
            }, function(response) {
                if(response.success) {
                    row.find('.qty-value').text(currentQty);
                    updateCartCount(response.count);
                    location.reload(); // Recargar para actualizar totales
                }
            }, 'json');
        });
        
        // Eliminar producto (en carrito.php)
        $('body').on('click', '.remove-from-cart', function() {
            const productId = $(this).data('id');
            
            Swal.fire({
                title: '¿Eliminar producto?',
                text: "¿Estás seguro de que quieres eliminar este producto del carrito?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('cart-actions.php', {
                        action: 'remove',
                        id: productId
                    }, function(response) {
                        if(response.success) {
                            updateCartCount(response.count);
                            location.reload();
                        }
                    }, 'json');
                }
            });
        });
        
        // Vaciar carrito (en carrito.php)
        $('body').on('click', '#clear-cart', function() {
            Swal.fire({
                title: '¿Vaciar carrito?',
                text: "¿Estás seguro de que quieres vaciar todo el carrito?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, vaciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('cart-actions.php', {
                        action: 'clear'
                    }, function(response) {
                        if(response.success) {
                            updateCartCount(response.count);
                            location.reload();
                        }
                    }, 'json');
                }
            });
        });
    });
    </script>

</body>
</html>