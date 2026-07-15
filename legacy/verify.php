<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    require_once('core/core.php');
    head();
    ?>

</head>

<body>

    <?php header_nav(); ?>

    <div class="section companion">
        <div class="container">
            <div class="companion-container">
                <div class="text-container">
                    <h1 id="titletext">Verifying...</h1>
                    <p id="bodytext">Please wait while we verify your email address.</p>

                    <a href="download.php" class="button is-primary is-medium">Download Siege Worlds</a>
                </div>
                <img src="img/character.png" alt="character" class="companion-image" />
            </div>
        </div>
    </div>

    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>

    <script>
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

        async function verifyUser() {
            const urlParams = new URLSearchParams(window.location.search);
            const username = urlParams.get('username');
            const token = urlParams.get('token');

            if (!username || !token) {
                document.getElementById('titletext').textContent = "Invalid Link";
                document.getElementById('bodytext').textContent = "This verification link is invalid or expired.";
                return;
            }

            try {
                const result = await (await fetch(
                    API_BASE + '/api/verify', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            username: username,
                            vkey: token
                        })
                    })).json()

                if (result && result.success === true) {
                    document.getElementById('titletext').textContent = "Email Verified!";
                    document.getElementById('bodytext').textContent = "Your email has been verified. You can now download and start playing Siege Worlds.";
                } else {
                    document.getElementById('titletext').textContent = "Verification Failed";
                    document.getElementById('bodytext').textContent = "This link may have already been used or is invalid. You can still play Siege Worlds.";
                }
            } catch (e) {
                document.getElementById('titletext').textContent = "Connection Error";
                document.getElementById('bodytext').textContent = "Could not verify your email. Please try again later.";
            }
        }

        window.onload = verifyUser;
    </script>

</body>

</html>
