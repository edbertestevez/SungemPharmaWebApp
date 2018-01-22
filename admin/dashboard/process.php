<?php
include '../library/config.php';
include '../classes/class.graph.php';
include '../classes/class.products.php';
include '../classes/class.payments.php';

$graph = new Graph();
$products = new Products();
$payments = new Payments();

if(isset($_POST['get_expiring_products'])){
 $list = $products->get_expiring_products();
?>
  <h3>On Stocks (3 months)</h3>
  <table class="popup-table">
  <tr>
    <th>Product Name</th>
    <th class="">Formulation</th>
    <th class="">Packaging</th>
    <th class="ta-right">Lot Number</th>
    <th class="ta-right">Quantity</th>
    <th class="ta-right">Expiry</th>
    <th class="ta-right">Status</th>
  </tr>
  <?php
  if($list){
    foreach($list as $values){
      ?>
      <tr>
        <td><?php echo "<b>".$values['pro_brand']."</b><br/>".$values['pro_generic'];?></td>
        <td><?php echo $values['pro_formulation'];?></td>
        <td><?php echo $values['pro_packaging'];?></td>
        <td class="ta-right"><?php echo $values['lot_number'];?></td>
        <td class="ta-right"><?php echo $values['quantity'];?></td>
        <td class="ta-right"><?php echo date('F d, Y', strtotime($values['expiry_date']));?></td>
        <td class="ta-right">
          <?php 
          $date1Timestamp = strtotime($values['expiry_date']);
          $date2Timestamp = strtotime(date("Y-m-d"));
          $difference = $date1Timestamp - $date2Timestamp;
          if((($difference / (60 * 60 * 24)))>15){
            echo "<b style='color: blue;'>".(($difference / (60 * 60 * 24)))." day/s </b>";
          }else if((($difference / (60 * 60 * 24)))>5){
            echo "<b style='color: orange;'>".(($difference / (60 * 60 * 24)))." day/s </b>";
          }else{
            echo "<b style='color: red;'>".(($difference / (60 * 60 * 24)))." day/s </b>";
          }
          
          ?>
        </td>
      </tr>
      <?php
    }
  }
  ?>
  </table>
  <br/>
<?php
$list_consign = $products->get_expiring_products_consignment();
?>
  <h3>On Consignment (3 months)</h3>
  <table class="popup-table">
  <tr>
    <th class="max-w-200">Client</th>
    <th class="max-w-150">Product Name</th>
    <th class="max-w-200">Formulation</th>
    <th>Packaging</th>
    <th class="ta-right">Lot Number</th>
    <th class="ta-right">Quantity</th>
    <th class="ta-right">Expiry</th>
    <th class="ta-right">Status</th>
  </tr>
  <?php
  if($list_consign){
    foreach($list_consign as $values_consign){
      ?>
      <tr>
        <td><?php echo $values_consign['client_name'];?></td>
        <td><?php echo "<b>".$values_consign['pro_brand']."</b><br/>".$values_consign['pro_generic'];?></td>
        <td><?php echo $values_consign['pro_formulation'];?></td>
        <td><?php echo $values_consign['pro_packaging'];?></td>
        <td class="ta-right"><?php echo $values_consign['lot_number'];?></td>
        <td class="ta-right"><?php echo $values_consign['lot_total'];?></td>
        <td class="ta-right"><?php echo date('F d, Y', strtotime($values_consign['expiry_date']));?></td>
        <td class="ta-right">
          <?php 
          $date1 = strtotime($values_consign['expiry_date']);
          $date2 = strtotime(date("Y-m-d"));
          $difference_consign = $date1 - $date2;
          if((($difference_consign / (60 * 60 * 24)))>60){
            echo "<b style='color: blue;'>".(($difference_consign / (60 * 60 * 24)))." day/s </b>";
          }else if((($difference_consign / (60 * 60 * 24)))>30){
            echo "<b style='color: orange;'>".(($difference_consign / (60 * 60 * 24)))." day/s </b>";
          }else{
            echo "<b style='color: red;'>".(($difference_consign / (60 * 60 * 24)))." day/s </b>";
          }
          
          ?>
        </td>
      </tr>
      <?php
    }
  }
  ?>
  </table>
<?php
}

