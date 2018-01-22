<?php
class Products{
	public $db;

	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function get_all_products(){
		$sql = "SELECT * FROM tbl_product WHERE status='1'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	
	public function get_product_details($id){
		$sql2 = "SELECT *, SUM(qty_total) as total_pending FROM tbl_order, tbl_orditem WHERE tbl_order.order_id=tbl_orditem.order_id AND tbl_order.status='0' AND pro_id='$id'";
		$result2 = mysqli_query($this->db,$sql2);
		$row2 = mysqli_fetch_assoc($result2);
		$value = $row2['total_pending'];
                

		$sql = "SELECT * FROM tbl_product WHERE pro_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$row["pending"]=$value;
		return $row;
	}

	public function get_product_formulation($id){
		$sql = "SELECT pro_formulation AS formu FROM tbl_product WHERE pro_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['formu'];
		return $value;
	}

	public function get_cat_info($id){
		$sql = "SELECT * FROM tbl_category WHERE cat_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_products_category($id){
		$sql = "SELECT * FROM tbl_product WHERE cat_id='$id' AND status='1'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_category_name($id){
		$sql = "SELECT cat_name FROM tbl_category WHERE cat_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['cat_name'];
		return $value;
	}

	public function get_category(){
		$sql = "SELECT * FROM tbl_category WHERE status='1'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_product_generic($id){
		$sql = "SELECT pro_generic FROM tbl_product WHERE pro_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['pro_generic'];
		return $value;
	}

	public function insert_to_cart($pro,$modal_qty,$id,$subtotal,$cl_id){
		$checksql = "SELECT * FROM tbl_cart WHERE pro_id='$pro' AND client_id='$cl_id'";
		$checkresult = mysqli_query($this->db,$checksql);
		$row1 = mysqli_fetch_assoc($checkresult);
		$cart_qty = $row1['c_qty'];
		if($cart_qty){
				$append_qty = $cart_qty + $modal_qty;
				$sql2 = "SELECT *, SUM(qty_total) as total_pending FROM tbl_order, tbl_orditem WHERE tbl_order.order_id=tbl_orditem.order_id AND tbl_order.status='0' AND pro_id='$pro'";
				$result2 = mysqli_query($this->db,$sql2);
				$row2 = mysqli_fetch_assoc($result2);
				$value2 = $row2['total_pending'];
										

				$sql3 = "SELECT * FROM tbl_product WHERE pro_id = '$pro'";
				$result3 = mysqli_query($this->db,$sql3);
				$row3 = mysqli_fetch_assoc($result3);
				$value_total = $row3['pro_total_qty'] - $value2;

				
					$update_query = "UPDATE tbl_cart SET c_qty = '$append_qty' WHERE pro_id='$pro' AND client_id='$cl_id'";
					$result = mysqli_query($this->db,$update_query) or die(mysqli_error() . "Cannot Update Data")	;

					
					$get_new_qty = "SELECT c_qty FROM tbl_cart WHERE pro_id='$pro' AND client_id='$cl_id'";
					$query = mysqli_query($this->db,$get_new_qty);
					$asd = mysqli_fetch_assoc($query);
					$kewk = $asd['c_qty'];

					$get_prod = "SELECT pro_unit_price FROM tbl_product WHERE pro_id = '$pro'";
					$get_prod_call = mysqli_query($this->db,$get_prod);
					$jkl = mysqli_fetch_assoc($get_prod_call);
					$op = $jkl['pro_unit_price'];

					$suma = $kewk * $op;

					$final_update = "UPDATE tbl_cart SET c_subtotal = '$suma' WHERE pro_id='$pro' AND client_id='$cl_id'";
					$qwerty = mysqli_query($this->db,$final_update) or die(mysqli_error(). "Cannot Update Data");

					return $qwerty;
					//return $result;
		}else{
			$insert_query = "INSERT INTO tbl_cart(pro_id,c_qty,usr_id,c_subtotal,client_id) VALUES('$pro','$modal_qty','$id','$subtotal','$cl_id')";
			$result = mysqli_query($this->db,$insert_query) or die(mysqli_error() . "Cannot Insert Data");
			return $result;
		}
	}

	public function remove_from_cart($id){
		$sql = "DELETE FROM tbl_cart WHERE c_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Delete Data");
		return $result;
	}

	public function remove_all_cart($id){
		$sql = "DELETE FROM tbl_cart WHERE usr_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Delete Data");
		return $result;
	}

	public function get_cart($id){
		$sql = "SELECT * FROM tbl_cart WHERE client_id='$id'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		if(!empty($list)){
			return $list;
		}
	}

	public function get_cart_total($id){
		$sql = "SELECT SUM(c_subtotal) AS cart_total_price FROM tbl_cart WHERE usr_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['cart_total_price'];
		return $value;
	}
	public function count_cart($id){
		$sql = "SELECT COUNT(usr_id) AS cart_total FROM tbl_cart WHERE usr_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['cart_total'];
		return $value;
	}
	public function count_qty_cart($id){
		$sql = "SELECT SUM(c_qty) AS cart_total FROM tbl_cart WHERE usr_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['cart_total'];
		return $value;
	}

	public function count_cart_hidden($id){
		$sql = "SELECT COUNT(usr_id) AS cart_total FROM tbl_cart WHERE usr_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['cart_total'];
		return $value;
	}

	public function get_product_brand($id){
		$sql = "SELECT pro_brand FROM tbl_product WHERE pro_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['pro_brand'];
		return $value;
	}

	public function get_product_packaging($id){
		$sql = "SELECT pro_packaging FROM tbl_product WHERE pro_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['pro_packaging'];
		return $value;
	}

	public function get_lot($id){
		$sql = "SELECT lot_number AS lot FROM tbl_lot WHERE pro_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['lot'];
		return $value;
	}
	public function get_ordtype(){
		$sql = "SELECT * from tbl_ordtype";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function get_price($id){
		$sql = "SELECT pro_unit_price FROM tbl_product WHERE pro_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['pro_unit_price'];
		return $value;
	}

	public function create_order_record($cl_id,$terms,$type,$usr_id){
		$sql = "INSERT INTO tbl_order(order_date,order_time,term_id,ordtype_id,client_id,usr_id) VALUES(NOW(),NOW(),'$terms','$type','$cl_id','$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $this->db->insert_id;
	}

	public function insert_orderitem($oid,$proid,$qty,$subtotal,$userid){
		$sql = "INSERT INTO tbl_orditem(order_id,pro_id,qty_total,subtotal,total,date_added,time_added,usr_id) VALUES('$oid','$proid','$qty','$subtotal','$subtotal',NOW(),NOW(),'$userid')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;

	}
	public function get_terms(){
		$sql = "SELECT * from tbl_payterm where term_name!='Consignment' OR term_id!='13'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function get_terms_consigned(){
		$sql = "SELECT * from tbl_payterm where term_name='Monthly' OR term_id='13'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
		return $list;
	}

	public function get_order_sum($id){
		$sql = "SELECT SUM(total) AS order_total FROM tbl_orditem WHERE order_id = '$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['order_total'];
		return $value;
	}

	public function update_order_amount($oid,$sum){
		$sql = "UPDATE tbl_order SET total_amount = '$sum' WHERE order_id = '$oid'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	//PENDING SANG KDA ROW KA PRODUCT
	public function get_restock_notif_list($pro_id){
		$sql = "SELECT *, SUM(qty_total) as total_pending FROM tbl_order, tbl_orditem WHERE tbl_order.order_id=tbl_orditem.order_id AND tbl_order.status='0' AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_pending'];
                
		return $value;
	}
}