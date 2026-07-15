<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    require_once('core/core.php');
    //include blog.php
    include_once("core/database/blog.php");
    //show errors
    ini_set('display_errors', 1);
    head();
    ?>

</head>

<body>

    <?php header_nav(); ?>

    <div class="section featured-blog">
        <div class="container">


            <div class="featured-blog" style="padding-top: 50px;">

                <div class="featured-blog-body">
                    <a href="#">
                        <h1>Project Launch</h1>
                    </a>
                    <div class="date-and-tags">
                        <div class="tag primary"><?php
                                                    //get the date of a blog post with id 1
                                                    echo (getBlogPostDate(1));
                                                    ?></div>

                    </div>
                    <p>
                        Welcome to the first blog post on the new Siege Worlds website!
                    </p>

                </div>
                <br><br><br><br><br><br>

            </div>
        </div>
    </div>

</body>

<footer class="section bg-dark">

    <?php footer_branding(); ?>
    <?php footer_copyright(); ?>

</footer>

</html>