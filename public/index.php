<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "../app/autoload.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = sanitize($_POST['email']);
        $password = sanitize($_POST['password']);

        $db = new Database();

        $select = $db->GetRows("SELECT * FROM `users` WHERE email = ? AND password = ?", [$email, $password]);

        if ($select) {
            foreach ($select as $row) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $email;
                redirect('home.php', "Welcome Back!");
            }
        } else {
            $_SESSION['status'] = "Wrong Email or Password";
            redirect('index.php', "Wrong Email or Password");
        }
    }
}
?>









<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Social Media - Sign in</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@1.9.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/components.css" rel="stylesheet">
    <link href="assets/css/auth.css" rel="stylesheet">
    <link href="assets/css/forms.css" rel="stylesheet">
    <link href="assets/css/media.css" rel="stylesheet">
</head>

<body>
    <div class="row ht-100v flex-row-reverse no-gutters">
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <div class="signup-form">
                <div class="auth-logo text-center mb-5">
                    <div class="row">
                   
                        <div class="col-md-10">
                            <p>Socail Media</p>


                            <?= alertMessage() ?>
                          
                        </div>
                    </div>
                </div>
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" value="ca26yyeha@mozmail.com" name="email" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="password" class="form-control" value="123456" name="password" placeholder="Password" required>
                            </div>
                        </div>
                      
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary sign-up">Sign In</button>
                            </div>
                        </div>
                      
                        <div class="col-md-12 text-center mt-5">
                            <span class="go-login">Not yet a member? <a href="signup.php">Sign Up</a></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6 auth-bg-image d-flex justify-content-center align-items-center">
            <div class="auth-left-content mt-5 mb-5 text-center">
                <div class="weather-small text-white">
          
                </div>
                <div class="text-white mt-5 mb-5">
                    <h2 class="create-account mb-3">Welcome Back</h2>
                    <p>Thank you for joining. Updates and new features are released daily.</p>
                </div>
                <div class="auth-quick-links">
              
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade fingerprint-modal" id="fingerprintModal" tabindex="-1" role="dialog" aria-labelledby="fingerprintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3 class="text-muted display-5">Place your Finger on the Device Now</h3>
                    <img src="assets/images/icons/auth-fingerprint.png" alt="Fingerprint">
                </div>
            </div>
        </div>
    </div>

    <!-- Core -->
    <script src="assets/js/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/js/popper/popper.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.min.js"></script>
    <!-- Optional -->
    <script src="assets/js/app.js"></script>
</body>

</html>
