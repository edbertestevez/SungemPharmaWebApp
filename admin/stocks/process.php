<?php
include '../library/config.php';
include '../classes/class.products.php';
include '../classes/class.clients.php';
include '../classes/class.suppliers.php';
include '../classes/class.orders.php';
include '../classes/class.mobile.php';

$mobile = new Mobile();
$products = new Products();
$suppliers = new Suppliers();
$orders = new Orders();
$clients = new Clients();

//UPDATED
if(isset($_POST['load_sold_orders'])){
  $client_id = $_POST['client_id'];
  $ordlist = $orders->get_client_delivered_sold_orders($client_id);
  
  if($ordlist){
  ?>
<div id="full-select">
    <div class="select-container select-with-button" >
        <h3>Select Order No.:</h3>
        <select class="order-select" name="order" id="select-order" style="width: 100%;">
            <?php
                foreach($ordlist AS $ordrow){ 
            ?>
              <option value=<?php echo $ordrow['order_id'];?>><?php echo $ordrow['order_id'];?></option>
            <?php
              }
            ?>
        </select>
        </div>
        <div class="material-button-wrapper">
        <button class="material-button-main" id="load_orders_btn">Load Items</button>
        </div>
    </div>

    <!--CONTAINER SANG ITEMS-->

    <div id="items-container"></div>
  <?php
}else{
  echo "No Goods from orders to be returned";
}
}

//VIRTUAL STOCK CARD
if(isset($_POST['view_stock_card'])){
  $pro_id = $_POST['pro_id'];
  $row = $products->get_product_select_info($pro_id);
  ?>
<div class="table-title">Digital Stock Card <?php echo "<br/> (".$row['pro_brand']." ".$row['pro_generic']." ".$row['pro_formulation'].")";?></div>
  <table class="table table-responsive table-striped light-border" id="stockcardlist" style="margin-bottom: 5px;">
  <thead>
  <tr>
    <th class="normal">Date</th>
    <th class="normal">FROM</th>
    <th class="center">Lot No.</th>
    <th class="center">Expiry</th>
    <th class="ta-right">IN</th>
    <th class="ta-right">OUT</th>
  </tr>
  </thead>
  <tbody>
  <?php
  //TANAN NA NI NGA DATA NEEDED
  $list = $products->get_stock_card($pro_id);
  if($list){
    foreach($list as $value){
      /*echo $pro_id." --> ".$value['from_name']." - ".$value['lot_number']." - ".$value['date_added']." --> IN(".$value['qty_in'].") --> OUT(".$value['qty_out'].")<br/>";*/
      
      //NOTE: Dont change the format sang date kay maguba ang sorting gamit sang datatable paps
      ?>
      <tr>
        <td><?php echo $value['date_added'];?></td>
        <td><?php 
          if($value['from_name']!=""){
            echo $value['from_name'];
          }else{
            echo "[Warehouse Disposal]";
          }
          
          ?></td>
        <td class="center"><?php echo $value['lot_number'];?></td>
        <td class="center"><?php echo $value['expiry_date'];?></td>
        <td class="ta-right"><?php echo $value['qty_in'];?></td>
        <td class="ta-right"><?php echo $value['qty_out'];?></td>
      </tr>
      <?php
    }
  }
  ?>
  </tbody>
  </table>
  <div class="material-button-wrapper">
  <input type="button" value="Withdraw" id="submit_withdraw_btn" class="material-button-main float-right">
  </div>
  <script>
    $('#stockcardlist').dataTable(
    {language: {searchPlaceholder: "Search:" }, "searching": false,"lengthMenu": [25,50,100],
    "columnDefs": [{"targets": [1,2,3,4],"orderable": false}]});
  </script>
  
  <?php
}


//BARCODE AUTO APPEND
if(isset($_POST['search_barcode'])){
  $barcode = $_POST['barcode_value'];

  $result = $products->search_barcode($barcode);

  if($result){
    echo $result['pro_id'];
  }else{
    echo "0";
  }
}

