<?php
class Reports{
	public $db;

	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function get_stocks_report($start, $end){
		//ORDITEM.lot_id!=0 -> Meaning wala pa ni nahimuan delivery since wala pa may naka assign sa iya nga lot id eh no ORAYT Rock and Roll (INDI KO MAKWA NGA I LEFT OUTER JOIN SYA NGA I CHECK ANG STATUS SA ORDER B SO AMO NLNG NI)

		//INCLUDE ANG DELIVERY
/*
		$sql = "SELECT *, tbl_prod_supplier.sumsupply AS total_in, tbl_orditem.sumtotal AS total_out, tbl_withdraw_lot.sumwithdrawn AS total_withdraw, tbl_disposal.sumdisposal AS total_dispose FROM (SELECT pro_id, date_added,  SUM(tbl_prod_supplier.quantity) AS sumsupply FROM tbl_prod_supplier WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id)tbl_prod_supplier RIGHT OUTER JOIN (SELECT * FROM tbl_product WHERE status='1')tbl_product ON tbl_product.pro_id=tbl_prod_supplier.pro_id LEFT OUTER JOIN (SELECT *, SUM(tbl_orditem.qty_delivery) AS sumtotal FROM tbl_orditem WHERE lot_id!='0' AND order_id IN(SELECT order_id FROM tbl_delivery WHERE '$start'<=tbl_delivery.date_added AND '$end'>=tbl_delivery.date_added AND delivery_status='1') GROUP BY pro_id) tbl_orditem ON tbl_product.pro_id=tbl_orditem.pro_id LEFT OUTER JOIN (SELECT pro_id, date_added, SUM(tbl_withdraw_lot.quantity) AS sumwithdrawn FROM tbl_withdraw_lot  WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id) tbl_withdraw_lot on tbl_withdraw_lot.pro_id=tbl_product.pro_id LEFT OUTER JOIN (SELECT pro_id, date_disposed, SUM(tbl_disposal.quantity) AS sumdisposal FROM tbl_disposal WHERE '$start'<=date_disposed AND '$end'>=date_disposed GROUP BY pro_id) tbl_disposal ON tbl_disposal.pro_id=tbl_product.pro_id GROUP BY tbl_product.pro_id ORDER BY pro_brand";
		$result = mysqli_query($this->db,$sql);
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }else{
            $list=false;
        }

        $sql1 = "SELECT *, tbl_prod_supplier.sumsupply AS total_in, tbl_orditem.sumtotal AS total_out, tbl_withdraw_lot.sumwithdrawn AS total_withdraw, tbl_disposal.sumdisposal AS total_dispose FROM (SELECT pro_id, date_added,  SUM(tbl_prod_supplier.quantity) AS sumsupply FROM tbl_prod_supplier WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id)tbl_prod_supplier INNER JOIN (SELECT * FROM tbl_product WHERE status='0')tbl_product ON tbl_product.pro_id=tbl_prod_supplier.pro_id LEFT OUTER JOIN (SELECT *, SUM(tbl_orditem.qty_delivery) AS sumtotal FROM tbl_orditem WHERE lot_id!='0' AND order_id IN(SELECT order_id FROM tbl_delivery WHERE '$start'<=tbl_delivery.date_added AND '$end'>=tbl_delivery.date_added AND delivery_status='1') GROUP BY pro_id) tbl_orditem ON tbl_product.pro_id=tbl_orditem.pro_id LEFT OUTER JOIN (SELECT pro_id, date_added, SUM(tbl_withdraw_lot.quantity) AS sumwithdrawn FROM tbl_withdraw_lot  WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id) tbl_withdraw_lot on tbl_withdraw_lot.pro_id=tbl_product.pro_id LEFT OUTER JOIN (SELECT pro_id, date_disposed, SUM(tbl_disposal.quantity) AS sumdisposal FROM tbl_disposal WHERE '$start'<=date_disposed AND '$end'>=date_disposed GROUP BY pro_id) tbl_disposal ON tbl_disposal.pro_id=tbl_product.pro_id GROUP BY tbl_product.pro_id ORDER BY pro_brand";
		$result1 = mysqli_query($this->db,$sql1);
		if (mysqli_num_rows($result1)>0){
			while($row1 = mysqli_fetch_assoc($result1)){
				$list[] = $row1;
			}
        }*/

        //NEW QUERY
        $sql1 = "SELECT *, tbl_prod_supplier.sumsupply AS total_in, tbl_delivery_items.sumtotal AS total_out, tbl_withdraw_lot.sumwithdrawn AS total_withdraw, tbl_disposal.sumdisposal AS total_dispose FROM (SELECT pro_id, date_added,  SUM(tbl_prod_supplier.quantity) AS sumsupply FROM tbl_prod_supplier WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id)tbl_prod_supplier LEFT OUTER JOIN tbl_product ON tbl_product.pro_id=tbl_prod_supplier.pro_id LEFT OUTER JOIN (SELECT *, SUM(tbl_delivery_items.quantity) AS sumtotal FROM tbl_delivery_items WHERE lot_id!='0' AND delivery_id IN(SELECT delivery_id FROM tbl_delivery WHERE '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND delivery_status='1') GROUP BY pro_id) tbl_delivery_items ON tbl_product.pro_id=tbl_delivery_items.pro_id LEFT OUTER JOIN (SELECT pro_id, date_added, SUM(tbl_withdraw_lot.quantity) AS sumwithdrawn FROM tbl_withdraw_lot  WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id) tbl_withdraw_lot on tbl_withdraw_lot.pro_id=tbl_product.pro_id LEFT OUTER JOIN (SELECT pro_id, date_disposed, SUM(tbl_disposal.quantity) AS sumdisposal FROM tbl_disposal WHERE '$start'<=date_disposed AND '$end'>=date_disposed GROUP BY pro_id) tbl_disposal ON tbl_disposal.pro_id=tbl_product.pro_id GROUP BY tbl_product.pro_id ORDER BY pro_brand";
		$result1 = mysqli_query($this->db,$sql1);
		if (mysqli_num_rows($result1)>0){	
			while($row1 = mysqli_fetch_assoc($result1)){
				$list[] = $row1;
			}
                
		return $list;
		}
	}



