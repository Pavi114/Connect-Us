<?php 
session_start();
include('config/config.php');
include_once('config/secret/secret.php');
include('Helpers/current_user.php');
if(isset($_SESSION['userLoggedIn'])){
	$_SESSION['id'] = $_SESSION['userLoggedIn'];
	$user = new CurrentUser($con,$_SESSION['userLoggedIn'],$_SESSION['userLoggedIn']);
}
else {
	header('location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUfYXPNvO89LB-STHNfbiBepcB4RF79FM&libraries=places&callback=initAutocomplete" async defer></script>
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,500,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Handlee&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="public/Stylesheets/home.css">
</head>
<body>
	<?php include('add-ons/pop-notify.php'); ?>
	<?php include('add-ons/navbar/navbar.php'); ?>
	<div id="basicInfo" class="container rounded">
		<div class="row">
			<?php echo $user->displayDetails(); ?>
		</div>
	</div>

	<div class='container drops rounded'>
		<h2 class="heading">Feed</h2>
		<hr>
		<ul id="list">

		</ul>
	</div>
	<?php include('add-ons/navbar/modal.php'); ?>
	<?php include('add-ons/navbar/privacy.php'); ?>
    <?php include('add-ons/scripts.php'); ?>
	<script type="text/javascript">
		var id="<?php echo $_SESSION['id'] ?>";
		var userId = "<?php echo $_SESSION['userLoggedIn']; ?>";
		var addDrop = document.querySelector('#addDrop');
		getDrops();
	</script>
</body>
</html>