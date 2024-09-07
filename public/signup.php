<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../app/config.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    if(isset($_POST['first_name'])){
        $first_name = sanitize($_POST['first_name']);
    }
    if(isset($_POST['last_name'])){
        $last_name = sanitize($_POST['last_name']);
    }
    if(isset($_POST['email'])){
        $email = sanitize($_POST['email']);
    }
    if(isset($_POST['phone'])){
        $phone = sanitize($_POST['phone']);
    }
    if(isset($_POST['dofb'])){
        $dofb = sanitize($_POST['dofb']);
    }
    if(isset($_POST['password'])){
        $password = sanitize($_POST['password']);
    }

    $db = new Database();

    $insert = $db->Insert("INSERT INTO `users`(`first_name`, `last_name`, `email`, `phone`, `dofb`, `password`) VALUES (?,?,?,?,?,?)",[$first_name,$last_name,$email,$phone,$dofb,$password]);

    redirect('public/index.php',"Account Created Successfully. login now");
   
}




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Social Media - Sign up</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@1.9.2/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/components.css" rel="stylesheet">
    <link href="assets/css/auth.css" rel="stylesheet">
    <link href="assets/css/media.css" rel="stylesheet">
</head>

<body>
    <div class="row ht-100v flex-row-reverse no-gutters">
        <div class="col-md-6 d-flex justify-content-center align-items-center">
            <div class="signup-form">
                <div class="auth-logo text-center mb-5">
                    <div class="row">
                        <div class="col-md-2">
                           
                        </div>
                        <div class="col-md-10">
                            <p>Social Media</p>
                        
                        </div>
                    </div>
                </div>
                <form action="" method="POST" class="pt-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                        <div class="form-group">
                                <input type="date" class="form-control" name="dofb" placeholder="Phone Number" required>
                            </div>
                          
                        </div>
                      
                      
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                        </div>
                     
                     
                        <div class="col-md-6">
                            <span class="go-login">Already a member? <a href="index.php">Sign In</a></span>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary sign-up">Sign Up</button>
                            </div>
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
                    <h2 class="create-account mb-3">Create Account</h2>
                    <p>Enter your personal details and start journey with us.</p>
                </div>
                <div class="auth-quick-links">
                
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
</body></html>
