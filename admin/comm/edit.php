<?php
session_start();
require_once('../../conn.php');
if (!isset($_POST['update'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM `comments` WHERE `id` = $id";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $cont = $row['content'];
    }
}
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cont = $_POST['content'];
    if (isset($_POST['update'])) {
        if ($_POST['content'] == '') {
            $err1 = "comment is required";
        } else {
            $n = $_POST['content'];
        }
        if (!isset($_POST['publish'])) {
            $Perr = "Please select an option.";
        } else {
            $selectedOption = $_POST['publish'];
        }
        if (isset($n) and isset($selectedOption)) {
            $q = "UPDATE `comments` SET `content` = '$n', `viewed` = '$selectedOption ' WHERE `id` = $id;";
            mysqli_query($conn, $q) or die("kys pt 2");
            header('location: index.php');
            $msg = "sucessfully updated!";
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
                <h2 class="heading"><a href="../">Edit Comment</a></h2>
            </div><!--//container-->
        </section>
        <article class="blog-post px-3 py-5 p-md-5">
            <div class="container">
            <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

                <body>
                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        Comment Content:<br> <textarea id="editor" name="content" value="<?php echo $cont ?>"><?php echo $cont ?></textarea>
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
                        <?php if (isset($err1)) {
                            echo "<font color='red'> **" . $err1 . "</font> ";
                        } ?><br>
                        Publish?:<?php if (isset($Perr)) {
                                        echo "<font color='red'> **" . $Perr . "</font> ";
                                    } ?><br>
                        <input type="radio" id="1" name="publish" value="1">
                        <label for="1">YES</label><br>
                        <input type="radio" id="0" name="publish" value="0">
                        <label for="0">NO</label><br>
                        <br>
                        <br><input type=submit class="btn btn-primary" name="update" value="update"><?php if (isset($err2)) {
                                                                                                        echo "<font color='red'> **" . $err2 . "</font> ";
                                                                                                    } ?><br>
                        <?php if (isset($msg)) {
                            echo $msg;
                        } ?>

                    </form>


                </body>
            </div>

</html>