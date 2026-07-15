<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    require_once('core/core.php');
    head();
    ?>

    <style>
        .terms-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            color: #bab1a8;
            line-height: 1.8;
        }
        .terms-content h1 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 4rem;
            color: #fff;
            margin: 0 0 1rem 0;
        }
        .terms-content h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 2rem;
            color: #e4dad1;
            margin: 2rem 0 1rem 0;
            border-bottom: 1px solid #4c4946;
            padding-bottom: 0.5rem;
        }
        .terms-content p {
            margin: 0 0 1rem 0;
            font-size: 0.95rem;
        }
        .terms-content ul {
            margin: 0 0 1rem 1.5rem;
            font-size: 0.95rem;
        }
        .terms-content ul li {
            margin-bottom: 0.5rem;
        }
        .terms-content strong {
            color: #e4dad1;
        }
        .terms-agreement-box {
            background-color: #2a2928;
            border: 1px solid #4c4946;
            border-radius: 4px;
            padding: 2rem;
            margin-top: 3rem;
            text-align: center;
        }
        .terms-agreement-box label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #e4dad1;
            font-size: 1rem;
            cursor: pointer;
            margin-bottom: 1.5rem;
        }
        .terms-agreement-box input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: #6a24fa;
            cursor: pointer;
        }
        #agree-btn {
            opacity: 0.4;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }
        #agree-btn.enabled {
            opacity: 1;
            pointer-events: auto;
        }
        #terms-error {
            color: #ff4444;
            margin-top: 1rem;
            display: none;
        }
    </style>

</head>

