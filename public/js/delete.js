	function deleteDrop(id){
		console.log(id);
		$.ajax({
			type: 'POST',
			url: 'Drop/delDrop.php',
			data: {id: id},
			success: function(response){
				console.log(response);
				getDrops();
			},
			error: function(){
				alert('error deleting');
			}
		});
	}