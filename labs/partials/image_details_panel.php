<?php
/**
 * Reusable Image Details panel for Labs tool pages.
 * Populated via JS when job data is available.
 */
?>
<div id="labs-image-details-panel" class="glass-card-neon p-3 mt-3" style="display:none;">
  <h6 class="text-white mb-2 small"><i class="fas fa-info-circle me-1"></i><?php echo t('labs.image_details', 'Image Details'); ?></h6>
  <div id="labs-image-details-body" class="small">
    <div class="row g-2" id="labs-details-rows"></div>
  </div>
  <div id="labs-image-details-actions" class="mt-2 d-flex flex-wrap gap-1"></div>
</div>
