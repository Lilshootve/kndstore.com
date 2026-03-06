<?php
require_once __DIR__ . '/_init.php';
labs_perf_checkpoint('character_after_init');

require_once __DIR__ . '/../includes/comfyui.php';
labs_perf_checkpoint('character_after_comfyui');

$toolName = t('ai.character.title', 'Character Lab');
$jobType = 'character';
$historyJobs = [];
if ($pdo) {
    try {
        $historyJobs = comfyui_get_user_jobs($pdo, current_user_id(), 10);
    } catch (\Throwable $e) {
        $historyJobs = [];
    }
}
$historyJobs = array_filter($historyJobs, fn($j) => ($j['tool'] ?? '') === 'character');
labs_perf_checkpoint('character_after_history_fetch');

$aiCss = __DIR__ . '/../assets/css/ai-tools.css';
$labsCss = __DIR__ . '/../assets/css/knd-labs.css';
$extraCss = '<link rel="stylesheet" href="/assets/css/ai-tools.css?v=' . (file_exists($aiCss) ? filemtime($aiCss) : time()) . '">';
$extraCss .= '<link rel="stylesheet" href="/assets/css/knd-labs.css?v=' . (file_exists($labsCss) ? filemtime($labsCss) : time()) . '">';
labs_perf_checkpoint('character_before_header');
echo generateHeader(t('labs.tool_page_title', '{tool} | KND Labs', ['tool' => $toolName]), t('labs.tool_page_desc', 'Create with AI'), $extraCss);
?>
<div id="particles-bg"></div>
<?php echo generateNavigation(); ?>

