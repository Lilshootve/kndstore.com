/**
 * KND Demo Future - Microinteracciones mínimas
 * Chips, toggles, collapse y estados de demo
 */
(function () {
  'use strict';

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
