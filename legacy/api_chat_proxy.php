<?php
// Proxy for Kinetik chat API — keeps API keys server-side
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$charId = $_GET['character'] ?? '';
if (!preg_match('/^[a-z0-9_-]+$/', $charId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid character ID']);
    exit;
}

$charFile = __DIR__ . '/core/database/characters.json';
$characters = json_decode(file_get_contents($charFile), true) ?: [];

$charData = null;
foreach ($characters as $c) {
    if ($c['id'] === $charId && $c['enabled'] && $c['api_key']) {
        $charData = $c;
        break;
    }
}

if (!$charData) {
    http_response_code(404);
    echo json_encode(['error' => 'Character not found or not enabled']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing message']);
    exit;
}

$ch = curl_init('https://kabdqrzcewkzbjmeqmxx.supabase.co/functions/v1/public-chat');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $charData['api_key']
    ],
    CURLOPT_POSTFIELDS => json_encode($input)
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($httpCode);
echo $response;
