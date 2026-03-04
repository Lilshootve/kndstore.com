(function () {
    'use strict';

    var CSRF     = window.DROP_CSRF || '';
    var ENDS_AT  = window.DROP_ENDS_AT;
    var ENTRY    = window.DROP_ENTRY || 420;
    var SCAN_MS  = 2200;

    var balanceEl  = document.getElementById('drop-balance');
    var resultEl   = document.getElementById('drop-result');
    var historyEl  = document.getElementById('drop-history');
    var countdownEl = document.getElementById('drop-countdown');
    var capsules   = document.querySelectorAll('.drop-capsule');

    var _rawBalance = parseInt((balanceEl ? balanceEl.textContent : '0').replace(/\D/g, ''), 10) || 0;
    var playing = false;

    var rarityStyles = {
        common:    {bg:'rgba(160,174,192,.15)', border:'rgba(160,174,192,.4)', text:'#a0aec0'},
        rare:      {bg:'rgba(66,153,225,.15)',   border:'rgba(66,153,225,.4)',   text:'#4299e1'},
        epic:      {bg:'rgba(159,122,234,.15)',  border:'rgba(159,122,234,.4)',  text:'#9f7aea'},
        legendary: {bg:'rgba(236,201,75,.15)',   border:'rgba(236,201,75,.4)',   text:'#ecc94b'}
    };

    function updateBalance(val) {
        _rawBalance = parseInt(val, 10) || 0;
        if (balanceEl) balanceEl.textContent = _rawBalance.toLocaleString();
        var b = document.querySelector('.sc-nav-badge');
        if (b) {
            if (_rawBalance > 0) {
                b.innerHTML = '<i class="fas fa-coins"></i> ' + _rawBalance.toLocaleString();
                b.style.display = '';
            } else {
                b.style.display = 'none';
            }
        }
    }

    function setCapsules(enabled) {
        capsules.forEach(function (c) {
            if (enabled) c.classList.remove('disabled');
            else c.classList.add('disabled');
        });
    }

    function resetCapsule(el) {
        el.classList.remove('active', 'scanning', 'drop-rarity-common', 'drop-rarity-rare', 'drop-rarity-epic', 'drop-rarity-legendary');
        el.querySelector('.drop-capsule-inner').innerHTML = '<i class="fas fa-cube"></i>';
    }

    function showResult(data) {
        var rc = rarityStyles[data.rarity] || rarityStyles.common;
        var isWin = data.reward_kp > 0;
        var cls = isWin ? 'alert-success' : 'alert-secondary';
        var icon = isWin ? 'fa-gem' : 'fa-box-open';
        var rewardText = isWin ? '+' + data.reward_kp.toLocaleString() + ' KP' : 'No drop';
        var html = '<div class="alert ' + cls + ' text-center mb-0">'
            + '<span class="badge me-2" style="background:' + rc.bg + '; color:' + rc.text + '; border:1px solid ' + rc.border + '; font-size:.85rem;">' + data.rarity.toUpperCase() + '</span>'
            + '<i class="fas ' + icon + ' me-1"></i>'
            + '<strong>' + rewardText + '</strong>'
            + ' <span class="text-muted small">(+' + data.xp_awarded + ' XP)</span>'
            + '</div>';
        resultEl.innerHTML = html;
        resultEl.style.display = 'block';
    }

    function addHistoryRow(d) {
        var rc = rarityStyles[d.rarity] || rarityStyles.common;
        var tr = document.createElement('tr');
        var rewardHtml = d.reward_kp > 0
            ? '+' + d.reward_kp.toLocaleString() + ' KP'
            : '<span class="text-white-50">No drop</span>';
        tr.innerHTML = '<td><span class="badge" style="background:' + rc.bg + '; color:' + rc.text + '; border:1px solid ' + rc.border + ';">' + d.rarity.charAt(0).toUpperCase() + d.rarity.slice(1) + '</span></td>'
            + '<td>' + rewardHtml + '</td>'
            + '<td>+' + d.xp_awarded + ' XP</td>'
            + '<td class="text-white-50">Just now</td>';

        var noData = historyEl.querySelector('td[colspan]');
        if (noData) noData.parentElement.remove();

        historyEl.insertBefore(tr, historyEl.firstChild);
        var rows = historyEl.querySelectorAll('tr');
        if (rows.length > 10) historyEl.removeChild(rows[rows.length - 1]);
    }

    function playDrop(capsuleEl) {
        if (playing) return;

        if (_rawBalance < ENTRY) {
            resultEl.innerHTML = '<div class="alert alert-danger text-center mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Not enough KP. Need ' + ENTRY.toLocaleString() + '.</div>';
            resultEl.style.display = 'block';
            return;
        }

        playing = true;
        setCapsules(false);
        resultEl.style.display = 'none';

        capsuleEl.classList.add('active', 'scanning');
        capsuleEl.querySelector('.drop-capsule-inner').innerHTML = '<i class="fas fa-search" style="animation:fa-spin 1s linear infinite;"></i>';

        var fd = new FormData();
        fd.append('csrf_token', CSRF);

        var startTime = Date.now();

        fetch('/api/drop/play.php', {method: 'POST', body: fd, credentials: 'same-origin'})
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var elapsed = Date.now() - startTime;
                var remaining = Math.max(0, SCAN_MS - elapsed);

                setTimeout(function () {
                    capsuleEl.classList.remove('scanning');

                    if (d.ok) {
                        var dd = d.data;
                        if (dd.xp_delta > 0 && typeof showXpGain === 'function') {
                            setTimeout(function () { showXpGain(dd.xp_delta); }, remaining + 100);
                        }
                        if (dd.level_up && dd.old_level != null && dd.new_level != null) {
                            var oldLvl = Number(dd.old_level);
                            var newLvl = Number(dd.new_level);
                            if (typeof updateNavLevelBadge === 'function') {
                                setTimeout(function () { updateNavLevelBadge(newLvl); }, remaining + 400);
                            }
                            if (typeof showLevelUp === 'function') {
                                setTimeout(function () { showLevelUp(oldLvl, newLvl); }, 1200);
                            }
                            if (typeof kndToast === 'function') {
                                setTimeout(function () { kndToast('success', 'Level Up: ' + oldLvl + ' → ' + newLvl); }, 1300);
                            }
                        } else if (dd.level && typeof updateNavLevelBadge === 'function') {
                            setTimeout(function () { updateNavLevelBadge(dd.level); }, remaining + 300);
                        }
                        var rarity = dd.rarity;
                        capsuleEl.classList.add('drop-rarity-' + rarity);
                        var rc = rarityStyles[rarity] || rarityStyles.common;
                        var rewardLabel = dd.reward_kp > 0 ? '+' + dd.reward_kp : '0';
                        capsuleEl.querySelector('.drop-capsule-inner').innerHTML =
                            '<div style="font-size:.55rem; text-transform:uppercase; color:' + rc.text + '; font-weight:700;">' + rarity + '</div>'
                            + '<div style="font-size:.85rem; font-weight:900; color:#fff;">' + rewardLabel + '</div>';

                        if (rarity === 'legendary') {
                            if (typeof kndConfetti === 'function') kndConfetti();
                            if (typeof kndToast === 'function') kndToast('success', 'LEGENDARY DROP!');
                        }

                        showResult(dd);
                        updateBalance(dd.balance);
                        addHistoryRow(dd);

                        setTimeout(function () {
                            resetCapsule(capsuleEl);
                            playing = false;
                            setCapsules(true);
                        }, 2000);
                    } else {
                        resetCapsule(capsuleEl);
                        var errMsg = d.error && d.error.message || 'Error';
                        resultEl.innerHTML = '<div class="alert alert-danger text-center mb-0"><i class="fas fa-exclamation-triangle me-2"></i>' + errMsg + '</div>';
                        resultEl.style.display = 'block';
                        if (typeof kndToast === 'function') kndToast('error', errMsg);
                        playing = false;
                        setCapsules(true);
                    }
                }, remaining);
            })
            .catch(function () {
                resetCapsule(capsuleEl);
                resultEl.innerHTML = '<div class="alert alert-danger text-center mb-0">Network error. Try again.</div>';
                resultEl.style.display = 'block';
                if (typeof kndToast === 'function') kndToast('error', 'Network error. Try again.');
                playing = false;
                setCapsules(true);
            });
    }

    capsules.forEach(function (c) {
        c.addEventListener('click', function () { playDrop(c); });
    });

    // Countdown
    if (countdownEl && ENDS_AT) {
        var endDate = new Date(ENDS_AT + 'Z');
        function tick() {
            var diff = Math.max(0, Math.floor((endDate - Date.now()) / 1000));
            if (diff <= 0) {
                countdownEl.textContent = 'Season ended';
                return;
            }
            var d = Math.floor(diff / 86400);
            var h = Math.floor((diff % 86400) / 3600);
            var m = Math.floor((diff % 3600) / 60);
            var s = diff % 60;
            countdownEl.textContent = d + 'd ' + h + 'h ' + m + 'm ' + s + 's';
        }
        tick();
        setInterval(tick, 1000);
    }
})();
