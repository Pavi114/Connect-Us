<?php
class Hashtag {
	private $con;
	private $id;
	private $hashtag;
	private $drops;

	function __construct($con,$hashtag=''){
		$this->con = $con;
        $this->hashtag = $hashtag;
	}

	function insert(){
		$stmt = $this->con->prepare("INSERT INTO hashtags (hashtag_name) VALUES (?)");
		$stmt->bind_param('s',$this->hashtag);
		if($stmt->execute()){
			echo 'yes';
		}
		else {
			$this->update();
		}
	}

	function update(){
         $stmt = $this->con->prepare("UPDATE hashtags SET drops = drops + 1 WHERE hashtag_name = ?");
         $stmt->bind_param('s',$this->hashtag);
         $stmt->execute();
	}
	function getTrending(){
		$hashtag = array();
		$stmt = $this->con->prepare("SELECT * FROM hashtags ORDER BY drops DESC LIMIT 8");
		$stmt->execute();
		$res = $stmt->get_result();
		while($row = $res->fetch_assoc()){
			array_push($hashtag, $row);
		}
		return $hashtag;
	}
	function getName($id){
		$stmt = $this->con->prepare("SELECT hashtag_name FROM hashtags WHERE id=?");
		$stmt->bind_param('i',$id);
		$stmt->execute();
		$res = $stmt->get_result();
		$row = $res->fetch_assoc();
		return $row['hashtag_name'];
	}
}
?>