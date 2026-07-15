<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require_once(__DIR__ . '/../core/core.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$accessToken = $input['access_token'] ?? '';
$refreshToken = $input['refresh_token'] ?? '';

if (!$accessToken) {
    echo json_encode(['success' => false, 'error' => 'No token']);
    exit;
}

// Verify token with LightningWorks SSO
$ssoBase = $GLOBALS['SSO_BASE'];
$ch = curl_init($ssoBase . '/api/verify');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(['token' => $accessToken]),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode !== 200) {
    echo json_encode(['success' => false, 'error' => 'Token verification failed']);
    exit;
}

$data = json_decode($response, true);

// Debug: log raw SSO response
file_put_contents(__DIR__ . '/sso_debug.json', json_encode($data, JSON_PRETTY_PRINT));

if (empty($data['valid']) || empty($data['user'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid token']);
    exit;
}

$user = $data['user'];

// Store in PHP session
$_SESSION['logged_in'] = true;
$_SESSION['sso_user_id'] = $user['id'];
$_SESSION['sso_email'] = $user['email'] ?? '';
$_SESSION['username'] = $user['username'] ?? '';
$_SESSION['display_name'] = $user['display_name'] ?? $user['username'] ?? '';
$_SESSION['sso_role'] = $user['role'] ?? 'user';
$_SESSION['avatar_url'] = $user['avatar_url'] ?? '';
$_SESSION['avatar_outer_color'] = $user['avatar_outer_color'] ?? '#000000';
$_SESSION['avatar_inner_color'] = $user['avatar_inner_color'] ?? '#000000';
$_SESSION['avatar_pan_x'] = $user['avatar_pan_x'] ?? 0.5;
$_SESSION['avatar_pan_y'] = $user['avatar_pan_y'] ?? 0.5;
$_SESSION['avatar_zoom'] = $user['avatar_zoom'] ?? 1.0;
$_SESSION['sso_access_token'] = $accessToken;
$_SESSION['sso_refresh_token'] = $refreshToken;

// Set role flags for nav/admin checks
$role = $user['role'] ?? 'user';
$_SESSION['roles'] = [$role];
if ($role === 'admin' || $role === 'superadmin') {
    $_SESSION['admin_logged_in'] = true;
}

echo json_encode(['success' => true]);
