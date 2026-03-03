/**
 * KND Arena - XP gain floating pop (+N XP)
 * Anchors to Lv badge or navbar
 */
(function () {
    'use strict';
    window.showXpGain = function (xpDelta, anchorEl) {
        if (!xpDelta || xpDelta <= 0) return;
        var anchor = anchorEl || document.querySelector('.lvl-badge') || document.querySelector('.navbar') || document.body;
        var rect = anchor.getBoundingClientRect();
        var el = document.createElement('div');
        el.className = 'knd-xp-pop';
        el.textContent = '+' + xpDelta + ' XP';
        el.style.cssText = 'position:fixed;left:' + (rect.left + rect.width/2) + 'px;top:' + rect.top + 'px;transform:translate(-50%,-50%);z-index:9997;font-size:.9rem;font-weight:700;color:#00d4ff;text-shadow:0 0 10px rgba(0,212,255,.8);pointer-events:none;opacity:0;animation:knd-xp-pop-anim 1.2s ease-out forwards;';
        document.body.appendChild(el);
        setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 1200);
    };
})();
