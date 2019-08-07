var hashtag = document.querySelector('#trending');
getTrending();
function getTrending(){
	$.ajax({
		type: 'GET',
		url: 'Drop/trending.php',
		success: function(response){
			console.log(response);
			displayHash(JSON.parse(response));
		},
		error: function(){
			console.log('error');
		}
	});
}

function displayHash(response){
	var output = '';
	for (var i = 0; i < response.length; i++) {
		output += `<li><div class="row ml-2 hash">${response[i].hashtag_name}</div><div class="row ml-3 count">${response[i].drops} drops</div></li>`;
	}
	hashtag.innerHTML = output;
}