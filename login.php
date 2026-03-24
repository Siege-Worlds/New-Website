<?php
require_once('core/core.php');

// If already logged in, go home
if (!empty($_SESSION['logged_in'])) {
    header('Location: /index.php');
    exit;
}

// Redirect to LightningWorks SSO
$ssoUrl = $GLOBALS['SSO_BASE'] . '/login?app=' . urlencode($GLOBALS['SSO_APP'])
    . '&redirect=' . urlencode($GLOBALS['SSO_REDIRECT']);
header('Location: ' . $ssoUrl);
exit;
