<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

echo generateHeader(t('order.meta.title'), t('order.meta.description'));
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="order-section py-5">
    <div class="container">
        <h1 class="mb-4 text-center"><?php echo t('order.title'); ?></h1>
        <p class="text-center mb-5"><?php echo t('order.subtitle'); ?></p>

        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card knd-card">
                    <div class="card-header">
                        <h4 class="mb-0"><?php echo t('order.selected_services.title'); ?></h4>
                    </div>
                    <div class="card-body">
                        <div id="order-items-container">
                            <!-- Aquí se inyectan los items vía JS -->
                        </div>
                        <div id="order-empty-message" class="text-center text-muted">
                            <?php echo t('order.empty_message'); ?>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong><?php echo t('order.total.label'); ?></strong>
                            <span id="order-total" class="fw-bold">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card knd-card">
                    <div class="card-header">
                        <h4 class="mb-0"><?php echo t('order.data.title'); ?></h4>
                    </div>
                    <div class="card-body">
                        <form id="order-form">
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('order.form.name_label'); ?></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('order.form.whatsapp_label'); ?></label>
                                <input type="text" name="whatsapp" class="form-control" placeholder="+58..." required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('order.form.payment_method_label'); ?></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value=""><?php echo t('order.form.payment_method_select'); ?></option>
                                    <option value="Zinli">Zinli</option>
                                    <option value="Binance Pay">Binance Pay</option>
                                    <option value="Pago Móvil">Pago Móvil</option>
                                    <option value="Transferencia bancaria">Transferencia bancaria</option>
                                    <option value="Criptomonedas">Criptomonedas</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('order.form.delivery_type_label'); ?></label>
                                <select name="delivery_type" class="form-select" id="delivery-type-select">
                                    <option value="Digital / remoto"><?php echo t('order.form.delivery_type.digital'); ?></option>
                                    <option value="Delivery coordinado"><?php echo t('order.form.delivery_type.coordinated'); ?></option>
                                </select>
                                <small class="text-muted"><?php echo t('order.form.delivery_type.note'); ?></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('order.form.notes_label'); ?></label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="<?php echo t('order.form.notes_placeholder'); ?>"></textarea>
                            </div>

                            <button type="button" id="send-whatsapp-order" class="btn btn-whatsapp w-100">
                                <i class="fab fa-whatsapp me-2"></i>
                                <?php echo t('order.form.submit'); ?>
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

    function saveOrderItems(items) {
        localStorage.setItem(ORDER_KEY, JSON.stringify(items));
    }

    function updateItemQuantity(itemId, change) {
        const items = loadOrderItems();
        const index = items.findIndex(i => i.id === itemId);
        
        if (index === -1) return items;
        
        items[index].qty += change;
        
        if (items[index].qty <= 0) {
            items.splice(index, 1);
        }
        
        saveOrderItems(items);
        return items;
    }

    function removeItem(itemId) {
        const items = loadOrderItems();
        const filtered = items.filter(i => i.id !== itemId);
        saveOrderItems(filtered);
        return filtered;
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

            // Construir información adicional (variants, brief)
            let additionalInfo = '';
            if (item.variants) {
                additionalInfo = `<div class="text-info small mb-1">`;
                if (item.variants.size) additionalInfo += `Talla: ${item.variants.size}`;
                if (item.variants.color) additionalInfo += ` | Color: ${item.variants.color}`;
                additionalInfo += `</div>`;
            }
            if (item.type === 'apparel') {
                additionalInfo += `<div class="text-warning small"><i class="fas fa-truck me-1"></i> + Delivery coordinado</div>`;
            }
            if (item.brief) {
                additionalInfo += `<div class="text-muted small mt-1"><i class="fas fa-file-alt me-1"></i> Brief incluido</div>`;
            }

            const div = document.createElement('div');
            div.className = 'order-item-row d-flex justify-content-between align-items-center mb-3 p-3';
            div.style.border = '1px solid rgba(37, 156, 174, 0.3)';
            div.style.borderRadius = '10px';
            div.style.background = 'rgba(26, 26, 46, 0.5)';
            div.innerHTML = `
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">${item.name}</strong>
                    <div class="text-muted small mb-2">Precio unitario: ${formatPrice(item.price)}</div>
                    ${additionalInfo}
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <button class="btn btn-sm btn-outline-neon qty-btn" data-action="decrease" data-id="${item.id}" ${item.qty <= 1 ? 'disabled' : ''}>
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="qty-display" style="min-width: 40px; text-align: center; font-weight: 600;">${item.qty}</span>
                        <button class="btn btn-sm btn-outline-neon qty-btn" data-action="increase" data-id="${item.id}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    <div class="fw-bold" style="font-size: 1.1rem; color: var(--knd-neon-blue);">
                        ${formatPrice(lineTotal)}
                    </div>
                    <button class="btn btn-sm btn-danger remove-item-btn" data-id="${item.id}" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(div);
        });

        totalEl.textContent = formatPrice(total);
        
        // Agregar listeners para los botones
        container.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = parseInt(this.dataset.id, 10);
                const action = this.dataset.action;
                const change = action === 'increase' ? 1 : -1;
                
                updateItemQuantity(itemId, change);
                renderOrderItems();
                updateOrderBadge();
            });
        });

        container.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = parseInt(this.dataset.id, 10);
                const item = items.find(i => i.id === itemId);
                
                const confirmTemplate = (window.I18N && window.I18N['order.confirm_remove']) 
                    ? window.I18N['order.confirm_remove']
                    : '¿Eliminar "{name}" del pedido?';
                const confirmMsg = confirmTemplate.replace('{name}', item.name);
                if (confirm(confirmMsg)) {
                    removeItem(itemId);
                    renderOrderItems();
                    updateOrderBadge();
                }
            });
        });
    }

    function updateOrderBadge() {
        const items = loadOrderItems();
        const totalQty = items.reduce((sum, item) => sum + item.qty, 0);
        const badge = document.querySelector('#order-count');
        if (badge) {
            if (totalQty > 0) {
                badge.textContent = totalQty;
                badge.style.display = 'inline-flex';
            } else {
                badge.style.display = 'none';
            }
        }
        // Actualizar también el badge de scroll nav si existe
        if (window.updateOrderBadgeInScrollNav && typeof window.updateOrderBadgeInScrollNav === 'function') {
            window.updateOrderBadgeInScrollNav();
        }
    }

    renderOrderItems();
    updateOrderBadge();
    
    // Actualizar delivery type si hay apparel en el pedido
    function checkDeliveryType() {
        const items = loadOrderItems();
        const hasApparel = items.some(item => item.type === 'apparel');
        const deliverySelect = document.getElementById('delivery-type-select');
        if (deliverySelect && hasApparel) {
            deliverySelect.value = 'Delivery coordinado';
        }
    }
    checkDeliveryType();

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
            let hasApparel = false;
            let hasService = false;
            
            items.forEach(item => {
                const lineTotal = item.price * item.qty;
                total += lineTotal;
                msg += `- ${item.name} (x${item.qty}) - $${lineTotal.toFixed(2)}%0A`;
                
                // Agregar variants si es apparel
                if (item.variants) {
                    if (item.variants.size) msg += `  Talla: ${item.variants.size}%0A`;
                    if (item.variants.color) msg += `  Color: ${item.variants.color}%0A`;
                    hasApparel = true;
                }
                
                // Agregar brief si es service
                if (item.brief) {
                    msg += `  Brief:%0A`;
                    if (item.brief.estilo) msg += `    Estilo: ${item.brief.estilo}%0A`;
                    if (item.brief.colores) msg += `    Colores: ${item.brief.colores}%0A`;
                    if (item.brief.texto) msg += `    Texto: ${item.brief.texto}%0A`;
                    if (item.brief.referencias) msg += `    Referencias: ${item.brief.referencias}%0A`;
                    if (item.brief.detalles) msg += `    Detalles: ${item.brief.detalles}%0A`;
                    hasService = true;
                }
                
                if (item.type === 'apparel') hasApparel = true;
                if (item.type === 'service') hasService = true;
            });

            msg += `%0A*Total:* $${total.toFixed(2)}%0A`;
            if (payment) msg += `*Método de pago:* ${payment}%0A`;
            
            // Actualizar delivery type si hay apparel
            if (hasApparel) {
                msg += `*Tipo de entrega:* Delivery coordinado (Apparel)%0A`;
            } else if (deliveryType) {
                msg += `*Tipo de entrega:* ${deliveryType}%0A`;
            }
            
            if (notes) {
                const notesLabel = (window.I18N && window.I18N['order.whatsapp.notes_label']) || 'Notas adicionales:';
                msg += `%0A*${notesLabel}* ${notes}%0A`;
            }

            if (hasApparel || hasService) {
                // Nota importante - podría venir de window.I18N si se necesita
                msg += '%0A*Nota importante:* Te contactaremos por WhatsApp/medios para coordinar delivery y/o detalles del diseño.%0A';
            }
            
            msg += '%0AEnvíame el comprobante de pago por aquí cuando lo tengas listo.';

            // Reemplaza este número con tu número de WhatsApp real
            const phone = '584246661334';
            const url = `https://wa.me/${phone}?text=${msg}`;

            window.open(url, '_blank');
        });
    }
});
</script>

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>

