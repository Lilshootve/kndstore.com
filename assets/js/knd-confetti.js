/**
 * KND Arena - Subtle confetti (Legendary Drop only). No external libs.
 */
(function () {
    'use strict';

    var COLORS = ['#ffffff', '#00d4ff', '#a78bfa'];

    window.kndConfetti = function (opts) {
        opts = opts || {};
        var count = opts.count || 35;
        var duration = opts.duration || 1100;
        var w = window.innerWidth;
        var h = window.innerHeight;
        var cx = w / 2;
        var cy = h / 2;

        var canvas = document.createElement('canvas');
        canvas.width = w;
        canvas.height = h;
        canvas.style.cssText = 'position:fixed; inset:0; z-index:9996; pointer-events:none;';
        document.body.appendChild(canvas);

        var ctx = canvas.getContext('2d');
        var particles = [];
        for (var i = 0; i < count; i++) {
            particles.push({
                x: cx + (Math.random() - 0.5) * 80,
                y: cy + (Math.random() - 0.5) * 40,
                vx: (Math.random() - 0.5) * 4,
                vy: (Math.random() - 0.5) * 3 - 2,
                size: 3 + Math.random() * 4,
                color: COLORS[Math.floor(Math.random() * COLORS.length)],
                life: 1
            });
        }

        var start = Date.now();
        function tick() {
            var dt = 16;
            var elapsed = Date.now() - start;
            if (elapsed >= duration) {
                document.body.removeChild(canvas);
                return;
            }
            ctx.clearRect(0, 0, w, h);
            var progress = elapsed / duration;
            for (var i = 0; i < particles.length; i++) {
                var p = particles[i];
                p.vy += 0.15;
                p.x += p.vx;
                p.y += p.vy;
                p.life = 1 - progress;
                ctx.globalAlpha = p.life;
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.globalAlpha = 1;
            requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    };
})();
