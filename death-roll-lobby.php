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
require_once __DIR__ . '/includes/support_credits.php';

require_login();
require_verified_email();

$csrfToken = csrf_token();
$username = htmlspecialchars(current_username());

$myKpBalance = 0;
try {
    $pdoLobby = getDBConnection();
    if ($pdoLobby) {
        $myKpBalance = get_available_points($pdoLobby, current_user_id());
    }
} catch (\Throwable $e) { /* graceful */ }
?>

<?php
$seoTitle = 'KND LastRoll | Next-Gen Death Roll 1v1 — Lobby';
$seoDesc  = 'KND LastRoll is a next-gen Death Roll 1v1 game. Create public or private rooms, challenge opponents in real time, and roll down to 1.';
$seoUrl   = 'https://kndstore.com/death-roll-lobby.php';
$ogHead   = '    <meta property="og:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead  .= '    <meta property="og:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
$ogHead  .= '    <meta property="og:type" content="website">' . "\n";
$ogHead  .= '    <meta property="og:url" content="' . $seoUrl . '">' . "\n";
$ogHead  .= '    <meta name="twitter:card" content="summary_large_image">' . "\n";
$ogHead  .= '    <meta name="twitter:title" content="' . htmlspecialchars($seoTitle) . '">' . "\n";
$ogHead  .= '    <meta name="twitter:description" content="' . htmlspecialchars($seoDesc) . '">' . "\n";
echo generateHeader($seoTitle, $seoDesc, $ogHead);
?>

<div id="particles-bg"></div>

<?php echo generateNavigation(); ?>

<section class="hero-section" style="min-height: 100vh; padding-top: 120px; padding-bottom: 60px;">
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="glow-text mb-1"><i class="fas fa-dice-d20 me-2"></i>KND LastRoll</h1>
                        <p class="text-white-50 mb-0">
                            <?php echo t('dr.lobby.subtitle_seo', 'A next-gen Death Roll 1v1 experience'); ?>
                            &mdash; <?php echo t('dr.lobby.welcome', 'Welcome'); ?>, <strong><?php echo $username; ?></strong>
                            &mdash; <span id="lobby-active-games">0</span> <?php echo t('dr.lobby.active_games', 'active games'); ?>
                        </p>
                        <p class="mb-0 mt-1" style="font-size:.9rem;">
                            <span id="my-kp-balance" class="badge <?php echo $myKpBalance > 0 ? 'bg-success' : 'bg-secondary'; ?>" style="font-size:.85rem;">
                                <i class="fas fa-wallet me-1"></i>
                                Your KP: <strong><?php echo number_format($myKpBalance); ?></strong>
                            </span>
                        </p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-neon-primary" data-bs-toggle="modal" data-bs-target="#modal-create">
                            <i class="fas fa-plus me-1"></i><?php echo t('dr.lobby.create_room', 'Create Room'); ?>
                        </button>
                        <button class="btn btn-outline-neon" data-bs-toggle="modal" data-bs-target="#modal-join">
                            <i class="fas fa-door-open me-1"></i><?php echo t('dr.lobby.join_code', 'Join by Code'); ?>
                        </button>
                        <button class="btn btn-outline-light btn-myrooms" data-bs-toggle="modal" data-bs-target="#modal-myrooms" id="btn-myrooms">
                            <i class="fas fa-th-list me-1"></i><?php echo t('dr.lobby.my_rooms', 'My Rooms'); ?>
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
                                    <th>Max</th>
                                    <th>Entry</th>
                                    <th><?php echo t('dr.lobby.created', 'Created'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="rooms-tbody">
                                <tr><td colspan="6" class="text-center text-white-50"><?php echo t('dr.lobby.loading', 'Loading...'); ?></td></tr>
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
                    <p class="small text-white-50 mt-3 mb-0" style="opacity:0.5; font-style:italic;"><?php echo t('dr.lobby.inspired', 'Inspired by the classic Death Roll format.'); ?></p>
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
                    <div class="mb-3">
                        <label class="form-label"><?php echo t('dr.lobby.initial_max', 'Initial Max'); ?></label>
                        <select name="initial_max" class="form-select">
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                            <option value="1000" selected>1,000</option>
                            <option value="2500">2,500</option>
                            <option value="5000">5,000</option>
                            <option value="10000">10,000</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-coins me-1" style="color:var(--knd-neon-blue);"></i>Entry Fee (KP)</label>
                        <select name="entry_kp" id="create-entry-kp" class="form-select">
                            <option value="5">5 KP</option>
                            <option value="10">10 KP</option>
                            <option value="25">25 KP</option>
                            <option value="50">50 KP</option>
                            <option value="100" selected>100 KP</option>
                            <option value="200">200 KP</option>
                            <option value="500">500 KP</option>
                            <option value="1000">1,000 KP</option>
                        </select>
                        <div class="mt-2 small" style="color:var(--knd-neon-blue);">
                            <i class="fas fa-trophy me-1"></i>Winner gets: <strong id="create-payout-preview">150</strong> KP
                        </div>
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

<!-- My Rooms Modal -->
<div class="modal fade" id="modal-myrooms" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-th-list me-2"></i><?php echo t('dr.lobby.my_rooms', 'My Rooms'); ?> <span id="myrooms-count" class="badge bg-secondary ms-1"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                <div id="myrooms-list">
                    <p class="text-white-50 text-center"><?php echo t('dr.lobby.loading', 'Loading...'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/navigation-extend.js"></script>

<?php echo generateFooter(); ?>

<script>
const CSRF = <?php echo json_encode($csrfToken); ?>;
const MY_USERNAME = <?php echo json_encode(current_username()); ?>;
const MY_KP_BALANCE = <?php echo (int)$myKpBalance; ?>;
</script>
<script src="/assets/js/deathroll-1v1.js?v=<?php echo filemtime(__DIR__ . '/assets/js/deathroll-1v1.js'); ?>" defer></script>

<?php echo generateScripts(); ?>