<body>

    <?php header_nav(); ?>

    <div class="section companion">
        <div class="container">
            <div class="terms-content">

                <h1>Terms & Conditions</h1>
                <p>Please read the following terms carefully before creating your account. You must scroll to the bottom and agree to these terms to proceed.</p>

                <h2>1. Acceptance of Terms</h2>
                <p>By creating an account and playing Siege Worlds ("the Game"), developed and published by Games Interactive Limited ("we", "us", "our"), you agree to be bound by these Terms & Conditions. If you do not agree, do not create an account or use the Game.</p>

                <h2>2. Beta Status & Service Availability</h2>
                <p><strong>Siege Worlds is currently in beta.</strong> This means:</p>
                <ul>
                    <li>The Game is under active development and may contain bugs, errors, and incomplete features</li>
                    <li>Game features, mechanics, balancing, and content may change at any time without notice</li>
                    <li>The Game may experience downtime, data loss, or service interruptions</li>
                    <li>We may reset, modify, or wipe game data including player progress, items, and statistics at any time</li>
                    <li>We make no guarantees regarding uptime, stability, or the continued availability of any feature</li>
                </ul>
                <p>By participating in the beta, you accept these risks and agree that Games Interactive Limited is not liable for any loss resulting from the beta nature of the Game.</p>

                <h2>3. Account Responsibility</h2>
                <p>You are solely responsible for:</p>
                <ul>
                    <li>Maintaining the confidentiality of your account credentials</li>
                    <li>All activity that occurs under your account</li>
                    <li>Using a strong, unique password for your account</li>
                    <li>Reporting any unauthorized access to your account immediately</li>
                </ul>
                <p>We are not responsible for any loss or damage resulting from unauthorized access to your account, including but not limited to stolen items, lost progress, or compromised credentials.</p>

                <h2>4. Virtual Items & In-Game Economy</h2>
                <p>The Game features virtual items, currencies, and an in-game economy. You acknowledge and agree that:</p>
                <ul>
                    <li>All virtual items are the property of Games Interactive Limited</li>
                    <li>Virtual items have no guaranteed real-world monetary value</li>
                    <li>We may modify, rebalance, add, or remove virtual items at any time</li>
                    <li>We may modify trade mechanics, item values, drop rates, and economy parameters</li>
                    <li>Any trading or exchange of virtual items for cryptocurrency or other assets is done at your own risk</li>
                    <li>We are not liable for any financial loss resulting from in-game trading, market fluctuations, or changes to the game economy</li>
                </ul>

                <h2>5. Digital Assets & Cryptocurrency</h2>
                <p>If the Game integrates with blockchain technology, cryptocurrency, or digital wallets, you acknowledge and agree that:</p>
                <ul>
                    <li><strong>We are not a financial institution</strong> and do not provide financial, investment, or legal advice</li>
                    <li>Cryptocurrency and digital asset transactions are <strong>irreversible</strong> — we cannot recover lost, stolen, or misdirected tokens or assets</li>
                    <li>You are solely responsible for the security of your digital wallets and private keys</li>
                    <li>We are not liable for any loss of tokens, NFTs, or digital assets due to hacking, phishing, scams, smart contract vulnerabilities, blockchain errors, or user error</li>
                    <li>The value of any tokens or digital assets associated with the Game may fluctuate and may become worthless</li>
                    <li>We make no promises or guarantees regarding future token listings, airdrops, or rewards</li>
                </ul>

                <h2>6. Security & Hacking</h2>
                <p>While we take reasonable measures to secure the Game and its infrastructure, <strong>no system is completely secure</strong>. You acknowledge that:</p>
                <ul>
                    <li>We cannot guarantee protection against all hacking, cheating, exploits, or security breaches</li>
                    <li>We are not liable for any loss resulting from hacking, phishing attacks, malware, social engineering, or other security incidents, whether targeting our systems or your personal devices</li>
                    <li>If a security breach occurs, we will make reasonable efforts to address it, but we cannot guarantee full recovery of lost data or assets</li>
                    <li>You agree not to exploit bugs, use cheats, hacks, or unauthorized third-party software. Doing so will result in permanent account suspension</li>
                </ul>

                <h2>7. Data Collection & Privacy</h2>
                <p>We collect the following data:</p>
                <ul>
                    <li><strong>Email address</strong> — for account verification and communications</li>
                    <li><strong>Username</strong> — displayed publicly on leaderboards and in-game</li>
                    <li><strong>IP address</strong> — for security and abuse prevention</li>
                    <li><strong>Gameplay statistics</strong> — kills, scores, play time, items, and other game data</li>
                </ul>
                <p>We do <strong>not</strong> collect your real name, physical address, phone number, or payment details. We do not sell your data to third parties. See our <a href="legal.php" style="color:#6a24fa;">Privacy Policy</a> for full details.</p>

                <h2>8. Fair Play & Conduct</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Use cheats, hacks, bots, or exploits</li>
                    <li>Harass, threaten, or abuse other players</li>
                    <li>Use offensive or discriminatory usernames</li>
                    <li>Attempt to disrupt game servers or infrastructure</li>
                    <li>Impersonate other players or staff</li>
                    <li>Engage in real-money trading outside of official game mechanisms</li>
                </ul>
                <p>Violations may result in temporary or permanent account suspension without refund or compensation.</p>

                <h2>9. Limitation of Liability</h2>
                <p><strong>To the maximum extent permitted by applicable law:</strong></p>
                <ul>
                    <li>The Game is provided "AS IS" and "AS AVAILABLE" without warranties of any kind</li>
                    <li>Games Interactive Limited, its directors, employees, and affiliates shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages</li>
                    <li>This includes but is not limited to: loss of data, loss of virtual items, loss of cryptocurrency or digital assets, loss of profits, loss of goodwill, or any other intangible losses</li>
                    <li>Our total liability to you for any claims arising from your use of the Game shall not exceed the amount you have paid to us in the 12 months preceding the claim, or $100 USD, whichever is less</li>
                </ul>

                <h2>10. Indemnification</h2>
                <p>You agree to indemnify and hold harmless Games Interactive Limited, its officers, directors, employees, and agents from any claims, damages, losses, or expenses (including legal fees) arising from your use of the Game, violation of these terms, or infringement of any third-party rights.</p>

                <h2>11. Governing Law</h2>
                <p>These terms shall be governed by and construed in accordance with the laws of England and Wales. Any disputes arising from these terms shall be subject to the exclusive jurisdiction of the courts of England and Wales.</p>

                <h2>12. Changes to Terms</h2>
                <p>We may update these terms at any time. Continued use of the Game after changes are posted constitutes acceptance of the revised terms. We will make reasonable efforts to notify users of significant changes.</p>

                <h2>13. Contact</h2>
                <p>For questions about these terms, contact us at <a href="mailto:geoff@lightningworks.io" style="color:#6a24fa;">geoff@lightningworks.io</a>.</p>

                <div class="terms-agreement-box">
                    <label>
                        <input type="checkbox" id="agree-checkbox" onchange="toggleAgreeBtn()">
                        I have read and agree to the Terms & Conditions, Privacy Policy, and acknowledge that Siege Worlds is in beta.
                    </label>
                    <a id="agree-btn" class="button is-primary is-large" onclick="acceptTerms()">I Agree to the Terms</a>
                    <p id="terms-error">You must check the box to continue.</p>
                </div>

            </div>
        </div>
    </div>

    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>

    <script>
        function toggleAgreeBtn() {
            var btn = document.getElementById('agree-btn');
            var checked = document.getElementById('agree-checkbox').checked;
            if (checked) {
                btn.classList.add('enabled');
            } else {
                btn.classList.remove('enabled');
            }
        }

        function acceptTerms() {
            if (!document.getElementById('agree-checkbox').checked) {
                document.getElementById('terms-error').style.display = 'block';
                return;
            }
            window.location.href = 'signup.php?agreed=1';
        }
    </script>

</body>

</html>
