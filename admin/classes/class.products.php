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
	
	public function search_barcode($value){
		if($value!=""){
			$sql = "SELECT * FROM tbl_product WHERE barcode='$value'";
			$result = mysqli_query($this->db,$sql);
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
	        }else{
	            $row=false;
	        }
	                
			return $row;	
		}else{
			return 0;
		}
		
	}

	public function get_product_select_info($pro_id){
		$sql = "SELECT * FROM tbl_product WHERE pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function get_products(){
		$sql = "SELECT *, tbl_product.status as product_status, cat_name from tbl_product, tbl_category where  tbl_product.cat_id=tbl_category.cat_id order by pro_brand";
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

	public function get_products_active(){
		$sql = "SELECT *, tbl_product.status as product_status, cat_name from tbl_product, tbl_category where  tbl_product.cat_id=tbl_category.cat_id AND tbl_product.status=1 order by pro_brand";
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

	public function get_products_selected_order($query){
		$sql = "SELECT *, tbl_product.status as product_status, cat_name from tbl_product, tbl_category where  tbl_product.cat_id=tbl_category.cat_id AND $query AND tbl_product.status=1 order by pro_brand";
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

	public function get_products_select($array_selected){
		$selected = explode(",", $array_selected);
		$ctr_selected = count($selected);
		$str_selected = "";
		for($x=0;$x<$ctr_selected;$x++){
			if($x<$ctr_selected-1){
				$str_selected.="pro_id!='".$selected[$x]."' AND ";
			}else{
				$str_selected.="pro_id!='".$selected[$x]."' ";
			}
		}
		$sql = "SELECT * from tbl_product where status='1' AND ".$str_selected." order by pro_brand";
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

	public function get_category(){
		$sql = "SELECT * from tbl_category where status='1' order by cat_name";
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

	public function get_specific_category($id){
		$sql = "SELECT * from tbl_category where status='1' AND cat_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return json_encode($row);
	}

	public function get_specific_product($id){
		$sql = "SELECT * from tbl_product WHERE pro_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return json_encode($row);
	}
	
	public function get_product_packaging($id){
		$sql = "SELECT pro_packaging from tbl_product where pro_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['pro_packaging'];
		return $value;
	}

	public function add_product($brandname, $genericname, $category, $formulation, $packaging, $price, $reorder, $barcode){
	$sqlName = "SELECT * FROM tbl_product WHERE (pro_brand = '$brandname' || pro_generic = '$genericname' ) AND pro_formulation='$formulation' AND status='1'";
	$checkName=$this->db->query($sqlName);
	$count_row_name = $checkName->num_rows;
		if($count_row_name == 0){
			$sqlBarcode = "SELECT * FROM tbl_product WHERE barcode='$barcode'";
			$checkBarcode=$this->db->query($sqlBarcode);
			$count_row_barcode = $checkBarcode->num_rows;
			if($count_row_barcode==0){
				$sql = "INSERT into tbl_product(barcode, pro_brand, pro_generic, cat_id, pro_formulation, pro_packaging, pro_unit_price, pro_reorder_level, date_added, time_added, status) VALUES ('$barcode', '$brandname', '$genericname', '$category', '$formulation','$packaging', '$price', '$reorder', NOW(), NOW(), 1)";

				$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
				
			}else{
				$result = "barcode";	
			}
		}else{
			$result = "name";
		}

		return json_encode($result);
	}

	public function add_category($catname, $description){
	$sql = "SELECT * FROM tbl_category WHERE cat_name = '$catname' AND status='1'";
	$check=$this->db->query($sql);
	$count_row = $check->num_rows;
		if($count_row == 0){
		$sql = "INSERT into tbl_category(cat_name, description, date_added, time_added) VALUES ('$catname', '$description', NOW(), NOW())";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
		}
		else{
			$result = "unable";
			return json_encode($result);
		}	
	}
	
	public function update_category($id, $catname, $description){
		$sql = "UPDATE tbl_category SET cat_name='$catname', description='$description', date_modified=NOW(), time_modified=NOW() where cat_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}	

	public function remove_category($id){
		$sql = "UPDATE tbl_category SET status='0', date_modified=NOW(), time_modified=NOW() where cat_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function update_product($id,$brandname, $genericname, $category, $formulation, $packaging, $price, $reorder, $barcode){
		if($barcode!=''){
			$sqlBarcode = "SELECT * FROM tbl_product WHERE barcode='$barcode' AND pro_id!='$id'";
			$checkBarcode=$this->db->query($sqlBarcode);
			$count_row_barcode = $checkBarcode->num_rows;
			if($count_row_barcode==0){
				$sql = "UPDATE tbl_product SET barcode='$barcode', pro_brand='$brandname', pro_generic='$genericname', pro_packaging='$packaging', pro_formulation='$formulation', pro_unit_price='$price', pro_reorder_level='$reorder', cat_id='$category', date_modified=NOW(), time_modified=NOW() where pro_id='$id'";
				$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
				return $result;
			}else{
				return "barcode";
			}	
		}else{
			$sql = "UPDATE tbl_product SET barcode='$barcode', pro_brand='$brandname', pro_generic='$genericname', pro_packaging='$packaging', pro_formulation='$formulation', pro_unit_price='$price', pro_reorder_level='$reorder', cat_id='$category', date_modified=NOW(), time_modified=NOW() where pro_id='$id'";
				$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
				return $result;
		}
		
	}	

	public function remove_product($id){
		$sql = "UPDATE tbl_product SET status='0', date_modified=NOW(), time_modified=NOW() where pro_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	public function activate_product($id){
		$sql = "UPDATE tbl_product SET status='1', date_modified=NOW(), time_modified=NOW() where pro_id='$id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Record");
		return $result;
	}

	//FOR THE STOCKS MODULE
	public function get_stocks(){
		$sql = "SELECT * from tbl_product WHERE status='1' ORDER BY pro_brand";
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

	//PRODUCT SUPPLIED TABLE
	public function add_prod_supplied($supplier, $product, $lot, $quantity, $user){
		$sql = "INSERT INTO tbl_prod_supplier(supplier_id, pro_id, lot_number, quantity, date_added, time_added, usr_id) VALUES ('$supplier', '$product', '$lot', '$quantity', NOW(), NOW(), '$user')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}

	//LOT TABLE
	public function add_lot($lot, $product, $date, $quantity){
		$sql_check = "SELECT * FROM tbl_lot WHERE lot_number ='$lot'";
		$check=$this->db->query($sql_check);
		$count_row = $check->num_rows;	
		if($count_row==0){
			$sql="INSERT INTO tbl_lot (lot_number, pro_id, expiry_date, quantity) VALUES ('$lot', '$product', '$date', '$quantity')"; //INSERT NEW LOT
			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		}else{
			$sql="UPDATE tbl_lot SET quantity=quantity + '$quantity' WHERE lot_number='$lot'";//UPDATE QUANTITY 
			$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		}
	}

	//UPDATE QUANTITY OF SUPPLIED PRODUCT ON TBL_PRODUCT
	public function add_prod_quantity($product, $quantity){
		$sql = "UPDATE tbl_product SET pro_total_qty = pro_total_qty + '$quantity' WHERE pro_id='$product'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	//GET LOT NUMBERS AVAILABLE FOR A SELECTED CREATED DELIVERY
	public function get_specific_available_lots($id){
		$sql = "SELECT * from tbl_lot WHERE pro_id='$id' AND quantity>'0' ORDER BY expiry_date";
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

	//GET LOT NUMBERS AVAILABLE FOR A SELECTED CREATED DELIVERY
	public function get_specific_available_lots_not_expiring($id){
		$sql = "SELECT * from tbl_lot WHERE pro_id='$id' AND quantity>'0' AND expiry_date>=DATE_ADD(CURDATE(), INTERVAL 3 MONTH) ORDER BY expiry_date";
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

	//UPDATE LOT QUANTITY FOR DELIVERY
	public function update_delivered_lot($lot, $qty){
		$sql = "UPDATE tbl_lot SET quantity = quantity - '$qty' WHERE lot_id='$lot'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	//UPDATE PRODUCT'S TOTAL QUANTITY FOR DELIVERY
	public function update_delivered_product($pro_id, $qty, $usr_id){
		$sql = "UPDATE tbl_product SET pro_total_qty = pro_total_qty - '$qty', usr_id='$usr_id', date_modified=NOW(), time_modified=NOW() WHERE pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	//LOT SELECTED SA DELIVERY
	public function get_lot_select($array_selected, $pro_id){
		$selected = explode(",", $array_selected);
		$ctr_selected = count($selected);
		$str_selected = "";
		for($x=0;$x<$ctr_selected;$x++){
			if($x<$ctr_selected-1){
				$str_selected.="lot_id!='".$selected[$x]."' AND ";
			}else{
				$str_selected.="lot_id!='".$selected[$x]."' ";
			}
		}
		$sql = "SELECT * from tbl_lot where pro_id='$pro_id' AND quantity>0 AND ".$str_selected." order by expiry_date";
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

	public function get_specific_lot_qty($id){
		$sql = "SELECT quantity from tbl_lot where lot_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['quantity'];
		return $value;
	}


	public function get_specific_lot_num($id){
		$sql = "SELECT lot_number from tbl_lot where lot_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['lot_number'];
		return $value;
	}

	public function get_specific_prodname($id){
		$sql = "SELECT pro_brand, pro_formulation from tbl_product where pro_id='$id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['pro_brand'].$row['pro_formulation'];
		return $value;
	}

	//INSERT DISPOSAL
	public function insert_disposal($pro_id, $lot_id, $qty, $reason, $usr_id){
		$sql = "INSERT INTO tbl_disposal (pro_id, lot_id, quantity, reason, date_disposed, time_disposed, usr_id) VALUES ('$pro_id', '$lot_id', '$qty', '$reason', NOW(), NOW(), '$usr_id')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Insert Data");
		return $result;
	}

	//DISPOSE UPDATE product total (MINUS)
	public function dispose_prod_quantity($product, $quantity, $usr_id){
		$sql = "UPDATE tbl_product SET pro_total_qty = pro_total_qty - '$quantity', date_modified=NOW(), time_modified=NOW(), usr_id='$usr_id' WHERE pro_id='$product'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}
	//DISPOSE UPDATE lot total (MINUS)
	public function dispose_lot_quantity($lot, $quantity){
		$sql = "UPDATE tbl_lot SET quantity = quantity - '$quantity' WHERE lot_id='$lot'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	//NOTIF SA DASHBOARD
	public function get_count_expiry_notif(){
		//1 month before its expiry na d pre ka STOCKS EXPIRY
		$sql = "SELECT SUM(quantity) AS total_expiry from tbl_lot where expiry_date<=DATE_ADD(CURDATE(), INTERVAL 3 MONTH) AND quantity>0";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_expiry'];
		return $value;
	}

	public function get_count_expiry_consign(){
		//1 month before its expiry na d pre ka STOCKS EXPIRY
		$sql = "SELECT *, SUM(qty_remaining) AS total_expiry from tbl_lot, tbl_orditem, tbl_order, tbl_delivery where expiry_date<=DATE_ADD(CURDATE(), INTERVAL 3 MONTH) AND qty_remaining>0 AND tbl_order.order_id=tbl_orditem.order_id AND tbl_order.ordtype_id='11' AND tbl_delivery.order_id=tbl_order.order_id AND tbl_delivery.delivery_status='1' AND tbl_order.status='2' AND tbl_orditem.lot_id = tbl_lot.lot_id";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_expiry'];
		return $value;
	}

	public function get_count_restock_notif(){
		$sql = "SELECT * FROM tbl_product WHERE status='1'";
		$result = mysqli_query($this->db,$sql);
		while($row = mysqli_fetch_assoc($result)){
			$list[] = $row;
		}
        $ctr = count($list);
        $ctr_restock = 0;
        for($x=0; $x<$ctr; $x++){
        	$cur_prod = $list[$x]['pro_id'];
        	$cur_qty = $list[$x]['pro_total_qty'];
        	$sqlord = "SELECT *, SUM(qty_total) as ord_count, SUM(qty_delivery) as ord_del FROM tbl_order, tbl_orditem WHERE tbl_orditem.order_id=tbl_order.order_id AND tbl_order.status='0' AND pro_id='$cur_prod'";
			$resultord = mysqli_query($this->db,$sqlord);
			$roword = mysqli_fetch_assoc($resultord);
			$cur_ordtotal = $roword['ord_count']-$roword['ord_del'];
        	if(($cur_qty-($cur_ordtotal)<=$list[$x]['pro_reorder_level'])){
        		$ctr_restock++;
        	}
        }
        return $ctr_restock;
	}

	public function get_lot_id($lot_num){
		//1 month before its expiry na d pre ka STOCKS EXPIRY
		$sql = "SELECT lot_id from tbl_lot where lot_number='$lot_num'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['lot_id'];
		return $value;
	}

	public function get_lot_details($lot_id){
		//1 month before its expiry na d pre ka STOCKS EXPIRY
		$sql = "SELECT * from tbl_lot where lot_id='$lot_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	public function get_unit_price($pro_id){
		//1 month before its expiry na d pre ka STOCKS EXPIRY
		$sql = "SELECT pro_unit_price from tbl_product where pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['pro_unit_price'];
		return $value;
	}

	//WITHDRAWN PRODUCT (Add balik)
	public function add_withdrawn_prod($pro_id, $quantity){
		$sql = "UPDATE tbl_product SET pro_total_qty=pro_total_qty+'$quantity' WHERE pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	//WITHDRAWN LOT (Add Balik)
	public function add_withdrawn_lot($lot_id, $quantity){
		$sql = "UPDATE tbl_lot SET quantity=quantity+'$quantity' WHERE lot_id='$lot_id'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error() . "Cannot Update Data");
		return $result;
	}

	//RESTOCK NOTIF CONTENT
	//PENDING SANG KDA ROW KA PRODUCT
	public function get_restock_notif_list($pro_id){
		$sql = "SELECT *, SUM(qty_total) as total_ordered, SUM(qty_delivery) as total_delivered FROM tbl_order, tbl_orditem WHERE tbl_order.order_id=tbl_orditem.order_id AND pro_id='$pro_id' AND (tbl_order.status='1' OR tbl_order.status='2')";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_ordered']-$row['total_delivered'];
                
		return $value;
	}

	public function get_expiring_notif_list($pro_id){
		$sql = "SELECT *, SUM(quantity) as total_expiring FROM tbl_lot WHERE expiry_date<=DATE_ADD(CURDATE(), INTERVAL 3 MONTH) AND pro_id='$pro_id'";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_assoc($result);
		$value = $row['total_expiring'];
                
		return $value;
	}

	//EXPIRING PRODUCTS SA STOCKS
	public function get_expiring_products(){
		$sql = "SELECT * FROM tbl_lot, tbl_product where expiry_date<=DATE_ADD(CURDATE(), INTERVAL 3 MONTH) AND quantity>0 AND tbl_lot.pro_id = tbl_product.pro_id GROUP BY tbl_lot.lot_id";
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

	//EXPIRING CONSIGMENT PRODUCTS
	public function get_expiring_products_consignment(){
		$sql = "SELECT *,SUM(tbl_orditem.qty_remaining) AS lot_total FROM tbl_lot, tbl_orditem, tbl_client, tbl_product, tbl_order, tbl_delivery where expiry_date<=DATE_ADD(CURDATE(), INTERVAL 3 MONTH) AND qty_remaining>0 AND tbl_order.order_id=tbl_orditem.order_id AND tbl_order.ordtype_id='11' AND tbl_orditem.lot_id = tbl_lot.lot_id AND tbl_lot.pro_id = tbl_product.pro_id AND tbl_order.status='2' AND tbl_client.client_id = tbl_order.client_id AND tbl_order.order_id=tbl_delivery.order_id AND tbl_delivery.delivery_status='1' GROUP BY tbl_client.client_id, tbl_orditem.lot_id";
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

	public function get_stock_card($pro_id){
		//DEFAULT
		$list=false;

		//SUPPLIED-IN
		$sql = "SELECT supplier_name as from_name,tbl_prod_supplier.date_added AS date_added, tbl_lot.lot_number AS lot_number, tbl_lot.expiry_date AS expiry_date, SUM(tbl_prod_supplier.quantity) AS qty_in FROM tbl_prod_supplier,tbl_supplier, tbl_lot WHERE tbl_lot.lot_number=tbl_prod_supplier.lot_number AND tbl_supplier.supplier_id=tbl_prod_supplier.supplier_id AND tbl_prod_supplier.pro_id = '$pro_id' GROUP BY tbl_lot.lot_number, supplier_name, tbl_prod_supplier.date_added ORDER by tbl_prod_supplier.date_added";
		$result = mysqli_query($this->db,$sql);
        if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }

        //DISPOSED-OUT
        $sql = "SELECT date_disposed AS date_added, lot_number, expiry_date, SUM(tbl_disposal.quantity) AS qty_out FROM tbl_disposal, tbl_lot WHERE tbl_disposal.lot_id=tbl_lot.lot_id AND tbl_disposal.pro_id='$pro_id' GROUP BY tbl_disposal.lot_id, date_disposed ORDER BY date_disposed";
		$result = mysqli_query($this->db,$sql);
        if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }

        //WITHDRAWN-IN
        $sql = "SELECT client_name AS from_name, tbl_withdraw_lot.date_added AS date_added,  expiry_date, lot_number, SUM(tbl_withdraw_lot.quantity) AS qty_in FROM tbl_withdrawal,tbl_withdraw_lot, tbl_lot, tbl_client WHERE tbl_withdrawal.withdraw_id=tbl_withdraw_lot.withdraw_id AND tbl_withdraw_lot.pro_id='$pro_id' AND tbl_withdraw_lot.lot_id=tbl_lot.lot_id AND tbl_withdrawal.client_id=tbl_client.client_id GROUP BY tbl_withdraw_lot.lot_id, tbl_withdrawal.client_id, tbl_withdraw_lot.date_added ORDER BY tbl_withdraw_lot.date_added";
		$result = mysqli_query($this->db,$sql);
        if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }

        //DELIVERED-OUT
        $sql = "SELECT client_name AS from_name, tbl_delivery.date_added AS date_added,  expiry_date, lot_number, SUM(tbl_orditem.qty_total) AS qty_out FROM tbl_orditem, tbl_order, tbl_delivery, tbl_lot, tbl_client WHERE tbl_delivery.order_id=tbl_order.order_id AND tbl_order.order_id=tbl_orditem.order_id AND tbl_orditem.pro_id='$pro_id' AND tbl_orditem.lot_id=tbl_lot.lot_id AND tbl_order.client_id=tbl_client.client_id GROUP BY tbl_orditem.lot_id, tbl_order.client_id, tbl_delivery.date_added ORDER BY tbl_delivery.date_added";
		$result = mysqli_query($this->db,$sql);
        if (mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$list[] = $row;
			}
        }
                
		return $list;
	}
}