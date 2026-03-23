<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    require_once('core/core.php');
    head();
    ?>
    <style>
        header.header .header-brand img { width: 350px; }
    </style>
</head>

<body>

    <?php header_nav(); ?>


    <?php page_hero(); ?>

    <?php highlight_stats(); ?>

    <?php trailer_video(); ?>

    <?php quote(); ?>


    <?php roadmap(); ?>

    <?php chat_shiyang(); ?>

    <?php content_center_button(); ?>

    <?php content_image_right("Competitive", "Tournaments", "Join week-long tournaments, where the highest scorers emerge victorious, claiming cryptocurrency or exclusive NFT prizes.", "", ""); ?>

    <?php partnerships(); ?>

    <!--?php image_section(); ?-->



    <footer class="section bg-dark">

        <?php footer_branding(); ?>
        <?php footer_copyright(); ?>

    </footer>


    <script src="/js/index.js"></script>
    <script>
        document.getElementById('opendivigo').addEventListener('click', function() {
            //open telegram and message @LWBOT
            window.open('https://t.me/LightningWorksBot', '_blank');
        });
    </script>
</body>

</html>
