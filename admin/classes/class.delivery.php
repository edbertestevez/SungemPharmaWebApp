<?php
class Delivery{
	public $db;
	
	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function get_delivery(){
		$sql = "SELECT * FROM tbl_delivery";
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

	public function get_search_delivery($client_id){
		$sql = "SELECT * FROM tbl_delivery, tbl_client, tbl_order, tbl_medrep WHERE tbl_delivery.order_id=tbl_order.order_id AND tbl_order.client_id = tbl_client.client_id AND tbl_medrep.medrep_id = tbl_client.medrep_id AND tbl_client.client_id='$client_id' ORDER BY tbl_delivery.delivery_id DESC";
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

	public function get_deliveryhistory($start, $end){
		$sql = "SELECT * FROM tbl_delivery, tbl_client, tbl_order, tbl_medrep WHERE tbl_delivery.order_id=tbl_order.order_id AND tbl_order.client_id = tbl_client.client_id AND tbl_medrep.medrep_id = tbl_client.medrep_id AND '$start'<=tbl_delivery.date_added AND '$end'>=tbl_delivery.date_added ORDER BY order_date DESC";
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


	public function get_selected_delivery($delivery_id){
		$sql = "SELECT tbl_order.order_id, tbl_delivery.medrep_id, tbl_medrep.medrep_id, tbl_delivery.order_id, tbl_client.client_name, tbl_client.client_id, tbl_order.client_id, date_delivered, time_delivered, or_number, tbl_ordtype.ordtype_id, ordtype_name, tbl_order.ordtype_id, mr_lastname, client_name FROM tbl_delivery, tbl_order, tbl_ordtype, tbl_client, tbl_medrep where delivery_id='$delivery_id'  AND tbl_delivery.order_id=tbl_order.order_id AND tbl_order.client_id=tbl_client.client_id AND tbl_order.ordtype_id=tbl_ordtype.ordtype_id";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function get_delivery_medrep($delivery_id){
		$sql = "SELECT medrep_id FROM tbl_delivery WHERE delivery_id='$delivery_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['medrep_id'];
	}

	public function get_delivery_total($delivery_id){
		$sql = "SELECT SUM(total) as sumtotal FROM tbl_delivery_items WHERE delivery_id='$delivery_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['sumtotal'];
	}

	//GET ORDER ID
	public function get_delivery_order($delivery_id){
		$sql = "SELECT order_id FROM tbl_delivery WHERE delivery_id='$delivery_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['order_id'];
	}

	public function get_pending_delivery(){
		$sql = "SELECT * FROM tbl_delivery WHERE delivery_status = '0'";
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

	public function get_pending_count(){
		$sql = "SELECT COUNT(*) AS total FROM tbl_delivery WHERE delivery_status='0'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['total'];
	}

	public function update_pending_delivery($delivery_id, $or_num){
		$sql = "UPDATE tbl_delivery SET delivery_status='1', date_delivered=NOW(), time_delivered=NOW(), or_number='$or_num' WHERE delivery_id='$delivery_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}
	
	public function add_delivery($order_id, $medrep_id, $usr_id){
		$sql = "INSERT INTO tbl_delivery(order_id, medrep_id, date_added, time_added, usr_id) VALUES ('$order_id', '$medrep_id', NOW(), NOW(), '$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $this->db->insert_id;
	}

	public function update_orditem_first_record($order_id, $ordstatus, $ordtype, $pro_id, $lot, $qty, $discount, $usr_id){
		$sql_price = "SELECT pro_unit_price from tbl_product WHERE pro_id='$pro_id'";
		$result_price = mysqli_query($this->db,$sql_price);
		$row = mysqli_fetch_assoc($result_price);

		//KWAON ANG RECENT TOTAL QTY KAG SAYLO SA GN APPEND KNG MAY ARA
		if($discount==0){
			$subtotal = $row['pro_unit_price'] * $qty;
			$total = $subtotal;
			$convert_discount=0;
		}else{
			$subtotal = $row['pro_unit_price'] * $qty;
			$convert_discount = ($row['pro_unit_price'] * $qty) * $discount/100;
			$total = ($row['pro_unit_price'] * $qty) - $convert_discount;
		}
		//KUNG CONSIDERED SOLD ANG ORDER NI PRE
			if($ordtype=='10'){
				$sql = "UPDATE tbl_orditem SET lot_id='$lot', qty_delivery=(qty_delivery+'$qty'), discount=(discount+'$convert_discount'), date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id', qty_sold='$qty' WHERE pro_id='$pro_id' AND order_id='$order_id'";
				$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
			}else{
				$sql = "UPDATE tbl_orditem SET lot_id='$lot', qty_delivery=(qty_delivery+'$qty'), discount=(discount+'$convert_discount'), date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id', qty_remaining='$qty' WHERE pro_id='$pro_id' AND order_id='$order_id'";
				$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
			}
		
		return $result;	
	}

	public function update_orditem_lot($order_id, $ordstatus, $ordtype, $pro_id, $lot, $qty, $discount, $usr_id){
		$sql_price = "SELECT pro_unit_price from tbl_product WHERE pro_id='$pro_id'";
		$result_price = mysqli_query($this->db,$sql_price);
		$row = mysqli_fetch_assoc($result_price);

		//KWAON ANG RECENT TOTAL QTY KAG SAYLO SA GN APPEND KNG MAY ARA
		if($discount==0){
			$subtotal = $row['pro_unit_price'] * $qty;
			$total = $subtotal;
			$convert_discount=0;
		}else{
			$subtotal = $row['pro_unit_price'] * $qty;
			$convert_discount = ($row['pro_unit_price'] * $qty) * $discount/100;
			$total = ($row['pro_unit_price'] * $qty) - $convert_discount;
		}
		if($ordstatus=='1'){
		//KUNG CONSIDERED SOLD ANG ORDER NI PRE
			if($ordtype=='10'){
				$sql = "UPDATE tbl_orditem SET lot_id='$lot', qty_delivery=(qty_delivery+'$qty'), discount=(discount+'$convert_discount'), date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id', qty_sold='$qty' WHERE pro_id='$pro_id' AND order_id='$order_id'";
			}else{
				$sql = "UPDATE tbl_orditem SET lot_id='$lot', qty_delivery=(qty_delivery+'$qty'), discount=(discount+'$convert_discount'), date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id', qty_remaining='$qty' WHERE pro_id='$pro_id' AND order_id='$order_id'";
			}
		}else if($ordstatus=='2'){
			//SUBTRACTAN ANG PREVIOUS NA GA HOLD
			$sqlPrev = "UPDATE tbl_orditem SET qty_total = (qty_total-'$qty') WHERE order_id='$order_id' AND pro_id='$pro_id' AND qty_total>qty_delivery";
			$resultPrev = mysqli_query($this->db,$sqlPrev) or die(mysqli_error() . "Cannot Update Record");
			
			//ADD SA TOTAL KAG DELIVERY SANG GN IBAN
			if($ordtype=='10'){
				$sql = "UPDATE tbl_orditem SET qty_total=(qty_total+'$qty'), qty_delivery=(qty_delivery+'$qty'), discount=(discount+'$convert_discount'), date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id', qty_sold=(qty_sold+'$qty') WHERE pro_id='$pro_id' AND order_id='$order_id' AND lot_id='$lot'";
			}else{
				$sql = "UPDATE tbl_orditem SET qty_total=(qty_total+'$qty'),qty_delivery=(qty_delivery+'$qty'), discount=(discount+'$convert_discount'), date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id', qty_remaining=(qty_remaining+'$qty') WHERE pro_id='$pro_id' AND order_id='$order_id' AND lot_id='$lot'";
			}
		}

		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;

	}

	public function update_order_status($order_id, $usr_id){
		$sql = "UPDATE tbl_order SET status='1', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	public function update_ordstatus_two($order_id, $usr_id){
		$sql = "UPDATE tbl_order SET status='2', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	//MEANING DELIVERED NA NI PRE. . . UPDATE STATUS NA COMPLETE NA TNAAN NYA NA ORDER
	public function update_order_delivered($order_id, $usr_id){
		$sql = "UPDATE tbl_order SET status='3', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	public function append_orditem($order_id,  $ordtype, $product, $lot, $append_total, $qty, $discount, $usr_id){
		$sql_price = "SELECT pro_unit_price from tbl_product WHERE pro_id='$product'";
		$result_price = mysqli_query($this->db,$sql_price);
		$row = mysqli_fetch_assoc($result_price);
		if($discount==0){
			$subtotal = $row['pro_unit_price'] * $qty;
			$total=$subtotal;
		}else{
			$subtotal = $row['pro_unit_price'] * $qty;
			$convert_discount = ($row['pro_unit_price'] * $qty) * $discount/100;
			$total = ($row['pro_unit_price'] * $qty) - $convert_discount;
		}

		if($ordtype=='10'){
			$sql = "INSERT into tbl_orditem(pro_id, lot_id, order_id, qty_total, qty_delivery, qty_sold, subtotal, discount, total, date_added, time_added, usr_id) VALUES ('$product', '$lot', '$order_id', '$append_total', '$qty', '$qty', '$subtotal', '$convert_discount', '$total', NOW(), NOW(), '$usr_id')";
		}else{
			$sql = "INSERT into tbl_orditem(pro_id, lot_id, order_id, qty_total, qty_delivery, qty_remaining, subtotal, discount, total, date_added, time_added, usr_id) VALUES ('$product', '$lot', '$order_id', '$append_total', '$qty', '$qty', '$subtotal', '$convert_discount', '$total', NOW(), NOW(), '$usr_id')";
		}
		
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}

	public function get_delivered_orditems($delivery_id){
		$sql = "SELECT *, tbl_delivery_items.pro_id as product_id, tbl_delivery_items.quantity AS qty_delivered, pro_brand, pro_generic, pro_packaging, pro_unit_price from tbl_delivery_items, tbl_product, tbl_lot WHERE tbl_delivery_items.delivery_id='$delivery_id' AND tbl_product.pro_id=tbl_delivery_items.pro_id AND tbl_lot.lot_id=tbl_delivery_items.lot_id";
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

	/*SAMPLE LANG NI GN UPDATE KO LANG ANG PREVIOUS RECORD
	public function update_delivered(){
		$sql = "SELECT * FROM tbl_delivery WHERE delivery_status=1";
		$result = mysqli_query($this->db,$sql);
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$ordid = $row['order_id'];
				$sqlUpdate = "UPDATE tbl_order SET status='2' WHERE order_id = '$ordid'";
				$resultUpdate = mysqli_query($this->db,$sqlUpdate) or die(mysqli_error() . "Cannot Update Data");
			}
        }else{
            $list=false;
        }

		
		return $result;
	}	
	*/

	//DELIVERY ITEMS
	public function insert_delivery_item($delivery_id,$pro_id,$lot_id,$unit_price,$qty_delivery,$discount){
		if($discount==0){
			$subtotal = $unit_price * $qty_delivery;
			$total = $subtotal;
			$convert_discount=0;
		}else{
			$subtotal = $unit_price * $qty_delivery;
			$convert_discount = ($unit_price * $qty_delivery) * $discount/100;
			$total = ($unit_price * $qty_delivery) - $convert_discount;
		}

		$sql = "INSERT INTO tbl_delivery_items(delivery_id,pro_id,lot_id,unit_price,quantity,subtotal,discount,total,date_added,time_added) VALUES('$delivery_id','$pro_id','$lot_id','$unit_price','$qty_delivery','$subtotal','$convert_discount','$total',NOW(),NOW())";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}
	/*
	public function insert_delivery_item($delivery_id,$pro_id,$lot_id,$unit_price,$qty_delivery,$subtotal,$discount,$total){
		$sql = "INSERT INTO tbl_delivery_items(delivery_id,pro_id,lot_id,unit_price,quantity,subtotal,discount,total,date_added,time_added) VALUES('$delivery_id','$pro_id','$lot_id','$unit_price','$qty_delivery','$subtotal','$discount','$total','$date_added','$date_added')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}*/
}