 function getDrops(){
    $.ajax({
     type: 'GET',
     url: 'Drop/getDrop.php?id='+ id,
     success: function(drops){
      if(drops != 'private'){
        console.log(drops);
      document.querySelector('#list').innerHTML = displayDrop(JSON.parse(drops));
      }
      else {
        document.querySelector('#list').innerHTML = '<div class="text-center">Account Private <br><small>Follow to get updates </small></div>'
      }
  },
  error: function(){
   alert('cannot retrieve drops');
}
});
}

function displayDrop(drops){
   var output = '';
   var id;
   var drop;
   for(var i = 0;i<drops.length ; i++){
    var replyId = '';
    var replyName = '';
     if(isRepost(drops[i]) && drops[i].reInfo.isreply != 1){
    id = drops[i].reInfo.id;
    drop = drops[i].reInfo;
    }
    else {
      id = drops[i].original.id;
      drop = drops[i].original;
    } 
    output += '<li class="li">';

      if(isRepost(drops[i])){
        if(drops[i].reInfo.isreply == 1){
          output +=  `<p class="ml-4">${drops[i].original.name}<small> ${drops[i].original.username}</small> replied to ${drops[i].reInfo.username}`;
        }
        else {
         output += `<p class="ml-4">${drops[i].reInfo.name}<small> ${drops[i].reInfo.username}</small> shared this post</p>`;  
         output += `<div class="row ml-4" style="font-size:1.3vw; font-family:Handlee;">${drops[i].reInfo.message}</div>`;
        }
      }
      output += `<div class="row">
                    <div class="col-sm-6" style="font-weight:500">
                       <img src="public/images/${drops[i].original.dp}" height="30" width="30">
                        ${drops[i].original.name} <a href="user.php?id=${drops[i].original.userId}" style="color:#FFFDD0;"><small>${drops[i].original.username}</small></a>
                    </div>
                    <div class="col-sm-6 text-right">
                       <small>${drops[i].original.time}</small><br>
                       <small>${drops[i].original.location}</small>
                    </div>
                </div>
                <div class="row ml-5" style="font-size:1.3vw; font-family:Handlee;">${drops[i].original.message}</div>`;

      if(drops[i].original.imgName != ''){
          var src='';
          if(drops[i].original.type == 'api'){
            src = searchGifById(drops[i].original.imgName);
          }
          else {
            src = `public/images/${drops[i].original.imgName}`;
          }

          output += `<div class="row"><img class="img" src="${src}" alt="image not found" height="300" width="350"></div>`;
        } 

        if(isRepost(drops[i]) && drops[i].reInfo.isreply == 1){
          replyId = drops[i].reInfo.id;
          replyName = `${drops[i].reInfo.username}:${drops[i].reInfo.userId}`;
        }
        else {
          replyId = drops[i].original.id;
          replyName = `${drops[i].original.username}:${drops[i].original.userId}`;
        }

        output += `<div class="row share">
                  <div class="col-sm-2" id="${drops[i].original.id}" name="${replyName}" onclick="repost(this)"><i class="fab fa-rev"></i><small>   ${drops[i].original.redrops}</small>
                  </div>`;
   
           
        output += `<div class="col-sm-2" name="${replyName}" id="${replyId}" onclick="reply(this)">
                  <i class="far fa-comments"></i><small>  ${drops[i].original.replies}</small>
               </div>`;
    if(userId == drop.userId){
          output += `<div class="col-sm-8 text-right" id="${id}" onclick="deleteDrop(this.id)"><i class="far fa-times-circle"></i></div>
                    </div>`;      
      } 
      output += '</div>';
      if(drop.replies > 0 || (isRepost(drops[i]) && drops[i].reInfo.isreply == 1)){
        output += `<a href="thread?id=${replyId}" class="viewthread">View Thread</a>`;
      } 
      output += '</li><hr>';
   }

return output;
}

function isRepost(drops){
    if(Object.keys(drops.reInfo).length > 0){
      return true;
    }
    else {
      return false;
    }
}