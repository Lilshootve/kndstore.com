<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../includes/comfyui.php';

$toolName = t('ai.text2img.title', 'Text → Image');
$jobType = 'text2img';
$historyJobs = [];
$providerFilter = trim($_GET['provider'] ?? '');
if ($pdo) {
    try {
        $historyJobs = comfyui_get_user_jobs($pdo, current_user_id(), 12);
    } catch (\Throwable $e) {
        $historyJobs = [];
    }
}
$historyJobs = array_filter($historyJobs, fn($j) => ($j['tool'] ?? '') === 'text2img');
if ($providerFilter === 'local') {
    $historyJobs = array_filter($historyJobs, fn($j) => ($j['provider'] ?? '') !== 'runpod');
} elseif ($providerFilter === 'runpod') {
    $historyJobs = array_filter($historyJobs, fn($j) => ($j['provider'] ?? '') === 'runpod');
} elseif ($providerFilter === 'failed') {
    $historyJobs = array_filter($historyJobs, fn($j) => ($j['status'] ?? '') === 'failed');
}

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
            <span class="ai-balance-badge" id="labs-balance"><i class="fas fa-coins me-1"></i><?php echo number_format($balance); ?> KP</span>
          </div>
          <p class="text-white-50 small mb-1" id="labs-cost-label"><?php echo t('labs.cost_label', 'Cost: 3 KP'); ?></p>
          <p class="text-white-50 small mb-3" id="labs-balance-after"></p>

          <form id="labs-comfy-form" class="labs-form" method="post" action="#" onsubmit="return false;">
            <input type="hidden" name="tool" value="text2img">
            <div class="mb-3">
              <label class="form-label text-white-50"><?php echo t('ai.text2img.prompt'); ?></label>
              <textarea name="prompt" class="form-control bg-dark text-white" id="labs-prompt-input" rows="3" maxlength="500" placeholder="Describe the image..."></textarea>
              <div class="form-text text-white-50 small" id="labs-prompt-hint"></div>
              <div class="form-text text-white-50 small mt-1"><?php echo t('labs.presets'); ?></div>
              <div class="d-flex flex-wrap gap-1 mt-2">
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Game character concept, fantasy armor, portrait" data-negative="ugly, blurry, deformed" data-steps="25" data-cfg="7.5" data-sampler="dpmpp_2m" data-width="1024" data-height="1024"><?php echo t('labs.preset_game', 'Game concept'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Anime portrait, detailed face, soft lighting" data-negative="ugly, blurry, bad anatomy" data-steps="25" data-cfg="7.5" data-sampler="euler" data-width="1024" data-height="1024"><?php echo t('labs.preset_anime', 'Anime portrait'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-btn" data-prompt="Landscape, mountains, sunset, atmospheric" data-negative="ugly, blurry, people" data-steps="25" data-cfg="7" data-sampler="dpmpp_2m" data-width="1024" data-height="768"><?php echo t('labs.preset_landscape', 'Landscape'); ?></button>
              </div>
              <div class="form-text text-white-50 small mt-1" id="labs-preset-summary"></div>
            </div>
            <div class="mb-3">
              <label class="form-label text-white-50"><?php echo t('labs.negative_prompt', 'Negative prompt'); ?></label>
              <input type="text" name="negative_prompt" class="form-control bg-dark text-white" id="labs-negative-input" maxlength="500" placeholder="ugly, blurry, low quality">
              <div class="d-flex flex-wrap gap-1 mt-2">
                <button type="button" class="btn btn-outline-secondary btn-sm preset-neg-btn" data-value="ugly, blurry, low quality"><?php echo t('labs.neg_default', 'Default'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-neg-btn" data-value="text, watermark, signature"><?php echo t('labs.neg_text', 'No text'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-neg-btn" data-value="bad anatomy, extra limbs, mutated"><?php echo t('labs.neg_anatomy', 'Anatomy'); ?></button>
                <button type="button" class="btn btn-outline-secondary btn-sm preset-neg-btn" data-value="disfigured, deformed, cartoon"><?php echo t('labs.neg_realistic', 'Realistic'); ?></button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label text-white-50"><?php echo t('ai.text2img.mode_label', 'Quality'); ?></label>
              <select name="quality" id="labs-quality-select" class="form-select bg-dark text-white">
                <option value="standard" selected>Standard (3 KP)</option>
                <option value="high">High (6 KP)</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label text-white-50 small"><?php echo t('labs.model', 'Model'); ?></label>
              <select name="model" class="form-select form-select-sm bg-dark text-white">
                <option value="v1_5" selected>v1.5 Pruned EMA</option>
                <option value="sd_xl_base">SD XL Base 1.0</option>
                <option value="sd_xl_refiner">SD XL Refiner 1.0</option>
                <option value="cyberrealistic_final">CyberRealistic Final</option>
                <option value="flux_dev">Flux Dev</option>
                <option value="iniverseMixSFWNSFW">Pony Real Guofeng V5.1</option>
                <option value="juggernaut_ragnarok">Juggernaut XL Ragnarok</option>
                <option value="juggernaut_v8">Juggernaut XL v8</option>
                <option value="NSFW_master">NSFW Master</option>
                <option value="pornmaster_asian">PornMaster Asian SDXL</option>
                <option value="realisticVision">Realistic Vision V6.0</option>
                <option value="waiANINSFWPONY">PONY XL v1.30</option>
                <option value="waiNSFW_v120">Illustrious v1.20</option>
                <option value="waiNSFW_v150">Illustrious v1.50</option>
              </select>
            </div>
            <div class="mb-3">
              <button type="button" class="btn btn-link btn-sm text-white-50 p-0" id="labs-advanced-toggle" data-bs-toggle="collapse" data-bs-target="#labs-advanced">
                <i class="fas fa-chevron-down me-1"></i><?php echo t('labs.advanced', 'Advanced'); ?>
              </button>
            </div>
            <div class="collapse mb-3" id="labs-advanced">
            <div class="row g-2 mb-3">
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.seed', 'Seed'); ?></label>
                <input type="number" name="seed" class="form-control form-control-sm bg-dark text-white" placeholder="Random">
              </div>
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.steps', 'Steps'); ?></label>
                <input type="number" name="steps" class="form-control form-control-sm bg-dark text-white" value="20" min="1" max="100">
              </div>
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.cfg', 'CFG'); ?></label>
                <input type="number" name="cfg" class="form-control form-control-sm bg-dark text-white" value="7.5" min="1" max="30" step="0.5">
              </div>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.sampler', 'Sampler'); ?></label>
                <select name="sampler_name" class="form-select form-select-sm bg-dark text-white">
                  <option value="euler" selected>Euler</option>
                  <option value="euler_ancestral">Euler Ancestral</option>
                  <option value="heun">Heun</option>
                  <option value="dpm_2">DPM 2</option>
                  <option value="dpm_2_ancestral">DPM 2 Ancestral</option>
                  <option value="lms">LMS</option>
                  <option value="dpmpp_2m">DPM++ 2M</option>
                  <option value="dpmpp_2m_sde">DPM++ 2M SDE</option>
                  <option value="dpmpp_sde">DPM++ SDE</option>
                  <option value="ddim">DDIM</option>
                  <option value="lcm">LCM</option>
                  <option value="uni_pc">UniPC</option>
                </select>
              </div>
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.scheduler', 'Scheduler'); ?></label>
                <select name="scheduler" class="form-select form-select-sm bg-dark text-white">
                  <option value="normal" selected>Normal</option>
                  <option value="karras">Karras</option>
                  <option value="exponential">Exponential</option>
                  <option value="sgm_uniform">SGM Uniform</option>
                  <option value="simple">Simple</option>
                </select>
              </div>
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.denoise', 'Denoise'); ?></label>
                <input type="number" name="denoise" class="form-control form-control-sm bg-dark text-white" value="1" min="0.01" max="1" step="0.01">
              </div>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.width', 'Width'); ?></label>
                <select name="width" class="form-select form-select-sm bg-dark text-white">
                  <option value="256">256</option>
                  <option value="512">512</option>
                  <option value="768">768</option>
                  <option value="1024" selected>1024</option>
                  <option value="1152">1152</option>
                  <option value="1280">1280</option>
                  <option value="1536">1536</option>
                  <option value="2048">2048</option>
                </select>
              </div>
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.height', 'Height'); ?></label>
                <select name="height" class="form-select form-select-sm bg-dark text-white">
                  <option value="256">256</option>
                  <option value="512">512</option>
                  <option value="768">768</option>
                  <option value="1024" selected>1024</option>
                  <option value="1152">1152</option>
                  <option value="1280">1280</option>
                  <option value="1536">1536</option>
                  <option value="2048">2048</option>
                </select>
              </div>
              <div class="col-4">
                <label class="form-label text-white-50 small"><?php echo t('labs.batch', 'Batch'); ?></label>
                <select name="batch_size" class="form-select form-select-sm bg-dark text-white">
                  <option value="1" selected>1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
              </div>
            </div>
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" name="private_image" id="labs-private-check" class="form-check-input" value="1">
              <label for="labs-private-check" class="form-check-label text-white-50 small"><?php echo t('labs.private_toggle', 'Keep this generation private'); ?></label>
            </div>
            <p class="text-white-50 small mb-3" id="labs-microcopy-private"><?php echo t('labs.images_stored', 'Your images are private. Stored for 30 days.'); ?></p>
            <button type="submit" class="btn btn-neon-primary w-100" id="labs-submit-btn" disabled>
              <i class="fas fa-magic me-2"></i><?php echo t('ai.text2img.generate'); ?>
            </button>
          </form>
        </div>
      </div>

      <div class="col-lg-7 order-lg-1">
        <div class="glass-card-neon p-4">
          <h5 class="text-white mb-3"><?php echo t('labs.result_area', 'Result'); ?></h5>
          <div id="labs-result-preview" class="labs-result-preview text-center py-5">
            <div id="labs-placeholder-tips" class="labs-placeholder-tips">
              <i class="fas fa-lightbulb fa-2x text-white-50 mb-2"></i>
              <p class="text-white-50 mb-1 small"><?php echo t('labs.tip_prompt', 'Use 1 subject + 1 style + 1 lighting'); ?></p>
              <p class="text-white-50 mb-0 small"><?php echo t('labs.tip_example', 'e.g. "Warrior, oil painting, golden hour"'); ?></p>
            </div>
          </div>
          <div id="labs-result-actions" class="mt-3" style="display:none;">
            <a href="#" id="labs-download-btn" class="btn btn-success me-2" download><i class="fas fa-download me-1"></i><?php echo t('ai.download'); ?></a>
            <a href="/labs-upscale.php" id="labs-use-input-btn" class="btn btn-outline-primary me-2"><i class="fas fa-search-plus me-1"></i><?php echo t('labs.use_as_input', 'Use as input'); ?></a>
            <button type="button" id="labs-regenerate-btn" class="btn btn-outline-primary me-2"><i class="fas fa-redo me-1"></i><?php echo t('labs.regenerate', 'Regenerate'); ?></button>
            <button type="button" id="labs-variations-btn" class="btn btn-outline-secondary"><i class="fas fa-images me-1"></i><?php echo t('labs.variations', 'Variations'); ?></button>
          </div>
          <div id="labs-status-panel" class="mt-3" style="display:none;">
            <div class="labs-stepper mb-2">
              <span class="labs-stepper-dot" data-step="queued"></span>
              <span class="labs-stepper-line"></span>
              <span class="labs-stepper-dot" data-step="picked"></span>
              <span class="labs-stepper-line"></span>
              <span class="labs-stepper-dot" data-step="generating"></span>
              <span class="labs-stepper-line"></span>
              <span class="labs-stepper-dot" data-step="done"></span>
            </div>
            <p class="text-white-50 small mb-1"><?php echo t('labs.queued_leave', 'Generation is queued. You can leave this page.'); ?></p>
            <div class="d-flex align-items-center">
              <div class="ai-spinner me-2"><i class="fas fa-cog fa-spin"></i></div>
              <span id="labs-status-text"><?php echo t('ai.status.processing'); ?></span>
            </div>
          </div>
          <div id="labs-error-msg" class="alert alert-danger mt-3" style="display:none;"></div>
        </div>

        <?php if (!empty($historyJobs)): ?>
        <div class="glass-card-neon p-4 mt-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-white mb-0"><?php echo t('labs.tool_history', 'Recent'); ?></h6>
            <select id="labs-recent-filter" class="form-select form-select-sm bg-dark text-white" style="width:auto;">
              <option value="" <?php echo $providerFilter === '' ? 'selected' : ''; ?>><?php echo t('labs.all', 'All'); ?></option>
              <option value="local" <?php echo $providerFilter === 'local' ? 'selected' : ''; ?>>Local</option>
              <option value="runpod" <?php echo $providerFilter === 'runpod' ? 'selected' : ''; ?>>RunPod</option>
              <option value="failed" <?php echo $providerFilter === 'failed' ? 'selected' : ''; ?>><?php echo t('labs.failed', 'Failed'); ?></option>
            </select>
          </div>
          <ul class="list-unstyled mb-0" id="labs-recent-list">
            <?php foreach (array_slice($historyJobs, 0, 8) as $j):
              $jid = $j['id'] ?? 0;
              $imgUrl = !empty($j['image_url']) ? $j['image_url'] : '/api/labs/image.php?job_id=' . $jid;
              if (strpos($imgUrl, 'job_id=') === false && $jid) $imgUrl = '/api/labs/image.php?job_id=' . $jid;
            ?>
            <li class="labs-recent-item d-flex align-items-center justify-content-between py-2 border-bottom border-secondary flex-wrap gap-2" data-job-id="<?php echo (int)($j['id'] ?? 0); ?>" data-provider="<?php echo htmlspecialchars($j['provider'] ?? ''); ?>" data-status="<?php echo htmlspecialchars($j['status'] ?? ''); ?>">
              <span class="text-white-50 small"><?php echo date('M j, H:i', strtotime($j['created_at'])); ?></span>
              <span class="badge bg-<?php echo ($j['status'] ?? '') === 'done' ? 'success' : (($j['status'] ?? '') === 'failed' ? 'danger' : 'warning'); ?>"><?php echo htmlspecialchars($j['status'] ?? 'pending'); ?></span>
              <?php if (!empty($imgUrl) && ($j['status'] ?? '') === 'done'): ?>
              <div class="labs-recent-thumb" style="position:relative;">
                <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="" class="rounded" style="width:48px;height:48px;object-fit:cover;" onerror="this.parentElement.classList.add('labs-img-error')">
                <span class="labs-provider-offline" style="display:none;"><?php echo t('labs.provider_offline', 'Provider offline'); ?></span>
              </div>
              <a href="<?php echo htmlspecialchars($imgUrl); ?>" class="btn btn-sm btn-outline-success" target="_blank" download><i class="fas fa-download"></i></a>
              <?php endif; ?>
              <button type="button" class="btn btn-sm btn-outline-secondary labs-view-details" data-job-id="<?php echo (int)($j['id'] ?? 0); ?>"><?php echo t('labs.view_details', 'View details'); ?></button>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="labs-job-details-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark border border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title text-white"><?php echo t('labs.view_details', 'View details'); ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-white-50" id="labs-job-details-body"></div>
    </div>
  </div>
</div>

<script src="/assets/js/navigation-extend.js"></script>
<script src="/assets/js/kndlabs.js"></script>
<script>
KNDLabs.init({ formId: 'labs-comfy-form', jobType: 'text2img', costLabelId: 'labs-cost-label', pricingKey: 'text2img', qualitySelectId: 'labs-quality-select', balanceEl: '#labs-balance' });
</script>
<?php echo generateFooter(); echo generateScripts(); ?>
