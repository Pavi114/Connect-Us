<?php
session_start();
include('../config/config.php');
include('../Helpers/current_user.php');
if($_GET['id']){
	$id = $_GET['id'];
	$user = new Currentuser($con,$_SESSION['userLoggedIn'],$id);
	$userDetails = $user->getUserDetails();
	if($userDetails['acc_type'] == 'private' && $id != $_SESSION['userLoggedIn']){
		if(!$user->getFollow()){
			echo 'private';
			return true;
		}
	}
	$following = $user->getFollowing();
	echo json_encode($following);
}
?>