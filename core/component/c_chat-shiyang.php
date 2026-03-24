<?php
$charFile = __DIR__ . '/../database/characters.json';
$allChars = json_decode(file_get_contents($charFile), true) ?: [];
$enabledChars = array_values(array_filter($allChars, fn($c) => $c['enabled'] && ($c['chat_api_key'] ?? $c['api_key'] ?? '')));
if (empty($enabledChars)) return;
$defaultChar = $enabledChars[0];
// Include chat API keys for iframe embed URL (required by the embed)
$jsChars = [];
foreach ($enabledChars as $c) {
    $jsChars[] = [
        'id' => $c['id'],
        'name' => $c['name'],
        'image' => $c['image'],
        'intro' => $c['intro'],
        'key' => $c['chat_api_key'] ?? $c['api_key'] ?? ''
    ];
}
?>

<div class="section shiyang-chat-section">
    <div class="container">
        <h1 id="chatSectionTitle" class="chat-section-title">Chat With <?php echo htmlspecialchars($defaultChar['name']); ?></h1>

        <div class="shiyang-chat-layout">
            <div class="shiyang-chat-left">
                <p class="shiyang-chat-intro" id="chatIntroText"><?php echo htmlspecialchars($defaultChar['intro']); ?></p>
                <div class="shiyang-chat-embed">
                    <iframe
                        id="shiyangChatIframe"
                        src=""
                        allow="clipboard-write"
                        title="Character Chat"
                    ></iframe>
                </div>
                <?php if (count($enabledChars) > 1): ?>
                <div class="character-toggle" id="charToggle">
                    <?php foreach ($enabledChars as $i => $c): ?>
                    <button class="char-toggle-btn <?php echo $i === 0 ? 'char-active' : ''; ?>"
                            id="toggle_<?php echo htmlspecialchars($c['id']); ?>"
                            onclick="switchCharacter('<?php echo htmlspecialchars($c['id']); ?>')">
                        <?php echo htmlspecialchars($c['name']); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="shiyang-chat-right">
                <img src="<?php echo htmlspecialchars($defaultChar['image']); ?>" alt="<?php echo htmlspecialchars($defaultChar['name']); ?>" class="shiyang-chat-img" id="chatCharImg" />
            </div>
        </div>
    </div>
</div>

<style>
    .shiyang-chat-section {
        padding: 4rem 0;
    }
    .chat-section-title {
        font-size: 4rem !important;
    }
    .shiyang-chat-layout {
        display: flex;
        align-items: flex-end;
        gap: 0;
        margin-top: 1.5rem;
        position: relative;
    }
    .shiyang-chat-left {
        flex: 1;
        min-width: 0;
        padding-right: 100px;
    }
    .shiyang-chat-intro {
        color: #bab1a8;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    .shiyang-chat-embed {
        width: calc(100% + 100px);
        height: 570px;
        border-radius: 12px;
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.4);
        border: 1px solid #3a3836;
        padding: 10px 0;
    }
    .shiyang-chat-embed iframe {
        width: 100%;
        height: 100%;
        border: none;
        display: block;
    }
    .shiyang-chat-right {
        flex: 0 0 auto;
        position: relative;
        margin-left: -35px;
        z-index: 2;
        align-self: flex-end;
    }
    .shiyang-chat-img {
        max-height: 550px;
        object-fit: contain;
        display: block;
    }
    .character-toggle {
        display: flex;
        gap: 0;
        margin-top: 1rem;
    }
    .char-toggle-btn {
        font-family: "Bebas Neue", sans-serif;
        font-size: 1.2rem;
        padding: 0.4rem 1.5rem;
        cursor: pointer;
        background: #2a2928;
        color: #bab1a8;
        border: 1px solid #3a3836;
        transition: all 0.2s ease;
        text-transform: uppercase;
    }
    .char-toggle-btn:first-child {
        border-radius: 6px 0 0 6px;
    }
    .char-toggle-btn:last-child {
        border-radius: 0 6px 6px 0;
    }
    .char-toggle-btn.char-active {
        background: #6a24fa;
        color: #fff;
        border-color: #6a24fa;
    }
    .char-toggle-btn:hover:not(.char-active) {
        background: #3a3836;
    }
    @media (max-width: 980px) {
        .chat-section-title {
            font-size: 3rem !important;
        }
        .shiyang-chat-layout {
            flex-direction: column;
            align-items: center;
        }
        .shiyang-chat-left {
            padding-right: 0;
        }
        .shiyang-chat-embed {
            width: 100%;
        }
        .shiyang-chat-right {
            order: -1;
            margin-left: 0;
        }
        .shiyang-chat-img {
            max-height: 300px;
        }
        .shiyang-chat-embed {
            height: 450px;
        }
    }
</style>

