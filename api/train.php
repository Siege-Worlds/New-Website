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
$charId = $input['character_id'] ?? '';
$text = $input['text'] ?? '';

if (!$text) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing game info text']);
    exit;
}

// Look up admin API key from characters.json
$charFile = __DIR__ . '/../core/database/characters.json';
$characters = json_decode(file_get_contents($charFile), true) ?: [];
$adminKey = '';
foreach ($characters as $c) {
    if ($c['id'] === $charId) {
        $adminKey = $c['admin_api_key'] ?? '';
        break;
    }
}

if (!$adminKey) {
    http_response_code(400);
    echo json_encode(['error' => 'No Admin API Key configured for this character']);
    exit;
}

$ch = curl_init('https://kabdqrzcewkzbjmeqmxx.supabase.co/functions/v1/public-ingest-knowledge');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        'api_key' => $adminKey,
        'text' => $text,
        'source_label' => 'siegeworlds-' . $charId . '-game-info',
    ]),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 120,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$data = json_decode($response, true);

if ($httpCode === 200 && isset($data['success']) && $data['success']) {
    echo json_encode([
        'success' => true,
        'chunksProcessed' => $data['chunksProcessed'] ?? null,
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'error' => $data['error'] ?? "HTTP $httpCode",
    ]);
}
