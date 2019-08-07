<?php
session_start();
include('../config/config.php');
include('../Helpers/drop.php');
include_once('../Helpers/current_user.php');
include_once('../Helpers/image.php');
if(isset($_GET['id'])){
	$drop = new Drop($con,$_SESSION['userLoggedIn']);
	$thread = $drop->getThread($_GET['id']);
	$user = new CurrentUser($con,$_SESSION['userLoggedIn'],$thread[0]->parentPost['user_id']);
	$image = new Image($con);
	$userDetails = $user->getUserDetails();
	$thread[0]->parentPost['user_details'] = (object)[
                                                      'username' => $userDetails['username'],
                                                      'name' => $userDetails['first_name'].' '.$userDetails['last_name'],
                                                      'dp' => $userDetails['image_name']
	                                                ];
     $thread[0]->parentPost['image_details'] = (object)[
                                               'image_name' => $image->getImageName($thread[0]->parentPost['id']),
                                               'upload_type' => $image->getUploadType($thread[0]->parentPost['id']),
                                            ];                                               
	foreach ($thread[0]->comments as $key => $value) {
		$user = new CurrentUser($con,$_SESSION['userLoggedIn'],$value->user_id);
		$userDetails = $user->getUserDetails();
	    $thread[0]->comments[$key]->user_details =  (object)[
                                                      'username' => $userDetails['username'],
                                                      'name' => $userDetails['first_name'].' '.$userDetails['last_name'],
                                                      'dp' => $userDetails['image_name']
	                                                ];   
	     $thread[0]->comments[$key]->image_details = (object)[
                                        'image_name' => $image->getImageName($value->id),
                                        'upload_type' => $image->getUploadType($value->id),
                                       ];                                                                                    
	}                                                
	echo json_encode($thread);
}
?>