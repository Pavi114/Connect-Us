<?php 
session_start();
include('config/secret/secret.php');
if(!isset($_GET['id'])){
  header('location: 404.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Thread</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Handlee&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="public/stylesheets/thread.css">
</head>
<body>
  <?php include('add-ons/pop-notify.php'); ?>
<?php include('add-ons/navbar/navbar.php'); ?>
<div class="container rounded">
	<ul class="thread">
		<div id="post">
			
		</div>
	</ul>
</div>
<?php include('add-ons/navbar/modal.php'); ?>
<?php include('add-ons/scripts.php'); ?>
<script type="text/javascript">
	var threadId = <?php echo $_GET['id']; ?>;
	$.ajax({
      type: 'GET',
      url: 'drop/thread.php?id='+threadId,
      success: function(response){
      	console.log(JSON.parse(response));
      	response = JSON.parse(response);
      	display(response[0].parentPost,'parent');
      	for (var i = 0; i < response[0].comments.length; i++) {
      		display(response[0].comments[i]);  
      	}
      	
      },
      error: function(){
      	console.log('error');
      }
	})

	function display(post,type=''){
		var output = '';
		if(type == 'parent'){
			output += '<li class="parent">';
		}
		else {
			output += '<li class="reply">';
		}
		output += `<div class="row">
                      <div class="col-sm-6">
                        <img src="public/images/${post.user_details.dp}" height="30" width="30">
                        ${post.user_details.name} <a href="user.php?id=${post.user_id}" style="color:#FFFDD0;"><small>@${post.user_details.username}</small></a>
                      </div>
                      <div class="col-sm-6 text-right" style="font-size:1vw;">
                         <small>${post.date_create}</small><br>
                         <small>${post.location}</small>
                      </div>
		           </div>
                      <div class="row ml-5" style="font-family:Handlee;">${post.message}</div>`;
                  
        if(post.image_details.image_name != ''){
          var src='';
          if(post.image_details.upload_type == 'api'){
            src = searchGifById(post.image_details.image_name);
          }
          else {
            src = `images/${post.image_details.image_name}`;
          }

          output += `<div class="row"><img class="img" src="${src}" alt="image not found" height="300" width="350"></div>`;
        }

        output += `<div class="row">
                  <div class="col-sm-2" id="${post.id}" onclick="repost(this.id)"><i class="fab fa-rev"></i><small> ${post.redrops}</small></div>`; 
        output += `<div class="col-sm-2" id="${post.id}" name="@${post.user_details.username}:${post.user_id}" onclick="reply(this)">
                        <i class="far fa-comments"></i><small> ${post.replies}</small>
                   </div>`;     

 
        output += '</div>';
        if(post.replies > 0 && type != 'parent'){
        	output += `<a href="thread?id=${post.id}" class="viewthread">View Thread</a>`;
        }

        output += '</li><hr>';
                   
		document.querySelector('#post').innerHTML += output;
	}
</script>
</body>
</html>