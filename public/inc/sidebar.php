<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include_once "../app/autoload.php";
    $email = $_SESSION['email'];
    $uid = $_SESSION['user_id'];
    $db = new Database();

    $invi_count = 0;
    $invi_count_db = $db->GetRow("SELECT count(*) as count from invitations WHERE recipient_id = ?", [$email]);
    $invi_count = $invi_count_db['count'];
    $friends_count = 0;

    $friends_count_db = $db->GetRow("SELECT friends_list FROM users WHERE id = ?", [$uid]);
    $decoded_arr = json_decode($friends_count_db["friends_list"], true);
    $friends_count = count($decoded_arr);;
?>

<div class="row newsfeed-right-side-content mt-3">
                    <div class="col-md-3 newsfeed-left-side sticky-top shadow-sm" id="sidebar-wrapper">
                        <div class="card newsfeed-user-card h-100">
                            <ul class="list-group list-group-flush newsfeed-left-sidebar">
                                <li class="list-group-item">
                                    <h6>Home</h6>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center sd-active">
                                    <a href="home.php" class="sidebar-item"><img src="assets/images/icons/left-sidebar/newsfeed.png" alt="newsfeed"> News Feed</a>
                                    <a href="#" class="newsfeedListicon"><i class='bx bx-dots-horizontal-rounded'></i></a>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="messages.php" class="sidebar-item"><img src="assets/images/icons/left-sidebar/message.png" alt="message"> Messages</a>
                                    <span class="badge badge-primary badge-pill">2</span>
                                </li>
                              
                            
                                <!-- <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="friends.php" class="sidebar-item"><img src="assets/images/icons/left-sidebar/find-friends.png" alt="find-friends"> Find Friends</a>
                                    <span class="badge badge-primary badge-pill"><i class='bx bx-chevron-right'></i></span>
                                </li> -->
                                
                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="people.php" class="sidebar-item"><img src="assets/images/icons/left-sidebar/find-friends.png" alt="find-friends"> Find People</a>
                                    <span class="badge badge-primary badge-pill"><i class='bx bx-chevron-right'></i></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="invitations.php" class="sidebar-item"><img src="assets/images/icons/left-sidebar/find-friends.png" alt="find-friends"> Invitations: <?php echo $invi_count; ?></a>
                                    <span class="badge badge-primary badge-pill"><i class='bx bx-chevron-right'></i></span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="my-friends.php" class="sidebar-item"><img src="assets/images/icons/left-sidebar/find-friends.png" alt="find-friends"> My Friends: <?php echo $friends_count; ?></a>
                                    <span class="badge badge-primary badge-pill"><i class='bx bx-chevron-right'></i></span>
                                </li>
                          
                            </ul>
                        </div>
                    </div>
                
                
             