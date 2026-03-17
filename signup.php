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
                    <input class="hs-input" id="pass1" style="width:400px;" type="password" placeholder="Password" name="password"><br>
                    <input class="hs-input" id="email1" style="width:400px;" type="text" placeholder="email" name="email"><br>


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

        async function signupbutton() {

            if (!isValidUsername($('#user1').val())) {
                alert("username can only contain characters a-z 0-9 _");
                return;
            }

            const queryString = window.location.search;
            console.log(queryString);
            const urlParams = new URLSearchParams(queryString);
            const ref = urlParams.get('ref');
            console.log(ref);

            if ($('#user1').val().length < 1) {
                alert("Error: No username entered.")
            } else if ($('#pass1').val().length < 8) {
                alert("Error: No password entered.")
            } else if ($('#email1').val().length < 5) {
                alert("Error: No email entered.")
            } else {
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
                if (result == false) {
                    alert("Username already exists. Please try again")
                } else {
                    alert("Your account has been created.")
                    window.location.replace("https://www.siegeworlds.com/account_created.php");
                }
            }
        }
    </script>

</body>

</html>