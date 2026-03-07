<?php
/** Upscale tool content for Labs app shell. Full integration: use standalone page link until shell form is wired. */
$standaloneUrl = '/labs-upscale.php';
if (!empty($_GET['source_job_id'])) $standaloneUrl .= '?source_job_id=' . urlencode($_GET['source_job_id']);
?>
<div class="ln-editor-header">
  <h1 class="ln-editor-title"><?php echo t('ai.upscale.title', 'Upscale'); ?></h1>
  <p class="ln-editor-subtitle">Improve resolution and clarity. 2× or 4× upscaling.</p>
</div>
<div class="ln-composer-wrap p-4 text-center">
  <p class="text-white-50 mb-3">Upscale is available in the full page with all options.</p>
  <a href="<?php echo htmlspecialchars($standaloneUrl); ?>" class="ln-cta"><i class="fas fa-search-plus me-2"></i>Open Upscale</a>
</div>
