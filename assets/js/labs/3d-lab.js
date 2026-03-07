(function () {
    'use strict';

    var cfg = window.KND_3D_LAB || {};
    var ep = cfg.endpoints || {};
    var state = { mode: 'image', file: null, selectedRecent: null, pollTimer: null, currentJobId: null };

    var el = {
        form: document.getElementById('labs-3d-form'),
        modeRadios: ['l3d-mode-text', 'l3d-mode-image', 'l3d-mode-text-image', 'l3d-mode-recent'],
        modeInput: document.getElementById('l3d-mode'),
        promptWrap: document.getElementById('l3d-prompt-wrap'),
        negativeWrap: document.getElementById('l3d-negative-wrap'),
        uploadWrap: document.getElementById('l3d-upload-wrap'),
        recentWrap: document.getElementById('l3d-recent-wrap'),
        recentGrid: document.getElementById('l3d-recent-grid'),
        dropzone: document.getElementById('l3d-dropzone'),
        fileInput: document.getElementById('l3d-file'),
        dropzoneContent: document.getElementById('l3d-dropzone-content'),
        previewWrap: document.getElementById('l3d-preview-wrap'),
        preview: document.getElementById('l3d-preview'),
        removeImg: document.getElementById('l3d-remove-img'),
        sourceId: document.getElementById('l3d-source-id'),
        sourceType: document.getElementById('l3d-source-type'),
        submit: document.getElementById('l3d-submit'),
        placeholder: document.getElementById('l3d-placeholder'),
        viewerWrap: document.getElementById('l3d-viewer-wrap'),
        modelViewer: document.getElementById('l3d-model-viewer'),
        resultActions: document.getElementById('l3d-result-actions'),
        downloadBtn: document.getElementById('l3d-download'),
        statusPanel: document.getElementById('l3d-status-panel'),
        statusText: document.getElementById('l3d-status-text'),
        errorEl: document.getElementById('l3d-error'),
        balance: document.getElementById('l3d-balance'),
        historyPlaceholder: document.getElementById('l3d-history-placeholder'),
        historyList: document.getElementById('l3d-history-list'),
        recentCreationsGrid: document.getElementById('l3d-recent-creations-grid'),
    };

    function toast(msg, type) {
        if (window.kndToast) window.kndToast(msg, type || 'info');
        else if (type === 'error') alert(msg);
    }

    var emptyEl = document.getElementById('l3d-placeholder-empty');
    var loadingEl = document.getElementById('l3d-placeholder-loading');
    var placeholderStatusEl = document.getElementById('l3d-placeholder-status-text');

    function updateStepper(step) {
        var dots = document.querySelectorAll('#l3d-placeholder-loading .labs-stepper-dot');
        var steps = ['queued', 'picked', 'generating', 'done'];
        dots.forEach(function (d, i) {
            d.classList.remove('active', 'current');
            if (steps[i] === step) d.classList.add('current');
            else if (steps.indexOf(step) > i) d.classList.add('active');
        });
    }

    function setStatus(s, text) {
        if (el.statusPanel) el.statusPanel.style.display = s ? 'block' : 'none';
        if (el.statusText) el.statusText.textContent = text || (s ? 'Processing...' : '');
        if (el.placeholder) el.placeholder.style.display = 'block';
        if (s && el.viewerWrap) el.viewerWrap.style.display = 'none';
        if (emptyEl) emptyEl.classList.toggle('d-none', !!s);
        if (loadingEl) loadingEl.classList.toggle('d-none', !s);
        if (placeholderStatusEl && s) placeholderStatusEl.textContent = text || 'Processing...';
        if (s) {
            var step = (text || '').toLowerCase().indexOf('queued') >= 0 ? 'queued' : ((text || '').toLowerCase().indexOf('processing') >= 0 ? 'generating' : 'picked');
            updateStepper(step);
        }
    }

    function showError(msg) {
        if (el.errorEl) { el.errorEl.textContent = msg || 'Error'; el.errorEl.style.display = 'block'; }
    }

    function clearError() {
        if (el.errorEl) el.errorEl.style.display = 'none';
    }

    function toggleModeUI() {
        var m = state.mode;
        if (el.promptWrap) el.promptWrap.style.display = (m === 'image') ? 'none' : 'block';
        if (el.negativeWrap) el.negativeWrap.style.display = (m === 'image') ? 'none' : 'block';
        if (el.uploadWrap) el.uploadWrap.style.display = (m === 'image' || m === 'text_image') ? 'block' : 'none';
        if (el.recentWrap) el.recentWrap.style.display = (m === 'recent') ? 'block' : 'none';
        if (m === 'recent') loadRecentForPick();
    }

    function setFile(file) {
        if (!file) return;
        state.file = file;
        state.selectedRecent = null;
        if (el.preview && el.previewWrap && el.dropzoneContent) {
            el.preview.src = URL.createObjectURL(file);
            el.previewWrap.style.display = 'block';
            el.dropzoneContent.style.display = 'none';
        }
        if (el.sourceId) el.sourceId.value = '';
    }

    function resetFile() {
        state.file = null;
        if (el.fileInput) el.fileInput.value = '';
        if (el.preview) el.preview.src = '';
        if (el.previewWrap) el.previewWrap.style.display = 'none';
        if (el.dropzoneContent) el.dropzoneContent.style.display = 'block';
    }

    function selectRecent(id) {
        state.selectedRecent = { id: id };
        state.file = null;
        resetFile();
        if (el.sourceId) el.sourceId.value = String(id);
        if (el.sourceType) el.sourceType.value = '3d_lab';
    }

    function canSubmit() {
        if (state.mode === 'text') return false;
        if (state.mode === 'text_image') {
            var p = document.getElementById('l3d-prompt');
            if (p && !p.value.trim()) return false;
            return !!state.file;
        }
        if (state.mode === 'image') return !!state.file;
        if (state.mode === 'recent') return !!state.selectedRecent;
        return false;
    }

    function loadRecentForPick() {
        if (!el.recentGrid) return;
        el.recentGrid.innerHTML = '<p class="text-white-50 small mb-0"><i class="fas fa-spinner fa-spin me-1"></i>Loading...</p>';
        fetch((ep.history || '/api/labs/3d-lab/history.php') + '?limit=12', { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                var jobs = (res.data && res.data.jobs) ? res.data.jobs : [];
                jobs = jobs.filter(function (j) { return j.status === 'completed' && (j.preview_url || j.glb_url); });
                if (!jobs.length) {
                    el.recentGrid.innerHTML = '<p class="text-white-50 small mb-0">No completed creations yet.</p>';
                    return;
                }
                var html = jobs.map(function (j) {
                    var sel = state.selectedRecent && state.selectedRecent.id === j.id ? ' selected' : '';
                    return '<div class="col-4 col-md-3"><div class="labs-3d-recent-card p-1' + sel + '" data-id="' + j.id + '" style="cursor:pointer; aspect-ratio:1;">' +
                        (j.preview_url ? '<img src="' + j.preview_url + '" alt="" class="w-100 h-100" style="object-fit:cover;">' : '<div class="w-100 h-100 bg-dark d-flex align-items-center justify-content-center"><i class="fas fa-cube text-white-50"></i></div>') +
                        '</div><small class="text-white-50 d-block text-truncate">' + (j.title || '') + '</small></div>';
                }).join('');
                el.recentGrid.innerHTML = html;
                el.recentGrid.querySelectorAll('.labs-3d-recent-card').forEach(function (n) {
                    n.addEventListener('click', function () {
                        selectRecent(parseInt(n.getAttribute('data-id'), 10));
                        el.recentGrid.querySelectorAll('.labs-3d-recent-card').forEach(function (x) { x.classList.remove('selected'); });
                        n.classList.add('selected');
                    });
                });
            })
            .catch(function () {
                el.recentGrid.innerHTML = '<p class="text-white-50 small mb-0">Could not load.</p>';
            });
    }

    function loadHistory() {
        fetch((ep.history || '/api/labs/3d-lab/history.php') + '?limit=8', { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                var jobs = (res.data && res.data.jobs) ? res.data.jobs : [];
                if (el.historyPlaceholder) el.historyPlaceholder.style.display = 'none';
                if (el.historyList) {
                    el.historyList.style.display = jobs.length ? 'block' : 'none';
                    el.historyList.innerHTML = jobs.length ? jobs.slice(0, 5).map(function (j) {
                        return '<li class="mb-2"><a href="#" class="text-white-50 small l3d-open-job" data-id="' + j.public_id + '">' + (j.title || j.public_id) + '</a></li>';
                    }).join('') : '<li class="text-white-50 small">No creations yet</li>';
                }
                if (el.recentCreationsGrid) {
                    if (!jobs.length) {
                        el.recentCreationsGrid.innerHTML = '<p class="knd-muted small mb-0">No 3D creations yet.</p>';
                    } else {
                        el.recentCreationsGrid.innerHTML = jobs.map(function (j) {
                            var prev = j.preview_url ? '<img src="' + j.preview_url + '" alt="" class="w-100 h-100" style="object-fit:cover;">' : '<div class="w-100 h-100 d-flex align-items-center justify-content-center"><i class="fas fa-cube fa-2x text-white-50"></i></div>';
                            return '<div class="knd-showcase-card" data-job-id="' + j.public_id + '"><div class="knd-showcase-card__img" style="aspect-ratio:1;">' + prev + '</div><div class="knd-showcase-card__body p-2"><div class="small text-white-50">' + (j.title || '') + '</div><div class="d-flex gap-1 mt-1"><a href="#" class="btn btn-sm btn-outline-light l3d-view-job" data-id="' + j.public_id + '">View</a><a href="' + (j.glb_url || '#') + '" class="btn btn-sm btn-success" download>Download</a></div></div></div>';
                        }).join('');
                    }
                }
                document.querySelectorAll('.l3d-open-job').forEach(function (a) {
                    a.addEventListener('click', function (e) { e.preventDefault(); openJob(a.getAttribute('data-id')); });
                });
                document.querySelectorAll('.l3d-view-job').forEach(function (a) {
                    a.addEventListener('click', function (e) { e.preventDefault(); openJob(a.getAttribute('data-id')); });
                });
            })
            .catch(function () {
                if (el.historyPlaceholder) el.historyPlaceholder.innerHTML = '<p class="knd-muted small mb-0">Could not load.</p>';
            });
    }

    function openJob(id) {
        state.currentJobId = id;
        pollStatus();
    }

    function renderViewer(job) {
        if (!job.has_glb) return;
        var glbUrl = (ep.download || '/api/labs/3d-lab/download.php') + '?id=' + encodeURIComponent(job.public_id) + '&format=glb&inline=1';
        var dlUrl = (ep.download || '/api/labs/3d-lab/download.php') + '?id=' + encodeURIComponent(job.public_id) + '&format=glb';
        if (el.placeholder) el.placeholder.style.display = 'none';
        if (el.viewerWrap) el.viewerWrap.style.display = 'block';
        if (el.modelViewer) {
            el.modelViewer.setAttribute('src', glbUrl);
        }
        if (el.resultActions) { el.resultActions.style.display = 'block'; }
        if (el.downloadBtn) { el.downloadBtn.href = dlUrl; el.downloadBtn.classList.remove('disabled'); }
    }

    function pollStatus() {
        if (!state.currentJobId) return;
        fetch((ep.status || '/api/labs/3d-lab/status.php') + '?id=' + encodeURIComponent(state.currentJobId), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (!res.ok || !res.data) return;
                var job = res.data;
                setStatus(true, job.status === 'queued' ? 'Queued...' : (job.status === 'processing' ? 'Processing...' : job.status));

                if (job.status === 'failed') {
                    setStatus(false);
                    showError(job.error_message || 'Generation failed');
                    stopPolling();
                } else if (job.status === 'completed') {
                    clearError();
                    setStatus(false);
                    renderViewer(job);
                    stopPolling();
                    loadHistory();
                    toast('3D model ready', 'success');
                }
            })
            .catch(function () {});
    }

    function startPolling(id) {
        stopPolling();
        state.currentJobId = id;
        state.pollTimer = setInterval(pollStatus, 3500);
        pollStatus();
    }

    function stopPolling() {
        if (state.pollTimer) { clearInterval(state.pollTimer); state.pollTimer = null; }
    }

    function onSubmit() {
        if (!canSubmit()) {
            toast('Please fill required fields.', 'error');
            return;
        }
        clearError();
        var fd = new FormData();
        fd.append('mode', state.mode);
        fd.append('category', (document.getElementById('l3d-category') || {}).value || 'Stylized Asset');
        fd.append('style', (document.getElementById('l3d-style') || {}).value || 'Stylized');
        fd.append('quality', (document.getElementById('l3d-quality') || {}).value || 'Standard');
        fd.append('prompt', (document.getElementById('l3d-prompt') || {}).value || '');
        fd.append('negative_prompt', (document.getElementById('l3d-negative') || {}).value || '');
        if (state.mode === 'image' || state.mode === 'text_image') fd.append('image', state.file);
        if (state.mode === 'recent' && state.selectedRecent) {
            fd.append('source_recent_job_id', String(state.selectedRecent.id));
            fd.append('source_recent_type', '3d_lab');
        }

        if (el.submit) el.submit.disabled = true;
        setStatus(true, 'Queuing...');

        fetch(ep.create || '/api/labs/3d-lab/create.php', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (!res.ok || !res.data) throw new Error((res.error && res.error.message) || 'Could not create job');
                var job = res.data;
                state.currentJobId = job.public_id;
                resetFile();
                state.selectedRecent = null;
                if (el.sourceId) el.sourceId.value = '';
                if (el.balance && res.data.available_after != null) el.balance.textContent = Number(res.data.available_after).toLocaleString();
                startPolling(job.public_id);
                toast('Job queued', 'success');
            })
            .catch(function (err) {
                setStatus(false);
                showError(err.message || 'Submission failed');
                toast(err.message, 'error');
            })
            .finally(function () {
                if (el.submit) el.submit.disabled = false;
            });
    }

    function bindEvents() {
        if (!el.form) return;

        el.modeRadios.forEach(function (id, i) {
            var r = document.getElementById(id);
            if (!r) return;
            if (r.disabled) return;
            r.addEventListener('change', function () {
                state.mode = ['text', 'image', 'text_image', 'recent'][i];
                if (el.modeInput) el.modeInput.value = state.mode;
                toggleModeUI();
            });
        });

        if (el.dropzone) {
            el.dropzone.addEventListener('click', function () { if (el.fileInput) el.fileInput.click(); });
            el.dropzone.addEventListener('dragover', function (e) { e.preventDefault(); el.dropzone.classList.add('is-dragover'); });
            el.dropzone.addEventListener('dragleave', function () { el.dropzone.classList.remove('is-dragover'); });
            el.dropzone.addEventListener('drop', function (e) {
                e.preventDefault();
                el.dropzone.classList.remove('is-dragover');
                var f = e.dataTransfer && e.dataTransfer.files ? e.dataTransfer.files[0] : null;
                if (f && /^image\/(jpeg|jpg|png|webp)$/i.test(f.type)) setFile(f);
            });
        }
        if (el.fileInput) el.fileInput.addEventListener('change', function () {
            var f = el.fileInput.files && el.fileInput.files[0];
            if (f) setFile(f);
        });
        if (el.removeImg) el.removeImg.addEventListener('click', function (e) { e.stopPropagation(); resetFile(); });

        el.form.addEventListener('submit', function (e) { e.preventDefault(); onSubmit(); });
        if (el.submit) el.submit.addEventListener('click', function (e) { e.preventDefault(); onSubmit(); });

        var fsBtn = document.getElementById('l3d-fullscreen');
        if (fsBtn && el.modelViewer) fsBtn.addEventListener('click', function () {
            if (el.modelViewer.requestFullscreen) el.modelViewer.requestFullscreen();
        });
    }

    bindEvents();
    toggleModeUI();
    loadHistory();
})();
