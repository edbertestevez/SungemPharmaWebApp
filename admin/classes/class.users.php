<?php
class Users{
	public $db;
	
	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}
	
	public function get_users(){
		$sql = "SELECT *, acc_name from tbl_users, tbl_access where tbl_users.acc_id=tbl_access.acc_id AND usr_stat='1' AND acc_name!='Client' order by usr_lastname ";
		
		$result = mysqli_query($this->db,$sql);
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }else{
            $list=false;
        }
                
		return $list;
	}

	public function get_specific_user($id){
		$sql = "SELECT *, acc_name from tbl_users, tbl_access where tbl_users.acc_id=tbl_access.acc_id AND usr_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function get_accesslist(){
		$sql = "SELECT * from tbl_access where acc_name!='Client'";
		
		$result = mysqli_query($this->db,$sql);
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }else{
            $list=false;
        }
                
		return $list;
	}

	public function new_user($username,$password,$access,$lastname,$firstname){
	$sql = "SELECT * FROM tbl_users WHERE usr_username = '$username'";
	$check=$this->db->query($sql);
	$count_row = $check->num_rows;
		if($count_row == 0){
			$newpassword = md5($password);
			$sql = "INSERT INTO tbl_users(usr_username,usr_password,usr_lastname,
			usr_firstname,usr_date_added,usr_time_added,acc_id, usr_stat) 
			VALUES('$username','$newpassword','$lastname','$firstname',NOW(),NOW(),'$access', 1)";
			
			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
			return $result;
		}else{
			$result = "unable";
			return $result;
		}
	}

	public function deactivate_user($usr_id){
		$sql = "UPDATE tbl_users SET usr_stat=0 where usr_id='$usr_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function update_user($id,$password,$lastname,$firstname){
		$new_pass=md5($password);
		if($password!=""){
			$sql = "UPDATE tbl_users SET usr_firstname='$firstname', usr_lastname='$lastname', usr_password='$new_pass' where usr_id='$id'";
		}else{
			$sql = "UPDATE tbl_users SET usr_firstname='$firstname', usr_lastname='$lastname' where usr_id='$id'";
		}
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function update_user_with_image($id,$password,$lastname,$firstname,$image){
		$new_pass=md5($password);
		if($password!=""){
			$sql = "UPDATE tbl_users SET usr_firstname='$firstname', usr_lastname='$lastname', usr_password='$new_pass', acc_photo='$image' where usr_id='$id'";
		}else{
			$sql = "UPDATE tbl_users SET usr_firstname='$firstname', usr_lastname='$lastname', acc_photo='$image' where usr_id='$id'";
		}
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function check_login($username,$password){
		$sql = "SELECT * FROM tbl_users WHERE 
		usr_username='$username' AND usr_password='$password' AND acc_id IN('4001','4002')";
		$result=mysqli_query($this->db,$sql);
		$userdata=mysqli_fetch_array($result);
		$count = $result->num_rows;
		if($count == 1){
			$_SESSION['login']=true;
            $_SESSION['access']=$userdata['acc_id'];
            if($userdata['acc_photo']!=""){
            	$_SESSION['image']=$userdata['acc_photo'];
        	}else{
        		$_SESSION['image']="img/logo.PNG";
        	}
            $_SESSION['userid']=$userdata['usr_id'];
            $_SESSION['current_user']=$userdata['usr_username'];
            $_SESSION['current_password']=$userdata['usr_password'];
            $_SESSION['userfname']=$userdata['usr_firstname'];
            $_SESSION['userlname']=$userdata['usr_lastname'];
			$_SESSION['userdata']= $userdata['usr_firstname'].' '.$userdata['usr_lastname'];
			return true;
		}
		else{
			return false;
		}
	}

	public function get_session(){
		if(isset($_SESSION['login']) && $_SESSION['login'] == true){
			return true;
		}
		else{
			return false;
		}
	}

	//PROFILE PIC
	public function update_profile_pic($img_name, $usr_id){
		$sql = "UPDATE tbl_users SET acc_photo='$img_name' WHERE usr_id='$usr_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}
}