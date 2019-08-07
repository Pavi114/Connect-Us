<div class="modal fade" id="privacymodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      	<div id="pvt">
      	<div>Make Account Private?</div>
      	<button type="button" class="btn" id="privacy" name="privacy">Confirm</button>
      </div>
       <div  id="pub">
      	<div>Make Account Public?</div>
      	<button type="button" class="btn" id="public" name="public">Confirm</button>
      </div>
      	<?php
          if(isset($user)){
      	  $details = $user->getUserDetails(); 
      	  if($details['acc_type'] == 'public'){
      	  	echo '<script>document.querySelector("#pub").style.display = "none" </script>';
      	  }
      	  else {
      	  	echo '<script>document.querySelector("#pvt").style.display = "none" </script>';
      	  }
        }
      	?>
      	
      </div>
  </div>
</div>
</div>

<style type="text/css">
	#privacymodal {
		color: black;
	}
</style>