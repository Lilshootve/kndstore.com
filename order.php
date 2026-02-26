<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/paypal_config.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

echo generateHeader(t('order.meta.title'), t('order.meta.description'));
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="order-section py-4">
    <div class="container">
        <h1 class="mb-2 text-center"><?php echo t('order.title'); ?></h1>
        <p class="text-center mb-4 order-subtitle"><?php echo t('order.subtitle'); ?></p>

        <div class="row align-items-stretch order-checkout-row">
            <div class="col-lg-7 mb-4">
                <div class="card knd-card h-100">
                    <div class="card-header">
                        <h4 class="mb-0"><?php echo t('order.selected_services.title'); ?></h4>
                    </div>
                    <div class="card-body">
                        <div id="order-items-container">
                            <!-- Items injected via JS -->
                        </div>
                        <div id="order-empty-message" class="text-center text-muted">
                            <?php echo t('order.empty_message'); ?>
                        </div>
                        <hr>
                        <div id="order-totals" class="order-totals">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted"><?php echo t('order.totals.subtotal'); ?></span>
                                <span id="order-subtotal">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted"><?php echo t('order.totals.shipping'); ?> <small>(<?php echo t('order.totals.shipping_quoted'); ?>)</small></span>
                                <span id="order-shipping">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between pt-2 border-top border-secondary">
                                <strong><?php echo t('order.total.label'); ?></strong>
                                <span id="order-total" class="fw-bold" style="color: var(--knd-neon-blue);">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card knd-card h-100">
                    <div class="card-header">
                        <h4 class="mb-0"><?php echo t('order.data.title'); ?></h4>
                    </div>
                    <div class="card-body">
                        <form id="order-form">
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_flow" id="payment-method-select" class="form-select">
                                    <option value="paypal" selected>PayPal</option>
                                    <option value="bank_transfer">Bank Transfer (ACH/Wire)</option>
                                    <option value="whatsapp">WhatsApp (Other)</option>
                                </select>
                                <?php
                                $paypalId = defined('PAYPAL_CLIENT_ID') ? PAYPAL_CLIENT_ID : '';
                                if (empty($paypalId) || strpos($paypalId, 'YOUR_') === 0 || stripos($paypalId, 'placeholder') !== false) {
                                    echo '<div class="alert alert-warning mt-2 py-2 small" role="alert"><i class="fas fa-exclamation-triangle me-1"></i>PayPal credentials not configured. Create includes/paypal_secrets.local.php or set PAYPAL_CLIENT_ID.</div>';
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="order-name"><?php echo t('order.form.name_label'); ?></label>
                                <input type="text" name="name" id="order-name" class="form-control" required>
                                <small class="form-text paypal-optional-hint text-muted" style="display:none;"><?php echo t('order.form.delivery_updates_hint'); ?></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="order-whatsapp"><?php echo t('order.form.whatsapp_label'); ?></label>
                                <input type="text" name="whatsapp" id="order-whatsapp" class="form-control" placeholder="+58..." required>
                                <small class="form-text paypal-optional-hint text-muted" style="display:none;"><?php echo t('order.form.delivery_updates_hint'); ?></small>
                            </div>
                            <div id="whatsapp-other-helper" class="mb-3 manual-only" style="display: none;">
                                <div class="small text-muted p-2 rounded" style="background: rgba(37, 156, 174, 0.1); border: 1px solid rgba(37, 156, 174, 0.2);">
                                    <?php echo t('order.form.whatsapp_other_helper'); ?>
                                </div>
                            </div>
                            <div class="mb-3 manual-only">
                                <label class="form-label"><?php echo t('order.form.payment_method_label'); ?></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value=""><?php echo t('order.form.payment_method_select'); ?></option>
                                    <option value="Bank Transfer">Bank Transfer (ACH/Wire)</option>
                                    <option value="Zinli">Zinli</option>
                                    <option value="Binance Pay">Binance Pay</option>
                                    <option value="Pipol Pay">Pipol Pay</option>
                                    <option value="Mobile Payment">Mobile Payment</option>
                                    <option value="Cryptocurrency">Cryptocurrency</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('order.form.delivery_type_label'); ?></label>
                                <select name="delivery_type" class="form-select" id="delivery-type-select">
                                    <option value="Digital / remote"><?php echo t('order.form.delivery_type.digital'); ?></option>
                                    <option value="Coordinated delivery"><?php echo t('order.form.delivery_type.coordinated'); ?></option>
                                </select>
                                <small class="text-muted"><?php echo t('order.form.delivery_type.note'); ?></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo t('order.form.notes_label'); ?></label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="<?php echo t('order.form.notes_placeholder'); ?>"></textarea>
                            </div>

                            <button type="button" id="send-whatsapp-order" class="btn btn-whatsapp w-100 manual-only">
                                <i class="fab fa-whatsapp me-2"></i>
                                <?php echo t('order.form.submit'); ?>
                            </button>

                            <div id="paypal-section" class="mt-3" style="display: none;">
                                <div class="paypal-card p-3 rounded">
                                    <h5 class="mb-2" style="color: var(--knd-neon-blue); font-size: 1rem;"><?php echo t('order.paypal.title'); ?></h5>
                                    <p class="small text-muted mb-3"><?php echo t('order.paypal.trust'); ?></p>
                                    <div id="paypal-button-container"></div>
                                    <div id="paypal-loading" class="paypal-loading text-center py-3" style="display:none;">
                                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                        <span><?php echo t('order.paypal.loading'); ?></span>
                                    </div>
                                    <div id="paypal-error" class="paypal-error alert alert-danger py-2 small mt-2" style="display:none;" role="alert"></div>
                                </div>
                            </div>
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
    let latestQuote = null;

    function loadOrderItems() {
        try {
            const raw = localStorage.getItem(ORDER_KEY);
            if (!raw) return [];
            return JSON.parse(raw);
        } catch (e) {
            console.error('Error reading order in order.php', e);
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

    async function fetchQuote(items, deliveryType) {
        const response = await fetch('/api/checkout/quote.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                items,
                deliveryType
            })
        });
        if (!response.ok) {
            throw new Error('Unable to calculate totals.');
        }
        return response.json();
    }

    async function renderOrderItems() {
        const items = loadOrderItems();
        const container = document.getElementById('order-items-container');
        const emptyMsg = document.getElementById('order-empty-message');
        const totalEl = document.getElementById('order-total');
        const deliveryType = document.getElementById('delivery-type-select')?.value || '';

        container.innerHTML = '';

        if (!items.length) {
            emptyMsg.style.display = 'block';
            const subtotalEl = document.getElementById('order-subtotal');
            const shippingEl = document.getElementById('order-shipping');
            if (subtotalEl) subtotalEl.textContent = '$0.00';
            if (shippingEl) shippingEl.textContent = '$0.00';
            totalEl.textContent = '$0.00';
            return;
        }

        emptyMsg.style.display = 'none';

        let quote = null;
        try {
            const payloadItems = items.map(item => ({
                id: item.id,
                qty: item.qty,
                variants: item.variants || null
            }));
            quote = await fetchQuote(payloadItems, deliveryType);
            latestQuote = quote;
        } catch (e) {
            const subtotalEl = document.getElementById('order-subtotal');
            const shippingEl = document.getElementById('order-shipping');
            if (subtotalEl) subtotalEl.textContent = '$0.00';
            if (shippingEl) shippingEl.textContent = '$0.00';
            totalEl.textContent = '$0.00';
            emptyMsg.textContent = 'Unable to calculate totals. Please try again.';
            emptyMsg.style.display = 'block';
            return;
        }

        const quoteById = new Map();
        quote.itemsDetailed.forEach(item => {
            quoteById.set(item.id, item);
        });

        items.forEach(item => {
            const quoteItem = quoteById.get(item.id);
            const unitPrice = quoteItem ? quoteItem.unit_price : 0;
            const lineTotal = quoteItem ? quoteItem.line_total : 0;

            // Construir información adicional (variants, brief)
            let additionalInfo = '';
            if (item.variants) {
                additionalInfo = `<div class="text-info small mb-1">`;
                if (item.variants.size) additionalInfo += `Size: ${item.variants.size}`;
                if (item.variants.color) additionalInfo += ` | Color: ${item.variants.color}`;
                additionalInfo += `</div>`;
            }
            if (item.type === 'apparel') {
                additionalInfo += `<div class="text-warning small"><i class="fas fa-truck me-1"></i> + Coordinated delivery</div>`;
            }
            if (item.brief) {
                additionalInfo += `<div class="text-muted small mt-1"><i class="fas fa-file-alt me-1"></i> Brief included</div>`;
            }

            const div = document.createElement('div');
            div.className = 'order-item-row d-flex justify-content-between align-items-center mb-3 p-3';
            div.style.border = '1px solid rgba(37, 156, 174, 0.3)';
            div.style.borderRadius = '10px';
            div.style.background = 'rgba(26, 26, 46, 0.5)';
            div.innerHTML = `
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">${item.name}</strong>
                    <div class="text-muted small mb-2">Unit price: ${formatPrice(unitPrice)}</div>
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
                    <button class="btn btn-sm btn-danger remove-item-btn" data-id="${item.id}" title="Remove">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(div);
        });

        const subtotalEl = document.getElementById('order-subtotal');
        const shippingEl = document.getElementById('order-shipping');
        if (subtotalEl) subtotalEl.textContent = formatPrice(quote.subtotal || 0);
        if (shippingEl) shippingEl.textContent = (quote.shipping != null && quote.shipping > 0) ? formatPrice(quote.shipping) : '—';
        totalEl.textContent = formatPrice(quote.total || 0);
        
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
                    : 'Remove "{name}" from your order?';
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
            deliverySelect.value = 'Coordinated delivery';
        }
    }
    checkDeliveryType();
    const deliverySelect = document.getElementById('delivery-type-select');
    if (deliverySelect) {
        deliverySelect.addEventListener('change', function () {
            renderOrderItems();
        });
    }

    // Envío a WhatsApp
    const sendBtn = document.getElementById('send-whatsapp-order');
    if (sendBtn) {
        sendBtn.addEventListener('click', function () {
            const items = loadOrderItems();
            if (!items.length) {
                alert('Your order is empty. Add items from the shop first.');
                return;
            }

            const form = document.getElementById('order-form');
            const formData = new FormData(form);
            const name = formData.get('name') || '';
            const whatsapp = formData.get('whatsapp') || '';
            const payment = formData.get('payment_method') || '';
            const deliveryType = formData.get('delivery_type') || '';
            const notes = formData.get('notes') || '';

            let msg = '🛰 *New order from KND Store*%0A%0A';
            if (name) msg += '*Name:* ' + name + '%0A';
            if (whatsapp) msg += '*Customer WhatsApp:* ' + whatsapp + '%0A';
            msg += '%0A*Requested items:*%0A';

            let total = 0;
            let hasApparel = false;
            let hasService = false;
            const quoteById = latestQuote && latestQuote.itemsDetailed
                ? new Map(latestQuote.itemsDetailed.map(item => [item.id, item]))
                : new Map();
            
            items.forEach(item => {
                const quoteItem = quoteById.get(item.id);
                const lineTotal = quoteItem ? quoteItem.line_total : 0;
                total += lineTotal;
                msg += `- ${item.name} (x${item.qty}) - $${lineTotal.toFixed(2)}%0A`;
                
                // Agregar variants si es apparel
                if (item.variants) {
                    if (item.variants.size) msg += `  Size: ${item.variants.size}%0A`;
                    if (item.variants.color) msg += `  Color: ${item.variants.color}%0A`;
                    hasApparel = true;
                }
                
                // Agregar brief si es service
                if (item.brief) {
                    msg += `  Brief:%0A`;
                    if (item.brief.estilo) msg += `    Style: ${item.brief.estilo}%0A`;
                    if (item.brief.colores) msg += `    Colors: ${item.brief.colores}%0A`;
                    if (item.brief.texto) msg += `    Text: ${item.brief.texto}%0A`;
                    if (item.brief.referencias) msg += `    References: ${item.brief.referencias}%0A`;
                    if (item.brief.detalles) msg += `    Details: ${item.brief.detalles}%0A`;
                    hasService = true;
                }
                
                if (item.type === 'apparel') hasApparel = true;
                if (item.type === 'service') hasService = true;
            });

            msg += `%0A*Total:* $${total.toFixed(2)}%0A`;
            if (payment) msg += `*Payment method:* ${payment}%0A`;
            
            // Actualizar delivery type si hay apparel
            if (hasApparel) {
                msg += `*Delivery type:* Coordinated delivery (Apparel)%0A`;
            } else if (deliveryType) {
                msg += `*Delivery type:* ${deliveryType}%0A`;
            }
            
            if (notes) {
                const notesLabel = (window.I18N && window.I18N['order.whatsapp.notes_label']) || 'Additional notes:';
                msg += `%0A*${notesLabel}* ${notes}%0A`;
            }

            if (hasApparel || hasService) {
                // Nota importante - podría venir de window.I18N si se necesita
                msg += '%0A*Important note:* We will contact you via WhatsApp/contact channels to coordinate delivery and/or design details.%0A';
            }
            
            msg += '%0ASend the payment receipt here when you have it ready.';

            // Reemplaza este número con tu número de WhatsApp real
            const phone = '584246661334';
            const url = `https://wa.me/${phone}?text=${msg}`;

            window.open(url, '_blank');
        });
    }

    // Payment flow toggle: paypal | bank_transfer | whatsapp
    const paymentMethodSelect = document.getElementById('payment-method-select');
    const manualOnly = document.querySelectorAll('.manual-only');
    const whatsappHelper = document.getElementById('whatsapp-other-helper');
    const paypalSection = document.getElementById('paypal-section');
    const paypalContainer = document.getElementById('paypal-button-container');
    const paymentMethodField = document.querySelector('select[name="payment_method"]');
    window.__paypalRendered = false;
    window.__paypalScriptInjected = false;

    function loadPayPalSDK(cb) {
        if (window.paypal) {
            if (cb) cb();
            return;
        }
        if (window.__paypalScriptInjected) {
            const check = setInterval(function() {
                if (window.paypal) {
                    clearInterval(check);
                    if (cb) cb();
                }
            }, 50);
            return;
        }
        window.__paypalScriptInjected = true;
        const script = document.createElement('script');
        script.src = 'https://www.paypal.com/sdk/js?client-id=<?php echo urlencode(PAYPAL_CLIENT_ID); ?>&currency=USD&components=buttons';
        script.onload = script.onreadystatechange = function() {
            if (script.readyState && script.readyState !== 'loaded' && script.readyState !== 'complete') return;
            if (cb) cb();
        };
        document.head.appendChild(script);
    }

    function updatePaymentFlow() {
        const val = paymentMethodSelect ? paymentMethodSelect.value : '';
        const isPayPal = val === 'paypal';
        const isWhatsApp = val === 'whatsapp';
        const isManual = val === 'bank_transfer' || isWhatsApp;

        manualOnly.forEach(el => {
            el.style.display = isManual ? '' : 'none';
        });
        if (whatsappHelper) whatsappHelper.style.display = isWhatsApp ? 'block' : 'none';
        if (paypalSection) paypalSection.style.display = isPayPal ? 'block' : 'none';
        if (paymentMethodField) paymentMethodField.required = isManual;

        const nameInput = document.getElementById('order-name');
        const whatsappInput = document.getElementById('order-whatsapp');
        const hints = document.querySelectorAll('.paypal-optional-hint');
        if (nameInput) nameInput.required = isManual;
        if (whatsappInput) whatsappInput.required = isManual;
        hints.forEach(function(h) { h.style.display = isPayPal ? 'block' : 'none'; });

        if (isPayPal && !window.__paypalRendered && paypalContainer) {
            loadPayPalSDK(function() {
                if (paymentMethodSelect.value === 'paypal') renderPayPalButtons();
            });
        }
    }

    function showPayPalLoading() {
        const loading = document.getElementById('paypal-loading');
        const err = document.getElementById('paypal-error');
        if (loading) loading.style.display = 'block';
        if (err) { err.style.display = 'none'; err.textContent = ''; }
    }
    function hidePayPalLoading() {
        const loading = document.getElementById('paypal-loading');
        if (loading) loading.style.display = 'none';
    }
    function showPayPalError(msg) {
        const err = document.getElementById('paypal-error');
        if (err) { err.textContent = msg; err.style.display = 'block'; }
    }

    function renderPayPalButtons() {
        if (window.__paypalRendered || !window.paypal || !paypalContainer) return;
        window.__paypalRendered = true;
        paypal.Buttons({
            createOrder: async function () {
                showPayPalLoading();
                try {
                    const items = loadOrderItems();
                    const deliveryType = document.getElementById('delivery-type-select')?.value || '';
                    const payloadItems = items.map(item => ({
                        id: item.id,
                        qty: item.qty,
                        variants: item.variants || null
                    }));
                    const response = await fetch('/api/paypal/create_order.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            items: payloadItems,
                            deliveryType
                        })
                    });
                    const data = await response.json();
                    hidePayPalLoading();
                    if (!response.ok || !data.id) {
                        showPayPalError('<?php echo addslashes(t('order.paypal.error')); ?>');
                        throw new Error('PayPal order creation failed');
                    }
                    return data.id;
                } catch (e) {
                    hidePayPalLoading();
                    if (!document.getElementById('paypal-error').textContent) {
                        showPayPalError('<?php echo addslashes(t('order.paypal.error')); ?>');
                    }
                    throw e;
                }
            },
            onApprove: async function (data) {
                showPayPalLoading();
                try {
                    const items = loadOrderItems();
                    const deliveryType = document.getElementById('delivery-type-select')?.value || '';
                    const form = document.getElementById('order-form');
                    const formData = new FormData(form);
                    const customer = {
                        name: formData.get('name') || '',
                        whatsapp: formData.get('whatsapp') || '',
                        notes: formData.get('notes') || ''
                    };
                    const payloadItems = items.map(item => ({
                        id: item.id,
                        qty: item.qty,
                        variants: item.variants || null
                    }));
                    const response = await fetch('/api/paypal/capture_order.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            orderID: data.orderID,
                            items: payloadItems,
                            deliveryType,
                            customer
                        })
                    });
                    const result = await response.json();
                    hidePayPalLoading();
                    if (response.ok && result.redirect) {
                        localStorage.removeItem(ORDER_KEY);
                        if (window.KND_ORDER && typeof window.KND_ORDER.updateOrderBadge === 'function') {
                            window.KND_ORDER.updateOrderBadge();
                        }
                        window.location.href = result.redirect;
                        return;
                    }
                    showPayPalError('<?php echo addslashes(t('order.paypal.error')); ?>');
                } catch (e) {
                    hidePayPalLoading();
                    showPayPalError('<?php echo addslashes(t('order.paypal.error')); ?>');
                }
            }
        }).render('#paypal-button-container');
    }

    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', updatePaymentFlow);
        updatePaymentFlow();
    }
});
</script>

<script src="/assets/js/navigation-extend.js"></script>

<?php 
echo generateFooter();
echo generateScripts();
?>

