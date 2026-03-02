// KND Store - Death Roll 1v1 Client Logic
(function () {
    'use strict';

    // ── Utilities ───────────────────────────────────────────
    function post(url, data) {
        var fd = new FormData();
        for (var k in data) fd.append(k, data[k]);
        return fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); });
    }

    function get(url) {
        return fetch(url, { credentials: 'same-origin' })
            .then(function (r) { return r.json(); });
    }

    function timeAgo(dateStr) {
        var diff = Math.floor((Date.now() - new Date(dateStr + ' UTC').getTime()) / 1000);
        if (diff < 60) return diff + 's';
        if (diff < 3600) return Math.floor(diff / 60) + 'm';
        return Math.floor(diff / 3600) + 'h';
    }

    function escHtml(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    // ── LOBBY PAGE ──────────────────────────────────────────
    if (document.getElementById('rooms-tbody')) {
        initLobby();
    }

    function initLobby() {
        function refreshLobby() {
            get('/api/deathroll-1v1/lobby_state.php')
                .then(function (d) {
                    if (!d.ok) return;
                    renderRooms(d.data.public_rooms);
                    renderOnline(d.data.online_users);
                    var el = document.getElementById('lobby-active-games');
                    if (el) el.textContent = d.data.active_games;
                })
                .catch(function () {});
        }

        function renderRooms(rooms) {
            var tbody = document.getElementById('rooms-tbody');
            if (!rooms || rooms.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-white-50">No rooms available. Create one!</td></tr>';
                return;
            }
            var html = '';
            rooms.forEach(function (r) {
                html += '<tr>';
                html += '<td><code style="font-size:1.1em; letter-spacing:2px;">' + r.code + '</code></td>';
                html += '<td>' + escHtml(r.creator) + '</td>';
                html += '<td class="text-white-50">' + timeAgo(r.created_at) + ' ago</td>';
                html += '<td><button class="btn btn-sm btn-outline-neon btn-join-room" data-code="' + r.code + '"><i class="fas fa-sign-in-alt me-1"></i>Join</button></td>';
                html += '</tr>';
            });
            tbody.innerHTML = html;
        }

        function renderOnline(users) {
            var list = document.getElementById('online-list');
            var countEl = document.getElementById('online-count');
            if (countEl) countEl.textContent = users.length;
            if (!users || users.length === 0) {
                list.innerHTML = '<li class="text-white-50">No one online.</li>';
                return;
            }
            var html = '';
            users.forEach(function (u) {
                var isMe = u.username === MY_USERNAME;
                html += '<li class="d-flex align-items-center mb-2">';
                html += '<span class="me-2" style="width:8px;height:8px;border-radius:50%;background:#00ff88;display:inline-block;"></span>';
                html += '<span' + (isMe ? ' class="fw-bold"' : '') + '>' + escHtml(u.username) + (isMe ? ' (you)' : '') + '</span>';
                html += '</li>';
            });
            list.innerHTML = html;
        }

        function pingPresence() {
            post('/api/presence/ping.php', { csrf_token: CSRF }).catch(function () {});
        }

        document.getElementById('rooms-tbody').addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-join-room');
            if (!btn) return;
            joinRoom(btn.getAttribute('data-code'));
        });

        var createForm = document.getElementById('form-create-room');
        if (createForm) {
            createForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var vis = createForm.querySelector('[name=visibility]').value;
                post('/api/deathroll-1v1/create_room.php', {
                    csrf_token: CSRF,
                    visibility: vis
                }).then(function (d) {
                    if (d.ok) {
                        window.location.href = d.data.join_url;
                    } else {
                        var el = document.getElementById('create-result');
                        el.innerHTML = '<div class="alert alert-danger mb-0">' + d.error.message + '</div>';
                        el.style.display = 'block';
                    }
                }).catch(function () {});
            });
        }

        var joinForm = document.getElementById('form-join-code');
        if (joinForm) {
            joinForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var code = joinForm.querySelector('[name=code]').value.toUpperCase();
                joinRoom(code);
            });
        }

        function joinRoom(code) {
            post('/api/deathroll-1v1/join_room.php', {
                csrf_token: CSRF,
                code: code
            }).then(function (d) {
                if (d.ok) {
                    window.location.href = '/death-roll-game.php?code=' + code;
                } else {
                    var alertEl = document.getElementById('join-alert');
                    if (alertEl) {
                        alertEl.innerHTML = '<div class="alert alert-danger mb-0">' + d.error.message + '</div>';
                        alertEl.style.display = 'block';
                    } else {
                        alert(d.error.message);
                    }
                }
            }).catch(function () {});
        }

        refreshLobby();
        setInterval(refreshLobby, 5000);
        pingPresence();
        setInterval(pingPresence, 15000);
    }

    // ── GAME PAGE ───────────────────────────────────────────
    if (typeof GAME_CODE !== 'undefined' && document.getElementById('btn-roll')) {
        initGame();
    }

    function initGame() {
        var lastRollCount = 0;
        var isRolling = false;
        var gameFinished = false;
        var rematchPolling = false;
        var rematchOffered = false;
        var countdownInterval = null;
        var serverSecondsLeft = null;
        var localCountdownStart = null;

        var btnRoll = document.getElementById('btn-roll');
        var btnRematchReq = document.getElementById('btn-rematch-request');
        var btnRematchAccept = document.getElementById('btn-rematch-accept');
        var btnRematchDecline = document.getElementById('btn-rematch-decline');
        var btnCopy = document.getElementById('btn-copy-link');
        var timerBar = document.getElementById('turn-timer-bar');
        var timerValue = document.getElementById('turn-timer-value');
        var timerProgress = document.getElementById('turn-timer-progress');

        function startLocalCountdown(secondsLeft) {
            serverSecondsLeft = secondsLeft;
            localCountdownStart = Date.now();
            if (countdownInterval) clearInterval(countdownInterval);
            updateTimerDisplay();
            countdownInterval = setInterval(updateTimerDisplay, 200);
        }

        function stopCountdown() {
            if (countdownInterval) clearInterval(countdownInterval);
            countdownInterval = null;
            if (timerBar) timerBar.style.display = 'none';
        }

        function updateTimerDisplay() {
            if (serverSecondsLeft === null || !timerBar) return;
            var elapsed = (Date.now() - localCountdownStart) / 1000;
            var left = Math.max(0, serverSecondsLeft - elapsed);
            var secs = Math.ceil(left);

            timerBar.style.display = 'block';
            timerValue.textContent = secs;

            var pct = (left / 13) * 100;
            timerProgress.style.width = pct + '%';

            if (left <= 3) {
                timerValue.style.color = '#ff4444';
                timerProgress.style.background = '#ff4444';
            } else if (left <= 6) {
                timerValue.style.color = '#ffaa00';
                timerProgress.style.background = '#ffaa00';
            } else {
                timerValue.style.color = 'var(--knd-neon-blue)';
                timerProgress.style.background = 'var(--knd-neon-blue)';
            }
        }

        function pollState() {
            if (isRolling) return;
            get('/api/deathroll-1v1/state.php?code=' + GAME_CODE)
                .then(function (d) {
                    if (d.ok) renderState(d.data);
                })
                .catch(function () {});
        }

        function renderState(s) {
            document.getElementById('p1-name').textContent = s.players.p1 ? s.players.p1.username : '—';
            document.getElementById('p2-name').textContent = s.players.p2 ? s.players.p2.username : (TEXTS.waitingP2 || 'Waiting...');

            var p1Card = document.getElementById('player1-card');
            var p2Card = document.getElementById('player2-card');
            p1Card.style.borderColor = 'rgba(37,156,174,0.3)';
            p2Card.style.borderColor = 'rgba(174,37,101,0.3)';
            if (s.game.status === 'playing' && s.game.turn_user_id) {
                if (s.players.p1 && s.game.turn_user_id === s.players.p1.id) {
                    p1Card.style.borderColor = '#259cae';
                } else if (s.players.p2 && s.game.turn_user_id === s.players.p2.id) {
                    p2Card.style.borderColor = '#ae2565';
                }
            }

            var maxEl = document.getElementById('current-max-display');
            maxEl.textContent = s.game.current_max;
            if (s.game.current_max <= 10) {
                maxEl.style.color = '#ff4444';
            } else if (s.game.current_max <= 50) {
                maxEl.style.color = '#ffaa00';
            } else {
                maxEl.style.color = 'var(--knd-neon-blue)';
            }

            var statusEl = document.getElementById('game-status-text');
            if (s.game.status === 'waiting') {
                statusEl.textContent = TEXTS.waiting || 'Waiting for opponent';
            } else if (s.game.status === 'playing') {
                statusEl.textContent = TEXTS.playing || 'Game in progress';
            } else {
                if (s.game.finished_reason === 'timeout') {
                    statusEl.textContent = 'Timeout';
                } else if (s.game.finished_reason === 'abandoned') {
                    statusEl.textContent = TEXTS.abandoned || 'Game abandoned';
                } else {
                    statusEl.textContent = TEXTS.finished || 'Game over';
                }
            }

            if (s.game.status === 'playing' && s.game.turn_seconds_left !== null && s.game.turn_seconds_left !== undefined) {
                startLocalCountdown(s.game.turn_seconds_left);
            } else {
                stopCountdown();
            }

            var turnInfo = document.getElementById('turn-info');
            if (s.game.status === 'waiting') {
                turnInfo.textContent = TEXTS.waitingP2 || 'Waiting for opponent...';
                btnRoll.disabled = true;
            } else if (s.game.status === 'playing') {
                if (s.me.can_roll) {
                    turnInfo.innerHTML = '<span style="color: var(--knd-neon-blue);">' + (TEXTS.yourTurn || 'Your turn!') + '</span>';
                    btnRoll.disabled = false;
                } else {
                    var oppName = s.game.turn_user_id === (s.players.p1 ? s.players.p1.id : 0) ?
                        (s.players.p1 ? s.players.p1.username : '?') :
                        (s.players.p2 ? s.players.p2.username : '?');
                    turnInfo.textContent = oppName + "'s turn...";
                    btnRoll.disabled = true;
                }
            } else {
                turnInfo.textContent = '';
                btnRoll.disabled = true;
                btnRoll.style.display = 'none';
            }

            renderRolls(s.rolls);

            if (s.rolls.length > lastRollCount && lastRollCount > 0) {
                showLastRoll(s.rolls[s.rolls.length - 1]);
            }
            lastRollCount = s.rolls.length;

            if (s.game.status === 'finished' && !gameFinished) {
                gameFinished = true;
                showGameOver(s);
                startRematchPolling();
            }
        }

        function renderRolls(rolls) {
            var container = document.getElementById('rolls-list');
            if (!rolls || rolls.length === 0) {
                container.innerHTML = '<p class="text-white-50 small">No rolls yet.</p>';
                return;
            }
            var html = '';
            rolls.forEach(function (r, i) {
                var isFatal = parseInt(r.roll_value) === 1;
                var color = isFatal ? '#ff4444' : (parseInt(r.roll_value) <= 10 ? '#ffaa00' : '#ccc');
                html += '<div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary' + (isFatal ? ' bg-danger bg-opacity-10' : '') + '">';
                html += '<span class="small">';
                html += '<strong>' + escHtml(r.username) + '</strong> ';
                html += (TEXTS.rolled || 'rolled') + ' ';
                html += '<span style="color:' + color + '; font-weight:700;">' + r.roll_value + '</span>';
                html += ' <span class="text-white-50">/ ' + r.max_value + '</span>';
                html += '</span>';
                html += '<span class="text-white-50 small">#' + (i + 1) + '</span>';
                html += '</div>';
            });
            container.innerHTML = html;
            container.scrollTop = container.scrollHeight;
        }

        function showLastRoll(roll) {
            var el = document.getElementById('last-roll-display');
            var whoEl = document.getElementById('last-roll-who');
            var valEl = document.getElementById('last-roll-value');
            whoEl.textContent = roll.username + ' ' + (TEXTS.rolled || 'rolled');
            valEl.textContent = roll.roll_value;
            valEl.style.color = parseInt(roll.roll_value) === 1 ? '#ff4444' : 'var(--knd-neon-blue)';
            el.style.display = 'block';
            valEl.style.animation = 'none';
            void valEl.offsetHeight;
            valEl.style.animation = 'dr-pop 0.5s ease-out';
        }

        function showGameOver(s) {
            stopCountdown();
            var panel = document.getElementById('game-over-panel');
            var icon = document.getElementById('game-over-icon');
            var text = document.getElementById('game-over-text');
            var iWon = s.game.winner_user_id === MY_USER_ID;
            var isTimeout = s.game.finished_reason === 'timeout';

            if (iWon) {
                icon.innerHTML = '\uD83C\uDFC6';
                text.textContent = isTimeout ? (TEXTS.timeoutOpponent || 'Opponent timed out!') : (TEXTS.youWin || 'YOU WIN!');
                text.style.color = '#00ff88';
                panel.style.background = 'rgba(0,255,136,0.05)';
                panel.style.border = '2px solid rgba(0,255,136,0.3)';
            } else {
                icon.innerHTML = isTimeout ? '\u23F0' : '\uD83D\uDC80';
                text.textContent = isTimeout ? (TEXTS.timeoutYou || 'You lost by timeout!') : (TEXTS.youLose || 'YOU LOSE!');
                text.style.color = '#ff4444';
                panel.style.background = 'rgba(255,68,68,0.05)';
                panel.style.border = '2px solid rgba(255,68,68,0.3)';
            }
            panel.style.display = 'block';
        }

        // ── Rematch flow ─────────────────────────────────────
        function startRematchPolling() {
            if (rematchPolling) return;
            rematchPolling = true;
            pollRematchState();
            setInterval(pollRematchState, 1500);
        }

        function pollRematchState() {
            get('/api/deathroll-1v1/rematch_state.php?code=' + GAME_CODE)
                .then(function (d) {
                    if (!d.ok) return;
                    handleRematchState(d.data);
                })
                .catch(function () {});
        }

        function handleRematchState(rs) {
            if (!rs.has_offer) return;

            var offerPanel = document.getElementById('rematch-offer-panel');
            var statusEl = document.getElementById('rematch-status');

            if (rs.offer_status === 'accepted' && rs.new_code) {
                window.location.href = '/death-roll-game.php?code=' + rs.new_code;
                return;
            }

            if (rs.offer_status === 'pending') {
                if (rs.offered_to === MY_USER_ID) {
                    // I'm the recipient — show accept/decline
                    offerPanel.style.display = 'block';
                    offerPanel.style.background = 'rgba(37,156,174,0.05)';
                    offerPanel.style.border = '2px solid rgba(37,156,174,0.3)';
                    document.getElementById('rematch-offer-who').textContent =
                        escHtml(rs.offered_by_username) + ' ' + (TEXTS.rematchRequested || 'wants a rematch!');
                } else if (rs.offered_by === MY_USER_ID) {
                    // I offered — show waiting status
                    rematchOffered = true;
                    if (btnRematchReq) {
                        btnRematchReq.disabled = true;
                        btnRematchReq.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + (TEXTS.rematchWaiting || 'Waiting...');
                    }
                    if (statusEl) {
                        statusEl.innerHTML = '<span class="text-white-50"><i class="fas fa-hourglass-half me-2"></i>' + (TEXTS.rematchWaiting || 'Waiting for opponent to accept...') + '</span>';
                        statusEl.style.display = 'block';
                    }
                }
            }

            if (rs.offer_status === 'declined') {
                if (statusEl) {
                    statusEl.innerHTML = '<span class="text-warning"><i class="fas fa-times-circle me-2"></i>' + (TEXTS.rematchDeclined || 'Opponent declined.') + '</span>';
                    statusEl.style.display = 'block';
                }
                if (btnRematchReq) {
                    btnRematchReq.disabled = false;
                    btnRematchReq.innerHTML = '<i class="fas fa-redo me-2"></i>' + (TEXTS.youWin ? 'Rematch' : 'Rematch');
                }
                rematchOffered = false;
                var offerP = document.getElementById('rematch-offer-panel');
                if (offerP) offerP.style.display = 'none';
            }
        }

        // Request rematch
        if (btnRematchReq) {
            btnRematchReq.addEventListener('click', function () {
                if (rematchOffered) return;
                btnRematchReq.disabled = true;
                btnRematchReq.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
                post('/api/deathroll-1v1/rematch_offer.php', {
                    csrf_token: CSRF,
                    code: GAME_CODE
                }).then(function (d) {
                    if (d.ok) {
                        rematchOffered = true;
                        btnRematchReq.innerHTML = '<i class="fas fa-hourglass-half me-2"></i>' + (TEXTS.rematchWaiting || 'Waiting...');
                        var statusEl = document.getElementById('rematch-status');
                        if (statusEl) {
                            statusEl.innerHTML = '<span class="text-white-50"><i class="fas fa-hourglass-half me-2"></i>' + (TEXTS.rematchWaiting || 'Waiting for opponent to accept...') + '</span>';
                            statusEl.style.display = 'block';
                        }
                    } else {
                        alert(d.error.message);
                        btnRematchReq.disabled = false;
                        btnRematchReq.innerHTML = '<i class="fas fa-redo me-2"></i>Rematch';
                    }
                }).catch(function () {
                    btnRematchReq.disabled = false;
                    btnRematchReq.innerHTML = '<i class="fas fa-redo me-2"></i>Rematch';
                });
            });
        }

        // Accept rematch
        if (btnRematchAccept) {
            btnRematchAccept.addEventListener('click', function () {
                btnRematchAccept.disabled = true;
                btnRematchDecline.disabled = true;
                post('/api/deathroll-1v1/rematch_respond.php', {
                    csrf_token: CSRF,
                    code: GAME_CODE,
                    action: 'accept'
                }).then(function (d) {
                    if (d.ok && d.data.new_code) {
                        window.location.href = d.data.join_url;
                    } else {
                        alert(d.error ? d.error.message : 'Error');
                        btnRematchAccept.disabled = false;
                        btnRematchDecline.disabled = false;
                    }
                }).catch(function () {
                    btnRematchAccept.disabled = false;
                    btnRematchDecline.disabled = false;
                });
            });
        }

        // Decline rematch
        if (btnRematchDecline) {
            btnRematchDecline.addEventListener('click', function () {
                btnRematchDecline.disabled = true;
                btnRematchAccept.disabled = true;
                post('/api/deathroll-1v1/rematch_respond.php', {
                    csrf_token: CSRF,
                    code: GAME_CODE,
                    action: 'decline'
                }).then(function (d) {
                    var panel = document.getElementById('rematch-offer-panel');
                    if (panel) panel.style.display = 'none';
                }).catch(function () {
                    btnRematchDecline.disabled = false;
                    btnRematchAccept.disabled = false;
                });
            });
        }

        // Roll button
        btnRoll.addEventListener('click', function () {
            if (isRolling || btnRoll.disabled) return;
            isRolling = true;
            btnRoll.disabled = true;
            btnRoll.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Rolling...';

            post('/api/deathroll-1v1/roll.php', {
                csrf_token: CSRF,
                code: GAME_CODE
            }).then(function (d) {
                isRolling = false;
                btnRoll.innerHTML = '<i class="fas fa-dice me-2"></i>ROLL!';
                if (d.ok) {
                    renderState(d.data);
                } else {
                    alert(d.error.message);
                    pollState();
                }
            }).catch(function () {
                isRolling = false;
                btnRoll.innerHTML = '<i class="fas fa-dice me-2"></i>ROLL!';
                pollState();
            });
        });

        // Copy link
        if (btnCopy) {
            btnCopy.addEventListener('click', function () {
                var url = window.location.origin + '/death-roll-game.php?code=' + GAME_CODE;
                navigator.clipboard.writeText(url).then(function () {
                    btnCopy.innerHTML = '<i class="fas fa-check me-1"></i>' + (TEXTS.copied || 'Copied!');
                    setTimeout(function () {
                        btnCopy.innerHTML = '<i class="fas fa-link me-1"></i>Share';
                    }, 2000);
                });
            });
        }

        function pingPresence() {
            post('/api/presence/ping.php', { csrf_token: CSRF }).catch(function () {});
        }

        // Inject CSS animation
        var style = document.createElement('style');
        style.textContent = '@keyframes dr-pop { 0% { transform: scale(0.5); opacity: 0; } 50% { transform: scale(1.3); } 100% { transform: scale(1); opacity: 1; } }';
        document.head.appendChild(style);

        // Start
        pollState();
        setInterval(pollState, 1500);
        pingPresence();
        setInterval(pingPresence, 15000);
    }
})();
