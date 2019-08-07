function reply(object){
	$('#drop').modal('show');
	var isReply = document.querySelector('input[name="isReply"]');
	var replyId = document.querySelector('input[name="replyPId"]');
	var replyUserId = document.querySelector('input[name="replyUserId"]');
	var replyPerson = document.querySelector('#replyPerson');
	var reply = document.querySelector('.reply');
	document.querySelector('.repost').style.display = 'none';
	var arr = object.getAttribute('name').split(':');
	reply.style.display = 'block';
	isReply.value = "yes";
	replyId.value = object.id;
	replyUserId.value = arr[1];
   replyPerson.innerHTML = `<span> ${arr[0]}</span>`;
   replyPerson.href = `user.php?id=${arr[1]}`;
}