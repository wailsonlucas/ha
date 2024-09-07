<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "../app/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	// var_dump($_SESSION);
	echo $_SESSION['email']
	echo json_encode(["c" => "j"]);
}

?>
