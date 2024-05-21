<?php
session_start();
require('../../conn.php');
$id = $_GET['id'];
$query = "SELECT * FROM `users` WHERE `user_id` = $id";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $uname = $row['username'];
    $email = $row['email'];
    $pwd = $row['pwd'];
}
if (isset($_POST['update'])) {
    $lengU = $_POST['name'];
    $lengE = $_POST['email'];
    $blacklistChars = '"%\'*;<>?^`{|}~/\\#=&';
    $pattern = preg_quote($blacklistChars, '/');
    $Atsym = "@";
    $pat = preg_quote($Atsym, '/');
    if (!isset($_POST['publish'])) {
        $Perr = "Please select an option.";
    } elseif (preg_match("/[0-9]/", $_POST['name'])) {
        $msg =  "Username may not contain numbers";
    } elseif (empty($_POST['name'])) {
        $msg =  "Username is missing";
    } elseif (strlen($lengU) > 33) {
        $msg =  "Username cannot exceed 33 characters";
    } elseif (preg_match('/[' . $pattern . ']/', $_POST['name'])) {
        $msg = "Username may not contain speacial characters";
    } elseif (empty($_POST['email'])) {
        $msg =  "email is missing";
    } elseif (strlen($lengE) > 55) {
        $msg =  "email cannot exceed 55 characters";
    } elseif (preg_match('/[' . $pattern . ']/', $_POST['email'])) {
        $msg = "email may not contain speacial characters";
    } elseif (!preg_match('/[' . $pat . ']/', $_POST['email'])) {
        $msg =  "email must contain the '@' symbol and a '.'";
    } else {
        $selectedOption = $_POST['publish'];
        $msg = "successfully updated!";
        $uname = $lengU;
        $email = $lengE;
        $query = "UPDATE `users` SET `username` = '$lengU', `email` = '$lengE', `role` = '$selectedOption'  WHERE `users`.`user_id` = $id;";

        mysqli_query($conn, $query) or die("kys pt 2");
    }
}
if (isset($_POST['upwd'])) {
    $Atsym = "@";
    if ($_POST['npwd'] == $_POST['vpwd']) {
        $lengP = $_POST['vpwd'];
        if (empty($_POST['npwd'])) {
            $msg2 =  "Password data is missing";
        } elseif (preg_match("/$Atsym/", $_POST['npwd'])) {
            $msg2 =  "Password may not contain the @ symbol";
        } elseif (strlen($lengP) > 77) {
            $msg2 =  "Password cannot exceed 77 characters";
        } else {
            $msg2 = "password successfully updated!";
            $lengP = $_POST['vpwd'];
            $p = password_hash($lengP, PASSWORD_DEFAULT);
            $query = "UPDATE `users` SET `pwd` = '$p' WHERE `users`.`user_id` = $id;";
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
    <link id="theme-style" rel="stylesheet" href="../../assets/css/theme-7.css">

</head>

<body>

    <header class="header text-center">
        <h1 class="blog-name pt-lg-4 mb-0"><a href="../../index.php">For the Record</a></h1>

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
                        <li class="nav-item">
                            <a class="nav-link" href="../../admin"><i class="fas fa-user fa-fw mr-2"></i>Admin Panel</a>
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
                <h2 class="heading">Edit User</h2>
            </div><!--//container-->
        </section>

        <body>
            <div class="container">
                <h5> Edit profile</h5>
                <form action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] . (isset($_GET['redirect']) ? "&redirect=" . $_GET['redirect'] : "") ?>" method="POST">
                    username: <input type=text name="name" placeholder="Enter name" value="<?php echo $uname ?>" required><br>
                    <br>email: <input type=text name="email" placeholder="Enter email" value="<?php echo $email ?>" required>
                    <?php
                    if ($_SESSION['role'] == 'admin') { ?>

                        <br><br>role: <br>
                        <input type="radio" id="admin" name="publish" value="admin" required>
                        <label for="admin">admin</label><br>
                        <input type="radio" id="basic" name="publish" value="basic">
                        <label for="basic">basic</label><br>
                    <?php } ?>
                    <br><input type=submit name="update" value="update info" class="btn btn-primary"><br>
                    <?php
                    if (isset($msg)) {
                        echo $msg;
                    } ?>
                    <br> <br>
                    <h5> Change Password </h5>
                    new password: <input type=text name="npwd" placeholder="enter new password">
                    <br> <br>verify password: <input type=text name="vpwd" placeholder="enter verified password">
                    <br> <br><input type=submit name="upwd" value="update password" class="btn btn-primary">
                    <br> <?php if (isset($msg2)) {
                                echo $msg2;
                            } ?>
                </form>

            </div>
        </body>
    </div>
    <!-- Javascript -->
    <script src="../../assets/plugins/jquery-3.3.1.min.js"></script>
    <script src="../../assets/plugins/popper.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>




</html>