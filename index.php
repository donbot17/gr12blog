<?php
session_start();

// CHECK LOGIN
if (!isset($_SESSION['username'])) {
    header('location: login.php');
}

//Aquire articles
require_once('./conn.php');

$category = $_GET['category'] ?? -1;
if(isset($_POST['go'])){
    $cat = $_POST['cat'];
}

if (!is_numeric($category)) header("Location: ./");
if (isset($_GET['search']) && $_GET['search'] === '') header("Location: ./");

//all cats
$categoriesQuery = "SELECT * FROM categories WHERE categories.viewed = 1";
$categories = mysqli_query($conn, $categoriesQuery) or die("Query failed: " . mysqli_error($conn));

$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
@$query = "SELECT posts.post_id, posts.title, posts.content, posts.datepost, posts.author, users.username AS author, categories.category 
            FROM posts 
            JOIN users ON posts.author = users.user_id 
            JOIN categories ON posts.category = categories.cat_id
            WHERE posts.viewed = 1 
            AND posts.title NOT LIKE '[deleted]'
            AND (categories.cat_id LIKE '%$cat%')
            AND (posts.title LIKE '%$search%' OR posts.content LIKE '%$search%' OR users.username LIKE '%$search%')
            ORDER BY posts.datepost";

$dbRes = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));
$res = [];

// Highlight search terms
while ($row = mysqli_fetch_array($dbRes)) {
    if (isset($_GET['search'])) {
        $row['content'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['content']);
        $row['title'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['title']);
        $row['author'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['author']);
    }
    $res[] = $row;

}

?>

<html>

<!-- LIST ARTICLES END -->

<!-- FILTERS START -->

    <script>
        function update(a, b) {
            let searchParams = new URLSearchParams(window.location.search);
            if (b != '')
                searchParams.set(a, b);
            else
                searchParams.delete(a);
            window.location.search = searchParams.toString();
        }
    </script>
   
    <!-- VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV -->

    <head>
        <title>For the Record</title>

        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Blog Template">
        <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
        <link rel="shortcut icon" href="favicon.ico">

        <!-- FontAwesome JS-->
        <script defer src="https://use.fontawesome.com/releases/v5.7.1/js/all.js" integrity="sha384-eVEQC9zshBn0rFj4+TU78eNA19HMNigMviK/PU/FFjLXqa/GKPgX58rvt5Z8PLs7" crossorigin="anonymous"></script>

        <!-- Theme CSS -->
        <link id="theme-style" rel="stylesheet" href="assets/css/theme-7.css">

    </head>

    <body>

        <header class="header text-center">
            <h1 class="blog-name pt-lg-4 mb-0"><a href="index.php">For the Record</a></h1>

            <nav class="navbar navbar-expand-lg navbar-dark">

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div id="navigation" class="collapse navbar-collapse flex-column">
                    <div class="profile-section pt-3 pt-lg-0">
                        <img class="profile-image mb-3 rounded-circle mx-auto" src="assets/images/vinyl.png" alt="image">

                        <div class="bio mb-3">For the Record, a place where you can read and post about your favourite artists, bands, songs, genres and more! For all your musical needs</a></div><!--//bio-->
                        <hr>
                    </div><!--//profile-section-->

                    <ul class="navbar-nav flex-column text-left">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php"><i class="fas fa-home fa-fw mr-2"></i>Blog Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="post.php"><i class="fas fa-bookmark fa-fw mr-2"></i>Create a Post</a>
                        </li>
                        <?php 
                        if($_SESSION['role'] == 'admin'){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./admin"><i class="fas fa-user fa-fw mr-2"></i>Admin Pannel</a>
                        </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./user"><i class="fas fa-user fa-fw mr-2"></i>Profile</a>
                        </li>
                    </ul>

                    <div class="my-2 my-md-3">
                        <a class="btn btn-primary" href="logout.php">Log Out?</a>
                        <!-- target="_blank" -->
                    </div>
                </div>
            </nav>
        </header>

        <div class="main-wrapper">
            <section class="cta-section theme-bg-light py-5">
                <div class="container text-center">
                    <h2 class="heading">For the Record</h2>
                    <div class="intro">For all your music desires</div>
                    <form method="GET" action="./" class="signup-form form-inline justify-content-center pt-3">
                        <div class="form-group">
                            <label class="sr-only" for="semail">search</label>
                            <input type="text" class="form-control mr-md-1 semail" name="search" placeholder="Search" value="<?= $_GET['search'] ?? '' ?>">
                        </div>
                        <button type="submit" value="Submit" class="btn btn-primary">Submit</button>
                        <a onclick="update('search', '');" href="javascript:void(0)" class="px-3 py-1 bg-gray-100 rounded-full w-min">
                            Clear
                        </a>
                    </form>
                    
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
Category:
                    <select name='cat' id='select'>
                        <option value=''> --select--</option>
                        <?php
                        $query = "SELECT * FROM `categories` WHERE categories.viewed = 1";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_array($result)) {
                            $id = $row['cat_id'];
                            $cat = $row['category'];

                            echo "<option value = $id> $cat </option>";
                        }

                        ?>
                         <input type="submit" value="go" name = 'go' class="btn btn-primary">
                    </select>
                
                    </form>
                </div><!--//container-->
            </section>

            <!-- // -->
         

            <!-- // -->
            <section class="blog-list px-3 py-5 p-md-5">
                <div class="container">
                <?php foreach ($res as $article) : ?>
                    <div class="item mb-5">
                        <div class="media">
                            <!-- <img class="mr-3 img-fluid post-thumb d-none d-md-flex" src="assets/images/blog/blog-post-thumb-1.jpg" alt="image"> -->
                            <div class="media-body">
                                <h3 class="title mb-1"><a href='./post?id=<?= $article['post_id'] ?>'><?= $article['title'] ?></a></h3>
                                <div class="meta mb-1"><span class="date"><?= date('M d, Y', strtotime($article['datepost'])) ?></span><span class="time"><?= $article['author'] ?></span><span class="comment"><a href="#"><?= $article['category'] ?></a></span></div>
                                <div class="intro"><?= (substr(strip_tags($article['content'], ['mark']), 0, 225)) ?>...</div>
                                <a class="more-link" href='./post?id=<?= $article['post_id'] ?>' >Read more &rarr;</a>
                            </div><!--//media-body-->
                            
                        </div><!--//media-->
                    </div><!--//item-->

                    <?php endforeach; ?>

                </div>
            </section>


        </div><!--//main-wrapper-->

<!--  -->

<!--  -->




        <!-- Javascript -->
        <script src="assets/plugins/jquery-3.3.1.min.js"></script>
        <script src="assets/plugins/popper.min.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>


    </body>