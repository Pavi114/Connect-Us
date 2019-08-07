var gifBtn = document.querySelector('button[name="gifBtn"]');
var gif = document.querySelector('#gif');
var drop = document.querySelector('#drop .modal-body');
var gifBox = document.querySelector("#gifBox");
var limit = 6;
var offset = 0;
var isSearch = false;
var flagSet = false;

gifBtn.addEventListener("click",function(){
        gifBox.style.display = 'block';
	         getGif('trending');
});
gif.addEventListener("input",function(){
	console.log('hi');
	         getGif('search',gif.value);
});


	$('#outerDiv').scroll(function(){
		if(flagSet) return;
		if($(outerDiv).scrollTop() +  $(outerDiv).height() >= $('#displayGif').height() && !isSearch){
		displayGif.innerHTML += `<div class="text-center"><div id="spinner" class="spinner-border text-light" role="status">
                               <span class="sr-only">Loading...</span>
                           </div></div>`;
         console.log('bottom');
           flagSet = true;
		  setTimeout(function(){
             getGif('trending');
		  },3000);
		}
	})

function getGif(type,q=''){
	if(type == 'trending'){
		url = `https://api.giphy.com/v1/gifs/trending?api_key=${GIPHY_KEY}&limit=`+ limit + '&offset=' + offset;
	}
	else {
		url = `https://api.giphy.com/v1/gifs/search?api_key=${GIPHY_KEY}&q=`+ q + '&limit=' + limit;
		isSearch = true;
	}
	$.ajax({
		type:'GET',
		url: url,
		success: function(response){
			console.log(response);
			if(type == 'trending'){
				var spinner = document.getElementById('spinner');
		         if(spinner != null){
		         	spinner.style.display = 'none'
		         	spinner.id='';
		         }
				flagSet = false;
				offset += limit;
				displayGif.innerHTML += displayGifs(response);
			}
			else {
				displayGif.innerHTML = displayGifs(response);
			}
			
		},
		error: function(){
			console.log("error");
		}
	})
}

function displayGifs(response){
	var displayGif = document.querySelector('#displayGif');
	if(response.hasOwnProperty('data')){
		var gifList = response.data;
		var output = '<div class="row">';
		for(var i = 0;i<gifList.length;i++){
			output += `<div class="col-sm-4"><img class="gifs" onclick="gifpreview(this.src,this.id)" src=${gifList[i].images['fixed_height_small'].url} id=${gifList[i].id}></div>`;
		}
		output += '</div>';
		return output;
	}
}

function gifpreview(src,id){
	var preview = document.querySelector('#gifPreview');
	var gifId = document.querySelector('input[name="gifId"]');
	var gifs = document.querySelector('.gifs');
	gifId.value = id;
	console.log(gifId.value);
	preview.src = src;
	preview.classList.add('gifs');
}

function searchGifById(id){
	var url = '';
	var status = false;
	$.ajax({
		type: 'GET',
		url: `http://api.giphy.com/v1/gifs?ids=${id}&api_key=${GIPHY_KEY}`,
		async: false,
		success: function(response){
			console.log(response);
			url = response.data[0].images.fixed_height_small.url;
			status = true;
		},
		error: function(){
			console.log('error');
		}
	})
	return url;

}