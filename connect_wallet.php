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
                        EARN
                    </h1>
                    <p>
                        <?php echo "Scan the QR code to connect your wallet and start earning as you play."; ?>
                    </p>
                    <?php
                    echo '<img src="https://lg.cr/qr/gameshare/telegram/' . $_GET['user'] . '" width="300" />';
                    ?>


                </div>
                <img src="img/character.png" alt="character" class="companion-image" />
            </div>

        </div>
    </div>

</body>

<footer class="section bg-dark">

    <?php footer_branding(); ?>
    <?php footer_copyright(); ?>

</footer>

</html>