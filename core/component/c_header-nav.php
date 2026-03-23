<?php
$_is_logged_in = is_logged_in();
$_is_admin = is_admin();
$_display_name = $_SESSION['display_name'] ?? $_SESSION['username'] ?? '';
$_sso_base = $GLOBALS['SSO_BASE'] ?? 'https://sso.lightningworks.io';
$_sso_app = $GLOBALS['SSO_APP'] ?? 'siegeworlds';
$_sso_redirect = $GLOBALS['SSO_REDIRECT'] ?? '';
$_login_url = $_sso_base . '/login?app=' . urlencode($_sso_app) . '&redirect=' . urlencode($_sso_redirect);
?>

<header class="header">
    <div class="container">
        <div class="header-navigation" id="myMenu">
          <div class="menu-logo">
            <a href="index.php" class="header-brand">
              <img src="img/sw_logo_800px.webp" alt="Siege Worlds Logo" />
            </a>
          </div>
          <div class="menu-options">
            <div class="nav-stacked">
              <a href="leaderboards.php">Leaderboards</a>
              <a href="https://medium.com/siege-worlds/beginners-guide-to-playing-siege-worlds-1725cec0e3b3" target="_blank">How to Play</a>
            </div>
            <a class="buy-button" href="download.php">Download</a>
            <?php if (!$_is_logged_in): ?>
            <a class="buy-button button-green" href="<?php echo htmlspecialchars($_login_url); ?>">Create Account</a>
            <?php endif; ?>
            <a class="buy-button button-contest" href="contest.php">Contest</a>
            <?php if ($_is_admin): ?>
            <a class="buy-button button-admin" href="admin_dashboard.php">Admin</a>
            <?php endif; ?>
            <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleHamburger()" aria-label="Menu">&#9776;</button>
          </div>

          <!-- Hamburger overlay + slide-in panel -->
          <div class="hamburger-overlay" id="hamburgerOverlay" onclick="closeHamburger()"></div>
          <nav class="hamburger-menu" id="hamburgerMenu">
            <button class="hamburger-close" onclick="closeHamburger()" aria-label="Close menu">&times;</button>

            <?php if ($_is_logged_in): ?>
            <div class="hamburger-status hamburger-status-in">
                <div class="hamburger-user-row">
                    <?php render_avatar(32); ?>
                    <span>Logged in<?php echo $_display_name ? ' as ' . htmlspecialchars($_display_name) : ''; ?></span>
                </div>
            </div>
            <?php else: ?>
            <a href="<?php echo htmlspecialchars($_login_url); ?>" class="hamburger-item hamburger-primary">Log In</a>
            <div class="hamburger-divider"></div>
            <a href="<?php echo htmlspecialchars($_login_url); ?>" class="hamburger-item hamburger-primary">Create Account</a>
            <?php endif; ?>

            <div class="hamburger-divider"></div>
            <a href="download.php" class="hamburger-item">Download</a>
            <div class="hamburger-divider"></div>
            <a href="leaderboards.php" class="hamburger-item">Leaderboards</a>
            <div class="hamburger-divider"></div>
            <a href="contest.php" class="hamburger-item">Contest</a>
            <div class="hamburger-divider"></div>
            <a href="https://medium.com/siege-worlds/beginners-guide-to-playing-siege-worlds-1725cec0e3b3" target="_blank" class="hamburger-item">How to Play</a>

            <div class="hamburger-divider"></div>
            <div class="hamburger-group-label">Join Us</div>
            <a href="https://discord.gg/siegeworlds" target="_blank" rel="noopener noreferrer" class="hamburger-item hamburger-sub"><i class="fa-brands fa-discord"></i> Discord</a>
            <a href="https://t.me/siegeworlds" target="_blank" rel="noopener noreferrer" class="hamburger-item hamburger-sub"><i class="fa-brands fa-telegram"></i> Telegram</a>
            <a href="https://x.com/siege_worlds" target="_blank" rel="noopener noreferrer" class="hamburger-item hamburger-sub"><i class="fa-brands fa-x-twitter"></i> X</a>
            <a href="https://www.youtube.com/@SiegeWorlds" target="_blank" class="hamburger-item hamburger-sub"><i class="fa-brands fa-youtube"></i> YouTube</a>
            <a href="https://www.twitch.tv/directory/game/Siege%20Worlds" target="_blank" rel="noopener noreferrer" class="hamburger-item hamburger-sub"><i class="fa-brands fa-twitch"></i> Twitch</a>

            <?php if ($_is_admin): ?>
            <div class="hamburger-divider"></div>
            <a href="admin_dashboard.php" class="hamburger-item hamburger-admin">Admin</a>
            <?php endif; ?>

            <div class="hamburger-divider"></div>

            <?php if ($_is_logged_in): ?>
            <a href="auth/logout.php" class="hamburger-item hamburger-logout">Log Out</a>
            <?php else: ?>
            <span class="hamburger-item hamburger-logout hamburger-disabled">Log Out</span>
            <?php endif; ?>
          </nav>

          <a href="javascript:void(0);" class="toggle-menu" onclick="myMenu()">&#9776;</a>
        </div>
    </div>
