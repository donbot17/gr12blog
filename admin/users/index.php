<?php
session_start();

// If not authed redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

if(@isset($_POST['sortName'])){
    if($_SESSION['asc']== true){
        $order = "ORDER BY users.username ASC";
        $_SESSION['asc']= false;
    }else{
        $order = "ORDER BY users.username DESC";
        $_SESSION['asc']= true;
    }
}
if(@isset($_POST['sortEmail'])){
    if($_SESSION['asc']== true){
        $order = "ORDER BY users.email ASC";
        $_SESSION['asc']= false;
    }else{
        $order = "ORDER BY users.email DESC";
        $_SESSION['asc']= true;
    }
}
if(@isset($_POST['sortRole'])){
    if($_SESSION['asc']== true){
        $order = "ORDER BY users.role ASC";
        $_SESSION['asc']= false;
    }else{
        $order = "ORDER BY users.role DESC";
        $_SESSION['asc']= true;
    }
}
if(@isset($_POST['sortDate'])){
    if($_SESSION['asc']== true){
        $order = "ORDER BY users.regisdate ASC";
        $_SESSION['asc']= false;
    }else{
        $order = "ORDER BY users.regisdate DESC";
        $_SESSION['asc']= true;
    }
}
require('../../conn.php');
if (isset($_GET['search']) && $_GET['search'] === '') header("Location: ./");
$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
@$query = "SELECT * FROM users WHERE username LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%' $order";
$result = mysqli_query($conn, $query);
$res = [];

while ($row = mysqli_fetch_array($result)) {
    if (isset($_GET['search'])) {
        $row['username'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['username']);
        $row['email'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['email']);
        $row['role'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['role']);
        $row['regisdate'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['regisdate']);
        $row['user_id'] = preg_replace('/(' . $search . ')/i', '<mark>$1</mark>', $row['user_id']);
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
                <h2 class="heading"><a href="../">Manage Users</a></h2>
                <div class="intro">Back end with developer tools</div>
                <form method="GET" action="./" class="signup-form form-inline justify-content-center pt-3">
                    <table>
                        <tr>
                            <td><a href="../" class="btn btn-primary">Back</a></td>
                            <td><a href="../posts" class="btn btn-primary">Edit Posts</a></td>
                            <td> <a href="../cat" class="btn btn-primary">Edit Categories</a></td>
                            <td><a href="../comm" class="btn btn-primary">Edit Comments</a></td>
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
                
                <!-- class="table table-striped my-5" -->
                <!-- class="display" -->

                <table class="table table-striped my-5" >
                <thead>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <tr>
                        <th scope="col"><button name = sortName style = 'background-color: transparent; border-color:transparent;color:#6f42c1'><b>Username</b></button></th>
                        <th scope="col"><button name = sortEmail style = 'background-color: transparent; border-color:transparent;color:#6f42c1'><b>Email</b></button></th>
                        <th scope="col"><button name = sortRole style = 'background-color: transparent; border-color:transparent;color:#6f42c1'><b>Role</b></button></th>
                        <th scope="col"><button name = sortDate style = 'background-color: transparent; border-color:transparent;color:#6f42c1'><b>Registration Date</b></button></th>
                        <th scope="col"></th>
                    </tr>
                    </form>
                    
        </thead>
                    <?php
                    foreach ($res as $row) {
                        $name = $row['username'];
                        $email = $row['email'];
                        $role = $row['role'];
                        $id = $row['user_id'];
                        $regis = $row['regisdate'];


                        echo "<tr>
        <td >$name</td>
    <td >$email</td>
    <td >$role</td>
    <td >$regis</td>
    <td ><a class='link' href='../../user/edit?id=$id&redirect=../../admin/users'>Edit</a></td>
    </tr>";
                    }
                    ?>
                </table>
            </div><!--//container-->
        </article>
    </div>
     <!-- Javascript -->
     <script src="../../assets/plugins/jquery-3.3.1.min.js"></script>
        <script src="../../assets/plugins/popper.min.js"></script>
        <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>


</html>