<?php
session_start();
include('../config/config.php');
include('../Helpers/activity.php');
include('../Helpers/current_user.php');
 if(isset($_GET['id'])){
   $user = new CurrentUser($con,$_SESSION['userLoggedIn'],$_GET['id']);
   $userDetails = $user->getUserDetails();
   if($userDetails['acc_type'] == 'private' && !$user->getFollow()){
   	$activity = new Activity($con,$_SESSION['userLoggedIn'],$_GET['id'],'request sent');
   	if($activity->isRequestSent()){
   	  if($activity->remove()){
   	  	echo 'unsend';
   	  }
   	}
   	else{
      if($activity->insert()){
		echo 'sent';
	  }
   	}
   	return true;
   }
   $status = $user->toggleFollow();
   if($status == 'followed'){
      $activity = new Activity($con,$_SESSION['userLoggedIn'],$_GET['id'],'followed');
      $activity->insert();
   }
   echo $status;
 }
?>