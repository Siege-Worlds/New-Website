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
                    <h1>
                        <?php echo "Sign-Up"; ?>
                    </h1>
                    <p>
                        <?php echo 'Siege Worlds is free to play, you can create your account and instantly start earning rewards from our play to earn ecosystem.'; ?>
                    </p>

                    <input class="hs-input" id="user1" style="width:400px;" type="text" placeholder="Username" name="username"> <br>
                    <input class="hs-input" id="pass1" style="width:400px;" type="password" placeholder="Password (8+ characters)" name="password"><br>
                    <input class="hs-input" id="pass2" style="width:400px;" type="password" placeholder="Confirm Password" name="password_confirm"><br>
                    <input class="hs-input" id="email1" style="width:400px;" type="email" placeholder="Email" name="email"><br>

                    <p id="signup-message" style="color:#ff4444;margin:0.5rem 0;"></p>
                    <a id="signup-button" class="button is-primary is-medium">Create Account</a>



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

        document.getElementById('signup-button').addEventListener('click', function() {
            signupbutton();
        });

        function isValidUsername(username) {
            return /^[a-zA-Z0-9_]+$/.test(username);
        }

        function showMessage(msg, isError) {
            var el = document.getElementById('signup-message');
            el.textContent = msg;
            el.style.color = isError ? '#ff4444' : '#44ff44';
        }

        async function signupbutton() {
            var btn = document.getElementById('signup-button');
            showMessage('', false);

            if (!isValidUsername($('#user1').val())) {
                showMessage('Username can only contain characters a-z, 0-9, and _', true);
                return;
            }

            if ($('#user1').val().length < 2) {
                showMessage('Username must be at least 2 characters.', true);
                return;
            }

            if ($('#pass1').val().length < 8) {
                showMessage('Password must be at least 8 characters.', true);
                return;
            }

            if ($('#pass1').val() !== $('#pass2').val()) {
                showMessage('Passwords do not match.', true);
                return;
            }

            if ($('#email1').val().length < 5 || $('#email1').val().indexOf('@') === -1) {
                showMessage('Please enter a valid email address.', true);
                return;
            }

            const urlParams = new URLSearchParams(window.location.search);
            const ref = urlParams.get('ref');

            btn.textContent = 'Creating Account...';
            btn.style.pointerEvents = 'none';

            try {
                const result = await (await fetch(
                    API_BASE + '/api/register', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            username: $('#user1').val(),
                            password: $('#pass1').val(),
                            email: $('#email1').val(),
                            referral: ref
                        })
                    })).json()

                if (result.success === true) {
                    window.location.replace('account_created.php');
                } else if (result.error) {
                    showMessage(result.error, true);
                } else {
                    showMessage('Username already exists. Please try again.', true);
                }
            } catch (e) {
                showMessage('Connection error. Please try again later.', true);
            }

            btn.textContent = 'Create Account';
            btn.style.pointerEvents = 'auto';
        }
    </script>

</body>

</html>