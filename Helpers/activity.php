<?php
 class Activity {
 	private $con;
 	private $userLoggedIn;
 	private $otherUser;
 	private $comment;

 	function __construct($con,$userLoggedIn,$otherUser='',$comment=''){
 		$this->con = $con;
 		$this->userLoggedIn = $userLoggedIn;
 		$this->otherUser = $otherUser;
 		$this->comment = $comment;
 		$this->status = 0;
 	}

 	public function insert(){
 		$stmt = $this->con->prepare("INSERT INTO activity (user_id,message,user_id2,status) VALUES (?, ?, ?,?)");
 		$stmt->bind_param('isii',$this->userLoggedIn,$this->comment,$this->otherUser,$this->status);
 		return $stmt->execute();
 	}

 	public function remove(){
 		$stmt = $this->con->prepare("DELETE FROM activity WHERE user_id = ? and user_id2 = ? and message = ?");
 		$stmt->bind_param('iis',$this->userLoggedIn,$this->otherUser,$this->comment);
 		return $stmt->execute();
 	}

 	public function isRequestSent(){
 		$stmt = $this->con->prepare("SELECT * FROM activity WHERE user_id = ? and user_id2 = ? and message = ?");
 		$stmt->bind_param('iis',$this->userLoggedIn,$this->otherUser,$this->comment);
 		$stmt->execute();
 		$res = $stmt->get_result();
 		if(mysqli_num_rows($res) > 0){
 			return true;
 		}
 		return false;
 	}

 	public function getTotalRequests(){
 		$stmt = $this->con->prepare("SELECT count(*) AS total FROM activity WHERE user_id2 = ? and message=? ");
 		$stmt->bind_param('is',$this->userLoggedIn,$this->comment);
 		$stmt->execute();
 		$res = $stmt->get_result();
 		$res = $res->fetch_assoc();
 		return $res['total'];
 	}

 	public function getRequests(){
 		$idList = array();
 		$stmt = $this->con->prepare("SELECT * FROM activity WHERE user_id2 = ? and message=? ");
 		$stmt->bind_param('is',$this->userLoggedIn,$this->comment);
 		$stmt->execute();
 		$res = $stmt->get_result();
 		while($row = $res->fetch_assoc()){
 			array_push($idList,$row['user_id']);
 		}
 		return $idList;
 	}

 	public function getTotalNotifs(){
 		$read = 0;
 		$stmt = $this->con->prepare("SELECT count(*) as total FROM activity WHERE user_id2=? and message != ? and status=?");
 		$stmt->bind_param('isi',$this->userLoggedIn,$this->comment,$read);
 		$stmt->execute();
 		$res = $stmt->get_result();
 		$res = $res->fetch_assoc();
 		return $res['total'];
 	}

 	public function getNotifs(){
 		$list = array();
 		$comment = 'request sent';
 		$stmt = $this->con->prepare("SELECT * FROM activity WHERE user_id2 = ? and message != ? ORDER BY id");
 		$stmt->bind_param('is',$this->userLoggedIn,$comment);
 		$stmt->execute();
 		$res = $stmt->get_result();
 		while($row = $res->fetch_assoc()){
 			array_push($list, $row);
 		}
 		return $list;
 	}

 	public function updateReadStatus($id){
 		$status = 1;
 		$stmt = $this->con->prepare("UPDATE activity SET status = ? WHERE id = ?");
 		$stmt->bind_param('ii',$status,$id);
 		$stmt->execute();
 	}


 }
?>