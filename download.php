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
                        <?php echo "Download"; ?>
                    </h1>
                    <p>
                        <?php echo 'Siege Worlds is free to play, you can download our game client version 4.3.0 here.'; ?>
                    </p>

                    <div class="download-box">
                      <div class="download-step">
                        <h2>Step 1</h2>
                        <h3 class="step-title"><i class="fa-solid fa-download"></i> Game Download</h3>
                        <a href="/downloads/siegeworlds_4-3-0_win.zip" target="_blank" class="download-button">
                          <i class="fa-brands fa-windows"></i>
                          Windows PC
                        </a>
                        <a href="downloads/siegeworlds_4-3-0_mac.zip" target="_blank" class="download-button">
                          <i class="fa-brands fa-apple"></i>
                          Mac OS
                        </a>
                        <a href="#" class="button-text">Installation help</a>
                      </div>
                      <div class="download-step">
                        <h2>Step 2</h2>
                        <h3 class="step-title"><i class="fa-brands fa-telegram"></i> Telegram Wallet</h3>
                        <div class="telegram-wallet">
                          <div class="telegram-wallet-qr">
                            <img src="/img/qr-code.png" title="Telegram Wallet QR" alt="Telegram Wallet QR"/>
                          </div>
                          <a href="" target="_blank" class="button is-primary is-medium">Or click here</a>
                        </div>
                      </div>
                    </div>
                </div>
                <img src="img/character.webp" alt="character" class="companion-image" />
            </div>

        </div>
    </div>

    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>

</body>

</html>
