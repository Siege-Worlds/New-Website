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

    <?php news_content(); ?>

</body>

<footer class="section bg-dark">

    <?php footer_branding(); ?>
    <?php footer_copyright(); ?>

</footer>

</html>