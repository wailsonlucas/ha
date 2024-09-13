<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../app/autoload.php";

    $uid = $_SESSION['user_id'];
    $client_email = $_SESSION['email'];
    // $target_email = "";
    $db = new Database();

    $friends = $db->GetRow("SELECT friends_list from users WHERE id = ?", [$uid]);
    $favorites = json_decode($friends['friends_list'], true);

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if(isset($data["get_all_messages"])) {
        header('Content-Type: application/json');
       
        $messages_db = $db->GetRows("SELECT * from messages WHERE (
            sender_email = ? AND
            recipient_email = ? OR
            sender_email = ? OR
            recipient_email = ? 
        ) ORDER BY timestamp DESC", [   $client_email,
                $data["get_all_messages"],
                $data["get_all_messages"],
                $client_email
         ]);

        // print_r($messages_db);
        echo json_encode([
            "target email"=> $messages_db
        ]);
        exit();
    }

    if( isset($data["messageInput"]) ) {
        header('Content-Type: application/json');
        // echo "dd";


     $insert_new_comment = $db->Insert("INSERT INTO `messages`( `sender_email`, `recipient_email`, `content`) VALUES (?,?,?)", [$client_email, $data["target"], $data["messageInput"]]);

         if($insert_new_comment) {
            echo json_encode([
                "success" => true,
                "message" => "Message inserted successfully.",
                "data" => [
                    "sender_email" => $client_email,
                    "recipient_email" => $data["target"],
                    "content" => $data["messageInput"]
                ]
            ]);
         }

        
        exit();
    }

    if( isset($data["poll_by_limit"]) ) {
        header('Content-Type: application/json');
       
        $messages_db = $db->GetRow("SELECT * from messages WHERE (
            sender_email = ? AND
            recipient_email = ? OR
            sender_email = ? OR
            recipient_email = ? 
        ) ORDER BY timestamp DESC LIMIT 1", [   $client_email,
                $data["poll_by_limit"],
                $data["poll_by_limit"],
                $client_email
         ]);

        print_r($messages_db);
        echo json_encode([
            "target email"=> $messages_db
        ]);
        exit();
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
    <title>Argon - Social Network</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@1.9.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/components.css" rel="stylesheet">
    <link href="assets/css/messenger.css" rel="stylesheet">
    <link href="assets/css/media.css" rel="stylesheet">
    <script src="assets/js/load.js" type="text/javascript"></script>
</head>

