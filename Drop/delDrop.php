<?php
session_start();
include('../config/config.php');
include('../Helpers/drop.php');
if(isset($_POST['id'])){
	$drop = new Drop($con,$_SESSION['userLoggedIn']);
	$dropDetails = $drop->getDropById($_POST['id']);
	if($dropDetails['is_reply'] == 1){
		$drop->update($dropDetails['parent_id'],'replies',true);
	}
	$stmt = $con->prepare('DELETE FROM drops WHERE id=? OR parent_id=?');
	$stmt->bind_param('ii',$_POST['id'],$_POST['id']);
	$stmt->execute();
	$stmt->close();
}
?>