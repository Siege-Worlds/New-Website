<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    require_once('core/core.php');
    head();
    ?>

    <style>
        .login-form {
            max-width: 400px;
        }
        .login-form input {
            width: 100%;
            display: block;
        }
        .login-message {
            margin: 0.5rem 0;
            min-height: 1.5rem;
        }
        .login-message.error { color: #ff4444; }
        .login-message.success { color: #44ff44; }

        .terms-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.9);
            z-index: 100;
            overflow-y: auto;
        }
        .terms-overlay.active { display: block; }
        .terms-overlay-content {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            color: #bab1a8;
            line-height: 1.8;
        }
        .terms-overlay-content h1 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 3rem;
            color: #fff;
            margin: 0 0 1rem 0;
        }
        .terms-overlay-content h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 1.8rem;
            color: #e4dad1;
            margin: 2rem 0 1rem 0;
            border-bottom: 1px solid #4c4946;
            padding-bottom: 0.5rem;
        }
        .terms-overlay-content p { margin: 0 0 1rem 0; font-size: 0.95rem; }
        .terms-overlay-content ul { margin: 0 0 1rem 1.5rem; font-size: 0.95rem; }
        .terms-overlay-content ul li { margin-bottom: 0.5rem; }
        .terms-overlay-content strong { color: #e4dad1; }
        .terms-agree-box {
            background-color: #2a2928;
            border: 1px solid #4c4946;
            border-radius: 4px;
            padding: 2rem;
            margin-top: 2rem;
            text-align: center;
        }
        .terms-agree-box label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #e4dad1;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 1.5rem;
        }
        .terms-agree-box input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #6a24fa;
            cursor: pointer;
        }
        #terms-agree-btn {
            opacity: 0.4;
            pointer-events: none;
        }
        #terms-agree-btn.enabled {
            opacity: 1;
            pointer-events: auto;
        }
    </style>

</head>

