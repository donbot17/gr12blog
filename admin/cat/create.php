<?php
session_start();
require_once('../../conn.php');
if (isset($_POST['cat_id'])) {
    $id = $_POST['cat_id'];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $desc = $_POST['descr'];
    $cats = $_POST['name'];
    if (isset($_POST['create'])) {
        if ($_POST['name'] == '') {
            $err1 = "name is required";
        } else {
            $n = $_POST['name'];
        }
        if ($_POST['descr'] == '') {

            $err2 = "description is required";
        } else {
            $d = $_POST['descr'];
        }
        if (!isset($_POST['publish'])) {
            $Perr = "Please select an option.";
        } else {
            $selectedOption = $_POST['publish'];
        }
        if (isset($n) and isset($d) and isset($selectedOption)) {
            $q = "INSERT INTO `categories` (`cat_id`, `category`, `descr`, `viewed`) VALUES (NULL, '$n', '$d', '$selectedOption');";
            mysqli_query($conn, $q) or die("kys pt 2");
            $msg = "sucessfully created!";
        }
    }
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
                <h2 class="heading"><a href="../">Edit Catagories</a></h2>
            </div><!--//container-->
        </section>
        <article class="blog-post px-3 py-5 p-md-5">
            <div class="container">



                <body>
    <h1> Create a Catagory</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        Category name: <input type=text name="name" placeholder="Enter name"><?php if (isset($err1)) {
                    echo "<font color='red'> **" . $err1 . "</font> ";
                } ?><br>
        Category description: <input type=text name="descr" placeholder="Enter description"><?php if (isset($err2)) {
                    echo "<font color='red'> **" . $err2 . "</font> ";
                } ?>
        <br><br>
                        Publish?:<?php if (isset($Perr)) {
                                        echo "<font color='red'> **" . $Perr . "</font> ";
                                    } ?><br>
                        <input type="radio" id="1" name="publish" value="1">
                        <label for="1">YES</label><br>
                        <input type="radio" id="0" name="publish" value="0">
                        <label for="0">NO</label><br>
                        <br><br> <input type=submit class="btn btn-primary" name="create" value="create"><br>
        <?php if (isset($msg)) {
            echo $msg;
        } ?>

    </form>


</body>

</html>