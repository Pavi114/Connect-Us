<?php
include_once('image.php');
include_once('current_user.php');
class Drop 
{
	private $con;
	private $id;
	private $drops = array();
	private $message;
  private $location;

  function __construct($con,$id,$message='',$location=''){
    $this->con = $con;
    $this->id = $id;
    $this->message = $message;
    $this->location = $location;
  }

  public function getDrops(){
    return $this->drops;
  }

  public function insertDrop($parentId=0,$comment=''){
   $date = date('d-m-Y H:i:s');
   $redrops = 0;
   $likes = 0;
   $replies = 0;
   $islike = 0;
   $isreply = 0; 
   $isredrop = 0;
   if($comment == 'repost'){
    $isredrop = 1;
    $this->update($parentId,'redrops');
  }
  else if($comment == 'reply'){
    $isreply = 1;
    $this->update($parentId,'replies');
  }
  $stmt = $this->con->prepare('INSERT INTO drops (user_id,message,date_create,redrops,likes,replies,is_like,is_reply,is_redrop,location,parent_id) VALUES (?, ?, ?,?,?,?,?,?,?,?,?)');
  $stmt->bind_param('issiiiiiisi',$this->id,$this->message,$date,$redrops,$likes,$replies,$islike,$isreply,$isredrop,$this->location,$parentId);
  return $stmt->execute();
}

public function getRecentDropId(){
  $stmt = $this->con->prepare("SELECT * FROM drops WHERE user_id='$this->id' ORDER BY id DESC LIMIT 1");
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  return $row['id'];
}

public function fetchDropsFromDb(){
 $stmt = $this->con->prepare('SELECT * FROM drops WHERE user_id = ? ORDER BY date_create DESC');
 $stmt->bind_param('i',$this->id);
 $stmt->execute();
 $result = $stmt->get_result();
 if(mysqli_num_rows($result) > 0){
  while($row = $result->fetch_assoc()){
    $reInfo = array();
    $orgInfo = array();
    if($row['parent_id'] != 0){
      if($row['is_reply'] == 1){
        $orgInfo = $this->getOrgInfo($row['id'],$row['user_id'],false);
        $orgUser = $this->getDropById($row['parent_id']);
        if($orgUser != 'deleted'){
          $reInfo = $this->getReInfo($orgUser['user_id'],$orgUser['date_create'],$row['parent_id'],1,$row['replies'],$row['message']);
        }
      }
      else if($row['is_redrop'] == 1){
       $orgInfo = $this->getOrgInfo($row['id'],$row['parent_id'],true);
       $reInfo = $this->getReInfo($row['user_id'],$row['date_create'],$row['id'],0,$row['replies'],$row['message']);
     }
   }
   else {
     $orgInfo = $this->getOrgInfo($row['id'],$row['user_id'],false);
   }

   array_push($this->drops, (object)[
    'original' => (object)$orgInfo,
    'reInfo' => (object)$reInfo
  ]);
 }
}
}

public function update($id,$type,$decrement=false){
  if(!$decrement){
   $query = "UPDATE drops SET $type = $type + 1 WHERE id='$id'";
 }
 else {
  $query = "UPDATE drops SET $type = $type - 1 WHERE id='$id'";
}

mysqli_query($this->con,$query);
}

public function getOrgInfo($id,$parent_id,$isRe){
  $orgInfo = array();
  $res = '';
  if($isRe){
    $res = $this->getDropById($parent_id);
  }
  else {
    $res = $this->getDropById($id);
  }
  
  $orgInfo['message'] = $res['message'];
  $orgInfo['location'] = $res['location'];
  $orgInfo['redrops'] = $res['redrops'];
  $orgInfo['likes'] = $res['likes'];
  $orgInfo['replies'] = $res['replies'];
  $orgInfo['time'] = date('d-m-Y H:i A',strtotime($res['date_create']));  
  $image = new Image($this->con); 
  $orgInfo['imgName'] = $image->getImageName($res['id']);
  $orgInfo['type'] = $image->getUploadType($res['id']);
  $orgInfo['id'] = $res['id'];

  $user = new CurrentUser($this->con,'',$res['user_id']);
  $userdetails = $user->getUserDetails();
  $orgInfo['userId'] = $userdetails['id'];
  $orgInfo['username'] =  '@'.$userdetails['username'];
  $orgInfo['name'] = $userdetails['first_name']. ' '.$userdetails['last_name'];
  $orgInfo['dp'] = $userdetails['image_name'];

  return $orgInfo;
}


private function getReInfo($user_id,$date,$id,$isReply,$replies,$message){
  $reInfo = array();
  $user = new CurrentUser($this->con,'',$user_id);
  $userdetails = $user->getUserDetails(); 
  $reInfo['user_id'] = $userdetails['id'];
  $reInfo['username'] = '@'.$userdetails['username'];
  $reInfo['name'] = $userdetails['first_name'].' '.$userdetails['last_name'];
  $reInfo['time'] = date('d-m-Y',strtotime($date)); 
  $reInfo['id'] = $id;
  $reInfo['isreply'] = $isReply;
  $reInfo['replies'] = $replies;
  $reInfo['message'] = $message;
  return $reInfo;
}

public function getTimeline($limit,$offset,$lastId){
  $list = array();
  $res = mysqli_query($this->con,"SELECT * FROM drops WHERE (user_id=$this->id OR user_id IN (SELECT following_id
   FROM following
   WHERE user_id=$this->id)) ORDER BY date_create DESC LIMIT $limit OFFSET $offset");
  while($row = $res->fetch_assoc()){
    $orgInfo = array();
    $reInfo = array();
    if($row['parent_id'] != 0){
     if($row['is_reply'] == 1){
      $orgInfo = $this->getOrgInfo($row['id'],$row['user_id'],false);
      $orgUser = $this->getDropById($row['parent_id']);
      if($orgUser != 'deleted'){
       $reInfo = $this->getReInfo($orgUser['user_id'],$orgUser['date_create'],$row['parent_id'],1,$row['replies'],$row['message']);
     }
   }
   else {
     $orgInfo = $this->getOrgInfo($row['id'],$row['parent_id'],true);
     $reInfo = $this->getReInfo($row['user_id'],$row['date_create'],$row['id'],0,$row['replies'],$row['message']); 
   }

 }
 else {
  $orgInfo = $this->getOrgInfo($row['id'],$row['user_id'],false);
}
array_push($list, (object)[
  'original' => (object)$orgInfo,
  'reInfo' => (object)$reInfo
]);
}
return $list;

}


