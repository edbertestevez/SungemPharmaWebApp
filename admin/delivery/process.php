<?php
include '../library/config.php';
include '../classes/class.delivery.php';
include '../classes/class.orders.php';
include '../classes/class.products.php';
include '../classes/class.payments.php';
include '../classes/class.mobile.php';
include '../classes/class.clients.php';

$delivery = new Delivery();
$orders = new Orders();
$products = new Products();
$payments = new Payments();
$mobile = new Mobile();
$clients = new Clients();

if(isset($_POST['load_delivery_search'])){
  $client_id = $_POST['client_id'];
  $list = $delivery->get_search_delivery($client_id);
  ?>
  <hr>
  <br/>
  <table class="table table-responsive table-striped table-hover" id="deliverylist">
  <div class="table-title"><?php echo $clients->get_client_name($client_id);?></div>
  <thead>
    <th class="column_one">Delivery #</th>
    <th class="column_two">Order #</th>
    <th class="column_three">Date Delivered</th>
    <th class="column_four">Time Delivered</th>          
    <th class="column_five">Client</th>
    <th class="column_six">Medrep</th>
  </thead>
  <tbody>
  <?php
  if($list){
    foreach($list as $values){
      ?>
      <tr class="selected_history" id=<?php echo $values['delivery_id'];?> onclick="popup_show_two()">
          <td class="column_one"><?php echo $values['delivery_id'];?></td>
          <td class="column_two"><?php echo $values['order_id'];?></td>
          <td class="column_three"><?php if($values['date_delivered']!='0000-00-00'){
              echo date('F d, Y', strtotime($values['date_delivered']));
              }else{
               echo "Pending";
              }
          ?>    
          </td>
          <td class="column_four"><?php if($values['time_delivered']!='00:00:00'){
                echo $values['time_delivered'];
                }else{
                echo "Pending";
               }
                ?>    
          </td>
          <td class="column_five"><?php echo $orders->get_order_clientname($values['order_id']);?></td>
          <td class="column_six"><?php echo $orders->get_order_medrep_name($values['order_id']);?></td>
          </tr>
      <?php
    }
  }
  ?>
  </tbody>
  <script>
            $('#deliverylist').dataTable(
                {language: {searchPlaceholder: "Search from list" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
  <?php
}

if(isset($_POST['display_date_history'])){
  ?>

<div id="sub-container">
  <div id="full-select">
      <div class="select-container select-with-button">
          <?php
          $clientlist = $clients->get_clients();
          if($clientlist){
            ?>
                <h3>Select Client:</h3>
                <select class="" name="delivery-client-select" id="delivery-client-select" style="width: 100%;">
                    <?php
                      foreach($clientlist AS $client_list){
                    ?>
                      <option value=<?php echo $client_list['client_id'];?>><?php echo $client_list['client_name'];?></option>
                    <?php
                      }
                    ?>
                </select>
                <?php
                  }else{
                    echo "No Clients Available";
                  }
                ?>
          <div class="material-button-wrapper">
          <input type="button" class="material-button-main" id="search_history_btn" value="Search">
          </div>
          </div>
          <br/>
    </div>
  </div>

 <div id="loading-screen-custom" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
  </div>
  <div id="subsub-container"></div>
  <?php
}

if(isset($_POST['update_delivery_status'])){
  $delivery_id = $_POST['delivery_id'];
  $or_num = $_POST['or_num'];
  $result = $delivery->update_pending_delivery($delivery_id, $or_num);

  //UPDATE ORDER STATUS (3) AS DELIVERED COMPLETE NA ONCE WLA NA UTANG NA ITEMS AND DELIVERED NA TANAN
  $order_id = $delivery->get_delivery_order($delivery_id);
  if(($orders->check_orders_complete($order_id) == 0) && ($orders->check_delivery_pending($order_id)==0)){
    $result = $delivery->update_order_delivered($order_id, $_SESSION['userid']);
  }
  

  //add counter para sa mobile
  $medrep_id = $delivery->get_delivery_medrep($delivery_id);
  $mobile -> updateDeliveryCount($medrep_id);

  //GENERATE INVOICE
  //CREATE INVOICE

  $ordtype = $orders->get_order_ordtype($order_id);
    if($ordtype==10){
      $delivery_total = $delivery->get_delivery_total($delivery_id);
      $order_term = $orders->get_order_term($order_id);
      $invoice_id = $payments->invoice_deliver_sold($order_id, $delivery_total, $order_term, $_SESSION['userid']);
      $payments->insert_order_invoice($invoice_id, $order_id, $_SESSION['userid']);
      $payments->create_sold_invoice($delivery_id, $invoice_id, $_SESSION['userid']);

      //add counter para sa mobile
      $mobile -> updatePaymentCount($medrep_id);

      echo json_encode("Invoice #".$invoice_id." Successfully Created"); 

      //create notif
      $client_id = $orders->get_order_client_id($order_id);
      $clients->insert_delivery_sold_notif($client_id, $order_id, $delivery_id, $invoice_id);

    }else if($ordtype==11){
      //create notif
      $client_id = $orders->get_order_client_id($order_id);
      $clients->insert_delivery_consigned_notif($client_id, $order_id, $delivery_id);
      echo json_encode("Consignment Record has been updated");
    }

    

}

if(isset($_POST['get_delivery_details'])){
  $delivery_id = $_POST['row_id'];
  $result = $delivery->get_selected_delivery($delivery_id);
  echo json_encode($result);
}

if(isset($_POST['show_details_table'])){
$delivery_id = $_POST['delivery_id'];
  $list = $delivery->get_delivered_orditems($delivery_id);
  ?>
  <table class="table light-border">
  <tr>
    <th>Product</th>
    <th>Formulation</th>
    <th>Packaging</th>
    <th>Lot No.</th>
    <th>Expiry Date</th>
    <th>Quantity</th>
  </tr>
  <?php
  if($list){
    foreach ($list as $value) {
    ?>
    <tr class="text_12">
      <td><?php echo $value['pro_brand']." ".$value['pro_generic'];?></td>
      <td><?php echo $value['pro_formulation'];?></td>
      <td><?php echo $value['pro_packaging'];?></td>
      <td><?php echo $value['lot_number'];?></td>
      <td><?php echo date('F d, Y', strtotime($value['expiry_date']));?></td>
      <td><?php echo $value['qty_delivered'];?></td>
    </tr>
    <?php
  }
  }
  ?>
  </table>
  <button type="button" class="material-button-main" onclick="popup_hide_two()" style="float: right;">Close</button>

  <?php
}

if(isset($_POST['show_details_table_pending'])){
$delivery_id = $_POST['delivery_id'];
  $list = $delivery->get_delivered_orditems($delivery_id);
  ?>
  <table class="table light-border">
  <tr>
    <th>Product</th>
    <th>Formulation</th>
    <th>Packaging</th>
    <th>Lot No.</th>
    <th>Expiry Date</th>
    <th>Quantity</th>
  </tr>
  <?php
  if($list){
    foreach ($list as $value) {
    ?>
    <tr class="text_12">
      <td><?php echo $value['pro_brand']." ".$value['pro_generic'];?></td>
      <td><?php echo $value['pro_formulation'];?></td>
      <td><?php echo $value['pro_packaging'];?></td>
      <td><?php echo $value['lot_number'];?></td>
      <td><?php echo date('F d, Y', strtotime($value['expiry_date']));?></td>
      <td><?php echo $value['qty_delivered'];?></td>
    </tr>
    <?php
  }
  }
  ?>
  </table>

  <?php
}

if(isset($_POST['validate_inputs'])){
  $prod_arr = $_POST['prod_arr']; //Specified products
  $qty_arr = $_POST['qty_arr']; //Total Lot numbers for specified products
  $row_prod = $_POST['row_prod']; //Product ID per line
  $prod_total_qty = $_POST['prod_total_qty']; //Total nga gn ORDER for specified products
  $row_lot = $_POST['row_lot']; //Lot ID per line
  $row_lot_qty = $_POST['row_lot_qty']; //Lot quantity per line
  $str = ""; //Error message
  $empty_ctr = 0;
  $ctr = count($row_lot);
  $ctr_prod = count($prod_arr);


  for($x=0; $x<$ctr-1; $x++){
    $lot_id = $row_lot[$x+1]; //+1 KAY MAY NULL VALUE NGA GA APPEAR SA 1ST VALUE KA ARRAY PRE
    $avail_lot = $products->get_specific_lot_qty($lot_id);
    $current_lot = $products->get_specific_lot_num($lot_id);
    if($lot_id==0){
      $str.="* No Available Lot for ".$products->get_specific_prodname($row_prod[$x])."\n";
    }
    if($row_lot_qty[$x]=="null"){
      $empty_ctr++;
    }

    if($empty_ctr==0){
      if($row_lot_qty[$x]>$avail_lot){
        $insufficient = $row_lot_qty[$x] - $avail_lot;
        $str.="* Lot No. ".$current_lot." for ".$products->get_specific_prodname($row_prod[$x])." is insufficient by ".$insufficient."\n"; 
      }
    }
  }

  for($i=0; $i<$ctr_prod; $i++){
    if($qty_arr[$i]==""){
      $qty_arr[$i]=0;
    }

    if($empty_ctr==0){
      if($prod_total_qty[$i]<$qty_arr[$i]){
        $str .= "* [".$products->get_specific_prodname($prod_arr[$i])."] exceeds by (".($qty_arr[$i]-$prod_total_qty[$i]).")\n";
      }
    }

    if($qty_arr[$i]<0){
      $str .= "* Negative quantity is not allowed. Please try again.\n";
    }
  }

  if($str=="" && $empty_ctr==0){
      $str="validated";
  }else{
    if($empty_ctr>0){
      $str.="* (Please fill up all Lot Qty fields)";
    }
  }
  echo $str;
}

if(isset($_POST['add_lot_row'])){
  $pro_id = $_POST['pro_id'];
  $array_selected=$_POST['selected'];
  $lotlist=$products->get_lot_select($array_selected, $pro_id);
  if(!$lotlist){
    return "error";
  }
  ?>
  <tr id=<?php echo $pro_id;?> class=<?php echo $pro_id;?> >
          <td class="td-other-smallest"></td>
          <td><input type="hidden" name="pro_id[]" value=<?php echo $pro_id;?>></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="td-other-medium row_lot_line">
            <?php
              if($lotlist){
                ?>
                <select name="lot[]" class=<?php echo "lot".$pro_id;?> style="cursor:pointer;">
                <?php
                foreach ($lotlist as $lotrow) {
              ?>
                <option value=<?php echo $lotrow['lot_id'];?>>Lot #<?php echo $lotrow['lot_number']." / (".$lotrow['expiry_date'].") / ".$lotrow['quantity'];?></option>
              <?php
                }
                ?>
                </select>
                <?php
              }
            ?>
          </td>
          <td><input type="number" style="text-align: center;" class=<?php echo "qty".$pro_id;?> name="lot_qty[]" value="1" min=1></td>
          <td><input type="hidden" name="discount[]" value="0"></td>
          <td><input type="button" class="lot_remove_btn" value="Remove"/></td>
        </tr>
        <script type="text/javascript">
           $('.<?php echo "qty".$pro_id;?>').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });

        </script>
    <?php
}

if(isset($_POST['display_pending_delivery'])){
  $list = $delivery->get_pending_delivery();
  ?>
            <div class="table-title add-marg">Pending Delivery</div>
                <table class="table table-responsive table-striped table-hover" id="pendinglist">
                  <thead>
                  <tr>
                    <th class="column_one">Delivery #</th>
                    <th class="column_two">Date Added</th>
                    <th class="column_three">Order #</th>
                    <th class="column_four">Client</th>
                    <th class="column_five">Medical Representative</th>
                  </tr>
                  </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr id=<?php echo $values['delivery_id'];?> class="selected_pending" onclick="popup_show();">
                        <td class="column_one"><?php echo $values['delivery_id'];?></td>
                        <td class="column_two"><?php echo date('F d, Y', strtotime($values['date_added']));?></td>
                        <td class="column_three"><?php echo $values['order_id'];?></td>
                        <td class="column_four"><?php echo $orders->get_order_clientname($values['order_id']);?></td>
                        <td class="column_five"><?php echo $orders->get_order_medrep_name($values['order_id']);?></td>
                      </tr>
                      <?php
                    }
                  ?>
                  </tbody>
                </table>
             <?php
              }
              ?>
            <script>
            $('#pendinglist').dataTable(
                {language: {searchPlaceholder: "Search Pending Delivery" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['display_history_delivery'])){
  $start = $_POST['date_start'];
  $end = $_POST['date_end'];
  $list = $delivery->get_deliveryhistory($start, $end);
  ?>
        <hr>
          <br/>
            <div class="table-title add-marg">Delivery History ( <?php echo $start." - ".$end;?> )</div>
                <table class="table table-responsive table-striped table-hover" id="historylist">
                  <thead>
                  <tr>
                    <th class="column_one">Delivery #</th>
                    <th class="column_two">Order #</th>
                    <th class="column_three">Date Delivered</th>
                    <th class="column_four">Time Delivered</th>          
                    <th class="column_five">Client</th>
                    <th class="column_six">Medrep</th>
                  </tr>
                  </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr class="selected_history" id=<?php echo $values['delivery_id'];?> onclick="popup_show_two()">
                        <td class="column_one"><?php echo $values['delivery_id'];?></td>
                        <td class="column_two"><?php echo $values['order_id'];?></td>
                        <td class="column_three"><?php if($values['date_delivered']!='0000-00-00'){
                                    echo date('F d, Y', strtotime($values['date_delivered']));
                                  }else{
                                    echo "Pending";
                                  }
                          ?>    
                        </td>
                        <td class="column_four"><?php if($values['time_delivered']!='00:00:00'){
                                    echo $values['time_delivered'];
                                  }else{
                                    echo "Pending";
                                  }
                          ?>    
                        </td>
                        <td class="column_five"><?php echo $orders->get_order_clientname($values['order_id']);?></td>
                        <td class="column_six"><?php echo $orders->get_order_medrep_name($values['order_id']);?></td>
                      </tr>
                      <?php
                    }
                  ?>
                  </tbody>
                </table>
             <?php
              }
              ?>
            <script>
            $('#historylist').dataTable(
                {language: {searchPlaceholder: "Search Delivery" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['display_create_delivery'])){
  ?>
  <div id="full-select">
    <div class="select-container select-with-button" >
        <h3>Select Order Number:</h3>
        <select class="order-select" name="order" id="select-pending-order" style="width: 100%;">
            <?php
                $ordlist = $orders->get_pending_ordnums();
                if($ordlist){
                foreach($ordlist AS $ordrow){ 
            ?>
              <option value=<?php echo $ordrow['order_id'];?>><?php echo $ordrow['order_id']." (".$ordrow['client_name'].")";?></option>
            <?php
              }
            }else{
            ?>
              <option value="" selected disabled>NO PENDING ORDERS</option>
            <?php
            }
            ?>
        </select>
        </div>
        <div class="material-button-wrapper">
        <button class="material-button-main" id="load-order-btn">Load Orders</button>
        </div>
    </div>
    
  <div id="pending_table" class="subsub-container"></div>
    <?php
}

if(isset($_POST['load_pending'])){
  $id = $_POST['order_id'];
  $pending = $orders->get_orditems($id);
  $order_selected = $orders->get_order_row($id);
  ?>
  <br/>
    <div class="table-title-medium">
      <?php echo "Order No. ".$id." </br>Client: ".$order_selected['client_name']." </br>Type: ".$order_selected['ordtype_name']; ?>
      </div>
    <form method="POST" id="form_delivery" name="form_delivery" style="margin-top:20px;">
    <input type="hidden" value="1" name="delivery_create"/>
    <input type="hidden" value=<?php echo $id?> name="selected_ord_id"/>
    <table class="table table-responsive table-striped light-border" id="pendingtable">
      <thead>
        <th class="center normal">#</th>
        <th class="normal">Product Name</th>
        <th class="normal column_three">Formulation</th>
        <th class="ta-right column_four">Unit Price</th>
        <th class="ta-right column_five" style="width: 125px;">Total Order Qty</th>
        <th class="center column_six">Lot No. / Expiry / Remaining Qty</th>
        <th class="ta-right column_six">Lot Qty</th>
        <th class="ta-right column_seven">Discount (%)</th>
        <th class="normal column_seven">Action</th>
      </thead>
      <tbody>
      <?php
      $row_ctr=1;
      foreach($pending as $value){
        ?>
        <tr id=<?php echo $value['pro_id']; ?> class=<?php echo $value['pro_id']; ?> >
          <td class="td-other-smallest"><?php echo $row_ctr; ?></td>
          <td class="td-prod-medium" style="width: 120px;max-width: 120px;"><input type="hidden" name="pro_id[]" value=<?php echo $value['pro_id']; ?> > <?php echo $value['pro_brand']."-".$value['pro_generic']; ?></td>
          <td class="td-other-medium column_three" style="width: 140px;max-width: 140px;"><?php echo $value['pro_formulation']; ?></td>
          <td class="td-other-small ta-right column_four"><?php echo $value['pro_unit_price']; ?></td>
          <td class="td-other-small ta-right column_five"><input type="hidden" class="pro_total_row" value=<?php echo $value['qty_total']; ?> > <?php echo $value['qty_total']; ?></td>
          <td class="td-other-medium row_lot_line column_six" style="padding-top: 10px;width:300px;max-width: 300px;">
            <?php
              $lotlist=$products->get_specific_available_lots($value['pro_id']);
              $lotlist_ctr=0;
              $lotlist_qty=0;
              if($lotlist){
                ?>
                <select name="lot[]" class=<?php echo "lot".$value['pro_id'];?> style="width:100%; padding:8px;cursor:pointer;">
                <?php
                foreach ($lotlist as $lotrow) {
                  $lotlist_ctr++;
                  $lotlist_qty+=$lotrow['quantity'];
              ?>
                <option value=<?php echo $lotrow['lot_id']; ?>>Lot # <?php echo $lotrow['lot_number']." / (".$lotrow['expiry_date'].") / ".$lotrow['quantity']; ?></option>
              <?php
                }
                ?>
                </select>
                <?php
              }else{
                ?>
                <select name="lot[]" class="bg_red" style="width:100%;padding: 8px;cursor:pointer;">
                  <option value="" selected>No Available Lot No.</option>
                </select>
                <?php
              }
            ?>
          </td>
          <td class="td-other-small center column_six"><input  style="width: 50px; text-align: center; max-width:50px; height: 28px;margin-top:-2px;" type="number" class=<?php echo "qty".$value['pro_id']; ?> value="1" min=1 name="lot_qty[]" id="log_qty_valid"></td>
          <td class="td-other-small column_seven"><input  style="width: 50px; text-align: center; max-width:50px; height: 28px;margin-top:-2px;" class="discount_perc" type="number" name="discount[]" min="0" value="0" placeholder="Discount" onchange="handleChange(this);" /></td>
          <td class="td-other-small column_seven"  style="width: 80px;max-width: 80px;">
            <?php
            if($lotlist_ctr=1 && $lotlist_qty>=$value['qty_total']){
              echo "N/A";
            }else{
              ?>
              <input type="button" class="btn_add_lot" value="Add Lot">
            <?php
            }
            ?>
            
          </td>
        </tr>


     <script>

     function handleChange(input){
      if(input.value < 0) input.value = 0;
      if(input.value > 100) input.value = 100;
     }


      $('.discount_perc').on('keypress', function(e){

      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
     
    }); 


      $('.<?php echo "qty".$value['pro_id']; ?>').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });
</script>

        <?php 
        $row_ctr++;
      }
      ?>
      </tbody>
      </table>
