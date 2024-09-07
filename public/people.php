<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../app/autoload.php";
include "../app/add_comment.php";



$email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];


$db = new Database();

$select = $db->GetRows("SELECT * FROM users WHERE email !=?", [$email]);



?>
    <!DOCTYPE html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Social Media - Home</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
        <link href="assets/css/boxicons.min.css" rel="stylesheet">

        <!-- Styles -->
        <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/custom-css/comments-css.css" rel="
        stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
        <!-- <link href="assets/css/components.css" rel="stylesheet"> -->
        <link href="assets/css/find-people.css" rel="stylesheet">
        <link href="assets/css/media.css" rel="stylesheet">
        <link href="assets/css/chat.css" rel="stylesheet">
        <link href="https://vjs.zencdn.net/7.4.1/video-js.css" rel="stylesheet">
        <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js" type="text/javascript"></script>
        <script src="assets/js/load.js" type="text/javascript"></script>
    </head>

    <body class="newsfeed">
        <div class="container-fluid" id="wrapper">
            <div class="row newsfeed-size">
                <div class="col-md-12 newsfeed-right-side">

                    <?php include_once "inc/navbar.php" ?>



                    <?php include_once "inc/sidebar.php" ?>


        <div class="listed-users-container">
            <?php
                foreach ($select as $row) {
                    echo "<a href='user-view.php?uid=". htmlspecialchars($row['id']) ."' class='listed-user'>";
                        echo "<div class='img-container'>";
                            echo "<img src='images/" . $row['profile'] . "'/>";
                            // echo "<img src='assets/images/avatar.png'/>";
                        echo "</div>";
                        echo "<div>";
                            echo "<p class='user-name'>";  echo $row["first_name"] ; echo " "; echo $row["last_name"]; echo "</p>";
                            echo "<p class='email'>";  echo $row["email"] ; echo "</p>";
                            echo "<p class='dofb'>";  echo $row["dofb"] ; echo "</p>";
                        echo "</div>";
                    echo "</a>";
                }
            ?>
        </div>

    <!-- Core -->
    <script src="assets/js/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/js/popper/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    <!-- Optional -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script type="text/javascript">
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/components/components.js"></script>
    <script src="../app/add_comment.js" defer></script>
    </body>

    </html>