<body>

    <?php header_nav(); ?>

    <div class="section companion">
        <div class="container">
            <div class="companion-container">
                <div class="text-container">
                    <h1>Log In</h1>
                    <p>Enter your username and password to accept the updated Terms & Conditions and continue playing Siege Worlds.</p>

                    <div class="login-form">
                        <input class="hs-input" id="login-user" type="text" placeholder="Username" name="username">
                        <input class="hs-input" id="login-pass" type="password" placeholder="Password" name="password">

                        <p id="login-message" class="login-message"></p>
                        <a id="login-btn" class="button is-primary is-medium" onclick="doLogin()">Log In</a>
                    </div>
                </div>
                <img src="img/character.png" alt="character" class="companion-image" />
            </div>
        </div>
    </div>

    <!-- Terms overlay - shown after successful login if terms not yet agreed -->
    <div class="terms-overlay" id="terms-overlay">
        <div class="terms-overlay-content">

            <h1>Updated Terms & Conditions</h1>
            <p>We've updated our Terms & Conditions. Please read and accept them to continue playing.</p>

            <h2>1. Acceptance of Terms</h2>
            <p>By playing Siege Worlds ("the Game"), developed by Games Interactive Limited ("we", "us", "our"), you agree to be bound by these Terms & Conditions.</p>

            <h2>2. Beta Status</h2>
            <p><strong>Siege Worlds is currently in beta.</strong> The Game may contain bugs, experience downtime, or undergo changes including data resets. We make no guarantees regarding uptime or stability. You accept these risks.</p>

            <h2>3. Account Responsibility</h2>
            <p>You are solely responsible for your account credentials and all activity on your account. We are not responsible for unauthorized access, stolen items, or compromised accounts.</p>

            <h2>4. Virtual Items & Economy</h2>
            <p>All virtual items are property of Games Interactive Limited with no guaranteed real-world value. We may modify items, values, drop rates, and economy mechanics at any time. Trading is at your own risk.</p>

            <h2>5. Digital Assets & Cryptocurrency</h2>
            <p>Cryptocurrency transactions are <strong>irreversible</strong>. We cannot recover lost, stolen, or misdirected tokens. You are responsible for your wallet security. We make no guarantees regarding token value or future rewards.</p>

            <h2>6. Security</h2>
            <p>We are not liable for losses from hacking, phishing, malware, exploits, or security breaches targeting our systems or your devices. You agree not to use cheats, hacks, or unauthorized software.</p>

            <h2>7. Privacy</h2>
            <p>We collect email, username, IP address, and gameplay data. We do not collect real names, addresses, or payment details. We do not sell your data. See our <a href="legal.php" style="color:#6a24fa;" target="_blank">Privacy Policy</a> for details.</p>

            <h2>8. Limitation of Liability</h2>
            <p>The Game is provided "AS IS" without warranties. Games Interactive Limited shall not be liable for any direct, indirect, or consequential damages including loss of data, virtual items, cryptocurrency, or profits. Total liability is limited to $100 USD or the amount paid in the preceding 12 months, whichever is less.</p>

            <h2>9. Indemnification</h2>
            <p>You agree to indemnify Games Interactive Limited from any claims arising from your use of the Game or violation of these terms.</p>

            <h2>10. Governing Law</h2>
            <p>These terms are governed by the laws of England and Wales.</p>

            <div class="terms-agree-box">
                <label>
                    <input type="checkbox" id="terms-checkbox" onchange="toggleTermsBtn()">
                    I have read and agree to the Terms & Conditions and acknowledge that Siege Worlds is in beta.
                </label>
                <a id="terms-agree-btn" class="button is-primary is-large" onclick="acceptTerms()">I Agree to the Terms</a>
            </div>

        </div>
    </div>

    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>

    <script>
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;
        var loggedInUser = null;

        function showMessage(msg, isError) {
            var el = document.getElementById('login-message');
            el.textContent = msg;
            el.className = 'login-message ' + (isError ? 'error' : 'success');
        }

        async function doLogin() {
            var username = $('#login-user').val().trim();
            var password = $('#login-pass').val();
            showMessage('', false);

            if (username.length < 1) {
                showMessage('Please enter your username.', true);
                return;
            }
            if (password.length < 1) {
                showMessage('Please enter your password.', true);
                return;
            }

            var btn = document.getElementById('login-btn');
            btn.textContent = 'Logging in...';
            btn.style.pointerEvents = 'none';

            try {
                // Verify credentials using password reset endpoint with same password
                // This checks if the username/password combination is valid
                const result = await (await fetch(
                    API_BASE + '/api/checklogin', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            username: username,
                            password: password
                        })
                    })).json();

                if (result.success === true) {
                    if (result.terms_agreed === true) {
                        showMessage('You have already accepted the terms. You can play!', false);
                    } else {
                        loggedInUser = username;
                        document.getElementById('terms-overlay').classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }
                } else {
                    showMessage('Incorrect username or password.', true);
                }
            } catch (e) {
                showMessage('Connection error. Please try again later.', true);
            }

            btn.textContent = 'Log In';
            btn.style.pointerEvents = 'auto';
        }

        function toggleTermsBtn() {
            var btn = document.getElementById('terms-agree-btn');
            if (document.getElementById('terms-checkbox').checked) {
                btn.classList.add('enabled');
            } else {
                btn.classList.remove('enabled');
            }
        }

        async function acceptTerms() {
            if (!document.getElementById('terms-checkbox').checked) return;

            var btn = document.getElementById('terms-agree-btn');
            btn.textContent = 'Saving...';
            btn.style.pointerEvents = 'none';

            try {
                const result = await (await fetch(
                    API_BASE + '/api/acceptterms', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            username: loggedInUser,
                            terms_version: '1.0'
                        })
                    })).json();

                if (result.success === true) {
                    document.getElementById('terms-overlay').classList.remove('active');
                    document.body.style.overflow = '';
                    showMessage('Terms accepted! You can now play Siege Worlds.', false);
                } else {
                    alert('Error saving terms agreement. Please try again.');
                }
            } catch (e) {
                alert('Connection error. Please try again.');
            }

            btn.textContent = 'I Agree to the Terms';
            btn.style.pointerEvents = 'auto';
        }

        // Allow Enter key to submit
        document.getElementById('login-pass').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') doLogin();
        });
        document.getElementById('login-user').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') doLogin();
        });
    </script>

</body>

</html>
