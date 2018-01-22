<?php
class Orders{
	public $db;
	
	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}
	
	public function get_all_orders(){
		$sql = "SELECT * FROM tbl_order";
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



	public function get_search_order($client_id){
		$sql = "SELECT *, client_name, term_name, ordtype_name from tbl_order, tbl_client, tbl_payterm, tbl_ordtype where tbl_order.client_id=tbl_client.client_id AND tbl_order.term_id=tbl_payterm.term_id AND tbl_order.ordtype_id=tbl_ordtype.ordtype_id AND tbl_client.client_id='$client_id'  ORDER BY tbl_order.order_id DESC";
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

	public function get_pending_orders(){
		$sql = "SELECT *, client_name, term_name, ordtype_name from tbl_order, tbl_client, tbl_payterm, tbl_ordtype where tbl_order.client_id=tbl_client.client_id AND tbl_order.term_id=tbl_payterm.term_id AND tbl_order.ordtype_id=tbl_ordtype.ordtype_id AND tbl_order.status='0' ORDER BY order_date";
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

	public function get_approved_orders(){
		//BOTH ANG APPROVED NI KAG ANG MAAY PENDING PA NA PARTIAL DELIVERY
		$sql = "SELECT *, client_name, term_name, ordtype_name from tbl_order, tbl_client, tbl_payterm, tbl_ordtype where tbl_order.client_id=tbl_client.client_id AND tbl_order.term_id=tbl_payterm.term_id AND tbl_order.ordtype_id=tbl_ordtype.ordtype_id AND (tbl_order.status='1' OR tbl_order.status='2') ORDER BY order_date";
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

	public function get_specific_order($id){
		$sql = "SELECT *, tbl_order.order_id AS ordid, client_name, term_name, ordtype_name from tbl_order, tbl_client, tbl_payterm, tbl_ordtype where tbl_order.client_id=tbl_client.client_id AND tbl_order.term_id=tbl_payterm.term_id AND tbl_order.ordtype_id=tbl_ordtype.ordtype_id AND tbl_order.order_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return json_encode($row);
	}

	public function get_order_row($id){
		$sql = "SELECT *, tbl_order.order_id AS ordid, client_name, term_name, ordtype_name from tbl_order, tbl_client, tbl_payterm, tbl_ordtype where tbl_order.client_id=tbl_client.client_id AND tbl_order.term_id=tbl_payterm.term_id AND tbl_order.ordtype_id=tbl_ordtype.ordtype_id AND tbl_order.order_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function get_order_ordtype($id){
		$sql = "SELECT * from tbl_ordtype, tbl_order where tbl_order.ordtype_id=tbl_ordtype.ordtype_id AND order_id ='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['ordtype_id'];
		return $value;
	}

	public function check_orders_complete($order_id){
		$sql = "SELECT COUNT(*) AS totalcheck from tbl_orditem WHERE qty_total>qty_delivery AND order_id='$order_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['totalcheck'];
		return $value;
	}

	public function check_delivery_pending($order_id){
		$sql = "SELECT COUNT(*) AS totalcheck from tbl_delivery WHERE delivery_status=0 AND order_id='$order_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['totalcheck'];
		return $value;
	}
	

	public function get_orderhistory($start, $end){
		$sql = "SELECT *,  tbl_order.order_id AS ordid, client_name, term_name, ordtype_name, tbl_order.status AS stat from tbl_order, tbl_client, tbl_payterm, tbl_ordtype where tbl_order.client_id=tbl_client.client_id AND tbl_order.term_id=tbl_payterm.term_id AND tbl_order.ordtype_id=tbl_ordtype.ordtype_id AND '$start'<=order_date AND '$end'>=order_date ORDER BY ordid DESC";
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

	public function get_terms(){
		//13 ang ID ka consignment
		$sql = "SELECT * from tbl_payterm where term_id!='13'";
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

	public function get_terms_consigned(){
		$sql = "SELECT * from tbl_payterm where term_name='Monthly' OR term_id='13'";
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

	public function get_order_total($id){
		$sql = "SELECT total_amount from tbl_order where order_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_amount'];
		return $value;
	}

	public function get_order_term($id){
		$sql = "SELECT term_id from tbl_order where order_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['term_id'];
		return $value;
	}

	public function get_order_termname($id){
		$sql = "SELECT * from tbl_payterm, tbl_order where tbl_order.term_id=tbl_payterm.term_id AND order_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['term_name'];
		return $value;
	}

	public function get_ordtype(){
		$sql = "SELECT * from tbl_ordtype";
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

	public function get_orditems_partial(){
		$sql = "SELECT order_id FROM tbl_orditem WHERE qty_total>qty_delivery AND qty_delivery!='0' GROUP BY order_id";
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

	public function update_partial($order_id){
		$sql = "UPDATE tbl_order SET status='2' WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $this->db->insert_id;
	}
	public function update_orditem_total($orditem_id,$unit_price){
		$sql = "UPDATE tbl_orditem SET subtotal=($unit_price * qty_total), total=($unit_price * qty_total)-discount WHERE orditem_id='$orditem_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}



	public function get_orditems($id){
		$sql = "SELECT *, tbl_orditem.overall as sumtotal, tbl_orditem.sumdelivery AS sumdelivery, tbl_orditem.sumdiscount as sumdiscount, tbl_orditem.pro_id as product_id, pro_brand, pro_generic, pro_packaging, pro_unit_price FROM (SELECT *, SUM(qty_total) as overall, SUM(qty_delivery) AS sumdelivery, SUM(discount) AS sumdiscount FROM tbl_orditem WHERE tbl_orditem.order_id='$id' GROUP BY tbl_orditem.pro_id)tbl_orditem, tbl_product WHERE tbl_product.pro_id=tbl_orditem.pro_id AND tbl_orditem.order_id='$id'";
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

	public function get_orditems_sum($id){
		$sql = "SELECT sum(total) as total from tbl_orditem where order_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['total'];
	}

	public function get_order_status($id){
		$sql = "SELECT status from tbl_order where order_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['status'];
	}

	public function check_approved_undelivered($order_id){
		$sql = "SELECT SUM(qty_total) as sumtotal, SUM(qty_delivery) AS sumdelivery FROM tbl_orditem WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		if(($row['sumtotal']-$row['sumdelivery'])>0){
			return true;
		}else{
			return false;
		}
	}


	public function new_order($client, $type, $terms){
		$sql = "INSERT into tbl_order(term_id, ordtype_id, client_id, order_date, order_time) VALUES ('$terms', '$type', '$client', NOW(), NOW())";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $this->db->insert_id;
	}

	public function add_orditem($order_id, $product, $quantity){
		$sql_price = "SELECT pro_unit_price from tbl_product WHERE pro_id='$product'";
		$result_price = mysqli_query($this->db,$sql_price);
		$row = mysqli_fetch_assoc($result_price);
		$subtotal = $row['pro_unit_price'] * $quantity;

		$sql = "INSERT into tbl_orditem(pro_id, order_id, qty_total, total, subtotal, date_added, time_added) VALUES ('$product', '$order_id', '$quantity', '$subtotal', '$subtotal', NOW(), NOW())";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}

	public function update_total($order_id){
		$sql_total = "SELECT SUM(total) as total_sum FROM tbl_orditem WHERE order_id='$order_id'";
		$result_total = mysqli_query($this->db,$sql_total);
		$row = mysqli_fetch_assoc($result_total);
		$total = $row['total_sum'];

		$sql = "UPDATE tbl_order SET total_amount='$total' WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}

	public function cancel_order($id, $usr_id){
		$sql = "UPDATE tbl_order SET status='4', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' where order_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function decline_order($id, $usr_id){
		$sql = "UPDATE tbl_order SET status='5', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' where order_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function approve_order($id, $usr_id){
		$sql = "UPDATE tbl_order SET status='1', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' where order_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function complete_order($id, $usr_id){
		$sql = "UPDATE tbl_order SET status='3', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' where order_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function remove_orditems($id){
		$sql = "DELETE FROM tbl_orditem where order_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function get_pending_ordnums(){
		$sql = "SELECT order_id, client_name FROM tbl_order, tbl_client WHERE tbl_order.client_id = tbl_client.client_id AND tbl_order.status = '0'";
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
		$sql = "SELECT COUNT(*) AS total FROM tbl_order WHERE status='0'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['total'];
	}

	//ORDER ITEM UPDATING SA DELIVERY
	public function get_previous_orditem_total($order_id,$pro_id){
		$sql = "SELECT qty_total FROM tbl_orditem WHERE qty_total>qty_delivery AND lot_id!='0' AND order_id='$order_id' AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['qty_total'];
	}

	public function get_previous_orditem_lot($order_id,$pro_id){
		$sql = "SELECT lot_id FROM tbl_orditem WHERE qty_total>qty_delivery AND lot_id!='0' AND order_id='$order_id' AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['lot_id'];
	}

	public function count_orditem_lot($order_id,$pro_id,$lot_id){
		$sql = "SELECT COUNT(*) AS total_count FROM tbl_orditem WHERE lot_id='$lot_id' AND order_id='$order_id' AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['total_count'];
	}

	public function count_orditem_with_lot_record($order_id,$pro_id){
		$sql = "SELECT COUNT(*) AS total_count FROM tbl_orditem WHERE lot_id!='0' AND order_id='$order_id' AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['total_count'];
	}
 	
 	public function update_previous_orditem_total($order_id,$pro_id,$lot_id,$discount){
 		$sql1 = "SELECT qty_delivery, pro_unit_price FROM tbl_orditem,tbl_product WHERE order_id='$order_id' AND tbl_product.pro_id='$pro_id' AND tbl_product.pro_id=tbl_orditem.pro_id AND lot_id='$lot_id'";
		$result1 = mysqli_query($this->db,$sql1);
		$row1 = mysqli_fetch_assoc($result1);
		$delivered = $row1['qty_delivery'];
		$unit_price = $row1['pro_unit_price'];

		if($discount==0){
			$subtotal = $unit_price * $delivered;
			$total=$subtotal;
		}else{
			$subtotal = $unit_price * $delivered;
			$convert_discount = ($unit_price * $delivered) * $discount/100;
			$total = ($unit_price * $delivered) - $convert_discount;
		}

		$sql = "UPDATE tbl_orditem SET qty_total = qty_delivery, subtotal='$subtotal', total='$total', discount=discount+'$convert_discount' WHERE order_id='$order_id' AND pro_id='$pro_id' AND lot_id='$lot_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function get_previous_new_delivery($order_id,$pro_id,$lot_id){
		$sql = "SELECT qty_delivery FROM tbl_orditem WHERE lot_id='$lot_id' AND order_id='$order_id' AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['qty_delivery'];
	}
	//END ORAYT
	public function get_order_medrep($ordid){
		$sql = "SELECT tbl_client.medrep_id as med_id FROM tbl_client, tbl_order WHERE tbl_order.client_id=tbl_client.client_id AND tbl_order.order_id='$ordid'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['med_id'];
		return $value;
	}

	public function get_client_medrep($client_id){
		$sql = "SELECT medrep_id FROM tbl_client WHERE client_id='$client_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['medrep_id'];
		return $value;
	}

	public function get_order_medrep_name($ordid){
		$sql = "SELECT mr_firstname, mr_lastname FROM tbl_medrep, tbl_client, tbl_order WHERE tbl_order.client_id=tbl_client.client_id AND tbl_order.order_id='$ordid' AND tbl_client.medrep_id=tbl_medrep.medrep_id";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['mr_firstname']." ".$row['mr_lastname'];
		return $value;
	}

	public function get_order_clientname($ordid){
		$sql = "SELECT client_name FROM tbl_client, tbl_order WHERE tbl_order.client_id=tbl_client.client_id AND tbl_order.order_id='$ordid'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['client_name'];
		return $value;
	}	

	public function get_order_client_id($ordid){
		$sql = "SELECT client_id FROM  tbl_order WHERE order_id='$ordid'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['client_id'];
		return $value;
	}	

	public function get_orditem_id($order_id, $lot_id){
		$sql = "SELECT orditem_id FROM tbl_orditem WHERE order_id='$order_id' AND lot_id='$lot_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['orditem_id'];
		return $value;
	}

	public function get_client_delivered_sold_orders($client_id){
		$sql = "SELECT * FROM tbl_order, tbl_client, tbl_delivery where tbl_client.client_id = tbl_order.client_id AND tbl_order.ordtype_id='10' AND tbl_delivery.order_id=tbl_order.order_id AND delivery_status='1' AND tbl_order.client_id='$client_id'";
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

	public function get_delivered_consigned_remain_orders(){
		$sql = "SELECT * FROM tbl_order, tbl_client, tbl_delivery where tbl_client.client_id = tbl_order.client_id AND tbl_order.status !='0' AND tbl_order.ordtype_id='11' AND tbl_delivery.order_id=tbl_order.order_id AND delivery_status='1'";
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

	public function get_orditems_withdraw($id){
		$sql = "SELECT *, tbl_orditem.pro_id as product_id, pro_brand, pro_generic, pro_packaging, pro_unit_price from tbl_orditem, tbl_product, tbl_lot WHERE tbl_product.pro_id=tbl_orditem.pro_id AND tbl_orditem.lot_id=tbl_lot.lot_id AND tbl_orditem.order_id='$id'";
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

	public function insert_sold_withdrawal($order_id, $usr_id){
		$sqlget = "SELECT client_id FROM tbl_order WHERE order_id='$order_id'";
		$resultget = mysqli_query($this->db,$sqlget);
		$rowget = mysqli_fetch_assoc($resultget);
		$client_id = $rowget['client_id'];

		$sql = "INSERT INTO tbl_withdrawal(description, client_id, date_added, time_added, usr_id) VALUES('Damaged', '$client_id', NOW(), NOW(), '$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Record");
		return $this->db->insert_id;
	}

	public function insert_sold_withdraw_items($withdraw_id, $orditem_id, $pro_id, $lot_id, $qty, $usr_id){
		$sql = "INSERT INTO tbl_withdraw_lot(withdraw_id, orditem_id, pro_id, lot_id, quantity, date_added, time_added, usr_id) VALUES('$withdraw_id', '$orditem_id', '$pro_id','$lot_id','$qty',NOW(),NOW(),'$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Record");
		return $result;
	}

	/*public function update_withdrawn_orderitem($order_id,$lot_id,$unit_price,$qty,$usr_id){
		$sqlget = "SELECT subtotal, qty_sold, discount FROM tbl_orditem WHERE order_id='$order_id' AND lot_id='$lot_id'";
		$resultget = mysqli_query($this->db,$sqlget);
		$rowget = mysqli_fetch_assoc($resultget);
		$prev_subtotal = $rowget['subtotal'];
		$prev_sold = $rowget['qty_sold'];
		$prev_discount = $rowget['discount'];

		if($prev_discount!=0){
			$val_discount = $prev_discount/$prev_subtotal;
			$new_discount = ($prev_sold - $qty)*$unit_price*$val_discount;
			$new_subtotal = ($prev_sold - $qty)*$unit_price;
			$new_total = (($prev_sold - $qty)*$unit_price)-($new_discount);
		}else{
			$new_subtotal = ($prev_sold - $qty)*$unit_price;
			$new_discount = 0;
			$new_total = $new_subtotal;
		}

		$sql = "UPDATE tbl_orditem SET qty_sold=qty_sold-'$qty', qty_withdrawn=qty_withdrawn+'$qty', subtotal='$new_subtotal', discount='$new_discount', total='$new_total', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE order_id='$order_id' AND lot_id='$lot_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}
	*/

	//UPDATE NIYA LANG NA DI ANG TOTAL_SOLD QUANTITY SA ORDER ITEM BUT ANG VALUE SANG ILA NGA ORDR KAG ORDER ID NGA VALUE INDI LANG PAG ISLAN SINCE PARA INDI MAG GUBA ANG RECORD... SAME LANG ANG ORDER VALUES KAG CONTENT KAG SA INVOICE LANG MA UPDATE SANG UNOD KA IYA NGA BALAYRAN. SINCE 1 TIME KA MAN LANG PWEDE KA WITHDRAW KAY CONSIDERED NGA ILA NA NA KA CLIENT ANG ORDER KUNG RECEIVED NA KAG WALA KA WITHDRAW
	public function update_withdrawn_orderitem($order_id,$lot_id,$unit_price,$qty,$usr_id){
		$sql = "UPDATE tbl_orditem SET qty_sold=qty_sold-'$qty', qty_withdrawn=qty_withdrawn+'$qty', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE order_id='$order_id' AND lot_id='$lot_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function update_withdrawn_invoiceitem($invoice_id,$lot_id,$unit_price,$qty,$usr_id){
		$sqlget = "SELECT * FROM tbl_invoice_item WHERE invoice_id='$invoice_id' AND lot_id='$lot_id'";
		$resultget = mysqli_query($this->db,$sqlget);
		$rowget = mysqli_fetch_assoc($resultget);
		$prev_subtotal = $rowget['subtotal'];
		$prev_qty = $rowget['quantity'];
		$prev_discount = $rowget['discount'];

		if($prev_discount!=0){
			$val_discount = $prev_discount/$prev_subtotal;
			$new_discount = ($prev_qty - $qty)*$unit_price*$val_discount;
			$new_subtotal = ($prev_qty - $qty)*$unit_price;
			$new_total = (($prev_qty - $qty)*$unit_price)-($new_discount);
		}else{
			$new_subtotal = ($prev_qty - $qty)*$unit_price;
			$new_discount = 0;
			$new_total = $new_subtotal;
		}

		$sql = "UPDATE tbl_invoice_item SET quantity=quantity-'$qty', subtotal='$new_subtotal', discount='$new_discount', total='$new_total', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE invoice_id='$invoice_id' AND lot_id='$lot_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function get_corresponding_invoice($order_id){
		//ONLY WORKS FOR CONSIDERED SOLD ORDERS 1 is to 1 RELATIONSHIP SILA PRE
		$sql = "SELECT invoice_id FROM tbl_ord_invoice WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['invoice_id'];
		return $value;
	}	

	public function update_invoice_total($invoice_id){
		$sql_total = "SELECT SUM(total) as total_sum FROM tbl_invoice_item WHERE invoice_id='$invoice_id'";
		$result_total = mysqli_query($this->db,$sql_total);
		$row = mysqli_fetch_assoc($result_total);
		$total = $row['total_sum'];

		$sql = "UPDATE tbl_invoice SET total_amount='$total', amount_remaining='$total'-amount_paid WHERE invoice_id='$invoice_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}

	//UPDATED NI EDBERT SEPT 28
	public function get_clients_with_consignment(){
		$sql = "SELECT * FROM tbl_client, tbl_order, tbl_orditem, tbl_delivery WHERE tbl_order.order_id=tbl_delivery.order_id AND delivery_status='1' AND tbl_order.client_id=tbl_client.client_id AND tbl_orditem.qty_remaining>0 AND tbl_orditem.order_id=tbl_order.order_id AND ordtype_id='11' GROUP BY tbl_order.client_id";
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

	//UPDATED NI EDBERT SEPT 28
	public function get_client_consigned_items($client_id){
		$sql = "SELECT *, SUM(qty_remaining) AS row_sum FROM tbl_order, tbl_orditem, tbl_product, tbl_lot, tbl_delivery WHERE qty_remaining>0 AND tbl_orditem.order_id=tbl_order.order_id AND tbl_orditem.lot_id!='' AND tbl_order.client_id='$client_id' AND tbl_product.pro_id=tbl_orditem.pro_id AND tbl_order.order_id=tbl_delivery.order_id AND delivery_status='1' AND tbl_lot.lot_id=tbl_orditem.lot_id GROUP BY tbl_orditem.lot_id";
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

	//GET ALL CONSIGNED PRODUCTS OF SAME NAME ON A SINGLE RECOD
	public function get_consignment_records($client_id, $lot_id){
		$sql = "SELECT *, tbl_orditem.order_id AS ord_id FROM tbl_orditem, tbl_order WHERE lot_id='$lot_id' AND client_id='$client_id' AND tbl_order.order_id=tbl_orditem.order_id AND qty_remaining>0 ORDER BY tbl_orditem.date_added, tbl_orditem.time_added";
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

	//CONSIGNMNENT WITHDRAWAL
	public function insert_consignment_withdrawal($client_id, $usr_id){
		$sql = "INSERT INTO tbl_withdrawal(description, client_id, date_added, time_added, usr_id) VALUES('Consignment', '$client_id', NOW(), NOW(), '$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Record");
		return $this->db->insert_id;
	}

	public function insert_consignment_withdraw_items($withdraw_id, $orditem_id, $pro_id, $lot_id, $qty, $usr_id){
		$sql = "INSERT INTO tbl_withdraw_lot(withdraw_id, orditem_id, pro_id, lot_id, quantity, date_added, time_added, usr_id) VALUES('$withdraw_id', '$orditem_id','$pro_id','$lot_id','$qty',NOW(),NOW(),'$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Record");
		return $result;
	}

	public function update_orditem_consignment($order_id, $lot_id, $qty, $usr_id){
		$sql = "UPDATE tbl_orditem SET qty_withdrawn=qty_withdrawn+'$qty', qty_remaining=qty_remaining-'$qty', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE order_id='$order_id' AND lot_id='$lot_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function deleteOldPendingOrder($order_id){
		$sql = "DELETE FROM tbl_orditem WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	/*public function get_order_ordtype($order_id){
		$sql = "SELECT ordtype_id FROM tbl_order WHERE order_id='$order_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['ordtype_id'];
		return $value;
	}*/
}