//WITHDRAWAL
if(isset($_POST['withdraw_consignment_action'])){
  $client_id = $_POST['client_id'];
  $prod_withdraw = $_POST['product'];
  $lot_withdraw = $_POST['lot'];
  $qty_withdraw = $_POST['withdraw_qty'];
  $qty_total = $_POST['total'];
  $ctr_main = count($prod_withdraw);
  $ctr=0;
  $ctr_nonzero=0;
  $error="";
  while($ctr_main>$ctr){
    if($qty_withdraw[$ctr]<0){
      $error.="* Invalid Number of goods return for Lot No.".$lot_withdraw[$ctr]."Please try again.\n";
    }else if($qty_withdraw[$ctr]>$qty_total[$ctr]){
        $error.="* Goods Return for Lot No.".$lot_withdraw[$ctr]." exceeds by [".($qty_withdraw[$ctr]-$qty_total[$ctr])."]. Please try again. \n";
    }
    if($qty_withdraw[$ctr]>0){
        $ctr_nonzero++;
    }
    $ctr++;
  }
  if($ctr_nonzero==0){
    echo "No Goods Return from order was made";
  }else if($error==""){
    //CREATE WITHDRAWAL RECORD
  $withdraw_id = $orders->insert_consignment_withdrawal($client_id, $_SESSION['userid']);

    $medrep_id = $orders->get_client_medrep($client_id);
    //add counter para sa mobile
    $mobile -> updateMonitorCount($medrep_id);

  $count=0;
  while($ctr_main>$count){
      //INSERT WITHDRAWAL ITEMS
      if($qty_withdraw[$count]>0){
        $lot_id = $products->get_lot_id($lot_withdraw[$count]);
        //get all lists ka orderitems na may amo ni nga lot number in ascending order based on the time gn deliver
        $ordlist = $orders->get_consignment_records($client_id, $lot_id);    
        //Total input ka withdraw sa current
        $total_withdraw = $qty_withdraw[$count];
        $count_ord=0;
        $count_ordlist = count($ordlist);
        foreach($ordlist AS $ordrow){
        if($total_withdraw>0){
          //INSERT KA ORDER ITEM
          //UPDATE ANG CURRENT ORDER na may remaining lot (Parameter ang current value ka total withdraw)
          if($ordrow['qty_remaining']>=$total_withdraw){
            $orders->insert_consignment_withdraw_items($withdraw_id, $ordrow['orditem_id'], $prod_withdraw[$count], $lot_id, $total_withdraw, $_SESSION['userid']);
            $orders->update_orditem_consignment($ordrow['order_id'], $lot_id, $total_withdraw, $_SESSION['userid']);
            //UPDATE PRODUCT (ma add ka quantity nga gn balik)
            $products->add_withdrawn_prod($ordrow['pro_id'],$total_withdraw);
            //UPDATE PRODUCT (ma add ka quantity nga gn balik)
            $products->add_withdrawn_lot($lot_id,$total_withdraw);
            $total_withdraw = 0;
          }else{
            $sub_qty = $ordrow['qty_remaining'];
            $orders->insert_consignment_withdraw_items($withdraw_id, $ordrow['orditem_id'], $prod_withdraw[$count], $lot_id, $sub_qty, $_SESSION['userid']);
            $orders->update_orditem_consignment($ordrow['order_id'], $lot_id, $sub_qty, $_SESSION['userid']);
            //UPDATE PRODUCT (ma add ka quantity nga gn balik)
            $products->add_withdrawn_prod($ordrow['pro_id'],$sub_qty);
            //UPDATE PRODUCT (ma add ka quantity nga gn balik)
            $products->add_withdrawn_lot($lot_id,$sub_qty);
            $total_withdraw = $total_withdraw - $sub_qty;
          }
          //UDPATE current num ka gn withdraw
          
        }
        }
      }
      $count++;
    }
  }else{
    echo $error;
  }

  //MAINTAIN ANG SUBTOTAL KAGTOTAL SA ORDER KAG ORDITEM KAY AMO NI BASIS KWAAN KA MEDREP KA SALES
  //ANG MED SALES SA INVOICE MAN YA MA BASE
  //ASK SUNGEM FOR FURTHER INFO
  //echo $error;

}


