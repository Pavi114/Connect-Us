<?php
session_start();
include('../config/config.php');
include_once('../Helpers/current_user.php');
include_once('../Helpers/drop.php');
include_once('../Helpers/Activity.php');
if(isset($_GET['type'])){
	if($_GET['type'] == 'numNotifs'){
		$activity = new Activity($con,$_SESSION['userLoggedIn'],'','request sent');
        echo $activity->getTotalNotifs();
	}
	else if($_GET['type'] == 'getNotifs'){
		$activity = new Activity($con,$_SESSION['userLoggedIn']);
		$notifList = $activity->getNotifs();
		$notifs = array();

		foreach ($notifList as $key => $value) {
			$user = new CurrentUser($con,$_SESSION['userLoggedIn'],$value['user_id']);
            $userDetails = $user->getUserDetails();

		    if(strpos($value['message'], 'repost') === 0 || strpos($value['message'], 'reply') === 0){
		      $message = explode(':', $value['message']);
		      $drop = new Drop($con,$_SESSION['userLoggedIn']);
			  $dropDetails = $drop->getDropById($message[1]);	
			  if(strpos($value['message'], 'repost') == 0){
			      array_push($notifs, notifDetail('repost',$dropDetails,$userDetails,$value['status'])); 	
			  }
			  else {
			    array_push($notifs, notifDetail('reply',$dropDetails,$userDetails,$value['status']));	
			  }
             
		    }
		    else if($value['message'] == 'request accept'){
              array_push($notifs, notifDetail('request accept','',$userDetails,$value['status']));
		    }
		    else if($value['message'] == 'followed'){
              array_push($notifs, notifDetail('followed','',$userDetails,$value['status']));
		    }

		    $activity->updateReadStatus($value['id']);
		}

		echo json_encode($notifs);
	}
}

function notifDetail($type,$drop = array(),$userDetails,$status){
	return (object)[
               'user' => (object)[
                                      'user_id' => $userDetails['id'],
                                      'name' => $userDetails['first_name'].' '.$userDetails['last_name'],
                                      'username' => $userDetails['username']
                                     ],
                          'post' => $drop,
                          'type' => $type,
                          'status' => $status
	        ];
}
?>