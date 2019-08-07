<?php
session_start();
include('config/config.php');
include('config/secret/secret.php');
if(!isset($_SESSION['userLoggedIn'])){
	header('location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Timeline</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,500,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Handlee&display=swap" rel="stylesheet">
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUfYXPNvO89LB-STHNfbiBepcB4RF79FM&libraries=places&callback=initAutocomplete" async defer></script>
	<link rel="stylesheet" type="text/css" href="public/Stylesheets/timeline.css">
</head>
<body>
	<?php include('add-ons/pop-notify.php'); ?>
	<?php include('add-ons/navbar/navbar.php'); ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<ul id="trending" class="rounded">
					
				</ul>
			</div>
			<div class="col-sm-9">
				<ul id="list" class="rounded">

				</ul>
			</div>
		</div>
	</div>
	<?php include('add-ons/navbar/modal.php'); ?>
	<?php include('add-ons/scripts.php'); ?>
	<script type="text/javascript" src="public/js/hashtag.js"></script>
	<script type="text/javascript">
		var offset = 0;
		var flag = false;
		var flag2 = false;
		var lastId = 0;
		var list = document.getElementById('list') ;
		var id = "<?php echo $_SESSION['userLoggedIn']; ?>";
		var userId = "<?php echo $_SESSION['userLoggedIn']; ?>";
		load();

		function appendPosts(response){
			var spinner = document.getElementById('spinner');
			if(spinner != null){
				spinner.style.display = 'none'
				spinner.id='';
			}
			list.innerHTML += displayDrop(response);
		}

		$(window).scroll(function(){
			if(flag2) return;
			if($(window).scrollTop() +  $(window).height() >= $('#list').height()){
				list.innerHTML += `<div class="text-center"><div id="spinner" class="spinner-border text-light" role="status">
				<span class="sr-only">Loading...</span>
				</div></div>`;
				flag2 = true;
				setTimeout(load,3000);
			}
		})

		function load(){
			if(flag) return;
			flag = true;
			$.ajax({
				type: 'GET',
				url: 'user/timeline.php?id="<?php echo $_SESSION['userLoggedIn']; ?>"',
				data: {
					limit: 6,
					offset: offset,
					lastId: lastId
				},
				success: function(response){
					if(JSON.parse(response).length == 0){
						list.innerHTML += '<div class="text-center"><button type="button" onclick="backToTop()" class="btn back">Back To Top</button></div>';
						var spinner = document.getElementById('spinner');
						if(spinner != null){
							spinner.style.display = 'none'
							spinner.id='';
						}
						return;
					}
					appendPosts(JSON.parse(response));
					offset += 6;
					flag = false;
					flag2 = false;
				},
				error: function(){
					alert('Something went wrong');
				}
			});
		}

		function backToTop(){
			$("html, body").animate({scrollTop: 0}, 1000);
		}	
	</script>
</body>
</html>