if(isset($_POST['display_consignment_stock'])){
$client_id = $_POST['client_id'];
$list = $orders->get_client_consigned_items($client_id);
  ?>
  <table class="table table-responsive table-striped light-border">
  <thead>
  <tr>
    <th class="normal column_one">Product Name</th>
    <th class="">Packaging</th>
    <th class="normal column_three">Formulation</th>
    <th class="ta-right column_four">Lot No.</th>
    <th class="ta-right column_five">Expiry Date</th>
    <th class="ta-right column_six">Remaining Quantity</th>
  </tr>
  </thead>
  <tbody>
  <?php
  if($list){
    foreach($list as $values){
      ?>
      <tr>
        <td class="column_one"><?php echo $values['pro_brand']." ".$values['pro_generic'];?></td>
        <td class=""><?php echo $values['pro_formulation'];?></td>
        <td class="column_three"><?php echo $values['pro_packaging'];?></td>
        <td class="ta-right column_four"><?php echo $values['lot_number'];?></td>
        <td class="ta-right column_five"><?php echo date('F d, Y', strtotime($values['expiry_date']));?></td>
        <td class="ta-right column_six">
        <input type="hidden" value=<?php echo $values['row_sum'];?> name="total[]">
        <?php echo $values['row_sum'];?>
        </td>
      </tr>
      <?php
    }
  }else{
    ?>
    <tr>
      <td colspan="6" style="text-align: center;">No Record Found</td>
    </tr>
    <?php
  }
  ?>
  </tbody>
  </table>
  <?php
}
//CONSIGNMENT STOCKS
if(isset($_POST['display_consigned_clients'])){
$consignlist = $orders->get_clients_with_consignment();
    if($consignlist){
?>
<div id="sub-container">
<div id="full-select">
      <div class="select-container select-with-button">
          <h3>Select Client with Consignment:</h3>
          <select class="consign-client-select material-input-dd" name="consign-client" id="consign-client-select" style="width: 100%;">
              <?php
                foreach($consignlist AS $con_list){
              ?>
                <option value=<?php echo $con_list['client_id'];?>><?php echo $con_list['client_name'];?></option>
              <?php
                }
              ?>
          </select>
          <div class="material-button-wrapper">
          <button class="material-button-main" id="client_consign_btn">Load Consignment</button>
          </div>
          </div>
      </div>
</div>
<?php
}
?>

<div id="loading-screen-3">
  <img src="img/loading-icon.gif" alt="Loading" style="width:75px;height:75px;margin-top: 10%;">
</div>





<div id="subsub-container"></div>
<?php
}

if(isset($_POST['load_consignment_items'])){
  $client_id = $_POST['client_id'];
  $list = $orders->get_client_consigned_items($client_id);
  ?>
  <form method="POST" name="withdraw_consign_form" id="withdraw_consign_form">
  <table class="table table-responsive table-striped light-border">
  <thead>
  <tr>
    <th class="normal column_one">Product Name</th>
    <th class="normal column_two">Packaging</th>
    <th class="ta-right column_three">Lot No.</th>
    <th class="ta-right column_four">Remaining Quantity</th>
    <th class="ta-right column_five">Return Quantity</th>
  </tr>
  </thead>
  <tbody>
  <input type="hidden" name="withdraw_consignment_action" value="1"> 
  <?php
  if($list){
    foreach($list as $values){
      ?>
      <tr>
        <td class="column_one">
        <input type="hidden" value=<?php echo $values['pro_id'];?> name="product[]">
        <?php echo $values['pro_brand']." ".$values['pro_generic']." ".$values['pro_formulation'];?>
        </td>
        <td class="column_two"><?php echo $values['pro_packaging'];?></td>
        <td class="ta-right column_three">
        <input type="hidden" value=<?php echo $values['lot_number'];?> name="lot[]">
        <?php echo $values['lot_number'];?>
        </td>
        <td class="ta-right column_four">
        <input type="hidden" value=<?php echo $values['row_sum'];?> name="total[]">
        <?php echo $values['row_sum'];?>
        </td>
        <td class="ta-right column_five">
        <input id="withdraw_qtyss" class="material-input ta-right all_withval" type="number" min="0" value="0" name="withdraw_qty[]"></td>
      </tr>
      <?php
    }
  }
  ?>
  </tbody>
  </table>
  <div class="material-button-wrapper">
  <input type="button" value="Withdraw" id="submit_consign_withdraw_btn" class="material-button-main float-right">
  </div>
  </form>

  <script>

$('.material-input').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
      
    });

