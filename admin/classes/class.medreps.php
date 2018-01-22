<?php
class Medreps{
	public $db;
	
	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}
	

	public function get_medreps(){
		$sql = "SELECT * from tbl_medrep order by status DESC";
		
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

	public function deactivate_medrep($medrep_id){
		$sql = "UPDATE tbl_medrep SET status=0 where medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function activate_medrep($medrep_id){
		$sql = "UPDATE tbl_medrep SET status=1 where medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}
	
	public function add_medrep($firstname, $middlename, $lastname, $address, $contact, $email, $username, $password, $usr_id){
	
	$sqlUsername = "SELECT * FROM tbl_medrep WHERE mr_username = '$username'";
	$checkUsername=$this->db->query($sqlUsername);
	$count_row_username = $checkUsername->num_rows;
	if($count_row_username > 0){
		$result = "username";
	}else{
		$sqlName = "SELECT * FROM tbl_medrep WHERE mr_firstname = '$firstname' AND mr_lastname='$lastname' AND mr_middlename='$middlename' AND status='1'";
		$checkName=$this->db->query($sqlName);
		$count_row = $checkName->num_rows;
		if($count_row > 0){
			$result = "unable";
		}else{
			$sql = "INSERT into tbl_medrep(mr_firstname, mr_middlename, mr_lastname, mr_address, mr_contact_no, mr_email, mr_username, mr_password, date_added, time_added, usr_id) VALUES ('$firstname', '$middlename', '$lastname', '$address', '$contact', '$email', '$username', '$password', NOW(),NOW(), '$usr_id')";
			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		}	
	}
	return $result;
	}
	
	public function get_specific_medrep($id){
		$sql = "SELECT * from tbl_medrep WHERE medrep_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return json_encode($row);
	}

	public function update_medrep($id, $firstname, $middlename, $lastname, $address, $contact, $email, $usr_id)	{
		$sqlrow = "SELECT * FROM tbl_medrep WHERE mr_firstname = '$firstname' AND mr_lastname='$lastname' AND mr_middlename='$middlename' AND medrep_id!='$id'";
			$check=$this->db->query($sqlrow);
			$count_row = $check->num_rows;
		if($count_row == 0){
		$sql = "UPDATE tbl_medrep SET mr_firstname='$firstname', mr_middlename='$middlename', mr_lastname='$lastname', mr_address='$address', mr_contact_no='$contact', mr_email='$email', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' where medrep_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
		}
		else{
			return 0;
		}
	}

	public function remove_medrep($id){
		$sql = "UPDATE tbl_medrep SET status='0' where medrep_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function get_assignments(){
		$sql = "SELECT client_id, client_name, address, area_name, mr_lastname, mr_firstname, mr_middlename FROM tbl_area, tbl_client LEFT OUTER JOIN tbl_medrep ON tbl_client.medrep_id=tbl_medrep.medrep_id where tbl_area.area_id=tbl_client.area_id AND tbl_client.status='1'";
		
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

	public function get_assign_info($client_id){
		$sql = "SELECT client_id, client_name, tbl_client.address as address, area_name, mr_lastname, mr_firstname, mr_middlename FROM tbl_area, tbl_client LEFT OUTER JOIN tbl_medrep ON tbl_client.medrep_id=tbl_medrep.medrep_id where tbl_area.area_id=tbl_client.area_id AND tbl_client.status='1' AND tbl_client.client_id='$client_id'";
		
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function update_assignment($client_id, $medrep_id)	{
		$sql = "UPDATE tbl_client SET medrep_id='$medrep_id' where client_id='$client_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function get_medrep_monthly(){
		//ACTIVE
		$sql = "SELECT *, SUM(payment_amount) as actual_sales FROM (SELECT * FROM tbl_payment WHERE MONTH(payment_date)=MONTH(CURRENT_TIMESTAMP) AND YEAR(payment_date)=YEAR(CURRENT_TIMESTAMP))tbl_payment RIGHT OUTER JOIN tbl_invoice ON tbl_invoice.invoice_id=tbl_payment.invoice_id RIGHT OUTER JOIN tbl_ord_invoice ON tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id RIGHT OUTER JOIN tbl_order ON tbl_order.order_id=tbl_ord_invoice.order_id RIGHT OUTER JOIN tbl_client ON tbl_client.client_id = tbl_order.client_id RIGHT OUTER JOIN (SELECT * FROM tbl_medrep WHERE status='1')tbl_medrep ON tbl_medrep.medrep_id=tbl_client.medrep_id GROUP BY tbl_medrep.medrep_id ORDER BY actual_sales DESC";
		$result = mysqli_query($this->db,$sql);
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }else{
            $list=false;
        }

        //INACTIVE
        $sql1 = "SELECT *, SUM(payment_amount) as actual_sales FROM (SELECT * FROM tbl_payment WHERE MONTH(payment_date)=MONTH(CURRENT_TIMESTAMP) AND YEAR(payment_date)=YEAR(CURRENT_TIMESTAMP))tbl_payment RIGHT OUTER JOIN tbl_invoice ON tbl_invoice.invoice_id=tbl_payment.invoice_id RIGHT OUTER JOIN tbl_ord_invoice ON tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id RIGHT OUTER JOIN tbl_order ON tbl_order.order_id=tbl_ord_invoice.order_id RIGHT OUTER JOIN tbl_client ON tbl_client.client_id = tbl_order.client_id INNER JOIN (SELECT * FROM tbl_medrep WHERE status='0')tbl_medrep ON tbl_medrep.medrep_id=tbl_client.medrep_id GROUP BY tbl_medrep.medrep_id ORDER BY actual_sales DESC";
		$result1 = mysqli_query($this->db,$sql1);
		if (mysqli_num_rows($result1)>0){
			while($row1 = mysqli_fetch_assoc($result1)){
				$list[] = $row1;
			}
        }
                
		return $list;
	}

	public function medrep_prod_monthly($medrep_id){
		$sql = "SELECT *, SUM(tbl_invoice_item.quantity) AS total_sold FROM tbl_invoice_item, tbl_ord_invoice, tbl_order, tbl_client WHERE tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND Year(tbl_invoice_item.date_added) = Year(CURRENT_TIMESTAMP) AND Month(tbl_invoice_item.date_added) = Month(CURRENT_TIMESTAMP) AND tbl_ord_invoice.order_id = tbl_order.order_id AND tbl_order.client_id=tbl_client.client_id AND tbl_client.medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sold'];
		return $value;
	}

	public function medrep_orders_monthly($medrep_id){
		$sql = "SELECT *, COUNT(tbl_order.order_id) AS total_orders FROM tbl_order, tbl_client WHERE Year(order_date) = Year(CURRENT_TIMESTAMP) AND Month(order_date) = Month(CURRENT_TIMESTAMP) AND tbl_order.client_id=tbl_client.client_id AND tbl_client.medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_orders'];
		return $value;
	}

	public function medrep_delivery_monthly($medrep_id){
		$sql = "SELECT *, COUNT(delivery_id) AS total_delivery FROM tbl_delivery WHERE Year(date_delivered) = Year(CURRENT_TIMESTAMP) AND Month(date_delivered) = Month(CURRENT_TIMESTAMP) AND  medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_delivery'];
		return $value;
	}
}