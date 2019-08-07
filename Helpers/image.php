<?php
class Image {
	private $con;
	private $imageName;
	private $imageExt;
	private $imageTemp;
	private $imageSize;
	private $uploadFrom;

	function __construct($con,$image='',$type='local',$gifId=''){
		$this->con = $con;
		if(!empty($gifId)){
			$this->imageName = $gifId;
			$this->uploadFrom = $type;
		}
		else if(!empty($image)){
			$this->imageName = $image['name'];
			$this->imageTemp = $image['tmp_name'];
			$nameSplit = explode('.',$this->imageName);
			$this->imageExt = strtolower(end($nameSplit));
			$this->imageSize = $image['size'];
			$this->uploadFrom = $type;
		}

	}

	public function getName(){
		return $this->imageName;
	}

	public function movefile($num = 1){
		$this->imageName = uniqid('',true).'.'.$this->imageExt;
		echo $this->imageName;
			if($num == 0){
           $target = 'public/images/'.$this->imageName;
		}
		else {
			$target = '../public//images/'.$this->imageName;
		}
		if(!move_uploaded_file($this->imageTemp, $target)){
			return false;
		}
		return true;
	}

	public function validatefile(){

		$allowedExt = array('jpg', 'jpeg', 'png', 'gif');
		if(!in_array($this->imageExt, $allowedExt)){
			echo '<script>alert("File of this type cannot be uploaded")</script>';
			header('location: ../home.php');
		}
		else if($this->imageSize > 10000000){
			echo '<script>alert("Size of file too large")</script>';
			header('location: ../home.php');
		}
	}

	public function insert($id){
		$stmt = $this->con->prepare('INSERT INTO files (drop_id,name,type) VALUES (?,?,?)');
		$stmt->bind_param('iss',$id,$this->imageName,$this->uploadFrom);
		if($stmt->execute()){
			return true;
		}
		$stmt->close();
		return false;
	}

	public function getImageName($id){
		$stmt = $this->con->prepare("SELECT * FROM files WHERE drop_id = '$id'");
		$stmt->execute();
		$result = $stmt->get_result();
		if(mysqli_num_rows($result) > 0){
			$row = $result->fetch_assoc();
			return $row['name'];
		}
		else {
			return '';
		}
	}
    
    public function getUploadType($id){
    	$stmt = $this->con->prepare("SELECT * FROM files WHERE drop_id = '$id'");
		$stmt->execute();
		$result = $stmt->get_result();
		if(mysqli_num_rows($result) > 0){
			$row = $result->fetch_assoc();
			return $row['type'];
		}
		else {
			return '';
		}
    }

}
?>