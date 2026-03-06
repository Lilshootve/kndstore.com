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
          self.bindUseLastPrompt();
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
          self.bindUseLastPrompt();
          self.updateCostLabel();
          self.updateBalanceAfter();
          self.updateSubmitButton();
        });
    },

    bindUseLastPrompt: function() {
      var self = this;
      var btn = document.getElementById('labs-use-last-prompt-btn');
      if (!btn) return;
      btn.addEventListener('click', function() {
        if (!self.lastJobPayload || !self.lastJobPayload.prompt) return;
        var form = document.getElementById(self.config.formId || 'labs-comfy-form');
        if (!form) return;
        var promptEl = form.querySelector('[name="prompt"]');
        var negEl = form.querySelector('[name="negative_prompt"]');
        if (promptEl) promptEl.value = self.lastJobPayload.prompt || '';
        if (negEl && self.lastJobPayload.negative_prompt) negEl.value = self.lastJobPayload.negative_prompt;
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
      if (key === 'consistency') return (p.consistency && p.consistency.base) || 5;
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
                var tool = self.config.jobType || 'text2img';
                var mode = 'style';
                preview.innerHTML = '<img src="' + proxyPreview + '" alt="Result" class="img-fluid rounded" style="max-height:400px;" data-job-id="' + jobId + '" data-job-tool="' + tool + '" data-job-mode="' + mode + '">';
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
              var imgEl = preview ? preview.querySelector('img[data-job-id]') : null;
              fetch(API_JOB + '?job_id=' + encodeURIComponent(jobId), { credentials: 'same-origin' })
                .then(function(r) { return r.json(); })
                .then(function(jobData) {
                  if (jobData.ok && jobData.data) {
                    self.lastJobSeed = jobData.data.seed;
                    self.lastJobPayload = jobData.data;
                    var useLastBtn = document.getElementById('labs-use-last-prompt-btn');
                    if (useLastBtn && jobData.data.prompt) { useLastBtn.style.display = 'inline-block'; }
                    if (imgEl && jobData.data.tool === 'consistency' && jobData.data.mode) {
                      imgEl.setAttribute('data-job-mode', jobData.data.mode);
                    }
                    self.renderImageDetails(jobData.data);
                    if (typeof self.onJobDone === 'function') self.onJobDone(jobId, jobData.data);
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

    buildConsistencyFormData: function(formEl) {
      var fd = new FormData();
      fd.set('mode', (formEl.querySelector('[name="mode"]')?.value || 'style'));
      fd.set('reference_source', (formEl.querySelector('[name="reference_source"]:checked')?.value || 'upload'));
      var refJob = formEl.querySelector('[name="reference_job_id"]');
      if (refJob && refJob.value) fd.set('reference_job_id', refJob.value);
      var refFile = formEl.querySelector('[name="reference_image"]');
      if (refFile && refFile.files && refFile.files[0]) fd.set('reference_image', refFile.files[0]);
      fd.set('base_prompt', (formEl.querySelector('[name="base_prompt"]')?.value || '').trim());
      fd.set('scene_prompt', (formEl.querySelector('[name="scene_prompt"]')?.value || '').trim());
      fd.set('negative_prompt', (formEl.querySelector('[name="negative_prompt"]')?.value || 'ugly, blurry, low quality'));
      fd.set('lock_seed', formEl.querySelector('[name="lock_seed"]')?.checked ? '1' : '0');
      fd.set('inherit_model', formEl.querySelector('[name="inherit_model"]')?.checked ? '1' : '0');
      fd.set('inherit_resolution', formEl.querySelector('[name="inherit_resolution"]')?.checked ? '1' : '0');
      fd.set('inherit_sampling', formEl.querySelector('[name="inherit_sampling"]')?.checked ? '1' : '0');
      fd.set('width', formEl.querySelector('[name="width"]')?.value || 1024);
      fd.set('height', formEl.querySelector('[name="height"]')?.value || 1024);
      fd.set('steps', formEl.querySelector('[name="steps"]')?.value || 28);
      fd.set('cfg', formEl.querySelector('[name="cfg"]')?.value || 7);
      fd.set('sampler', formEl.querySelector('[name="sampler"]')?.value || 'dpmpp_2m');
      var seedEl = formEl.querySelector('[name="seed"]');
      if (seedEl && seedEl.value) fd.set('seed', seedEl.value);
      var modelEl = formEl.querySelector('[name="model"]');
      if (modelEl && modelEl.value) fd.set('model', modelEl.value);
      return fd;
    },

    submitForm: function(formEl) {
      var tool = this.config.jobType || formEl.querySelector('[name="tool"]')?.value || 'text2img';
      var apiUrl = API_GENERATE;
      var fd;
      if (tool === 'consistency' && this.config.apiConsistency) {
        fd = this.buildConsistencyFormData(formEl);
        apiUrl = this.config.apiConsistency;
      } else {
        fd = new FormData(formEl);
        fd.set('tool', tool);
      }

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

        fetch(apiUrl, {
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
            if (self.config.keepFormOnSuccess !== false) { /* keep form (prompt, etc.) for variations */ } else { formEl.reset(); }
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
        var ipEn = form.querySelector('[name="ipadapter_enabled"]');
        if (ipEn && ipEn.checked && self.config.jobType === 'text2img') {
          var ipImg = form.querySelector('[name="ipadapter_image"]');
          if (!ipImg || !ipImg.files || !ipImg.files.length) {
            if (typeof kndToast !== 'undefined') kndToast('Reference image required when IPAdapter is enabled', 'error');
            else alert('Reference image required when IPAdapter is enabled');
            return;
          }
        }
        var cnEn = form.querySelector('[name="controlnet_enabled"]');
        if (cnEn && cnEn.checked && self.config.jobType === 'text2img') {
          var cnImg = form.querySelector('[name="controlnet_image"]');
          if (!cnImg || !cnImg.files || !cnImg.files.length) {
            if (typeof kndToast !== 'undefined') kndToast('Control image required when ControlNet is enabled', 'error');
            else alert('Control image required when ControlNet is enabled');
            return;
          }
        }
        if (self.config.jobType === 'consistency') {
          var refSource = form.querySelector('[name="reference_source"]:checked');
          var refVal = refSource ? refSource.value : 'upload';
          if (refVal === 'recent') {
            var refJob = form.querySelector('[name="reference_job_id"]');
            if (!refJob || !refJob.value || refJob.value === '') {
              if (typeof kndToast !== 'undefined') kndToast('Select a reference job from recent.', 'error');
              else alert('Select a reference job from recent.');
              return;
            }
          } else {
            var refFile = form.querySelector('[name="reference_image"]');
            if (!refFile || !refFile.files || !refFile.files.length) {
              if (typeof kndToast !== 'undefined') kndToast('Upload a reference image.', 'error');
              else alert('Upload a reference image.');
              return;
            }
          }
          var baseP = form.querySelector('[name="base_prompt"]');
          var sceneP = form.querySelector('[name="scene_prompt"]');
          var baseVal = baseP ? (baseP.value || '').trim() : '';
          var sceneVal = sceneP ? (sceneP.value || '').trim() : '';
          if (!baseVal && !sceneVal) {
            if (typeof kndToast !== 'undefined') kndToast('Base prompt or Scene prompt is required.', 'error');
            else alert('Base prompt or Scene prompt is required.');
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
      form.querySelectorAll('.preset-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var p = form.querySelector('[name="prompt"]');
          if (p) p.value = btn.getAttribute('data-prompt') || '';
          var n = form.querySelector('[name="negative_prompt"]');
          if (n) n.value = btn.getAttribute('data-negative') || n.value;
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
      var form = document.getElementById(this.config.formId || 'labs-comfy-form');
      if (!form) return;
      form.querySelectorAll('.preset-neg-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var n = form.querySelector('[name="negative_prompt"]');
          if (!n) return;
          var val = (btn.getAttribute('data-value') || '').trim();
          if (val) n.value = val;
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
        prompt.addEventListener('keyup', check);
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
      var btn = form.querySelector('[type="submit"]') || document.getElementById('generateBtn') || document.getElementById('labs-submit-btn');
      if (this.config.jobType === 'upscale') {
        var img = form.querySelector('[name="image"]');
        var hasFile = img && img.files && img.files.length > 0;
        if (btn) btn.disabled = !hasFile;
        return;
      }
      // text2img: always enabled, validate on submit
      if (btn) btn.disabled = false;
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

    renderImageDetails: function(job) {
      if (!job) return;
      var panel = document.getElementById('labs-image-details-panel');
      var rowsEl = document.getElementById('labs-details-rows');
      var actionsEl = document.getElementById('labs-image-details-actions');
      if (!panel || !rowsEl) return;
      var rows = [];
      function addRow(label, value) {
        if (value != null && value !== '' && value !== '—') rows.push({ label: label, value: String(value) });
      }
      addRow('Tool', job.tool || '—');
      addRow('Status', job.status || '—');
      addRow('Model', job.model || '—');
      addRow('Seed', job.seed);
      addRow('Sampler', job.sampler_name || job.sampler || '—');
      addRow('Steps', job.steps);
      addRow('CFG', job.cfg);
      addRow('Resolution', (job.width && job.height) ? job.width + '\u00D7' + job.height : null);
      addRow('Created', job.created_at || '—');
      addRow('Job ID', job.job_id || '—');
      addRow('Cost', job.cost_kp ? job.cost_kp + ' KP' : null);
      if (job.tool === 'consistency') {
        addRow('Mode', job.mode || '—');
        addRow('Reference', job.reference_source || '—');
        addRow('Ref Job', job.reference_job_id);
      }
      rowsEl.innerHTML = rows.map(function(r) {
        return '<div class="col-6 col-md-4"><span class="text-white-50">' + r.label + ':</span> <span class="text-white">' + (r.value.length > 40 ? r.value.substring(0, 37) + '...' : r.value) + '</span></div>';
      }).join('');
      if (actionsEl) {
        var jid = job.job_id;
        var mode = (job.tool === 'consistency' && job.mode) ? job.mode : 'style';
        var actions = [];
        if (jid && (job.tool === 'text2img' || job.tool === 'consistency')) {
          actions.push('<a href="/labs-consistency.php?reference_job_id=' + jid + '&mode=' + mode + '" class="btn btn-sm btn-outline-primary">' + (typeof t === 'function' ? t('labs.generate_variations', 'Generate Variations') : 'Generate Variations') + '</a>');
        }
        if (jid) actions.push('<a href="/labs-upscale.php?source_job_id=' + jid + '" class="btn btn-sm btn-outline-secondary">Upscale</a>');
        actionsEl.innerHTML = actions.join(' ');
      }
      panel.style.display = 'block';
    },

    bindViewDetails: function() {
      var self = this;
      var drawer = document.getElementById('labs-details-drawer');
      var backdrop = document.getElementById('labs-details-backdrop');
      var closeBtn = document.getElementById('labs-details-close');
      function closeDrawer() {
        if (drawer) drawer.classList.remove('is-open');
        if (backdrop) backdrop.classList.remove('is-visible');
      }
      function openDrawer() {
        if (drawer) drawer.classList.add('is-open');
        if (backdrop) backdrop.classList.add('is-visible');
      }
      if (backdrop) backdrop.addEventListener('click', closeDrawer);
      if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
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
            var imgUrl = J.image_url || (API_IMAGE + '?job_id=' + jid);
            var sizeStr = (J.width && J.height) ? J.width + '\u00D7' + J.height : '';
            var html = '';
            html += '<div class="knd-details-block"><div class="knd-details-block__title">Config</div>';
            html += '<p class="text-white-50 small mb-1"><strong class="text-white">Prompt:</strong><br>' + (J.prompt ? self.esc(J.prompt) : '-') + '</p>';
            html += '<p class="text-white-50 small mb-1"><strong class="text-white">Negative:</strong><br>' + (J.negative_prompt ? self.esc(J.negative_prompt) : '-') + '</p>';
            html += '<p class="text-white-50 small mb-0">Model: ' + (J.model || '-') + ' | Quality: ' + (J.cost_kp ? J.cost_kp + ' KP' : '-') + '</p>';
            html += '<p class="text-white-50 small mb-0">Aspect: ' + (sizeStr || '-') + ' | Steps: ' + (J.steps || '-') + ' | CFG: ' + (J.cfg || '-') + ' | Sampler: ' + (J.sampler_name || '-') + '</p>';
            html += '</div>';
            html += '<div class="knd-details-block"><div class="knd-details-block__title">Result</div>';
            html += '<div class="knd-details-preview">';
            if (J.status === 'done' && imgUrl) {
              html += '<img src="' + imgUrl.replace(/"/g, '&quot;') + '" alt="">';
            } else {
              html += '<span class="d-flex align-items-center justify-content-center text-white-50" style="min-height:200px;"><i class="fas fa-image fa-3x opacity-50"></i></span>';
            }
            html += '</div>';
            html += '<p class="text-white-50 small mb-0">' + (J.created_at || '-') + ' | ' + (J.status || '') + (J.cost_kp ? ' | ' + J.cost_kp + ' KP' : '') + '</p>';
            if (J.error_message) html += '<p class="text-danger small mt-1">' + self.esc(J.error_message) + '</p>';
            html += '</div>';
            html += '<div class="knd-details-block"><div class="knd-details-block__title">Actions</div><div class="knd-details-actions">';
            if (J.status === 'done' && (J.tool === 'text2img' || J.tool === 'consistency')) {
              var mode = (J.tool === 'consistency' && J.mode) ? J.mode : 'style';
              html += '<a href="/labs-upscale.php?source_job_id=' + jid + '" class="btn btn-sm knd-btn-secondary"><i class="fas fa-search-plus me-1"></i>Send to Upscale</a>';
              html += '<a href="/labs-consistency.php?reference_job_id=' + jid + '&mode=' + mode + '" class="btn btn-sm knd-btn-secondary"><i class="fas fa-palette me-1"></i>Consistency</a>';
              html += '<a href="/labs-consistency.php?reference_job_id=' + jid + '&mode=' + mode + '" class="btn btn-sm knd-btn-secondary"><i class="fas fa-images me-1"></i>Create Variations</a>';
            }
            if (J.status === 'done') {
              html += '<a href="' + imgUrl.replace(/"/g, '&quot;') + '" class="btn btn-sm knd-btn-secondary" download><i class="fas fa-download me-1"></i>Download</a>';
            }
            html += '<button type="button" class="btn btn-sm knd-btn-secondary labs-details-reuse" data-job-id="' + jid + '"><i class="fas fa-copy me-1"></i>Reuse Settings</button>';
            html += '</div></div>';
            var body = document.getElementById('labs-details-body');
            if (!body) return;
            body.innerHTML = html;
            body.querySelectorAll('.labs-details-reuse').forEach(function(b) {
              b.addEventListener('click', function() {
                self.reuseJobSettings(jid);
                closeDrawer();
              });
            });
            self.renderImageDetails(J);
            openDrawer();
          });
      });
    },

    esc: function(s) {
      if (s == null) return '';
      var div = document.createElement('div');
      div.textContent = String(s);
      return div.innerHTML;
    },

    reuseJobSettings: function(jid) {
      var self = this;
      fetch(API_JOB + '?job_id=' + jid, { credentials: 'same-origin' })
        .then(function(r) { return r.json(); })
        .then(function(d) {
          if (!d.ok || !d.data) return;
          var J = d.data;
          var form = document.getElementById(self.config.formId || 'labs-comfy-form');
          if (!form) return;
          var set = function(name, val) { var el = form.querySelector('[name="' + name + '"]'); if (el && val != null) el.value = val; };
          set('prompt', J.prompt);
          set('negative_prompt', J.negative_prompt);
          set('model', J.model);
          set('seed', J.seed);
          set('steps', J.steps);
          set('cfg', J.cfg);
          set('width', J.width);
          set('height', J.height);
          set('sampler_name', J.sampler_name);
          set('scheduler', J.scheduler);
          if (self.updateCostLabel) self.updateCostLabel();
          if (self.updateBalanceAfter) self.updateBalanceAfter();
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
