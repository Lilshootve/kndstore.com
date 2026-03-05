<?php
/**
 * Image→3D - Wrapper to triposr-3d.php (InstantMesh)
 */
require_once __DIR__ . '/_init.php';

$toolName = t('ai.img23d.link', 'Image → 3D');
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
    <div class="glass-card-neon p-4 mt-4 text-center">
      <p class="text-white-50 mb-4"><?php echo t('labs.img23d_redirect', 'Image→3D uses InstantMesh. You will be redirected.'); ?></p>
      <a href="/triposr-3d.php" class="btn btn-neon-primary btn-lg"><i class="fas fa-cube me-2"></i><?php echo t('triposr.upload.btn', 'Generate 3D Model'); ?></a>
    </div>
  </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>
<?php echo generateFooter(); echo generateScripts(); ?>