<div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" value="Save" id="save_delivery" />
      </div>
      </form>


<?php
}

if(isset($_POST['delivery_create'])){
  $order_id = $_POST['selected_ord_id'];
  $lot = $_POST['lot'];
  $pro_id = $_POST['pro_id'];
  $qty = $_POST['lot_qty'];
  $discount = $_POST['discount'];
  $ctr_main = count($lot);
  $ordtype = $orders->get_order_ordtype($order_id);
  $current_product = "";
  $current_discount=0;
  $ctr_lot = count(array_filter($lot));
  $ctr_discount = count(array_filter($discount,"valid_number"));


  if($ctr_main==$ctr_lot){
    //INSERT TO DELIVERY WITH STATUS 0 PENDING
    $medrep_id = $orders->get_order_medrep($order_id);

    $delivery_id = $delivery -> add_delivery($order_id, $medrep_id, $_SESSION['userid']);

    //add counter para sa mobile
    $mobile -> updateDeliveryCount($medrep_id);

    for($x=0; $x<$ctr_main; $x++){
      if($current_product==""){
        $current_discount=$discount[$x];
        $current_product=$pro_id[$x];

        //UPDATE ORDER ITEMS LOT NUMBER tbl_orditem
        $delivery->update_orditem_lot($order_id, $ordtype, $pro_id[$x], $lot[$x], $qty[$x], $current_discount, $_SESSION['userid']);

        //INSERT DELIVERY ITEMS UNDER THIS DELIVERY
        $unit_price = $products->get_unit_price($pro_id[$x]);
        $delivery->insert_delivery_item($delivery_id,$pro_id[$x],$lot_id[$x],$unit_price,$qty[$x],$current_discount);

      }else if($current_product==$pro_id[$x]){
        $current_discount=$current_discount;
        $delivery->append_orditem($order_id, $ordtype, $pro_id[$x], $lot[$x], $qty[$x], $current_discount, $_SESSION['userid']);
      }else if($current_product!=$pro_id[$x]){
        $current_discount=$discount[$x];
        $delivery->update_orditem_lot($order_id, $ordtype, $pro_id[$x], $lot[$x], $qty[$x], $current_discount, $_SESSION['userid']);
      }
      //UPDATE ACTUAL QUANTITY OF LOT NUMBER
      $products->update_delivered_lot($lot[$x], $qty[$x]);
      //UPDATE ACTUAL QUANTITY OF TOTAL PRODUCT
      $products->update_delivered_product($pro_id[$x],$qty[$x],$_SESSION['userid']);
    }
    //UPDATE ORDER TOTAL 
    $orders->update_total($order_id);
    //UPDATE ORDER STATUS tbl_order
    $delivery->update_order_status($order_id, $_SESSION['userid']);
  }
  else{
    echo "unable";
  }
}

function valid_number($arr) {
    return ($arr['discount'] >= 0);
}
exit();
?>