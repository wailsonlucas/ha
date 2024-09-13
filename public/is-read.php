<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../app/autoload.php";
$db = new Database();

$messageIds = $_POST['message_ids']; 

 $db->update("UPDATE `messages` SET is_read = true WHERE id = ?", [$messageIds]);




?>