</header>

<style>
/* Admin button in top nav */
header.header .header-navigation a.button-admin {
    background-color: #6a24fa;
    color: #fff;
}
header.header .header-navigation a.button-admin:hover {
    background-color: #7b3aff;
}

/* Hamburger button — inline in nav row */
.hamburger-btn {
    float: left;
    background: rgba(42, 41, 40, 0.9);
    border: 1px solid #3a3836;
    color: #fff;
    width: 40px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.3rem;
    padding: 10px 0;
    margin: 0 0 0 10px;
    transition: background 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    box-sizing: border-box;
}
.hamburger-btn:hover {
    background: rgba(106, 36, 250, 0.9);
}

/* Overlay */
.hamburger-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10001;
}
.hamburger-overlay.open {
    display: block;
}

/* Slide-in menu */
.hamburger-menu {
    position: fixed;
    top: 0;
    right: -300px;
    width: 280px;
    height: 100vh;
    background: #1a1918;
    border: 1px solid #6a24fa;
    border-right: none;
    z-index: 10002;
    overflow-y: auto;
    transition: right 0.3s ease;
    padding: 3.5rem 0 1rem;
    display: flex;
    flex-direction: column;
}
.hamburger-menu.open {
    right: 0;
}

/* Close button */
.hamburger-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    color: #bab1a8;
    font-size: 2rem;
    cursor: pointer;
    line-height: 1;
    padding: 4px 10px;
}
.hamburger-close:hover {
    color: #fff;
}

/* Status line */
.hamburger-status {
    display: block;
    padding: 0.75rem 1.5rem;
    font-family: "Bebas Neue", sans-serif;
    font-size: 1.2rem;
    text-transform: uppercase;
}
.hamburger-status-in {
    color: #6a24fa;
}
.hamburger-user-row {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

/* Menu items */
.hamburger-item {
    display: block;
    padding: 0.75rem 1.5rem;
    color: #bab1a8;
    text-decoration: none;
    font-family: "Bebas Neue", sans-serif;
    font-size: 1.2rem;
    text-transform: uppercase;
    transition: background 0.15s, color 0.15s;
    white-space: nowrap;
}
.hamburger-item:hover {
    background: #2a2928;
    color: #fff;
}
.hamburger-item i {
    width: 24px;
    text-align: center;
    margin-right: 0.5rem;
}

/* Group label (Join Us) */
.hamburger-group-label {
    padding: 0.75rem 1.5rem 0.25rem;
    font-family: "Bebas Neue", sans-serif;
    font-size: 1.1rem;
    text-transform: uppercase;
    color: #fff;
    letter-spacing: 0.05em;
}

/* Sub-items indented under group label */
.hamburger-sub {
    padding-left: 2.5rem;
    font-size: 1.1rem;
}

/* Primary items (Log In, Create Account) */
.hamburger-primary {
    color: #fff;
    font-size: 1.3rem;
}

/* Admin item */
.hamburger-admin {
    color: #6a24fa;
}
.hamburger-admin:hover {
    background: #6a24fa;
    color: #fff;
}

/* Logout */
.hamburger-logout {
    color: #CD412B;
}
.hamburger-logout:hover {
    background: #CD412B;
    color: #fff;
}

/* Disabled state */
.hamburger-disabled {
    color: #555;
    cursor: default;
    pointer-events: none;
}
.hamburger-disabled:hover {
    background: none;
    color: #555;
}

/* Divider */
.hamburger-divider {
    height: 1px;
    background: #3a3836;
    margin: 0 1.5rem;
    flex-shrink: 0;
}
</style>

<script>
function toggleHamburger() {
    document.getElementById('hamburgerMenu').classList.toggle('open');
    document.getElementById('hamburgerOverlay').classList.toggle('open');
}
function closeHamburger() {
    document.getElementById('hamburgerMenu').classList.remove('open');
    document.getElementById('hamburgerOverlay').classList.remove('open');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeHamburger();
});
</script>
