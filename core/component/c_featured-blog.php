<div class="section featured-blog">
    <div class="container">
        <div class="section-header">
            <span class="icon">
                <i>article</i>
            </span>
            <h1>Latest News</h1>
        </div>

        <div class="featured-blog">
            <a href="news.php">
                <div class="featured-blog-image">
                    <img src="img/226.jpeg" alt="Header" />
                </div>
            </a>

            <?php
            //import blog.php
            include_once("core/database/blog.php");
            //get the latest blog post
            $latestBlogPosts = getBlogPosts();

            //get the latest blog post data
            $latestBlogPostTitle = $latestBlogPosts[0]->title;
            $latestBlogPostDate = $latestBlogPosts[0]->date;
            $latestBlogPostURL = $latestBlogPosts[0]->url;
            $latestBlogPostAuthor = $latestBlogPosts[0]->author;
            $latestBlogPostContent = $latestBlogPosts[0]->content;

            //calculate how many days ago the blog post was posted
            $latestBlogPostDate = date_create($latestBlogPostDate);
            $today = date_create(date("Y-m-d"));
            $diff = date_diff($latestBlogPostDate, $today);
            $latestBlogPostDate = $diff->format("%a days ago");
            //change so if it's 1 day ago
            if ($latestBlogPostDate == "1 days ago") {
                $latestBlogPostDate = "1 day ago";
            }
            //if 0 days ago put today
            if ($latestBlogPostDate == "0 days ago") {
                $latestBlogPostDate = "Today";
            }
            //append Author to author
            $latestBlogPostAuthor = "Author: " . $latestBlogPostAuthor;

            echo "
<div class='featured-blog-body'>
    <a href='" . $latestBlogPostURL . "'>
        <h1> " . $latestBlogPostTitle . "</h1>
    </a>
    <div class='date-and-tags'>
        <div class='tag primary'>" . $latestBlogPostDate . "</div>
        <a href='' class='tag community'>" . $latestBlogPostAuthor . "</a>
    </div>
    <p>
        " . $latestBlogPostContent . "
    </p>
    <a href='" . $latestBlogPostURL . "' class='button is-primary is-medium'>
        Read more
    </a>
</div>
";
            ?>
        </div>
    </div>
</div>