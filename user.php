<?php 
session_start();
include('config/config.php');
include('config/Secret/secret.php');
include('Helpers/current_user.php');
if(isset($_GET['id'])){
	$_SESSION['id'] = $_GET['id'];
	$user = new CurrentUser($con,$_SESSION['userLoggedIn'],$_SESSION['id']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Search</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,500,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Handlee&display=swap" rel="stylesheet">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUfYXPNvO89LB-STHNfbiBepcB4RF79FM&libraries=places&callback=initAutocomplete" async defer></script>
	<link rel="stylesheet" type="text/css" href="public/Stylesheets/home.css">
</head>
<body>
	<?php include('add-ons/pop-notify.php'); ?>
	<?php include('add-ons/navbar/navbar.php'); ?>
	<div id="basicInfo" class="container">
		<div class="row">
			<?php echo $user->displayDetails(); ?>
		</div>
	</div>
	<div class="container drops">
		<h2 class="heading">Feed</h2>
		<hr>
		<ul id="list">

		</ul>	
	</div>
	<?php include('add-ons/navbar/modal.php'); ?>
	<?php include('add-ons/scripts.php'); ?>
	<script type="text/javascript">
		var id1="<?php echo $_SESSION['id'] ?>";
		var id="<?php echo $_GET['id'] ?>";
		var userId = "<?php echo $_SESSION['userLoggedIn']; ?>";
		getDrops();
	</script>
	<script type="text/javascript" src="public/js/followers.js"></script>
</body>
</html>