</script>
  <?php
}

if(isset($_POST['withdraw_action'])){
  $order_id = $_POST['order_id'];
  $prod_withdraw = $_POST['product'];
  $lot_withdraw = $_POST['lot'];
  $qty_withdraw = $_POST['withdraw_qty'];
  $qty_total = $_POST['total'];
  $ctr_main = count($prod_withdraw);
  $ctr=0;
  $ctr_nonzero=0;
  $error="";
  while($ctr_main>$ctr){
    if($qty_withdraw[$ctr]<0){
      $error.="* Invalid Number of Goods Return for Lot No.".$lot_withdraw[$ctr]."Please try again.\n";
    }else if($qty_withdraw[$ctr]>$qty_total[$ctr]){
        $error.="* Goods Return for Lot No.".$lot_withdraw[$ctr]." exceeds by [".($qty_withdraw[$ctr]-$qty_total[$ctr])."]. Please try again. \n";
    }
    if($qty_withdraw[$ctr]>0){
        $ctr_nonzero++;
    }
    $ctr++;
  }
  if($ctr_nonzero==0){
    echo "No Goods Return from order was made";
  }else if($error==""){
    //INSERT WITHDRAWAL
    $withdraw_id = $orders->insert_sold_withdrawal($order_id, $_SESSION['userid']);
    $count=0;
    $invoice_id = $orders->get_corresponding_invoice($order_id);
    while($ctr_main>$count){
      //INSERT WITHDRAWAL ITEMS
      if($qty_withdraw[$count]>0){
        $lot_id = $products->get_lot_id($lot_withdraw[$count]);
        $orditem_id = $orders->get_orditem_id($order_id, $lot_id);
        $orders->insert_sold_withdraw_items($withdraw_id, $orditem_id, $prod_withdraw[$count],$lot_id,$qty_withdraw[$count], $_SESSION['userid']);
        
        //UPDATE ANG ORDER ITEM TABLE OF THE WITHDRAWN PRODUCT (solve discount)
        $unit_price = $products->get_unit_price($prod_withdraw[$count]);
        $orders->update_withdrawn_orderitem($order_id,$lot_id,$unit_price,$qty_withdraw[$count],$_SESSION['userid']);
        //UPDATE ANG INVOICE ITEMS same sa babaw (solve discount)
        $orders->update_withdrawn_invoiceitem($invoice_id,$lot_id,$unit_price,$qty_withdraw[$count],$_SESSION['userid']);
        //UPDATE PRODUCT (ma add ka quantity nga gn balik)
        $products->add_withdrawn_prod($prod_withdraw[$count],$qty_withdraw[$count]);
        //UPDATE PRODUCT (ma add ka quantity nga gn balik)
        $products->add_withdrawn_lot($lot_id,$qty_withdraw[$count]);
      }
      $count++;
    }
     //UPDATE ang ORDER TOTAL (get all records sa orditem na updated na)
    //$orders->update_total($order_id);
     //UPDATE ang INVOICE TOTAL  (get all records sa invoice items na updated na)
    $orders->update_invoice_total($invoice_id);

    echo "validated";
  }else{
    echo $error;
  }
}

