<?php
include '../library/config.php';
include '../classes/class.orders.php';
include '../classes/class.products.php';
include '../classes/class.payments.php';
include '../classes/class.clients.php';
include '../classes/class.mobile.php';

$clients = new Clients();
$orders = new Orders();
$products = new Products();
$payment = new Payments();
$mobile = new Mobile();

if(isset($_POST['display_history_payment'])){
$start = $_POST['date_start'];
  $end = $_POST['date_end'];
$list = $payment->get_paymenthistory($start, $end);
  ?>
  <br/>
  <hr>
  <br/>
            <div class="table-title add-marg">Payment History (<?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?>)</div>
                <table class="table table-responsive table-striped table-hover" id="paymenthistory">
                    <thead>
                    <tr>
                      <th class="column_one">Client</th>
                      <th class="column_two">Mode of Payment</th>
                      <th class="column_three">Invoice ID</th>
                      <th class="column_four">Amount</th>
                      <th class="column_five">Date Received</th>
                    </tr>
                    </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr id="<?php echo $values['payment_id'];?>">
                        <td class="column_one"><?php echo $values['client_name'];?></td>
                        <td class="column_two"><?php echo $values['paymode_name'];?></td>
                        <td class="column_three"><?php echo $values['invoice_id'];?></td>
                        <td class="column_four"><?php echo $values['payment_amount'];?></td>
                        <td class="column_five"><?php echo date('F d, Y', strtotime($values['payment_date']));?></td>
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
            $('#paymenthistory').dataTable(
                {language: {searchPlaceholder: "Search from list" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['load_payment_search'])){
$client_id = $_POST['client_id'];
$list = $payment->get_search_payment($client_id);
  ?>
  <br/>
  <hr>
  <br/>
  <br/><table class="table table-responsive table-striped table-hover" id="paymentrecord">
  <div class="table-title"><?php echo $clients->get_client_name($client_id);?></div>
                    <thead>
                    <tr>
                      <th class="column_one">Client</th>
                      <th class="column_two">Mode of Payment</th>
                      <th class="column_three">Invoice ID</th>
                      <th class="column_four">Amount</th>
                      <th class="column_five">Date Received</th>
                      <th class="column_six">Time Received</th>
                    </tr>
                    </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr class="payment_row" id="<?php echo $values['payment_id'];?>" onclick="popup_show_two()">
                        <td class="column_one"><?php echo $values['client_name'];?></td>
                        <td class="column_two"><?php echo $values['paymode_name'];?></td>
                        <td class="column_three"><?php echo $values['invoice_id'];?></td>
                        <td class="column_four"><?php echo $values['payment_amount'];?></td>
                        <td class="column_five"><?php echo date('F d, Y', strtotime($values['payment_date']));?></td>
                        <td class="column_six"><?php echo $values['payment_time'];?></td>
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
            $('#paymentrecord').dataTable(
                {language: {searchPlaceholder: "Search from list" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['display_history_invoice'])){
$start = $_POST['date_start'];
  $end = $_POST['date_end'];
  $list = $payment->get_invoicehistory($start, $end);
  ?>
    <br/>
      <hr>
      <br/>
            <div class="table-title add-marg">Invoice History (<?php echo date('F d, Y', strtotime($start))." - ".date('F d, Y', strtotime($end));?>)</div>
                <table class="table table-responsive table-striped table-hover" id="invoicelist">
                  <thead>
                  <tr>
                    <th class="column_one">Invoice No.</th>
                    <th class="column_two">Client</th>
                    <th class="column_three">Order No. </th>
                    <th class="column_four">Total Balance</th>
                    <th class="column_five">Remaining Balance</th>
                    <th class="column_six">Due Date</th>
                  </tr>
                  </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr class="unpaid_selected" id=<?php echo $values['invoice_id'];?> onclick="popup_show()">
                        <td class="column_one"><?php echo $values['invoice_id'];?></td>
                        <td class="column_two"><?php echo $values['client_name'];?></td>
                        <td class="column_three"><?php echo $values['order_id'];?></td>
                        <td class="column_four"><?php echo $values['total_amount'];?></td>
                        <td class="column_five"><?php echo $values['amount_remaining'];?></td>
                        <td class="column_six"><?php echo date('F d, Y', strtotime($values['date_due']));?></td>
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
            $('#invoicelist').dataTable(
                {language: {searchPlaceholder: "Search from list" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['load_invoice_search'])){
$client_id = $_POST['client_id'];
$list = $payment->get_search_invoice($client_id);
  ?>
  <br/><hr><br/>
            <table class="table table-responsive table-striped table-hover" id="invoicelist">
  <div class="table-title"><?php echo $clients->get_client_name($client_id);?></div>
                  <thead>
                  <tr>
                    <th class="column_one">Invoice No.</th>
                    <th class="column_two">Client</th>
                    <th class="column_three">Order No. </th>
                    <th class="column_four">Total Balance</th>
                    <th class="column_five">Remaining Balance</th>
                    <th class="column_six">Due Date</th>
                  </tr>
                  </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr class="unpaid_selected" id=<?php echo $values['invoice_id'];?> onclick="popup_show()">
                        <td class="column_one"><?php echo $values['invoice_id'];?></td>
                        <td class="column_two"><?php echo $values['client_name'];?></td>
                        <td class="column_three"><?php echo $values['order_id'];?></td>
                        <td class="column_four"><?php echo $values['total_amount'];?></td>
                        <td class="column_five"><?php echo $values['amount_remaining'];?></td>
                        <td class="column_six"><?php echo date('F d, Y', strtotime($values['date_due']));?></td>
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
            $('#invoicelist').dataTable(
                {language: {searchPlaceholder: "Search from list" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['display_date_invoice'])){
  ?>

<div id="sub-container">
  <div id="full-select">
      <div class="select-container select-with-button">
          <?php
          $clientlist = $clients->get_clients();
          if($clientlist){
            ?>
                <h3>Select Client:</h3>
                <select class="" name="invoice-client-select" id="invoice-client-select" style="width: 100%;">
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
          <input type="button" class="material-button-main" id="search_invoice_btn" value="Search">
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

if(isset($_POST['display_date_payment'])){
  ?>

<div id="sub-container">
  <div id="full-select">
      <div class="select-container select-with-button">
          <?php
          $clientlist = $clients->get_clients();
          if($clientlist){
            ?>
                <h3>Select Client:</h3>
                <select class="" name="payment-client-select" id="payment-client-select" style="width: 100%;">
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
          <input type="button" class="material-button-main" id="search_payment_btn" value="Search">
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

if(isset($_POST['decline_pdc'])){
  $pdc_no = $_POST['pdc_no'];
  $result = $payment->pdc_decline($pdc_no, $_SESSION['userid']);
  $payment->remove_pdc_line($pdc_no);
  echo $result;
}

if(isset($_POST['update_pdc_status'])){
  $pdc_no = $_POST['pdc_no'];
  $result = $payment->pdc_received($pdc_no, $_SESSION['userid']);
  echo $result;
}

if(isset($_POST['load_payments_made'])){
  $invoice_id = $_POST['row_id'];
  $list = $payment->get_invoice_payments($invoice_id);
  ?>
  <table class="table table-responsive table-striped table-hover light-border">
  <tr>
    <th>Type</th>
    <th>Amount</th>
    <th>Date</th>
  </tr>
  <?php
  if($list){
    foreach ($list as $value) {
    ?>
    <tr>
      <td><?php echo $value['paymode_name'];?></td>
      <td><?php echo "P".$value['payment_amount'];?></td>
      <td><?php echo date('F d, Y', strtotime($value['payment_date']));?></td>
    </tr>
    <?php
  }
  }
  else{
    ?>
    <tr>
      <td colspan="3" style="color: red; text-align: center">NO PAYMENTS MADE FOR THIS INVOICE</td>
    </tr>
    <?php
  }
  ?>
  </table>
  <?php
}

if(isset($_POST['get_invoice_details'])){
    $id = $_POST['row_id'];
    $result = $payment->get_pay_invoice($id);
    echo json_encode($result);
}
if(isset($_POST['get_payment_details'])){
    $id = $_POST['row_id'];
    $result = $payment->get_payment_selected($id);
    echo json_encode($result);
}

if(isset($_POST['remove_payment'])){
    $id = $_POST['row_id'];
    $result = $payment->remove_payment($id);
    echo $result;
}

if(isset($_POST['submit_pay'])){
  $client_id = $_POST['client_id'];
  $invoice = $_POST['invoice'];
  $amt_remain = $_POST['amt_remain'];
  $paymode = $_POST['paymode'];
  $amount = $_POST['amount'];
  $curdate=date("Y-m-d");
  $msg = "";
  if($paymode==41){
    $pdc_no = $_POST['pdc_no'];
    $pdc_date = $_POST['pdc_date'];
    $pdc_bank = $_POST['pdc_bank'];
    if($pdc_no==""){
      $msg.="* Please enter a valid PDC No. \n";
    }
    if($pdc_date==null || $curdate>=$pdc_date){
      $msg.="* Please enter a valid PDC Date. \n";
    }
    if($pdc_bank==""){
      $msg.="* Please enter bank name. \n";
    }
  }else if($paymode==40){
    $pdc_no = "none";
    $pdc_date = "none";
  }
  if($amount=="" || $amount<=0){
    $msg.="* Please enter a valid amount \n";
  }
  if($amount>$amt_remain){
    $msg.="* Amount to be received exceeds unpaid amount \n";
  }
  if($msg!=""){
    echo $msg;
  }else{
    if($paymode==40){
      $payment_id=$payment->insert_cash_payment($invoice,$client_id,$amount,$paymode,$_SESSION['userid']); 
      $medrep_id = $payment->get_payment_medrep($invoice);
      $mobile->updatePaymentCount($medrep_id);
    }else if($paymode==41){
      $payment_id = $payment->insert_pdc_payment($invoice,$client_id,$amount,$paymode,$pdc_no,$pdc_date,$pdc_bank,$_SESSION['userid']);
      $medrep_id = $payment->get_payment_medrep($invoice);
      $mobile->updatePaymentCount($medrep_id);
    }
    //I UPDATE NLNG PATI ANG PDC. . .MABALIK LANG ANG AMOUNT IF NAG DECLINE OR NAG BOUNCE ANG PAYMENT KA PDC MA INCREMENT LNG KA REMAINING BALANCE BALE OTHER WAT AROUND
    //MA BASE LANG SA STATUS KA PDC, IF RECEIVED NGA "1" SO OKAY PERO MAG DECLINE "0" ANG BALANCE KA PDC MABALIK SA IYA D BALANCE AND MADULA SYA SA PENDING PDC (REMOVE)
    $payment->update_invoice_pay($invoice,$amount,$_SESSION['userid']); 
    echo "validated";
  }
}

if(isset($_POST['show_pdc'])){
  ?>
  <h5>PDC No.:</h5>
  <input type="text" class="material-input pdc_val" id="pdc_no" placeholder="PDC No." name="pdc_no"><br/>
  <h5>PDC Date:</h5>
  <input type="date" class="material-input" name="pdc_date" id="pdc_date">
  <h5>Bank Name:</h5>
  <input type="text" class="material-input" id="pdc_bank" placeholder="Bank Name" name="pdc_bank">
  <hr>

  <script>
    
    $('.pdc_val').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });

  </script>
  <?php
}

if(isset($_POST['load_payment'])){
  $invoice_id=$_POST['invoice_id'];
  $client_id = $_POST['client_id'];
  ?>
  <br/>
  <div class="table-title">Invoice No. <?php echo $invoice_id;?></div>
  <input type="hidden" id="inv_no" value=<?php echo $invoice_id;?>>
  <table class="table table-responsive table-striped" id="invoice-info" >
  <thead>
  <tr>
    <th class="column_one">Product Name</th>
    <th class="column_two">Lot No.</th>
    <th class="column_three">Expiry Date</th>
    <th class="column_four">Quantity</th>
    <th class="column_five">Unit Price</th>
    <th class="column_six">Subtotal</th>
    <th class="column_seven">Discount</th>
    <th class="column_seven">Total</th>
  </tr>
  </thead>
  <tbody>
  <?php
    $content = $payment->get_invoice_items($invoice_id);
    if($content){
      foreach($content as $row){
  ?>
  <tr>
    <td class="column_one"><?php echo $row['pro_name'];?></td>
    <td class="column_two"><?php echo $row['lot_number'];?></td>
    <td class="column_three"><?php echo date('F d, Y', strtotime($row['expiry_date']));?></td>
    <td class="column_four"><?php echo $row['total_qty'];?></td>
    <td class="column_five"><?php echo $row['pro_unit_price'];?></td>
    <td class="column_six"><?php echo $row['subtotal'];?></td>
    <td class="column_seven"><?php echo $row['discount'];?></td>
    <td class="column_seven"><?php echo $row['total'];?></td>
  </tr>
  <?php
  }
  }
  ?>
  </tbody>
  </table>
  <br/>
  <table class="table table-responsive table-striped " id="content-info">
  <thead>
  <tr>
    <th class="column_one">Client Name</th>
    <th class="column_two">Date Issued</th>
    <th class="column_three">Date Due</th>
    <th class="column_four">Total Amount</th>
    <th class="column_five">Amount Paid</th>
    <th class="column_six">Remaining Balance</th>
  </tr>
  </thead>
  <tr>
  <?php
    $info = $payment->get_pay_invoice($invoice_id);
  ?>
    <td class="column_one"><?php echo $payment->get_pay_client($client_id);?></td>
    <td class="column_two"><?php echo date('F d, Y', strtotime($info['date_issued']));?></td>
    <td class="column_three"><?php echo date('F d, Y', strtotime($info['date_due']));?></td>
    <td class="column_four"><?php echo $info['total_amount'];?></td>
    <td class="column_five"><?php echo $info['amount_paid'];?></td>
    <td class="column_six"><input type="hidden" value=<?php echo $info['amount_remaining'];?> id="amt_remain"><?php echo $info['amount_remaining'];?></td>
  </tr>
  </tbody>
  </table>
  <br/>
  <div id="payment_area">
  <form method="post" id="form_payment">
  <input type="hidden" name="submit_pay" value=1>
  <input type="hidden" name="client_id" value=<?php echo $client_id;?>>
  <?php 
    $mode_list = $payment->get_paymode();
    if($mode_list){
      $ctr=0;
      foreach($mode_list as $mode){
        if($ctr==0){
        ?>
         <h5>Select Payment Mode:</h5><br/>
         <label>
         <input type="radio" id="rdo_cash" name="paymode" class="radio-btn" value=<?php echo $mode['paymode_id'];?> checked><?php echo $mode['paymode_name'];?>
         </label>
         <br>
        <?php
        $ctr++;
        }else{
          ?>
          <label>
          <input type="radio" id="rdo_pdc" name="paymode"  class="radio-btn" value=<?php echo $mode['paymode_id'];?>><?php echo $mode['paymode_name'];?><br>
          </label>
          <?php
        }
      }
      ?>
      <hr>
        <div id="pdc_area"></div>
        <h5>Received Amount:</h5>
      <input type="text" id="amount_whole" name="amount" class="material-input text18 price_valid" placeholder="Amount" step=".01" min="1">
        <div class="material-button-wrapper w-100 ">
          <input type="button" name="btn_submit_payment float-right" class="material-button-main" id="btn_submit_payments" value="Submit Payment" maskedFormat="5,2">
        </div>
      <?php
    }
  ?>
  </form>
  </div>
 <script>


 
function check_dec(field){
  setTimeout(function(){
      var regex = /\d*\.\?\d?/g;
      field.value = regex.exec(field.value);
  }, 0);

}

  $('#content-info').dataTable(
    {"bFilter": false, "bInfo": false, "bLengthChange" : false, "bPaginate": false, "bSort": false});
  $('#invoice-info').dataTable(
    {"bFilter": false, "bInfo": false, "bLengthChange" : false, "bPaginate": false, "bSort": false});

  $('.price_valid').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9\.]/.test(String.fromCharCode(e.which)); // numbers
    });


  </script>
  
  <?php
}

if(isset($_POST['load_invoice'])){
$client_id=$_POST['client_id'];
$invlist = $payment->get_client_invoice($client_id);
if($invlist){
?>
<div id="full-select">
    <div class="select-container select-with-button" >
        <h3>Select Invoice No.:</h3>
        <select class="invoice-select" name="invoice" id="select-invoice" style="width: 100%;">
            <?php
                foreach($invlist AS $invrow){ 
            ?>
              <option value=<?php echo $invrow['invoice_id'];?>><?php echo $invrow['invoice_id'];?></option>
            <?php
              }
            ?>
        </select>
        </div>
        <div class="material-button-wrapper">
        <button class="material-button-main" id="load-payment-btn">Make Payment</button>
        </div>
    </div>
  <?php
}else{
  echo "No Invoice Exists for this client!";
}
}

if(isset($_POST['display_receive_payment'])){
  ?>
  <div id="full-select">
    <div class="select-container select-with-button" >
        <h3>Select Client:</h3>
        <select class="client-select" name="client" id="select-client" style="width: 100%;">
            <?php
                $clientlist = $clients->get_clients();
                if($clientlist){
                foreach($clientlist AS $clientrow){ 
            ?>
              <option value=<?php echo $clientrow['client_id'];?>><?php echo $clientrow['client_name'];?></option>
            <?php
              }
            }else{
            ?>
              <option value="" selected disabled>NO CLIENTS EXIST</option>
            <?php
            }
            ?>
        </select>
        </div>
        <div class="material-button-wrapper">
        <button class="material-button-main" id="load-invoice-btn">Load Invoice</button>
        </div>
    </div>
    <div id="sub-full-select"></div>
    <div id="subsub-container"></div>
  <?php
}


if(isset($_POST['display_record_payment'])){
$list = $payment->get_all_payment();
  ?>
            <div class="table-title">Payment Records</div>
                <table class="table table-responsive table-striped table-hover" id="paymentrecord">
                    <thead>
                    <tr>
                      <th>Client</th>
                      <th>Mode of Payment</th>
                      <th>Invoice ID</th>
                      <th>Amount</th>
                      <th>Date Received</th>
                    </tr>
                    </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr>
                        <td><?php echo $payment->get_payment_client($values['invoice_id']);?></td>
                        <td><?php echo $values['paymode_name'];?></td>
                        <td><?php echo $values['invoice_id'];?></td>
                        <td><?php echo $values['payment_amount'];?></td>
                        <td><?php echo date('F d, Y', strtotime($values['payment_date']));?></td>
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
            $('#paymentrecord').dataTable(
                {language: {searchPlaceholder: "Search Payment" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['display_invoice_list'])){
$list = $payment->get_all_invoice();
  ?>
            <div class="table-title add-marg">Invoice List</div>
                <table class="table table-responsive table-striped table-hover" id="invoicelist">
                  <thead>
                  <tr>
                    <th class="column_one">Invoice No.</th>
                    <th class="column_two">Client</th>
                    <th class="column_three">Order No. </th>
                    <th class="column_four">Total Balance</th>
                    <th class="column_five">Remaining Balance</th>
                    <th class="column_six">Due Date</th>
                  </tr>
                  </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr class="unpaid_selected" id=<?php echo $values['invoice_id'];?> onclick="popup_show()">
                        <td class="column_one"><?php echo $values['invoice_id'];?></td>
                        <td class="column_two"><?php echo $values['client_name'];?></td>
                        <td class="column_three"><?php echo $values['order_id'];?></td>
                        <td class="column_four"><?php echo $values['total_amount'];?></td>
                        <td class="column_five"><?php echo $values['amount_remaining'];?></td>
                        <td class="column_six"><?php echo date('F d, Y', strtotime($values['date_due']));?></td>
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
            $('#invoicelist').dataTable(
                {language: {searchPlaceholder: "Search Invoice" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['display_unpaid_invoice'])){
$list = $payment->get_unpaid_invoice();
  ?>
            <div class="table-title add-marg">Invoice List</div>
                <table class="table table-responsive table-striped table-hover" id="unpaidinvoicelist">
                  <thead>
                  <tr>
                    <th class="column_one">Invoice No.</th>
                    <th class="column_two">Client</th>
                    <th class="column_three">Order No. </th>
                    <th class="column_four">Total Balance</th>
                    <th class="column_five">Remaining Balance</th>
                    <th class="column_six">Due Date</th>
                  </tr>
                  </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr class="unpaid_selected" id=<?php echo $values['invoice_id'];?> onclick="popup_show()">
                        <td class="column_one"><?php echo $values['invoice_id'];?></td>
                        <td class="column_two"><?php echo $values['client_name'];?></td>
                        <td class="column_three"><?php echo $values['order_id'];?></td>
                        <td class="column_four"><?php echo $values['total_amount'];?></td>
                        <td class="column_five"><?php echo $values['amount_remaining'];?></td>
                        <td class="column_six"><?php echo date('F d, Y', strtotime($values['date_due']));?></td>
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
            $('#unpaidinvoicelist').dataTable(
                {language: {searchPlaceholder: "Search Invoice" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}

if(isset($_POST['display_pdc_list'])){
$list = $payment->get_all_pdc();
  ?>
            <div class="table-title add-marg">Pending Post Dated Cheques</div>
                <table class="table table-responsive" id="pdclist">
                  <thead>
                  <tr>
                    <th class="column_one">Client</th>
                    <th class="column_two">Invoice No. </th>
                    <th class="column_three">Amount</th>
                    <th class="column_four">PDC No.</th>
                    <th class="column_five">Date Received</th>
                    <th class="column_five">PDC Date</th>
                    <th class="">Action</th>
                    <th class=""></th>
                  </tr>
                  </thead>  
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr>
                        <td class="column_one"><?php echo $payment->get_payment_client($values['invoice_id']);?></td>
                        <td class="column_two"><?php echo $values['invoice_id'];?></td>
                        <td class="column_three"><?php echo $values['payment_amount'];?></td>
                        <td class="column_four"><?php echo $values['pdc_no'];?></td>
                        <td class="column_five"><?php echo date('F d, Y', strtotime($values['payment_date']));?></td>
                        <td class="column_five"><?php echo date('F d, Y', strtotime($values['pdc_date']));?></td>
                        <td class="ta-center"><input type="button" id=<?php echo $values['pdc_no'];?> class="material-button-main accept_pdc" value="Received"/></td>
                        <td class="ta-center"><input type="button" id=<?php echo $values['pdc_no'];?> class="material-button-main red cancel_pdc" value="Decline"/></td>
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
            $('#pdclist').dataTable(
                {language: {searchPlaceholder: "Search PDC" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
              <?php
}
?>