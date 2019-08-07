<?php 
include('../config/config.php');
include('../helpers/drop.php');
if(isset($_GET['id'],$_GET['limit'],$_GET['offset'])){
	$id = $_GET['id'];
 $user = new Drop($con,$id);
 $timeline = $user->getTimeline($_GET['limit'],$_GET['offset'],$_GET['lastId']);
 echo json_encode($timeline);
}
?>