<section class="labs-tool-section py-5">
  <div class="container">
    <?php labs_breadcrumb($toolName); ?>

    <div class="row mt-4">
      <div class="col-lg-5 order-lg-2 mb-4">
        <div class="glass-card-neon p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-white mb-0"><?php echo htmlspecialchars($toolName); ?></h4>
            <span class="ai-balance-badge" id="labs-balance"><i class="fas fa-coins me-1"></i><?php echo number_format($balance); ?> KP</span>
          </div>
          <p class="text-white-50 small" id="labs-cost-label"><?php echo t('labs.cost_label', 'Cost: 15 KP'); ?></p>

          <form id="labs-comfy-form" class="labs-form" method="post" action="#" onsubmit="return false;">
            <input type="hidden" name="tool" value="character">
            <div class="mb-3">
              <label class="form-label text-white-50"><?php echo t('ai.text2img.prompt'); ?></label>
              <textarea name="prompt" class="form-control bg-dark text-white" rows="3" maxlength="500" placeholder="Describe your character..."></textarea>
              <div class="form-text text-white-50 small"><?php echo t('labs.presets'); ?></div>
              <div class="d-flex flex-wrap gap-1 mt-2">
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Game character concept, fantasy armor, portrait"><?php echo t('labs.preset_game', 'Game concept'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Anime portrait, detailed face, soft lighting"><?php echo t('labs.preset_anime', 'Anime portrait'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Realistic character, studio lighting"><?php echo t('labs.preset_realistic', 'Realistic'); ?></button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label text-white-50"><?php echo t('labs.negative_prompt', 'Negative prompt'); ?></label>
              <input type="text" name="negative_prompt" class="form-control bg-dark text-white" maxlength="500" placeholder="ugly, blurry, deformed">
            </div>
            <div class="mb-3">
              <label class="form-label text-white-50 small"><?php echo t('labs.model', 'Model'); ?></label>
              <select name="model" class="form-select form-select-sm bg-dark text-white">
                <option value="dreamshaper" selected>DreamShaper XL</option>
                <option value="juggernaut">Juggernaut XL</option>
                <option value="realvis">RealVis XL</option>
              </select>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label text-white-50 small"><?php echo t('labs.seed', 'Seed'); ?></label>
                <input type="number" name="seed" class="form-control form-control-sm bg-dark text-white" placeholder="Random">
              </div>
              <div class="col-3">
                <label class="form-label text-white-50 small"><?php echo t('labs.steps', 'Steps'); ?></label>
                <input type="number" name="steps" class="form-control form-control-sm bg-dark text-white" value="25" min="10" max="50">
              </div>
              <div class="col-3">
                <label class="form-label text-white-50 small"><?php echo t('labs.cfg', 'CFG'); ?></label>
                <input type="number" name="cfg" class="form-control form-control-sm bg-dark text-white" value="7.5" min="1" max="20" step="0.5">
              </div>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label text-white-50 small"><?php echo t('labs.width', 'Width'); ?></label>
                <select name="width" class="form-select form-select-sm bg-dark text-white">
                  <option value="512">512</option>
                  <option value="768">768</option>
                  <option value="1024" selected>1024</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label text-white-50 small"><?php echo t('labs.height', 'Height'); ?></label>
                <select name="height" class="form-select form-select-sm bg-dark text-white">
                  <option value="512">512</option>
                  <option value="768">768</option>
                  <option value="1024" selected>1024</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-neon-primary w-100" id="labs-submit-btn">
              <i class="fas fa-user-plus me-2"></i><?php echo t('ai.character.create'); ?>
            </button>
          </form>
        </div>
      </div>

      <div class="col-lg-7 order-lg-1">
        <div class="glass-card-neon p-4">
          <h5 class="text-white mb-3"><?php echo t('labs.result_area', 'Result'); ?></h5>
          <div id="labs-result-preview" class="labs-result-preview text-center py-5">
            <i class="fas fa-user-astronaut fa-3x text-white-50 mb-3"></i>
            <p class="text-white-50 mb-0"><?php echo t('labs.no_result_yet', 'Submit to generate'); ?></p>
          </div>
          <div id="labs-result-actions" class="mt-3" style="display:none;">
            <a href="#" id="labs-download-btn" class="btn btn-success me-2" download><i class="fas fa-download me-1"></i><?php echo t('ai.download'); ?></a>
            <button type="button" id="labs-retry-btn" class="btn btn-outline-primary"><i class="fas fa-redo me-1"></i><?php echo t('labs.generate_again', 'Generate again'); ?></button>
          </div>
          <div id="labs-status-panel" class="mt-3" style="display:none;">
            <div class="d-flex align-items-center"><div class="ai-spinner me-2"><i class="fas fa-cog fa-spin"></i></div><span id="labs-status-text"><?php echo t('ai.status.processing'); ?></span></div>
          </div>
          <div id="labs-error-msg" class="alert alert-danger mt-3" style="display:none;"></div>
        </div>

        <?php if (!empty($historyJobs)): ?>
        <div class="glass-card-neon p-4 mt-4">
          <h6 class="text-white mb-3"><?php echo t('labs.tool_history', 'Recent'); ?></h6>
          <ul class="list-unstyled mb-0">
            <?php foreach (array_slice($historyJobs, 0, 5) as $j): ?>
            <li class="d-flex align-items-center justify-content-between py-2 border-bottom border-secondary flex-wrap gap-2">
              <span class="text-white-50 small"><?php echo date('M j, H:i', strtotime($j['created_at'])); ?></span>
              <span class="badge bg-<?php echo ($j['status'] ?? '') === 'done' ? 'success' : (($j['status'] ?? '') === 'failed' ? 'danger' : 'warning'); ?>"><?php echo htmlspecialchars($j['status'] ?? 'pending'); ?></span>
              <?php if (!empty($j['image_url'])): ?>
              <a href="<?php echo htmlspecialchars($j['image_url']); ?>" class="btn btn-sm btn-outline-success" target="_blank"><i class="fas fa-download"></i></a>
              <?php endif; ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>
<script src="/assets/js/kndlabs.js"></script>
<script>KNDLabs.init({ formId: 'labs-comfy-form', jobType: 'character', costLabelId: 'labs-cost-label', pricingKey: 'character', balanceEl: '#labs-balance' });</script>
<?php echo generateFooter(); echo generateScripts(); echo labs_perf_comment(); labs_perf_log(); ?>