if(isset($_POST['load_delivered_orders'])){
  $order_id = $_POST['order_id'];
  $list = $orders->get_orditems_withdraw($order_id);
  ?>
  <form method="POST" name="withdraw_form" id="withdraw_form">
  <table class="table table-responsive table-striped light-border" id="withdrawlist" style="margin-bottom: 5px;">
  <thead>
  <tr>
    <th class="normal column_one">Product Name</th>
    <th class="normal column_two">Packaging</th>
    <th class="ta-right column_three">Lot No.</th>
    <th class="ta-right column_four">Delivered</th>
    <th class="ta-right column_six">Returned</th>
    <th class="ta-right column_six">Return Quantity</th>
  </tr>
  </thead>
  <tbody>
  <input type="hidden" name="withdraw_action" value="1"> 
  <?php
  if($list){
    foreach($list as $values){
      ?>
      <tr>
        <td class="column_one">
        <input type="hidden" value=<?php echo $values['product_id'];?> name="product[]">
        <?php echo $values['pro_brand']." ".$values['pro_generic']." ".$values['pro_formulation'];?>
        </td>
        <td class="column_two"><?php echo $values['pro_packaging'];?></td>
        <td class="ta-right column_three">
        <input type="hidden" value=<?php echo $values['lot_number'];?> name="lot[]">
        <?php echo $values['lot_number'];?>
        </td>
        <td class="ta-right column_four"><?php echo $values['qty_delivery'];?>
        </td>
        <input type="hidden" value=<?php echo $values['qty_sold'];?> name="total[]">
        <td class="ta-right column_six"><?php echo $values['qty_withdrawn'];?></td>
        <td class="ta-right column_six">
        <input id="withdraw_qtyss" class="material-input ta-right all_withval" type="number" min="0" value="0" name="withdraw_qty[]"></td>
      </tr>
      <?php
    }
  }
  ?>
  </tbody>
  </table>
  <div class="material-button-wrapper">
  <input type="button" value="Withdraw" id="submit_withdraw_btn" class="material-button-main float-right">
  </div>
  </form>

  <script>
    
    $('.material-input').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
      
    });

</script>
  </script>

  <?php
}

if(isset($_POST['display_type_withdraw'])){
  ?>
  <div id="sub-container">
  <div id="full-select">
    <div class="select-container select-with-button" >
      <h3>Select Order Type:</h3>
      <form name="form_order_type" id="form_order_type">
        <select name="select-type" id="select-type" style="width: 100%;">
        <?php
        $typelist = $orders->get_ordtype();
        if($typelist){
          foreach($typelist AS $type){
            ?>
            <option value=<?php echo $type['ordtype_id'];?>><?php echo $type['ordtype_name'];?></option>
            <?php
          }
        }
        ?>
        </select>
      </form>
    </div>
    <div class="material-button-wrapper">
    <button class="material-button-main" id="load_ordtype_content">Search</button>
    </div>
  </div>
  </div>
  <div id="subsub-container"></div>
  <?php
}


//DIRI MA LOAD KNG CONSIGNMENT OR CONSIDERED SOLD
if(isset($_POST['display_client_withdraw'])){
  $type_id = $_POST['type_id'];
  if($type_id==10){
  //LOAD ORDERS NA NA DELIVER NA
    $clientlist = $clients->get_clients();
    if($clientlist){
      ?>
      <div id="sub-container">
      <div id="full-select">
      <div class="select-container select-with-button">
          <h3>Select Client:</h3>
          <select class="" name="return-client-select" id="return-client-select" style="width: 100%;">
              <?php
                foreach($clientlist AS $client_list){
              ?>
                <option value=<?php echo $client_list['client_id'];?>><?php echo $client_list['client_name'];?></option>
              <?php
                }
              ?>
          </select>
          <div class="material-button-wrapper">
          <button class="material-button-main" id="load_sold_orders_btn">Load Orders</button>
          </div>
          </div>
      </div>
      </div>
  <?php
    }
  }else if($type_id==11){
    //LOAD CLIENTS WITH CONSIGNMENT NA DI PRE
    $consignlist = $orders->get_clients_with_consignment();
    if($consignlist){
      ?>
      <div id="sub-container">
      <div id="full-select">
      <div class="select-container select-with-button">
          <h3>Select Client with Consignment:</h3>
          <select class="" name="consign-client" id="consign-client-select" style="width: 100%;">
              <?php
                foreach($consignlist AS $con_list){
              ?>
                <option value=<?php echo $con_list['client_id'];?>><?php echo $con_list['client_name'];?></option>
              <?php
                }
              ?>
          </select>
          <div class="material-button-wrapper">
          <button class="material-button-main" id="load_consign_orders_btn">Load Consignment</button>
          </div>
          </div>
      </div>
      </div>
      <?php
    }
  }
    ?>

    <div id="loading-screen-custom" style="height: 30vh;">
  <img src="img/loading-icon.gif" alt="Loading" style="width:75px;height:75px;margin-top: 7%;">
</div>

    <div id="subsubsub-container"></div>
    <?php
  }

