<?php
session_start();
$id = $_SESSION['id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['enter'])) {
        $cat = $_POST['cat'];
        $editor_data = $_POST['content'];

        if (empty($_POST["title"])) {
            $Terr = "title is required";
        } else {
            $title = $_POST["title"];
        }

        if ($cat == '0') {
            $Cerr = "please choose a catagory";
        } else {
            $cate = true;
        }
        if ($editor_data == '') {
            $Aerr = "please write something for your article";
        } else {
            $cont = true;
        }
        if (!isset($_POST['publish'])) {
            $Perr = "Please select an option.";
        } else {
            $selectedOption = $_POST['publish'];
        }

        if (isset($title) && isset($selectedOption) && $cate == true and $cont == true) {
            require('conn.php');
            $q = "INSERT INTO `posts` (`post_id`, `title`, `content`, `datepost`, `author`, `category`, `viewed`)
             VALUES (NULL, '$title', '$editor_data', CURDATE(), '$id', '$cat', '$selectedOption')";
            mysqli_query($conn, $q) or die("kys pt 2");
            if ($selectedOption == 0) {
                $msg = "sucessfully saved!";
            } else {
                $msg = "sucessfully posted!";
            }
        }
    }
}
?>

<html>

<head>
    <title>For the Record</title>
    <script type="text/javascript" src="editor/ckeditor.js"></script>

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
    <link id="theme-style" rel="stylesheet" href="./assets/css/theme-7.css">

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
                    <img class="profile-image mb-3 rounded-circle mx-auto" src="./assets/images/vinyl.png" alt="image">

                    <div class="bio mb-3">For the Record, a place where you can read and post about your favourite artists, bands, songs, genres and more! For all your musical needs</a></div><!--//bio-->
                    <hr>
                </div><!--//profile-section-->

                <ul class="navbar-nav flex-column text-left">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home fa-fw mr-2"></i>Blog Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="post.php"><i class="fas fa-bookmark fa-fw mr-2"></i>Create a Post</a>
                    </li>
                    <?php
                    if ($_SESSION['role'] == 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./admin"><i class="fas fa-user fa-fw mr-2"></i>Admin Panel</a>
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
                <h2 class="heading">Create a Post</h2>
            </div><!--//container-->
        </section>

        <body>
            <div class="container">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    Title: <?php if (isset($Terr)) {
                                echo "<font color='red'> **" . $Terr . "</font> ";
                            } ?>
                    <br> <input type='text' name='title' autofocus placeholder=" Title" autocomplete="off" <?php if (isset($_POST['title'])) {
                                                                                                                echo "value = '" . $_POST['title'] . "'";
                                                                                                            } ?>>
                    <br>
                    <br>
                    Category: <?php if (isset($Cerr)) {
                                    echo "<font color='red'> **" . $Cerr . "</font> ";
                                } ?><br>
                    <select name='cat' id='select'>
                        <option value='0'> --select--</option>
                        <?php
                        require('conn.php');
                        $query = "SELECT * FROM `categories`";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_array($result)) {
                            $id = $row['cat_id'];
                            $cat = $row['category'];

                            echo "<option value = $id> $cat </option>";
                            // } else {
                            //     $err_msg = "help";
                        }

                        ?>
                    </select>
                    <p>
                        Body of Article: <?php if (isset($Aerr)) {
                                                echo "<font color='red'> **" . $Aerr . "</font> ";
                                            } ?><br />
                    <main>

                        <div class="centered">
                            <textarea name='content' id="editor">
                    <?php
                    if (isset($editor_data)) {
                        echo $editor_data;
                    }
                    ?>
                </textarea>
                        </div>

                    </main>



                    <script>
                        ClassicEditor
                            .create(document.querySelector('#editor'), {
                                // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
                            })
                            .then(editor => {
                                window.editor = editor;
                            })
                            .catch(err => {
                                console.error(err.stack);
                            });
                    </script>


                    </p>
                    <p>
                        <br>
                        Publish?:<?php if (isset($Cerr)) {
                                        echo "<font color='red'> **" . $Perr . "</font> ";
                                    } ?><br>
                        <input type="radio" id="1" name="publish" value="1">
                        <label for="1">YES</label><br>
                        <input type="radio" id="0" name="publish" value="0">
                        <label for="0">NO</label><br>
                        <br>

                        <input type='submit' name='enter' value='Enter' class="btn btn-primary">
                        <?php if (isset($msg)) {
                            echo "<font color='green'> **" . $msg . "</font> ";
                        } ?>
                    </p>
                </form>
                <a href="index.php"> back </a>
            </div>
        </body>
    </div>
    <!-- Javascript -->
    <script src="assets/plugins/jquery-3.3.1.min.js"></script>
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>


</html>