<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<?php
$charFile = __DIR__ . '/../database/characters.json';
$trainingDir = __DIR__ . '/../database/training/';

if (!is_dir($trainingDir)) {
    mkdir($trainingDir, 0755, true);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['char_action'])) {
    $characters = json_decode(file_get_contents($charFile), true) ?: [];

    if ($_POST['char_action'] === 'save') {
        $id = preg_replace('/[^a-z0-9_-]/', '', strtolower($_POST['char_id'] ?? ''));
        if ($id) {
            $found = false;
            foreach ($characters as &$c) {
                if ($c['id'] === $id) {
                    $c['name'] = $_POST['char_name'] ?? $c['name'];
                    $c['api_key'] = $_POST['char_api_key'] ?? $c['api_key'];
                    $c['image'] = $_POST['char_image'] ?? $c['image'];
                    $c['intro'] = $_POST['char_intro'] ?? $c['intro'];
                    $c['enabled'] = isset($_POST['char_enabled']);
                    $found = true;
                    break;
                }
            }
            unset($c);
            if (!$found) {
                $characters[] = [
                    'id' => $id,
                    'name' => $_POST['char_name'] ?? $id,
                    'api_key' => $_POST['char_api_key'] ?? '',
                    'image' => $_POST['char_image'] ?? '',
                    'intro' => $_POST['char_intro'] ?? '',
                    'enabled' => isset($_POST['char_enabled'])
                ];
            }
            file_put_contents($charFile, json_encode($characters, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            echo '<div class="alert alert-success">Character "' . htmlspecialchars($_POST['char_name']) . '" saved.</div>';
        }
    }

    if ($_POST['char_action'] === 'delete') {
        $id = $_POST['char_id'] ?? '';
        $characters = array_values(array_filter($characters, fn($c) => $c['id'] !== $id));
        file_put_contents($charFile, json_encode($characters, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo '<div class="alert alert-warning">Character deleted.</div>';
    }

    if ($_POST['char_action'] === 'upload_training') {
        $id = preg_replace('/[^a-z0-9_-]/', '', strtolower($_POST['char_id'] ?? ''));
        if ($id && isset($_FILES['training_file']) && $_FILES['training_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['training_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['txt', 'md', 'json', 'csv'];
            if (in_array($ext, $allowed)) {
                $charTrainingDir = $trainingDir . $id . '/';
                if (!is_dir($charTrainingDir)) {
                    mkdir($charTrainingDir, 0755, true);
                }
                $filename = basename($_FILES['training_file']['name']);
                move_uploaded_file($_FILES['training_file']['tmp_name'], $charTrainingDir . $filename);
                echo '<div class="alert alert-success">Training file "' . htmlspecialchars($filename) . '" uploaded for ' . htmlspecialchars($id) . '.</div>';
            } else {
                echo '<div class="alert alert-danger">Invalid file type. Allowed: txt, md, json, csv</div>';
            }
        }
    }

    if ($_POST['char_action'] === 'send_training') {
        $id = preg_replace('/[^a-z0-9_-]/', '', strtolower($_POST['char_id'] ?? ''));
        $charTrainingDir = $trainingDir . $id . '/';
        $charData = null;
        foreach ($characters as $c) {
            if ($c['id'] === $id) { $charData = $c; break; }
        }
        if ($charData && $charData['api_key'] && is_dir($charTrainingDir)) {
            $files = glob($charTrainingDir . '*');
            $allContent = '';
            foreach ($files as $f) {
                $allContent .= "--- " . basename($f) . " ---\n" . file_get_contents($f) . "\n\n";
            }
            if ($allContent) {
                $ch = curl_init('https://kabdqrzcewkzbjmeqmxx.supabase.co/functions/v1/public-ingest-knowledge');
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $charData['api_key']
                    ],
                    CURLOPT_POSTFIELDS => json_encode([
                        'content' => $allContent,
                        'source' => 'admin-upload'
                    ])
                ]);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($httpCode >= 200 && $httpCode < 300) {
                    echo '<div class="alert alert-success">Training data sent to Kinetik for ' . htmlspecialchars($charData['name']) . '.</div>';
                } else {
                    echo '<div class="alert alert-danger">Kinetik API error (HTTP ' . $httpCode . '): ' . htmlspecialchars($response) . '</div>';
                }
            } else {
                echo '<div class="alert alert-warning">No training files found for this character.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Missing API key or training files.</div>';
        }
    }
}

$characters = json_decode(file_get_contents($charFile), true) ?: [];
?>

<style>
    .char-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .char-card h4 {
        margin-top: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .char-card .badge-enabled {
        background: #28a745;
        color: #fff;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
    .char-card .badge-disabled {
        background: #6c757d;
        color: #fff;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
    .char-card img.char-preview {
        max-height: 120px;
        border-radius: 6px;
        margin-bottom: 0.5rem;
    }
    .api-key-field {
        font-family: monospace;
        font-size: 0.85rem;
    }
    .training-files {
        list-style: none;
        padding: 0;
        margin: 0.5rem 0;
    }
    .training-files li {
        padding: 4px 0;
        font-size: 0.9rem;
        color: #495057;
    }
    .section-divider {
        border-top: 2px solid #dee2e6;
        margin: 2rem 0;
    }
</style>

<h3>Character APIs</h3>
<p class="text-muted">Manage AI chat characters. API keys are stored server-side and never exposed to visitors.</p>

<?php foreach ($characters as $char): ?>
<div class="char-card">
    <h4>
        <?php echo htmlspecialchars($char['name']); ?>
        <span class="<?php echo $char['enabled'] ? 'badge-enabled' : 'badge-disabled'; ?>">
            <?php echo $char['enabled'] ? 'Enabled' : 'Disabled'; ?>
        </span>
    </h4>

    <?php if ($char['image']): ?>
        <img src="<?php echo htmlspecialchars($char['image']); ?>" alt="<?php echo htmlspecialchars($char['name']); ?>" class="char-preview" />
    <?php endif; ?>

    <form method="post" action="admin_dashboard.php?form=characterapis">
        <input type="hidden" name="char_action" value="save" />
        <input type="hidden" name="char_id" value="<?php echo htmlspecialchars($char['id']); ?>" />

        <div class="form-group">
            <label>Display Name</label>
            <input type="text" name="char_name" class="form-control" value="<?php echo htmlspecialchars($char['name']); ?>" />
        </div>
        <div class="form-group">
            <label>API Key</label>
            <input type="text" name="char_api_key" class="form-control api-key-field" value="<?php echo htmlspecialchars($char['api_key']); ?>" placeholder="kinet_..." />
        </div>
        <div class="form-group">
            <label>Character Image Path</label>
            <input type="text" name="char_image" class="form-control" value="<?php echo htmlspecialchars($char['image']); ?>" placeholder="/img/character.webp" />
        </div>
        <div class="form-group">
            <label>Chat Intro Text</label>
            <textarea name="char_intro" class="form-control" rows="2"><?php echo htmlspecialchars($char['intro']); ?></textarea>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="char_enabled" class="form-check-input" id="enabled_<?php echo $char['id']; ?>" <?php echo $char['enabled'] ? 'checked' : ''; ?> />
            <label class="form-check-label" for="enabled_<?php echo $char['id']; ?>">Enabled on website</label>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save Character</button>
    </form>

    <div class="section-divider"></div>

    <h5>Training Data</h5>
    <?php
    $charTrainingDir = $trainingDir . $char['id'] . '/';
    $trainingFiles = is_dir($charTrainingDir) ? glob($charTrainingDir . '*') : [];
    ?>
    <?php if ($trainingFiles): ?>
        <ul class="training-files">
            <?php foreach ($trainingFiles as $f): ?>
                <li>&#128196; <?php echo htmlspecialchars(basename($f)); ?> (<?php echo round(filesize($f) / 1024, 1); ?> KB)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted" style="font-size:0.9rem;">No training files uploaded yet.</p>
    <?php endif; ?>

    <form method="post" action="admin_dashboard.php?form=characterapis" enctype="multipart/form-data" class="mt-2">
        <input type="hidden" name="char_action" value="upload_training" />
        <input type="hidden" name="char_id" value="<?php echo htmlspecialchars($char['id']); ?>" />
        <div class="form-group">
            <label>Upload Training File (.txt, .md, .json, .csv)</label>
            <input type="file" name="training_file" class="form-control-file" accept=".txt,.md,.json,.csv" />
        </div>
        <button type="submit" class="btn btn-outline-secondary btn-sm">Upload File</button>
    </form>

    <form method="post" action="admin_dashboard.php?form=characterapis" class="mt-2">
        <input type="hidden" name="char_action" value="send_training" />
        <input type="hidden" name="char_id" value="<?php echo htmlspecialchars($char['id']); ?>" />
        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Send all training data to Kinetik API?')">
            Send Training to Kinetik
        </button>
    </form>

    <form method="post" action="admin_dashboard.php?form=characterapis" class="mt-3">
        <input type="hidden" name="char_action" value="delete" />
        <input type="hidden" name="char_id" value="<?php echo htmlspecialchars($char['id']); ?>" />
        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this character?')">Delete Character</button>
    </form>
</div>
<?php endforeach; ?>

<div class="section-divider"></div>

<h4>Add New Character</h4>
<form method="post" action="admin_dashboard.php?form=characterapis">
    <input type="hidden" name="char_action" value="save" />
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>ID (lowercase, no spaces)</label>
            <input type="text" name="char_id" class="form-control" placeholder="e.g. shiyang" required pattern="[a-z0-9_-]+" />
        </div>
        <div class="form-group col-md-3">
            <label>Display Name</label>
            <input type="text" name="char_name" class="form-control" placeholder="e.g. Shi Yang" required />
        </div>
        <div class="form-group col-md-6">
            <label>API Key</label>
            <input type="text" name="char_api_key" class="form-control api-key-field" placeholder="kinet_..." />
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label>Character Image Path</label>
            <input type="text" name="char_image" class="form-control" placeholder="/img/character.webp" />
        </div>
        <div class="form-group col-md-8">
            <label>Chat Intro Text</label>
            <input type="text" name="char_intro" class="form-control" placeholder="Ask this character about..." />
        </div>
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" name="char_enabled" class="form-check-input" id="enabled_new" />
        <label class="form-check-label" for="enabled_new">Enabled on website</label>
    </div>
    <button type="submit" class="btn btn-primary">Add Character</button>
</form>
