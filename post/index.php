<?php

session_start();

// If not authed redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

// If no id redirect to index
// if (!isset($_GET['id'])) {
//     header("Location: ../");
// }

// Get article
require_once('../conn.php');

$id = $_GET['id'];

// Get post (as long as it's visible or the author is the current user)
$query = "SELECT posts.post_id, posts.title, posts.content, posts.datepost, posts.author, posts.viewed, users.username, categories.category FROM posts 
        JOIN users ON posts.author = users.user_id 
        JOIN categories ON posts.category = categories.cat_id 
        WHERE posts.post_id = $id 
        AND posts.viewed = 1";
$res = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));

$row = mysqli_fetch_assoc($res);

// Redirect if no post
if (mysqli_num_rows($res) == 0) {
    header("Location: ../");
}

// Get comments
$commentsQuery = "SELECT comments.id, comments.content, comments.auth_id, users.username , comments.datepost FROM comments 
                JOIN users ON comments.auth_id = users.user_id 
                WHERE comments.post_id = $id 
                AND comments.viewed = 1";
// -- ORDER BY comments.created_at ASC";
$comments = mysqli_query($conn, $commentsQuery) or die("Query failed: " . mysqli_error($conn));

// Create comment
$error = "";

if (isset($_POST['submit'])) {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author = $_SESSION['id'];

    if (empty($content)) {
        $error = "Please enter a comment";
    } else {
        $query = "INSERT INTO comments (id, content, viewed, auth_id, post_id, datepost) VALUES (NULL, '$content', 1,  $author, $id, CURDATE())";
        $res = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));

        if ($res) {
            header("Location: index.php?id=$id");
        } else {
            $error = "Something went wrong";
        }
    }
}

?>

<html>
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
<link id="theme-style" rel="stylesheet" href="../assets/css/theme-7.css">

</head>

<body>

    <header class="header text-center">
        <h1 class="blog-name pt-lg-4 mb-0"><a href="../index.php">For the Record</a></h1>

        <nav class="navbar navbar-expand-lg navbar-dark">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navigation" class="collapse navbar-collapse flex-column">
                <div class="profile-section pt-3 pt-lg-0">
                    <img class="profile-image mb-3 rounded-circle mx-auto" src="../assets/images/vinyl.png" alt="image">

                    <div class="bio mb-3">For the Record, a place where you can read and post about your favourite artists, bands, songs, genres and more! For all your musical needs</a></div><!--//bio-->
                    <hr>
                </div><!--//profile-section-->

                <ul class="navbar-nav flex-column text-left">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php"><i class="fas fa-home fa-fw mr-2"></i>Blog Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../post.php"><i class="fas fa-bookmark fa-fw mr-2"></i>Create a Post</a>
                    </li>
                    <?php
                    if ($_SESSION['role'] == 'admin') { ?>
                        <li class="nav-item ">
                            <a class="nav-link" href="../admin"><i class="fas fa-user fa-fw mr-2"></i>Admin Panel</a>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../user"><i class="fas fa-user fa-fw mr-2"></i>Profile</a>
                    </li>
                </ul>

                <div class="my-2 my-md-3">
                    <a class="btn btn-primary" href="../logout.php">Log Out?</a>
                    <!-- target="_blank" -->
                </div>
            </div>
        </nav>
    </header>

    <div class="main-wrapper">

        <body>

            <!--  ARTICLE START -->
            <article class="blog-post px-3 py-5 p-md-5">
                <div class="container">
                    <?php
                    $title = $row['title'];
                    $content = $row['content'];
                    $author = $row['username'];
                    $cat = $row['category'];
                    $date = date('M d, Y', strtotime($row['datepost'])); ?>
                    <header class="blog-post-header">
                        <h2 class="title mb-2"><?= $title ?></h2>
                        <div class="meta mb-3"><span class="date"><?= $date ?></span><span class="time"><?= $author ?></span><span class="comment"><a href="#"><?= $cat ?></a></span></div>
                    </header>
                    <div class="blog-post-body">
                        <p><?= $content ?></p>

                        <?php if ($row['viewed'] == 0) : ?>
                            <p>This post is not visible to the public.</p>
                        <?php endif; ?>

                        <?php
                        if (mysqli_num_rows($res) === 0) {
                            echo "<p >No post found.</p>";
                        }
                        ?>
                    </div>
                    <div class="blog-comments-section">
                        <div id="disqus_thread"></div>
                        <!-- ARTICLE END -->

                        <div>
                            <!-- COMMENTS START -->
                            <style>
                                .comment-container {
                                    margin-bottom: 20px;

                                    border-bottom: 1px solid #ccc;

                                    padding-bottom: 20px;
                                }
                            </style>
                            <h5 class="mb-3">Comments:</h5>

                            <?php foreach ($comments as $comment) : ?>
                                <?php
                                $content = $comment['content'];
                                $author = $comment['username'];
                                $date = $comment['datepost'];
                                ?>
                                <div class="comment-container">
                                    <div id="comment_<?= $comment['id'] ?>">

                                        <div class="meta mb-3"><span class="time"><b><?= $author ?></b></span><span class="date"><?= $date ?></span></div>
                                        <?php echo $content ?>



                                    </div>
                                    </div>

                                <?php endforeach; ?>
                                <?php
                                if (mysqli_num_rows($comments) === 0) {
                                    echo "<p>No comments yet.</p>";
                                }
                                ?>
                                </div>

                                <!-- COMMENTS END -->


                                <!-- COMMENT FORM START -->
                                <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

                                <div>
                                    <div>
                                        <p><?php echo $error ?></p>

                                        <!-- Only show form if post isn't deleted -->
                                        <?php if (strpos($title, '[deleted]') === false) : ?>
                                            <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] ?>" method="POST" class="flex flex-col w-full gap-2">
                                                <textarea id="editor" name="content" placeholder="Write your comment..."></textarea>
                                                <script>
                                                    ClassicEditor
                                                        .create(document.querySelector('#editor'), {
                                                            toolbar: ['bold', 'italic', 'link']
                                                        })
                                                        .then(editor => {
                                                            console.log(editor);
                                                            document.getElementsByClassName("ck-editor__main")[0].classList.add("prose");
                                                            document.getElementsByClassName("ck-editor__main")[0].style.maxWidth = "none";
                                                        })
                                                        .catch(error => {
                                                            console.error(error);
                                                        });
                                                </script>

                                                <input class="button" type="submit" name="submit" value="Comment" />
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- COMMENT FORM END -->



                        </div>
                    </div><!--//blog-comments-section-->

                </div><!--//container-->
            </article>
        </body>

</html>