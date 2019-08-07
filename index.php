<?php
session_start();
include('config/Secret/secret.php');
include('config/config.php');
include('config/database.php');
include('Helpers/user.php');

$user = new User($con);
$loginSuccessful = false;
$signUp = false;

function recaptcha(){
	$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.SECRET_KEY.'&response='.$_POST['recaptcha-response']);
	$response = json_decode($response);
	
	if($response->success && $response->score > 0.5){
		return true;
	}
	else {
		return false;
	}
}

if(isset($_POST['signup'])){

	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$conpass = $_POST['confirm'];
	if(recaptcha()){
		$signUp = $user->signUp($fname,$lname,$username,$password,$conpass);
	}
	else {
		echo '<script>alert("Try Again")</script>';
	}
	
}
if(isset($_POST['signin'])){
	$username = $_POST['userlogin'];
	$password = $_POST['passwordlogin'];

	$loginSuccessful = $user->signIn($username,$password);
	if($loginSuccessful){
		$_SESSION['userLoggedIn'] = $user->getUserId($username);
		$_SESSION['name'] = $username;
		header('location: home.php');
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pigeons: Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://www.google.com/recaptcha/api.js?render=<?php echo SITE_KEY; ?>"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,500,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<link rel="stylesheet" type="text/css" href="public/Stylesheets/index.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" id="headMain" href="index.php"><span><i class="fas fa-project-diagram"></i></span>Pigeons</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ml-auto">
				<li class="dropdown"><a class="dropdown-toggle btn" href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-log-in"></span> Login</a>
					<div class="dropdown-menu dropdown-menu-right">
						<form action="index.php" method="POST">
							<div class="form-group">
								<label for="userLogin">Username</label>
								<input type="text" id="userLogin" class="form-control" placeholder="username" name="userlogin">
							</div>
							<div class="form-group">
								<label for="passwordlogin">Password</label>
								<input type="password" id="passwordlogin" class="form-control" placeholder="password" name="passwordlogin">
							</div>
							<button type='submit' class="btn" id='login' name='signin'>Login</button>		
						</form>
					</div>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container main">
		<div class="row">
			<div class="col-sm-6">
				<h2>REGISTER</h2>
				<form action="index.php" method="POST">
					<?php
					if($signUp){
						echo 'Successfully Registered';
					}
					?>
					<?php echo $user->getError('Invalid First Name'); ?>
					<?php echo $user->getError('Invalid Last Name'); ?>
					<div class="row">
						<div class="col">
							<label for="name">First Name:</label>
							<input type="text" id="name" class="form-control" placeholder="3-20 char" name="fname" required>
						</div>
						<div class="col">
							
							<label for="name">Last Name:</label>
							<input type="text" id="name" class="form-control" placeholder="max 20 char" name="lname">
						</div>
					</div>
					<div class="form-group">
						<?php echo $user->getError('Invalid Username'); ?>
						<?php echo $user->getError('Username Exists'); ?>
						<label for="username">Username</label>
						<input type="text" name="username" id="username" class="form-control" required>
					</div>
					<div class="form-group">
						<?php echo $user->getError('Longer Password required'); ?>
						<label for="password">Password</label>
						<input type="password" name="password" class="form-control" required>
						<small>Password must be minimum 8 characters</small>
					</div>
					<div class="form-group">
						<?php echo $user->getError( "Passwords don't match"); ?>
						<label for="confirmPass">Confirm Password</label>
						<input type="password" name="confirm" class="form-control" required>
					</div>
					<input type="submit" class="btn form-control" name="signup" value="Sign Up">
					<input type="hidden" name="recaptcha-response">
				</form>
			</div>
			<div class="col-sm-4 title">
				<h2><span><i class="fas fa-project-diagram"></i></span>PIGEONS</h2>
				<div>Place to share your messages</div>
			</div>
		</div>	
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script>
		grecaptcha.ready(function() {
			grecaptcha.execute('<?php echo SITE_KEY; ?>', {action: 'homepage'}).then(function(token) {
				console.log(token);
				document.querySelector('input[name="recaptcha-response"]').value = token;
			});
		});

		var username = document.getElementById('username');
		username.addEventListener('input',function(){
			$.ajax({
				type:'GET',
				url:'user/checkUser.php?username='+username.value,
				success:function(response){
					console.log(response);
					if(response == 'yes'){
						username.style.boxShadow = '0 0 4px #00FF00';
						username.style.border = '1px solid #00FF00';
					}
					else {
						username.style.boxShadow = '0 0 4px #FF0000';
						username.style.border = '2px #FF0000';
					}
				},
				error: function(){
					console.log('error occured');
				}
			})
		})
	</script>
</body>
</html>