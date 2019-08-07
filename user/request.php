<?php
session_start();
include('../config/config.php');
include('../Helpers/activity.php');
include('../Helpers/current_user.php');
 if(isset($_GET['type'])){
 	$type = $_GET['type'];
 	if($type == 'numRequests'){
 		$activity = new Activity($con,$_SESSION['userLoggedIn'],'','request sent');
 		echo $activity->getTotalRequests();
 	}
 	else if($type == 'fetchRequests'){
       $activity = new Activity($con,$_SESSION['userLoggedIn'],'','request sent');
       $idList = $activity->getRequests();
       $details = array();
       for ($i=0; $i < count($idList); $i++ ) { 
       	 $user = new CurrentUser($con,$_SESSION['userLoggedIn'],$idList[$i]);
       	 $userDetails = $user->getUserDetails();
       	 array_push($details,(object)[
       	 	         'id' => $userDetails['id'],
                    'username' =>  $userDetails['username'],
                    'dp' => $userDetails['image_name']
       	           ]);
       }
       echo json_encode($details);
 	}
 }
?>