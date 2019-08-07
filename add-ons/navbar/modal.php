<div class="modal fade" id="drop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Drop</h5>
      </div>
      <div class="modal-body">
        <div class="reply">
         <div class="row reply">Replying To  <a id="replyPerson"></a></div>  
       </div>
       <div class="repost">
         <div class="row repost">Repost: </div>  
       </div>
       <form id="form" method="POST" enctype="multipart/form-data" action="Drop/addDrop.php">
        <input type="hidden" name="isReply" value="no">
        <input type="hidden" name="isRepost" value="no">
        <input type="hidden" name="replyPId" value="">
        <input type="hidden" name="replyUserId" value="">
        <input type="hidden" name="repostPId" value="">
         <input type="hidden" name="repostUserId" value="">
        <input type="hidden" name="hashtags" value="">

        <div>
         <div class="input-group mb-3 ml-auto">
          <div class="input-group-prepend">
            <span class="input-group-text" id="mapIcon"><i class="fas fa-map-marker-alt"></i></span>
          </div>
          <input type="text" class="form-control" name="place">
        </div>   
        <span id="removeLoc"><i class="far fa-times-circle"></i></span>
      </div>

      <textarea id='message' name="message" class="form-control mt-3" rows='4' placeholder='your message'></textarea>
      <img src="" id="gifPreview">
      <input type="hidden" name="gifId">

      <div class="row" id="feature">
        <div class="col-sm-2">
          <button class='btn' type="button" onclick="document.querySelector('#image').click()"><i class="far fa-images"></i></button>
          <input type='file' name='image' onchange="preview()" style="display: none" id='image'>
        </div>
        <div class="col-sm-2">
          <button class='btn' type="button" name="gifBtn">gif</button>
        </div>
        <div class="col-sm-2">
          <button class='btn' type="button" name='loc'><i class="fas fa-map-marker-alt"></i></button>
        </div>
        <div class="col-sm-2 ml-auto">
         <p><span id='charCount'>0</span>/150</p>
       </div>
     </div>
     <div id='gifBox' class="row">
       <input type="text" class="form-control" name="gif" id="gif">
       <div id="outerDiv">
         <div id="displayGif">
         </div>  
       </div>
     </div>
   </div>
   <div class="modal-footer">
    <button type="submit" id="addDrop" class="btn btn-primary">Drop It</button>
  </div>
</form>
</div>
</div>
</div>

<style type="text/css">
#drop {
  color: #000000;
}
.modal-body.dropdown{
  display: none;
}
.modal {
  margin-top: 60px;
}
#outerDiv {
  height: 200px;
  overflow: scroll;
}

#removeLoc{
  position: absolute;
  right: 20px;
  top: 15px;
  bottom: 0;
  height: 14px;
  color: #000000;
}

.hidden {
  display: none;
}
</style>

<script type="text/javascript">
  var removeLoc = document.querySelector('#removeLoc');
  var place = document.querySelector('input[name="place"]');
  var loc = document.querySelector('button[name="loc"]');
  var gifBox = document.querySelector('#gifBox');
  document.querySelector('.reply').style.display = 'none';
  document.querySelector('.repost').style.display = 'none';
  gifBox.style.display = 'none';
  removeLoc.parentNode.style.display = 'none';
  removeLoc.addEventListener("click",function(){
   place.value = "";
   removeLoc.parentNode.style.display = 'none';
 })
  loc.addEventListener("click",function(){
    removeLoc.parentNode.style.display = 'block';
  })
</script>