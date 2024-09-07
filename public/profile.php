<?php
include_once "../app/autoload.php";


$email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];

$db = new Database();

$select = $db->GetRows("SELECT * FROM users WHERE email = ?", [$email]);

foreach ($select as $row) {



    if (isset($_POST['upload_profile'])) {

        if (isset($_FILES['file'])) {
            $file = $_FILES['file']['name'];
            $file_size = $_FILES['file']['size'];
            $file_error = $_FILES['file']['error'];
            $fileExt = explode(".", $file);
            $fileActualExt = strtolower(end($fileExt));
            $allowed = array("jpg", "jpeg", "png", "svg");
            if (in_array($fileActualExt, $allowed)) {
                if ($file_error == 0) {
                    if ($file_size < 600000000) {
                        $new_name = time() . '.' . $fileActualExt;
                        $target = "images/" . $new_name;
                        if (!empty($file)) {
                            move_uploaded_file($_FILES['file']['tmp_name'], $target);
                            $db = new Database();
                            $update = $db->Update("UPDATE `users` SET `profile`= ?  WHERE email = ?", [$new_name, $email]);
                            redirect('profile.php', "");
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
    }




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
        redirect('profile.php', '');
    } elseif (isset($text) && isset($filepath)) {
        $text = sanitize($_POST['text']);
        $db = new Database();
        $insert = $db->Insert("INSERT INTO `posts`(`file`, `text`, `user_id`) VALUES (?,?,?)", [$filepath, $text, $user_id]);
        redirect('profile.php', '');
    } elseif (isset($text) && !isset($filepath)) { {
            $text = sanitize($_POST['text']);
            $db = new Database();
            $insert = $db->Insert("INSERT INTO `posts`( `text`, `user_id`) VALUES (?,?)", [$text, $user_id]);
            redirect('profile.php', '');
        }
    }

?>

    <!DOCTYPE html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" type="image/png" href="assets/images/logo-16x16.png" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Social Media - Profile</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
        <link href='https://cdn.jsdelivr.net/npm/boxicons@1.9.2/css/boxicons.min.css' rel='stylesheet'>

        <!-- Styles -->
        <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/components.css" rel="stylesheet">
        <link href="assets/css/profile.css" rel="stylesheet">
        <link href="assets/css/media.css" rel="stylesheet">
        <script src="assets/js/load.js" type="text/javascript"></script>
    </head>

    <body class="profile">
        <div class="container-fluid newsfeed d-flex" id="wrapper">
            <div class="row newsfeed-size">
                <div class="col-md-12 p-0">
                    
                    
                    
                    <div class="row profile-right-side-content">
                        <?php include_once "inc/navbar.php" ?>
                        <div class="user-profile">
                            <div style="height: 100px;">

                            </div>

                            <div class="row profile-rows">
                                <div class="col-md-3">
                                    <div class="profile-info-left">
                                        <div class="text-center">
                                            <div class="profile-img w-shadow">
                                                <div class="profile-img-overlay"></div>
                                                <img src="images/<?= $row['profile'] ?>" alt="Avatar" class="avatar img-circle">

                                            </div>
                                            <p class="profile-fullname mt-3"><?= ucfirst($row['first_name']) . " " . ucfirst($row['last_name']); ?></p>


                                            <form action="" method="post" enctype="multipart/form-data">
                                                <input type="file" name="file" id="file">
                                                <button type="submit" class="btn btn-sm btn-info" name="upload_profile">Upload</button>
                                            </form>
                                        </div>

                                        <div class="intro mt-5 mv-hidden">


                                            <div class="intro-item d-flex justify-content-between align-items-center">
                                                <p class="intro-title text-muted"><i class='bx bx-time text-primary'></i> Last Login <a href="#">Online <span class="ml-1 online-status bg-success"></span></a></p>
                                            </div>

                                        </div>
                                        <div class="intro mt-5 row mv-hidden">
                                        
                                        </div>
                                        <div class="intro mt-5 mv-hidden">
                                            <div class="intro-item d-flex justify-content-between align-items-center">
                                                <h3 class="intro-about">Other Social Accounts</h3>
                                            </div>
                                            <div class="intro-item d-flex justify-content-between align-items-center">
                                                <p class="intro-title text-muted"><i class='bx bxl-facebook-square facebook-color'></i> <a href="#" target="_blank">facebook.com/username</a></p>
                                            </div>
                                            <div class="intro-item d-flex justify-content-between align-items-center">
                                                <p class="intro-title text-muted"><i class='bx bxl-twitter twitter-color'></i> <a href="#" target="_blank">twitter.com/username</a></p>
                                            </div>
                                            <div class="intro-item d-flex justify-content-between align-items-center">
                                                <p class="intro-title text-muted"><i class='bx bxl-instagram instagram-color'></i> <a href="#" target="_blank">instagram.com/username</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 p-0">
                                    <div class="profile-info-right">

                                        <!-- Posts section -->
                                        <div class="row">
                                            <div class="col-md-9 profile-center">
                                               
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


                                                <?php $select2 = $db->GetRows("SELECT * FROM `posts` WHERE user_id = ? order by id desc ", [$user_id]);

                                                foreach ($select2 as $row2):

                                                ?>
                                                    <div class="post border-bottom p-3 bg-white w-shadow">
                                                        <div class="media text-muted pt-3">
                                                            <img src="images/<?= $row['profile'] ?>" alt="Online user" class="mr-3 post-user-image">
                                                            <div class="media-body pb-3 mb-0 small lh-125">
                                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                                    <span class="post-type text-muted"><span class="text-gray-dark post-user-name mr-2"><?= ucfirst($row['first_name']) . " " . ucfirst($row['last_name']); ?></span>
                                                                        <div class="dropdown">

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
                                                                                            <span class="fs-9">Snooze Arthur for 30 days</span>
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
                                                                <img src="posts/<?= $row2['file']; ?>" class="w-100 mb-3">
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="mb-2">

                                                            <!-- Reactions -->
                                                            <div class="argon-reaction">
                                                                <span class="like-btn">
                                                                    <a href="#" class="post-card-buttons" id="reactions"><i class='bx bxs-like mr-2'></i> 67</a>
                                                                    <ul class="reactions-box dropdown-shadow">
                                                                        <li class="reaction reaction-like" data-reaction="Like"></li>
                                                                        <li class="reaction reaction-love" data-reaction="Love"></li>
                                                                        <li class="reaction reaction-haha" data-reaction="HaHa"></li>
                                                                        <li class="reaction reaction-wow" data-reaction="Wow"></li>
                                                                        <li class="reaction reaction-sad" data-reaction="Sad"></li>
                                                                        <li class="reaction reaction-angry" data-reaction="Angry"></li>
                                                                    </ul>
                                                                </span>
                                                            </div>

                                                            <a href="javascript:void(0)" class="post-card-buttons" id="show-comments"><i class='bx bx-message-rounded mr-2'></i> 5</a>
                                                            <div class="dropdown dropup share-dropup">
                                                                <a href="#" class="post-card-buttons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class='bx bx-share-alt mr-2'></i> Share
                                                                </a>
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
                                                                                            <form action="" method="" role="form">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="input-group">
                                                                                                            <input type="text" class="form-control comment-input" placeholder="Write a comment...">

                                                                                                            <div class="input-group-btn">
                                                                                                                <button type="button" class="btn comment-form-btn" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bxs-smiley-happy'></i></button>
                                                                                                                <button type="button" class="btn comment-form-btn comment-form-btn" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bx-camera'></i></button>
                                                                                                                <button type="button" class="btn comment-form-btn comment-form-btn" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bx-microphone'></i></button>
                                                                                                                <button type="button" class="btn comment-form-btn" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class='bx bx-file-blank'></i></button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="media">
                                                                                        <a href="#" class="pull-left">
                                                                                            <img src="assets/images/users/user-2.jpg" alt="" class="img-circle">
                                                                                        </a>
                                                                                        <div class="media-body">
                                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                                <strong class="text-gray-dark"><a href="#" class="fs-8">Karen Minas</a></strong>
                                                                                                <a href="#"><i class='bx bx-dots-horizontal-rounded'></i></a>
                                                                                            </div>
                                                                                            <span class="d-block comment-created-time">30 min ago</span>
                                                                                            <p class="fs-8 pt-2">
                                                                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                                                                Lorem ipsum dolor sit amet, <a href="#">#consecteturadipiscing </a>.
                                                                                            </p>
                                                                                            <div class="commentLR">
                                                                                                <button type="button" class="btn btn-link fs-8">Like</button>
                                                                                                <button type="button" class="btn btn-link fs-8">Reply</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="media">
                                                                                        <a href="#" class="pull-left">
                                                                                            <img src="https://bootdey.com/img/Content/user_2.jpg" alt="" class="img-circle">
                                                                                        </a>
                                                                                        <div class="media-body">
                                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                                <strong class="text-gray-dark"><a href="#" class="fs-8">Lia Earnest</a></strong>
                                                                                                <a href="#"><i class='bx bx-dots-horizontal-rounded'></i></a>
                                                                                            </div>
                                                                                            <span class="d-block comment-created-time">2 hours ago</span>
                                                                                            <p class="fs-8 pt-2">
                                                                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                                                                Lorem ipsum dolor sit amet, <a href="#">#consecteturadipiscing </a>.
                                                                                            </p>
                                                                                            <div class="commentLR">
                                                                                                <button type="button" class="btn btn-link fs-8">Like</button>
                                                                                                <button type="button" class="btn btn-link fs-8">Reply</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="media">
                                                                                        <a href="#" class="pull-left">
                                                                                            <img src="https://bootdey.com/img/Content/user_3.jpg" alt="" class="img-circle">
                                                                                        </a>
                                                                                        <div class="media-body">
                                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                                <strong class="text-gray-dark"><a href="#" class="fs-8">Rusty Mickelsen</a></strong>
                                                                                                <a href="#"><i class='bx bx-dots-horizontal-rounded'></i></a>
                                                                                            </div>
                                                                                            <span class="d-block comment-created-time">17 hours ago</span>
                                                                                            <p class="fs-8 pt-2">
                                                                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                                                                Lorem ipsum dolor sit amet, <a href="#">#consecteturadipiscing </a>.
                                                                                            </p>
                                                                                            <div class="commentLR">
                                                                                                <button type="button" class="btn btn-link fs-8">Like</button>
                                                                                                <button type="button" class="btn btn-link fs-8">Reply</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="media">
                                                                                        <div class="media-body">
                                                                                            <div class="comment-see-more text-center">
                                                                                                <button type="button" class="btn btn-link fs-8">See More</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New message modal -->
        <div class="modal fade" id="newMessageModal" tabindex="-1" role="dialog" aria-labelledby="newMessageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header new-msg-header">
                        <h5 class="modal-title" id="newMessageModalLabel">Start new conversation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body new-msg-body">
                        <form action="" method="" class="new-msg-form">
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Message:</label>
                                <textarea class="form-control search-input" rows="5" id="message-text" placeholder="Type a message..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer new-msg-footer">
                        <button type="button" class="btn btn-primary btn-sm">Send message</button>
                    </div>
                </div>
            </div>
        </div>
    <?php }   ?>
    <!-- Core -->
    <script src="assets/js/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/js/popper/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    <!-- Optional -->
    <script src="assets/js/app.js"></script>
    <script src="assets/js/components/components.js"></script>


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
    </body>

    </html>