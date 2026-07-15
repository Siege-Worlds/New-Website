<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require_once(__DIR__ . '/../core/core.php');
header('Content-Type: application/json');

if (!is_admin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

$charId = $_POST['character_id'] ?? '';
if (!$charId || !preg_match('/^[a-z0-9_-]+$/', $charId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid character ID']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES['image'];
$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($file['type'], $allowed)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type. Allowed: JPEG, PNG, WebP, GIF']);
    exit;
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename = $charId . '_side_img.' . $ext;
$destPath = __DIR__ . '/../img/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Upload failed']);
    exit;
}

$imgPath = '/img/' . $filename;

// Update characters.json with new image path
$charFile = __DIR__ . '/../core/database/characters.json';
$characters = json_decode(file_get_contents($charFile), true) ?: [];
foreach ($characters as &$c) {
    if ($c['id'] === $charId) {
        $c['image'] = $imgPath;
        break;
    }
}
unset($c);
file_put_contents($charFile, json_encode($characters, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo json_encode(['success' => true, 'path' => $imgPath]);