<script>
(function() {
    var characters = <?php echo json_encode($jsChars); ?>;
    var currentCharacter = characters[0].id;
    var KINET_EMBED_URL = 'https://fairytime.lovable.app/embed/chat';
    var BG_COLOR = '1a112e';
    var ACCENT_COLOR = '6a24fa';

    var USER_ID = '<?php echo isset($_SESSION["sso_user_id"]) ? htmlspecialchars($_SESSION["sso_user_id"], ENT_QUOTES) : ""; ?>';
    var USER_AVATAR = '<?php echo isset($_SESSION["avatar_url"]) ? htmlspecialchars($_SESSION["avatar_url"], ENT_QUOTES) : ""; ?>';
    var USER_NAME = '<?php echo isset($_SESSION["display_name"]) ? htmlspecialchars($_SESSION["display_name"], ENT_QUOTES) : (isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"], ENT_QUOTES) : ""); ?>';
    var USER_BORDER_COLOR = '<?php echo isset($_SESSION["avatar_outer_color"]) ? htmlspecialchars($_SESSION["avatar_outer_color"], ENT_QUOTES) : ""; ?>';
    var USER_INNER_COLOR = '<?php echo isset($_SESSION["avatar_inner_color"]) ? htmlspecialchars($_SESSION["avatar_inner_color"], ENT_QUOTES) : ""; ?>';

    function hexToHsl(hex) {
        if (!hex || hex === '#000000' || hex.length < 7) return '';
        var r = parseInt(hex.slice(1, 3), 16) / 255;
        var g = parseInt(hex.slice(3, 5), 16) / 255;
        var b = parseInt(hex.slice(5, 7), 16) / 255;
        var max = Math.max(r, g, b), min = Math.min(r, g, b);
        var l = (max + min) / 2;
        if (max === min) return 'hsl(0,0%,' + Math.round(l * 100) + '%)';
        var d = max - min;
        var s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        var h = 0;
        if (max === r) h = ((g - b) / d + (g < b ? 6 : 0)) / 6;
        else if (max === g) h = ((b - r) / d + 2) / 6;
        else h = ((r - g) / d + 4) / 6;
        return 'hsl(' + Math.round(h * 360) + ',' + Math.round(s * 100) + '%,' + Math.round(l * 100) + '%)';
    }

    function buildIdentityMsg() {
        return {
            type: 'setUserIdentity',
            userId: USER_ID,
            user_id: USER_ID,
            sub: USER_ID,
            id: USER_ID,
            avatar: USER_AVATAR,
            user_avatar: USER_AVATAR,
            userAvatar: USER_AVATAR,
            name: USER_NAME,
            userName: USER_NAME,
            borderColor: hexToHsl(USER_BORDER_COLOR),
            userBorderColor: hexToHsl(USER_BORDER_COLOR),
            innerColor: hexToHsl(USER_INNER_COLOR),
            userInnerColor: hexToHsl(USER_INNER_COLOR),
            userColor: hexToHsl(USER_BORDER_COLOR)
        };
    }

    function sendIdentity() {
        var iframe = document.getElementById('shiyangChatIframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.postMessage(buildIdentityMsg(), '*');
        }
    }

    function getCharById(id) {
        for (var i = 0; i < characters.length; i++) {
            if (characters[i].id === id) return characters[i];
        }
        return characters[0];
    }

    function loadChat(charKey) {
        var char = getCharById(charKey);
        var iframe = document.getElementById('shiyangChatIframe');
        var url = KINET_EMBED_URL + '?key=' + encodeURIComponent(char.key)
            + '&bg=' + BG_COLOR
            + '&accent=' + ACCENT_COLOR
            + '&header=false';
        if (USER_ID) url += '&user_id=' + encodeURIComponent(USER_ID);
        if (USER_NAME) url += '&user_name=' + encodeURIComponent(USER_NAME);
        iframe.src = url;
        iframe.onload = function() {
            sendIdentity();
            setTimeout(sendIdentity, 500);
            setTimeout(sendIdentity, 1500);
            setTimeout(sendIdentity, 3000);
        };
    }

    window.addEventListener('message', function(event) {
        if (event.origin.indexOf('lovable.app') === -1 && event.origin.indexOf('kinet.ink') === -1) return;
        // Respond to identity requests and any other messages from the embed
        if (event.data && (event.data.type === 'embed:requestUserIdentity' || event.data.type)) {
            sendIdentity();
        }
    });

    window.switchCharacter = function(charKey) {
        if (charKey === currentCharacter) return;
        currentCharacter = charKey;
        var char = getCharById(charKey);

        document.getElementById('chatSectionTitle').textContent = 'Chat With ' + char.name;
        document.getElementById('chatIntroText').textContent = char.intro;
        document.getElementById('chatCharImg').src = char.image;
        document.getElementById('chatCharImg').alt = char.name;

        var btns = document.querySelectorAll('.char-toggle-btn');
        for (var i = 0; i < btns.length; i++) {
            btns[i].classList.toggle('char-active', btns[i].id === 'toggle_' + charKey);
        }

        loadChat(charKey);
    };

    // Load the chat iframe when section scrolls into view
    var loaded = false;
    var observer = new IntersectionObserver(function(entries) {
        if (entries[0].isIntersecting && !loaded) {
            loaded = true;
            loadChat(currentCharacter);
            observer.disconnect();
        }
    }, { threshold: 0.1 });
    observer.observe(document.querySelector('.shiyang-chat-section'));
})();
</script>
