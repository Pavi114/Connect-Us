		var charCount = document.querySelector('#charCount');
		var message = document.querySelector('#message');
		var image = document.querySelector('#image');
	document.querySelector('#adddropbutton').addEventListener('click',function(){
		message.value = '';	
		charCount.innerHTML = 0;
		if(image.files[0]){
			image.files[0].value = '';
		}
	});
	message.addEventListener("input",function(){
		charCount.innerHTML = message.value.length;
		if(message.value.charAt(message.value.length - 1) == '#'){
			message.style.color = '#0000e5';
		}
		if(message.value.charAt(message.value.length - 1) == ' '){
			message.style.color = '#000000';
		}
		getHashtags(message.value);
		if(message.value.length > 150){
			message.style.shadowBlur = '0 0 5px #FF0000';
			message.style.border = '2px solid red';
		}
		else {
			message.style.shadowBlur = '0 0 5px #00FF00';
			message.style.border = '2px solid green';
		}
	});

	function preview(){
		img = document.createElement('IMG');
		img.width = '90';
		img.height = '80';
		var reader = new FileReader();
		reader.onload = function(event){
			img.src = event.target.result;
		}
		reader.readAsDataURL(image.files[0]);
		message.parentNode.insertBefore(img,message.nextSibling);
	}
   
    function getHashtags(string){
    	var re = /\B(\#[a-zA-Z]+\b)(?!;)/g;

    	var hashtags = string.match(re);
        if(hashtags.length > 0){
             console.log(JSON.stringify(hashtags));
             document.querySelector('input[name="hashtags"]').value = JSON.stringify(hashtags);
         }
    }