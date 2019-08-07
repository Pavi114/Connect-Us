<?php
include_once('activity.php');
class CurrentUser
{  
	private $con;
	private $id;
	private $userLoggedIn;
	
	function __construct($con,$userLoggedIn,$id)
	{
	   $this->con = $con;
	   $this->id = $id;
	   $this->userLoggedIn = $userLoggedIn;
	}

	public function displayDetails(){
		$output = '';
		
        $row = $this->getUserDetails();
        $drops = $this->execute('drops');
	    $followers = $this->execute('followers');
	    $following = $this->execute('following');
	    $isfollowing = $this->getFollow();

	    $activity = new Activity($this->con,$this->userLoggedIn,$this->id,'request sent');
	    $isRequestSent = $activity->isRequestSent();
	    $button = '';
	    if($this->id != $this->userLoggedIn){
	      if($isfollowing){
	    	$button = '<button type="button" name="follow" style="color: #00FF00; border: 1px solid white" class="btn" id="'.$this->id.'" title="unfollow"><i class="fas fa-check-double"></i>  Following</button>';
	      }
	      else if($isRequestSent){
	      	$button = '<button type="button" name="follow" style="color: #FFFFFF; border: 1px solid white" class="btn" id="'.$this->id.'" title="unsend"><i class="far fa-clock"></i> Requested</button>';
	      }
	      else {
	    	$button = '<button type="button" name="follow" style="color: #FFFFFF; border: 1px solid white" class="btn" id="'.$this->id.'" title="follow">Follow</button>';
	      }
	    }
	    
	    $output .= '<div class="col-sm-4">
	                   <div class="row"><img src="public/images/'.$row['image_name'].'" height="170" width="170"></div>
                       <div class="row">'.$row["first_name"].' '.$row['last_name'].'</div>';
         if(!empty($row['dob'])){
         	$output .= '<div class="row"><span class="mr-2"><i class="fas fa-birthday-cake"></i></span><i> '.$row['dob'].'</i></div>'; 
         }              
	                 
	      $output .= '</div>
	                  <div class="col-sm-7" style="font-family: Handlee;">
	                  <div class="row">'.$button.'</div>
	                  <div class="row">
	                   <div class="col-sm-3">
                        <button type="button" name="followers" class="btn" style="color: #00FF00">Followers<br>'.$followers.'</button>
	                   </div>
	                   <div class="col-sm-3">
                        <button type="button" name="following" class="btn" style="color: #FFFF00">Following<br>'.$following.'</button>
	                   </div>
	                   <div class="col-sm-3">
                        <button type="button" name="drops" class="btn" style="color: #FF4500">Posts<br>'.$drops.'</button>
	                   </div>
	                   </div>';
	     if(!empty($row['bio'])){
	     	$output .= '<div class="row mt-4 ml-5"><p><i>Bio: '.$row['bio'].'</p></i></div>';
	     }               
	        $output .= '</div></div>';
	    
	    return $output;            
	}

	public function getUserDetails(){
		$stmt = $this->con->prepare('SELECT * FROM user WHERE id=?');
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		return $row;
	}

	private function execute($tablename){
		$query = "SELECT count(*) as total FROM ".$tablename." WHERE user_id='$this->id'";
		$result = mysqli_query($this->con,$query);
		$row = $result->fetch_assoc();
		return $row['total'];
	}

	public function getFollow(){
		$stmt = $this->con->prepare('SELECT * FROM following WHERE user_id=? and following_id=?');
		$stmt->bind_param('ii',$this->userLoggedIn,$this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		if(mysqli_num_rows($result) > 0){
			return true;
		}
		return false;
	}

	public function toggleFollow(){
		if($this->getFollow()){
			$stmt = $this->con->prepare('DELETE FROM following WHERE user_id=? and following_id=?');
			$stmt->bind_param('ii',$this->userLoggedIn,$this->id);
			if($stmt->execute()){
				$stmt = $this->con->prepare('DELETE FROM followers WHERE user_id=? and followers_id=?');
				$stmt->bind_param('ii',$this->id,$this->userLoggedIn);
				if($stmt->execute()){
					return 'unfollowed';
				}
			}
		}
		else {
			$stmt = $this->con->prepare('INSERT INTO following (user_id,following_id) VALUES (?, ?)');
			$stmt->bind_param('ii',$this->userLoggedIn,$this->id);
			if($stmt->execute()){
				$stmt = $this->con->prepare('INSERT INTO followers (user_id,followers_id) VALUES (?, ?)');
				$stmt->bind_param('ii',$this->id,$this->userLoggedIn);
				if($stmt->execute()){
					return 'followed';
				}
			}
		}
	}
	
	public function getFollowers(){
		$followList = array();
		$stmt = $this->con->prepare('SELECT * FROM followers WHERE user_id=?');
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$res = $stmt->get_result();
		while($row = $res->fetch_assoc()){
			$stmt = $this->con->prepare("SELECT * FROM user WHERE id = ?");
			$stmt->bind_param('i',$row['followers_id']);
			$stmt->execute();
			$result = $stmt->get_result();
			$result = $result->fetch_assoc();
			array_push($followList, (object)[
				       'id' => $result['id'],
			         'username' => $result['username'],
			          'name' => $result['first_name'] .' '. $result['last_name'],
			          'dp' => $result['image_name']
			      ]);
		}
		return $followList;
		
	}

	public function getFollowing(){
       $followingList = array();
		$stmt = $this->con->prepare('SELECT * FROM following WHERE user_id=?');
		$stmt->bind_param('i',$this->id);
		$stmt->execute();
		$res = $stmt->get_result();
		while($row = $res->fetch_assoc()){
			$stmt = $this->con->prepare("SELECT * FROM user WHERE id = ?");
			$stmt->bind_param('i',$row['following_id']);
			$stmt->execute();
			$result = $stmt->get_result();
			$result = $result->fetch_assoc();
			array_push($followingList, (object)[
				       'id' => $result['id'],
			         'username' => $result['username'],
			          'name' => $result['first_name'] .' '. $result['last_name'],
			          'dp' => $result['image_name']
			      ]);
		}
		return $followingList;
	}

	public function setAccType($status){
       $stmt = $this->con->prepare("UPDATE user SET acc_type = ? WHERE id= ?");
       $stmt->bind_param('si',$status,$this->id);
       if($stmt->execute()){
       	return 'success';
       }
       return 'no';
	}
}
?>