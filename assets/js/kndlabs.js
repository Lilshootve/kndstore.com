/**
 * KND Labs - ComfyUI frontend (Orbitron vibe)
 * Shared logic for text2img, upscale, character-lab.
 */
(function() {
  'use strict';

  var POLL_INTERVAL = 2000;
  var API_GENERATE = '/api/labs/generate.php';
  var API_STATUS = '/api/labs/status.php';
  var API_JOBS = '/api/labs/jobs.php';

  var KNDLabs = {
    config: {},
    pollTimer: null,
    currentJobId: null,

    init: function(cfg) {
      this.config = cfg || {};
      this.config.balanceEl = this.config.balanceEl
        ? document.querySelector(this.config.balanceEl)
        : null;
      this.bindForm();
      this.bindPresets();
      this.bindRetry();
    },

    updateBalance: function(kp) {
      var el = this.config.balanceEl;
      if (el) el.textContent = parseInt(kp, 10).toLocaleString() + ' KP';
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
      function poll() {
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
              var imgUrl = d.data.image_url;
              if (preview && imgUrl) {
                preview.innerHTML = '<img src="' + imgUrl + '" alt="Result" class="img-fluid rounded" style="max-height:400px;" crossorigin="anonymous">';
              }
              var dlBtn = document.getElementById('labs-download-btn');
              if (dlBtn && imgUrl) {
                dlBtn.href = imgUrl;
                dlBtn.target = '_blank';
                dlBtn.download = 'knd-labs-output.png';
              }
              if (actions) actions.style.display = 'block';
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

      fetch(API_GENERATE, { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(d) {
          if (submitBtn) submitBtn.disabled = false;
          if (d.ok && d.data && d.data.job_id) {
            formEl.reset();
            self.showStatus(d.data.job_id);
          } else {
            var msg = (d.error && d.error.message) ? d.error.message : 'Error';
            if (typeof kndToast !== 'undefined') kndToast(msg, 'error');
            else alert(msg);
          }
        })
        .catch(function() {
          if (submitBtn) submitBtn.disabled = false;
          if (typeof kndToast !== 'undefined') kndToast('Network error', 'error');
          else alert('Network error');
        });
    },

    bindForm: function() {
      var form = document.getElementById(this.config.formId || 'labs-comfy-form');
      if (!form) return;

      var self = this;
      form.addEventListener('submit', function(e) {
        e.preventDefault();
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