	public function get_stocks_report_print($start, $end){
		//ORDITEM.lot_id!=0 -> Meaning wala pa ni nahimuan delivery since wala pa may naka assign sa iya nga lot id eh no ORAYT Rock and Roll (INDI KO MAKWA NGA I LEFT OUTER JOIN SYA NGA I CHECK ANG STATUS SA ORDER B SO AMO NLNG NI)

		$sql = "SELECT *, tbl_prod_supplier.sumsupply AS total_in, tbl_orditem.sumtotal AS total_out, tbl_withdraw_lot.sumwithdrawn AS total_withdraw, tbl_disposal.sumdisposal AS total_dispose FROM (SELECT pro_id, date_added,  SUM(tbl_prod_supplier.quantity) AS sumsupply FROM tbl_prod_supplier WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id)tbl_prod_supplier RIGHT OUTER JOIN (SELECT * FROM tbl_product WHERE status='1')tbl_product ON tbl_product.pro_id=tbl_prod_supplier.pro_id LEFT OUTER JOIN (SELECT *, SUM(tbl_orditem.qty_delivery) AS sumtotal FROM tbl_orditem WHERE lot_id!='0' AND order_id IN(SELECT order_id FROM tbl_delivery WHERE '$start'<=tbl_delivery.date_added AND '$end'>=tbl_delivery.date_added AND delivery_status='1') GROUP BY pro_id) tbl_orditem ON tbl_product.pro_id=tbl_orditem.pro_id LEFT OUTER JOIN (SELECT pro_id, date_added, SUM(tbl_withdraw_lot.quantity) AS sumwithdrawn FROM tbl_withdraw_lot  WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id) tbl_withdraw_lot on tbl_withdraw_lot.pro_id=tbl_product.pro_id LEFT OUTER JOIN (SELECT pro_id, date_disposed, SUM(tbl_disposal.quantity) AS sumdisposal FROM tbl_disposal WHERE '$start'<=date_disposed AND '$end'>=date_disposed GROUP BY pro_id) tbl_disposal ON tbl_disposal.pro_id=tbl_product.pro_id GROUP BY tbl_product.pro_id ORDER BY pro_brand";
		$result = mysqli_query($this->db,$sql);
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }else{
            $list=false;
        }

        $sql1 = "SELECT *, tbl_prod_supplier.sumsupply AS total_in, tbl_orditem.sumtotal AS total_out, tbl_withdraw_lot.sumwithdrawn AS total_withdraw, tbl_disposal.sumdisposal AS total_dispose FROM (SELECT pro_id, date_added,  SUM(tbl_prod_supplier.quantity) AS sumsupply FROM tbl_prod_supplier WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id)tbl_prod_supplier INNER JOIN (SELECT * FROM tbl_product WHERE status='0')tbl_product ON tbl_product.pro_id=tbl_prod_supplier.pro_id LEFT OUTER JOIN (SELECT *, SUM(tbl_orditem.qty_delivery) AS sumtotal FROM tbl_orditem WHERE lot_id!='0' AND order_id IN(SELECT order_id FROM tbl_delivery WHERE '$start'<=tbl_delivery.date_added AND '$end'>=tbl_delivery.date_added AND delivery_status='1') GROUP BY pro_id) tbl_orditem ON tbl_product.pro_id=tbl_orditem.pro_id LEFT OUTER JOIN (SELECT pro_id, date_added, SUM(tbl_withdraw_lot.quantity) AS sumwithdrawn FROM tbl_withdraw_lot  WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id) tbl_withdraw_lot on tbl_withdraw_lot.pro_id=tbl_product.pro_id LEFT OUTER JOIN (SELECT pro_id, date_disposed, SUM(tbl_disposal.quantity) AS sumdisposal FROM tbl_disposal WHERE '$start'<=date_disposed AND '$end'>=date_disposed GROUP BY pro_id) tbl_disposal ON tbl_disposal.pro_id=tbl_product.pro_id GROUP BY tbl_product.pro_id ORDER BY pro_brand";
		$result1 = mysqli_query($this->db,$sql1);
		if (mysqli_num_rows($result1)>0){
			while($row1 = mysqli_fetch_assoc($result1)){
				$list[] = $row1;
			}
        }
                
