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

  public function get_json_consigned($id){
		$year_today = date("Y");
		$sql = "SELECT DISTINCT MONTH(date_delivered) AS month_selected, SUM(tbl_orditem.qty_total) AS month_sales FROM tbl_order,tbl_delivery, tbl_orditem WHERE tbl_order.order_id = tbl_delivery.order_id AND tbl_delivery.delivery_status = 1 AND tbl_orditem.order_id=tbl_order.order_id AND ordtype_id = 11 AND client_id = '$id' AND YEAR(date_delivered)='$year_today' GROUP BY month_selected";
		

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

	public function get_json_sold($id){
		$year_today = date("Y");
		$sql = "SELECT DISTINCT MONTH(date_delivered) AS month_selected, SUM(tbl_orditem.qty_total) AS month_sales FROM tbl_order,tbl_delivery, tbl_orditem WHERE tbl_order.order_id = tbl_delivery.order_id AND tbl_delivery.delivery_status = 1 AND tbl_orditem.order_id=tbl_order.order_id AND ordtype_id = 10 AND client_id = '$id' AND YEAR(date_delivered)='$year_today' GROUP BY month_selected";
		

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
  
}
?>