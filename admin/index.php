<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
// Get all users from DB
require_once('../conn.php');

$query = "SELECT * FROM users";
$res = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));

// --------------------- STATS ---------------------
// Get all posts with their category name from DB
$postsQuery = "SELECT posts.title, posts.datepost, categories.category AS category FROM posts JOIN categories ON posts.category = categories.cat_id";
$posts = mysqli_query($conn, $postsQuery) or die("Query failed: " . mysqli_error($conn));

// Get all comments from DB
$commentsQuery = "SELECT * FROM comments";
$comments = mysqli_query($conn, $commentsQuery) or die("Query failed: " . mysqli_error($conn));

// Get all categories from DB
$categoriesQuery = "SELECT * FROM categories";
$categories = mysqli_query($conn, $categoriesQuery) or die("Query failed: " . mysqli_error($conn));
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
                        <li class="nav-item active">
                            <a class="nav-link" href=""><i class="fas fa-user fa-fw mr-2"></i>Admin Panel</a>
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
        <section class="cta-section theme-bg-light py-5">
            <div class="container text-center">
                <h2 class="heading">Admin Panel</h2>
                <div class="intro">Back end with developer tools</div>
                <form method="GET" action="./" class="signup-form form-inline justify-content-center pt-3">
                    <table>
                        <tr>
                            <td><a href="users" class="btn btn-primary">Edit Users</a></td>
                            <td><a href="posts" class="btn btn-primary">Edit Posts</a></td>
                            <td> <a href="cat" class="btn btn-primary">Edit Categories</a></td>
                            <td><a href="comm" class="btn btn-primary">Edit Comments</a></td>
                        </tr>
                    </table>
                </form>
            </div><!--//container-->
        </section>
        <div class="container">

