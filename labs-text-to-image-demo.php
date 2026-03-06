<?php
/**
 * KND Labs - Text to Image DEMO (Premium Future UI)
 * Vista prototipo visual. No modifica el sitio global.
 * Ruta: /labs-text-to-image-demo.php
 */
require_once __DIR__ . '/labs/_init.php';

$toolName = 'Text → Image';
$balance = isset($balance) ? $balance : 866;

$extraCss = '<link rel="stylesheet" href="/assets/css/labs-future-ui.css?v=' . (file_exists(__DIR__ . '/assets/css/labs-future-ui.css') ? filemtime(__DIR__ . '/assets/css/labs-future-ui.css') : time()) . '">';
echo generateHeader($toolName . ' (Demo) | KND Labs', 'Create Epic, Hyper-Realistic & Cinematic AI Art', $extraCss);
?>
<?php echo generateNavigation(); ?>

<div class="lfu">
  <main class="lfu-page">
    <div class="container">
      <!-- Breadcrumb -->
      <nav class="lfu-breadcrumb">
        <a href="/">Home</a> / <a href="/knd-labs.php">KND Labs</a> / <span><?php echo htmlspecialchars($toolName); ?></span> <small class="text-muted">(Demo)</small>
      </nav>

      <h1 class="lfu-title"><?php echo htmlspecialchars($toolName); ?> Generator</h1>
      <p class="lfu-subtitle">Create Epic, Hyper-Realistic & Cinematic AI Art</p>

      <div class="row g-4">
        <!-- Columna principal -->
        <div class="col-lg-8">
          <!-- Result Preview -->
          <div class="lfu-panel mb-4">
            <div class="lfu-result" id="lfu-result">
              <div class="lfu-result-placeholder">
                <i class="fas fa-wand-magic-sparkles"></i>
                <p>Describe your vision below and generate</p>
              </div>
            </div>
          </div>

          <!-- Prompt -->
          <div class="lfu-panel mb-4">
            <label class="lfu-label" for="lfu-prompt-input">Prompt</label>
            <textarea id="lfu-prompt-input" class="lfu-textarea" rows="3" placeholder="Describe your vision..." maxlength="500">Ultra realistic portrait, professional lighting, 8K</textarea>
            <div class="lfu-section-title mt-3">Presets</div>
            <div class="lfu-chip-group d-flex flex-wrap gap-2 mt-2">
              <button type="button" class="lfu-chip" data-prompt="Game character concept, fantasy armor, cinematic portrait">Game Concept</button>
              <button type="button" class="lfu-chip" data-prompt="Anime portrait, detailed face, soft lighting">Anime</button>
              <button type="button" class="lfu-chip active" data-prompt="Ultra realistic portrait, professional lighting, 8K">Photorealistic</button>
              <button type="button" class="lfu-chip" data-prompt="Cyberpunk city, neon lights, rain, night">Cyberpunk</button>
              <button type="button" class="lfu-chip" data-prompt="Landscape, mountains, sunset, atmospheric">Landscape</button>
            </div>
          </div>

          <!-- Negative + Options -->
          <div class="lfu-panel mb-4">
            <label class="lfu-label" for="lfu-negative-input">Negative Prompt</label>
            <input type="text" id="lfu-negative-input" class="lfu-input" value="ugly, blurry, low quality" placeholder="ugly, blurry, low quality">
            <div class="lfu-divider"></div>
            <div class="lfu-section-title">Aspect Ratio</div>
            <div class="lfu-aspect-group mt-2">
              <button type="button" class="lfu-aspect-btn" data-ratio="1:1" title="1:1">□</button>
              <button type="button" class="lfu-aspect-btn active" data-ratio="16:9" title="16:9">▭</button>
              <button type="button" class="lfu-aspect-btn" data-ratio="9:16" title="9:16">▯</button>
              <button type="button" class="lfu-aspect-btn" data-ratio="4:3" title="4:3">▭</button>
            </div>
            <div class="row g-2 mt-3">
              <div class="col-6 col-md-4">
                <label class="lfu-label">Style</label>
                <select class="lfu-select">
                  <option>Ultra Realistic</option>
                  <option>Anime</option>
                  <option>Concept Art</option>
                  <option>Product Render</option>
                </select>
              </div>
              <div class="col-6 col-md-4">
                <label class="lfu-label">Resolution</label>
                <select class="lfu-select">
                  <option>8K</option>
                  <option>4K</option>
                  <option>2K</option>
                  <option>1K</option>
                </select>
              </div>
            </div>
          </div>

          <!-- CTA Generate -->
          <div class="d-flex justify-content-center gap-2 mb-4">
            <button type="button" class="lfu-btn-primary" id="lfu-generate-btn">
              <i class="fas fa-arrow-down"></i> Generate
            </button>
            <button type="button" class="lfu-btn-secondary"><i class="fas fa-cog"></i> Settings</button>
          </div>

          <!-- Recent Creations (mini gallery) -->
          <div class="lfu-panel">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="lfu-panel-header">Recent Creations</span>
              <a href="/labs-text-to-image.php" class="lfu-btn-secondary btn-sm">View All</a>
            </div>
            <div class="row g-2">
              <div class="col-3 col-md-3"><div class="rounded overflow-hidden bg-dark" style="aspect-ratio:1; background:var(--lfu-panel-alt);"></div></div>
              <div class="col-3 col-md-3"><div class="rounded overflow-hidden bg-dark" style="aspect-ratio:1; background:var(--lfu-panel-alt);"></div></div>
              <div class="col-3 col-md-3"><div class="rounded overflow-hidden bg-dark" style="aspect-ratio:1; background:var(--lfu-panel-alt);"></div></div>
              <div class="col-3 col-md-3"><div class="rounded overflow-hidden bg-dark" style="aspect-ratio:1; background:var(--lfu-panel-alt);"></div></div>
            </div>
          </div>
        </div>

        <!-- Columna lateral -->
        <div class="col-lg-4 lfu-sidebar">
          <!-- Credits -->
          <div class="lfu-panel mb-4">
            <div class="lfu-section-title">Credits</div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="lfu-credits">
                <i class="fas fa-gem"></i>
                <span><?php echo number_format($balance); ?> KP</span>
              </div>
              <button type="button" class="lfu-btn-secondary btn-sm">+ Add Credits</button>
            </div>
          </div>

          <!-- Model -->
          <div class="lfu-panel mb-4">
            <div class="lfu-section-title">Model</div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-white">Juggernaut XL V8</span>
              <span class="lfu-badge completed">NEW</span>
            </div>
            <button type="button" class="lfu-btn-secondary btn-sm mt-2 w-100"><i class="fas fa-cog"></i> Model Settings</button>
          </div>

          <!-- Advanced Settings -->
          <div class="lfu-panel mb-4">
            <button class="lfu-collapse-trigger" type="button" data-target="lfu-advanced" aria-expanded="false">
              <span class="lfu-section-title mb-0">Advanced Settings</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="lfu-collapse-content" id="lfu-advanced" style="display:none;">
              <div class="lfu-divider"></div>
              <div class="lfu-toggle">
                <span class="lfu-toggle-label">High Contrast</span>
                <div class="lfu-toggle-switch"></div>
              </div>
              <div class="lfu-toggle">
                <span class="lfu-toggle-label">Ray Tracing</span>
                <div class="lfu-toggle-switch active"></div>
              </div>
            </div>
          </div>

          <!-- Generation History -->
          <div class="lfu-panel">
            <div class="lfu-panel-header">Generation History</div>
            <div class="lfu-history-item">
              <span>Today, 10:25 AM</span>
              <span class="lfu-badge completed">Completed</span>
            </div>
            <div class="lfu-divider"></div>
            <div class="lfu-history-item">
              <span>Yesterday, 11:40 PM</span>
              <span class="lfu-badge completed">Completed</span>
            </div>
            <div class="lfu-divider"></div>
            <div class="lfu-history-item">
              <span>Mar 20, 02:15 PM</span>
              <span class="lfu-badge processing">Processing</span>
            </div>
          </div>
        </div>
      </div>

      <footer class="text-center mt-5 pt-4 lfu-breadcrumb">
        <a href="/labs-text-to-image.php">← Back to current Text to Image</a>
      </footer>
    </div>
  </main>
</div>

<script src="/assets/js/labs-future-ui.js?v=<?php echo file_exists(__DIR__ . '/assets/js/labs-future-ui.js') ? filemtime(__DIR__ . '/assets/js/labs-future-ui.js') : time(); ?>"></script>
<script>
// Bootstrap collapse fallback (si no está cargado)
(function(){
  var btn = document.getElementById('lfu-generate-btn');
  if (btn) btn.addEventListener('click', function() {
    var r = document.getElementById('lfu-result');
    if (r) r.innerHTML = '<div class="lfu-result-placeholder"><i class="fas fa-spinner fa-spin"></i><p>Generating...</p></div>';
    setTimeout(function() {
      if (r) r.innerHTML = '<div class="lfu-result-placeholder"><i class="fas fa-wand-magic-sparkles"></i><p>Describe your vision below and generate</p></div>';
    }, 3000);
  });
})();
</script>
<?php echo generateFooter(); echo generateScripts(); ?>
