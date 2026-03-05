/**
 * KND Labs - ComfyUI frontend (Orbitron vibe)
 * Shared logic for text2img, upscale, character-lab.
 */
(function() {
  'use strict';

  var POLL_INTERVAL = 2000;
  var POLL_MAX_COUNT = 150;
  var API_GENERATE = '/api/labs/generate.php';
  var API_STATUS = '/api/labs/status.php';
  var API_JOBS = '/api/labs/jobs.php';
  var API_JOB = '/api/labs/job.php';
  var API_IMAGE = '/api/labs/image.php';
  var API_PRICING = '/api/labs/pricing.php';
  var API_PREFERENCE = '/api/labs/preference.php';

  var KNDLabs = {
    config: {},
    pollTimer: null,
    currentJobId: null,
    lastJobSeed: null,
    lastJobPayload: null,
    pricing: null,

    init: function(cfg) {
      this.config = cfg || {};
      this.config.balanceEl = this.config.balanceEl
        ? document.querySelector(this.config.balanceEl)
        : null;
      var self = this;
      fetch(API_PRICING, { credentials: 'same-origin' })
        .then(function(r) { return r.json(); })
        .then(function(d) {
          if (d.ok && d.data) self.pricing = d.data;
          self.bindForm();
          self.bindPresets();
          self.bindPresetsNegative();
          self.bindRetry();
          self.bindRegenerate();
          self.bindVariations();
          self.bindPricingUpdates();
          self.bindPromptValidation();
          self.bindAdvancedToggle();
          self.bindViewDetails();
          self.bindRecentFilter();
          self.bindPrivateCheck();
          self.updateCostLabel();
          self.updateBalanceAfter();
          self.updateSubmitButton();
        })
        .catch(function() {
          self.bindForm();
          self.bindPresets();
          self.bindPresetsNegative();
          self.bindRetry();
          self.bindRegenerate();
          self.bindVariations();
          self.bindPromptValidation();
          self.bindAdvancedToggle();
          self.bindViewDetails();
          self.bindRecentFilter();
          self.bindPrivateCheck();
          self.updateCostLabel();
          self.updateBalanceAfter();
          self.updateSubmitButton();
        });
    },

    getCost: function() {
      var p = this.pricing;
      var key = this.config.pricingKey || 'text2img';
      if (!p) return key === 'text2img' ? 3 : (key === 'upscale' ? 5 : 15);
      if (key === 'text2img') {
        var q = document.getElementById(this.config.qualitySelectId);
        var qual = q ? q.value : 'standard';
        return (p.text2img && p.text2img[qual]) || 3;
      }
      if (key === 'upscale') {
        var s = document.getElementById(this.config.scaleSelectId);
        var scale = s ? s.value : '2';
        return (p.upscale && p.upscale[scale + 'x']) || (scale === '4' ? 8 : 5);
      }
      return (p.character && p.character.base) || 15;
    },

    updateCostLabel: function() {
      var el = document.getElementById(this.config.costLabelId);
      if (el) el.textContent = 'Cost: ' + this.getCost() + ' KP';
    },

    updateBalanceAfter: function() {
      var el = document.getElementById('labs-balance-after');
      if (!el) return;
      var bal = 0;
      var balEl = this.config.balanceEl ? (typeof this.config.balanceEl === 'string' ? document.querySelector(this.config.balanceEl) : this.config.balanceEl) : null;
      if (balEl) {
        var m = balEl.textContent.match(/[\d,]+/);
        if (m) bal = parseInt(m[0].replace(/,/g, ''), 10) || 0;
      }
      var cost = this.getCost();
      var after = Math.max(0, bal - cost);
      el.textContent = 'Balance after: ' + after.toLocaleString() + ' KP';
    },

    bindPricingUpdates: function() {
      var self = this;
      var q = document.getElementById(this.config.qualitySelectId);
      if (q) q.addEventListener('change', function() { self.updateCostLabel(); self.updateBalanceAfter(); });
      var s = document.getElementById(this.config.scaleSelectId);
      if (s) s.addEventListener('change', function() { self.updateCostLabel(); self.updateBalanceAfter(); });
    },

    updateBalance: function(kp) {
      var sel = this.config.balanceEl;
      var el = typeof sel === 'string' ? document.querySelector(sel) : sel;
      if (el) el.innerHTML = '<i class="fas fa-coins me-1"></i>' + parseInt(kp, 10).toLocaleString() + ' KP';
    },

    showStatus: function(jobId) {
      this.currentJobId = jobId;
      var panel = document.getElementById('labs-status-panel');
      var text = document.getElementById('labs-status-text');
      var preview = document.getElementById('labs-result-preview');
      var actions = document.getElementById('labs-result-actions');
      var errorEl = document.getElementById('labs-error-msg');

      if (panel) panel.style.display = 'block';
      if (actions) actions.style.display = 'none';
      if (errorEl) errorEl.style.display = 'none';
      if (text) text.textContent = 'Processing...';
      this.updateStepper('queued');

      var self = this;
      var pollCount = 0;
      function poll() {
        pollCount++;
        if (pollCount > POLL_MAX_COUNT) {
          self.stopPoll();
          if (panel) panel.style.display = 'none';
          if (errorEl) {
            errorEl.textContent = 'Taking too long. Ensure ComfyUI is running and config/comfyui.php has COMFYUI_BASE_URL set.';
            errorEl.style.display = 'block';
          }
          self.currentJobId = null;
          return;
        }
        fetch(API_STATUS + '?job_id=' + encodeURIComponent(jobId))
          .then(function(r) { return r.json(); })
          .then(function(d) {
            if (!d.ok || !d.data) return;
            var st = d.data.status;
            var msg = st.charAt(0).toUpperCase() + st.slice(1);
            var stage = d.data.stage || st;
            self.updateStepper(stage);
            if (st === 'queued') {
              var pos = d.data.queue_position;
              var eta = d.data.eta_seconds;
              msg = 'En cola';
              if (typeof pos === 'number') msg += ' (# ' + pos + ')';
              if (typeof eta === 'number' && eta > 0) {
                var m = Math.floor(eta / 60);
                var s = eta % 60;
                msg += ' ~ETA ' + (m > 0 ? m + 'm ' : '') + s + 's';
              }
            } else if (st === 'processing') {
              msg = 'Generando…';
            }
            if (text) text.textContent = msg;

            if (st === 'failed') {
              self.stopPoll();
              if (panel) panel.style.display = 'none';
              if (errorEl) {
                errorEl.textContent = d.data.error_message || 'Failed';
                errorEl.style.display = 'block';
              }
              self.currentJobId = null;
            } else if (st === 'done' || st === 'completed') {
              self.stopPoll();
              if (panel) panel.style.display = 'none';
              self.updateStepper('done');
              var proxyPreview = API_IMAGE + '?job_id=' + encodeURIComponent(jobId);
              var proxyDownload = API_IMAGE + '?job_id=' + encodeURIComponent(jobId) + '&download=1';
              var useInputUrl = '/labs-upscale.php?source_job_id=' + encodeURIComponent(jobId);
              if (preview) {
                preview.innerHTML = '<img src="' + proxyPreview + '" alt="Result" class="img-fluid rounded" style="max-height:400px;" data-job-id="' + jobId + '">';
              }
              var dlBtn = document.getElementById('labs-download-btn');
              if (dlBtn) {
                dlBtn.href = proxyDownload;
                dlBtn.download = 'knd-labs-output.png';
              }
              var useBtn = document.getElementById('labs-use-input-btn');
              if (useBtn) useBtn.href = useInputUrl;
              if (actions) actions.style.display = 'block';
              if (d.data.available_after !== undefined) self.updateBalance(d.data.available_after);
              self.updateBalanceAfter();
              self.currentJobId = null;
              self.lastJobId = jobId;
              fetch(API_JOB + '?job_id=' + encodeURIComponent(jobId), { credentials: 'same-origin' })
                .then(function(r) { return r.json(); })
                .then(function(d) {
                  if (d.ok && d.data) {
                    self.lastJobSeed = d.data.seed;
                    self.lastJobPayload = d.data;
                  }
                });
            }
          })
          .catch(function() {});
      }

      this.stopPoll();
      poll();
      this.pollTimer = setInterval(poll, POLL_INTERVAL);
    },

    stopPoll: function() {
      if (this.pollTimer) {
        clearInterval(this.pollTimer);
        this.pollTimer = null;
      }
    },

    updateStepper: function(stage) {
      var dots = document.querySelectorAll('.labs-stepper-dot');
      var steps = ['queued', 'picked', 'generating', 'done'];
      var idx = steps.indexOf(stage);
      if (idx < 0) idx = 0;
      dots.forEach(function(dot, i) {
        var s = dot.getAttribute('data-step');
        var i = steps.indexOf(s);
        dot.classList.toggle('active', i <= idx);
        dot.classList.toggle('current', s === stage);
      });
    },

    submitForm: function(formEl) {
      var fd = new FormData(formEl);
      var tool = fd.get('tool') || this.config.jobType || 'text2img';
      fd.set('tool', tool);

      var submitBtn = formEl.querySelector('[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      var self = this;
      var preview = document.getElementById('labs-result-preview');
      if (preview) {
        preview.innerHTML = '<div class="labs-processing-anim">' +
          '<div class="labs-processing-rings">' +
          '<div class="labs-processing-scan"></div>' +
          '<div class="labs-processing-ring"></div><div class="labs-processing-ring"></div><div class="labs-processing-ring"></div>' +
          '<div class="labs-processing-orbit"><div class="labs-processing-dot"></div></div>' +
          '<div class="labs-processing-core"></div>' +
          '</div>' +
          '<div class="labs-processing-bar"><div class="labs-processing-bar-fill"></div></div>' +
          '<span class="labs-processing-label">Processing...</span>' +
          '</div>';
      }

        fetch(API_GENERATE, {
        method: 'POST',
        body: fd,
        cache: 'no-store',
        credentials: 'same-origin'
      })
        .then(function(r) {
          return r.json().catch(function() { return { ok: false, error: { message: 'Invalid response from server' } }; })
            .then(function(d) {
              if (r.status === 429 && d.error && d.error.code === 'RATE_LIMIT') {
                d.error.message = d.error.message || 'Too many active jobs. Wait for current ones to finish.';
              }
              return d;
            });
        })
        .then(function(d) {
          if (submitBtn) submitBtn.disabled = false;
            if (d.ok && d.data && d.data.job_id) {
            if (d.data.available_after !== undefined) self.updateBalance(d.data.available_after);
            formEl.reset();
            self.updateCostLabel();
            self.updateBalanceAfter();
            self.updateSubmitButton();
            self.showStatus(d.data.job_id);
          } else {
            var msg = (d.error && d.error.message) ? d.error.message : 'Error';
            if (typeof kndToast !== 'undefined') kndToast(msg, 'error');
            else alert(msg);
            var prev = document.getElementById('labs-result-preview');
            if (prev) prev.innerHTML = '<div id="labs-placeholder-tips" class="labs-placeholder-tips"><i class="fas fa-lightbulb fa-2x text-white-50 mb-2"></i><p class="text-white-50 mb-1 small">Use 1 subject + 1 style + 1 lighting</p><p class="text-white-50 mb-0 small">e.g. &quot;Warrior, oil painting, golden hour&quot;</p></div>';
            var errEl = document.getElementById('labs-error-msg');
            if (errEl) { errEl.textContent = msg; errEl.style.display = 'block'; }
          }
        })
        .catch(function(err) {
          if (submitBtn) submitBtn.disabled = false;
          var msg = (err && err.message) ? err.message : 'Network error. Check console.';
          if (typeof kndToast !== 'undefined') kndToast(msg, 'error');
          else alert(msg);
        });
    },

    bindForm: function() {
      var form = document.getElementById(this.config.formId || 'labs-comfy-form');
      if (!form) return;

      var self = this;
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var prompt = form.querySelector('[name="prompt"]');
        if (prompt && self.config.jobType !== 'upscale') {
          if (!prompt.value || prompt.value.trim().length === 0) {
            if (typeof kndToast !== 'undefined') kndToast('Prompt is required', 'error');
            else alert('Prompt is required');
            return;
          }
        }
        var imageInput = form.querySelector('[name="image"]');
        if (imageInput && self.config.jobType === 'upscale') {
          if (!imageInput.files || !imageInput.files.length) {
            if (typeof kndToast !== 'undefined') kndToast('Image is required', 'error');
            else alert('Image is required');
            return;
          }
        }
        self.submitForm(form);
      });
    },

    bindPresets: function() {
      var form = document.getElementById(this.config.formId || 'labs-comfy-form');
      if (!form) return;
      var self = this;
      document.querySelectorAll('.preset-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var p = form.querySelector('[name="prompt"]');
          if (p) p.value = btn.getAttribute('data-prompt') || '';
          var n = form.querySelector('[name="negative_prompt"]');
          if (n && btn.getAttribute('data-negative')) n.value = btn.getAttribute('data-negative');
          var steps = form.querySelector('[name="steps"]');
          if (steps && btn.getAttribute('data-steps')) steps.value = btn.getAttribute('data-steps');
          var cfg = form.querySelector('[name="cfg"]');
          if (cfg && btn.getAttribute('data-cfg')) cfg.value = btn.getAttribute('data-cfg');
          var sampler = form.querySelector('[name="sampler_name"]');
          if (sampler && btn.getAttribute('data-sampler')) sampler.value = btn.getAttribute('data-sampler');
          var w = form.querySelector('[name="width"]');
          if (w && btn.getAttribute('data-width')) w.value = btn.getAttribute('data-width');
          var h = form.querySelector('[name="height"]');
          if (h && btn.getAttribute('data-height')) h.value = btn.getAttribute('data-height');
          var sum = document.getElementById('labs-preset-summary');
          if (sum) {
            var wv = btn.getAttribute('data-width') || '1024';
            var hv = btn.getAttribute('data-height') || '1024';
            var cfgv = btn.getAttribute('data-cfg') || '7.5';
            var stepsv = btn.getAttribute('data-steps') || '25';
            var samp = btn.getAttribute('data-sampler') || 'euler';
            sum.textContent = 'Preset: ' + wv + '\u00D7' + hv + ', CFG ' + cfgv + ', Steps ' + stepsv + ', ' + samp;
          }
          self.updateCostLabel();
          self.updateBalanceAfter();
          self.updateSubmitButton();
        });
      });
    },

    bindPresetsNegative: function() {
      document.querySelectorAll('.preset-neg-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var n = document.querySelector('[name="negative_prompt"]');
          if (!n) return;
          var toAdd = (btn.getAttribute('data-value') || '').trim();
          if (!toAdd) return;
          var current = (n.value || '').trim();
          if (current === '') {
            n.value = toAdd;
          } else {
            n.value = current + ', ' + toAdd;
          }
        });
      });
    },

    bindPromptValidation: function() {
      var self = this;
      if (self.config.jobType === 'upscale') return;
      var prompt = document.getElementById('labs-prompt-input') || document.querySelector('[name="prompt"]');
      var hint = document.getElementById('labs-prompt-hint');
      function check() {
        var v = (prompt && prompt.value ? prompt.value : '').trim();
        if (hint) {
          if (v.length === 0) hint.textContent = 'Enter a prompt';
          else if (v.length < 10) hint.textContent = 'Add style, environment or lighting for better results';
          else hint.textContent = '';
        }
        self.recomputeGenerateState();
      }
      if (prompt) {
        prompt.addEventListener('input', check);
        prompt.addEventListener('change', check);
      }
      var model = document.getElementById('labs-model-select') || document.querySelector('#labs-comfy-form [name="model"]');
      var widthSel = document.getElementById('labs-width-select') || document.querySelector('#labs-comfy-form [name="width"]');
      var heightSel = document.getElementById('labs-height-select') || document.querySelector('#labs-comfy-form [name="height"]');
      [model, widthSel, heightSel].forEach(function(el) {
        if (el) {
          el.addEventListener('input', function() { self.recomputeGenerateState(); });
          el.addEventListener('change', function() { self.recomputeGenerateState(); });
        }
      });
      self.recomputeGenerateState();
    },

    recomputeGenerateState: function() {
      var form = document.getElementById(this.config.formId || 'labs-comfy-form');
      if (!form) return;
      var prompt = document.getElementById('labs-prompt-input') || form.querySelector('[name="prompt"]');
      var model = document.getElementById('labs-model-select') || form.querySelector('[name="model"]');
      var widthSel = document.getElementById('labs-width-select') || form.querySelector('[name="width"]');
      var heightSel = document.getElementById('labs-height-select') || form.querySelector('[name="height"]');
      var promptVal = (prompt && prompt.value ? prompt.value : '').trim();
      var modelVal = (model && model.value ? model.value : '');
      var widthVal = widthSel ? (Number(widthSel.value) || 0) : 1024;
      var heightVal = heightSel ? (Number(heightSel.value) || 0) : 1024;
      var btn = document.getElementById('generateBtn') || document.getElementById('labs-submit-btn');
      if (this.config.jobType === 'upscale') {
        var img = form.querySelector('[name="image"]');
        var hasFile = img && img.files && img.files.length > 0;
        if (btn) btn.disabled = !hasFile;
        return;
      }
      var valid = promptVal.length > 0 && modelVal !== '' && widthVal > 0 && heightVal > 0;
      if (btn) btn.disabled = !valid;
    },

    updateSubmitButton: function() {
      this.recomputeGenerateState();
    },

    bindAdvancedToggle: function() {
      var adv = document.getElementById('labs-advanced');
      var toggle = document.getElementById('labs-advanced-toggle');
      if (!adv || !toggle) return;
      adv.addEventListener('shown.bs.collapse', function() {
        var icon = toggle.querySelector('i');
        if (icon) { icon.classList.remove('fa-chevron-down'); icon.classList.add('fa-chevron-up'); }
      });
      adv.addEventListener('hidden.bs.collapse', function() {
        var icon = toggle.querySelector('i');
        if (icon) { icon.classList.remove('fa-chevron-up'); icon.classList.add('fa-chevron-down'); }
      });
    },

    bindViewDetails: function() {
      var self = this;
      document.addEventListener('click', function(e) {
        var btn = e.target.closest('.labs-view-details');
        if (!btn) return;
        e.preventDefault();
        var jid = btn.getAttribute('data-job-id');
        if (!jid) return;
        fetch(API_JOB + '?job_id=' + jid, { credentials: 'same-origin' })
          .then(function(r) { return r.json(); })
          .then(function(d) {
            if (!d.ok || !d.data) return;
            var J = d.data;
            var html = '<table class="table table-sm table-dark"><tbody>' +
              '<tr><td class="text-white-50">Prompt</td><td class="text-white">' + (J.prompt ? J.prompt.substring(0, 200) + (J.prompt.length > 200 ? '...' : '') : '-') + '</td></tr>' +
              '<tr><td class="text-white-50">Model</td><td class="text-white">' + (J.model || '-') + '</td></tr>' +
              '<tr><td class="text-white-50">Seed</td><td class="text-white">' + (J.seed != null ? J.seed : '-') + '</td></tr>' +
              '<tr><td class="text-white-50">Size</td><td class="text-white">' + (J.width || '') + '\u00D7' + (J.height || '') + '</td></tr>' +
              '<tr><td class="text-white-50">Steps</td><td class="text-white">' + (J.steps || '-') + '</td></tr>' +
              '<tr><td class="text-white-50">CFG</td><td class="text-white">' + (J.cfg || '-') + '</td></tr>' +
              '<tr><td class="text-white-50">Sampler</td><td class="text-white">' + (J.sampler_name || '-') + '</td></tr>' +
              '<tr><td class="text-white-50">Charged</td><td class="text-white">' + (J.cost_kp || 0) + ' KP</td></tr>' +
              '<tr><td class="text-white-50">Provider</td><td class="text-white">' + (J.provider || 'Local') + '</td></tr>' +
              '<tr><td class="text-white-50">Created</td><td class="text-white">' + (J.created_at || '-') + '</td></tr>' +
              '</tbody></table>';
            if (J.error_message) html += '<p class="text-danger small">' + J.error_message + '</p>';
            var body = document.getElementById('labs-job-details-body');
            if (body) body.innerHTML = html;
            var modal = document.getElementById('labs-job-details-modal');
            if (modal && typeof bootstrap !== 'undefined') {
              var m = bootstrap.Modal.getOrCreateInstance(modal);
              m.show();
            }
          });
      });
    },

    bindRecentFilter: function() {
      var sel = document.getElementById('labs-recent-filter');
      if (!sel) return;
      sel.addEventListener('change', function() {
        var v = sel.value;
        window.location.href = window.location.pathname + (v ? '?provider=' + encodeURIComponent(v) : '');
      });
    },

    bindPrivateCheck: function() {
      var cb = document.getElementById('labs-private-check');
      if (!cb) return;
      cb.addEventListener('change', function() {
        var fd = new FormData();
        fd.set('private', cb.checked ? '1' : '0');
        fetch(API_PREFERENCE, { method: 'POST', body: fd, credentials: 'same-origin' }).catch(function() {});
      });
    },

    bindRetry: function() {
      var retryBtn = document.getElementById('labs-retry-btn');
      if (!retryBtn) return;
      retryBtn.addEventListener('click', function() {
        var preview = document.getElementById('labs-result-preview');
        var actions = document.getElementById('labs-result-actions');
        if (preview) preview.innerHTML = '<div id="labs-placeholder-tips" class="labs-placeholder-tips"><i class="fas fa-lightbulb fa-2x text-white-50 mb-2"></i><p class="text-white-50 mb-1 small">Use 1 subject + 1 style + 1 lighting</p><p class="text-white-50 mb-0 small">e.g. &quot;Warrior, oil painting, golden hour&quot;</p></div>';
        if (actions) actions.style.display = 'none';
      });
    },

    bindRegenerate: function() {
      var self = this;
      var btn = document.getElementById('labs-regenerate-btn');
      if (!btn) return;
      btn.addEventListener('click', function() {
        var form = document.getElementById(self.config.formId || 'labs-comfy-form');
        if (!form) return;
        var seedEl = form.querySelector('[name="seed"]');
        if (seedEl && self.lastJobSeed != null) seedEl.value = self.lastJobSeed;
        self.submitForm(form);
      });
    },

    bindVariations: function() {
      var self = this;
      var btn = document.getElementById('labs-variations-btn');
      if (!btn) return;
      btn.addEventListener('click', function() {
        var form = document.getElementById(self.config.formId || 'labs-comfy-form');
        if (!form) return;
        var seedEl = form.querySelector('[name="seed"]');
        if (seedEl) {
          var current = parseInt(seedEl.value, 10);
          if (isNaN(current)) current = Math.floor(Math.random() * 2147483647);
          seedEl.value = (current + 1) % 2147483647;
        }
        self.submitForm(form);
      });
    }
  };

  window.KNDLabs = KNDLabs;
})();
