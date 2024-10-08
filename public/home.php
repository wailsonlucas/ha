<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../app/autoload.php";
include "../app/add_comment.php";



$email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];

// if (isset($_POST['submit_comm'])) {
//     $comm = $_POST['comm'];
//     $post_id = $_POST['post_id'];
//     $db = new Database();
//     $insert = $db->Insert("INSERT INTO `comments`( `post_id`, `user_id`, `comment`) VALUES (?,?,?)", [$post_id, $user_id, $comm]);
//     redirect('home.php', 'Comment Posted');
// }


$db = new Database();

$select = $db->GetRows("SELECT * FROM users WHERE email = ?", [$email]);

foreach ($select as $row) {


    if (isset($_POST['text'])) {
        $text = sanitize($_POST['text']);
    }




    if (isset($_FILES['filePost'])) {
        $file = $_FILES['filePost']['name'];
        $file_size = $_FILES['filePost']['size'];
        $file_error = $_FILES['filePost']['error'];
        $fileExt = explode(".", $file);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array("jpg", "jpeg", "png", "svg");
        if (in_array($fileActualExt, $allowed)) {
            if ($file_error == 0) {
                if ($file_size < 600000000) {
                    $new_name = time() . '.' . $fileActualExt;
                    $target = "posts/" . $new_name;
                    if (!empty($file)) {
                        move_uploaded_file($_FILES['filePost']['tmp_name'], $target);

                        $filepath = $new_name;
                    }
                } else {
                    $_SESSION['error'] =  "file too big";
                }
            } else {

                $_SESSION['error'] =  "Error in your file";
            }
        } else {
            $_SESSION['error'] =  "Error  in type of file | accept: jpg , jpeg , png , svg";
        }
    }


    if (isset($filepath) && !isset($text)) {
        $text = sanitize($_POST['text']);
        $db = new Database();
        $insert = $db->Insert("INSERT INTO `posts`(`file`, `user_id`) VALUES (?,?)", [$filepath, $user_id]);
        redirect('home.php', '');
    } elseif (isset($text) && isset($filepath)) {
        $text = sanitize($_POST['text']);
        $db = new Database();
        $insert = $db->Insert("INSERT INTO `posts`(`file`, `text`, `user_id`) VALUES (?,?,?)", [$filepath, $text, $user_id]);
        redirect('home.php', '');
    } elseif (isset($text) && !isset($filepath)) { {
            $text = sanitize($_POST['text']);
            $db = new Database();
            $insert = $db->Insert("INSERT INTO `posts`( `text`, `user_id`) VALUES (?,?)", [$text, $user_id]);
            redirect('home.php', '');
        }
    }






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
        <link href="assets/css/components.css" rel="stylesheet">
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

                    <div class="col-md-6 second-section" id="page-content-wrapper">


                        <ul class="list-unstyled" style="margin-bottom: 0;">
                            <form action="" method="post" enctype="multipart/form-data">
                                <li class="media post-form w-shadow">
                                    <div class="media-body">
                                        <div class="form-group post-input">
                                            <textarea name="text" class="form-control" id="postForm" rows="2" placeholder="What's on your mind?"></textarea>
                                        </div>

                                        <input type="file" name="filePost" id="">
                                        <div class="row post-form-group">
                                            <div class="col-md-9">

                                            </div>
                                            <div class="col-md-3 text-right">
                                                <button name="posted" class="btn btn-primary btn-sm">Publish</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </form>
                        </ul>

                        <!-- Posts -->
                        <div class="posts-section mb-5">
                            <?php $select2 = $db->GetRows("SELECT users.first_name,
                                users.id,
                                users.last_name,
                                users.profile,
                                users.status,
                                posts.file,
                                posts.text,
                                posts.likes,
                                posts.user_id,
                                posts.id,
                                COUNT(comments.id) AS comment_count

                             FROM users 
                             INNER JOIN posts ON posts.user_id = users.id
                             LEFT JOIN comments ON comments.post_id = posts.id
                             GROUP BY posts.id, users.id
                             ORDER BY posts.id DESC", []);

                            foreach ($select2 as $row2):




                            ?>
                                <div class="post border-bottom p-3 bg-white w-shadow">
                                    <div class="media text-muted pt-3">
                                        <img src="assets/images/users/user-1.jpg" alt="Online user" class="mr-3 post-user-image">
                                        <div class="media-body pb-3 mb-0 small lh-125">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <a href="#" class="text-gray-dark post-user-name"><?= ucfirst($row['first_name']) . " " . ucfirst($row['last_name']); ?></a>
                                                <div class="dropdown">
                                                    <a href="#" class="post-more-settings" role="button" data-toggle="dropdown" id="postOptions" aria-haspopup="true" aria-expanded="false">
                                                        <i class='bx bx-dots-horizontal-rounded'></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left post-dropdown-menu">
                                                        <a href="#" class="dropdown-item" aria-describedby="savePost">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <i class='bx bx-bookmark-plus post-option-icon'></i>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <span class="fs-9">Save post</span>
                                                                    <small id="savePost" class="form-text text-muted">Add this to your saved items</small>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="dropdown-item" aria-describedby="hidePost">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <i class='bx bx-hide post-option-icon'></i>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <span class="fs-9">Hide post</span>
                                                                    <small id="hidePost" class="form-text text-muted">See fewer posts like this</small>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="dropdown-item" aria-describedby="snoozePost">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <i class='bx bx-time post-option-icon'></i>
                                                                </div>
                                                                <div class="col-md-10">

                                                                    <small id="snoozePost" class="form-text text-muted">Temporarily stop seeing posts</small>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="dropdown-item" aria-describedby="reportPost">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <i class='bx bx-block post-option-icon'></i>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <span class="fs-9">Report</span>
                                                                    <small id="reportPost" class="form-text text-muted">I'm concerned about this post</small>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <p><?= $row2['text']; ?></p>
                                    </div>
                                    <div class="d-block mt-3">
                                        <?php if ($row2['file'] != ""): ?>
                                            <img src="posts/<?= $row2['file']; ?>" class="post-content" alt="post image">
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3">
                                        <!-- Reactions -->
                                        <div class="argon-reaction">
    <span class="like-btn">
        <a href="javascript:void(0)"  class="post-card-buttons" id="reactions" data-pid="<?= $row2['id'] ?>" data-likes-conut="<?= $row2['likes'] ?>">
            <i  class='bx bxs-like mr-2'></i><?= $row2['likes'] ?></a>

        <ul class="reactions-box dropdown-shadow">
            <li class="reaction reaction-like" data-reaction="Like"></li>
        </ul>
    </span>
                                        </div>
                                        <a href="javascript:void(0)" class="post-card-buttons" id="show-comments"><i class='bx bx-message-rounded mr-2'></i> <?= $row2["comment_count"] ?></a>
                                        <div class="dropdown dropup share-dropup">

                                            <div class="dropdown-menu post-dropdown-menu">
                                                <a href="#" class="dropdown-item">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <i class='bx bx-share-alt'></i>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <span>Share Now (Public)</span>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="dropdown-item">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <i class='bx bx-share-alt'></i>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <span>Share...</span>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="#" class="dropdown-item">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <i class='bx bx-message'></i>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <span>Send as Message</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-top pt-3 hide-comments" style="display: none;">
                                        <div class="row bootstrap snippets">
                                            <div class="col-md-12">
                                                <div class="comment-wrapper">
                                                    <div class="panel panel-info">
                                                        <div class="panel-body">
                                                            <ul class="media-list comments-list">
                                                                <li class="media comment-form">
                                                                    <a href="#" class="pull-left">
                                                                        <img src="assets/images/users/user-4.jpg" alt="" class="img-circle">
                                                                    </a>
                                                                    <div class="media-body">
    <form id="comment-form">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">


                    <input type="text" name="comm" class="form-control comment-input" id="comment-input" placeholder="Write a comment...">

                    <input type="number" name="post_id" id="post_id" value="<?= $row2['id']; ?>" hidden>
                    <input type="number" id="uid" value="<?= $row['id']; ?>" hidden>

                    <button type="submit" class="btn btn-primary btn-sm" name="submit_comm">Post</button>
    </form>



                                                                    </div>
                                                        </div>
                                                    </div>
                                                    </form>
                                                </div>
                                                </li>




        <?php

         

            $all_comments = $db->GetRows("SELECT * FROM comments;");
            if(!empty($all_comments)) {

                foreach($all_comments as $comm) {
                  echo '<div id="comments-container">';
                       echo '<div class="comment_child">';
                           echo '<div class="img-container">';
                             echo '<a href="#" class="pull-left">
                                    <img src="assets/images/users/user-4.jpg" alt="" class="img-circle">
                                </a>';
                           echo '</div>';

                           echo '<div class="comment-body">';

                                echo '<p>' . htmlspecialchars($comm['comment']) . '</p>';

                           echo '</div>';

                        echo '</div>';
                    echo '</div>';
                };
            };

        ?>
















                                                <?php $select2 = $db->GetRows("SELECT DISTINCT
    c.id AS comment_id, 
    c.comment, 
    CONCAT(u.first_name, ' ', u.last_name) AS user_name,
    u.profile AS profile_img,
    p.file AS post_file,
    p.text AS post_text
FROM 
    comments c
JOIN 
    users u ON c.user_id = u.id
JOIN 
    posts p ON c.post_id = p.id
WHERE 
    c.post_id = 123; -- Replace with the actual post ID you are testing
", []);

                                                foreach ($select2 as $row2):




                                                ?>


    <li class="media">
        <a href="#" class="pull-left">
            <img src="images/<?= $row2['profile_img'];?>" alt="" class="img-circle">
        </a>
        <div class="media-body">
            <div class="d-flex justify-content-between align-items-center w-100">
                <strong class="text-gray-dark"><a href="#" class="fs-8"><?= $row2['user_name'];?></a></strong>
                <a href="#"><i class='bx bx-dots-horizontal-rounded'></i></a>
            </div>
        
            <p class="fs-8 pt-2">
            <?= $row2['comment'];?>

        </a>.
            </p>
            <div class="commentLR">
                <button type="button" class="btn btn-link fs-8">Like</button>
                <button type="button" class="btn btn-link fs-8">Reply</button>
            </div>
        </div>
    </li>

                                                <?php endforeach; ?>



                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                        </div>
                    </div>
                </div>

                <!-- end of posts -->
            <?php endforeach; ?>

            </div>
        </div>

        </div>
        </div>
        </div>
        </div>

        <!-- Modals -->
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="postModal" aria-labelledby="postModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body post-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-7 post-content">
                                    <img src="https://scontent.fevn1-2.fna.fbcdn.net/v/t1.0-9/56161887_588993861570433_2896723195090436096_n.jpg?_nc_cat=103&_nc_eui2=AeFI0UuTq3uUF_TLEbnZwM-qSRtgOu0HE2JPwW6b4hIki73-2OWYhc7L1MPsYl9cYy-w122CCak-Fxj0TE1a-kjsd-KXzh5QsuvxbW_mg9qqtg&_nc_ht=scontent.fevn1-2.fna&oh=ea44bffa38f368f98f0553c5cef8e455&oe=5D050B05" alt="post-image">
                                </div>
                                <div class="col-md-5 pr-3">
                                    <div class="media text-muted pr-3 pt-3">
                                        <img src="assets/images/users/user-1.jpg" alt="user image" class="mr-3 post-modal-user-img">
                                        <div class="media-body">
                                            <div class="d-flex justify-content-between align-items-center w-100 post-modal-top-user fs-9">
                                                <a href="#" class="text-gray-dark">John Michael</a>
                                                <div class="dropdown">
                                                    <a href="#" class="postMoreSettings" role="button" data-toggle="dropdown" id="postOptions" aria-haspopup="true" aria-expanded="false">
                                                        <i class='bx bx-dots-horizontal-rounded'></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left postDropdownMenu">
                                                        <a href="#" class="dropdown-item" aria-describedby="savePost">
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <i class='bx bx-bookmark-plus postOptionIcon'></i>
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <span class="postOptionTitle">Save post</span>
                                                                    <small id="savePost" class="form-text text-muted">Add this to your saved items</small>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="d-block fs-8">3 hours ago <i class='bx bx-globe ml-3'></i></span>
                                        </div>
                                    </div>
                                    <div class="mt-3 post-modal-caption fs-9">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quis voluptatem veritatis harum, tenetur, quibusdam voluptatum, incidunt saepe minus maiores ea atque sequi illo veniam sint quaerat corporis totam et. Culpa?</p>
                                    </div>
                                </div>

                                <!-- okd -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Popup -->
        <!--
    <div class="chat-popup shadow" id="hide-in-mobile">
        <div class="row chat-window col-xs-5 col-md-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="top-bar shadow-sm d-flex align-items-center">
                        <div class="col-md-6 col-xs-6">
                            <a href="profile.html">
                                <img src="assets/images/users/user-2.jpg" class="mr-2 chatbox-user-img" alt="Chat user image">
                                <span class="panel-title">Karen Minas</span>
                            </a>
                        </div>
                        <div class="col-md-6 col-xs-6 d-flex align-items-center justify-content-between">
                            <a href="#">
                                <img src="assets/images/icons/messenger/video-call.png" class="chatbox-call" alt="Chatbox contact types">
                            </a>
                            <a href="#" data-toggle="modal" data-target="#callModal">
                                <img src="assets/images/icons/messenger/call.png" class="chatbox-call" alt="Chatbox contact types">
                            </a>
                            <a href="javascript:void(0)"><i id="minimize-chat-window" class="bx bx-minus icon_minim"></i></a>
                            <a href="javascript:void(0)" id="close-chatbox"><i class="bx bx-x"></i></a>
                        </div>
                    </div>
                    <div id="messagebody" class="msg_container_base">
                        <div class="row msg_container base_sent">
                            <div class="col-md-10 col-xs-10">
                                <div class="messages message-reply bg-primary shadow-sm">
                                    <p>Are you going to the party on Saturday?</p>
                                </div>
                            </div>
                        </div>
                        <div class="row msg_container base_receive">
                            <div class="col-md-10 col-xs-10">
                                <div class="messages message-receive shadow-sm">
                                    <p>I was thinking about it. Are you?</p>
                                </div>
                            </div>
                        </div>
                        <div class="row msg_container base_receive">
                            <div class="col-xs-10 col-md-10">
                                <div class="messages message-receive shadow-sm">
                                    <p>Really? Well, what time does it start?</p>
                                </div>
                            </div>
                        </div>
                        <div class="row msg_container base_sent">
                            <div class="col-xs-10 col-md-10">
                                <div class="messages message-reply bg-primary shadow-sm">
                                    <p>It starts at 8:00 pm, and I really think you should go.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row msg_container base_receive">
                            <div class="col-xs-10 col-md-10">
                                <div class="messages message-receive shadow-sm">
                                    <p>Well, who else is going to be there?</p>
                                </div>
                            </div>
                        </div>
                        <div class="row msg_container base_sent">
                            <div class="col-md-10 col-xs-10">
                                <div class="messages message-reply bg-primary shadow-sm">
                                    <p>Everybody from school.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer chat-inputs">
                        <div class="col-md-12 message-box">
                            <input type="text" class="w-100 search-input type-message" placeholder="Type a message..." />
                            <div class="chat-tools">
                                <a href="#" class="chatbox-tools">
                                    <img src="assets/images/icons/theme/post-image.png" class="chatbox-tools-img" alt="Chatbox tool">
                                </a>
                                <a href="#" class="chatbox-tools">
                                    <img src="assets/images/icons/messenger/gif.png" class="chatbox-tools-img" alt="Chatbox tool">
                                </a>
                                <a href="#" class="chatbox-tools">
                                    <img src="assets/images/icons/messenger/smile.png" class="chatbox-tools-img" alt="Chatbox tool">
                                </a>
                                <a href="#" class="chatbox-tools">
                                    <img src="assets/images/icons/messenger/console.png" class="chatbox-tools-img" alt="Chatbox tool">
                                </a>
                                <a href="#" class="chatbox-tools">
                                    <img src="assets/images/icons/messenger/attach-file.png" class="chatbox-tools-img" alt="Chatbox tool">
                                </a>
                                <a href="#" class="chatbox-tools">
                                    <img src="assets/images/icons/messenger/photo-camera.png" class="chatbox-tools-img" alt="Chatbox tool">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
-->
        <!-- END Chat Popup -->
    <?php } ?>
    <!-- Call modal -->
    <div id="callModal" class="modal fade call-modal" tabindex="-1" role="dialog" aria-labelledby="callModalLabel" aria-hidden="true">
        <div class="modal-dialog call-modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header align-items-center">
                    <div class="call-status">
                        <h1 id="callModalLabel" class="modal-title mr-3">Connected</h1>
                        <span class="online-status bg-success"></span>
                    </div>
                    <div class="modal-options d-flex align-items-center">
                        <button type="button" class="btn btn-quick-link" id="minimize-call-window">
                            <i class='bx bx-minus'></i>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row h-100">
                        <div class="col-md-12 d-flex align-items-center justify-content-center">
                            <div class="call-user text-center">
                                <div class="call-user-img-anim">
                                    <img src="assets/images/users/user-1.jpg" class="call-user-img" alt="Call user image">
                                </div>
                                <p class="call-user-name">Name Surename</p>
                                <p class="text-muted call-time">05:28</p>
                            </div>
                        </div>
                        <div class="col-md-4 offset-md-4 d-flex align-items-center justify-content-between call-btn-list">
                            <a href="#" class="btn call-btn" data-toggle="tooltip" data-placement="top" data-title="Disable microphone"><i class='bx bxs-microphone'></i></a>
                            <a href="#" class="btn call-btn" data-toggle="tooltip" data-placement="top" data-title="Enable camera"><i class='bx bxs-video-off'></i></a>
                            <a href="#" class="btn call-btn drop-call" data-toggle="tooltip" data-placement="top" data-title="End call" data-dismiss="modal" aria-label="Close"><i class='bx bxs-phone'></i></a>
                            <a href="#" class="btn call-btn" data-toggle="tooltip" data-placement="top" data-title="Share Screen"><i class='bx bx-laptop'></i></a>
                            <a href="#" class="btn call-btn" data-toggle="tooltip" data-placement="top" data-title="Dark mode"><i class='bx bx-moon'></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END call modal -->

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