<?php
require_once __DIR__ . '/_init.php';

$toolName = t('ai.text2img.title', 'Text → Image');
$jobType = 'text2img';
$costMin = 3;
$costMax = 6;
$historyJobs = $pdo ? ai_get_jobs_by_type($pdo, current_user_id(), $jobType, 10) : [];

$aiCss = __DIR__ . '/../assets/css/ai-tools.css';
$labsCss = __DIR__ . '/../assets/css/knd-labs.css';
$extraCss = '<link rel="stylesheet" href="/assets/css/ai-tools.css?v=' . (file_exists($aiCss) ? filemtime($aiCss) : time()) . '">';
$extraCss .= '<link rel="stylesheet" href="/assets/css/knd-labs.css?v=' . (file_exists($labsCss) ? filemtime($labsCss) : time()) . '">';
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
            <span class="ai-balance-badge"><i class="fas fa-coins me-1"></i><?php echo number_format($balance); ?> KP</span>
          </div>
          <p class="text-white-50 small"><?php echo t('labs.cost_range', 'Cost: {min}–{max} KP', ['min' => $costMin, 'max' => $costMax]); ?></p>

          <form id="labs-t2i-form" class="labs-form">
            <input type="hidden" name="type" value="text2img">
            <div class="mb-3">
              <label class="form-label text-white-50"><?php echo t('ai.text2img.prompt'); ?></label>
              <textarea name="prompt" class="form-control bg-dark text-white" rows="3" maxlength="500" placeholder="Describe the image..."></textarea>
              <div class="form-text text-white-50 small"><?php echo t('labs.presets'); ?></div>
              <div class="d-flex flex-wrap gap-1 mt-2">
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Game character concept, fantasy armor, portrait"><?php echo t('labs.preset_game', 'Game concept'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Anime portrait, detailed face, soft lighting"><?php echo t('labs.preset_anime', 'Anime portrait'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Landscape, mountains, sunset, atmospheric"><?php echo t('labs.preset_landscape', 'Landscape'); ?></button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label text-white-50"><?php echo t('ai.text2img.mode_label', 'Quality'); ?></label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="mode" id="mode-standard" value="standard" checked>
                <label class="form-check-label text-white-50" for="mode-standard"><?php echo t('ai.text2img.mode_standard'); ?></label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="mode" id="mode-high" value="high">
                <label class="form-check-label text-white-50" for="mode-high"><?php echo t('ai.text2img.mode_high'); ?></label>
              </div>
            </div>
            <button type="submit" class="btn btn-neon-primary w-100" id="labs-submit-btn">
              <i class="fas fa-magic me-2"></i><?php echo t('ai.text2img.generate'); ?>
            </button>
          </form>
        </div>
      </div>

      <div class="col-lg-7 order-lg-1">
        <div class="glass-card-neon p-4">
          <h5 class="text-white mb-3"><?php echo t('labs.result_area', 'Result'); ?></h5>
          <div id="labs-result-preview" class="labs-result-preview text-center py-5">
            <i class="fas fa-image fa-3x text-white-50 mb-3"></i>
            <p class="text-white-50 mb-0"><?php echo t('labs.no_result_yet', 'Submit to generate'); ?></p>
          </div>
          <div id="labs-result-actions" class="mt-3" style="display:none;">
            <a href="#" id="labs-download-btn" class="btn btn-success me-2"><i class="fas fa-download me-1"></i><?php echo t('ai.download'); ?></a>
            <button type="button" id="labs-retry-btn" class="btn btn-outline-primary"><i class="fas fa-redo me-1"></i><?php echo t('labs.generate_again', 'Generate again'); ?></button>
          </div>
          <div id="labs-status-panel" class="mt-3" style="display:none;">
            <div class="d-flex align-items-center">
              <div class="ai-spinner me-2"><i class="fas fa-cog fa-spin"></i></div>
              <span id="labs-status-text"><?php echo t('ai.status.processing'); ?></span>
            </div>
          </div>
          <div id="labs-error-msg" class="alert alert-danger mt-3" style="display:none;"></div>
        </div>

        <?php if (!empty($historyJobs)): ?>
        <div class="glass-card-neon p-4 mt-4">
          <h6 class="text-white mb-3"><?php echo t('labs.tool_history', 'Recent'); ?></h6>
          <ul class="list-unstyled mb-0">
            <?php foreach (array_slice($historyJobs, 0, 5) as $j): ?>
            <li class="d-flex align-items-center justify-content-between py-2 border-bottom border-secondary">
              <span class="text-white-50 small"><?php echo date('M j, H:i', strtotime($j['created_at'])); ?></span>
              <span class="badge bg-<?php echo $j['status'] === 'completed' ? 'success' : ($j['status'] === 'failed' ? 'danger' : 'warning'); ?>"><?php echo $j['status']; ?></span>
              <?php if ($j['status'] === 'completed'): ?>
              <a href="/api/ai/download.php?job_id=<?php echo urlencode($j['job_uuid']); ?>" class="btn btn-sm btn-outline-success" target="_blank"><i class="fas fa-download"></i></a>
              <?php else: ?>
              <a href="/labs/job.php?job_id=<?php echo urlencode($j['job_uuid']); ?>" class="btn btn-sm btn-outline-secondary"><?php echo t('labs.view'); ?></a>
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
<script src="/assets/js/knd-labs.js"></script>
<script>
KNDLabs.init({ formId: 'labs-t2i-form', jobType: 'text2img', balanceEl: null });
</script>
<?php echo generateFooter(); echo generateScripts(); ?>
