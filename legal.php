<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    require_once('core/core.php');
    head();
    ?>

    <style>
        .legal-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            color: #bab1a8;
            line-height: 1.8;
        }
        .legal-content h1 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 4rem;
            color: #fff;
            margin: 0 0 2rem 0;
        }
        .legal-content h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 2rem;
            color: #e4dad1;
            margin: 2rem 0 1rem 0;
            border-bottom: 1px solid #4c4946;
            padding-bottom: 0.5rem;
        }
        .legal-content p {
            margin: 0 0 1rem 0;
            font-size: 0.95rem;
        }
        .legal-content ul {
            margin: 0 0 1rem 1.5rem;
            font-size: 0.95rem;
        }
        .legal-content ul li {
            margin-bottom: 0.5rem;
        }
        .legal-content a {
            color: #6a24fa;
        }
        .legal-content .last-updated {
            color: #7a7572;
            font-size: 0.85rem;
            margin-top: 3rem;
        }
    </style>

</head>

<body>

    <?php header_nav(); ?>

    <div class="section companion">
        <div class="container">
            <div class="legal-content">

                <h1>Legal & Privacy</h1>

                <h2>Terms of Service</h2>
                <p>By accessing and playing Siege Worlds ("the Game"), you agree to the following terms. If you do not agree, please do not use the Game or this website.</p>
                <p>Siege Worlds is developed and published by Games Interactive Limited ("we", "us", "our"). We reserve the right to modify, suspend, or discontinue any aspect of the Game at any time without prior notice.</p>

                <h2>Account & Eligibility</h2>
                <p>You must be at least 13 years of age to create an account. You are responsible for maintaining the security of your account credentials. We are not liable for any loss or damage resulting from unauthorized access to your account.</p>
                <p>We reserve the right to suspend or permanently ban any account that violates these terms, engages in cheating, exploits, or any behavior that disrupts the experience for other players.</p>

                <h2>In-Game Economy</h2>
                <p>Siege Worlds features an in-game economy where players can earn, trade, and forge virtual items. All virtual items remain the property of Games Interactive Limited. We make no guarantees regarding the real-world value of any in-game items or currencies.</p>
                <p>We reserve the right to modify the game economy, item values, drop rates, and trading mechanics at any time to maintain game balance and fairness.</p>

                <h2>Privacy Policy</h2>
                <p>We take your privacy seriously. Here is what data we collect and how we use it:</p>

                <p><strong>Data we collect:</strong></p>
                <ul>
                    <li><strong>Email address</strong> — used for account verification and important account-related communications only</li>
                    <li><strong>Username</strong> — your chosen in-game name, displayed publicly on leaderboards</li>
                    <li><strong>IP address</strong> — logged for security purposes and abuse prevention</li>
                    <li><strong>Gameplay data</strong> — kills, scores, play time, and other in-game statistics used for leaderboards and game balancing</li>
                </ul>

                <p><strong>Data we do NOT collect:</strong></p>
                <ul>
                    <li>Real name, physical address, or phone number</li>
                    <li>Payment information (handled by third-party processors)</li>
                    <li>Biometric data or device identifiers beyond IP address</li>
                    <li>Data from other apps or services on your device</li>
                </ul>

                <p>We do not sell, rent, or share your personal data with third parties for marketing purposes. Your email will never be shared or sold.</p>

                <h2>Cookies & Analytics</h2>
                <p>This website uses Google Analytics to understand how visitors interact with the site. This may involve cookies. No personally identifiable information is shared with Google beyond standard analytics data.</p>

                <h2>User-Generated Content</h2>
                <p>Any usernames, chat messages, or other content you create within the Game must not be offensive, discriminatory, or infringe upon the rights of others. We reserve the right to remove any content and suspend accounts that violate this policy.</p>

                <h2>Disclaimer of Warranties</h2>
                <p>Siege Worlds is provided "as is" without warranties of any kind, either express or implied. We do not guarantee that the Game will be uninterrupted, error-free, or free of harmful components. You use the Game at your own risk.</p>

                <h2>Limitation of Liability</h2>
                <p>To the fullest extent permitted by law, Games Interactive Limited shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the Game or this website, including but not limited to loss of data, loss of virtual items, or loss of profits.</p>

                <h2>Intellectual Property</h2>
                <p>All content in Siege Worlds — including but not limited to graphics, sounds, music, game mechanics, code, and branding — is the property of Games Interactive Limited and is protected by applicable intellectual property laws. You may not reproduce, distribute, or create derivative works without our written permission.</p>

                <h2>Changes to These Terms</h2>
                <p>We may update these terms from time to time. Continued use of the Game after changes are posted constitutes your acceptance of the revised terms.</p>

                <h2>Contact</h2>
                <p>For questions about these terms or our privacy practices, contact us at <a href="mailto:geoff@lightningworks.io">geoff@lightningworks.io</a>.</p>

                <p class="last-updated">Last updated: March 2026</p>

            </div>
        </div>
    </div>

    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>

</body>

</html>
