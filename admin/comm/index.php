<?php
session_start();

// If not authed redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
if (@isset($_POST['sortPost'])) {
    if ($_SESSION['asc'] == true) {
        $order = "ORDER BY posts.title ASC";
        $_SESSION['asc'] = false;
    } else {
        $order = "ORDER BY posts.title DESC";
        $_SESSION['asc'] = true;
    }
}
if (@isset($_POST['sortAuth'])) {
    if ($_SESSION['asc'] == true) {
        $order = "ORDER BY users.username ASC";
        $_SESSION['asc'] = false;
    } else {
        $order = "ORDER BY users.username DESC";
        $_SESSION['asc'] = true;
    }
}
if (@isset($_POST['sortPub'])) {
    if ($_SESSION['asc'] == true) {
        $order = "ORDER BY comments.viewed ASC";
        $_SESSION['asc'] = false;
    } else {
        $order = "ORDER BY comments.viewed DESC";
        $_SESSION['asc'] = true;
    }
}
if (@isset($_POST['sortCont'])) {
    if ($_SESSION['asc'] == true) {
        $order = "ORDER BY comments.content ASC";
        $_SESSION['asc'] = false;
    } else {
        $order = "ORDER BY comments.content DESC";
        $_SESSION['asc'] = true;
    }
}

require('../../conn.php');
if (isset($_GET['search']) && $_GET['search'] === '') header("Location: ./");
$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
@$query = "SELECT comments.*, users.username, posts.title 
FROM comments 
INNER JOIN users ON comments.auth_id = users.user_id 
INNER JOIN posts ON comments.post_id = posts.post_id
WHERE comments.content LIKE '%$search%' OR users.username LIKE '%$search%' OR posts.title LIKE '%$search%' $order";
$result = mysqli_query($conn, $query);
$res = [];

while ($row = mysqli_fetch_array($result)) {
    if (isset($_GET['search'])) {
        $row['auth_id'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['auth_id']);
        $row['viewed'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['viewed']);
        $row['content'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['content']);
        $row['id'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['id']);
    }
    $res[] = $row;
}

?>
<html>

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
    <link id="theme-style" rel="stylesheet" href="../../assets/css/theme-7.css">

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
                    <img class="profile-image mb-3 rounded-circle mx-auto" src="../../assets/images/vinyl.png" alt="image">

                    <div class="bio mb-3">For the Record, a place where you can read and post about your favourite artists, bands, songs, genres and more! For all your musical needs</a></div><!--//bio-->
                    <hr>
                </div><!--//profile-section-->

                <ul class="navbar-nav flex-column text-left">
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php"><i class="fas fa-home fa-fw mr-2"></i>Blog Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../post.php"><i class="fas fa-bookmark fa-fw mr-2"></i>Create a Post</a>
                    </li>
                    <?php
                    if ($_SESSION['role'] == 'admin') { ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="../"><i class="fas fa-user fa-fw mr-2"></i>Admin Panel</a>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../../user"><i class="fas fa-user fa-fw mr-2"></i>Profile</a>
                    </li>
                </ul>

                <div class="my-2 my-md-3">
                    <a class="btn btn-primary" href="../../logout.php">Log Out?</a>
                    <!-- target="_blank" -->
                </div>
            </div>
        </nav>
    </header>
    <div class="main-wrapper">

        <section class="cta-section theme-bg-light py-5">
            <div class="container text-center">
                <h2 class="heading"><a href="../">Manage Comments</a></h2>
                <div class="intro">Back end with developer tools</div>
                <form method="GET" action="./" class="signup-form form-inline justify-content-center pt-3">
                    <table>
                        <tr>
                            <td><a href="../users" class="btn btn-primary">Edit Users</a></td>
                            <td><a href="../posts" class="btn btn-primary">Edit Posts</a></td>
                            <td> <a href="../cat" class="btn btn-primary">Edit Categories</a></td>
                            <td><a href="../" class="btn btn-primary">Back</a></td>
                        </tr>
                    </table>
                </form>
            </div><!--//container-->
        </section>
        <article class="blog-post px-3 py-5 p-md-5">
            <div class="container">

                <form method="GET" action="./" class="flex flex-wrap gap-2">
                    <input type="text" class="form-control mr-md-1 semail" name="search" placeholder="Search" value="<?= $_GET['search'] ?? '' ?>">
                    <div class="flex justify-end w-full gap-2">
                        <input type="submit" value="Submit" class="px-3 py-1 rounded-full cursor-pointer bg-neutral-200 w-min">
                        <a href="./" class="px-3 py-1 bg-gray-100 rounded-full w-min">
                            Clear
                        </a>
                    </div>
                </form>
                <table class="table table-striped my-5">
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <tr>
                            <th scope="col"><button name=sortAuth style='background-color: transparent; border-color:transparent;color:#6f42c1'><b>Author</b></button></th>
                            <th scope="col"><button name=sortPost style='background-color: transparent; border-color:transparent;color:#6f42c1'><b>Post</b></button></th>
                            <th scope="col"><button name=sortPub style='background-color: transparent; border-color:transparent;color:#6f42c1'><b>Published</b></button></th>
                            <th scope="col"><button name=sortCont style='background-color: transparent; border-color:transparent;color:#6f42c1'><b>Content</b></button></th>
                            <th scope="col"><b></th>

                        </tr>
                    </form>
                    <?php
                    foreach ($res as $row) {
                        $author = $row['username'];
                        $post = $row['title'];
                        $view = $row['viewed'];
                        $cont = $row['content'];
                        $id = $row['id'];


                        echo "<tr>
    <td >$author</td>
    <td >$post</td>
    <td >";
                        if ($view == 1) {
                            echo 'yes';
                        } else {
                            echo 'no';
                        }
                        echo "</td>
      <td >$cont</td>
    <td ><a class='link' href='edit.php?id=$id&redirect='>Edit</a></td>
    </tr>";
                    }
                    ?>
                </table>
                <!-- Javascript -->
                <script src="../../assets/plugins/jquery-3.3.1.min.js"></script>
                <script src="../../assets/plugins/popper.min.js"></script>
                <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>

</html>