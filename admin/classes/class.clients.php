<?php
class Clients{
	public $db;
	
	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}
	
	/**public function get_clients(){
		$sql = "SELECT *,tbl_client.status AS client_status, area_name from tbl_client, tbl_area where tbl_client.area_id = tbl_area.area_id order by client_name, area_name";
		
		$result = mysqli_query($this->db,$sql);
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }else{
            $list=false;
        }
                
		return $list;
	}**/

	public function get_clients(){
		$sql = "SELECT *,tbl_client.status as client_status, area_name from tbl_client, tbl_area where tbl_client.area_id = tbl_area.area_id order by client_name, area_name";
		
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

	public function get_clients_activated(){
		$sql = "SELECT *,tbl_client.status as client_status, area_name from tbl_client, tbl_area where tbl_client.status = 1 AND tbl_client.area_id = tbl_area.area_id order by client_name, area_name";
		
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

	public function get_clients_desc(){
		$sql = "SELECT *, area_name from tbl_client, tbl_area where tbl_client.area_id = tbl_area.area_id AND tbl_client.status='1' order by area_name ASC, client_name ASC";
		
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

	public function get_requests(){
		$sql = "SELECT *, area_name from tbl_requests, tbl_area where tbl_requests.area_id = tbl_area.area_id order by date_requested";
		
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

	public function get_area(){
		$sql = "SELECT * from tbl_area";
		
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

	public function add_area($area_name){
	$sqlcheck = "SELECT * FROM tbl_area WHERE area_name = '$area_name' and status='1'";
	$check=$this->db->query($sqlcheck);
	$count_row = $check->num_rows;
		if($count_row == 0){
		$sql = "INSERT into tbl_area(area_name) VALUES ('$area_name')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
		}
		else{
			return "error";
		}	
	}

	public function update_area($area_id, $area_name){
	$sqlcheck = "SELECT * FROM tbl_area WHERE area_name = '$area_name' AND status='1' AND area_id!='$area_id'";
	$check=$this->db->query($sqlcheck);
	$count_row = $check->num_rows;
		if($count_row == 0){
		$sql = "UPDATE tbl_area SET area_name='$area_name' where area_id='$area_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
		}
		else{
			return "error";
		}	
	}

	public function get_specific_area($area_id){
		$sql = "SELECT * from tbl_area WHERE area_id='$area_id' AND status='1'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return json_encode($row);
	}

	public function add_client($name, $area, $address, $license, $contact, $email){
	$sql = "SELECT * FROM tbl_client WHERE client_name = '$name' OR lto_no='$license' AND status='1'";
	$check=$this->db->query($sql);
	$count_row = $check->num_rows;
		if($count_row == 0){
		$sql = "INSERT into tbl_client(client_name,area_id,address,lto_no,contact_no,email,date_added,time_added) VALUES ('$name', '$area', '$address', '$license', '$contact', '$email', NOW(), NOW())";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
		}
		else{
			$result = 0;
			$data = "unable";
			return $data;
		}	
	}

	public function get_specific_client($row_id){
		$sql = "SELECT * from tbl_client WHERE client_id='$row_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return json_encode($row);
	}

	public function get_client_name($row_id){
		$sql = "SELECT client_name from tbl_client WHERE client_id='$row_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['client_name'];
	}

	public function update_client($id, $name, $area, $address, $license, $contact, $email){
		$sqlrow = "SELECT * FROM tbl_client WHERE client_name = '$name' AND status='1' AND client_id!='$id'";
			$check=$this->db->query($sqlrow);
			$count_row = $check->num_rows;
		if($count_row == 0){
			$sql = "UPDATE tbl_client SET client_name='$name', area_id='$area', address='$address', lto_no='$license', contact_no='$contact', email='$email', date_modified=NOW(), time_modified=NOW() where client_id='$id'";
			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
			return $result;
		}else{
			return "error";
		}
	}

	public function remove_client($id){
		$sql = "UPDATE tbl_client SET status='0', date_modified=NOW(), time_modified=NOW() where client_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}
	public function activate_client($id){
		$sql = "UPDATE tbl_client SET status='1', date_modified=NOW(), time_modified=NOW() where client_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function accept_request($id){
		$getsql = "SELECT * FROM tbl_requests WHERE request_id = '$id'";
		$result = mysqli_query($this->db,$getsql);
		$request = mysqli_fetch_assoc($result);
		$username = $request['username'];
		$password = $request['password'];
		$name = $request['name'];
		$license = $request['lto_no'];
		$area = $request['area_id'];
		$address = $request['address'];
		$contact = $request['contact_no'];
		$email = $request['email'];
		$firstname=$request['usr_FName'];
		$lastname = $request['usr_LName'];

		//UPDATE KA CLIENT EITHER MA INSERT OR UPDATE NA DI PRE
		$sql = "SELECT * FROM tbl_client WHERE status='1' AND lto_no='$license'";
		$check=$this->db->query($sql);
			$count_row = $check->num_rows;
		if($count_row == 0){
			$insertsql = "INSERT INTO tbl_client(client_name, usr_username,usr_password, usr_firstname, usr_lastname, lto_no, area_id, address, contact_no, email, web_status, date_added, time_added, status) VALUES ('$name', '$username', '$password', '$firstname','$lastname', '$license', '$area', '$address', '$contact', '$email', '1', NOW(),NOW(),'1')";
			$result = mysqli_query($this->db,$insertsql) or die(mysqli_error() . "Cannot Insert Record");
			$data = "insert";
		}
		else{
			$updatesql = "UPDATE tbl_client SET client_name='$name', usr_username='$username', usr_password='$password', usr_firstname='$firstname', usr_lastname='$lastname', lto_no='$license', area_id='$area', address='$address', contact_no='$contact', email='$email', web_status=1, usr_id='$user_added', date_modified=NOW(),time_modified=NOW() WHERE lto_no='$license'";
			$result = mysqli_query($this->db,$updatesql) or die(mysqli_error() . "Cannot Update Record");
			$data = "update";
		}

		return $data;
	}

	public function delete_request($id){
		$sql = "DELETE FROM tbl_requests WHERE request_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Remove Record");
		return $result;
	}

	public function insert_decline_notif($client_id, $order_id){
		$title = "Declined Order";
		$subject = "Order Update";
		$message = "Your order with Order No.".$order_id." has been declined by Sungem Pharma. Thank you.";
		$sql = "INSERT INTO tbl_notifications(n_date_added, n_time_added, n_title, n_subject, n_message, client_id) VALUES(NOW(),NOW(),'$title','$subject','$message','$client_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Remove Record");
		return $result;
	}

	public function insert_approved_notif($client_id, $order_id){
		$title = "Approved Order";
		$subject = "Order Update";
		$message = "Your order with Order No.".$order_id." has been approved by Sungem Pharma. The order is being processed for delivery as of the moment. Thank you.";
		$sql = "INSERT INTO tbl_notifications(n_date_added, n_time_added, n_title, n_subject, n_message, client_id) VALUES(NOW(),NOW(),'$title','$subject','$message','$client_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Remove Record");
		return $result;
	}

	public function insert_delivery_notif($client_id, $order_id){
		$title = "On Delivery";
		$subject = "Order Update";
		$message = "A delivery record for your order with Order No.".$order_id." has been made by Sungem Pharma. The delivery is set to be delivered within 1-2 days. Thank you.";
		$sql = "INSERT INTO tbl_notifications(n_date_added, n_time_added, n_title, n_subject, n_message, client_id) VALUES(NOW(),NOW(),'$title','$subject','$message','$client_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Remove Record");
		return $result;
	}

	public function insert_delivery_sold_notif($client_id, $order_id, $delivery_id, $invoice_id){
		$title = "Delivery Received";
		$subject = "Considered Sold Order";
		$message = "Delivery No.".$delivery_id." for Order No.".$order_id." has been delivered successfully. An invoice with Invoice No.".$invoice_id." was sent to your account for payment transaction. Thank you.";
		$sql = "INSERT INTO tbl_notifications(n_date_added, n_time_added, n_title, n_subject, n_message, client_id) VALUES(NOW(),NOW(),'$title','$subject','$message','$client_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Remove Record");
		return $result;
	}
}