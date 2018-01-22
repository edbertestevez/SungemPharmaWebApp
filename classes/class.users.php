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
		$sql = "SELECT * FROM tbl_users";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}
	
	public function get_area(){
		$sql = "SELECT * FROM tbl_area";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_paylist($id){
		$sql = "SELECT *, tbl_invoice.total_amount FROM tbl_invoice,tbl_ord_invoice,tbl_order WHERE tbl_invoice.invoice_id = tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id = tbl_order.order_id AND tbl_order.client_id = '$id' AND tbl_invoice.amount_remaining > 0";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_order_history($id){
		$sql = "SELECT * FROM tbl_order WHERE client_id = '$id' ORDER BY order_date DESC";
		$result = mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_array($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	/*
	 select *, sum(tbl_delivery_items.quantity) as chuchu from tbl_delivery_items, tbl_delivery, tbl_orders WHERE tbl_delivery_items.delivery_id=tbl_delivery.delivery_id AND tbl_delivery.order_id=tbl_delivery.order_id  AND tbl_delivery.delivery_status='1' GROUP BY tbl_delivery_items.pro_id LIMIT 5

	*/
	public function get_top_consigned($id){
		$sql = "SELECT *, SUM(tbl_delivery_items.quantity) as chuchu from tbl_delivery_items, tbl_delivery, tbl_order, tbl_product WHERE tbl_delivery_items.delivery_id=tbl_delivery.delivery_id AND tbl_delivery.order_id=tbl_delivery.order_id AND tbl_delivery_items.pro_id = tbl_product.pro_id AND tbl_delivery.delivery_status='1' AND tbl_order.ordtype_id = 11 AND tbl_order.client_id = '$id' GROUP BY tbl_delivery_items.pro_id ORDER BY chuchu DESC LIMIT 5";
		$result = mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_array($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}
	public function get_top_considered($id){
		$sql = "SELECT *, SUM(tbl_delivery_items.quantity) as chuchu from tbl_delivery_items, tbl_delivery, tbl_order, tbl_product WHERE tbl_delivery_items.delivery_id=tbl_delivery.delivery_id AND tbl_delivery.order_id=tbl_delivery.order_id AND tbl_delivery_items.pro_id = tbl_product.pro_id AND tbl_delivery.delivery_status='1' AND tbl_order.ordtype_id = 10 AND tbl_order.client_id = '$id' GROUP BY tbl_delivery_items.pro_id ORDER BY chuchu DESC LIMIT 5";
		$result = mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_array($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_pending_orders($id){
		$sql = "SELECT COUNT(order_id) AS total FROM tbl_order WHERE status = 0 AND client_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total'];
		return $value;
	}
	public function get_approved_orders($id){
		$sql = "SELECT COUNT(order_id) AS total FROM tbl_order WHERE status = 1 AND client_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total'];
		return $value;
	}
	/*
	OLD PENDING ORDERS SQL
	public function get_pending_orders($id){
		$sql = "SELECT COUNT(tbl_order.order_id) AS total FROM tbl_order,tbl_delivery WHERE tbl_order.order_id = tbl_delivery.order_id AND tbl_order.deliver_status = 0 AND client_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total'];
		return $value;
	}*/

	public function get_total_orders($id){
		$sql = "SELECT COUNT(tbl_order.order_id) AS total FROM tbl_order WHERE client_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total'];
		return $value;
	}

	public function get_consigned_meds($id){
		$sql = "SELECT SUM(tbl_orditem.qty_remaining) AS total FROM tbl_orditem, tbl_order, tbl_delivery WHERE tbl_orditem.order_id=tbl_order.order_id AND tbl_order.client_id = '$id' AND tbl_delivery.order_id=tbl_order.order_id AND ordtype_id=11 AND tbl_delivery.delivery_status=1";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total'];
		return $value;
	}

	public function get_unpaid_invoice($id){
		$sql = "SELECT COUNT(tbl_ord_invoice.order_id) AS total FROM tbl_ord_invoice,tbl_order,tbl_invoice WHERE client_id = '$id' AND tbl_ord_invoice.order_id = tbl_order.order_id AND tbl_ord_invoice.invoice_id = tbl_invoice.invoice_id AND amount_remaining > 0";

		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total'];
		return $value;
	}
	public function get_order_details($id){
		$sql = "SELECT *, pro_brand, pro_generic, pro_formulation, pro_packaging FROM tbl_orditem,tbl_product WHERE tbl_orditem.pro_id = tbl_product.pro_id AND order_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_array($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}
	public function get_order_total($id){
		$sql = "SELECT SUM(total) AS total_price FROM tbl_orditem WHERE order_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_price'];
		return $value;
	}
	public function get_order_delivery_status($id){
		$sql = "SELECT status FROM tbl_order WHERE order_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['status'];
		return $value;
	}
	public function get_order_payment_status($id){
		$sql = "SELECT SUM(total) AS total_price FROM tbl_orditem WHERE order_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_price'];
		return $value;
	}
	public function get_client($id){
		$sql = "SELECT client_id FROM tbl_client WHERE usr_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['client_id'];
		return $value;
	}
	
	public function get_profile_info($id){
		$sql = "SELECT * from tbl_client where client_id='$id'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_password($usr_id){
		$sql = "SELECT usr_password FROM tbl_client where client_id = '$usr_id'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_notif_data($id,$cid){
		$sql = "SELECT * FROM tbl_notifications WHERE n_id='$id' AND client_id='$cid'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_array($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function delete_notif($id,$cid){
		$sql = "DELETE FROM tbl_notifications WHERE n_id='$id' AND client_id='$cid'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Delete Data");
		return $result;
	}

	public function clear_notif($cid){
		$sql = "DELETE FROM tbl_notifications WHERE client_id='$cid'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Delete Data");
		return $result;
	}

	public function mark_read_notif($id,$cid){
		$sql = "UPDATE tbl_notifications SET n_status = 1 WHERE client_id='$cid' AND n_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_errno() . "Cannot Update Data");
		return $result;
	}
	public function mark_unread_notif($id,$cid){
		$sql = "UPDATE tbl_notifications SET n_status = 0 WHERE client_id='$cid' AND n_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_errno() . "Cannot Update Data");
		return $result;
	}

	public function get_notif_count($id){
		$sql = "SELECT COUNT(n_status) AS total_unread FROM tbl_notifications WHERE n_status = 0 AND client_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_unread'];
		return $value;
	}
	public function new_user_request($LtoNumber, $CompanyName, $Address, $ContactNumber, $EmailAddress, $Username, $Password, $areaName, $FirstName, $LastName){
	$sql = "SELECT * FROM tbl_client WHERE lto_no = '$LtoNumber'";
	$check=$this->db->query($sql);
	$count_row = $check->num_rows;
		if($count_row == 0){
			$newpassword = md5($Password);
			$sql = "INSERT INTO tbl_requests(lto_no, name, address, contact_no, email, area_id, username, password, date_requested, time_requested, usr_FName, usr_LName)
			VALUES('$LtoNumber', '$CompanyName', '$Address', '$ContactNumber', '$EmailAddress', '$areaName', '$Username', '$newpassword', NOW(), NOW(), '$FirstName', '$LastName')";

			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
			return $result;
		}else{
			return false;
		}
	}
	public function get_notifications($id){
		$sql = "SELECT * FROM tbl_notifications WHERE client_id='$id' ORDER BY n_id DESC";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_array($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function check_login($username,$password){
		$sql = "SELECT * FROM tbl_client WHERE
		usr_username='$username' AND usr_password='$password'";
		$result=mysqli_query($this->db,$sql);
		$userdata=mysqli_fetch_array($result);
		$count = $result->num_rows;
		if($count == 1){
			$_SESSION['c_login']=true;
            $_SESSION['c_userid']=$userdata['client_id'];
            $_SESSION['c_username']=$userdata['usr_username'];
            $_SESSION['c_userfullname']=$userdata['usr_firstname'] . " " . $userdata['usr_lastname'];
						$_SESSION['clientid']=$userdata['client_id'];
						
						//$uid = $_SESSION['c_userid'];
            //$sql2 = "SELECT client_id FROM tbl_client WHERE usr_id = '$uid'";
            //$result2 = mysqli_query($this->db,$sql2);
            //$value = mysqli_fetch_assoc($result2);
            //$_SESSION['clientid'] = $value['client_id'];
			return true;
		}
		else{
			return false;
		}
	}


	public function get_session(){
		if(isset($_SESSION['c_login']) && $_SESSION['c_login'] == true){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function edit_prof($name, $address, $contact, $email, $usr_id){
		$sql = "UPDATE tbl_client SET client_name='$name', address='$address', contact_no='$contact', email='$email' WHERE client_id='$usr_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	
	}


	
	public function new_pssword($new_confirmed_pass, $usr_id){
		$sql = "UPDATE tbl_client SET usr_password='$new_confirmed_pass' WHERE client_id='$usr_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $results;	
	}

	public function get_sli($id){
		$sql = "SELECT tbl_orditem.qty_remaining AS quantity,tbl_lot.expiry_date AS expiry,tbl_lot.lot_number AS lotno, tbl_product.pro_brand AS brand,tbl_product.pro_generic AS generic,tbl_product.pro_formulation AS formu,tbl_product.pro_packaging AS pack,tbl_orditem.order_id FROM tbl_lot,tbl_product,tbl_order,tbl_orditem,tbl_delivery WHERE tbl_order.order_id = tbl_orditem.order_id AND tbl_product.pro_id = tbl_orditem.pro_id AND tbl_order.client_id = '$id' AND tbl_delivery.order_id = tbl_order.order_id AND delivery_status = 1 AND tbl_orditem.qty_remaining > 0 AND tbl_lot.lot_id = tbl_orditem.lot_id AND tbl_order.ordtype_id = 11";
		$result = mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_array($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}
	public function get_consideredsold($id){
		$sql = "SELECT *, SUM(tbl_delivery_items.quantity) as chuchu from tbl_delivery_items, tbl_delivery, tbl_order, tbl_product WHERE tbl_delivery_items.delivery_id=tbl_delivery.delivery_id AND tbl_delivery.order_id=tbl_order.order_id AND tbl_delivery_items.pro_id = tbl_product.pro_id AND tbl_delivery.delivery_status='1' AND tbl_order.ordtype_id = 10 AND tbl_order.client_id = '$id' GROUP BY tbl_delivery_items.pro_id ORDER BY chuchu DESC";
		$result = mysqli_query($this->db,$sql);
		while($row=mysqli_fetch_array($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}
	public function get_total_balance($id){
		$sql = "SELECT SUM(tbl_invoice.total_amount) AS total_balance FROM tbl_invoice,tbl_ord_invoice,tbl_order WHERE tbl_invoice.invoice_id = tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id = tbl_order.order_id AND tbl_order.client_id = '$id' AND tbl_invoice.amount_remaining > 0";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_balance'];
		return $value;
	}

	public function convert_delivery_status($id){
		$ostatus0 = "Pending";
		$ostatus1 = "Approved";
		$ostatus2 = "Ongoing";
		$ostatus3 = "Delivered";
		$ostatus4 = "Cancelled";
		$ostatus5 = "Declined";
	
		switch($id){
			case '0':
				$value = $ostatus0;
				break;
			case '1':
				$value = $ostatus1;
				break;
			case '2':
				$value = $ostatus2;
				break;
			case '3':
				$value = $ostatus3;
				break;
			case '4':
				$value = $ostatus4;
				break;
			case '5':
				$value = $ostatus5;
				break;
		}
		return $value;
	}

	public function receive_order_notif($id){
		$sql = "INSERT INTO tbl_notifications(n_date_added,n_time_added,n_title,n_subject,n_message,n_status,client_id) VALUES(NOW(),NOW(),'Order Received','Pending','Your order has been placed. We will notify you once your order has been approved. Thank you for shopping at Sungem Pharma.','0','$id')"; 
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}
	public function get_order_by_invoice($id){
		$sql = "SELECT order_id FROM tbl_ord_invoice WHERE invoice_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['order_id'];
		return $value;
	}
	public function get_order_date($id){
		$sql = "SELECT order_date FROM tbl_order WHERE order_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['order_date'];
		return $value;
	}

	public function cancel_order($id){
		$sql = "DELETE FROM tbl_order WHERE order_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Delete Data");
		return $result;
	}
}