if(isset($_POST['get_overdue_payments'])){
 ?>
  <table class="table table-responsive table-striped light-border">
  <tr>
    <th>PDC No.</th>
    <th>Claim Date</th>
    <th>Bank</th>
    <th>Client</th>
    <th>Amount</th>
  </tr>
  <?php
  $pdclist = $payments->get_pdc_notif_list();
  if($pdclist){
    foreach($pdclist as $pdcvalues){
      ?>
      <tr>
        <td><?php echo $pdcvalues['pdc_no'];?></td>
        <td><?php echo date('F d, Y', strtotime($pdcvalues['pdc_date']));?></td>
        <td><?php echo $pdcvalues['pdc_bank'];?></td>
        <td><?php echo $pdcvalues['client_name'];?></td>
        <td><?php echo "&#8369;".$pdcvalues['payment_amount'];?></td>
      </tr>
      <?php
    }
  }
  ?>
  </table>
  <br/>
  <?php
  $list = $payments->get_overdue_payments_list();
?>
  <table class="table table-responsive table-striped light-border">
  <div class="table-title-pop">Overdue Payments</div>
  
  <tr>
    <th>Invoice No.</th>
    <th>Client</th>
    <th>Total Amount</th>
    <th class="center">Amount Paid</th>
    <th class="center">Remaining Balance</th>
    <th>Date Issued</th>
    <th>Due Date</th>
  </tr>
  <?php
  if($list){
    foreach($list as $values){
      ?>
      <tr>
        <td><?php echo $values['invoice_id'];?></td>
        <td><?php echo $values['client_name'];?></td>
        <td><?php echo "&#8369;".$values['total_amount'];?></td>
        <td><?php echo "&#8369;".$values['amount_paid'];?></td>
        <td><b style="color: #8b0505;"><?php echo "&#8369;". $values['amount_remaining'];?></b></td>
        <td><?php echo date('F d, Y', strtotime($values['date_issued']));?></td>
        <td><?php echo date('F d, Y', strtotime($values['date_due']));?></td>
      </tr>
      <?php
    }
  }
  ?>
  </table>
 
<?php
}

if(isset($_POST['get_needs_restock'])){
  $list = $products->get_products_active();
?>
  <table class="popup-table">
  <tr>
    <th class="max-w-200">Product Name</th>
    <th class="max-w-250">Formulation</th>
    <th>Packaging</th>
    <th class="ta-right">Warehouse</th>
    <th class="ta-right">Orders</th>
    <th class="ta-right">Expiring</th>
    <th class="ta-right">Available</th>
    <th class="ta-right">Reorder Level</th>
  </tr>
  <?php
  if($list){
    $ctr=1;
    foreach($list as $values){
      $pending_items = $products->get_restock_notif_list($values['pro_id']);
      $expiring_items = $products->get_expiring_notif_list($values['pro_id']);
      
      if(($values['pro_total_qty']-$pending_items)<=$values['pro_reorder_level']){
      ?>
      <tr>
        <td><?php echo "<b>".$values['pro_brand']."</b><br/>".$values['pro_generic'];?></td>
        <td><?php echo $values['pro_formulation'];?></td>
        <td><?php echo $values['pro_packaging'];?></td>
        <td class="ta-right"><?php echo $values['pro_total_qty'];?></td>
        <?php 
          if($pending_items!=""){
            ?>
            <td class="ta-right">
            <?php
            echo $pending_items;
            ?>
            </td>
          <?php
          }else{
            ?>
            <td class="ta-right">
            <?php
            echo "0";
            ?>
            </td>
            <?php
          }

          if($expiring_items!=""){
            ?>
            <td class="ta-right">
            <?php
            echo $expiring_items;
            ?>
            </td>
          <?php
          }else{
            ?>
            <td class="ta-right">
            <?php
            echo "0";
            ?>
            </td>
            <?php
          }
          $remain = ($values['pro_total_qty'])-($pending_items)-($expiring_items);
        ?>
         <td class="ta-right red-text bold"><?php echo $remain;?></td>
        <td class="ta-right"><?php echo $values['pro_reorder_level'];?></td>
      </tr>
      <?php
      $ctr++;
    }
    }
  }
  ?>
  </table>
<?php
}

if(isset($_POST['display_duepayment_num'])){
	$result = $payments->get_count_duepayment_notif();
  	echo $result;
}

if(isset($_POST['display_restock_num'])){
	$result = $products->get_count_restock_notif();
  	echo $result;
}

if(isset($_POST['display_expiry_num'])){
	$result = $products->get_count_expiry_notif();
	$result_consign = $products->get_count_expiry_consign();
  	echo $result + $result_consign;
}

if(isset($_POST['get_max_month'])){
	$result = $graph->get_json_max_month();
  	echo json_encode($result);
}

if(isset($_POST['graph_year_sales'])){
  $result = $graph->get_json_year_sales();
  echo json_encode($result);
}

if(isset($_POST['graph_year_actual_sales'])){
  $result = $graph->get_json_year_actual_sales();
  echo json_encode($result);
}


if(isset($_POST['graph_top_medsales'])){
  $result = $graph->get_json_top_medsales();
  echo json_encode($result);
}

if(isset($_POST['graph_medrep_sales'])){
  $result = $graph->get_json_medrep_sales();
  echo json_encode($result);
}
?>