		return $list;
	}

	public function get_consignment_report($start, $end){
		$sql = "SELECT *, tbl_product.pro_id AS product_id, tbl_orditem.consign_total AS total_consigned, tbl_withdraw_lot.sumwithdrawn AS total_withdraw, tbl_invoice_item.sumsales AS total_sales, tbl_invoice_item.sumsold AS total_sold FROM (SELECT * FROM tbl_product WHERE status='1')tbl_product LEFT OUTER JOIN (SELECT *, SUM(qty_total) AS consign_total FROM tbl_orditem WHERE tbl_orditem.order_id IN (SELECT order_id FROM tbl_order WHERE ordtype_id='11') AND tbl_orditem.order_id IN(SELECT order_id FROM tbl_delivery WHERE '$start'<=tbl_delivery.date_added AND '$end'>=tbl_delivery.date_added) GROUP BY pro_id)tbl_orditem ON tbl_product.pro_id=tbl_orditem.pro_id LEFT OUTER JOIN (SELECT pro_id, date_added, SUM(tbl_withdraw_lot.quantity) AS sumwithdrawn FROM tbl_withdraw_lot  WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id) tbl_withdraw_lot on tbl_withdraw_lot.pro_id=tbl_product.pro_id LEFT OUTER JOIN (SELECT *, SUM(tbl_invoice_item.total) AS sumsales, SUM(quantity) AS sumsold FROM tbl_invoice_item WHERE '$start'<=date_added AND '$end'>=date_added AND invoice_id IN (SELECT invoice_id FROM tbl_ord_invoice WHERE order_id IN (SELECT order_id FROM tbl_order WHERE ordtype_id='11')) GROUP BY pro_id) tbl_invoice_item ON tbl_invoice_item.pro_id=tbl_product.pro_id ORDER BY total_consigned DESC, total_withdraw DESC, total_sales DESC, pro_brand";

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

	public function get_consignment_report_print($start, $end){
		$sql = "SELECT *, tbl_product.pro_id AS product_id, tbl_orditem.consign_total AS total_consigned, tbl_withdraw_lot.sumwithdrawn AS total_withdraw, tbl_invoice_item.sumsales AS total_sales, tbl_invoice_item.sumsold AS total_sold FROM (SELECT * FROM tbl_product WHERE status='1')tbl_product LEFT OUTER JOIN (SELECT *, SUM(qty_total) AS consign_total FROM tbl_orditem WHERE tbl_orditem.order_id IN (SELECT order_id FROM tbl_order WHERE ordtype_id='11') AND tbl_orditem.order_id IN(SELECT order_id FROM tbl_delivery WHERE '$start'<=tbl_delivery.date_added AND '$end'>=tbl_delivery.date_added) GROUP BY pro_id)tbl_orditem ON tbl_product.pro_id=tbl_orditem.pro_id LEFT OUTER JOIN (SELECT pro_id, date_added, SUM(tbl_withdraw_lot.quantity) AS sumwithdrawn FROM tbl_withdraw_lot  WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id) tbl_withdraw_lot on tbl_withdraw_lot.pro_id=tbl_product.pro_id LEFT OUTER JOIN (SELECT *, SUM(tbl_invoice_item.total) AS sumsales, SUM(quantity) AS sumsold FROM tbl_invoice_item WHERE '$start'<=date_added AND '$end'>=date_added AND invoice_id IN (SELECT invoice_id FROM tbl_ord_invoice WHERE order_id IN (SELECT order_id FROM tbl_order WHERE ordtype_id='11')) GROUP BY pro_id) tbl_invoice_item ON tbl_invoice_item.pro_id=tbl_product.pro_id ORDER BY pro_brand";

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

	public function get_consignment_prod_count($pro_id, $start, $end, $client_id){
		$sql = "SELECT *, SUM(tbl_orditem.qty_total) AS total_consigned FROM tbl_orditem, tbl_order, tbl_delivery WHERE tbl_orditem.order_id=tbl_order.order_id AND tbl_order.ordtype_id='11' AND tbl_delivery.order_id=tbl_order.order_id AND '$start'<=tbl_delivery.date_added AND '$end'>=tbl_delivery.date_added AND tbl_orditem.pro_id='$pro_id' AND tbl_order.client_id='$client_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_consigned'];
		return $value;
	}

	public function get_consignment_prod_sold($pro_id, $start, $end, $client_id){
		$sql = "SELECT *, SUM(tbl_invoice_item.quantity) AS total_sold FROM tbl_invoice_item, tbl_ord_invoice, tbl_order WHERE pro_id='$pro_id' AND tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.order_id=tbl_ord_invoice.order_id AND ordtype_id='11' AND client_id='$client_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sold'];
		return $value;
	}

	public function get_consignment_prod_withdrawn($pro_id, $start, $end, $client_id){
		$sql = "SELECT *, SUM(tbl_withdraw_lot.quantity) AS total_withdrawn FROM tbl_withdraw_lot, tbl_withdrawal WHERE '$start'<=tbl_withdraw_lot.date_added AND '$end'>=tbl_withdraw_lot.date_added AND tbl_withdraw_lot.pro_id='$pro_id' AND tbl_withdrawal.client_id='$client_id' AND tbl_withdrawal.withdraw_id=tbl_withdraw_lot.withdraw_id";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_withdrawn'];
		return $value;
	}

	public function get_consignment_prod_sales($pro_id, $start, $end, $client_id){
		$sql = "SELECT *, SUM(tbl_invoice_item.total) AS total_sales FROM tbl_invoice_item, tbl_ord_invoice, tbl_order WHERE pro_id='$pro_id' AND tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.order_id=tbl_ord_invoice.order_id AND ordtype_id='11' AND client_id='$client_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sales'];
		return $value;
	}

	public function get_medsales_report($start, $end){
		$sql = "SELECT *, tbl_invoice_item.sumqty AS sold_qty, tbl_invoice_item.sumtotal AS sold_sales FROM (SELECT * FROM tbl_product WHERE status='1')tbl_product LEFT OUTER JOIN (SELECT *, SUM(quantity) AS sumqty, SUM(total) AS sumtotal FROM tbl_invoice_item WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id)tbl_invoice_item ON tbl_product.pro_id=tbl_invoice_item.pro_id ORDER BY pro_brand";
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

	public function get_medsales_report_print($start, $end){
		$sql = "SELECT *, tbl_invoice_item.sumqty AS sold_qty, tbl_invoice_item.sumtotal AS sold_sales FROM (SELECT * FROM tbl_product WHERE status='1')tbl_product LEFT OUTER JOIN (SELECT *, SUM(quantity) AS sumqty, SUM(total) AS sumtotal FROM tbl_invoice_item WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY pro_id)tbl_invoice_item ON tbl_product.pro_id=tbl_invoice_item.pro_id ORDER BY pro_brand";
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

	public function get_medrep_report($start, $end){
		$sql = "SELECT *, SUM(tbl_invoice_item.med_qty) AS med_qty, tbl_delivery.del_count, tbl_invoice.inv_sales as inv_sales, SUM(inv_sales) as med_sales FROM (SELECT *, SUM(quantity) as med_qty FROM tbl_invoice_item WHERE '$start'<=date_added AND '$end'>=date_added GROUP BY invoice_id)tbl_invoice_item RIGHT OUTER JOIN (SELECT *,SUM(total_amount) as inv_sales FROM tbl_invoice WHERE '$start'<=date_issued AND '$end'>=date_issued GROUP BY invoice_id)tbl_invoice ON tbl_invoice.invoice_id=tbl_invoice_item.invoice_id RIGHT OUTER JOIN (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice ON tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id RIGHT OUTER JOIN tbl_order ON tbl_order.order_id=tbl_ord_invoice.order_id RIGHT OUTER JOIN (SELECT * FROM tbl_client)tbl_client ON tbl_client.client_id=tbl_order.client_id  INNER JOIN (SELECT * FROM tbl_medrep)tbl_medrep ON tbl_medrep.medrep_id=tbl_client.medrep_id LEFT OUTER JOIN (SELECT *,COUNT(*) AS del_count FROM tbl_delivery WHERE '$start'<=date_delivered AND '$end'>=date_delivered GROUP BY medrep_id)tbl_delivery ON tbl_delivery.medrep_id=tbl_medrep.medrep_id GROUP BY tbl_medrep.medrep_id ";
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

	public function get_medrep_medsales($start, $end){
		$sql = "SELECT *, SUM(tbl_invoice.total_amount) AS total_medsales FROM tbl_medrep RIGHT OUTER JOIN tbl_client ON tbl_client.medrep_id=tbl_medrep.medrep_id LEFT OUTER JOIN tbl_order ON tbl_order.client_id=tbl_client.client_id LEFT OUTER JOIN (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice ON tbl_ord_invoice.order_id=tbl_order.order_id LEFT OUTER JOIN tbl_invoice ON tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id WHERE '$start'<=tbl_invoice.date_issued AND '$end'>=tbl_invoice.date_issued GROUP BY tbl_client.medrep_id ORDER BY total_medsales";





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

	//SPECIFIC ACTUAL SALES
	public function get_medrep_actual_sales($start, $end){
		$sql = "SELECT *, SUM(payment_amount) as actual_sales FROM (SELECT * FROM tbl_payment WHERE '$start'<=tbl_payment.payment_date AND '$end'>=tbl_payment.payment_date)tbl_payment RIGHT OUTER JOIN tbl_invoice ON tbl_invoice.invoice_id=tbl_payment.invoice_id RIGHT OUTER JOIN (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice ON tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id RIGHT OUTER JOIN tbl_order ON tbl_order.order_id=tbl_ord_invoice.order_id RIGHT OUTER JOIN tbl_client ON tbl_client.client_id = tbl_order.client_id RIGHT OUTER JOIN (SELECT * FROM tbl_medrep WHERE status='1')tbl_medrep ON tbl_medrep.medrep_id=tbl_client.medrep_id GROUP BY tbl_medrep.medrep_id ORDER BY actual_sales DESC";
		$result = mysqli_query($this->db,$sql);
		if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }else{
            $list=false;
        }

        $sql1 = "SELECT *, SUM(payment_amount) as actual_sales FROM (SELECT * FROM tbl_payment WHERE '$start'<=tbl_payment.payment_date AND '$end'>=tbl_payment.payment_date)tbl_payment RIGHT OUTER JOIN tbl_invoice ON tbl_invoice.invoice_id=tbl_payment.invoice_id RIGHT OUTER JOIN (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice ON tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id RIGHT OUTER JOIN tbl_order ON tbl_order.order_id=tbl_ord_invoice.order_id RIGHT OUTER JOIN tbl_client ON tbl_client.client_id = tbl_order.client_id INNER JOIN (SELECT * FROM tbl_medrep WHERE status='0')tbl_medrep ON tbl_medrep.medrep_id=tbl_client.medrep_id GROUP BY tbl_medrep.medrep_id ORDER BY actual_sales DESC";
		$result1 = mysqli_query($this->db,$sql1);
		if (mysqli_num_rows($result1)>0){
			while($row1 = mysqli_fetch_assoc($result1)){
				$list[] = $row1;
			}
        }
                
		return $list;
	}

	public function get_medrep_actual_sales_specific($medrep_id, $start, $end){
		$sql = "SELECT *, SUM(payment_amount) as actual_sales FROM tbl_payment, tbl_invoice, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order, tbl_client WHERE tbl_payment.invoice_id=tbl_invoice.invoice_id AND tbl_ord_invoice.invoice_id=tbl_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.client_id=tbl_client.client_id AND tbl_client.medrep_id='$medrep_id' AND '$start'<=tbl_payment.payment_date AND '$end'>=tbl_payment.payment_date";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['actual_sales'];
		return $value;
	}

	//CONTINUE TODAY
	public function medrep_prod_qty($medrep_id, $start, $end){
		$sql = "SELECT *, SUM(qty_total) AS total_prod FROM tbl_orditem, tbl_order, tbl_client WHERE tbl_orditem.order_id=tbl_order.order_id AND '$start'<=tbl_order.order_date AND '$end'>=tbl_order.order_date AND tbl_order.client_id=tbl_client.client_id AND tbl_client.medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_prod'];
		return $value;
	}

	public function get_total_supplied($start, $end){
		$sql = "SELECT SUM(quantity) AS total_supplied FROM tbl_prod_supplier WHERE '$start'<=date_added AND '$end'>=date_added";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_supplied'];
		return $value;
	}

	//INCLUDED ANG DELIVERY na table bali amo ni sila ang
	public function get_total_delivered($start, $end){
		$sql = "SELECT *, SUM(quantity) AS total_delivered FROM tbl_delivery_items, tbl_delivery WHERE tbl_delivery_items.delivery_id=tbl_delivery.delivery_id AND '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND tbl_delivery.delivery_status='1'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_delivered'];
		return $value;
	}
	public function get_total_withdrawn($start, $end){
		$sql = "SELECT SUM(quantity) AS total_withdrawn FROM tbl_withdraw_lot WHERE '$start'<=date_added AND '$end'>=date_added";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_withdrawn'];
		return $value;
	}

	public function get_total_withdrawn_consignment($start, $end){
		$sql = "SELECT SUM(tbl_withdraw_lot.quantity) AS total_withdrawn FROM tbl_withdraw_lot,tbl_withdrawal WHERE '$start'<=tbl_withdraw_lot.date_added AND '$end'>=tbl_withdraw_lot.date_added AND tbl_withdraw_lot.withdraw_id=tbl_withdrawal.withdraw_id AND (description='Consignment' OR description='Withdraw Consignment')";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_withdrawn'];
		return $value;
	}

	public function get_total_disposed($start, $end){
		$sql = "SELECT SUM(quantity) AS total_disposed FROM tbl_disposal WHERE '$start'<=date_disposed AND '$end'>=date_disposed";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_disposed'];
		return $value;
	}

	public function get_total_consignment($start, $end){
		//11 ang sa ordtype nga CONSIGNMENT //CONSIDERED DELIVERED amo na ang delivery includeed
		$sql = "SELECT *, SUM(tbl_delivery_items.quantity) AS total_consigned FROM tbl_delivery_items, tbl_order, tbl_delivery WHERE tbl_delivery.order_id=tbl_order.order_id AND tbl_delivery.delivery_id=tbl_delivery_items.delivery_id AND tbl_order.ordtype_id='11' AND '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND delivery_status='1'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_consigned'];
		return $value;
	}

	public function get_total_medqty($start, $end){
		$sql = "SELECT SUM(quantity) AS total_sold_qty FROM tbl_invoice_item WHERE '$start'<=date_added AND '$end'>=date_added";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sold_qty'];
		return $value;
	}

	public function get_total_management_sales($start, $end){
		$sql = "SELECT SUM(payment_amount) AS management_sales FROM tbl_payment WHERE '$start'<=payment_date AND '$end'>=payment_date";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['management_sales'];
		return $value;
	}

	public function get_total_medsales($start, $end){
		$sql = "SELECT SUM(total) AS total_sold_sales FROM tbl_invoice_item WHERE '$start'<=date_added AND '$end'>=date_added";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sold_sales'];
		return $value;
	}

	public function get_total_consignment_sales($start, $end){
		$sql = "SELECT *, SUM(tbl_invoice_item.total) AS subtotal_sales FROM tbl_order, tbl_product, tbl_lot, tbl_invoice_item, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice WHERE '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added AND tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.ordtype_id='11' AND tbl_invoice_item.lot_id=tbl_lot.lot_id AND tbl_lot.pro_id=tbl_product.pro_id";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['subtotal_sales'];
	}

	public function get_total_considered_sold($start, $end){
		$sql = "SELECT *, SUM(tbl_invoice_item.total) AS subtotal_sales FROM tbl_order, tbl_invoice_item, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice WHERE '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added AND tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.ordtype_id='10'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row['subtotal_sales'];
	}

	//INCLUDE GD ANG DELIVERY PARA CONSIDERED SYA AS CONSIGNED NAGD NA PRODUCT
	public function get_json_top_consigned($start, $end){
		$sql = "SELECT SUM(tbl_delivery_items.quantity) AS total_qty, pro_brand, pro_generic, pro_formulation, tbl_order.order_date, tbl_delivery_items.delivery_id, tbl_delivery_items.pro_id, tbl_order.order_id, tbl_order.ordtype_id, order_date, tbl_delivery.order_id, tbl_delivery.date_delivered FROM tbl_order, tbl_delivery_items, tbl_product, tbl_delivery WHERE tbl_delivery_items.delivery_id = tbl_delivery.delivery_id AND tbl_delivery_items.pro_id = tbl_product.pro_id AND tbl_order.ordtype_id='11' AND tbl_delivery.order_id=tbl_order.order_id AND '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND delivery_status='1' GROUP BY tbl_delivery_items.pro_id ORDER BY total_qty DESC LIMIT 8";
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

	public function get_consign_sales_client($start, $end){
		$sql = "SELECT client_name, SUM(tbl_invoice.total_amount) AS consign_sales FROM tbl_invoice, tbl_order, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_client WHERE tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.order_id=tbl_ord_invoice.order_id AND tbl_order.client_id=tbl_client.client_id AND '$start'<=tbl_invoice.date_issued AND '$end'>=tbl_invoice.date_issued AND tbl_order.ordtype_id='11' GROUP BY tbl_order.client_id ORDER by consign_sales DESC";
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

	public function get_json_area_consigned($start, $end){
		$sql = "SELECT SUM(tbl_delivery_items.quantity) AS area_total, area_name, tbl_order.order_date, tbl_order.ordtype_id, tbl_order.order_id, tbl_order.client_id, tbl_client.client_id, tbl_area.area_id, tbl_client.area_id, tbl_delivery.order_id, tbl_delivery.date_added FROM (tbl_area LEFT OUTER JOIN tbl_client ON tbl_client.area_id=tbl_area.area_id), tbl_order, tbl_delivery_items, tbl_delivery WHERE tbl_delivery_items.delivery_id=tbl_delivery.delivery_id AND tbl_order.ordtype_id='11' AND tbl_order.client_id=tbl_client.client_id AND tbl_client.area_id=tbl_area.area_id AND tbl_order.order_id=tbl_delivery.order_id AND '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND delivery_status='1' GROUP BY tbl_client.client_id, tbl_area.area_id";
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

	public function get_monthly_order_count(){
		$month_today = date("m");
		$year_today = date("Y");
		$sql = "SELECT COUNT(*) AS order_count FROM tbl_order WHERE '$month_today'=MONTH(order_date) AND '$year_today'=YEAR(order_date)";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['order_count'];
		return $value;
	}
	//omclude ang delivery
	public function get_monthly_consigned_count(){
		$month_today = date("m");
		$year_today = date("Y");
		$sql = "SELECT *, SUM(qty_delivery) AS consigned_count FROM tbl_orditem, tbl_order, tbl_delivery WHERE '$month_today'=MONTH(tbl_delivery.date_added) AND '$year_today'=YEAR(tbl_delivery.date_added) AND tbl_order.order_id=tbl_orditem.order_id AND tbl_order.ordtype_id='11' AND tbl_orditem.lot_id!='0' AND tbl_order.order_id=tbl_delivery.order_id AND tbl_delivery.delivery_status='1'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['consigned_count'];
		return $value;
	}

public function get_monthly_medicine_sales(){
		$month_today = date("m");
		$year_today = date("Y");
		$sql = "SELECT SUM(total_amount) AS total_sold_sales FROM tbl_invoice WHERE '$month_today'=MONTH(date_issued) AND '$year_today'=YEAR(date_issued)";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sold_sales'];
		return $value;
	}

	public function get_monthly_management_sales(){
		$month_today = date("m");
		$year_today = date("Y");

		$sql = "SELECT SUM(payment_amount) AS total_management_sales FROM tbl_payment WHERE MONTH(payment_date)='$month_today' AND YEAR(payment_date)='$year_today'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_management_sales'];
		return $value;
	}

	public function get_json_topsales_consigned($start, $end){
		$sql = "SELECT tbl_invoice_item.invoice_id,tbl_invoice_item.pro_id, tbl_ord_invoice.invoice_id, tbl_ord_invoice.order_id,tbl_order.order_id,tbl_order.ordtype_id, tbl_product.pro_id, pro_brand, pro_generic, pro_formulation, pro_packaging, tbl_invoice_item.date_added, SUM(tbl_invoice_item.total) AS consign_sales FROM tbl_invoice_item, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order, tbl_product WHERE tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_invoice_item.pro_id=tbl_product.pro_id  AND tbl_order.ordtype_id='11' AND '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added GROUP BY tbl_invoice_item.pro_id ORDER BY consign_sales DESC LIMIT 8";
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

	public function get_json_top_supplier($start, $end){
		$sql = "SELECT *, SUM(quantity) AS total_supplied FROM tbl_prod_supplier, tbl_supplier WHERE '$start'<=tbl_prod_supplier.date_added AND '$end'>=tbl_prod_supplier.date_added AND tbl_supplier.supplier_id=tbl_prod_supplier.supplier_id GROUP BY tbl_prod_supplier.supplier_id";
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

	public function get_json_top_supplied_clients($start, $end){
		$sql = "SELECT *, SUM(quantity) AS total_supplied FROM tbl_delivery_items, tbl_order, tbl_delivery, tbl_client WHERE '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND tbl_order.order_id=tbl_delivery.order_id AND tbl_delivery.delivery_id=tbl_delivery_items.delivery_id AND tbl_client.client_id=tbl_order.client_id GROUP BY tbl_order.client_id";
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

	public function get_json_top_supplied_stock($start, $end){
		$sql = "SELECT tbl_product.*, tbl_prod_supplier.pro_id, tbl_prod_supplier.date_added, SUM(tbl_prod_supplier.quantity) AS total_supplied FROM tbl_product, tbl_prod_supplier WHERE tbl_product.pro_id=tbl_prod_supplier.pro_id AND '$start'<=tbl_prod_supplier.date_added AND '$end'>=tbl_prod_supplier.date_added GROUP BY tbl_prod_supplier.pro_id ORDER BY total_supplied DESC LIMIT 8";
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

	public function get_json_top_delivered_stock($start, $end){
		$sql = "SELECT *, SUM(quantity) AS total_delivered FROM tbl_delivery_items, tbl_product, tbl_delivery WHERE tbl_delivery_items.pro_id=tbl_product.pro_id AND tbl_delivery_items.delivery_id=tbl_delivery.delivery_id AND '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND delivery_status='1' GROUP BY tbl_delivery_items.pro_id ORDER BY total_delivered DESC LIMIT 8";
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

	public function get_supplier_report($start, $end){
		$sql = "SELECT *, SUM(quantity) as total_supplied FROM tbl_supplier, tbl_prod_supplier WHERE tbl_supplier.supplier_id=tbl_prod_supplier.supplier_id AND '$start'<=tbl_prod_supplier.date_added AND '$end'>=tbl_prod_supplier.date_added GROUP BY tbl_prod_supplier.supplier_id ORDER BY total_supplied DESC";
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

	public function get_json_top_medsales($start, $end){
		$sql = "SELECT *, SUM(tbl_invoice_item.total) AS total_sales FROM tbl_invoice_item, tbl_product WHERE tbl_invoice_item.pro_id=tbl_product.pro_id AND '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added GROUP BY tbl_invoice_item.pro_id ORDER BY total_sales DESC LIMIT 8";
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

	public function get_json_management_area($start, $end){
		$sql = "SELECT area_name, SUM(payment_amount) AS total_sales FROM tbl_payment, tbl_ord_invoice, tbl_order, tbl_client,tbl_area WHERE tbl_payment.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.client_id=tbl_client.client_id AND '$start'<=tbl_payment.payment_date AND '$end'>=tbl_payment.payment_date AND tbl_client.area_id=tbl_area.area_id GROUP BY tbl_client.area_id";
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

	public function get_json_receive_management($start, $end){
		$sql = "SELECT client_name, SUM(payment_amount) AS total_sales FROM tbl_payment, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order, tbl_client WHERE tbl_payment.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.client_id=tbl_client.client_id AND '$start'<=tbl_payment.payment_date AND '$end'>=tbl_payment.payment_date GROUP BY tbl_order.client_id ORDER BY total_sales DESC";
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

	public function get_json_medsales_area($start, $end){
		$sql = "SELECT *, SUM(tbl_invoice_item.total) AS total_sales FROM tbl_invoice_item, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order, tbl_client, tbl_area WHERE tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_client.client_id=tbl_order.client_id AND tbl_client.area_id=tbl_area.area_id AND '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added GROUP BY tbl_area.area_id";
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

	//CLIENTS CONSIGNMENT (Include ang delivery. Bali amo ni sila ang considered nahatag nga consigned nagd)
	public function get_client_report_consigned($client_id, $start, $end){
		$sql = "SELECT *, SUM(quantity) as total_consigned FROM tbl_delivery_items, tbl_order, tbl_delivery WHERE '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND delivery_status='1' AND tbl_delivery.order_id=tbl_order.order_id AND tbl_delivery_items.delivery_id=tbl_delivery.delivery_id AND tbl_order.client_id='$client_id' AND ordtype_id='11'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_consigned'];
		return $value;
	}

	public function get_client_report_withdrawn($client_id, $start, $end){
		$sql = "SELECT *, SUM(quantity) as total_withdrawn FROM tbl_withdraw_lot, tbl_withdrawal WHERE '$start'<=tbl_withdraw_lot.date_added AND '$end'>=tbl_withdraw_lot.date_added AND tbl_withdraw_lot.withdraw_id=tbl_withdrawal.withdraw_id AND tbl_withdrawal.client_id='$client_id' AND (description='Consignment' OR description='Withdraw Consignment')";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_withdrawn'];
		return $value;
	}

	public function get_client_report_sold($client_id, $start, $end){
		$sql = "SELECT *, SUM(quantity) as total_qty FROM tbl_invoice_item, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order WHERE '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added AND tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.client_id='$client_id' AND tbl_order.ordtype_id='11'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_qty'];
		return $value;
	}

	public function get_client_report_sales($client_id, $start, $end){
		$sql = "SELECT *, SUM(total) as total_sales FROM tbl_invoice_item, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order WHERE '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added AND tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.client_id='$client_id' AND tbl_order.ordtype_id='11'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sales'];
		return $value;
	}

	//END OF CONSIGNMENT

	//MANAGEMENT SALES REPORT
	public function get_client_received_sales($client_id, $start, $end){
		$sql = "SELECT *, SUM(payment_amount) as total_sales FROM tbl_payment, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order WHERE tbl_payment.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.order_id=tbl_ord_invoice.order_id AND tbl_order.client_id='$client_id' AND '$start'<=payment_date AND '$end'>=payment_date";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sales'];
		return $value;
	}


	//NEW STUFFS UPDATES
	public function get_supplied_specific($start, $end, $pro_id){
		$sql = "SELECT *, SUM(quantity) AS total_supplied FROM tbl_prod_supplier WHERE '$start'<=date_added AND '$end'>=date_added AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_supplied'];
		return $value;
	}

	public function get_delivered_specific($start, $end, $pro_id){
		$sql = "SELECT *, SUM(quantity) AS total_delivered FROM tbl_delivery_items WHERE '$start'<=date_added AND '$end'>=date_added AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_delivered'];
		return $value;
	}

	public function get_withdrawn_specific($start, $end, $pro_id){
		$sql = "SELECT *, SUM(quantity) AS total_withdrawn FROM tbl_withdraw_lot WHERE '$start'<=date_added AND '$end'>=date_added AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_withdrawn'];
		return $value;
	}

	public function get_disposed_specific($start, $end, $pro_id){
		$sql = "SELECT *, SUM(quantity) AS total_disposed FROM tbl_disposal WHERE '$start'<=date_disposed AND '$end'>=date_disposed AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_disposed'];
		return $value;
	}

	public function total_consign_specific($start, $end, $pro_id){
		$sql = "SELECT *, SUM(quantity) AS total_consigned FROM tbl_delivery_items, tbl_order, tbl_delivery WHERE '$start'<=tbl_delivery.date_delivered AND '$end'>=tbl_delivery.date_delivered AND tbl_delivery.delivery_status='1' AND pro_id='$pro_id' AND tbl_delivery.delivery_id=tbl_delivery_items.delivery_id AND tbl_order.order_id=tbl_delivery.order_id AND tbl_order.ordtype_id='11'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_consigned'];
		return $value;
	}

	public function total_consign_withdrawn_specific($start, $end, $pro_id){
		$sql = "SELECT SUM(tbl_withdraw_lot.quantity) AS total_withdrawn FROM tbl_withdraw_lot,tbl_withdrawal WHERE '$start'<=tbl_withdraw_lot.date_added AND '$end'>=tbl_withdraw_lot.date_added AND tbl_withdraw_lot.withdraw_id=tbl_withdrawal.withdraw_id AND (description='Consignment' OR description='Withdraw Consignment') AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_withdrawn'];
		return $value;
	}

	public function total_consign_sold_specific($start, $end, $pro_id){
		$sql = "SELECT *, SUM(tbl_invoice_item.quantity) AS total_sold FROM tbl_invoice_item, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice)tbl_ord_invoice, tbl_order WHERE pro_id='$pro_id' AND tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_order.order_id=tbl_ord_invoice.order_id AND ordtype_id='11' AND '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_sold'];
		return $value;
	}

	public function total_consign_value_specific($start, $end, $pro_id){
		$sql = "SELECT *, SUM(tbl_invoice_item.total) AS total_value FROM tbl_invoice_item, (SELECT DISTINCT(invoice_id), order_id FROM tbl_ord_invoice GROUP BY invoice_id)tbl_ord_invoice, tbl_order WHERE pro_id='$pro_id' AND tbl_invoice_item.invoice_id=tbl_ord_invoice.invoice_id AND tbl_ord_invoice.order_id=tbl_order.order_id AND tbl_order.ordtype_id='11' AND '$start'<=tbl_invoice_item.date_added AND '$end'>=tbl_invoice_item.date_added";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_value'];
		return $value;
	}

	public function get_instock_specific($start, $end, $pro_id){
		//ALL SUPPLIED HALIN SANG START OF BUSINESS UNTIL THE PREVIOUS KA DATE
		$sql = "SELECT SUM(quantity) as total_supplied FROM tbl_prod_supplier WHERE pro_id='$pro_id' AND date_added<'$start'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_supplied'];

		$sql1 = "SELECT SUM(quantity) as total_delivered FROM tbl_delivery_items WHERE pro_id='$pro_id' AND date_added<'$start'";
		$result1 = mysqli_query($this->db,$sql1);
		$row1 = mysqli_fetch_assoc($result1);
		$value1 = $row1['total_delivered'];

		$sql2 = "SELECT SUM(quantity) as total_return FROM tbl_withdraw_lot WHERE pro_id='$pro_id' AND date_added<'$start'";
		$result2 = mysqli_query($this->db,$sql2);
		$row2 = mysqli_fetch_assoc($result2);
		$value2 = $row2['total_return'];

		$sql3 = "SELECT SUM(quantity) as total_disposed FROM tbl_disposal WHERE pro_id='$pro_id' AND date_disposed<='$start'";
		$result3 = mysqli_query($this->db,$sql3);
		$row3 = mysqli_fetch_assoc($result3);
		$value3 = $row3['total_disposed'];

		return $value-$value1+$value2-$value3;
	}	

	public function get_committed_specific($start, $end, $pro_id){
		$sql = "SELECT SUM(qty_total) as total_orders FROM tbl_orditem,tbl_order WHERE pro_id='$pro_id' AND date_added<='$end' AND tbl_orditem.order_id=tbl_order.order_id AND (tbl_order.status='1' OR tbl_order.status='2' OR tbl_order.status='3')";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_orders'];

		$sql1 = "SELECT SUM(quantity) as total_delivery FROM tbl_delivery_items WHERE pro_id='$pro_id' AND date_added<='$start'";
		$result1 = mysqli_query($this->db,$sql1);
		$row1 = mysqli_fetch_assoc($result1);
		$value1 = $row1['total_delivery'];

		return $value-$value1;
	}


	public function get_nearexpiry_specific($start, $end, $pro_id){
		$sql = "SELECT SUM(tbl_prod_supplier.quantity) as total_supplied FROM tbl_prod_supplier,tbl_lot WHERE tbl_prod_supplier.pro_id='$pro_id' AND tbl_prod_supplier.date_added<='$end' AND tbl_prod_supplier.lot_number=tbl_lot.lot_number AND expiry_date<=DATE_ADD('$end', INTERVAL 3 MONTH)";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_supplied'];

		$sql1 = "SELECT SUM(tbl_delivery_items.quantity) as total_delivery FROM tbl_delivery_items,tbl_lot WHERE tbl_delivery_items.pro_id='$pro_id' AND tbl_delivery_items.date_added<='$end' AND tbl_delivery_items.lot_id=tbl_lot.lot_id AND expiry_date<=DATE_ADD('$end', INTERVAL 3 MONTH)";
		$result1 = mysqli_query($this->db,$sql1);
		$row1 = mysqli_fetch_assoc($result1);
		$value1 = $row1['total_delivery'];

		$sql2 = "SELECT SUM(tbl_withdraw_lot.quantity) as total_withdraw FROM tbl_withdraw_lot,tbl_lot WHERE tbl_withdraw_lot.pro_id='$pro_id' AND tbl_withdraw_lot.date_added<='$end' AND tbl_withdraw_lot.lot_id=tbl_lot.lot_id AND expiry_date<=DATE_ADD('$end', INTERVAL 3 MONTH)";
		$result2 = mysqli_query($this->db,$sql2);
		$row2 = mysqli_fetch_assoc($result2);
		$value2 = $row2['total_withdraw'];

		$sql3 = "SELECT SUM(tbl_disposal.quantity) as total_disposed FROM tbl_disposal,tbl_lot WHERE tbl_disposal.pro_id='$pro_id' AND tbl_disposal.date_disposed<='$end' AND tbl_disposal.lot_id=tbl_lot.lot_id AND expiry_date<=DATE_ADD('$end', INTERVAL 3 MONTH)";
		$result3 = mysqli_query($this->db,$sql3);
		$row3 = mysqli_fetch_assoc($result3);
		$value3 = $row3['total_disposed'];

		return $value-$value1+$value2-$value3;
	}
}