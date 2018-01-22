<?php
include '../library/config.php';
include '../classes/class.products.php';
include '../classes/class.suppliers.php';
include '../classes/class.payments.php';
include '../classes/class.orders.php';
include '../classes/class.delivery.php';
include '../classes/class.clients.php';
include '../classes/class.users.php';
include '../classes/class.reports.php';

$users = new Users();
$suppliers = new Suppliers();
$payments = new Payments();
$orders = new Orders();
$delivery = new Delivery();
$products = new Products();
$clients = new Clients();
$reports = new Reports();


//PAYMENT REPORT
if(isset($_POST['load_payments_datepicker'])){
?>
<div id="sub-container">
  <div id="full-select">
      <div class="select-container select-with-button">
<div id="date-container">
<h3>Payments Report</h3>
  <div class="report_date_form">
  <form id="date_payments_form" name="date_payments_form" method="POST">
    <input type="hidden" name="date_trigger_payments" value="1">
    <input type="date" id="start_date" class="material-input-dd" name="start_date">&nbsp; to &nbsp;
    <input type="date" id="end_date" class="material-input-dd" name="end_date">
    <div class="material-button-wrapper">
    <input type="button" id="submit_payments_date" class="material-button-main" name="submit" value="Generate">
    </div>
  </form>
  </div>
</div>  
  <br/><br/><br/>
  </div>
</div>
</div>
<div id="loading-screen-custom" style="height: 110px; padding-top: 10%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div id="reports_area"></div>
<?php
}

