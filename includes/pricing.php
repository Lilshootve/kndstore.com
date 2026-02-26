<?php
// Centralized pricing map keyed by product slug.

$PRICING_MAP = [
    // Apparel
    'hoodie-knd-style' => 44.99,
    'tshirt-knd-oversize' => 24.99,
    'hoodie-knd-black-edition' => 49.99,
    'hoodie-anime-style' => 59.99,
    'hoodie-dark-eyes-style' => 59.99,

    // Digital consulting
    'asesoria-pc-gamer' => 19.00,
    'compatibilidad-piezas' => 9.00,
    'simulacion-build' => 29.00,
    'analisis-pc' => 19.00,

    // Remote services
    'formateo-limpieza-pc' => 10.00,
    'instalacion-windows-drivers' => 49.00,
    'optimizacion-gamer' => 49.00,
    'instalacion-software' => 39.00,
    'pc-ready-pack' => 79.00,

    // Digital assets
    'tutorial-knd' => 12.00,
    'wallpaper-personalizado' => 15.00,
    'avatar-personalizado' => 19.00,
    'icon-pack-knd' => 15.00,
    'death-roll-crate' => 5.00,
    'activacion-juegos-giftcards' => 3.00,

    // Custom services
    'custom-tshirt-design' => 24.99,
    'custom-hoodie-design' => 34.99,
    'custom-full-outfit-concept' => 69.99,
];

if (!function_exists('getProductPriceValue')) {
    function getProductPriceValue(string $slug, array $product = []): float {
        global $PRICING_MAP;
        if (isset($PRICING_MAP[$slug])) {
            return (float) $PRICING_MAP[$slug];
        }
        if (isset($product['precio'])) {
            return (float) $product['precio'];
        }
        return 0.0;
    }
}
?>
