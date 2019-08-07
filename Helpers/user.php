 <?php
 // include('Secret/secret.php');
 class User {
    private $error;
    private $con;

  	
  	function __construct($con)
  	{
  		$this->error = array();
  		$this->con = $con;
  	}

  	public function signUp($fname,$lname,$un,$pass,$pass2){
      $this->validateFirstName($fname);
  		$this->validateLastName($lname);
  		$this->validateUsername($un);
  		$this->validatePassword($pass,$pass2);
  		if(empty($this->error)){
  			return $this->insertDetails($fname,$lname,$un,$pass);	
  		}
  		else {
  			return false;
  		}
  	}

  	private function insertDetails($fn,$ln,$un,$pass){
  		$encrypt = password_hash($pass,PASSWORD_DEFAULT);
      $image = 'profile_default';
      $bio = '';
      $dob = '';
      $accType = 'public';
  		$stmt = $this->con->prepare("INSERT INTO user (first_name,last_name,username,password,image_name,bio,dob,acc_type) VALUES (?, ?, ?, ?, ?,?,?,?)");
      $stmt->bind_param('ssssssss',$fn,$ln,$un,$encrypt,$image,$bio,$dob,$accType);
      $stmt->execute();
      $stmt->close();
      return true;
  	}

  	private function validateFirstName($fn){
  		if(strlen($fn) > 20 || strlen($fn) < 3){
  			array_push($this->error,"Invalid First Name");
  			return;
  		}
  	}

  	private function validateLastName($ln){
  		if(strlen($ln) > 20){
  			array_push($this->error,"Invalid Last Name");
  			return;
  		}
  	}

  	private function validateUsername($un){
  		if(strlen($un) > 20){
          array_push($this->error,"Invalid Username");
          return;
  		}

  		$query = "SELECT * FROM user WHERE username = '$un'";
  		$existingUsername = mysqli_query($this->con,$query);
  		if(mysqli_num_rows($existingUsername) > 0){
  			array_push($this->error, "Username Exists");
  			return;
  		}
  	}

  	public function validatePassword($pass,$pass2){
  		if(strlen($pass) < 8 || strlen($pass2) < 8){
  			array_push($this->error,"Longer Password required");
  			return;
  		}
  		if($pass != $pass2){
  			array_push($this->error, "Passwords don't match");
  		}
  	}

  	public function signIn($username,$password){

  		$query = "SELECT * FROM user WHERE username = '$username'";
  		$checkLogin = mysqli_query($this->con,$query);
        if(mysqli_num_rows($checkLogin) == 1){
  	       $row = $checkLogin->fetch_assoc();
  	       $passwordCorrect = password_verify($password,$row['password']);
  	       if($passwordCorrect){
  	       	return true;
  	       }
  	       else{
  	       	 array_push($this->error,"Incorrect Password");
             return false;
           }
  	    }
  	    else {
  	    	array_push($this->error,"Username doesn't exist");
  	    	return false;
  	    }
  	}

  	public function getError($error){
  		if(!in_array($error,$this->error)){
  		       $error = "";
  		}
      if($error != "")
  		  return '<small style="color: #FF4500">'.$error.'</small><br>';
      
  	}

    public function getUserId($username){
      $stmt = $this->con->prepare("SELECT * FROM user WHERE username = ?");
      $stmt->bind_param('s',$username);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      return $row['id'];
    }
    
    public function editDetails($id,$fn,$ln,$bio,$bday,$dpName){
      $stmt = $this->con->prepare('SELECT * FROM user WHERE id=?');
      $stmt->bind_param('i',$id);
      $stmt->execute();
      $res = $stmt->get_result();
      $row = $res->fetch_assoc();
      $this->validateFirstName($fn);
      $this->validateLastName($ln);
      if(empty($this->error)){
       $stmt=$this->con->prepare('UPDATE user SET first_name=?, last_name=?, bio=?,  dob=?, image_name=? WHERE id=?');
       $stmt->bind_param('sssssi',$fn,$ln,$bio,$bday,$dpName,$id);
       return $stmt->execute();
      }
      else {
        return false;
      }
    }
   
    

}

?>