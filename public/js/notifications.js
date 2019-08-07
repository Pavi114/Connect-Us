var unreadNotifs = document.querySelector('#unreadNotifs');
var notifCount = 0;
var read = document.querySelector(".read");
var bellBtn = document.querySelector('#bell');
var newNotif = document.querySelector('.newNotif');
var bell = document.querySelector('.bell');
var audio = new Audio('public/audio/notif.mp3');
var prevNotif = 0;
numNotifs();
setInterval(numNotifs,10000);

bellBtn.addEventListener("click",function(){
	audio.play();
})

function numNotifs(){
	$.ajax({
	type:'GET',
	url:'user/notifications.php?type=numNotifs',
	success: function(response){
		console.log(response);
		if(response > prevNotif){
			prevNotif = response;
			document.querySelector('.fa-bell').classList.add('bell');
			bellBtn.click();	
		}
		unreadNotifs.innerHTML = response;
	},
	error: function(){
		console.log("Error occured");
	}
});
}



function getNotifs(){
	console.log('hi');
  $.ajax({
  	type:'GET',
  	url: 'user/notifications.php?type=getNotifs',
  	success: function(response){
  		console.log(response);
  		response = JSON.parse(response);
  		notifCount = 0;
  		if(response.length == 0){
  			document.querySelector('.notifications').innerHTML += "No Notifications :(";
  		}
  		for (var i = response.length-1; i >= 0; i--) {
  			displayNotif(response[i]);
  		}
         
  	},
  	error: function(){
  		console.log('error');
  	}
  })
}

function displayNotif(notif){
	var color = getRandomColor();
	var output = `<li id="${notifCount++}" style="border-right: 1px solid ${color}"`;
	output += `>`;
	if(notif.status == 0){
		output += `<hr class="newnotif" style="background: ${color}">`
	}
	output += `<div class="row">
	             <div class="col-sm-2" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.6)), ${color} ;"></div>`;
	output += `<div class="col-sm-9">${notif.user.name}<a href="user.php?id=${notif.user.user_id}"> @${notif.user.username}</a>`;
	if(notif.type == 'repost'){
		output += ` shared your <a href="thread.php?id=${notif.post.id}">post</a></div>`;
	}
	else if(notif.type == 'request accept'){
		output += ` accepted your follow request</div>`;
	}
	else if(notif.type == 'reply'){
		output += ` replied to your <a href="thread.php?id=${notif.post.id}">post</a></div>`;
	}
	else if(notif.type == 'followed'){
		output += ` started following you</div>`;
	}
	output += '</div>';

	if(notif.status == 0){
		output += `<hr class="newnotif" style="background: ${color}">`
	}
	output += '</li>';

	document.querySelector('.notifications').innerHTML += output;
  
}

function getRandomColor(){
	var letters = "0123456789ABCDEF";
	var color = "#";
	 for (var i = 0; i < 6; i++) {
       color += letters[Math.floor(Math.random() * 16)];
     }
     return color;
}

