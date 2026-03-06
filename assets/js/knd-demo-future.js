/**
 * KND Demo Future - Microinteracciones mínimas
 * Chips, toggles, header particles y estados de demo
 */
(function () {
  'use strict';

  // Header particles (sutiles, coherentes con footer)
  if (document.querySelector('.knd-demo-shell')) {
    var nav = document.querySelector('.navbar');
    if (nav && !document.getElementById('particles-header-demo')) {
      var particlesDiv = document.createElement('div');
      particlesDiv.id = 'particles-header-demo';
      particlesDiv.className = 'knd-demo-header-particles';
      nav.style.position = 'relative';
      nav.insertBefore(particlesDiv, nav.firstChild);
    }
    function initHeaderParticles() {
      if (typeof particlesJS === 'undefined') {
        setTimeout(initHeaderParticles, 100);
        return;
      }
      var el = document.getElementById('particles-header-demo');
      if (!el) return;
      particlesJS('particles-header-demo', {
        particles: {
          number: { value: 18, density: { enable: true, value_area: 600 } },
          color: { value: ['#35C2FF', '#8B5CFF'] },
          opacity: { value: 0.12, random: true },
          size: { value: 2, random: true },
          line_linked: {
            enable: true,
            distance: 120,
            color: 'rgba(103, 213, 255, 0.1)',
            opacity: 0.08,
            width: 0.8
          },
          move: { enable: true, speed: 2, direction: 'none', out_mode: 'out' }
        },
        interactivity: { detect_on: 'canvas', events: { onhover: { enable: false }, resize: true } },
        retina_detect: true
      });
    }
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initHeaderParticles);
    } else {
      setTimeout(initHeaderParticles, 50);
    }
  }

  // Chips: toggle is-active en click
  document.querySelectorAll('.knd-chip').forEach(function (chip) {
    chip.addEventListener('click', function () {
      var group = chip.closest('.d-flex');
      if (group) {
        group.querySelectorAll('.knd-chip').forEach(function (c) { c.classList.remove('is-active'); });
      }
      chip.classList.add('is-active');
    });
  });

  // Generate button: feedback visual (demo)
  var genBtn = document.getElementById('knd-gen-btn');
  if (genBtn) {
    genBtn.addEventListener('click', function () {
      genBtn.disabled = true;
      genBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating…';
      setTimeout(function () {
        genBtn.disabled = false;
        genBtn.innerHTML = '<i class="fas fa-bolt me-2"></i>Generate';
      }, 2500);
    });
  }
})();
