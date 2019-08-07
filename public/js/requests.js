var requestbtn = document.getElementById('getRequests');
var numRequests = document.getElementById('numRequests');
getNumRequests();
setInterval(getNumRequests,20000);
function getNumRequests(){
	$.ajax({
	type:'GET',
	url:'user/request.php?type=numRequests',
	success: function(response){
		numRequests.innerHTML = response;
	},
	error: function(){
		console.log("Error occured");
	}
  });
}
requestbtn.addEventListener("click",function(){
  $.ajax({
  	type:'GET',
	url:'user/request.php?type=fetchRequests',
	success: function(response){
		displayRequests(JSON.parse(response));
	},
	error: function(){
		console.log("Error occured");
	}
  })
})

function displayRequests(response){
	var output = '';
	for(var i = 0;i<response.length;i++){
		output += `<li><div class="row">
		                 <div class="col-sm-8"><img src="public/images/${response[i].dp}" width="40" height="40"> @${response[i].username} </div>
		                 <div class="col-sm-2"><button type="button" class="btn" style="color: #00e500" id="${response[i].id}" onclick="requestResponse(this.id)" name="requestResponse" value="Accept">Accept</button></div>
		                 <div class="col-sm-2"><button type="button" class="btn" style="color: #ff4500" id="${response[i].id}" onclick="requestResponse(this.id)" name="requestResponse" value="Decline">Decline</button></div> 
		               </div>
		           </li>`;
	}
	document.querySelector('#list').innerHTML = output;
}

function requestResponse(id){
	var list = document.getElementById('list');
	list.onclick = function(event){
		var response = event.target.closest('button[name="requestResponse"]');
		if(response){
			if(response.value = 'Accept'){
              callAjaxResponse('accept',id);
			}
			else if(response.value = 'Decline'){
				callAjaxResponse('decline',id);
			}
		}
	}
}

function callAjaxResponse(status,id){
	$.ajax({
		type:'GET',
		url: `user/requestResponse.php?status=${status}&id=${id}`,
		success: function(response){
			document.querySelectorAll('button[name="requestResponse"]')[0].style.display = 'none';
			document.querySelectorAll('button[name="requestResponse"]')[1].innerHTML = 'Accepted';
		},
		error: function(){
			console.log('error');
		}
	})
}