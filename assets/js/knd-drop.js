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
    var fragmentsEl = document.getElementById('drop-fragments');
    var capsules   = document.querySelectorAll('.drop-capsule');

    var _rawBalance = parseInt((balanceEl ? balanceEl.textContent : '0').replace(/\D/g, ''), 10) || 0;
    var playing = false;

    var rarityStyles = {
        common:    {bg:'rgba(160,174,192,.15)', border:'rgba(160,174,192,.4)', text:'#a0aec0'},
        special:   {bg:'rgba(139,92,246,.15)',   border:'rgba(139,92,246,.4)',   text:'#8b5cf6'},
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

    function updateFragments(val) {
        if (fragmentsEl) {
            fragmentsEl.textContent = parseInt(val, 10).toLocaleString();
        }
    }

    function setCapsules(enabled) {
        capsules.forEach(function (c) {
            if (enabled) c.classList.remove('disabled');
            else c.classList.add('disabled');
        });
    }

    function resetCapsule(el) {
        el.classList.remove('active', 'scanning', 'drop-rarity-common', 'drop-rarity-special', 'drop-rarity-rare', 'drop-rarity-epic', 'drop-rarity-legendary');
        el.querySelector('.drop-capsule-inner').innerHTML = '<i class="fas fa-cube"></i>';
    }

    function showResult(data) {
        var rc = rarityStyles[data.rarity] || rarityStyles.common;
        var item = data.item || {};
        var isDupe = data.was_duplicate;
        var fragments = data.fragments_awarded || 0;
        var badges = data.badges_unlocked || [];
        
        var html = '<div class="alert alert-info text-center mb-0" style="background:' + rc.bg + '; border:2px solid ' + rc.border + ';">';
        
        // Rarity badge
        html += '<div class="mb-2"><span class="badge" style="background:' + rc.bg + '; color:' + rc.text + '; border:1px solid ' + rc.border + '; font-size:.95rem; padding:.5rem 1rem;">' + data.rarity.toUpperCase() + '</span></div>';
        
        // Item info
        html += '<div class="mb-2"><strong style="font-size:1.1rem; color:' + rc.text + ';">' + (item.name || 'Avatar Item') + '</strong></div>';
        html += '<div class="text-white-50 small mb-2">' + (item.slot ? item.slot.toUpperCase() : 'ITEM') + '</div>';
        
        // NEW or DUPLICATE
        if (isDupe) {
            html += '<div class="mb-2"><span class="badge bg-secondary"><i class="fas fa-copy me-1"></i>DUPLICATE</span></div>';
            if (fragments > 0) {
                html += '<div class="mb-2" style="color:#a78bfa;"><i class="fas fa-gem me-1"></i><strong>+' + fragments + ' Fragments</strong></div>';
            }
        } else {
            html += '<div class="mb-2"><span class="badge bg-success"><i class="fas fa-star me-1"></i>NEW!</span></div>';
        }
        
        // XP
        html += '<div class="text-white-50 small">+' + data.xp_awarded + ' XP</div>';
        
        // Badges
        if (badges.length > 0) {
            html += '<div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.1);">';
            html += '<div class="text-warning mb-2"><i class="fas fa-award me-1"></i><strong>Badge' + (badges.length > 1 ? 's' : '') + ' Unlocked!</strong></div>';
            badges.forEach(function(badge) {
                html += '<div class="badge bg-warning text-dark me-1 mb-1">' + badge + '</div>';
            });
            html += '</div>';
        }
        
        html += '</div>';
        
        resultEl.innerHTML = html;
        resultEl.style.display = 'block';
    }

    function addHistoryRow(d) {
        var rc = rarityStyles[d.rarity] || rarityStyles.common;
        var tr = document.createElement('tr');
        var item = d.item || {};
        var itemName = item.name || 'Avatar Item';
        var isDupe = d.was_duplicate;
        var fragments = d.fragments_awarded || 0;
        
        var rewardHtml = isDupe && fragments > 0
            ? '<span class="text-muted">' + itemName + '</span> <span style="color:#a78bfa;">+' + fragments + ' frags</span>'
            : '<span style="color:' + rc.text + ';">' + itemName + '</span>';
        
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
                        
                        // Handle XP gain
                        if (dd.xp_delta > 0 && typeof showXpGain === 'function') {
                            setTimeout(function () { showXpGain(dd.xp_delta); }, remaining + 100);
                        }
                        
                        // Handle level up
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
                        
                        // Handle badges
                        if (dd.badges_unlocked && dd.badges_unlocked.length > 0 && typeof kndToast === 'function') {
                            setTimeout(function () {
                                dd.badges_unlocked.forEach(function(badge) {
                                    kndToast('success', '🏆 Badge Unlocked: ' + badge);
                                });
                            }, 1500);
                        }
                        
                        var rarity = dd.rarity;
                        capsuleEl.classList.add('drop-rarity-' + rarity);
                        var rc = rarityStyles[rarity] || rarityStyles.common;
                        var itemName = (dd.item && dd.item.name) ? dd.item.name.substring(0, 12) : rarity;
                        capsuleEl.querySelector('.drop-capsule-inner').innerHTML =
                            '<div style="font-size:.55rem; text-transform:uppercase; color:' + rc.text + '; font-weight:700;">' + rarity + '</div>'
                            + '<div style="font-size:.7rem; font-weight:700; color:#fff;">' + itemName + '</div>';

                        if (rarity === 'legendary') {
                            if (typeof kndConfetti === 'function') kndConfetti();
                            if (typeof kndToast === 'function') kndToast('success', 'LEGENDARY DROP!');
                        }

                        showResult(dd);
                        updateBalance(dd.balance);
                        if (dd.fragments_total !== undefined) {
                            updateFragments(dd.fragments_total);
                        }
                        addHistoryRow(dd);

                        setTimeout(function () {
                            resetCapsule(capsuleEl);
                            playing = false;
                            setCapsules(true);
                        }, 3000);
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
    
    // Load initial fragment balance
    fetch('/api/avatar/fragments.php', {credentials: 'same-origin'})
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.ok && d.data && d.data.fragments !== undefined) {
                updateFragments(d.data.fragments);
            }
        })
        .catch(function() {});
})();
