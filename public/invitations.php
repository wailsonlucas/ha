<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../app/autoload.php";

    $email = $_SESSION['email'];
    $uid = $_SESSION['user_id'];
    $db = new Database();

    $invitations_db = $db->GetRows("SELECT sender_id from invitations WHERE recipient_id = ?", [$email]);


   if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $dataSenderId = $data['dataSenderId'];
        $dataRecipientId = $data['dataRecipientId'];

         try {
            // Execute the delete operation
            $delete_invi = $db->Delete(
                "DELETE FROM invitations WHERE sender_id=? AND recipient_id=?",
                [$dataSenderId, $dataRecipientId]
            );

            // Check if any rows were affected
            if ($delete_invi) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Invitation deleted successfully.'
                ]);
                exit()
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No invitation found to delete.'
                ]);
                exit()
            }
        } catch (Exception $e) {
            // Handle any exceptions
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while deleting the invitation.',
                'error' => $e->getMessage()
            ]);
            exit()
        }

    }




 if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $dataSenderId = $data['dataSenderId'];
        $dataRecipientId = $data['dataRecipientId'];
        header('content-type: application/json');

        $delete_invi = $db->Delete(
                "DELETE FROM invitations WHERE sender_id=? AND recipient_id=?",
                [$dataSenderId, $dataRecipientId]
            );
    
        $get_sender_row = $db->GetRow("SELECT friends_list FROM users WHERE email=?", [$dataSenderId]);
        $decoded_sender_friends_list = json_decode($get_sender_row["friends_list"], true);
        if(!in_array($dataRecipientId ,$decoded_sender_friends_list)){
            $decoded_sender_friends_list[] = $dataRecipientId;
            $encoded_sender_friends_list = json_encode($decoded_sender_friends_list);
            $update_recipient_row = $db->Update("UPDATE users SET friends_list = '$encoded_sender_friends_list' WHERE email=?", [$dataSenderId]);
        }


        $get_recipient_row = $db->GetRow("SELECT friends_list FROM users WHERE email=?", [$dataRecipientId]);
        $decoded_recipient_friends_list = json_decode($get_recipient_row["friends_list"], true);
        if(!in_array($dataSenderId ,$decoded_recipient_friends_list)){
            $decoded_recipient_friends_list[] = $dataSenderId;
            $encoded_recipient_friends_list = json_encode($decoded_recipient_friends_list);
            $update_recipient_row = $db->Update("UPDATE users SET friends_list = '$encoded_recipient_friends_list' WHERE email=?", [$dataRecipientId]);
        }


        // UPDATE users SET friends_list = jsonb_set(friends_list )

        // $g = json_encode(["apple", "banana", "cherry"]);

        // $get_sender_row["friends_list"];

        echo json_encode([
            // 'in db' => $get_sender_row["friends_list"],
            "status" => true
        ]);
        exit();



        //  try {
        //     Execute the delete operation
        //     $delete_invi = $db->Delete(
        //         "DELETE FROM invitations WHERE sender_id=? AND recipient_id=?",
        //         [$dataSenderId, $dataRecipientId]
        //     );

        //     if ($delete_invi) {
        //         echo json_encode([
        //             'success' => true,
        //             'message' => 'Invitation deleted successfully.'
        //         ]);
        //     } else {
        //         echo json_encode([
        //             'success' => false,
        //             'message' => 'No invitation found to delete.'
        //         ]);
        //     }
        // } catch (Exception $e) {
        //     echo json_encode([
        //         'success' => false,
        //         'message' => 'An error occurred while deleting the invitation.',
        //         'error' => $e->getMessage()
        //     ]);
        // }

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
        <!-- <link href="assets/css/components.css" rel="stylesheet"> -->
        <link href="assets/css/find-people.css" rel="stylesheet">
        <link href="assets/css/media.css" rel="stylesheet">
        <link href="assets/css/chat.css" rel="stylesheet">
        <link href="invitations.css" rel="stylesheet">
        <link href="https://vjs.zencdn.net/7.4.1/video-js.css" rel="stylesheet">
        <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js" type="text/javascript"></script>
        <script src="assets/js/load.js" type="text/javascript"></script>
    </head>

    <body class="newsfeed">
        <div class="container-fluid" id="wrapper">
            <div class="row newsfeed-size">
                <div class="col-md-12 newsfeed-right-side">

                    <?php include_once "inc/navbar.php" ?>
<!--  -->
                    <?php include_once "inc/sidebar.php" ?>


        <div class="listed-users-container">
            <?php

             // print_r($invitations_db);
              foreach($invitations_db as $invi) {
                $sender_email = $invi['sender_id'];
                $sender_email_db = $db->GetRow("SELECT * FROM users WHERE email = ?", [$sender_email]);
                // print_r($sender_email_db);
                echo "<div class='listed-invi'>";
                        echo "<div class='img-container'>";
                            echo "<img src='images/" . $sender_email_db['profile'] ."'/>";
                             echo "<img src='images/ man-1.jpg '/>";
                        echo "</div>";
                        echo "<div>";
                            echo "<p class='user-name'>";  echo $sender_email_db["first_name"] ; echo " "; echo $sender_email_db["last_name"]; echo "</p>";
                            echo "<p class='email'>";  echo $sender_email_db["email"] ; echo "</p>";
                            echo "<p class='dofb'>";  echo $sender_email_db["dofb"] ; echo "</p>";
                        echo "</div>";
                        
                        echo "<div>";
                            echo "<button onClick='handleAcceptInvi(event)' class='accept-invi' data-sender-id='".$sender_email."' data-recipient-id='".$email."'>";
                             echo "Accept";
                            echo "</button>";
                          
                            echo "<button class='refuse-invi' onClick='handleRemoveInvi(event)' data-sender-id='".$sender_email."' data-recipient-id='".$email."'>";
                              echo "Refuse";
                            echo "</button>";
                        echo "</div>";

                    echo "</div>";

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
    <script>
        function handleRemoveInvi(e){
            let dataSenderId = e.currentTarget.getAttribute('data-sender-id')
            let dataRecipientId = e.currentTarget.getAttribute('data-recipient-id')
            // console.log(dataSenderId)
            // console.log(dataRecipientId)

            fetch('/ha/public/invitations.php', {
                method:"DELETE",
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({dataSenderId, dataRecipientId})
            })
            .then(res => res.json())
            .then(c => {
                window.location = "/"
            })
            .catch(err => console.error(err))
        }

        function handleAcceptInvi(e){
            let dataSenderId = e.currentTarget.getAttribute('data-sender-id')
            let dataRecipientId = e.currentTarget.getAttribute('data-recipient-id')
            // console.log(dataSenderId)
            // console.log(dataRecipientId)

            fetch('/ha/public/invitations.php', {
                method:"POST",
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({dataSenderId, dataRecipientId})
            })
            .then(res => res.json())
            .then(res => console.log(res))
            .then(c => {
                window.location.reload();

            })
            .catch(err => console.error(err))
        }
        
    </script>
    </body>

    </html>