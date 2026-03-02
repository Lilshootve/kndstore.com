<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/footer.php';

require_login();

$csrfToken = csrf_token();
$username = htmlspecialchars(current_username());
?>

<?php echo generateHeader(t('dr.lobby.title', 'Death Roll 1v1 - Lobby'), t('meta.default_description')); ?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height: 100vh; padding-top: 120px; padding-bottom: 60px;">
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="glow-text mb-1"><i class="fas fa-dice-d20 me-2"></i>Death Roll 1v1</h1>
                        <p class="text-white-50 mb-0">
                            <?php echo t('dr.lobby.welcome', 'Welcome'); ?>, <strong><?php echo $username; ?></strong>
                            &mdash; <span id="lobby-active-games">0</span> <?php echo t('dr.lobby.active_games', 'active games'); ?>
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-neon-primary" data-bs-toggle="modal" data-bs-target="#modal-create">
                            <i class="fas fa-plus me-1"></i><?php echo t('dr.lobby.create_room', 'Create Room'); ?>
                        </button>
                        <button class="btn btn-outline-neon" data-bs-toggle="modal" data-bs-target="#modal-join">
                            <i class="fas fa-door-open me-1"></i><?php echo t('dr.lobby.join_code', 'Join by Code'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Public Rooms -->
            <div class="col-lg-8">
                <div class="glass-card-neon p-4">
                    <h4 class="mb-3"><i class="fas fa-globe me-2" style="color: var(--knd-neon-blue);"></i><?php echo t('dr.lobby.public_rooms', 'Public Rooms'); ?></h4>
                    <div id="lobby-rooms" class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><?php echo t('dr.lobby.room_code', 'Code'); ?></th>
                                    <th><?php echo t('dr.lobby.creator', 'Creator'); ?></th>
                                    <th><?php echo t('dr.lobby.created', 'Created'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="rooms-tbody">
                                <tr><td colspan="4" class="text-center text-white-50"><?php echo t('dr.lobby.loading', 'Loading...'); ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Online Users -->
            <div class="col-lg-4">
                <div class="glass-card-neon p-4">
                    <h4 class="mb-3"><i class="fas fa-users me-2" style="color: var(--knd-electric-purple);"></i><?php echo t('dr.lobby.online', 'Online'); ?> <span id="online-count" class="badge bg-success ms-1">0</span></h4>
                    <ul id="online-list" class="list-unstyled mb-0" style="max-height: 400px; overflow-y: auto;">
                        <li class="text-white-50"><?php echo t('dr.lobby.loading', 'Loading...'); ?></li>
                    </ul>
                </div>

                <!-- Rules -->
                <div class="glass-card-neon p-4 mt-4">
                    <h5 class="mb-3"><i class="fas fa-scroll me-2" style="color: var(--knd-neon-blue);"></i><?php echo t('dr.lobby.rules_title', 'How to Play'); ?></h5>
                    <ol class="small text-white-50 mb-0 ps-3">
                        <li class="mb-1"><?php echo t('dr.lobby.rule1', 'Create or join a room'); ?></li>
                        <li class="mb-1"><?php echo t('dr.lobby.rule2', 'Player 1 rolls 1-1000'); ?></li>
                        <li class="mb-1"><?php echo t('dr.lobby.rule3', 'Players alternate rolling 1 to last result'); ?></li>
                        <li class="mb-1"><?php echo t('dr.lobby.rule4', 'Whoever rolls 1 loses!'); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Create Room Modal -->
<div class="modal fade" id="modal-create" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i><?php echo t('dr.lobby.create_room', 'Create Room'); ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-create-room">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('dr.lobby.visibility', 'Visibility'); ?></label>
                        <select name="visibility" class="form-select">
                            <option value="public"><?php echo t('dr.lobby.public', 'Public'); ?> — <?php echo t('dr.lobby.public_desc', 'visible in lobby'); ?></option>
                            <option value="private"><?php echo t('dr.lobby.private', 'Private'); ?> — <?php echo t('dr.lobby.private_desc', 'invite by code only'); ?></option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-neon-primary w-100">
                        <i class="fas fa-rocket me-2"></i><?php echo t('dr.lobby.create_go', 'Create & Wait'); ?>
                    </button>
                </form>
                <div id="create-result" class="mt-3" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Join by Code Modal -->
<div class="modal fade" id="modal-join" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-door-open me-2"></i><?php echo t('dr.lobby.join_code', 'Join by Code'); ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-join-code">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('dr.lobby.enter_code', 'Room Code'); ?></label>
                        <input type="text" name="code" class="form-control text-uppercase" required minlength="8" maxlength="8" pattern="[A-Za-z0-9]{8}" placeholder="ABCD1234" style="letter-spacing:3px; font-size:1.2rem; text-align:center;">
                    </div>
                    <button type="submit" class="btn btn-outline-neon w-100">
                        <i class="fas fa-sign-in-alt me-2"></i><?php echo t('dr.lobby.join_btn', 'Join Room'); ?>
                    </button>
                </form>
                <div id="join-alert" class="mt-3" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/navigation-extend.js"></script>

<?php echo generateFooter(); ?>

<script>
const CSRF = <?php echo json_encode($csrfToken); ?>;
const MY_USERNAME = <?php echo json_encode(current_username()); ?>;
</script>
<script src="/assets/js/deathroll-1v1.js?v=<?php echo filemtime(__DIR__ . '/assets/js/deathroll-1v1.js'); ?>" defer></script>

<?php echo generateScripts(); ?>
