function repost(object){
	console.log(object);
	$('#drop').modal('show');
	var arr = object.getAttribute('name').split(':');
	document.querySelector('input[name="isRepost"]').value = "yes";
	document.querySelector('input[name="repostPId"]').value = object.id;
	document.querySelector('input[name="repostUserId"]').value = arr[1];
	// document.querySelector('#repostNotif').style.display = 'block';
	document.querySelector('.repost').style.display = 'block';
	document.querySelector('#feature').classList.add(document.querySelector('.hidden'));
	setTimeout(function(){
		document.querySelector('#repostNotif').style.display = 'none';
	},3000);
}
