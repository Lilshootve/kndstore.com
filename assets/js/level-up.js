/**
 * KND Arena - Level Up overlay animation
 * Custom sci-fi HUD style, no alert/SweetAlert
 */
(function () {
    'use strict';

    var DURATION_MS = 2500;

    window.showLevelUp = function (oldLevel, newLevel) {
        if (typeof oldLevel !== 'number' || typeof newLevel !== 'number' || newLevel <= oldLevel) return;

        var overlay = document.createElement('div');
        overlay.className = 'knd-level-up-overlay';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-label', 'Level Up!');

        var html = '<div class="knd-level-up-backdrop"></div>';
        html += '<div class="knd-level-up-content">';
        html += '  <div class="knd-level-up-title">LEVEL UP</div>';
        html += '  <div class="knd-level-up-levels">Level ' + oldLevel + ' <span class="knd-level-up-arrow">&rarr;</span> Level ' + newLevel + '</div>';
        html += '</div>';

        overlay.innerHTML = html;
        document.body.appendChild(overlay);

        requestAnimationFrame(function () {
            overlay.classList.add('visible');
        });

        function close() {
            overlay.classList.remove('visible');
            setTimeout(function () {
                if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
            }, 400);
        }

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay || e.target.classList.contains('knd-level-up-backdrop')) close();
        });

        setTimeout(close, DURATION_MS);
    };

    // Ensure styles exist
    var styleId = 'knd-level-up-styles';
    if (!document.getElementById(styleId)) {
        var style = document.createElement('style');
        style.id = styleId;
        style.textContent = [
            '.knd-level-up-overlay { position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity .3s ease; pointer-events:none; }',
            '.knd-level-up-overlay.visible { opacity:1; pointer-events:auto; }',
            '.knd-level-up-backdrop { position:absolute; inset:0; background:rgba(0,0,0,.7); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); cursor:pointer; }',
            '.knd-level-up-content { position:relative; padding:2rem 3rem; text-align:center; background:linear-gradient(135deg,rgba(0,212,255,.12),rgba(37,156,174,.08)); border:2px solid rgba(0,212,255,.5); border-radius:16px; box-shadow:0 0 40px rgba(0,212,255,.3), inset 0 0 30px rgba(0,212,255,.05); animation:knd-level-up-pop .5s ease forwards; max-width:90vw; }',
            '.knd-level-up-title { font-family:"Orbitron",sans-serif; font-size:2rem; font-weight:700; color:#00d4ff; text-transform:uppercase; letter-spacing:.2em; margin-bottom:.5rem; text-shadow:0 0 20px rgba(0,212,255,.8); animation:knd-level-up-glow 1.5s ease-in-out infinite; }',
            '.knd-level-up-levels { font-size:1.25rem; color:rgba(255,255,255,.95); font-family:"Orbitron",monospace; }',
            '.knd-level-up-arrow { color:#00d4ff; margin:0 .5rem; }',
            '@keyframes knd-level-up-pop { 0%{transform:scale(.7); opacity:0} 70%{transform:scale(1.05)} 100%{transform:scale(1); opacity:1} }',
            '@keyframes knd-level-up-glow { 0%,100%{text-shadow:0 0 20px rgba(0,212,255,.8)} 50%{text-shadow:0 0 30px rgba(0,212,255,1)} }',
            '@media (max-width:576px){ .knd-level-up-content{padding:1.5rem 1.5rem} .knd-level-up-title{font-size:1.4rem; letter-spacing:.1em} .knd-level-up-levels{font-size:1rem} }'
        ].join('\n');
        document.head.appendChild(style);
    }
})();