if(isset($_POST['dispose_prod_submit'])){
  $pro_id = $_POST['dispose_select'];
  $lot_id = $_POST['dispose_lot'];
  $qty = $_POST['quantity']; 
  $reason = $_POST['reason'];
  $error="";
  $avail_lot = $products->get_specific_lot_qty($lot_id);
  $current_lot = $products->get_specific_lot_num($lot_id);

  if($pro_id=="" || $pro_id==null){
     $error .= "Please select a product to be disposed \n";
  }else{
    if($avail_lot=="" || $avail_lot==null){
        $error .= "No Lot no. available for this product to be disposed \n";
    }else if($qty>$avail_lot){
      $error .= "Quantity of Lot No. ".$current_lot." exceeds by [".($qty-$avail_lot)."] \n";
    }
  }
  if($error==""){
    //INSERT DISPOSAL
    $products->insert_disposal($pro_id, $lot_id, $qty, $reason, $_SESSION['userid']);
    //UPDATE lot and product qty
    $products->dispose_prod_quantity($pro_id, $qty, $_SESSION['userid']);
    $products->dispose_lot_quantity($lot_id, $qty);
    echo "success";
  }else{
    echo $error;
  }

}

if(isset($_POST['load_dispose_packaging'])){
   $pro_id = $_POST['pro_id'];
  echo $products->get_product_packaging($pro_id);
}
if(isset($_POST['load_lot_dispose'])){
  $pro_id = $_POST['pro_id'];
  $list = $products->get_specific_available_lots($pro_id);
  ?>
  <select style="width: 250px; max-width:250px;" id="dispose_lot" name="dispose_lot">
  <?php
  if($list){
    foreach($list as $values){
  ?>
    <option value=<?php echo $values['lot_id'];?>><?php echo "Lot ".$values['lot_number']." - (".$values['expiry_date'].") - (".$values['quantity']."pcs)";?></option>
  <?php
    }
  }else{
    ?>
    <option value="">No Available Lot</option>
    <?php
  }
  ?>
  </select>
  <?php
}

if(isset($_POST['load_lot_none'])){
?>
<select style="width: 200px; max-width:200px;" id="dispose_lot" name="dispose_lot">
    <option value="">No Selected Product</option>
</select>
<?php
}

if(isset($_POST['display_dispose'])){
?>
<form method="POST" name="disposal_form" id="disposal_form">
<div class="table-title">Dispose Products</div>
<br/>
<br/>

<table class="table table-responsive table-striped light-border" id="disposelist" style="margin-bottom: 5px;">
  <thead>
  <tr>
    <th class="normal column_one max-w-200">Product Name</th>
    <th class="center normal column_two">Packaging</th>
    <th class="normal column_three">Lot No. / Expiry / (Qty)</th>
    <th class="ta-center column_four">Quantity</th>
    <th class="ta-center column_five">Reason</th>
  </tr>
  </thead>
  <tbody>
  <input type="hidden" name="dispose_prod_submit" value=1>
  <tr>
    <td class="column_one" width="250px">
      <select id="dispose_select" name="dispose_select" class="material-input-dd max-w-250">
        <option value="" disabled selected>Select Product</option>
        <?php
        $list = $products->get_stocks();
        if($list){
          foreach($list as $values){
            ?>
            <option value=<?php echo $values['pro_id'];?>><?php echo $values['pro_brand']." ".$values['pro_generic']." ".$values['pro_formulation'];?></option>
            <?php
          }
        }
        ?>
      </select>
    </td>
    <td class="column_two"><p id="dispose_package" class="pad-top-10 center">Unavailable</p></td>
    <td class="column_three" style="width: 250px; max-width:250px;" id="select_lot_area">
      <!---AJAX CALL---->
    </td>
    <td class="column_four"><input type="number" id="dispose_quantity_number" class="material-input ta-right" value="1" min="1" name="quantity"></td>
    <td class="column_five">
      <label><input type="radio" name="reason" value="Expired" class="material-input" id="rdo_expire" checked>Expired</label>
      <label><input type="radio" name="reason" value="Damaged" class="material-input" id="rdo_damaged">Damaged</label>
    </td>
  </tr>

  </tbody>
</table>
<div class="material-button-wrapper">
  <input type="button" value="Dispose" id="submit_dispose_btn" class="material-button-main float-right">
</div>
</form>

<script>
  

      $('#dispose_quantity_number').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
      
    });

