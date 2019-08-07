<?php
session_start();
if(!isset($_GET['id'])){
	header('location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Notifications</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<link rel="stylesheet" type="text/css" href="public/stylesheets/notifications.css">
</head>
<body>
    <?php include('add-ons/navbar/navbar.php'); ?>
    <div class="container">
    	<ol class="notifications rounded">
    		
    	</ol>
    </div>
    <?php include('add-ons/navbar/modal.php'); ?>
    <?php include('add-ons/navbar/privacy.php'); ?>
    <?php include('add-ons/scripts.php'); ?>
<script type="text/javascript">
    document.querySelector('#getRequests').style.display = 'none';
    getNotifs();
</script>

</body>
</html>