<body class="messenger">
    <div class="container-fluid newsfeed d-flex" id="wrapper">
        <div class="row newsfeed-size">
            <div class="col-md-12 message-right-side">
                <nav id="navbar-main" class="navbar navbar-expand-lg shadow-sm sticky-top">
                    <div class="w-100 justify-content-md-center">
                        <ul class="nav navbar-nav enable-mobile px-2">
                            <li class="nav-item">
                                <button type="button" class="btn nav-link p-0"><img src="assets/images/icons/theme/post-image.png" class="f-nav-icon" alt="Quick make post"></button>
                            </li>
                            <li class="nav-item w-100 py-2">
                                <form class="d-inline form-inline w-100 px-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control search-input" placeholder="Search for people, companies, events and more..." aria-label="Search" aria-describedby="search-addon">
                                        <div class="input-group-append">
                                            <button class="btn search-button" type="button"><i class='bx bx-search'></i></button>
                                        </div>
                                    </div>
                                </form>
                            </li>
                            <li class="nav-item">
                                <a href="messages.html" class="nav-link nav-icon nav-links message-drop drop-w-tooltip" data-placement="bottom" data-title="Messages">
                                    <img src="assets/images/icons/navbar/message.png" class="message-dropdown f-nav-icon" alt="navbar icon">
                                </a>
                            </li>
                        </ul>
                        <ul class="navbar-nav mr-5 flex-row" id="main_menu">
                            <a class="navbar-brand nav-item mr-lg-5" href="index.html"><img src="assets/images/logo-64x64.png" width="40" height="40" class="mr-3" alt="Logo"></a>
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <form class="w-30 mx-2 my-auto d-inline form-inline mr-5">
                                <div class="input-group">
                                    <input type="text" class="form-control search-input w-75" placeholder="Search for people, companies, events and more..." aria-label="Search" aria-describedby="search-addon">
                                    <div class="input-group-append">
                                        <button class="btn search-button" type="button"><i class='bx bx-search'></i></button>
                                    </div>
                                </div>
                            </form>
                            <li class="nav-item s-nav dropdown d-mobile">
                                <a href="#" class="nav-link nav-icon nav-links drop-w-tooltip" data-toggle="dropdown" data-placement="bottom" data-title="Create" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="assets/images/icons/navbar/create.png" alt="navbar icon">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right nav-dropdown-menu">
                                    <a href="#" class="dropdown-item" aria-describedby="createGroup">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <i class='bx bx-group post-option-icon'></i>
                                            </div>
                                            <div class="col-md-10">
                                                <span class="fs-9">Group</span>
                                                <small id="createGroup" class="form-text text-muted">Find people with shared interests</small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item" aria-describedby="createEvent">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <i class='bx bx-calendar post-option-icon'></i>
                                            </div>
                                            <div class="col-md-10">
                                                <span class="fs-9">Event</span>
                                                <small id="createEvent" class="form-text text-muted">bring people together with a public or private event</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item s-nav dropdown message-drop-li">
                                <a href="#" class="nav-link nav-links message-drop drop-w-tooltip" data-toggle="dropdown" data-placement="bottom" data-title="Messages" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="assets/images/icons/navbar/message.png" class="message-dropdown" alt="navbar icon"> <span class="badge badge-pill badge-primary">1</span>
                                </a>
                                <ul class="dropdown-menu notify-drop dropdown-menu-right nav-drop shadow-sm">
                                    <div class="notify-drop-title">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6 fs-8">Messages | <a href="#">Requests</a></div>
                                            <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                                <a href="#" class="notify-right-icon">
                                                    Mark All as Read
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                  
                                  
                                </ul>
                            </li>
                            <li class="nav-item s-nav dropdown notification">
                                <a href="#" class="nav-link nav-links rm-drop-mobile drop-w-tooltip" data-toggle="dropdown" data-placement="bottom" data-title="Notifications" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="assets/images/icons/navbar/notification.png" class="notification-bell" alt="navbar icon"> <span class="badge badge-pill badge-primary">3</span>
                                </a>
                                <ul class="dropdown-menu notify-drop dropdown-menu-right nav-drop shadow-sm">
                                    <div class="notify-drop-title">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-xs-6 fs-8">Notifications <span class="badge badge-pill badge-primary ml-2">3</span></div>
                                            <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                                <a href="#" class="notify-right-icon">
                                                    Mark All as Read
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end notify title -->
                                    <!-- notify content -->
                                    <div class="drop-content">
                                        <li>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="notify-img">
                                                    <img src="assets/images/users/user-10.png" alt="notification user image">
                                                </div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href="#" class="notification-user">Sean</a> <span class="notification-type">replied to your comment on a post in </span><a href="#" class="notification-for">PHP</a>
                                                <a href="#" class="notify-right-icon">
                                                    <i class='bx bx-radio-circle-marked'></i>
                                                </a>
                                                <p class="time">
                                                    <span class="badge badge-pill badge-primary"><i class='bx bxs-group'></i></span> 3h
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="notify-img">
                                                    <img src="assets/images/users/user-7.png" alt="notification user image">
                                                </div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href="#" class="notification-user">Kimberly</a> <span class="notification-type">likes your comment "I would really... </span>
                                                <a href="#" class="notify-right-icon">
                                                    <i class='bx bx-radio-circle-marked'></i>
                                                </a>
                                                <p class="time">
                                                    <span class="badge badge-pill badge-primary"><i class='bx bxs-like'></i></span> 7h
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="notify-img">
                                                    <img src="assets/images/users/user-8.png" alt="notification user image">
                                                </div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <span class="notification-type">10 people saw your story before it disappeared. See who saw it.</span>
                                                <a href="#" class="notify-right-icon">
                                                    <i class='bx bx-radio-circle-marked'></i>
                                                </a>
                                                <p class="time">
                                                    <span class="badge badge-pill badge-primary"><i class='bx bx-images'></i></span> 23h
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="notify-img">
                                                    <img src="assets/images/users/user-11.png" alt="notification user image">
                                                </div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href="#" class="notification-user">Michelle</a> <span class="notification-type">posted in </span><a href="#" class="notification-for">Argon Social Design System</a>
                                                <a href="#" class="notify-right-icon">
                                                    <i class='bx bx-radio-circle-marked'></i>
                                                </a>
                                                <p class="time">
                                                    <span class="badge badge-pill badge-primary"><i class='bx bxs-quote-right'></i></span> 1d
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="notify-img">
                                                    <img src="assets/images/users/user-5.png" alt="notification user image">
                                                </div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href="#" class="notification-user">Karen</a> <span class="notification-type">likes your comment "Sure, here... </span>
                                                <a href="#" class="notify-right-icon">
                                                    <i class='bx bx-radio-circle-marked'></i>
                                                </a>
                                                <p class="time">
                                                    <span class="badge badge-pill badge-primary"><i class='bx bxs-like'></i></span> 2d
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <div class="notify-img">
                                                    <img src="assets/images/users/user-12.png" alt="notification user image">
                                                </div>
                                            </div>
                                            <div class="col-md-10 col-sm-10 col-xs-10">
                                                <a href="#" class="notification-user">Irwin</a> <span class="notification-type">posted in </span><a href="#" class="notification-for">Themeforest</a>
                                                <a href="#" class="notify-right-icon">
                                                    <i class='bx bx-radio-circle-marked'></i>
                                                </a>
                                                <p class="time">
                                                    <span class="badge badge-pill badge-primary"><i class='bx bxs-quote-right'></i></span> 3d
                                                </p>
                                            </div>
                                        </li>
                                    </div>
                                    <div class="notify-drop-footer text-center">
                                        <a href="#">See More</a>
                                    </div>
                                </ul>
                            </li>
                            <li class="nav-item s-nav dropdown d-mobile">
                                <a href="#" class="nav-link nav-links nav-icon drop-w-tooltip" data-toggle="dropdown" data-placement="bottom" data-title="Pages" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="assets/images/icons/navbar/flag.png" alt="navbar icon">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right nav-drop">
                                    <a class="dropdown-item" href="newsfeed-2.html">Newsfeed 2</a>
                                    <a class="dropdown-item" href="sign-in.html">Sign in</a>
                                    <a class="dropdown-item" href="sign-up.html">Sign up</a>
                                </div>
                            </li>
                            <li class="nav-item s-nav">
                                <a href="profile.html" class="nav-link nav-links">
                                    <div class="menu-user-image">
                                        <img src="assets/images/users/user-4.jpg" class="menu-user-img ml-1" alt="Menu Image">
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item s-nav nav-icon dropdown">
                                <a href="settings.html" data-toggle="dropdown" data-placement="bottom" data-title="Settings" class="nav-link settings-link rm-drop-mobile drop-w-tooltip" id="settings-dropdown"><img src="assets/images/icons/navbar/settings.png" class="nav-settings" alt="navbar icon"></a>
                                <div class="dropdown-menu dropdown-menu-right settings-dropdown shadow-sm" aria-labelledby="settings-dropdown">
                                    <a class="dropdown-item" href="#">
                                        <img src="assets/images/icons/navbar/help.png" alt="Navbar icon"> Help Center</a>
                                    <a class="dropdown-item d-flex align-items-center dark-mode" onClick="event.stopPropagation();" href="#">
                                        <img src="assets/images/icons/navbar/moon.png" alt="Navbar icon"> Dark Mode
                                        <button type="button" class="btn btn-lg btn-toggle ml-auto" data-toggle="button" aria-pressed="false" autocomplete="off">
                                            <div class="handle"></div>
                                        </button>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <img src="assets/images/icons/navbar/gear-1.png" alt="Navbar icon"> Settings</a>
                                    <a class="dropdown-item logout-btn" href="#">
                                        <img src="assets/images/icons/navbar/logout.png" alt="Navbar icon"> Log Out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="message-right-side-content">
                        <div id="message-frame">
                            <div class="message-sidepanel">
                                <div class="message-profile">
                                    <div class="wrap">
                                        <img src="assets/images/users/user-4.jpg" class="online conv-img" alt="Conversation user" />
                                        <p>Arthur Minasyan</p>
                                        <i class='bx bx-chevron-down expand-button'></i>
                                        <div id="status-options">
                                            <ul id="set-online-status">
                                                <li id="status-online" class="messenger-user-active"><span class="status-circle"></span>
                                                    <p>Online</p>
                                                </li>
                                                <li id="status-away"><span class="status-circle"></span>
                                                    <p>Away</p>
                                                </li>
                                                <li id="status-busy"><span class="status-circle"></span>
                                                    <p>Busy</p>
                                                </li>
                                                <li id="status-offline"><span class="status-circle"></span>
                                                    <p>Offline</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div id="expanded">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <a href="javascript:void(0)" class="text-dark fs-9"><i class='bx bx-search text-primary mr-3'></i> Search in Conversation</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <a href="javascript:void(0)" class="text-dark fs-9"><i class='bx bx-pencil text-primary mr-3'></i> Edit Nicknames</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <a href="javascript:void(0)" class="text-dark fs-9"><i class='bx bxs-color-fill text-primary mr-3'></i> Change Color</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <a href="javascript:void(0)" class="text-dark fs-9"><i class='bx bx-bell text-primary mr-3'></i> Notifications</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-search position-relative d-flex">
                                    <label for=""><i class='bx bx-search'></i></label>
                                    <input type="text" class="form-control search-input" placeholder="Search for conversations..." />
                                    <button type="button" class="btn btn-create-conversation" data-toggle="modal" data-target="#newConversation"><i class='bx bx-pencil'></i></button>
                                </div>
                                <div class="message-contacts">
                                    <ul class="conversations">
                                        
                                        

                 <?php
                    foreach ($favorites as $friend) {
                        $account = $db->GetRow("SELECT * FROM users WHERE email = ?", [$friend]);

                        echo "<li class='contact' onclick='updateTargetEmail(\"$friend\")'>";
                            echo "<div class='wrap'>";
                                echo "<span class='contact-status online'></span>"; // You can adjust the status dynamically if needed
                                echo "<img src='images/" . htmlspecialchars($account['profile']) . "' alt='Conversation user' />";
                                echo "<span class='unread-messages'>0</span>"; // Adjust unread messages as needed
                                echo "<div class='meta'>";
                                    echo "<p class='name'>" . htmlspecialchars($account["first_name"]) . " " . htmlspecialchars($account["last_name"]) . "</p>";
                                    echo "<p class='preview'>" . htmlspecialchars($account["email"]) . "</p>"; // Change this to a more appropriate preview message if needed
                                echo "</div>";
                            echo "</div>";
                        echo "</li>";
                    }
                ?>
                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="content">
                                <div class="content-row-77">
                                    <div class="messenger-top-section">
                                        
                                        <div class="contact-profile ">
                                            
                                            <div class="messenger-top-luser df-aic">
                                                <img src="assets/images/users/user-6.png" class="messenger-user" alt="Convarsation user image" />
                                                <a href="#" class="message-profile-name">Susan P. Jarvis</a>
                                            </div>

                                            
                                        </div>

                                    </div>

                                        <div class="messages">
                                            <div
                                              id="messages-displayer-77">
                                            </div>
                                        </div>

                                        <div class="message-input">
                                                <form class="d-inline form-inline">
                                                    <div class="d-flex align-items-center justify-content-between messenger-icons">
                                                        <button type="button" class="btn search-button">
                                                            <img src="assets/images/icons/messenger/m-camera.png" alt="Messenger icons">
                                                        </button>
                                                        <button type="button" class="btn search-button">
                                                            <img src="assets/images/icons/messenger/m-photo.png" alt="Messenger icons">
                                                        </button>
                                                        <button type="button" class="btn search-button">
                                                            <img src="assets/images/icons/messenger/m-gamepad.png" alt="Messenger icons">
                                                        </button>
                                                        <button type="button" class="btn search-button">
                                                            <img src="assets/images/icons/messenger/m-microphone.png" alt="Messenger icons">
                                                        </button>
                                                        <div class="input-group messenger-input">
             <input type="text" id="message-input-sender" class="form-control search-input"  placeholder="Type a message..." aria-label="Type a message..." aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button 
                        class="btn search-button"
                        type="button"
                        id="button-addon2 send-message">
                            <img src="assets/images/icons/messenger/m-smile.png" alt="Messenger icons">
                        </button>
                    </div>
                    </div>
                        <button 
                            type="button"
                            class="btn search-button"
                            id="send-message-messanger"
                            >
                            <img src="assets/images/icons/messenger/m-send.png" alt="Messenger icons">
                        </button>
                    </div>
                                                </form>
                                        </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Conversation Modal -->
    <div class="modal fade" id="newConversation" tabindex="-1" role="dialog" aria-labelledby="newConversationLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header new-msg-header">
                    <h5 class="modal-title" id="newConversationLabel">Start new conversation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body new-msg-body">
                    <form action="" method="" class="new-msg-form">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Recipient:</label>
                            <input type="text" class="form-control search-input" id="recipient-name" placeholder="Add recipient...">
                        </div>
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
                            <a href="#" class="btn call-btn drop-call" data-toggle="tooltip" data-placement="top" data-title="End call"><i class='bx bxs-phone'></i></a>
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
    <script src="assets/js/jquery/jquery-ui.js"></script>
    <!-- Optional -->
    <script src="assets/js/components/components.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="messanger.js"></script>

</body>

</html>
