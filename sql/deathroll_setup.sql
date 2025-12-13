-- KND Store - Death Roll Minijuego
-- Tablas y datos iniciales

-- Tabla de recompensas
CREATE TABLE IF NOT EXISTS `deathroll_rewards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rarity` enum('Common','Rare','Epic','Legendary') NOT NULL,
  `description` text,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `rarity` (`rarity`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de rolls (resultados)
CREATE TABLE IF NOT EXISTS `deathroll_rolls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `result_number` int(11) NOT NULL,
  `rarity` enum('Common','Rare','Epic','Legendary') NOT NULL,
  `reward_id` int(11) NOT NULL,
  `payload_sig` varchar(128) NOT NULL,
  `roll_steps` text,
  `seed_hash` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `reward_id` (`reward_id`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `deathroll_rolls_ibfk_1` FOREIGN KEY (`reward_id`) REFERENCES `deathroll_rewards` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar recompensas iniciales según SET DEFINITIVO v1

-- Common (siempre ganas algo, costo ~0)
INSERT INTO `deathroll_rewards` (`name`, `rarity`, `description`, `is_active`) VALUES
('Wallpaper Pack exclusivo Death Roll', 'Common', 'Pack de wallpapers exclusivos de Death Roll (no vendible aparte)', 1),
('Avatar + Banner Death Roll (edición sesión)', 'Common', 'Avatar y banner personalizados con temática Death Roll de la sesión actual', 1),
('Cupón $2 para próxima compra', 'Common', 'Cupón de descuento de $2 para tu próxima compra (expira en 7 días)', 1);

-- Rare (vale la pena, costo bajo)
INSERT INTO `deathroll_rewards` (`name`, `rarity`, `description`, `is_active`) VALUES
('Avatar personalizado (neon)', 'Rare', 'Avatar personalizado con estilo neon', 1),
('Avatar personalizado (anime)', 'Rare', 'Avatar personalizado con estilo anime', 1),
('Avatar personalizado (sci-fi)', 'Rare', 'Avatar personalizado con estilo sci-fi', 1),
('Avatar personalizado (minimal)', 'Rare', 'Avatar personalizado con estilo minimal', 1);

-- Epic (premium, costo medido) - Epic Bundle fijo
INSERT INTO `deathroll_rewards` (`name`, `rarity`, `description`, `is_active`) VALUES
('Epic Bundle: Avatar + Wallpaper personalizado (matching)', 'Epic', 'Avatar personalizado + Wallpaper personalizado que hacen juego (matching)', 1),
('Créditos KND $5', 'Epic', 'Créditos de $5 para usar en futuros servicios de KND Store', 1),
('Prioridad de entrega', 'Epic', 'Acceso prioritario para entrega de servicios (SLA mejorado)', 1);

-- Legendary (evento especial, NO por defecto)
INSERT INTO `deathroll_rewards` (`name`, `rarity`, `description`, `is_active`) VALUES
('Legendary Bundle: Epic Bundle + Bonus digital exclusivo', 'Legendary', 'Epic Bundle completo + bonus digital exclusivo (solo eventos/promociones)', 0);

