<?php
include('../config/config.php');
if(isset($_GET['q'])){
	$search = '%'. $_GET['q'] .'%';
	$list = array();
	$stmt = $con->prepare("SELECT * FROM user WHERE username LIKE ? LIMIT 5");
	$stmt->bind_param('s',$search);
	$stmt->execute();
	$result = $stmt->get_result();
	if(mysqli_num_rows($result) > 0){
		while($row = $result->fetch_assoc()){
			array_push($list, $row);
		}
	}
	echo json_encode($list);
}
?>