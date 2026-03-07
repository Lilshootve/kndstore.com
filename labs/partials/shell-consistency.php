<?php
/** Consistency System - stub for shell. Full form integration in Phase 2. */
$standaloneUrl = '/labs-consistency.php';
if (!empty($refJobId)) $standaloneUrl .= '?reference_job_id=' . (int)$refJobId . '&mode=' . urlencode($preloadMode ?? 'style');
?>
<div class="ln-editor-header">
  <h1 class="ln-editor-title"><?php echo t('labs.consistency.title', 'Consistency System'); ?></h1>
  <p class="ln-editor-subtitle">Lock style or character across multiple generations.</p>
</div>
<div class="ln-composer-wrap p-4 text-center">
  <p class="text-white-50 mb-3">Consistency is available in the full page with all options.</p>
  <a href="<?php echo htmlspecialchars($standaloneUrl); ?>" class="ln-cta"><i class="fas fa-lock me-2"></i>Open Consistency</a>
</div>
