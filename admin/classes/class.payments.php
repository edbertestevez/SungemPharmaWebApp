<?php
class Payments{
	public $db;
	
	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}
//edit
	public function insert_order_invoice($invoice_id, $order_id, $usr_id){
		$sql = "INSERT INTO tbl_ord_invoice (order_id, invoice_id, usr_id) VALUES ('$order_id','$invoice_id', '$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}

	public function create_sold_invoice($delivery_id, $invoice_id,$usr_id){
		$sqlItems = "SELECT * FROM tbl_delivery_items, tbl_product  WHERE delivery_id='$delivery_id' AND tbl_delivery_items.pro_id=tbl_product.pro_id";
		$resultItems = mysqli_query($this->db,$sqlItems);
		while($row = mysqli_fetch_assoc($resultItems)){
			$lot_id = $row['lot_id'];
			$pro_id = $row['pro_id'];
			$pro_name = $row['pro_brand']." - ".$row['pro_formulation'];
			$unit_price = $row['pro_unit_price'];
			$quantity = $row['quantity'];
			$subtotal = $row['subtotal'];
			$discount = $row['discount'];
			$total = $row['total'];
			$sql = "INSERT INTO tbl_invoice_item (invoice_id, pro_id, pro_name, lot_id, unit_price, quantity, subtotal, discount, total, date_added, time_added, usr_id) VALUES ('$invoice_id', '$pro_id', '$pro_name', '$lot_id', '$unit_price', '$quantity', '$subtotal', '$discount','$total', NOW(), NOW(), '$usr_id')";
			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		}
		return $resultItems;
	}

	public function invoice_deliver_sold($order_id, $total, $term, $usr_id){
		switch($term){
			//ON DELIVERY
			case '10': 
				$due = date('Y-m-d');
			break;
			//30 DAYS
			case '11': 
				$due = date('Y-m-d', strtotime("+30 days"));
			break;
			//60 DAYS
			case '12': 
				$due = date('Y-m-d', strtotime("+60 days"));
			break;
			default:
			break;
		}
		$sql = "INSERT INTO tbl_invoice(total_amount, amount_remaining, date_issued, time_issued, date_due, usr_id) VALUES ('$total', '$total', NOW(), NOW(), '$due', '$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $this->db->insert_id;
	}

	public function get_search_invoice($client_id){
		$sql = "SELECT *, tbl_invoice.total_amount as total_amount FROM tbl_invoice, tbl_order, tbl_ord_invoice, tbl_client where tbl_client.client_id=tbl_order.client_id AND tbl_order.order_id = tbl_ord_invoice.order_id AND tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.client_id='$client_id' ORDER BY date_issued asc, date_due asc, amount_remaining desc";
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

	public function get_specific_invoice($invoice_id){
		$sql = "SELECT * FROM tbl_invoice, tbl_order, tbl_ord_invoice, tbl_client, tbl_payterm where tbl_client.client_id=tbl_order.client_id AND tbl_order.order_id = tbl_ord_invoice.order_id AND tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id AND tbl_invoice.invoice_id='$invoice_id' AND tbl_order.term_id=tbl_payterm.term_id";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function get_all_invoice(){
		$sql = "SELECT * FROM tbl_invoice, tbl_order, tbl_ord_invoice, tbl_client where tbl_client.client_id=tbl_order.client_id AND tbl_order.order_id = tbl_ord_invoice.order_id AND tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id ORDER BY date_due asc, amount_remaining desc";
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

	public function get_invoicehistory($start, $end){
		$sql = "SELECT * FROM tbl_invoice, tbl_order, tbl_ord_invoice, tbl_client where tbl_client.client_id=tbl_order.client_id AND tbl_order.order_id = tbl_ord_invoice.order_id AND tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id AND '$start'<=tbl_invoice.date_issued AND '$end'>=tbl_invoice.date_issued ORDER BY tbl_invoice.date_issued asc, date_due asc, amount_remaining desc";
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

	public function get_unpaid_invoice(){
		//GROUP BY PARA INDI MAG DUPLICATE ANG IYA KA CONSIGNMENT NA ENTRIES 1 client man lng
		$sql = "SELECT *, tbl_invoice.total_amount as total_amount FROM tbl_invoice, tbl_order, tbl_ord_invoice, tbl_client where tbl_invoice.amount_remaining>0 AND tbl_client.client_id=tbl_order.client_id AND tbl_order.order_id = tbl_ord_invoice.order_id AND tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id GROUP BY tbl_invoice.invoice_id ORDER BY date_due asc, amount_remaining desc";
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

	public function get_search_payment($client_id){
		$sql = "SELECT * FROM tbl_payment, tbl_paymode, tbl_client, tbl_order, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice where tbl_payment.paymode_id=tbl_paymode.paymode_id AND tbl_payment.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.order_id	= tbl_ord_invoice.order_id	AND tbl_client.client_id = tbl_order.client_id AND tbl_order.client_id='$client_id' ORDER BY tbl_payment.payment_date desc";
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

	public function get_all_payment(){
		$sql = "SELECT * FROM tbl_payment, tbl_paymode where tbl_payment.paymode_id=tbl_paymode.paymode_id";
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

	public function get_paymenthistory($start, $end){
		$sql = "SELECT * FROM tbl_payment, tbl_paymode, tbl_client, tbl_order, tbl_ord_invoice where tbl_payment.paymode_id=tbl_paymode.paymode_id AND tbl_payment.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.order_id	= tbl_ord_invoice.order_id	AND tbl_client.client_id = tbl_order.client_id AND '$start'<=payment_date AND '$end'>=payment_date";
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

	public function get_all_pdc(){
		$sql = "SELECT * FROM tbl_payment where paymode_id='41' AND status='0'";
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

	public function get_invoice_payments($id){
		$sql = "SELECT * FROM tbl_payment, tbl_paymode where invoice_id='$id' AND tbl_paymode.paymode_id=tbl_payment.paymode_id";
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
	
	public function get_payment_client($invoice_id){
		$sql = "SELECT * from tbl_client, tbl_ord_invoice, tbl_order WHERE tbl_client.client_id=tbl_order.client_id AND tbl_order.order_id = tbl_ord_invoice.order_id AND tbl_ord_invoice.invoice_id='$invoice_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['client_name'];
		return $value;
	}

	public function get_client_invoice($client_id){
		$sql = "SELECT * from tbl_invoice, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order WHERE tbl_order.order_id = tbl_ord_invoice.order_id AND tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.client_id='$client_id' AND tbl_invoice.amount_remaining>0 GROUP BY tbl_invoice.invoice_id";
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

	public function get_pay_invoice($invoice_id){
		$sql = "SELECT * from tbl_invoice WHERE invoice_id='$invoice_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function get_pay_client($client_id){
		$sql = "SELECT client_name from tbl_client WHERE client_id='$client_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value=$row['client_name'];
		return $value;
	}

	public function get_pending_cheques(){
		$sql = "SELECT COUNT(*) AS total from tbl_payment WHERE pdc_no!='' AND status='0'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['total'];
	}

	public function get_pending_invoice(){
		$sql = "SELECT COUNT(*) AS total from tbl_invoice WHERE amount_remaining>0";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['total'];
	}

	public function get_invoice_items($invoice_id){
		$sql = "SELECT *, tbl_invoice_item.quantity AS total_qty from tbl_invoice_item, tbl_lot, tbl_product WHERE invoice_id='$invoice_id' AND tbl_lot.lot_id=tbl_invoice_item.lot_id AND tbl_product.pro_id=tbl_lot.pro_id";
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

	public function get_paymode(){
		$sql = "SELECT * FROM tbl_paymode";
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

	public function insert_cash_payment($invoice,$client_id,$amount,$paymode,$usr_id){
		$sql = "INSERT INTO tbl_payment(invoice_id, client_id, payment_amount, paymode_id,payment_date,payment_time,usr_id,status) VALUES ('$invoice','$client_id','$amount','$paymode',NOW(),NOW(),'$usr_id',1)";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $this->db->insert_id;
	}

	public function insert_pdc_payment($invoice,$client_id,$amount,$paymode,$pdc_no,$pdc_date,$bank,$usr_id){
		$sql = "INSERT INTO tbl_payment(invoice_id,client_id,payment_amount,paymode_id,pdc_no,pdc_date,payment_date,payment_time,pdc_bank,usr_id) VALUES ('$invoice','$client_id','$amount','$paymode','$pdc_no','$pdc_date',NOW(),NOW(),'$bank','$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $this->db->insert_id;
	}

	public function update_invoice_pay($invoice,$amount,$usr_id){
		$sqlRow = "SELECT total_amount, amount_paid FROM tbl_invoice WHERE invoice_id='$invoice'";
		$resultRow = mysqli_query($this->db,$sqlRow);
		$row = mysqli_fetch_assoc($resultRow);
		$total=$row['total_amount'];
		$paid=$row['amount_paid']+$amount;
		$remain=$total-$paid;

		$sql = "UPDATE tbl_invoice SET amount_paid='$paid', amount_remaining='$remain', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE invoice_id='$invoice'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	public function pdc_received($pdc_no, $usr_id){
		$sql = "UPDATE tbl_payment SET status='1', pdc_date_received=NOW(), usr_id='$usr_id' WHERE pdc_no='$pdc_no'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	public function pdc_decline($pdc_no, $usr_id){
		$sqlget = "SELECT * from tbl_payment WHERE pdc_no='$pdc_no'";
		$resultget = mysqli_query($this->db,$sqlget);
		$row = mysqli_fetch_assoc($resultget);
		$invoice_id=$row['invoice_id'];
		$amount=$row['payment_amount'];

		$sql = "UPDATE tbl_invoice SET amount_paid=amount_paid-'$amount', amount_remaining=amount_remaining+'$amount', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE invoice_id='$invoice_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}
	public function remove_pdc_line($pdc_no){
		$sql = "DELETE FROM tbl_payment where pdc_no='$pdc_no'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Remove Data");
		return $result;
	}

	public function get_count_duepayment_notif(){
		//2 DAYS INTERVAL PARA MASUKOT KAG MA CHECK
		$sql = "SELECT COUNT(*) AS total_due FROM tbl_invoice WHERE amount_remaining>0 AND date_due<=DATE_ADD(CURDATE(), INTERVAL 2 DAY)";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_due'];
		return $value;
	}

	public function get_count_pdc_notif(){
		//2 DAYS INTERVAL PARA MASUKOT KAG MA CHECK
		$sql = "SELECT COUNT(*) AS pdc_count FROM tbl_payment WHERE status='0' AND pdc_date<=DATE_ADD(CURDATE(), INTERVAL 2 DAY)";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['pdc_count'];
		return $value;
	}

	public function get_overdue_payments_list(){
		$sql = "SELECT * FROM tbl_invoice, tbl_ord_invoice, tbl_order, tbl_client WHERE amount_remaining>0 AND date_due<=DATE_ADD(CURDATE(), INTERVAL 2 DAY) AND tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.client_id=tbl_client.client_id";
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

	public function get_pdc_notif_list(){
		$sql = "SELECT * FROM tbl_payment, tbl_invoice, tbl_ord_invoice, tbl_order, tbl_client WHERE tbl_payment.invoice_id=tbl_invoice.invoice_id AND paymode_id='41' AND pdc_date<=DATE_ADD(CURDATE(), INTERVAL 2 DAY) AND tbl_payment.status='0' AND tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.client_id=tbl_client.client_id";
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

	//GET MEDREP ASSINGED SA PAYMENT PARA SA MOBILE
	public function get_payment_medrep($invoice_id){
		$sql = "SELECT tbL_client.medrep_id FROM tbl_ord_invoice, tbl_order, tbl_client WHERE tbl_ord_invoice.invoice_id='$invoice_id' AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.client_id=tbl_client.client_id";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['medrep_id'];
	}

	public function get_payment_selected($payment_id){
		$sql = "SELECT tbl_payment.*, client_name, paymode_name FROM tbl_payment, tbl_client, tbl_paymode where payment_id='$payment_id' AND tbl_payment.paymode_id = tbl_paymode.paymode_id AND tbl_client.client_id=tbl_payment.client_id";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function remove_payment($payment_id){
		$sqlRow = "SELECT * FROM tbl_payment where payment_id='$payment_id'";
		$resultRow = mysqli_query($this->db,$sqlRow);
		$row = mysqli_fetch_assoc($resultRow);
		$invoice_id = $row['invoice_id'];
		$amount = $row['payment_amount'];

		$sqlDelete = "DELETE FROM tbl_payment where payment_id='$payment_id'";
		$resultDelete = mysqli_query($this->db,$sqlDelete) or die(mysqli_error() . "Cannot Remove Data");
		
		$sqlUpdate = "UPDATE tbl_invoice SET amount_paid = amount_paid-$amount, amount_remaining=amount_remaining+$amount where invoice_id='$invoice_id'";
		$result = mysqli_query($this->db,$sqlUpdate) or die(mysqli_error() . "Cannot UPDATE Data");


		return $result;
	}

	public function subtract_invoice_pay($invoice,$amount,$usr_id){
		$sqlRow = "SELECT total_amount, amount_paid FROM tbl_invoice WHERE invoice_id='$invoice'";
		$resultRow = mysqli_query($this->db,$sqlRow);
		$row = mysqli_fetch_assoc($resultRow);
		$total=$row['total_amount'];
		$paid=$row['amount_paid']+$amount;
		$remain=$total-$paid;

		$sql = "UPDATE tbl_invoice SET amount_paid='$paid', amount_remaining='$remain', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE invoice_id='$invoice'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}
}