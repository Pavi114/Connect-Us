<?php
session_start();
include('../config/config.php');
include('../Helpers/activity.php');
include('../Helpers/current_user.php');
if(isset($_GET['status'],$_GET['id'])){
	if($_GET['status'] == 'accept'){
       $user = new CurrentUser($con,$_GET['id'],$_SESSION['userLoggedIn']);
       echo $user->toggleFollow();
       $activity = new Activity($con,$_SESSION['userLoggedIn'],$_GET['id'],'request accept');
       $activity->insert();
	}
	else if($_GET['status'] == 'decline'){
       echo 'declined';
	}
	$activity = new Activity($con,$_GET['id'],$_SESSION['userLoggedIn'],'request sent');
	$st = $activity->remove();
}
?>