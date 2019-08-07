<?php
session_start();
include('../config/config.php');
include('../Helpers/drop.php');
if(isset($_POST['id'])){
  $id = $_POST['id'];
  $drop = new Drop($con,$_SESSION['userLoggedIn']);
  if($drop->insertDrop($id,'repost')){
  	  $user2 = $drop->getDropById($id);
  	  $userId2 = $user2['user_id'];
  	  $comment = 'repost:'.$id;
  	  $activity = new Activity($con,$_SESSION['userLoggedIn'],$userId2,$comment);
  	  if($activity->insert()){
          	echo 'repost';
  	  }
  }
}
?>