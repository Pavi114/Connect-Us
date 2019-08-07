var basicInfo = document.querySelector('#basicInfo');
var list = document.querySelector('#list');
var type;
basicInfo.onclick = function(event){
	var url = '';
	var followers = event.target.closest('button[name="followers"]');
	var following = event.target.closest('button[name="following"]');
	var posts = event.target.closest('button[name="drops"]');
	var follow = event.target.closest('button[name="follow"]');
	if(followers){
		url = 'user/followers.php?id='+ id;
		type = 1;
	}
	else if(following){
		url = 'user/following.php?id='+ id;
		type = 2;
	}
	else if(posts){
		document.querySelector(".heading").innerHTML = "Feed";
		getDrops();
		type = 3;
	}
	else if(follow){
		$.ajax({
			type: 'GET',
			url:'user/toggleFollow.php?id='+id1,
			success: function(response){
				console.log(response);
				if(response == 'followed'){
					follow.innerHTML = '<i class="fas fa-check-double"></i> Following';
					follow.style.color = '#00FF00';
					follow.title = "unfollow";
				}
				else 
				if(response == 'unsend'){
					follow.innerHTML = 'Follow';
					follow.style.color = '#FFFFFF';
					follow.title = "follow";
				}
				else if(response == 'sent'){
					follow.innerHTML = '<i class="far fa-clock"></i> Requested';
					follow.style.color = '#FFFFFF';
					follow.title = "unfollow";
				}
				else {
					follow.innerHTML = 'Follow';
					follow.style.color = '#FFFFFF';
				}
				return;
			},
			error: function(){
				console.log('error');
			}
		});
	}
	if(url != ''){
		$.ajax({
			type: 'GET',
			url: url,
			success: function(response){
				console.log(response);
				if(response == 'private'){
					document.querySelector('#list').innerHTML = '<div class="text-center">Account Private <br><small>Follow to get updates </small></div>';
					return;
				}
				displayList(JSON.parse(response),type);

			},
			error: function(){
				console.log('something unexpected occurred');
			}
		})
	}
}

function displayList(response,type){
	document.querySelector(".heading").innerHTML = "";
	var color = '';
	if(type == 1){
		color = "#00c500";
	}
	else {
		color = "#FFFF00";
	}
	var output = '<div class="row">';
	for (var i = response.length - 1; i >= 0; i--) {
		output += `<div class="col-md-3 follow" style="border-color: ${color}">
		<div class="text-center">
		<img src="public/images/${response[i].dp}" alt="not found" width="60" height="65"><br>
		<div class="dispUser text-left pl-2" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)),  ${color}">${response[i].name} <br><a href="user.php?id=${response[i].id}"><small>@${response[i].username}</small></a></div>
		</div>
		</div>`;  
	}
	output += '</div>';
	list.innerHTML = output;
}