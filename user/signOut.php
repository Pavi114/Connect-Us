<?php
  session_start();
  if(isset($_SESSION['userLoggedIn'])){
  	session_destroy();
  	header('location: ../index.php');
  }
?>