</script>
<?php
}

if(isset($_POST['load_avail_stock'])){
  $pro_id = $_POST['row_id'];
  $list = $products->get_specific_available_lots($pro_id);
  ?>
  <table class="table table-responsive table-striped light-border">
  <tr>
    <th>Lot Number</th>
    <th>Expiry Date</th>
    <th>Quantity</th>
  </tr>
  <?php
  if($list){
    foreach ($list as $value) {
    ?>
    <tr>
      <td><?php echo $value['lot_number'];?></td>
      <td><?php echo date('F d, Y', strtotime($value['expiry_date']));?></td>
      <td><?php echo $value['quantity'];?></td>
    </tr>
    <?php
  }
  }
  else{
    ?>
    <tr>
      <td colspan="3" style="color: red; text-align: center">NO AVAILABLE STOCK FOR THIS PRODUCT</td>
    </tr>
    <?php
  }
  ?>
  </table>
  <?php
}

if(isset($_POST['get_stock_name'])){
  $id = $_POST['row_id'];
  $result = $products->get_specific_product($id);
  echo $result;
}

if(isset($_POST['add_supplies'])){
  $supplier = $_POST['supplier'];
  $product = $_POST['product'];
  $lot = $_POST['lot'];
  $date = $_POST['date'];
  $quantity = $_POST['quantity'];

  $ctr_main = count($product);
  $ctr_product = count(array_filter($product));
  $ctr_lot = count(array_filter($lot));
  $ctr_date = count(array_filter($date));
  $ctr_quantity = count(array_filter($quantity));
  
  if($ctr_main==$ctr_product && $ctr_main==$ctr_lot && $ctr_main==$ctr_date && $ctr_main==$ctr_quantity){
    for($x=0; $x<$ctr_main; $x++){
    //ADD SA PROD_SUPPLIED TABLE
      $products->add_prod_supplied($supplier, $product[$x], $lot[$x], $quantity[$x], $_SESSION['userid']);
    //UPDATE LOT_NO TABLE (UPDATE IF MAY SIMILAR OR ADD IF NEW LOT NO.) #tbl_lot
      $products->add_lot($lot[$x], $product[$x], $date[$x], $quantity[$x]);
    //UPDATE PRODUCT TOTAL QUANTITY #tbl_product
      $products->add_prod_quantity($product[$x], $quantity[$x]); //UPDATE KARON
    }
    if($ctr_main==null){
      echo json_encode("incomplete");
    }
    else{
      echo json_encode("success");
    }
  }else{
    echo json_encode("incomplete");
  }
}

