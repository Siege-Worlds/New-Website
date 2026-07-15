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
                    <h1 id="titletext">

                    </h1>
                    <p id="bodytext">

                    </p>


                    <a href="play.php" class="button is-primary is-medium">Play Siege Worlds</a>



                </div>
                <img src="img/character.png" alt="character" class="companion-image" />
            </div>

        </div>
    </div>

</body>

<footer class="section bg-dark">

    <?php footer_branding(); ?>
    <?php footer_copyright(); ?>


    <script>
        const API_BASE = <?php echo json_encode($GLOBALS['API_BASE'] ?? ''); ?>;

        async function verifyUser() {
            //get username from url params
            const urlParams = new URLSearchParams(window.location.search);
            const username = urlParams.get('username');
            const token = urlParams.get('token');


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

            if (result == false) {
                document.getElementById('titletext').innerHTML = "Error";
                document.getElementById('bodytext').innerHTML = "Your email has not been verified, however you can still play Siege Worlds. Use the button below to play.";
            } else {
                document.getElementById('titletext').innerHTML = "Success";
                document.getElementById('bodytext').innerHTML = "Your email has been verified. Use the button below to start playing Siege Worlds.";
            }
        }

        window.onload = verifyUser();
    </script>
</footer>

</html>