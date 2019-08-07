 var search = document.querySelector('input[name="search"]');
 search.addEventListener('input',function(){
  $.ajax({
    type: 'GET',
    url: 'user/searchUser.php?q='+search.value,
    success: function(users){
      console.log(JSON.parse(users));
        displaylist(JSON.parse(users));
      },
      error: function(){
        alert('something went wrong')
      }
    });

});

 function displaylist(users){
  var output = '';
   for (var i = users.length - 1; i >= 0; i--) {
      output += `<div id='${users[i].id}' class="user dropdown-item" onMouseOver="this.style.backgroundColor='rgba(0,0,0,0.4)'" onMouseOut="this.style.backgroundColor='rgba(0,0,0,0.2)'">
                   <form action="user.php" method='GET'><button class="btn" type="submit" style="color: #FFFFFF;">
                    <div class="row m-0"><div class="col-sm-3"><img src="public/images/${users[i].image_name}" height="40" width="40"></div>
                     <div class="col-sm-9">${users[i].first_name} ${users[i].last_name}<br>@${users[i].username}</div></div></button>
                     <input type="hidden" value='${users[i].id}' name="id"></form></div>`;
   }
   document.querySelector('#searchRes').innerHTML = output;
 }