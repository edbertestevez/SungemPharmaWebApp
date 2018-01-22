<?php
class Mobile{
	public $db;

	public function __construct(){
		$this->db = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
		if(mysqli_connect_errno()){
			echo "Database connection error.";
			exit;
		}
	}
	
	public function updateDeliveryCount($medrep_id){
		$sql = "UPDATE tbl_mobile_update SET delivery_update=(delivery_update+1) WHERE medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	public function updatePaymentCount($medrep_id){
		$sql = "UPDATE tbl_mobile_update SET payment_update=(payment_update+1) WHERE medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	public function updateMonitorCount($medrep_id){
		$sql = "UPDATE tbl_mobile_update SET monitor_update=(monitor_update+1) WHERE medrep_id='$medrep_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

}