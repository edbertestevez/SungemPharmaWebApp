<?php
class Graph{
	public $db;
	
	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}

	public function get_json_clients(){
		$sql = "SELECT area_name, tbl_area.area_id, tbl_client.area_id, COUNT(client_id) AS total_client from tbl_client, tbl_area WHERE tbl_client.area_id=tbl_area.area_id GROUP BY tbl_area.area_id";
		
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

	//BASED ON MEDICINES INVOICE
	public function get_json_year_sales(){
		$year_today = date("Y");
		$sql = "SELECT DISTINCT MONTH(date_issued) AS month_selected, SUM(total_amount) as month_sales FROM tbl_invoice WHERE YEAR(date_issued)='$year_today' GROUP BY month_selected";
		
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

	//BASED ON RECEIVED PAYMENTS
	public function get_json_year_actual_sales(){
		$year_today = date("Y");
		$sql = "SELECT DISTINCT MONTH(payment_date) AS month_selected, SUM(payment_amount) as month_collection FROM tbl_payment WHERE YEAR(payment_date)='$year_today' GROUP BY month_selected";
		
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

	public function get_json_max_month(){
		$year_today = date("Y");
		$sql = "SELECT MAX(MONTH(date_issued)) AS max_month FROM tbl_invoice WHERE YEAR(date_issued)='$year_today'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['max_month'];
		return $value;
	}

	public function get_json_top_medsales(){
		$sql = "SELECT pro_brand, pro_generic, pro_packaging, pro_formulation, tbl_invoice_item.date_added, tbl_product.pro_id, tbl_invoice_item.pro_id, SUM(subtotal) AS med_sales FROM tbl_invoice_item, tbl_product WHERE MONTH(tbl_invoice_item.date_added)=Month(CURRENT_TIMESTAMP) AND YEAR(tbl_invoice_item.date_added)=Year(CURRENT_TIMESTAMP) AND tbl_invoice_item.pro_id = tbl_product.pro_id GROUP BY tbl_invoice_item.pro_id ORDER BY med_sales DESC LIMIT 5";
		
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

	public function get_json_medrep_sales(){
		$sql = "SELECT *, SUM(payment_amount) as total_sales FROM tbl_payment RIGHT OUTER JOIN tbl_invoice ON tbl_invoice.invoice_id=tbl_payment.invoice_id RIGHT OUTER JOIN tbl_ord_invoice ON tbl_invoice.invoice_id=tbl_ord_invoice.invoice_id RIGHT OUTER JOIN tbl_order ON tbl_order.order_id=tbl_ord_invoice.order_id RIGHT OUTER JOIN tbl_client ON tbl_client.client_id = tbl_order.client_id RIGHT OUTER JOIN (SELECT * FROM tbl_medrep WHERE status='1')tbl_medrep ON tbl_medrep.medrep_id=tbl_client.medrep_id GROUP BY tbl_medrep.medrep_id ORDER BY total_sales DESC";
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


}