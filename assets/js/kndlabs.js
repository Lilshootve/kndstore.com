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
  var API_IMAGE = '/api/labs/image.php';
  var API_PRICING = '/api/labs/pricing.php';

  var KNDLabs = {
    config: {},
    pollTimer: null,
    currentJobId: null,
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
          self.bindRetry();
          self.bindPricingUpdates();
          self.updateCostLabel();
        })
        .catch(function() {
          self.bindForm();
          self.bindPresets();
          self.bindRetry();
          self.bindPricingUpdates();
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

    bindPricingUpdates: function() {
      var self = this;
      var q = document.getElementById(this.config.qualitySelectId);
      if (q) q.addEventListener('change', function() { self.updateCostLabel(); });
      var s = document.getElementById(this.config.scaleSelectId);
      if (s) s.addEventListener('change', function() { self.updateCostLabel(); });
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
            if (text) text.textContent = st.charAt(0).toUpperCase() + st.slice(1);

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
              var proxyPreview = API_IMAGE + '?job_id=' + encodeURIComponent(jobId);
              var proxyDownload = API_IMAGE + '?job_id=' + encodeURIComponent(jobId) + '&download=1';
              if (preview) {
                preview.innerHTML = '<img src="' + proxyPreview + '" alt="Result" class="img-fluid rounded" style="max-height:400px;">';
              }
              var dlBtn = document.getElementById('labs-download-btn');
              if (dlBtn) {
                dlBtn.href = proxyDownload;
                dlBtn.download = 'knd-labs-output.png';
              }
              if (actions) actions.style.display = 'block';
              if (d.data.available_after !== undefined) self.updateBalance(d.data.available_after);
              self.currentJobId = null;
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

    submitForm: function(formEl) {
      var fd = new FormData(formEl);
      var tool = fd.get('tool') || this.config.jobType || 'text2img';
      fd.set('tool', tool);

      var submitBtn = formEl.querySelector('[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      var self = this;
      var preview = document.getElementById('labs-result-preview');
      if (preview) {
        preview.innerHTML = '<div class="ai-spinner"><i class="fas fa-cog fa-spin fa-2x"></i></div><p class="text-white-50 mt-2 mb-0">Processing...</p>';
      }

      fetch(API_GENERATE, {
        method: 'POST',
        body: fd,
        cache: 'no-store',
        credentials: 'same-origin'
      })
        .then(function(r) {
          return r.json().catch(function() { return { ok: false, error: { message: 'Invalid response from server' } }; });
        })
        .then(function(d) {
          if (submitBtn) submitBtn.disabled = false;
          if (d.ok && d.data && d.data.job_id) {
            if (d.data.available_after !== undefined) self.updateBalance(d.data.available_after);
            formEl.reset();
            self.updateCostLabel();
            self.showStatus(d.data.job_id);
          } else {
            var msg = (d.error && d.error.message) ? d.error.message : 'Error';
            if (typeof kndToast !== 'undefined') kndToast(msg, 'error');
            else alert(msg);
            var prev = document.getElementById('labs-result-preview');
            if (prev) prev.innerHTML = '<i class="fas fa-image fa-3x text-white-50 mb-3"></i><p class="text-white-50 mb-0">Submit to generate</p>';
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
      document.querySelectorAll('.preset-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var p = form.querySelector('[name="prompt"]');
          if (p) p.value = btn.getAttribute('data-prompt') || '';
        });
      });
    },

    bindRetry: function() {
      var retryBtn = document.getElementById('labs-retry-btn');
      if (retryBtn) {
        retryBtn.addEventListener('click', function() {
          var preview = document.getElementById('labs-result-preview');
          var actions = document.getElementById('labs-result-actions');
          if (preview) preview.innerHTML = '<i class="fas fa-image fa-3x text-white-50 mb-3"></i><p class="text-white-50 mb-0">Submit to generate</p>';
          if (actions) actions.style.display = 'none';
        });
      }
    }
  };

  window.KNDLabs = KNDLabs;
})();
