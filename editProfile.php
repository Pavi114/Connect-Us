<?php
session_start();
include('config/config.php');
include('Helpers/current_user.php');
include('Helpers/user.php');
include('Helpers/image.php');

$editProfile = false;
if(isset($_POST['edit'])){
	$editUser = new User($con);
	$user = new CurrentUser($con,'',$_SESSION['userLoggedIn']);
	$details = $user->getUserDetails();
	if(isset($_FILES['image'])){
		$dp = $_FILES['image'];
		$dp = new Image($con,$_FILES['image']);
		if($dp->movefile(0)){
			$name = $dp->getName();
		}
	else {
		$name = $details['image_name'];
	}
}
	if($editUser->editDetails($_SESSION['userLoggedIn'],$_POST['first_name'],$_POST['last_name'],$_POST['bio'],$_POST['bday'],$name)){
		$editProfile = true;
	}
}
if(!isset($_SESSION['userLoggedIn'])){
	header('location: index.php');
}
else {
	$user = new CurrentUser($con,'',$_SESSION['userLoggedIn']);
	$details = $user->getUserDetails();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Profile</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUfYXPNvO89LB-STHNfbiBepcB4RF79FM&libraries=places&callback=initAutocomplete" async defer></script>
    <link rel="stylesheet" type="text/css" href="public/stylesheets/editProfile.css">
</head>
<body>
	<?php include('add-ons/navbar/navbar.php'); ?>
	<div class="container rounded">
		<!-- <span id='success'>Edited Successfully</span> -->
		<form action="editProfile.php" method="POST" enctype="multipart/form-data">
			<div class="row form-group">
				<div class="col">
					<label>First Name</label>
					<input type="text" name="first_name" value="<?php echo $details['first_name']; ?>" class="form-control">
				</div>
				<div class="col">
					<label>Last Name</label>
					<input type="text" name="last_name" value="<?php echo $details['last_name']; ?>" class="form-control">
				</div>
			</div>
			<div class="row form-group">
				<img src="public/images/<?php echo $details['image_name']; ?>" id="profile" width="120" height="120">	
				<div class="custom-file">
					<label class="custom-file-label" for="image">Choose file</label>
					<input type="file" id="image" name="image" onchange="preview()">
				</div>
			</div>
			<div class="form-group">
				<label>Tell us a bit about yourself</label>
				<textarea rows="6" cols="10" name="bio" class="form-control" placeholder="Bio"><?php echo $details['bio']; ?></textarea>
			</div>
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
				</div>
				<input type="date" name="bday" class="form-control" value="<?php echo $details['dob']; ?>" max="2019-08-30">		
			</div>
			<div class="text-center">
			   <button type="submit" class="btn" name="edit">Save Changes</button>	
			</div>
		</form>
	</div>
	<?php include('add-ons/navbar/modal.php'); ?>
	<?php include('add-ons/navbar/privacy.php'); ?>
	<?php include('add-ons/pop-notify.php'); ?>
	<?php include_once('add-ons/scripts.php'); ?>
	<?php
    	if($editProfile){
             echo '<script>document.querySelector("#editPost").style.display = "block";
                      setTimeout(function(){
                      	document.querySelector("#editPost").style.display = "none";
                      },3000);
                   </script>';
	    }
	?>
	<script type="text/javascript">
		function preview(){
			img = document.getElementById('profile');
			image = document.getElementById('image');
			var reader = new FileReader();
			reader.onload = function(event){
				img.src = event.target.result
			}
			reader.readAsDataURL(image.files[0]);
		}
	</script>
</body>
</html>