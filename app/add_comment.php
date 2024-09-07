<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	include_once "Database.php";
	$db = new Database();


// 	if ($_SERVER["REQUEST_METHOD"] == "POST") {
// 	// Get the form data
// 	$comm = $_POST['comm'];
// 	$uid = $_POST['uid'];
// 	// echo "comm: " . htmlspecialchars($comm); //OK
// 	// echo "uid: " . htmlspecialchars($uid); //OK

 // $insert = $db->Insert("INSERT INTO `comments`( `post_id`, `user_id`, `comment`) VALUES (?,?,?)", [0, $uid, $comm]);

// 	}

	
	// $pid = $data['pid'];
	// $response = $pid;
	// $response = [
	//     'status' => 'success',
	//     'message' => 'Comment added',
	//     'data' => "888"
	// ];
	// json_encode($response);

  	if ($_SERVER["REQUEST_METHOD"] == "POST") {

  		$json = file_get_contents('php://input');
		$data = json_decode($json, true); 

		$comment = $data["comment"];
		$pid = $data["pid"];
		$uid = $data["uid"];

		//insert new comment
		 $insert_new_comment = $db->Insert("INSERT INTO `comments`( `post_id`, `user_id`, `comment`) VALUES (?,?,?)", [$pid, $uid, $comment]);

		 // if(empty($comment) || empty($uid) || empty($pid) ) {
		 // 	return echo json_encode(['status' => 'error', 'message' => 'User ID and comment cannot be empty.']);
		 // }

		 if($insert_new_comment){
		 	echo json_encode([
	    		'comment' => $comment
		    ]);
		 }else {
		 	echo json_encode([
		 		'status' => 'error',
		 		'message' => 'Faild to add comment'
		 	]);
		 };

	    
	}




?>


