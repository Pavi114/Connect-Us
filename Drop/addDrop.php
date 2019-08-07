<?php
session_start();
include('../config/config.php');
include('../Helpers/image.php');
include('../Helpers/drop.php');
include('../Helpers/hashtag.php');
if(isset($_POST['message'],$_POST['isReply'])){
  print_r($_POST);
  if(!empty($_POST['hashtags'])){
    print_r(json_decode($_POST['hashtags'])); 
    foreach (json_decode($_POST['hashtags']) as $key => $value) {
      $hashtag = new Hashtag($con,$value);
      $hashtag->insert();
    }
  }
  $location = '';
  $parentId = 0;
  $comment = '';

  if(!empty($_POST['gifId'])){
    $gifId = $_POST['gifId'];
    $gif = new Image($con,'','api',$_POST['gifId']);
  }
  
  if(!empty($_FILES['image']['name'])){
    $image = new Image($con,$_FILES['image']);
    $image->validatefile();
  }
  if(!empty($_POST['place'])){
    $location = $_POST['place'];
  }
  $drop = new Drop($con,$_SESSION['userLoggedIn'],$_POST['message'],$location);
  if($_POST['isReply'] == 'yes'){
    $parentId = $_POST['replyPId'];
    $comment = 'reply';
    $activity = new Activity($con,$_SESSION['userLoggedIn'],$_POST['replyUserId'],'reply:'.$parentId);
    $activity->insert();
  }
  else if($_POST['isRepost'] == 'yes'){
    $parentId = $_POST['repostPId'];
    $comment = 'repost';
    $activity = new Activity($con,$_SESSION['userLoggedIn'],$_POST['repostUserId'],'repost:'.$parentId);
    $activity->insert();
  }

  if($drop->insertDrop($parentId,$comment)){
   $id = $drop->getRecentDropId();
   if(isset($gif)){
      if($gif->insert($id)){
       header('location: ../home.php');
      }
    }
   if(isset($image) && $image->movefile()){
     if($image->insert($id)){
       header('location: ../home.php');
     }
    }
       header('location: ../home.php');
  }
}
?>