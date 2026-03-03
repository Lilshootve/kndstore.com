/**
 * KND Arena - XP gain floating pop (+N XP)
 * Anchors to Lv badge or navbar
 */
(function () {
    'use strict';
    window.showXpGain = function (xpDelta, anchorEl) {
        if (!xpDelta || xpDelta <= 0) return;
        var el = document.createElement('div');
        el.className = 'knd-xp-pop';
        el.textContent = '+' + xpDelta + ' XP';
        el.setAttribute('aria-live', 'polite');
        document.body.appendChild(el);
        setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 1600);
    };
})();
