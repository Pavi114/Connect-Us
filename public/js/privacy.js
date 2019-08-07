var privacybtn = document.getElementById('privacy');
var publicbtn = document.getElementById('public');
privacybtn.addEventListener("click",function(){
	url = 'user/privacy.php?status=private';
	callAjax(url);
})

publicbtn.addEventListener("click",function(){
   url = 'user/privacy.php?status=public';
   callAjax(url);
})

function callAjax(url){
$.ajax({
		type:'GET',
		url: url,
		success: function(response){
			console.log(response);
			if(response == 'success'){
				document.querySelector('#privacymodal .modal-body').innerHTML += '<div> Successfully Changed </div>';
			}
		},
		error: function(){
			console.log('SOmething unexpected happened');
		}
	});
}