<?php
session_start();
include('../config/config.php');
 include('../Helpers/current_user.php');
 if(isset($_GET['status'])){
 	$user = new Currentuser($con,$_SESSION['userLoggedIn'],$_SESSION['userLoggedIn']);
 	echo $user->setAccType($_GET['status']);
 }
?>