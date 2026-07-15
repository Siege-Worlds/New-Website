<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require_once(__DIR__ . '/../core/core.php');
header('Content-Type: application/json');

if (!is_admin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$charId = $input['id'] ?? '';

if (!$charId || !preg_match('/^[a-z0-9_-]+$/', $charId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid character ID']);
    exit;
}

$charFile = __DIR__ . '/../core/database/characters.json';
$characters = json_decode(file_get_contents($charFile), true) ?: [];

$found = false;
foreach ($characters as &$c) {
    if ($c['id'] === $charId) {
        if (isset($input['name'])) $c['name'] = $input['name'];
        if (isset($input['chat_api_key'])) $c['chat_api_key'] = $input['chat_api_key'];
        if (isset($input['admin_api_key'])) $c['admin_api_key'] = $input['admin_api_key'];
        if (isset($input['intro'])) $c['intro'] = $input['intro'];
        if (isset($input['game_info'])) $c['game_info'] = $input['game_info'];
        if (isset($input['enabled'])) $c['enabled'] = (bool)$input['enabled'];
        $found = true;
        break;
    }
}
unset($c);

if (!$found) {
    if (!empty($input['_create'])) {
        $characters[] = [
            'id' => $charId,
            'name' => $input['name'] ?? $charId,
            'chat_api_key' => $input['chat_api_key'] ?? '',
            'admin_api_key' => $input['admin_api_key'] ?? '',
            'image' => '',
            'intro' => $input['intro'] ?? '',
            'game_info' => $input['game_info'] ?? '',
            'enabled' => (bool)($input['enabled'] ?? false)
        ];
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Character not found']);
        exit;
    }
}

file_put_contents($charFile, json_encode($characters, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo json_encode(['success' => true]);
