<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

require_login();

$code = strtoupper(trim($_GET['code'] ?? ''));
if (!preg_match('/^[A-Z0-9]{8}$/', $code)) {
    header('Location: /death-roll-lobby.php');
    exit;
}

$csrfToken = csrf_token();
?>

<?php echo generateHeader(t('dr.game.title', 'Death Roll 1v1') . ' — ' . $code, t('meta.default_description')); ?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height: 100vh; padding-top: 120px; padding-bottom: 60px;">
    <div class="container">
        <!-- Game Header -->
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h2 class="glow-text mb-1">
                            <i class="fas fa-dice-d20 me-2"></i>
                            <?php echo t('dr.game.room', 'Room'); ?>
                            <span class="ms-2" style="letter-spacing:3px; font-family: monospace;"><?php echo $code; ?></span>
                        </h2>
                        <p class="text-white-50 mb-0" id="game-status-text"><?php echo t('dr.game.loading', 'Loading game...'); ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-light" id="btn-copy-link" title="<?php echo t('dr.game.copy_link', 'Copy invite link'); ?>">
                            <i class="fas fa-link me-1"></i><?php echo t('dr.game.share', 'Share'); ?>
                        </button>
                        <a href="/death-roll-lobby.php" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i><?php echo t('dr.game.back_lobby', 'Lobby'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Game Area -->
            <div class="col-lg-8">
                <div class="glass-card-neon p-4">
                    <!-- Players Bar -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded text-center" id="player1-card" style="background: rgba(37,156,174,0.1); border: 1px solid rgba(37,156,174,0.3);">
                                <div class="small text-white-50">Player 1</div>
                                <div class="fw-bold" id="p1-name">—</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded text-center" id="player2-card" style="background: rgba(174,37,101,0.1); border: 1px solid rgba(174,37,101,0.3);">
                                <div class="small text-white-50">Player 2</div>
                                <div class="fw-bold" id="p2-name"><?php echo t('dr.game.waiting_opponent', 'Waiting...'); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Max Display -->
                    <div class="text-center mb-4">
                        <div class="small text-white-50 mb-1"><?php echo t('dr.game.current_max', 'Current Max'); ?></div>
                        <div id="current-max-display" style="font-size: 4rem; font-weight: 900; line-height: 1; font-family: 'Orbitron', monospace; color: var(--knd-neon-blue);">1000</div>
                    </div>

                    <!-- Roll Button -->
                    <div class="text-center mb-4">
                        <div id="turn-info" class="mb-2 text-white-50"></div>
                        <button id="btn-roll" class="btn btn-lg btn-neon-primary px-5 py-3" disabled style="font-size: 1.3rem;">
                            <i class="fas fa-dice me-2"></i><?php echo t('dr.game.roll', 'ROLL!'); ?>
                        </button>
                    </div>

                    <!-- Last Roll Animation -->
                    <div id="last-roll-display" class="text-center mb-3" style="display:none;">
                        <div class="small text-white-50" id="last-roll-who"></div>
                        <div id="last-roll-value" style="font-size: 3rem; font-weight: 900; font-family: 'Orbitron', monospace;"></div>
                    </div>

                    <!-- Game Over -->
                    <div id="game-over-panel" style="display:none;" class="text-center p-4 rounded" >
                        <div id="game-over-icon" style="font-size: 4rem;"></div>
                        <h3 id="game-over-text" class="mt-2"></h3>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <button id="btn-rematch" class="btn btn-neon-primary">
                                <i class="fas fa-redo me-2"></i><?php echo t('dr.game.rematch', 'Rematch'); ?>
                            </button>
                            <a href="/death-roll-lobby.php" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i><?php echo t('dr.game.back_lobby', 'Lobby'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roll History -->
            <div class="col-lg-4">
                <div class="glass-card-neon p-4">
                    <h5 class="mb-3"><i class="fas fa-history me-2" style="color: var(--knd-neon-blue);"></i><?php echo t('dr.game.history', 'Roll History'); ?></h5>
                    <div id="rolls-list" style="max-height: 500px; overflow-y: auto;">
                        <p class="text-white-50 small"><?php echo t('dr.game.no_rolls', 'No rolls yet.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/assets/js/navigation-extend.js"></script>

<?php echo generateFooter(); ?>

<script>
const GAME_CODE = <?php echo json_encode($code); ?>;
const CSRF = <?php echo json_encode($csrfToken); ?>;
const MY_USER_ID = <?php echo json_encode(current_user_id()); ?>;
const MY_USERNAME = <?php echo json_encode(current_username()); ?>;
const TEXTS = {
    yourTurn:      <?php echo json_encode(t('dr.game.your_turn', 'Your turn! Roll the dice!')); ?>,
    opponentTurn:  <?php echo json_encode(t('dr.game.opponent_turn', "Waiting for opponent's roll...")); ?>,
    waitingP2:     <?php echo json_encode(t('dr.game.waiting_opponent', 'Waiting for opponent...')); ?>,
    youWin:        <?php echo json_encode(t('dr.game.you_win', 'YOU WIN!')); ?>,
    youLose:       <?php echo json_encode(t('dr.game.you_lose', 'YOU LOSE!')); ?>,
    rolled:        <?php echo json_encode(t('dr.game.rolled', 'rolled')); ?>,
    outOf:         <?php echo json_encode(t('dr.game.out_of', 'out of')); ?>,
    copied:        <?php echo json_encode(t('dr.game.link_copied', 'Link copied!')); ?>,
    playing:       <?php echo json_encode(t('dr.game.status_playing', 'Game in progress')); ?>,
    waiting:       <?php echo json_encode(t('dr.game.status_waiting', 'Waiting for opponent')); ?>,
    finished:      <?php echo json_encode(t('dr.game.status_finished', 'Game over')); ?>,
};
</script>
<script src="/assets/js/deathroll-1v1.js" defer></script>

<?php echo generateScripts(); ?>
