(function () {
    'use strict';

    var CSRF   = window.AU_CSRF || '';
    var TICK_MS = 60;
    var ROLL_DURATION_MS = 2200;
    var PAYOUT_RATIO = 1.7;

    var diceWrap   = document.getElementById('au-dice-wrap');
    var diceNum    = document.getElementById('au-dice-num');
    var diceStatus = document.getElementById('au-dice-status');
    var btnUnder   = document.getElementById('btn-under');
    var btnAbove   = document.getElementById('btn-above');
    var balanceEl  = document.getElementById('au-balance');
    var historyEl  = document.getElementById('au-history-body');
    var resultBanner = document.getElementById('au-result-banner');
    var entrySelect  = document.getElementById('au-entry-select');
    var payoutPreview = document.getElementById('au-payout-preview');

    var rolling = false;
    var tickTimer = null;

    function getEntry() {
        return parseInt(entrySelect.value, 10) || 200;
    }

    function getPayout(entry) {
        return Math.floor(entry * PAYOUT_RATIO);
    }

    function updatePayoutPreview() {
        var entry = getEntry();
        payoutPreview.textContent = getPayout(entry).toLocaleString() + ' KP';
    }

    if (entrySelect) {
        entrySelect.addEventListener('change', updatePayoutPreview);
        updatePayoutPreview();
    }

    function setButtons(enabled) {
        btnUnder.disabled = !enabled;
        btnAbove.disabled = !enabled;
    }

    function startRoll() {
        rolling = true;
        setButtons(false);
        if (entrySelect) entrySelect.disabled = true;
        diceWrap.classList.add('dr-rolling');
        diceWrap.classList.remove('dr-result', 'dr-pop');
        diceNum.classList.remove('dr-critical');
        diceStatus.textContent = 'Rolling…';
        resultBanner.style.display = 'none';

        tickTimer = setInterval(function () {
            diceNum.textContent = Math.floor(Math.random() * 10) + 1;
        }, TICK_MS);
    }

    function stopRoll(value, win, choice, entry, payout, xp) {
        clearInterval(tickTimer);
        tickTimer = null;

        diceNum.textContent = value;
        diceWrap.classList.remove('dr-rolling');
        diceWrap.classList.add('dr-result');

        setTimeout(function () { diceWrap.classList.add('dr-pop'); }, 50);
        setTimeout(function () { diceWrap.classList.remove('dr-pop'); }, 500);

        if (!win) {
            diceNum.classList.add('dr-critical');
        }

        diceStatus.textContent = win ? 'WIN' : 'LOSE';

        var bannerClass = win ? 'alert-success' : 'alert-danger';
        var icon = win ? 'fa-trophy' : 'fa-times-circle';
        var label = choice.toUpperCase();
        var msg = win
            ? 'You chose <strong>' + label + '</strong> — rolled <strong>' + value + '</strong>. +' + payout.toLocaleString() + ' KP (+' + xp + ' XP)'
            : 'You chose <strong>' + label + '</strong> — rolled <strong>' + value + '</strong>. −' + entry.toLocaleString() + ' KP (+' + xp + ' XP)';
        resultBanner.innerHTML = '<div class="alert ' + bannerClass + ' mb-0"><i class="fas ' + icon + ' me-2"></i>' + msg + '</div>';
        resultBanner.style.display = 'block';

        rolling = false;
        setButtons(true);
        if (entrySelect) entrySelect.disabled = false;
    }

    var _rawBalance = parseInt(balanceEl.textContent.replace(/\D/g, ''), 10) || 0;

    function updateBalance(val) {
        _rawBalance = parseInt(val, 10) || 0;
        balanceEl.textContent = _rawBalance.toLocaleString();
        var navBadge = document.querySelector('.sc-nav-badge');
        if (navBadge) {
            if (_rawBalance > 0) {
                navBadge.innerHTML = '<i class="fas fa-coins"></i> ' + _rawBalance.toLocaleString();
                navBadge.style.display = '';
            } else {
                navBadge.style.display = 'none';
            }
        }
    }

    function addHistoryRow(r) {
        var rows = historyEl.querySelectorAll('tr');
        if (rows.length >= 10) {
            historyEl.removeChild(rows[rows.length - 1]);
        }
        var tr = document.createElement('tr');
        var winBadge = r.win
            ? '<span class="badge bg-success">WIN</span>'
            : '<span class="badge bg-danger">LOSE</span>';
        var delta = r.win ? '+' + r.payout.toLocaleString() : '−' + r.entry.toLocaleString();
        tr.innerHTML = '<td>' + r.choice.toUpperCase() + '</td>'
            + '<td style="font-family:Orbitron,monospace;font-weight:700;">' + r.rolled + '</td>'
            + '<td>' + winBadge + '</td>'
            + '<td>' + delta + ' KP</td>'
            + '<td>+' + r.xp + ' XP</td>';
        historyEl.insertBefore(tr, historyEl.firstChild);
    }

    function doRoll(choice) {
        if (rolling) return;

        var entry = getEntry();
        if (_rawBalance < entry) {
            resultBanner.innerHTML = '<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Not enough KP. You need ' + entry.toLocaleString() + ' KP.</div>';
            resultBanner.style.display = 'block';
            return;
        }

        startRoll();

        var fd = new FormData();
        fd.append('choice', choice);
        fd.append('entry_kp', entry);
        fd.append('csrf_token', CSRF);

        var rollStart = Date.now();

        fetch('/api/above-under/roll.php', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var elapsed = Date.now() - rollStart;
                var remaining = Math.max(0, ROLL_DURATION_MS - elapsed);

                if (d.ok) {
                    setTimeout(function () {
                        stopRoll(d.data.rolled, d.data.win, d.data.choice, d.data.entry, d.data.payout, d.data.xp_awarded);
                        updateBalance(d.data.points_balance);
                        addHistoryRow({
                            choice: d.data.choice,
                            rolled: d.data.rolled,
                            win: d.data.win,
                            payout: d.data.payout,
                            entry: d.data.entry,
                            xp: d.data.xp_awarded
                        });
                    }, remaining);
                } else {
                    clearInterval(tickTimer);
                    diceWrap.classList.remove('dr-rolling');
                    diceNum.textContent = '—';
                    diceStatus.textContent = 'Ready';
                    rolling = false;
                    setButtons(true);
                    if (entrySelect) entrySelect.disabled = false;
                    resultBanner.innerHTML = '<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>' + (d.error && d.error.message || 'Error') + '</div>';
                    resultBanner.style.display = 'block';
                }
            })
            .catch(function () {
                clearInterval(tickTimer);
                diceWrap.classList.remove('dr-rolling');
                diceNum.textContent = '—';
                diceStatus.textContent = 'Ready';
                rolling = false;
                setButtons(true);
                if (entrySelect) entrySelect.disabled = false;
                resultBanner.innerHTML = '<div class="alert alert-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Network error. Try again.</div>';
                resultBanner.style.display = 'block';
            });
    }

    btnUnder.addEventListener('click', function () { doRoll('under'); });
    btnAbove.addEventListener('click', function () { doRoll('above'); });
})();
