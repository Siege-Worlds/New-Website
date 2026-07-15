<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>

<?php
$charFile = __DIR__ . '/../database/characters.json';
$characters = json_decode(file_get_contents($charFile), true) ?: [];
?>

<style>
    .char-panel {
        background: #2a2928;
        border: 1px solid #3a3836;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .char-panel h3 {
        margin: 0 0 1rem 0;
        color: #fff;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .char-panel h3 .badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        font-weight: 600;
    }
    .badge-on { background: #34A853; color: #fff; }
    .badge-off { background: #6c757d; color: #fff; }

    .char-top-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .char-img-col {
        flex: 0 0 240px;
    }
    .char-img-preview {
        width: 100%;
        max-height: 360px;
        object-fit: contain;
        border-radius: 8px;
        background: #1a1918;
        border: 1px solid #3a3836;
        display: block;
        margin-bottom: 0.75rem;
    }
    .char-img-col label {
        color: #bab1a8;
        font-size: 0.85rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .char-info-col {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }
    .char-info-col label {
        color: #bab1a8;
        font-size: 0.85rem;
        display: block;
        margin-bottom: 0.25rem;
    }
    .char-info-col .helper-text {
        color: #7a7572;
        font-size: 0.78rem;
        margin-bottom: 0.5rem;
    }
    .char-info-col textarea {
        flex: 1;
        min-height: 280px;
        resize: vertical;
        background: #1a1918;
        color: #bab1a8;
        border: 1px solid #3a3836;
        border-radius: 6px;
        padding: 0.75rem;
        font-size: 0.9rem;
        font-family: "Open Sans", sans-serif;
        line-height: 1.5;
    }
    .char-counter {
        text-align: right;
        font-size: 0.78rem;
        color: #7a7572;
        margin-top: 4px;
    }
    .char-counter.warn { color: #ff8800; }

    .char-keys-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .char-keys-row .key-field {
        flex: 1;
    }
    .char-keys-row label {
        color: #bab1a8;
        font-size: 0.85rem;
        display: block;
        margin-bottom: 0.25rem;
    }
    .char-keys-row .helper-text {
        color: #7a7572;
        font-size: 0.78rem;
        margin-bottom: 0.5rem;
    }
    .char-keys-row input {
        width: 100%;
        background: #1a1918;
        color: #bab1a8;
        border: 1px solid #3a3836;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-family: monospace;
        font-size: 0.85rem;
    }

    .char-meta-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .char-meta-row .meta-field {
        flex: 1;
    }
    .char-meta-row label {
        color: #bab1a8;
        font-size: 0.85rem;
        display: block;
        margin-bottom: 0.25rem;
    }
    .char-meta-row input, .char-meta-row textarea.intro-field {
        width: 100%;
        background: #1a1918;
        color: #bab1a8;
        border: 1px solid #3a3836;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        font-family: "Open Sans", sans-serif;
    }
    .char-meta-row textarea.intro-field {
        min-height: 60px;
        resize: vertical;
    }
    .char-meta-row .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-top: 1.2rem;
    }
    .char-meta-row .form-check input {
        width: 18px;
        height: 18px;
        accent-color: #6a24fa;
    }
    .char-meta-row .form-check label {
        margin: 0;
    }

    .char-btn-row {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
    }
    .char-btn-row .train-result {
        color: #bab1a8;
        font-size: 0.85rem;
        margin-right: auto;
    }
    .char-btn-row .train-result.error { color: #CD412B; }
    .char-btn-row .train-result.success { color: #34A853; }

    .btn-save {
        background: #6a24fa;
        color: #fff;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        min-width: 100px;
    }
    .btn-save:hover { background: #7b3aff; }
    .btn-save:disabled { opacity: 0.6; cursor: default; }
    .btn-save.saved { background: #34A853; }

    .btn-train {
        background: #ff8800;
        color: #fff;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        min-width: 100px;
    }
    .btn-train:hover { background: #ffaa44; }
    .btn-train:disabled { opacity: 0.6; cursor: default; }
    .btn-train.training { background: #3a3938; }
    .btn-train.trained { background: #34A853; }

    .btn-upload {
        background: #3a3836;
        color: #bab1a8;
        border: 1px solid #4c4946;
        padding: 0.4rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
    }
    .btn-upload:hover { background: #4c4946; color: #fff; }

    .char-divider {
        border: none;
        border-top: 1px solid #3a3836;
        margin: 2rem 0;
    }
</style>

<h3>Character Management</h3>
<p class="text-muted">Manage AI chat characters, API keys, and training data. Admin API Keys are kept server-side only.</p>

<?php foreach ($characters as $idx => $char): ?>
<div class="char-panel" id="panel-<?php echo $char['id']; ?>">
    <h3>
        <?php echo htmlspecialchars($char['name']); ?>
        <span class="badge <?php echo $char['enabled'] ? 'badge-on' : 'badge-off'; ?>">
            <?php echo $char['enabled'] ? 'Enabled' : 'Disabled'; ?>
        </span>
    </h3>

    <!-- Name, Intro, Enabled -->
    <div class="char-meta-row">
        <div class="meta-field">
            <label>Display Name</label>
            <input type="text" class="char-field" data-char="<?php echo $char['id']; ?>" data-field="name"
                   value="<?php echo htmlspecialchars($char['name']); ?>" />
        </div>
        <div class="meta-field" style="flex:2;">
            <label>Chat Intro Text</label>
            <textarea class="intro-field char-field" data-char="<?php echo $char['id']; ?>" data-field="intro"><?php echo htmlspecialchars($char['intro']); ?></textarea>
        </div>
        <div class="meta-field">
            <div class="form-check">
                <input type="checkbox" class="char-field" data-char="<?php echo $char['id']; ?>" data-field="enabled"
                       id="enabled-<?php echo $char['id']; ?>"
                       <?php echo $char['enabled'] ? 'checked' : ''; ?> />
                <label for="enabled-<?php echo $char['id']; ?>">Enabled on website</label>
            </div>
        </div>
    </div>

    <!-- Image (left) + Game Info (right) -->
    <div class="char-top-row">
        <div class="char-img-col">
            <label>Character Side Image</label>
            <?php if ($char['image']): ?>
            <img src="<?php echo htmlspecialchars($char['image']); ?>" class="char-img-preview" id="img-preview-<?php echo $char['id']; ?>" />
            <?php else: ?>
            <div class="char-img-preview" id="img-preview-<?php echo $char['id']; ?>" style="height:200px;display:flex;align-items:center;justify-content:center;color:#7a7572;">No image</div>
            <?php endif; ?>
            <input type="file" id="img-input-<?php echo $char['id']; ?>" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;"
                   onchange="uploadCharImage('<?php echo $char['id']; ?>')" />
            <button class="btn-upload" onclick="document.getElementById('img-input-<?php echo $char['id']; ?>').click()">
                <?php echo $char['image'] ? 'Replace Image' : 'Upload Image'; ?>
            </button>
        </div>
        <div class="char-info-col">
            <label>Game Info (RAG Training Data)</label>
            <div class="helper-text">Paste all information you want this character to know about Siege Worlds. This will be sent to Kinet.ink for training.</div>
            <textarea class="char-field" data-char="<?php echo $char['id']; ?>" data-field="game_info"
                      id="gameinfo-<?php echo $char['id']; ?>" maxlength="500000"
                      placeholder="Paste game info, lore, mechanics, FAQ, etc..."><?php echo htmlspecialchars($char['game_info'] ?? ''); ?></textarea>
            <div class="char-counter" id="counter-<?php echo $char['id']; ?>">0 / 500,000</div>
        </div>
    </div>

    <!-- API Keys -->
    <div class="char-keys-row">
        <div class="key-field">
            <label>Chat API Key</label>
            <div class="helper-text">Embedded in iframe URL — safe to expose client-side</div>
            <input type="text" class="char-field" data-char="<?php echo $char['id']; ?>" data-field="chat_api_key"
                   value="<?php echo htmlspecialchars($char['chat_api_key'] ?? ''); ?>" placeholder="kinet_..." />
        </div>
        <div class="key-field">
            <label>Admin API Key</label>
            <div class="helper-text">Server-side only — used for RAG training. Never exposed to browsers.</div>
            <input type="text" class="char-field" data-char="<?php echo $char['id']; ?>" data-field="admin_api_key"
                   value="<?php echo htmlspecialchars($char['admin_api_key'] ?? ''); ?>" placeholder="kinet_..." />
        </div>
    </div>

    <!-- Buttons -->
    <div class="char-btn-row">
        <span class="train-result" id="train-result-<?php echo $char['id']; ?>"></span>
        <button class="btn-train" id="train-btn-<?php echo $char['id']; ?>"
                onclick="trainCharacter('<?php echo $char['id']; ?>')">Train</button>
        <button class="btn-save" id="save-btn-<?php echo $char['id']; ?>"
                onclick="saveCharacter('<?php echo $char['id']; ?>')">Save</button>
    </div>
</div>
<?php endforeach; ?>

<!-- Add New Character -->
<hr class="char-divider" />
<div class="char-panel">
    <h3>Add New Character</h3>
    <div class="char-meta-row">
        <div class="meta-field">
            <label>ID (lowercase, no spaces)</label>
            <input type="text" id="new-char-id" placeholder="e.g. shiyang" pattern="[a-z0-9_-]+" />
        </div>
        <div class="meta-field">
            <label>Display Name</label>
            <input type="text" id="new-char-name" placeholder="e.g. Shi Yang" />
        </div>
        <div class="meta-field">
            <div class="form-check" style="padding-top:1.2rem;">
                <button class="btn-save" onclick="addCharacter()">Add Character</button>
            </div>
        </div>
    </div>
</div>

<script>
// Update character counters on load
document.querySelectorAll('textarea[data-field="game_info"]').forEach(function(ta) {
    updateCounter(ta.dataset.char);
    ta.addEventListener('input', function() { updateCounter(this.dataset.char); });
});

// Reset buttons when any field is edited
document.querySelectorAll('.char-field').forEach(function(el) {
    var evt = el.tagName === 'TEXTAREA' || el.type === 'text' ? 'input' : 'change';
    el.addEventListener(evt, function() {
        var id = this.dataset.char;
        resetSaveBtn(id);
        if (this.dataset.field === 'game_info') resetTrainBtn(id);
    });
});

function updateCounter(charId) {
    var ta = document.getElementById('gameinfo-' + charId);
    var counter = document.getElementById('counter-' + charId);
    if (!ta || !counter) return;
    var len = ta.value.length;
    counter.textContent = len.toLocaleString() + ' / 500,000';
    counter.className = 'char-counter' + (len > 490000 ? ' warn' : '');
}

function resetSaveBtn(charId) {
    var btn = document.getElementById('save-btn-' + charId);
    btn.textContent = 'Save';
    btn.className = 'btn-save';
    btn.disabled = false;
}

function resetTrainBtn(charId) {
    var btn = document.getElementById('train-btn-' + charId);
    btn.textContent = 'Train';
    btn.className = 'btn-train';
    btn.disabled = false;
    document.getElementById('train-result-' + charId).textContent = '';
}

function getCharFields(charId) {
    var data = { id: charId };
    document.querySelectorAll('.char-field[data-char="' + charId + '"]').forEach(function(el) {
        var field = el.dataset.field;
        if (el.type === 'checkbox') {
            data[field] = el.checked;
        } else {
            data[field] = el.value;
        }
    });
    return data;
}

function saveCharacter(charId) {
    var btn = document.getElementById('save-btn-' + charId);
    btn.textContent = 'Saving...';
    btn.disabled = true;

    var data = getCharFields(charId);

    fetch('/api/admin-save.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) {
            btn.textContent = 'Saved';
            btn.className = 'btn-save saved';
        } else {
            btn.textContent = 'Error';
            btn.disabled = false;
            alert('Save failed: ' + (res.error || 'Unknown error'));
        }
    })
    .catch(function() {
        btn.textContent = 'Error';
        btn.disabled = false;
        alert('Connection error');
    });
}

function trainCharacter(charId) {
    var ta = document.getElementById('gameinfo-' + charId);
    if (!ta || !ta.value.trim()) {
        alert('No game info to train on.');
        return;
    }

    var btn = document.getElementById('train-btn-' + charId);
    var result = document.getElementById('train-result-' + charId);
    btn.textContent = 'Training...';
    btn.className = 'btn-train training';
    btn.disabled = true;
    result.textContent = '';
    result.className = 'train-result';

    fetch('/api/train.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            character_id: charId,
            text: ta.value
        })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) {
            btn.textContent = 'Trained';
            btn.className = 'btn-train trained';
            result.textContent = res.chunksProcessed ? res.chunksProcessed + ' chunks processed' : 'Training complete';
            result.className = 'train-result success';
        } else {
            btn.textContent = 'Train';
            btn.className = 'btn-train';
            btn.disabled = false;
            result.textContent = 'Error: ' + (res.error || 'Unknown');
            result.className = 'train-result error';
        }
    })
    .catch(function() {
        btn.textContent = 'Train';
        btn.className = 'btn-train';
        btn.disabled = false;
        result.textContent = 'Connection error';
        result.className = 'train-result error';
    });
}

function uploadCharImage(charId) {
    var input = document.getElementById('img-input-' + charId);
    if (!input.files.length) return;

    var formData = new FormData();
    formData.append('image', input.files[0]);
    formData.append('character_id', charId);

    // Show preview immediately
    var preview = document.getElementById('img-preview-' + charId);
    if (preview.tagName === 'IMG') {
        preview.src = URL.createObjectURL(input.files[0]);
    }

    fetch('/api/admin-upload.php', {
        method: 'POST',
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) {
            resetSaveBtn(charId);
        } else {
            alert('Upload failed: ' + (res.error || 'Unknown error'));
        }
    })
    .catch(function() {
        alert('Upload connection error');
    });
}

function addCharacter() {
    var id = document.getElementById('new-char-id').value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '');
    var name = document.getElementById('new-char-name').value.trim();
    if (!id || !name) {
        alert('Please enter both an ID and a name.');
        return;
    }

    fetch('/api/admin-save.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: id,
            name: name,
            chat_api_key: '',
            admin_api_key: '',
            intro: '',
            game_info: '',
            enabled: false,
            _create: true
        })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success || res.error === 'Character not found') {
            // For new characters, we need to add them
            window.location.reload();
        }
    });
}
</script>
