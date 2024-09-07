<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "Database.php";
$db = new Database();



  	if ($_SERVER["REQUEST_METHOD"] == "POST") {

  		$json = file_get_contents('php://input');
		$data = json_decode($json, true); 
		// $likesCount = $data["currentLikesCount"];
		$postId = $data["postId"];
		// echo $likesCount;
		// echo $postId;

		

        $get_post = $db->GetRow("SELECT likers FROM `posts` WHERE id = ?", [$postId]);


        if ($get_post) {
        	$likers = $get_post["likers"];
        	$likers_to_array = json_decode($likers, true);
        	
        	// print_r($likers_to_array);

        	if (in_array($postId, $likers_to_array)) {
		        $likers_to_array = array_filter($likers_to_array, function($liker) use ($postId) {
		            return $liker !== $postId; 
		        });
		        // print_r($likers_to_array);
		        $parsed_likers_array = json_encode($likers_to_array);
		        $db->update("UPDATE posts SET likers = ? WHERE id = ?", [$parsed_likers_array, $postId]);
		         echo json_encode([
			 	 	'liked' => false
			 	 ]);

			} else {
	        	$likers_to_array[] = $postId;
	        	$parsed_likers_array = json_encode($likers_to_array);
	        	// print_r($likers_to_array);
	        	$db->update("UPDATE posts SET likers = ? WHERE id = ?", [$parsed_likers_array, $postId]);
	        	 echo json_encode([
		 		 	'liked' => true
		 		 ]);
			}

        	// if($likesCount ===  $likes_db) {
        	// 	//increment likes
        	// 	$incer_qry = $db->Update("UPDATE `posts` SET likes = ? WHERE id = ?", [$likes_db + 1, $postId]);
        	// 	echo json_encode([
		 	// 		'newCount' => $likes_db + 1
		 	// 	]);
        	// } else {
        	// 	//decrement likes
        	// 	echo $likesCount;
        	// 	echo "-----";
        	// 	echo $likes_db;

        	// 	$decer_qry = $db->Update("UPDATE `posts` SET likes = ? WHERE id = ?", [max(0, $likes_db - 1), $postId]);
        	// 	// echo json_encode([
		 	// 	// 	'newCount' => $likes_db - 1
		 	// 	// ]);
        	// }
        } 
	    
	}

?>

<?php

