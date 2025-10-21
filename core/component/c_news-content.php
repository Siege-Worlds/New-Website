<div class="section featured-blog">
    <div class="container">
        <div class="section-header">
            <span class="icon">
                <i>article</i>
            </span>
            <h1>News</h1>
        </div>

        <div class="featured-blog">

            <div class="featured-blog-image">
                <img src="img/226.jpeg" alt="Header" />
            </div>


            <?php

            //include blog.php
            include_once("core/database/blog.php");
            //show errors
            ini_set('display_errors', 1);
            //get the blog posts
            $blogPosts = getBlogPosts();
            $blogpostCount = count($blogPosts);


            for ($i = 0; $i < $blogpostCount; $i++) {

                $blogPost = $blogPosts[$i];
                $blogPostTitle = $blogPost->title;
                $blogPostDate = $blogPost->date;
                $blogPostURL = $blogPost->url;
                $blogPostAuthor = $blogPost->author;
                $blogPostContent = $blogPost->content;

                //calculate how many days ago the blog post was posted
                $blogPostDate = date_create($blogPostDate);
                $today = date_create(date("Y-m-d"));
                $diff = date_diff($blogPostDate, $today);
                $blogPostDate = $diff->format("%a days ago");
                //change so if it's 1 day ago
                if ($blogPostDate == "1 days ago") {
                    $blogPostDate = "1 day ago";
                }
                //if 0 days ago put today
                if ($blogPostDate == "0 days ago") {
                    $blogPostDate = "Today";
                }

                //append the word "Author:" to the author
                $blogPostAuthor = "Author: " . $blogPostAuthor;

                echo "
<div class='featured-blog-body'>
    <a href='" . $blogPostURL . "'>
        <h1> " . $blogPostTitle . "</h1>
    </a>
    <div class='date-and-tags'>
        <div class='tag primary'>" . $blogPostDate . "</div>
        <a href='' class='tag community'>" . $blogPostAuthor . "</a>
    </div>
    <p>
        " . $blogPostContent . "
    </p>
    <a href='" . $blogPostURL . "' class='button is-primary is-medium'>
        Read more
    </a>
</div>
<br><br><br><br><br><br>
";
            }

            ?>


        </div>
    </div>
</div>