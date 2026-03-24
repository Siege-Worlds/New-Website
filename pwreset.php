<!DOCTYPE html>
<html lang="en">

<head>
    <title>Reset Password - Siege Worlds</title>

    <?php
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    require_once('./core/core.php');
    head();
    ?>

</head>

<body>

    <?php header_nav(); ?>

    <div class="section companion">
        <div class="container">
            <div class="companion-container">
                <div class="text-container">
                    <h1>Reset Password</h1>
                    <p>Enter your username and current password to set a new password.</p>

                    <input class="hs-input" id="user1" style="width:400px;" type="text" placeholder="Username" name="username"> <br>
                    <input class="hs-input" id="oldpass" style="width:400px;" type="password" placeholder="Current Password" name="password"><br>
                    <input class="hs-input" id="newpass" style="width:400px;" type="password" placeholder="New Password (8+ characters)" name="new_password"><br>
                    <input class="hs-input" id="newpass2" style="width:400px;" type="password" placeholder="Confirm New Password" name="new_password_confirm"><br>

                    <p id="reset-message" style="color:#ff4444;margin:0.5rem 0;"></p>
                    <a id="reset-button" class="button is-primary is-medium">Reset Password</a>
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

        function showMessage(msg, isError) {
            var el = document.getElementById('reset-message');
            el.textContent = msg;
            el.style.color = isError ? '#ff4444' : '#44ff44';
        }

        document.getElementById('reset-button').addEventListener('click', async function() {
            var btn = document.getElementById('reset-button');
            showMessage('', false);

            if ($('#user1').val().length < 1) {
                showMessage('Please enter your username.', true);
                return;
            }

            if ($('#oldpass').val().length < 1) {
                showMessage('Please enter your current password.', true);
                return;
            }

            if ($('#newpass').val().length < 8) {
                showMessage('New password must be at least 8 characters.', true);
                return;
            }

            if ($('#newpass').val() !== $('#newpass2').val()) {
                showMessage('New passwords do not match.', true);
                return;
            }

            btn.textContent = 'Resetting...';
            btn.style.pointerEvents = 'none';

            try {
                const result = await (await fetch(
                    API_BASE + '/api/pwreset', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            username: $('#user1').val(),
                            password1: $('#oldpass').val(),
                            password2: $('#newpass').val(),
                        })
                    })).json()

                if (result.success === true) {
                    showMessage('Password has been reset successfully.', false);
                    $('#oldpass').val('');
                    $('#newpass').val('');
                    $('#newpass2').val('');
                } else {
                    showMessage('Incorrect username or password. Please try again.', true);
                }
            } catch (e) {
                showMessage('Connection error. Please try again later.', true);
            }

            btn.textContent = 'Reset Password';
            btn.style.pointerEvents = 'auto';
        });
    </script>

</body>

</html>