public function getDropById($id){
  $stmt = $this->con->prepare("SELECT * FROM drops WHERE id=?");
  $stmt->bind_param('i',$id);
  $stmt->execute();
  $res = $stmt->get_result();
  if(mysqli_num_rows($res) > 0){
    $row = $res->fetch_assoc();
  // $date = date('d-M-Y H:i A',strtotime($row['date_create']));
    return $row;  
  }
  else {
    return 'deleted';
  }
  
}

public function getComments($parent_id){
 $is_reply = 1;
 $comments = array();
 $stmt = $this->con->prepare("SELECT * FROM drops WHERE parent_id = ? and is_reply = ? ORDER BY date_create DESC");
 $stmt->bind_param('ii',$parent_id,$is_reply);
 $stmt->execute();
 $res = $stmt->get_result();
 while($row = $res->fetch_assoc()){
  $row['date_create'] = date('d-M-Y H:i A',strtotime($row['date_create']));
  array_push($comments,(object)$row);
}
return $comments;
}

public function getThread($id){
  $parentPost = $this->getDropById($id);
  $comments = $this->getComments($id);
  $thread = array();
  array_push($thread,(object)[
   'parentPost' => $parentPost,
   'comments' => $comments
 ]);
  return $thread;
}

// public function getDropsByHashtag($name){
//   $list = array();
//   $string = '%'.$name.'%';
//   $res = mysqli_query($this->con,'SELECT * FROM drops WHERE message LIKE $string');
//   while ($row = $res->fetch_assoc()) {
//     $orgInfo = array();
//     $reInfo = array();
//     if($row['parent_id'] != 0){
//      if($row['is_reply'] == 1){
//       $orgInfo = $this->getOrgInfo($row['id'],$row['user_id'],false);
//       $orgUser = $this->getDropById($row['parent_id']);
//       if($orgUser != 'deleted'){
//        $reInfo = $this->getReInfo($orgUser['user_id'],$orgUser['date_create'],$row['parent_id'],1,$row['replies'],$row['message']);
//       }
//      }
//     else {
//      $orgInfo = $this->getOrgInfo($row['id'],$row['parent_id'],true);
//      $reInfo = $this->getReInfo($row['user_id'],$row['date_create'],$row['id'],0,$row['replies'],$row['message']); 
//      } 
//    }
//   else {
//     $orgInfo = $this->getOrgInfo($row['id'],$row['user_id'],false);
//     }
//    array_push($list, (object)[
//       'original' => (object)$orgInfo,
//       'reInfo' => (object)$reInfo
//     ]); 
//   }
//   return $list;
//  }
}
?>