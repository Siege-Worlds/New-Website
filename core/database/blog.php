<?php

function createBlogTable()
{

    //connect to the database
    $servername = "3.139.247.245";
    $username = "swadmin";
    $password = ",)CG65je}BXn";
    $dbname = "jakesw_blogs";

    //create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //create table
    $sql = "CREATE TABLE blog (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(30) NOT NULL,
    content VARCHAR(1000) NOT NULL,
    author VARCHAR(30) NOT NULL,
    date VARCHAR(30) NOT NULL,
    url VARCHAR(30) NOT NULL
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Table blog created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    //close connection
    $conn->close();
}

function writeBlogToDB()
{
    //conect to database
    $servername = "3.139.247.245";
    $username = "swadmin";
    $password = ",)CG65je}BXn";
    $dbname = "jakesw_blogs";

    //create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //get data from form
    $title = $_POST["title"];
    $content = $_POST["content"];
    $author = $_POST["author"];
    $url = $_POST["url"];

    //get the date automatically
    $date = date("Y-m-d");

    //find the id to add
    $sql = "SELECT * FROM blog ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $id = $row["id"] + 1;

    //insert data into database
    $sql = "INSERT INTO blog (id, title, content, author, date, url)
    VALUES ('$id','$title', '$content', '$author', '$date', '$url')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    //close connection
    $conn->close();
}

class BlogPost
{
    public $title;
    public $content;
    public $author;
    public $date;
    public $url;

    function __construct($title, $content, $author, $date, $url)
    {
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->date = $date;
        $this->url = $url;
    }
}

function getBlogPosts()
{
    //conect to database
    $servername = "3.139.247.245";
    $username = "swadmin";
    $password = ",)CG65je}BXn";
    $dbname = "jakesw_blogs";

    //create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //get the latest 5 blog posts
    $sql = "SELECT * FROM blog ORDER BY id DESC LIMIT 5";

    //get the result
    $result = $conn->query($sql);

    //create an array of blog posts
    $blogPosts = array();

    //if there are results
    if ($result->num_rows > 0) {
        //loop through each result
        while ($row = $result->fetch_assoc()) {
            //create a new blog post
            $blogPost = new BlogPost($row["title"], $row["content"], $row["author"], $row["date"], $row["url"]);
            //add the blog post to the array
            array_push($blogPosts, $blogPost);
        }
    } else {
        echo "0 results";
    }




    //close connection
    $conn->close();

    //return the blobposts
    return $blogPosts;
}

//get the date of a blog post
function getBlogPostDate($id)
{
    //conect to database
    $servername = "3.139.247.245";
    $username = "swadmin";
    $password = ",)CG65je}BXn";
    $dbname = "jakesw_blogs";

    //create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //select the entry with the id
    $sql = "SELECT * FROM blog WHERE id = $id";

    // caclulate how many days ago the blog post was posted
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $blogPostDate = $row["date"];
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

    //close connection
    $conn->close();

    //return the date
    return $blogPostDate;
}
