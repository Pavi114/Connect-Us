<?php
session_start();
include('../config/config.php');
include('../Helpers/drop.php');
include_once('../Helpers/current_user.php');
if(isset($_GET['id']))
  $id = $_GET['id'];
  $user = new Currentuser($con,$_SESSION['userLoggedIn'],$id);
  $userDetails = $user->getUserDetails();
  if($userDetails['acc_type'] == 'private' && $id != $_SESSION['userLoggedIn']){
  	if(!$user->getFollow()){
       echo 'private';
       return true;
  	}
  }
  $drops = new Drop($con,$id);
  $drops->fetchDropsFromDb();
  echo json_encode($drops->getDrops());
?>