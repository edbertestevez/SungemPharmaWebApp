<?php
class Suppliers{
	public $db;
	
	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}
	

	
	public function get_suppliers(){
		$sql = "SELECT * from tbl_supplier order by supplier_name";
		
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

public function get_suppliers_active(){
		$sql = "SELECT * from tbl_supplier WHERE status='1' order by supplier_name";
		
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


	public function get_delivery_count($supplier_id, $start, $end){
		$sql = "SELECT COUNT(*) AS total FROM tbl_prod_supplier WHERE supplier_id='$supplier_id' AND '$start'<=date_added AND '$end'>=date_added";
		
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['total'];
	}

	public function add_supplier($name, $address, $contact, $email, $usr_id){
		$sql = "SELECT * FROM tbl_supplier WHERE supplier_name='$name' AND status='1'";
		$check=$this->db->query($sql);
		$count_row = $check->num_rows;
			if($count_row == 0){
			$sql = "INSERT into tbl_supplier(supplier_name,address,contact_no,email,date_added,time_added, usr_id) VALUES ('$name', '$address', '$contact', '$email', NOW(), NOW(), '$usr_id')";
			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
			return $result;
			}
			else{
				$result = 0;
				$data = "unable";
				return $data;
			}	
		}

	public function get_specific_supplier($row_id){
		$sql = "SELECT * from tbl_supplier WHERE supplier_id='$row_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return json_encode($row);
	}

	public function update_supplier($id, $name, $address, $contact, $email, $usr_id){
		$sqlrow = "SELECT * FROM tbl_supplier WHERE supplier_name='$name' AND status='1' AND supplier_id!='$id'";
		$check=$this->db->query($sqlrow);
		$count_row = $check->num_rows;
			if($count_row == 0){
			$sql = "UPDATE tbl_supplier SET supplier_name='$name', address='$address', contact_no='$contact', email='$email', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' where supplier_id='$id'";
			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
			return $result;
		}else{
			return "error";
		}
		
	}

	public function remove_supplier($id, $usr_id){
		$sql = "UPDATE tbl_supplier SET status='0', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' where supplier_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function activate_supplier($id, $usr_id){
		$sql = "UPDATE tbl_supplier SET status='1', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' where supplier_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

}