if(isset($_POST['date_trigger_payments'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  if($start=="" || $end==""){
    echo "<b style='color:red;'>* Please enter valid dates</b>";
  }else if($end<$start){
    echo "<b style='color:red;'>* [End Date] should be later than [Start Date]</b>";
  }
  else{
  $list = $reports->get_medsales_report($start, $end);
?>
  <div id="report_display1">
    
  <!------PRINT PAYMENTS REPORTS BUTTON ---->
    <input type="submit" class="print_btn" name="print_payment" id="print_payment" value="PRINT REPORT">

    <div id="report_graph">

    <div class="graph-box-two">
      <div class="graph_report_title">Clients Payment Collection</div>
        <div class="bar_graph_report">
          <canvas height="320px" width="650px" id="payments-graph-one"></canvas>
        </div>    
    </div>

    <div class="graph-box-last">
      <div class="graph_report_title">Area Payment Collection</div>
        <div class="bar_graph_report">
          <canvas height="300px" id="payments-graph-two"></canvas>
        </div>    
    </div>

    </div>
  </div>
    <div id="report_result">
      <table style="border: 2px solid #cacaca;padding: 20px; width: 100%;">
      <tr>
        <td class="center report_num">
        <?php
          if($reports->get_total_medsales($start, $end)!=""){
            echo "&#8369;".number_format($reports->get_total_medsales($start, $end), 2, '.', ',');  
          }else{
            echo "&#8369;0";
          }
        ?>
        </td>
        <td class="center report_num">
        <?php
          if($reports->get_total_considered_sold($start, $end)!=""){
            echo "&#8369;".number_format($reports->get_total_considered_sold($start, $end), 2, '.', ',');
          }else{
            echo "&#8369;0";
          }
        ?>
        </td>
        <td class="center report_num">
          <?php
          if($reports->get_total_consignment_sales($start, $end)!=""){
            echo "&#8369;".number_format($reports->get_total_consignment_sales($start, $end), 2, '.', ',');  
          }else{
            echo "&#8369;0";
          }
        ?>
        </td>
      </tr>
      <tr>
        <th class="center">Total Medicine Sales</th>
        <th class="center">Considered Sold Sales</th>
        <th class="center">Consignment Sales</th>
      </tr>
      </table>
    </div>
    <br/>
      <div class="report-container" style="margin-top: 200px;">
  <table  class="table table-responsive table-striped table-hover" id="management_table">
  <div class="table-title">Payments Report (Collection)</div>
  <h4 style="margin:0; margin-top:5px;"><?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?></h4>
  <thead class="pad-top-10">
    <th class="column_one">#</th>
    <th>Client Name</th>
    <th class="column_three">Area</th>
    <th class="column_four"><center>Received Payment (Collection)</center></th>
  </thead>
  <tbody>
  <?php
  $i = 1;
  $client_list = $clients->get_clients_desc();
  if($client_list){
    foreach($client_list as $client_row){
      $client_amount = $reports->get_client_received_sales($client_row['client_id'],$start,$end);
      if($client_amount>0){
  ?>
  <tr>
  <td class="padding10 column_one"><?php echo $i;?></td>
    <td class="padding10"><?php echo $client_row['client_name'];?></td>
    <td class="padding10 column_three"><?php echo $client_row['area_name'];?></td>
    <td class="padding1050 column_four">
      <?php 
        echo "&#8369;".number_format($client_amount, 2, '.', ',');
        ?>
    </td>
  </tr>
  <?php
  $i++;
  }
  }
  }
  ?>
  </tbody>
  </table>
  </div>
  <script type="text/javascript">
    $('#management_table').dataTable(
                {language: {searchPlaceholder: "Search Client/Area" }, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
  </script>


 <?php
}
}

// MEDICAL REPRESENTATIVES SALES
if(isset($_POST['graph_medrep_sales'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_medrep_actual_sales($start, $end);
  echo json_encode($result);
}

if(isset($_POST['graph_medrep_medsales'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_medrep_medsales($start, $end);
  echo json_encode($result);
}

if(isset($_POST['date_trigger_medrepsales'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  if($start=="" || $end==""){
    echo "<b style='color:red;'>* Please enter valid dates</b>";
  }else if($end<$start){
    echo "<b style='color:red;'>* [End Date] should be later than [Start Date]</b>";
  }
  else{
  $list = $reports->get_medrep_report($start, $end);
?>
  <div id="report_display">

  <!------PRINT MEDREP REPORTS BUTTON ---->
    <input type="submit" class="print_btn" name="print_medrep" id="print_medrep" value="PRINT REPORT">

      <div class="graph-box-big">
      <div class="graph_report_title">Medical Representative Sales</div>
        <div class="bar_graph_report_big">
          <canvas id="medrep-graph-one"></canvas>
        </div>  
</div>
<div class="graph-box-big">
        <div class="graph_report_title">Medicine Sales Value</div>
        <div class="bar_graph_report_big">
          <canvas id="medrep-graph-two"></canvas>
        </div>     
    </div>
  </div>
  <br/>
  <div class="report-container" style="margin-top: 220px;">
  <table  class="table table-responsive table-striped table-hover" id="medrep_table">
  <div class="table-title">Medical Representatives Report</div>
  <h4 style="margin:0; margin-top:5px;"><?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?></h4>
  <thead class="pad-top-10">
    <th class="column_one">#</th>
    <th class="">Medical Representative</th>
    <th class="column_three">Deliveries Made</th>
    <th class="column_three">Medicines Sold<br/>(Invoice Items)</th>
    <th class="column_four">Sales Value<br/>(Medicines Sold)</th>
    <th class="column_five">Actual Sales<br/>(Collection)</th>
  </thead>
  <tbody>
<?php
if($list){
  $ctr=1;
    foreach($list as $values){
?>
  <tr>
    <td class="padding10 column_one"><?php echo $ctr;?></td>
    <td class="padding10"><?php echo $values['mr_firstname']." ".$values['mr_middlename']." ".$values['mr_lastname'];?></td>
    <td class="padding10 column_three">
    <?php 
    if($values['del_count']!=""){
      echo "".$values['del_count'];  
    }else{
      echo "0";
    }
    ?>
    </td>
    <td class="padding10 column_three">
    <?php 
    if($values['med_qty']!=""){
      echo "".$values['med_qty'];  
    }else{
      echo "0";
    }
    ?>
    </td>
    <td class="padding10 column_four">
    <?php 
    if($values['med_sales']!=""){
      echo "&#8369;".number_format($values['med_sales'], 2, '.', ',');  
    }else{
      echo "&#8369;0";
    }
    ?>
    </td>
    <td class="padding10 column_five">
    <?php 
    $actual_sales = $reports->get_medrep_actual_sales_specific($values['medrep_id'],$start,$end);
    if($actual_sales!=""){
      echo "&#8369;".number_format($actual_sales, 2, '.', ',');  
    }else{
      echo "&#8369;0";
    }
    ?>
    </td>
  </tr>
<?php
$ctr++;
}
}
}
?>
</tbody>
  </table>
  </div>

  <script>
    $('#medrep_table').dataTable(
                {language: {searchPlaceholder: "Search Medrep" }, "bLengthChange" : false,"pageLength":50,"aaSorting": [[3, "desc"]], "bSort": false});
  </script>
<?php
}

if(isset($_POST['load_medrepsales_datepicker'])){
?>
<div id="sub-container">
  <div id="full-select">
      <div class="select-container select-with-button">
<div id="date-container">
<h3>Medical Representatives Sales Report</h3>
  <div class="report_date_form">
  <form id="date_medrepsales_form" name="date_medrepsales_form" method="POST">
    <input type="hidden" name="date_trigger_medrepsales" value="1">
    <input type="date" id="start_date" class="material-input-dd" name="start_date">
    &nbsp; to &nbsp;
    <input type="date" id="end_date" class="material-input-dd" name="end_date">
    <div class="material-button-wrapper">
    <input type="button" id="submit_medrepsales_date" class="material-button-main" name="submit" value="Generate">
    </div>
  </form>
  </div>
</div>  
</div>
</div>
</div>
<div id="loading-screen-custom" style="height: 110px; padding-top: 10%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div id="reports_area"></div>
<?php
}
// END OF MEDICAL REPRESENTATIVES SALES

//MEDICINE SALES
if(isset($_POST['graph_receive_management'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_receive_management($start, $end);
  echo json_encode($result);
}
if(isset($_POST['graph_management_area'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_management_area($start, $end);
  echo json_encode($result);
}
if(isset($_POST['graph_medsales_area'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_medsales_area($start, $end);
  echo json_encode($result);
}
if(isset($_POST['graph_top_medsales'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_top_medsales($start, $end);
  echo json_encode($result);
}

if(isset($_POST['date_trigger_medsales'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  if($start=="" || $end==""){
    echo "<b style='color:red;'>* Please enter valid dates</b>";
  }else if($end<$start){
    echo "<b style='color:red;'>* [End Date] should be later than [Start Date]</b>";
  }
  else{
  $list = $reports->get_medsales_report($start, $end);
?>
  <div id="report_display1">
    
  <!------PRINT SALES REPORTS BUTTON ---->
    <input type="submit" class="print_btn" name="print_sales" id="print_sales" value="PRINT REPORT">

    <div id="report_graph">

    

    <div class="graph-box-two">
      <div class="graph_report_title">Top Selling Medicines</div>
        <div class="bar_graph_report">
          <canvas height="280px" id="medsales-graph-one"></canvas>
        </div>    
    </div>

    <div class="graph-box-last">
      <div class="graph_report_title">Area Medicine Sales</div>
        <div class="bar_graph_report">
          <canvas height="280px" id="medsales-graph-two"></canvas>
        </div>    
    </div>
    </div>
  </div>
    <div id="report_result">
      <table style="border: 2px solid #cacaca;padding: 20px; width: 100%;">
      <tr>
        <td class="center report_num">
        <?php
          if($reports->get_total_medsales($start, $end)!=""){
            echo "&#8369;".number_format($reports->get_total_medsales($start, $end), 2, '.', ',');  
          }else{
            echo "&#8369;0";
          }
        ?>
        </td>
        <td class="center report_num">
        <?php
          if($reports->get_total_considered_sold($start, $end)!=""){
            echo "&#8369;".number_format($reports->get_total_considered_sold($start, $end), 2, '.', ',');
          }else{
            echo "&#8369;0";
          }
        ?>
        </td>
        <td class="center report_num">
          <?php
          if($reports->get_total_consignment_sales($start, $end)!=""){
            echo "&#8369;".number_format($reports->get_total_consignment_sales($start, $end), 2, '.', ',');  
          }else{
            echo "&#8369;0";
          }
        ?>
        </td>
      </tr>
      <tr>
        <th class="center">Total Medicine Sales</th>
        <th class="center">Considered Sold Sales</th>
        <th class="center">Consignment Sales</th>
      </tr>
      </table>
    </div>
    <br/>

  <div class="report-container" style="margin-top: 200px">
  <table  class="table table-responsive table-striped table-hover" id="medicine_table">
  <div class="table-title">Medicine Sales Report</div>
  <h4 style="margin:0; margin-top:5px;"><?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?></h4>
  <thead class="pad-top-10">
    <th class="column_one">#</th>
    <th class="" style="width: 100px; max-width: 100px;" >Product Name</th>
    <th class="column_three" style="width: 30px; max-width: 30px;">Formulation</th>
    <th class="column_four">Packaging</th>
    <th class="center column_five">Quantity Sold</th>
    <th class="center column_six">Total Sales Value</th>
  </thead>
  <tbody>
<?php
if($list){
  $ctr=1;
  foreach($list as $values){
    if($values['sold_qty']!=""){
?>
  <tr>
    <td class="padding10 column_one"><?php echo $ctr;?></td>
    <td class="padding10"><b><?php echo $values['pro_brand']."</b><br/>".$values['pro_generic'];?></td>
    <td class="padding10 column_three"><?php echo $values['pro_formulation'];?></td>
    <td class="padding10 column_four"><?php echo $values['pro_packaging'];?></td>
    <td class="padding10 center column_five">
    <?php 
      if($values['sold_qty']!=""){
        echo $values['sold_qty'];  
      }else{
        echo "0";
      }
      ?>
    </td>
    <td class="padding1050 column_six">
      <?php 
      if($values['sold_sales']!=""){
        echo "&#8369;".number_format($values['sold_sales'], 2, '.', ',');  
      }else{
        echo "&#8369;0";
      }
      ?>
    </td>
  </tr>
<?php
$ctr++;
  }
}
}
?>
</tbody>
  </table>
  </div>
    <script type="text/javascript">
    $('#medicine_table').dataTable(
                {language: {searchPlaceholder: "Search Product" }, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
  </script>
<?php
}
}

if(isset($_POST['load_medsales_datepicker'])){
?>
<div id="sub-container">
  <div id="full-select">
      <div class="select-container select-with-button">
<div id="date-container">
<!--<h3>Medicine Sales Report</h3>-->
  <h3>Medicine Sales Report</h3>
  <div class="report_date_form">
  <form id="date_medsales_form" name="date_medsales_form" method="POST">
    <input type="hidden" name="date_trigger_medsales" value="1">
    <input type="date" id="start_date" class="material-input-dd" name="start_date">&nbsp; to &nbsp;
    <input type="date" id="end_date" class="material-input-dd" name="end_date">
    <div class="material-button-wrapper">
    <input type="button" id="submit_medsales_date" class="material-button-main" name="submit" value="Generate">
    </div>
  </form>
  </div>
</div>  
  <br/><br/><br/>
  </div>
</div>
</div>
<div id="loading-screen-custom" style="height: 110px; padding-top: 10%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div id="reports_area"></div>
<?php
}
// END OF MEDICINE SALES

//CONSIGNMENT
if(isset($_POST['get_consign_prod_table'])){
  $pro_id = $_POST['pro_id'];
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $list = $clients->get_clients();
  ?>

  <table class="table light-border">
  <tr>
  <th>Area Name</th>
  <th>Area</th>
  <th>Total Consigned</th>
  <th>Total Returns</th>
  <th>Quantity Sold</th>
  <th>Total Sold Value</th>
  </tr>
  <?php
  if($list){
    foreach($list as $values){
      ?>
      <tr>
      <td><?php echo $values['client_name'];?></td>
      <td><?php echo $values['area_name'];?></td>
      <td class="center">
      <?php 
      $consign = $reports->get_consignment_prod_count($pro_id, $start, $end, $values['client_id']);
      if($consign!=""){
        echo "<b>".$consign."</b>";
      }else{
        echo "0"; 
      }
      ?>
      </td>
      <td class="center">
      <?php 
      $withdraw = $reports->get_consignment_prod_withdrawn($pro_id, $start, $end, $values['client_id']);
      if($withdraw!=""){
        echo "<b>".$withdraw."</b>";
      }else{
        echo "0"; 
      }
      ?>
      </td>
      <td class="center">
      <?php 
      $sold = $reports->get_consignment_prod_sold($pro_id, $start, $end, $values['client_id']);
      if($sold!=""){
        echo "<b>".$sold."</b>";
      }else{
        echo "0"; 
      }
      ?>
      </td>
      <td class="center">
        <?php 
      $sales = $reports->get_consignment_prod_sales($pro_id, $start, $end, $values['client_id']);
      if($sales!=""){
        echo "<b>".$sales."</b>";
      }else{
        echo "0"; 
      }
      ?>
      </td>
      </tr>
      <?php
    }
  }
  echo $row;
}

if(isset($_POST['consignment_selected'])){
  $pro_id = $_POST['pro_id'];
  $result = $products->get_specific_product($pro_id);
  echo $result;
}

if(isset($_POST['graph_topsales_consigned'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_topsales_consigned($start, $end);
  echo json_encode($result);
}

if(isset($_POST['graph_area_consign_sales'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_consign_sales_client($start, $end);
  echo json_encode($result);
}

if(isset($_POST['graph_area_consigned'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_area_consigned($start, $end);
  echo json_encode($result);
}

if(isset($_POST['graph_top_consigned'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_top_consigned($start, $end);
  echo json_encode($result);
}

if(isset($_POST['date_trigger_consignment'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  if($start=="" || $end==""){
    echo "<b style='color:red;'>* Please enter valid dates</b>";
  }else if($end<$start){
    echo "<b style='color:red;'>* [End Date] should be later than [Start Date]</b>";
  }
  else{

?>
  <div id="report_display">

    <!------PRINT CONSIGNMENT REPORTS BUTTON ---->
    <input type="submit" class="print_btn" name="print_consignment" id="print_consignment" value="PRINT REPORT">
  

    <div id="report_graph">

    <div class="graph-box-two">
      <div class="graph_report_title">Client Consigned Medicines Sales</div>
        <div class="bar_graph_report">
          <canvas id="consign-graph-one"></canvas>
        </div>    
    </div>

    <div class="graph-box-last">
      <div class="graph_report_title">Area's Consignment Statistics</div>
        <div class="bar_graph_report">
          <canvas height="280px" id="consign-graph-two"></canvas>
        </div>    
    </div>

     <div class="graph-box-two">
      <div class="graph_report_title">Top Selling Consigned Medicines</div>
        <div class="bar_graph_report">
          <canvas id="consign-graph-three"></canvas>
        </div>    
    </div>

    <div class="graph-box-last">
      <div class="graph_report_title">Medicine Consignment Quantity</div>
        <div class="bar_graph_report">
          <canvas height="280px" id="consign-graph-four"></canvas>
        </div>    
    </div>
    </div>

    </div>
  </div>
    <div id="report_result">

      <table style="border: 2px solid #cacaca;padding: 20px; width: 100%;">
      <tr>
        <td class="center report_num">
        <?php
          if($reports->get_total_consignment($start, $end)!=""){
            echo $reports->get_total_consignment($start, $end);
          } else{
            echo "0";
          }
        ?>
        </td>
        <td class="center report_num">
        <?php
          if($reports->get_total_withdrawn_consignment($start, $end)!=""){
            echo $reports->get_total_withdrawn_consignment($start, $end);
          } else{
            echo "0";
          }
        ?>
        </td>
        <td class="center report_num">
           <?php
          if($reports->get_total_consignment_sales($start, $end)!=""){
            echo "&#8369;".number_format($reports->get_total_consignment_sales($start, $end), 2, '.', ',');
          } else{
            echo "&#8369;0";
          }
        ?>
        </td>
      </tr>
      <tr>
        <th class="center">Total Consigned Medicines<br/>(Quantity Delivered)</th>
        <th class="center">Total Returned Consignment<br/>(Goods Return)</th>
        <th class="center">Total Consignment Sales<br/>(Sales Value)</th>
      </tr>
      </table>
    </div>
  <br/>

  <div class="report-container" style="margin-top: 300px;">
  <table  class="table table-responsive table-striped table-hover" id="area_table">
  <div class="table-title">Client/Area Consignment Report</div>
  <h4 style="margin:0; margin-top:5px;"><?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?></h4>
  <thead class="pad-top-10">
    <th style="width: 300px; max-width: 300px;">Client Name</th>
    <th class="column_two" style="width: 200px; max-width: 200px;">Area</th>
    <th class="center column_three"><center>Total<br/>Consigned</center></th>
    <th class="center column_four"><center>Total<br/>Return</center></th>
    <th class="center column_five"><center>Total<br/>Sold</center></th>
    <th class="center column_five"><center>Total<br/>Sold Value</center></th>
  </tr>
  </thead>
  <?php
  $i = 1;
  $client_list = $clients->get_clients_desc();
  ?>
  <tbody>
  <?php
  if($client_list){
    foreach($client_list as $client_row){
      $client_consigned = $reports->get_client_report_consigned($client_row['client_id'],$start,$end);
      $client_withdrawn = $reports->get_client_report_withdrawn($client_row['client_id'],$start,$end);
      $client_qty = $reports->get_client_report_sold($client_row['client_id'],$start,$end);
      $client_sales = $reports->get_client_report_sales($client_row['client_id'],$start,$end);
      $client_overall = $client_consigned+$client_withdrawn+$client_qty+$client_sales;

      if($client_overall>0){
  ?>
  <tr>
    <td class="padding10"><?php echo $client_row['client_name'];?></td>
    <td class="padding10 column_two"><?php echo $client_row['area_name'];?></td>
    <td class="padding10 center column_three">
        <?php 
        
        if($client_consigned!=0){
          echo $client_consigned;
        }else{
          echo "0";
        }
        ?>
    </td>
    <td class="padding10 center column_four">
       <?php 
        
        if($client_withdrawn!=0){
          echo $client_withdrawn;
        }else{
          echo "0";
        }
        ?>
    </td>
    <td class="padding10 center column_five">
      <?php 
        
        if($client_qty!=0){
          echo $client_qty;
        }else{
          echo "0";
        }
        ?>
    </td>
    <td class="padding10 column_five" style="padding-left: 30px;">
      <?php 
        
        if($client_sales!=0){
          echo "&#8369;".number_format($client_sales, 2, '.', ',');
        }else{
          echo "&#8369;0";
        }
        ?>
    </td>
  </tr>
  <?php
  $i++;
}
  }
  }
  ?>
  </tbody>
  </table>
  </div>
  <script>
  $('#area_table').dataTable(
                {language: {searchPlaceholder: "Search Client/Area" }, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
  </script>
  <div class="report-container">
 <table  class="table table-responsive table-striped table-hover" id="med_consign_table">
  <div class="table-title">Medicine Consignment Report</div>
  <h4 style="margin:0; margin-top:5px;"><?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?></h4>
  <thead class="pad-top-10">
    <th style="width: 100px; max-width: 100px;" >Product Name</th>
    <th class="column_three" style="width: 30px; max-width: 30px;">Formulation</th>
    <th class="column_four">Packaging</th>
    <th class="center column_five"><center>Total<br/>Consigned</center></th>
    <th class="center column_six"><center>Total<br/>Returned</center></th>
    <th class="center column_seven"><center>Total<br/>Sold</center></th>
    <th class="center column_seven"><center>Total<br/>Sold Value</center></th>
  </thead>
  <tbody>
<?php
  $list = $products->get_products();
if($list){
  $ctr=1;
  foreach($list as $values){
    $total_consign_specific=$reports->total_consign_specific($start, $end, $values['pro_id']);
    $total_consign_withdrawn_specific=$reports->total_consign_withdrawn_specific($start, $end, $values['pro_id']);
    $total_consign_sold_specific=$reports->total_consign_sold_specific($start, $end, $values['pro_id']);
    $total_consign_value_specific=$reports->total_consign_value_specific($start, $end, $values['pro_id']);
    $overall = $total_consign_specific + $total_consign_withdrawn_specific + $total_consign_sold_specific + $total_consign_value_specific;
    if($overall>0){
?>
  <tr>
    <td class="padding10 con_prod_selected max-w-250" id=<?php echo $values['product_id'];?> style="cursor:pointer;" onclick="popup_show()"><b><?php echo $values['pro_brand']."</b><br/>".$values['pro_generic'];?></td>
    <td class="padding10 column_three max-w-200"><?php echo $values['pro_formulation'];?></td>
    <td class="padding10 column_four max-w-150"><?php echo $values['pro_packaging'];?></td>
    <td class="padding10 center column_five">
        <?php 
        if($total_consign_specific!=0){
          echo $total_consign_specific;
        }else{
          echo "0";
        }
        ?>
    </td>
    <td class="padding10 center column_six">
      <?php 
        if($total_consign_withdrawn_specific!=0){
          echo $total_consign_withdrawn_specific;
        }else{
          echo "0";
        }
        ?>
    </td>
    <td class="padding10 center column_seven">
      <?php 
        if($total_consign_sold_specific!=0){
          echo $total_consign_sold_specific;
        }else{
          echo "0";
        }
        ?>
    </td>
    <td class="padding10 column_seven" style="padding-left: 30px;">
      <?php 
        if($total_consign_value_specific!=0){
          echo "&#8369;".number_format($total_consign_value_specific, 2, '.', ',');
        }else{
          echo "&#8369;0";
        }
        ?>
    </td>
  </tr>
<?php
$ctr++;
}
  }
}
?>
</tbody>
  </table>
  </div>
  <script>
  $('#med_consign_table').dataTable(
                {language: {searchPlaceholder: "Search Product" }, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
  </script>
<?php
}
}

if(isset($_POST['load_consignment_datepicker'])){
?>
<div id="sub-container">
  <div id="full-select">
      <div class="select-container select-with-button">
<div id="date-container">
<h3>Consignment Report</h3>
  <div class="report_date_form">
  <form id="date_consignment_form" name="date_consignment_form" method="POST">
    <input type="hidden" name="date_trigger_consignment" value="1">
    <input type="date" id="start_date" class="material-input-dd" name="start_date">&nbsp; to &nbsp;
    <input type="date" id="end_date" class="material-input-dd" name="end_date">
    <div class="material-button-wrapper">
    <input type="button" id="submit_consignment_date" class="material-button-main" name="submit" value="Generate">
    </div>
  </form>
  </div>
</div>  
  <br/><br/><br/>
  </div>
</div>
</div>
<div id="loading-screen-custom" style="height: 110px; padding-top: 10%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div id="reports_area"></div>
<?php
}
//END OF CONSIGNMENT

//STOCKS-----------------------------
if(isset($_POST['graph_top_delivered_stock'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_top_delivered_stock($start, $end);
  echo json_encode($result);
}
if(isset($_POST['graph_top_supplied_stock'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_top_supplied_stock($start, $end);
  echo json_encode($result);
}

if(isset($_POST['graph_top_supplier'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_top_supplier($start, $end);
  echo json_encode($result);
}

if(isset($_POST['graph_top_supplied_clients'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  $result = $reports->get_json_top_supplied_clients($start, $end);
  echo json_encode($result);
}

if(isset($_POST['date_trigger'])){
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];
  if($start=="" || $end==""){
    echo "<b style='color:red;'>* Please enter valid dates</b>";
  }else if($end<$start){
    echo "<b style='color:red;'>* [End Date] should be later than [Start Date]</b>";
  }
  else{
  
?>
  <div id="report_display">

    <!------PRINT STOCKS REPORTS BUTTON ---->
    <input type="submit"  class="print_btn" name="print_stock" id="print_stock" value="PRINT REPORT">
  
    <div id="report_graph">
    <div class="graph-box-two">
      <div class="graph_report_title">Top Supplied Stocks</div>
        <div class="bar_graph_report">
          <canvas  id="stocks-graph-one"></canvas>
        </div>    
    </div>

    <div class="graph-box-last">
      <div class="graph_report_title">Top Suppliers</div>
        <div class="bar_graph_report">
          <canvas height="280px" id="stocks-graph-two"></canvas>
        </div>    
    </div>

    <div class="graph-box-two">
      <div class="graph_report_title">Top Delivered Stocks</div>
        <div class="bar_graph_report">
          <canvas id="stocks-graph-three"></canvas>
        </div>    
    </div>

    <div class="graph-box-last">
      <div class="graph_report_title">Top Clients Delivery</div>
        <div class="bar_graph_report">
          <canvas height="280px" id="stocks-graph-four"></canvas>
        </div>    
    </div>

    </div>
  </div>

    <div id="report_result">
      <table style="border: 2px solid #cacaca;padding: 20px; width: 100%;">
      <tr>
        <td class="center report_num">
        <?php
          if($reports->get_total_supplied($start, $end)!=""){
            echo number_format($reports->get_total_supplied($start, $end), 0, '.', ',');  

          } else{
            echo "0";
          }
        ?>
        </td>
        <td class="center report_num">
        <?php
          if($reports->get_total_delivered($start, $end)!=""){
            echo number_format($reports->get_total_delivered($start, $end), 0, '.', ',');
          } else{
            echo "0";
          }
        ?>
        </td>
        <td class="center report_num">
        <?php
          if($reports->get_total_withdrawn($start, $end)!=""){
            echo $reports->get_total_withdrawn($start, $end);
          } else{
            echo "0";
          }
        ?>
        </td>
        <td class="center report_num">
        <?php
          if($reports->get_total_disposed($start, $end)!=""){
            echo $reports->get_total_disposed($start, $end);
          } else{
            echo "0";
          }
        ?>
        </td>
      </tr>
      <tr>
        <th class="center">Total Supplied</th>
        <th class="center">Total Delivered</th>
        <th class="center">Total Return</th>
        <th class="center">Total Disposed</th>
      </tr>
      </table>
    </div>

<div class="report-container" style="margin-top: 300px;">
  <table  class="table table-responsive table-striped table-hover" id="suppliers_table">
  <div class="table-title">Suppliers Report</div>
  <h4 style="margin:0; margin-top:5px;"><?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?></h4>
  <thead class="pad-top-15">
    <th class="column_one">#</th>
    <th>Supplier Name</th>
    <th class="center column_two">Deliveries Made</th>
    <th class="center column_three">Medicines Supplied</th>
  </thead>

  <tbody>
<?php
$list_supplier=$reports->get_supplier_report($start,$end);
if($list_supplier){
  $ctr=1;
  foreach($list_supplier as $values){
?>
  <tr>
    <td class="padding10 column_one"><?php echo $ctr;?></td>
    <td class="padding10"><?php echo $values['supplier_name'];?></td>
    <td class="padding10 center column_two"><?php echo $suppliers->get_delivery_count($values['supplier_id'],$start,$end);?></td>
    <td class="padding10 center column_three"><?php echo number_format($values['total_supplied'], ',');?></td>
  </tr>
<?php
$ctr++;
  }
}
?>
  </tbody>
</table>
</div>
<div class="report-container">
  <table  class="table table-responsive table-striped table-hover" style="margin-top:80px;" id="stocks_table">
  <div class="table-title">Inventory Report</div>
  <h4 style="margin:0; margin-top:5px;"><?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?></h4>
 <thead class="pad-top-15">
    <th class="max-w-200" >Product</th>
    <th class="center column_five max-w-150 ta-right">In Stock</th>
    <th class="center column_six max-w-150 ta-right">Committed Orders</th>
    <th class="center column_six max-w-150 ta-right">Supplied</th>
    <th class="center column_six max-w-150 ta-right">Returned</th>
    <th class="center column_six max-w-150 ta-right">Disposed</th>
    <th class="center column_six max-w-150 ta-right">Expired/<br/>Near Expiry</th>
    <th class="center column_six max-w-150 ta-right">Available</th>

  </thead>
  <tbody>
<?php
$list = $products->get_products();
if($list){
  $ctr=1;
  
  foreach($list as $values){
    $total_supplied=$reports->get_supplied_specific($start, $end, $values['pro_id']);
    $total_delivered=$reports->get_delivered_specific($start, $end, $values['pro_id']);
    $total_withdrawn=$reports->get_withdrawn_specific($start, $end, $values['pro_id']);
    $total_disposed=$reports->get_disposed_specific($start, $end, $values['pro_id']);

    //REVISIONS AREA
    $in_stock=$reports->get_instock_specific($start, $end, $values['pro_id']);
    $committed=$reports->get_committed_specific($start, $end, $values['pro_id']);
    $near_expiry=$reports->get_nearexpiry_specific($start, $end, $values['pro_id']);
    //if($overall>0){
?>
  <tr>
    <td class="padding10"><b><?php echo $values['pro_brand']."</b><br/>".$values['pro_generic']."<br/>".$values['pro_formulation'];?></td>
    <td class="ta-right"><?php echo $in_stock;?></td>
    <td class="ta-right"><?php echo $committed;?></td>
    <td class="padding10 center column_five ta-right"><?php
         if($total_supplied==""){
            echo "0";
          }else{
            echo $total_supplied;
          }
        ?>
    </td>
    <td class="padding10 center column_five ta-right"><?php
         if($total_withdrawn==""){
            echo "0";
          }else{
            echo $total_withdrawn;
          }
        ?>
    </td>
    <td class="padding10 center column_five ta-right"><?php
         if($total_disposed==""){
            echo "0";
          }else{
            echo $total_disposed;
          }
        ?>
    </td>
    <td class="ta-right"><?php echo $near_expiry;?></td>
    <td class="ta-right"><?php echo $in_stock-$committed+$total_supplied-$near_expiry-$total_disposed+$total_withdrawn;?>
      
    </td>
  </tr>
<?php
$ctr++;
  //}
  }
}
?>
</tbody>
  </table>
</div>
  <script>
    $('#suppliers_table').dataTable(
                {language: {searchPlaceholder: "Search Supplier" }, "bLengthChange" : false,"pageLength":15,"aaSorting": [[3, "desc"]], "bSort": false});

    $('#stocks_table').dataTable(
                {language: {searchPlaceholder: "Search Product" }, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
  </script>

<?php
}
}

if(isset($_POST['load_datepicker'])){
?>
<div id="sub-container">
  <div id="full-select">
      <div class="select-container select-with-button">
<div id="date-container">
<h3>Inventory Report</h3>
  <div class="report_date_form">
  <form id="date_form" name="date_form" method="POST">
    <input type="hidden" name="date_trigger" value="1">
    <input type="date" id="start_date" class="material-input-dd" name="start_date">&nbsp; to &nbsp;
    <input type="date" id="end_date" class="material-input-dd" name="end_date">
    <div class="material-button-wrapper">
    <input type="button" id="submit_date" class="material-button-main" name="submit" value="Generate">
    </div>
  </form>
  </div>
</div>  
  <br/><br/><br/>
  </div>
</div>
</div>
<div id="loading-screen-custom" style="height: 110px; padding-top: 10%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div id="reports_area"></div>
<?php
}
//--------------------------------------------
?>