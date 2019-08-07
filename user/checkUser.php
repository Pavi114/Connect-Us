<?php 
include("../config/config.php");
if(isset($_GET['username'])){
  $username = $_GET['username'];
  $query = "SELECT * FROM user WHERE username = '$username'";
  $result = mysqli_query($con,$query);
  if(mysqli_num_rows($result) > 0){
  	echo 'no';
  }
  else {
  	echo 'yes';
  }
}
?>