<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determinar si el usuario está logueado y la sesión es válida
$usuario_logueado = false;
if (!empty($_SESSION['user_id']) && !empty($_SESSION['session_token'])) {
    require_once __DIR__ . '/config.php';
    $stmt = $pdo->prepare('SELECT session_token, full_name FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();
    if ($row && $row['session_token'] === $_SESSION['session_token']) {
        $usuario_logueado = true;
        $nombre_usuario = $row['full_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KND Store | Premium Gaming Services</title>
    
    <!-- Google Fonts: Orbitron & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tu CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">

    <style>
        :root {
          --knd-neon-blue: #00bfff;
          --knd-electric-purple: #8a2be2;
          --knd-gunmetal-gray: #2c2c2c;
          --knd-black: #000000;
          --knd-white: #ffffff;
          --knd-btn-hover-blue: #00a0d6;
          --knd-price: var(--knd-electric-purple);
        }
        body {
          background-color: var(--knd-black);
          color: var(--knd-white);
          font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, .heading {
          font-family: 'Orbitron', sans-serif;
        }
        /* Botón principal con degradado azul–morado */
        .btn-gradient {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          padding: 12px 24px;
          font-family: 'Orbitron', sans-serif;
          font-size: 1rem;
          font-weight: 600;
          color: var(--knd-white);
          background: linear-gradient(90deg, var(--knd-neon-blue), var(--knd-electric-purple));
          border: none;
          border-radius: 12px;
          cursor: pointer;
          text-transform: uppercase;
          transition: transform 0.2s ease, box-shadow 0.3s ease;
          box-shadow: 0 0 10px rgba(138, 43, 226, 0.4);
        }
        .btn-gradient:hover {
          transform: translateY(-2px);
          box-shadow: 0 0 14px rgba(138, 43, 226, 0.6);
          background: linear-gradient(90deg, var(--knd-btn-hover-blue), var(--knd-electric-purple));
        }
        /* Tarjetas de producto */
        .card {
          background-color: var(--knd-gunmetal-gray);
          padding: 1rem;
          border-radius: 12px;
          color: var(--knd-white);
        }
        .card-title {
          font-family: 'Orbitron', sans-serif;
          font-size: 1.2rem;
        }
        .card-price {
          color: var(--knd-price);
          font-weight: 700;
        }
        /* Hero principal */
        .hero {
          padding: 4rem 0 3rem 0;
          text-align: center;
          background: linear-gradient(120deg, rgba(0,191,255,0.08) 0%, rgba(138,43,226,0.12) 100%);
          border-radius: 0 0 32px 32px;
          margin-bottom: 2rem;
        }
        .hero .heading {
          font-size: 2.8rem;
          margin-bottom: 1.2rem;
          letter-spacing: 1px;
        }
        .hero p {
          font-size: 1.2rem;
          margin-bottom: 2rem;
          opacity: 0.9;
        }
        /* Badge del carrito (ajuste para nuevo diseño) */
        .btn-outline-neon.position-relative {
            position: relative !important;
            overflow: visible !important;
        }
        #cart-count.cart-badge {
            color: #fff !important;
            background: var(--knd-electric-purple) !important;
            font-size: 0.85rem !important;
            min-width: 18px;
            min-height: 18px;
            padding: 0 4px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            z-index: 99999 !important;
            opacity: 1 !important;
            position: absolute !important;
            right: -4px !important;
            top: -6px !important;
            box-shadow: 0 0 6px var(--knd-electric-purple);
            border: 2px solid #1a0630;
            pointer-events: none;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand" href="index.php">
      <img src="assets/images/knd-logo.png" alt="KND Logo" height="46">
    </a>
    <!-- Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Menú -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto" style="gap: 0.5rem;">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php?categoria=games">Games</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php?categoria=bikes">Bikes</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php?categoria=deals">Deals</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      </ul>
      <div class="d-flex align-items-center">
        <!-- Carrito -->
        <?php 
          $count = isset($_SESSION['carrito']) 
                   ? array_sum(array_map('count', (array)$_SESSION['carrito'])) 
                   : 0;
        ?>
        <a class="btn btn-outline-neon position-relative me-3" href="cart.php">
          <i class="fas fa-shopping-cart fa-lg"></i>
          <span id="cart-count"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-badge"
                style="<?= ($count == 0 ? 'display:none;' : '') ?>">
            <?= $count ?>
          </span>
        </a>
        <!-- Usuario / Login -->
        <?php if($usuario_logueado): ?>
          <a class="btn btn-neon d-flex align-items-center" href="perfil.php">
            <i class="fas fa-user-astronaut me-2"></i> <?= htmlspecialchars($nombre_usuario) ?>
          </a>
        <?php else: ?>
          <a class="btn btn-outline-neon me-2" href="login.php">
            <i class="fas fa-sign-in-alt me-2"></i> INGRESAR
          </a>
          <a class="btn btn-neon" href="register.php">
            <i class="fas fa-user-plus me-2"></i> REGISTRO
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<main>
<script>
// Mostrar dropdown en hover en desktop
if (window.matchMedia('(pointer: fine)').matches) {
  document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.querySelector('.dropdown');
    var menu = dropdown ? dropdown.querySelector('.dropdown-menu') : null;
    if(dropdown && menu) {
      dropdown.addEventListener('mouseenter', function() {
        menu.classList.add('show');
      });
      dropdown.addEventListener('mouseleave', function() {
        menu.classList.remove('show');
      });
    }
  });
}
// Fix scroll suave: ignorar anchors con href="#"
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    if (this.getAttribute('href') !== '#') {
      e.preventDefault();
      var destino = document.querySelector(this.getAttribute('href'));
      if(destino) {
        destino.scrollIntoView({ behavior: 'smooth' });
      }
    } else {
      e.preventDefault(); // Solo previene el salto
    }
  });
});
</script>