//APPEND ROW SA RECEIVE SUPPLIES
if(isset($_POST['add_row'])){
  if($_POST['pro_id']!=null){
    $product_selected=$_POST['pro_id'];
  }else{
    $product_selected = 0;
  }

  //$array_selected=$_POST['selected'];
  $i = $_POST['i'];
  ?>
  <td class="td-prod">
  <?php 
    $prod_list = $products->get_products();
    if($prod_list){
      if($product_selected!=null){
      ?>
      <select disabled class="prod-select rows-text" placeholder="Select Product" name="product[]" id=<?php echo "select".$i;?> style="width: 100%; left: -29;">
      <?php 
      }else{
        ?>
        <select class="prod-select rows-text" placeholder="Select Product" name="product[]" id=<?php echo "select".$i;?> style="width: 100%; left: -29;">
      
        <?php
      }
        foreach($prod_list AS $prod_row){
          if($product_selected==$prod_row['pro_id']){
            //SELECTED VALUE
            ?>
            <option selected value=<?php echo $prod_row['pro_id'];?>><?php echo $prod_row['pro_brand']." (".$prod_row['pro_generic'].") - ".$prod_row['pro_formulation'];?></option>
            <?php
          }
          //OTHER VALUE
          ?>
          <option value=<?php echo $prod_row['pro_id'];?>><?php echo $prod_row['pro_brand']." (".$prod_row['pro_generic'].") - ".$prod_row['pro_formulation'];?></option>
          <?php 
          }?>
          </select>
            </td>
            <td class="td-other"><input type="text" name="lot[]" placeholder="Lot No." class="material-input" id="lot"/></td>
            <td class="td-other"><input type="date" name="date[]" class="material-input-dd" id="expiry"/></td>
            <td class="td-other"><input type="number" name="quantity[]" placeholder="Quantity" class="material-input validate_number" id="quantity"/></td>
            <td class="td-remove"><button type="button" name="remove" id=<?php echo $i;?> class="btn btn-danger btn_remove">&times;</button></td>

            <script>



          /**************** UPDATE NI RICK NA NUMBER AND TEXT LNG WLA SPECIAL CHARACTER ********************/
          
          $('.validate_number').on('keypress', function(e){
          return e.metaKey || // cmd/ctrl
          e.which <= 0 || // arrow keys
          e.which == 8 || // delete key
          /[0-9]/.test(String.fromCharCode(e.which)); // numbers
      
          });
          
          </script>
      }


          
  <?php
  }
}
if(isset($_POST['display_stocks_table'])){
        $list = $products->get_stocks();
?>
  <div class="table-title">Current Stocks</div>
                <table class="table table-responsive table-striped table-hover light-border" id="stocklist">
                  <thead>
                  <tr>
                    <th class="column_one">Product Name</th>
                    <th class="column_two">Formulation</th>
                    <th class="ta-right column_three">Warehouse Stocks</th>
                    <th class="ta-right column_four">Commited Orders</th>
                    <th class="ta-right column_five">Expiring</th>
                    <th class="ta-right column_five">Available</th>
                    <th class="ta-right column_six">Reorder Level</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    if($list){
                    foreach($list as $values){
                      $pending_items = $products->get_restock_notif_list($values['pro_id']);
                      $expiring_items = $products->get_expiring_notif_list($values['pro_id']);
                      if($pending_items==""){
                        $pending_items=0;
                      }
                      if($expiring_items==""){
                        $expiring_items=0;
                      }
                      ?>
                      <tr class="stock_selected" id=<?php echo $values['pro_id'];?> onclick="popup_show()">
                        <?php
                        if(($values['pro_total_qty']-$pending_items-$expiring_items)<=$values['pro_reorder_level']){
                        ?>
                        <td class="redtext column_one"><b><?php echo $values['pro_brand']."</b><br/>".$values['pro_generic'];?></td>
                        <td class="redtext column_two"><?php echo $values['pro_formulation'];?></td>
                        <td class="ta-right redtext column_three"><?php echo $values['pro_total_qty'];?></td>
                        <td class="ta-right redtext column_four"><?php echo $pending_items;?></td>
                        <td class="ta-right redtext column_five"><?php echo $expiring_items;?></td>
                        <td class="ta-right redtext column_six" style="color:#cc0000;font-weight:bold;">
                        <?php 
                        if(($values['pro_total_qty']-$pending_items-$expiring_items)>=0){
                          echo $values['pro_total_qty']-$pending_items-$expiring_items;
                        }else{
                          echo "( ".($values['pro_total_qty']-$pending_items-$expiring_items)." )";
                        }
                        ?>
                        </td>
                        
                        <td class="ta-right redtext column_seven"><?php echo $values['pro_reorder_level'];?></td>
                      <?php
                      }else{
                        ?>
                        <td class="column_one"><b><?php echo $values['pro_brand']."</b><br/>".$values['pro_generic'];?></td>
                        <td class="column_two"><?php echo $values['pro_formulation'];?></td>
                        <td class="ta-right column_three"><?php echo $values['pro_total_qty'];?></td>
                        <td class="ta-right column_four"><?php echo $pending_items;?></td>
                        <td class="ta-right column_five"><?php echo $expiring_items;?></td>
                        <td class="ta-right redtext column_six">
                        <?php 
                          echo $values['pro_total_qty']-$pending_items-$expiring_items;
                        ?>
                          
                        </td>
                        <td class="ta-right column_seven"><?php echo $values['pro_reorder_level'];?></td>
                        <?php
                      }
                    ?>
                    </tr>
                    <?php
                    }
                    }
                  ?>
                  </tbody>
                </table>
            <script>
                $('#stocklist').dataTable(
                {language: {searchPlaceholder: "Search Product" }, "lengthMenu": [25,50,100],
                "columnDefs": [{"targets": [1,2,3],"orderable": false}]}
            );



            </script>
<?php
}
exit();
?>