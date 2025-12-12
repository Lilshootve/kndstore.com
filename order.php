<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

echo generateHeader('Tu Pedido', 'Revisa y confirma tu pedido en KND Store');
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="order-section py-5">
    <div class="container">
        <h1 class="mb-4 text-center">Tu pedido</h1>
        <p class="text-center mb-5">Revisa los servicios seleccionados, completa tus datos y envía el pedido por WhatsApp.</p>

        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card knd-card">
                    <div class="card-header">
                        <h4 class="mb-0">Servicios seleccionados</h4>
                    </div>
                    <div class="card-body">
                        <div id="order-items-container">
                            <!-- Aquí se inyectan los items vía JS -->
                        </div>
                        <div id="order-empty-message" class="text-center text-muted">
                            No tienes servicios en el pedido todavía.
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <span id="order-total" class="fw-bold">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card knd-card">
                    <div class="card-header">
                        <h4 class="mb-0">Datos del pedido</h4>
                    </div>
                    <div class="card-body">
                        <form id="order-form">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control" placeholder="+58..." required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Método de pago</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Selecciona un método</option>
                                    <option value="Zinli">Zinli</option>
                                    <option value="Binance Pay">Binance Pay</option>
                                    <option value="Pago Móvil">Pago Móvil</option>
                                    <option value="Transferencia bancaria">Transferencia bancaria</option>
                                    <option value="Criptomonedas">Criptomonedas</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tipo de entrega</label>
                                <select name="delivery_type" class="form-select" required>
                                    <option value="Digital / remoto">Digital / remoto</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notas adicionales</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Especifica detalles de tu servicio, horarios, usuario de juego, etc."></textarea>
                            </div>

                            <button type="button" id="send-whatsapp-order" class="btn btn-whatsapp w-100">
                                <i class="fab fa-whatsapp me-2"></i>
                                Enviar pedido por WhatsApp
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Lógica para leer el pedido desde localStorage y rellenar la vista en order.php

document.addEventListener('DOMContentLoaded', function () {
    const ORDER_KEY = 'knd_order_items';

    function loadOrderItems() {
        try {
            const raw = localStorage.getItem(ORDER_KEY);
            if (!raw) return [];
            return JSON.parse(raw);
        } catch (e) {
            console.error('Error leyendo pedido en order.php', e);
            return [];
        }
    }

    function formatPrice(amount) {
        return '$' + amount.toFixed(2);
    }

    function renderOrderItems() {
        const items = loadOrderItems();
        const container = document.getElementById('order-items-container');
        const emptyMsg = document.getElementById('order-empty-message');
        const totalEl = document.getElementById('order-total');

        container.innerHTML = '';

        if (!items.length) {
            emptyMsg.style.display = 'block';
            totalEl.textContent = '$0.00';
            return;
        }

        emptyMsg.style.display = 'none';

        let total = 0;

        items.forEach(item => {
            const lineTotal = item.price * item.qty;
            total += lineTotal;

            const div = document.createElement('div');
            div.className = 'd-flex justify-content-between align-items-center mb-2';
            div.innerHTML = `
                <div>
                    <strong>${item.name}</strong>
                    <div class="text-muted small">Cantidad: ${item.qty} · Precio: ${formatPrice(item.price)}</div>
                </div>
                <div class="fw-bold">
                    ${formatPrice(lineTotal)}
                </div>
            `;
            container.appendChild(div);
        });

        totalEl.textContent = formatPrice(total);
    }

    renderOrderItems();

    // Envío a WhatsApp
    const sendBtn = document.getElementById('send-whatsapp-order');
    if (sendBtn) {
        sendBtn.addEventListener('click', function () {
            const items = loadOrderItems();
            if (!items.length) {
                alert('Tu pedido está vacío. Añade servicios desde el catálogo primero.');
                return;
            }

            const form = document.getElementById('order-form');
            const formData = new FormData(form);
            const name = formData.get('name') || '';
            const whatsapp = formData.get('whatsapp') || '';
            const payment = formData.get('payment_method') || '';
            const deliveryType = formData.get('delivery_type') || '';
            const notes = formData.get('notes') || '';

            let msg = '🛰 *Nuevo pedido desde KND Store*%0A%0A';
            if (name) msg += '*Nombre:* ' + name + '%0A';
            if (whatsapp) msg += '*WhatsApp cliente:* ' + whatsapp + '%0A';
            msg += '%0A*Servicios solicitados:*%0A';

            let total = 0;
            items.forEach(item => {
                const lineTotal = item.price * item.qty;
                total += lineTotal;
                msg += `- ${item.name} (x${item.qty}) - $${lineTotal.toFixed(2)}%0A`;
            });

            msg += `%0A*Total:* $${total.toFixed(2)}%0A`;
            if (payment) msg += `*Método de pago:* ${payment}%0A`;
            if (deliveryType) msg += `*Tipo de entrega:* ${deliveryType}%0A`;
            if (notes) msg += `%0A*Notas adicionales:* ${notes}%0A`;

            msg += '%0AEnvíame el comprobante de pago por aquí cuando lo tengas listo.';

            // Reemplaza este número con tu número de WhatsApp real
            const phone = '584246661334';
            const url = `https://wa.me/${phone}?text=${msg}`;

            window.open(url, '_blank');
        });
    }
});
</script>

<?php
echo generateFooter();
echo generateScripts();
?>