<center>
            <h2>Activity in the Past 7 Days</h2>
                    </center>

            <body>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <!-- Create chart of posts in last 14 days -->
                <h5 style = 'color: grey'>New Posts</h5>
                <canvas id="myChart" width="500" height="200"></canvas>
                <script>
                    let ctx = document.getElementById('myChart').getContext('2d');
                let myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [
                                <?php
                                // Get the last 14 days
                                $days = array();
                                for ($i = 0; $i <  7; $i++) {
                                    $days[] = date("M d, Y", strtotime("-$i days"));
                                }
                                // Get the day for each post
                                $postsPerDay = array();
                                foreach ($days as $day) {
                                    $postsPerDay[$day] = 0;
                                }
                                while ($row = mysqli_fetch_assoc($posts)) {
                                    $date = date("M d, Y", strtotime($row['datepost']));

                                    if (array_key_exists($date, $postsPerDay)) {
                                        $postsPerDay[$date]++;
                                    }
                                }
                                // Create labels for chart
                                $postsPerDay = array_reverse($postsPerDay);
                                 foreach ($postsPerDay as $day => $count) {
                                    echo "'$day', ";
                                }
                            
                                ?>
                            ],
                            datasets: [{
                                    label: '# of Posts',
                                    data: [
                                        <?php
                                        // Create data for chart
                                        foreach ($postsPerDay as $day => $count) {
                                            echo "$count, ";
                                        }
                                        ?>
                                    ],
                                    Color: [
                                        'rgba(224, 29, 29)',
                                    ],
                                    borderWidth: 1
                                }
                               
                            ]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                    },
                                }
                            }
                        }
                    });
                </script>
                <h5 style = 'color: grey'>New Comments</h5>
         
                <canvas id="myChart2" width="500" height="200"></canvas>
                <script>
                let ctx2 = document.getElementById('myChart2').getContext('2d');
                let myChart2 =new Chart(ctx2, {
                        type: 'line',
                        data: {
                            labels: [
                                <?php
                                // Get the last 14 days
                                $days = array();
                                for ($i = 0; $i <  7; $i++) {
                                    $days[] = date("M d, Y", strtotime("-$i days"));
                                }
                                $cpd = array();
                                foreach ($days as $day) {
                                    $cpd[$day] = 0;
                                }
                                while ($row = mysqli_fetch_assoc($comments)) {
                                    $date = date("M d, Y", strtotime($row['datepost']));

                                    if (array_key_exists($date, $cpd)) {
                                        $cpd[$date]++;
                                    }
                                }
                                // Create labels for chart
                                $cpd = array_reverse($cpd);
                                 foreach ($cpd as $day => $count) {
                                    echo "'$day', ";
                                }
                            
                                ?>
                            ],
                            datasets: [{
                                    label: '# of New Comments',
                                    data: [
                                        <?php
                                        // Create data for chart
                                        foreach ($cpd as $day => $count) {
                                            echo "$count, ";
                                        }
                                        ?>
                                    ],
                                    Color: [
                                        'rgb(201, 0, 0)',
                                    ],
                                    borderWidth: 1
                                }
                               
                            ]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                    },
                                }
                            }
                        }
                    });
                </script>
 <h5 style = 'color: grey'> New Users</h5>
         
         <canvas id="myChart4" width="500" height="200"></canvas>
         <script>
         let ctx4 = document.getElementById('myChart4').getContext('2d');
         let myChart4 =new Chart(ctx4, {
                 type: 'line',
                 data: {
                     labels: [
                         <?php
                         // Get the last 14 days
                         $days = array();
                         for ($i = 0; $i <  7; $i++) {
                             $days[] = date("M d, Y", strtotime("-$i days"));
                         }
                         $upd = array();
                         foreach ($days as $day) {
                             $upd[$day] = 0;
                         }
                         while ($row = mysqli_fetch_assoc($res)) {
                             $date = date("M d, Y", strtotime($row['regisdate']));

                             if (array_key_exists($date, $upd)) {
                                 $upd[$date]++;
                             }
                         }
                         // Create labels for chart
                         $upd = array_reverse($upd);
                          foreach ($upd as $day => $count) {
                             echo "'$day', ";
                         }
                     
                         ?>
                     ],
                     datasets: [{
                             label: '# of New Users',
                             data: [
                                 <?php
                                 // Create data for chart
                                 foreach ($upd as $day => $count) {
                                     echo "$count, ";
                                 }
                                 ?>
                             ],
                             Color: [
                                 'rgb(201, 0, 0)',
                             ],
                             borderWidth: 1
                         }
                        
                     ]
                 },
                 options: {
                     scales: {
                         y: {
                             beginAtZero: true,
                             ticks: {
                                 stepSize: 1,
                             },
                         }
                     }
                 }
             });
         </script>

                <h3>Posts By Category</h3>
                    <canvas id="myChart3" width="250" height="100"></canvas>
                </div>
                <script>
                    let ctx3 = document.getElementById('myChart3').getContext('2d');
                    let myChart3 = new Chart(ctx3, {
                        type: 'pie',
                        data: {
                            labels: [
                                <?php
                                $cats = [];
                                while ($row = mysqli_fetch_assoc($categories)) {
                                    array_push($cats, $row['category']);
                                }
                                array_unique($cats);
                                foreach ($cats as $cat) {
                                    echo "'$cat', ";
                                }
                                ?>
                            ],
                            datasets: [{
                                label: '# of Posts',
                                data: [
                                    <?php
                                    foreach ($cats as $cat) {
                                        $count = 0;

                                        mysqli_data_seek($posts, 0);

                                        while ($row = mysqli_fetch_assoc($posts)) {
                                            if ($row['category'] == $cat) $count++;
                                        }

                                        echo $count . ',';
                                    }
                                    ?>
                                ],
                                backgroundColor: [
                                    'rgb(127, 174, 186)',
                                    'rgb(127, 153, 186)',
                                    'rgb(127, 132, 186)',
                                    'rgb(149, 127, 186)',
                                    'rgb(165, 127, 186)',
                                    'rgb(186, 127, 179)',
                                    'rgb(186, 127, 149)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    position: 'right'
                                }
                            }
                        }
                    });
                </script>
  <center>
                <table cell-spacing=5px>
                    <tr>
                        <td>
                            <b>Total Users</b>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <b>Total Posts</b>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <b>Total Comments</b>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo mysqli_num_rows($res); ?></td>
                        <td></td>
                        <td></td>
                        <td><?php echo mysqli_num_rows($posts); ?></td>
                        <td></td>
                        <td></td>
                        <td><?php echo mysqli_num_rows($comments); ?></td>
                    </tr>
                </table>
            </center>
    </div>
    <!-- STATS END -->

    <br>
    </div>

    <!-- ADMIN DASHBOARD END -->
    <!-- </body> -->
    <script src="../assets/plugins/jquery-3.3.1.min.js"></script>
    <script src="../assets/plugins/popper.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>

</html>