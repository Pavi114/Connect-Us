<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-dark m-0" id="navbar">
  <a class="navbar-brand" id="headMain" href="#"><span><i class="fas fa-project-diagram"></i></span>PIGEONS</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="home.php">@<?php echo $_SESSION['name'] ?></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="timeline.php?id=<?php echo $_SESSION['userLoggedIn']; ?>">Timeline</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="notifications.php?id=<?php echo $_SESSION['userLoggedIn']; ?>"><span class="icon"><i class="fas fa-bell"></i><sup id="unreadNotifs"><span>0</span></sup></span></a>
        <input type="hidden" id="bell">
      </li>
      <li class="nav-item active">
        <button class="btn" id="getRequests"><span class="icon"><i class="fas fa-user-friends"></i><sup id="numRequests"><span>0</span></sup></span></button>
      </li>
    </ul>
    <ul class="navbar-nav m-auto">
      <li class="nav-item">
        <button class="btn" id="adddropbutton" data-toggle="modal" data-target="#drop"><span class="icon"><i class="fas fa-feather"></i></span></button>
      </li>
      
    </ul>
    <ul class="navbar-nav ml-auto">
     <li class="nav-item mr-2 dropdown">
      <div class="nav-link dropdown-toggle" href="#" id="search" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         <input class="form-control mr-sm-2" name='search' type="search" placeholder="Search for user" aria-label="Search"> 
    </div>
    <div class="dropdown-menu bg-dark" aria-labelledby="search" id="searchRes">
    </div>
  </li>
  <li class="nav-item dropdown mr-4">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Settings
    </a>
    <div class="dropdown-menu bg-dark" aria-labelledby="navbarDropdown">
      <a class="dropdown-item bg-dark" href="editProfile.php">Edit Profile</a>
      <a href="#" class="dropdown-item bg-dark" data-toggle="modal" data-target="#privacymodal">Privacy</a>
      <a class="dropdown-item bg-dark" href="user/signOut.php">Sign Out</a>
    </div>
  </li>
</ul>
</div>
</nav>

<style type="text/css">
.navbar {
  min-height: 30px;
  max-height: 50px;
}
.dropdown-toggle::after {
  display:none;
}

#navbarSupportedContent .nav-item a {
  color: white;
}
#headMain {
  color: #ff4500;
  letter-spacing: 5px;
}
.dropdown-menu .dropdown-item {
  color: white;
}
#getRequests {
  color: white;
}
#numRequests,#unreadNotifs {
  font-size: 1.2vw;
  border-radius: 50%;
  background: #000000;
  padding: 2px;
  color: #F5F6Ef;
}

.icon {
  padding: 6px;
  color: white;

}
input[name="search"]{
  border-radius: 20px;
}

.bell {
  animation: notifbell 2s ease-in 5;

}

#searchRes .dropdown-item {
  padding: 0;
  border-radius: 2px;
}
@keyframes notifbell {

  0% {transform: rotate(0); }
  1% {transform: rotate(30deg); }
  5% {transform: rotate(-28deg); }
  10% {transform: rotate(23deg); }
  15% {transform: rotate(-20deg); }
  20% {transform: rotate(17deg); }
  25% {transform: rotate(-15deg); }
  30% {transform: rotate(12deg); }
  35% {transform: rotate(-10deg); }
  40% {transform: rotate(8deg); }
  45% {transform: rotate(-5deg); }
  50% {transform: rotate(2deg); }
  55% {transform: rotate(-1deg); }
  60% {transform: rotate(0deg); }
  100% {transform: rotate(0deg); }

}
</style>