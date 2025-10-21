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


    <?php page_hero(); ?>

    <?php highlight_stats(); ?>

    <?php quote(); ?>


    <?php roadmap(); ?>



    <?php content_center_button(); ?>

    <?php content_image_right("Competitive", "Tournaments", "Join week-long tournaments, where the highest scorers emerge victorious, claiming cryptocurrency or exclusive NFT prizes.", "", ""); ?>

    <!--?php image_section(); ?-->



    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>


    <script src="/js/index.js"></script>
</body>

<script>
    document.getElementById('opendivigo').addEventListener('click', function() {
        //open telegram and message @LWBOT
        window.open('https://t.me/LightningWorksBot', '_blank');
    });
</script>

</html>
