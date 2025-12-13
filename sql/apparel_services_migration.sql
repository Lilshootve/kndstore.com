-- KND Store - Migración para Apparel y Custom Services
-- Ejecutar después de verificar la estructura de la base de datos

-- 1. Agregar columna product_type a la tabla products (si existe)
-- Si la tabla no existe, esta migración puede ser ignorada
ALTER TABLE `products` 
ADD COLUMN IF NOT EXISTS `product_type` VARCHAR(20) DEFAULT 'digital' 
AFTER `categoria`;

-- Actualizar productos existentes a tipo 'digital' (si no tienen tipo)
UPDATE `products` 
SET `product_type` = 'digital' 
WHERE `product_type` IS NULL OR `product_type` = '';

-- 2. Crear tabla product_variants para manejar tallas y colores
CREATE TABLE IF NOT EXISTS `product_variants` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `size` VARCHAR(10) NOT NULL COMMENT 'S, M, L, XL',
    `color` VARCHAR(50) DEFAULT NULL COMMENT 'magenta, black, turquoise, tri-tone',
    `stock` INT DEFAULT 0,
    `price_override` DECIMAL(10,2) DEFAULT NULL,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_product_id` (`product_id`),
    INDEX `idx_status` (`status`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Crear tabla order_items_variants para almacenar variants en pedidos
CREATE TABLE IF NOT EXISTS `order_items_variants` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `order_item_id` INT NOT NULL,
    `variant_size` VARCHAR(10) DEFAULT NULL,
    `variant_color` VARCHAR(50) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_order_id` (`order_id`),
    INDEX `idx_order_item_id` (`order_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Crear tabla custom_design_briefs para almacenar briefs de servicios
CREATE TABLE IF NOT EXISTS `custom_design_briefs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT DEFAULT NULL,
    `order_item_id` INT DEFAULT NULL,
    `estilo` TEXT DEFAULT NULL,
    `colores` TEXT DEFAULT NULL,
    `texto` TEXT DEFAULT NULL,
    `referencias` TEXT DEFAULT NULL,
    `detalles` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_order_id` (`order_id`),
    INDEX `idx_order_item_id` (`order_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nota: Esta migración asume que ya existe una tabla `products` y posiblemente `orders`.
-- Si estas tablas no existen, el sistema funcionará con localStorage como fallback.

