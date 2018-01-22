<?php
include '../library/config.php';
include '../classes/class.orders.php';
include '../classes/class.products.php';
include '../classes/class.clients.php';
include '../classes/class.delivery.php';
include '../classes/class.mobile.php';

$orders = new Orders();
$delivery = new Delivery();
$products = new Products();
$clients = new Clients();
$mobile = new Mobile();


if(isset($_POST['delivery_create'])){
  $order_id = $_POST['selected_ord_id'];
  $lot = $_POST['lot'];
  $pro_id = $_POST['pro_id'];
  $qty = $_POST['lot_qty'];
  $discount = $_POST['discount'];
  $ctr_main = count($pro_id);
  $ordtype = $orders->get_order_ordtype($order_id);
  $current_product = "";
  $current_discount=0;
  $ctr_lot = count(array_filter($lot));
  $ctr_discount = count(array_filter($discount,"valid_number"));
  $qty_count = 0;

  /*$txt="";
  for($x=0; $x<$ctr_main; $x++){
      if($qty[$x]>0){
        $txt.=$pro_id[$x]."=>".$lot[$x]."=>".$qty[$x]."\n";
      }
    }
    echo $txt;
  }*/
  if($ctr_main==$ctr_lot){
    //INSERT TO DELIVERY WITH STATUS 0 PENDING
    $medrep_id = $orders->get_order_medrep($order_id);

    $delivery_id = $delivery -> add_delivery($order_id, $medrep_id, $_SESSION['userid']);

    //add counter para sa mobile
    $mobile -> updateDeliveryCount($medrep_id);

    for($x=0; $x<$ctr_main; $x++){
      if($qty[$x]>0){

        //INSERT DELIVERY ITEMS UNDER THIS DELIVERY
        $unit_price = $products->get_unit_price($pro_id[$x]);
        //UPDATE ORDER ITEMS LOT NUMBER tbl_orditem
        $ordstatus = $orders->get_order_status($order_id);
          
        if($current_product==""){
          $current_discount=$discount[$x];
          $current_product=$pro_id[$x];        

          if(($orders->count_orditem_with_lot_record($order_id,$pro_id[$x]))==0){
          $delivery->update_orditem_first_record($order_id, $ordstatus, $ordtype, $pro_id[$x], $lot[$x], $qty[$x], $current_discount, $_SESSION['userid']);
          }else{
            if(($orders->count_orditem_lot($order_id,$pro_id[$x],$lot[$x]))>0){
            $delivery->update_orditem_lot($order_id, $ordstatus, $ordtype, $pro_id[$x], $lot[$x], $qty[$x], $current_discount, $_SESSION['userid']);
            }else{
              //CHECK IF MAY EXISTING NA NA ANY RECORD OF LOT OR WALA
              if(($orders->count_orditem_with_lot_record($order_id,$pro_id[$x]))>0){
                //MINUS ANG TOTAL SANG SA PREVIOUS NA GA HOLD SANNG TNAN2 NA BILIN
                $prev_total = $orders->get_previous_orditem_total($order_id,$pro_id[$x]);
                $prev_lot = $orders->get_previous_orditem_lot($order_id,$pro_id[$x]);
                //UPDATE OLD NA TOTAL QTY and TOTAL VALUE
                $orders->update_previous_orditem_total($order_id,$pro_id[$x],$prev_lot,$current_discount);
                
                $prev_new_delivery = $orders->get_previous_new_delivery($order_id,$pro_id[$x],$prev_lot);
                $append_total = $prev_total-$prev_new_delivery;
                $delivery->append_orditem($order_id, $ordtype, $pro_id[$x], $lot[$x], $append_total, $qty[$x], $current_discount, $_SESSION['userid']);
              }
          }
        }

        }else if($current_product!=$pro_id[$x]){
          $current_discount=$discount[$x];
        
          if(($orders->count_orditem_with_lot_record($order_id,$pro_id[$x]))==0){
          $delivery->update_orditem_first_record($order_id, $ordstatus, $ordtype, $pro_id[$x], $lot[$x], $qty[$x], $current_discount, $_SESSION['userid']);
          }else{
            if(($orders->count_orditem_lot($order_id,$pro_id[$x],$lot[$x]))>0){
            $delivery->update_orditem_lot($order_id, $ordstatus, $ordtype, $pro_id[$x], $lot[$x], $qty[$x], $current_discount, $_SESSION['userid']);
            }else{
              //CHECK IF MAY EXISTING NA NA ANY RECORD OF LOT OR WALA
              if(($orders->count_orditem_with_lot_record($order_id,$pro_id[$x]))>0){
                //MINUS ANG TOTAL SANG SA PREVIOUS NA GA HOLD SANNG TNAN2 NA BILIN
                $prev_total = $orders->get_previous_orditem_total($order_id,$pro_id[$x]);
                $prev_lot = $orders->get_previous_orditem_lot($order_id,$pro_id[$x]);
                //UPDATE OLD NA TOTAL QTY and TOTAL VALUE
                $orders->update_previous_orditem_total($order_id,$pro_id[$x],$prev_lot,$current_discount);
                
                $prev_new_delivery = $orders->get_previous_new_delivery($order_id,$pro_id[$x],$prev_lot);
                $append_total = $prev_total-$prev_new_delivery;
                $delivery->append_orditem($order_id, $ordtype, $pro_id[$x], $lot[$x], $append_total, $qty[$x], $current_discount, $_SESSION['userid']);
              }
          }
        }
        
        }


        if($current_product==$pro_id[$x]&&$qty_count>0){
          if(($orders->count_orditem_lot($order_id,$pro_id[$x],$lot[$x]))>0){
            $delivery->update_orditem_lot($order_id, $ordstatus, $ordtype, $pro_id[$x], $lot[$x], $qty[$x], $current_discount, $_SESSION['userid']);
            }else{
              //CHECK IF MAY EXISTING NA NA ANY RECORD OF LOT OR WALA
              if(($orders->count_orditem_with_lot_record($order_id,$pro_id[$x]))>0){
                //MINUS ANG TOTAL SANG SA PREVIOUS NA GA HOLD SANNG TNAN2 NA BILIN
                $prev_total = $orders->get_previous_orditem_total($order_id,$pro_id[$x]);
                $prev_lot = $orders->get_previous_orditem_lot($order_id,$pro_id[$x]);
                //UPDATE OLD NA TOTAL QTY and TOTAL VALUE
                $orders->update_previous_orditem_total($order_id,$pro_id[$x],$prev_lot,$current_discount);
                
                $prev_new_delivery = $orders->get_previous_new_delivery($order_id,$pro_id[$x],$prev_lot);
                $append_total = $prev_total-$prev_new_delivery;
                $delivery->append_orditem($order_id, $ordtype, $pro_id[$x], $lot[$x], $append_total, $qty[$x], $current_discount, $_SESSION['userid']);
              }
          }
        }

        

        $current_product=$pro_id[$x];
        $delivery->insert_delivery_item($delivery_id,$pro_id[$x],$lot[$x],$unit_price,$qty[$x],$current_discount);
        //UPDATE ACTUAL QUANTITY OF LOT NUMBER
        $products->update_delivered_lot($lot[$x], $qty[$x]);
        //UPDATE ACTUAL QUANTITY OF TOTAL PRODUCT
        $products->update_delivered_product($pro_id[$x],$qty[$x],$_SESSION['userid']);
        
        $qty_count++;
      
       }
      }
    if($qty_count>0){
      //UPDATE ORDER TOTAL 
      $orders->update_total($order_id);
      //UPDATE ORDER STATUS tbl_order
      $delivery->update_ordstatus_two($order_id, $_SESSION['userid']);
      //display sa prompt
      $client_id = $orders->get_order_client_id($order_id);
      $clients->insert_delivery_notif($client_id, $order_id);
      echo $delivery_id;
    }else{
      echo "empty";
    }
  }
  else{
    echo "unable";
  }
}

if(isset($_POST['set_delivery_action'])){
  $order_id = $_POST['approved_order_id'];
  $pending = $orders->get_orditems($order_id);
?>
  <!--CONTENT-->
    <input type="hidden" value="1" name="delivery_create"/>
    <input type="hidden" value="<?php echo $order_id?>" name="selected_ord_id"/>
    <h3 style="font-weight:bold;">Delivery for Order #<?php echo $order_id;?></h3>
    <table class="table table-responsive table-striped light-border" id="pendingtable">
      <thead>
        <th class="center" style="max-width: 5px;width: 5px;">#</th>
        <th class="normal" style="max-width: 200px;width: 200px;">Product</th>
        <th class="normal column_three" style="max-width: 180px;width: 180px;">Formulation</th>
        <th class="center column_four" style="max-width: 70px;width: 70px;">Unit<br/>Price</th>
        <th class="center column_five" style="max-width: 70px;width: 70px;">Remaining Order</th>
        <th class="center column_six">Lot No. / Expiry / Available<br/>(To be Delivered)</th>
        <th class="center column_six" style="max-width: 65px;width: 65px;">Quantity</th>
        <th class="center column_seven" style="max-width: 65px;width: 65px;">Discount(%)</th>
        <th class="center column_seven" style="max-width: 50px;width: 50px;">Action</th>
      </thead>
      <!--Pending Items-->
      <tbody class="set_delivery_container">
      <?php
      $row_ctr=1;

      foreach($pending as $value){
        if(($value['sumtotal']-$value['sumdelivery'])>0){
        ?>
        <tr id=<?php echo $value['pro_id']; ?> class=<?php echo $value['pro_id'];?> >
          <td><?php echo $row_ctr; ?></td>
          <td><input type="hidden" name="pro_id[]" value=<?php echo $value['pro_id']; ?> > <?php echo $value['pro_brand']."-".$value['pro_generic']; ?></td>
          <td><?php echo $value['pro_formulation']; ?></td>
          <td class="center"><?php echo $value['pro_unit_price']; ?></td>
          <td class="center"><input type="hidden" class="pro_total_row" value=<?php echo $value['sumtotal']-$value['sumdelivery']; ?> > <?php echo $value['sumtotal']-$value['sumdelivery']; ?></td>
          <td class="row_lot_line">
            <?php
              $lotlist=$products->get_specific_available_lots_not_expiring($value['pro_id']);
              $lotlist_ctr=0;
              $lotlist_qty=0;
              if($lotlist){
                ?>
                <select name="lot[]" class=<?php echo "lot".$value['pro_id'];?> style="cursor:pointer;">
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
                <select name="lot[]" class="bg_red" style="cursor:pointer;">
                  <option value="" selected>No Available Lot No.</option>
                </select>
                <?php
              }
            ?>
          </td>
          <td><input  style="text-align: center; " type="number" class=<?php echo "qty".$value['pro_id']; ?> value="1" min=1 name="lot_qty[]" id="log_qty_valid"></td>
          <td><input  style="text-align: center;" class="discount_perc" type="number" name="discount[]" min="0" value="0" placeholder="Discount" onchange="handleChange(this);" /></td>
          <td>
            <?php
            if($lotlist_ctr=1 && $lotlist_qty>=$value['qty_total']){
              echo "N/A";
            }else{
              ?>
              <input type="button" class="btn_add_lot" value="Add Row">
            <?php
            }
            ?>
            
          </td>
        </tr>


        <?php 
        $row_ctr++;
      }
      }
      ?>
      </tbody>
      </table>
      

  <!--BUTTONS-->
  <div class="material-button-wrapper ta-center">
  <button type="button" class="material-button-main" onclick="edit_popup_hide_two_approved()" style="float: right;">Cancel</button>
  <button type="button" id="btn_save_delivery" class="material-button-main" style="float: right; background: #4c4cff;margin-right: 5px;">Send for Delivery</button>
  </div>

<?php
}


if(isset($_POST['load_order_search'])){
  $client_id = $_POST['client_id'];
  $list = $orders->get_search_order($client_id);
  ?>
  <hr/>
  <br/>
  <table class="table table-responsive table-striped table-hover" id="orderlist">
  <div class="table-title"><?php echo $clients->get_client_name($client_id);?></div>
  <thead>
    <th class="column_one">Order #</th>
    <th class="column_two">Client</th>
    <th class="column_three">Type</th>
    <th class="column_four">Payment Term</th>
    <th class="column_five">Date Ordered</th>
    <th class="column_six">Total</th>
  </thead>
  <tbody>
  <?php
  if($list){
    foreach($list as $values){
      ?>
      <tr onclick="edit_popup_show_three()" id=<?php echo $values['order_id'];?> class="history_selected">
          <td class="column_one"><?php echo $values['order_id'];?></td>
          <td class="column_two"><?php echo $values['client_name'];?></td>
          <td class="column_three"><?php echo $values['ordtype_name'];?></td>
          <td class="column_four"><?php echo $values['term_name'];?></td>
          <td class="column_five"><?php echo date('F d, Y', strtotime($values['order_date']));?></td>
          <td class="column_six"><?php echo $values['total_amount'];?></td>
      </tr>
      <?php
    }
  }
  ?>
  </tbody>
  <script>
            $('#orderlist').dataTable(
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
                <select class="" name="order-client-select" id="order-client-select" style="width: 100%;">
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

//MAG SELECT KA RADIO BUTTON MA CHANGE ANG CONTENT KA TERMS OF PAYMENT
if(isset($_POST['payterm_radio'])){
  $terms_list = $orders->get_terms();
    if($terms_list){
 ?>
  <select class="material-input-dd" placeholder="Select Terms of Payment" name="terms" id="terms" style="width: 100%;">
   <?php
    foreach($terms_list as $terms_row){
  ?>
   <option value=<?php echo $terms_row['term_id'];?>><?php echo $terms_row['term_name'];?></option>
  <?php
  }
  ?>
  </select>
   <?php
  } 
}
if(isset($_POST['payterm_radio_consignment'])){
  $terms_list = $orders->get_terms_consigned();
    if($terms_list){
 ?>
  <select class="material-input-dd" placeholder="Select Terms of Payment" name="terms" id="terms" style="width: 100%;">
   <?php
    foreach($terms_list as $terms_row){
  ?>
   <option value=<?php echo $terms_row['term_id'];?>><?php echo $terms_row['term_name'];?></option>
  <?php
  }
  ?>
  </select> 
   <?php
  } 
}
if(isset($_POST['remove_order'])){
  $id = $_POST['row_id'];
  $orders -> decline_order($id, $_SESSION['userid']);
  $client_id = $orders->get_order_client_id($id);
  $clients->insert_decline_notif($client_id, $id);
  //$orders -> remove_orditems($id);
  echo json_encode($id);
}

if(isset($_POST['approve_order_action'])){
  $id = $_POST['row_id'];
  $orders -> approve_order($id, $_SESSION['userid']);
  //$orders -> remove_orditems($id);
  $client_id = $orders->get_order_client_id($id);
  $clients->insert_approved_notif($client_id, $id);
  echo json_encode($id);
}

if(isset($_POST['get_specific_order'])){
  $id = $_POST['row_id'];
  $result = $orders->get_specific_order($id);
  echo $result;
}

if(isset($_POST['get_order_orditems'])){
  $id = $_POST['row_id'];
  $result = $orders->get_orditems($id);
  ?>
  
  <form method="POST" id="update_order_form" name="update_order_form" style="width: 100%; margin-left: -30px;">
  <table id="dynamic_table" class="popup_table form-row border-out" border="1" >
  
  <th id="prod_ord_header" style="max-width: 500px" ><h6>Products</h6></th>
  <th style="max-width: 500px;" class="word-wrap edit_hidden"><h6>Formulation</h6></th>
  <th class="center edit_hidden"><h6>Unit Price</h6></th>
  <th style="padding-left:10px;width: 50px;"><h6 style="text-align:center;">Qty</h6></th>
  <th class="center edit_hidden"><h6>Total</h6></th>
  
  <?php
  $row_ctr=1;
  foreach($result as $value){
    ?>
    <tr>
      <td style="padding-left:8px;"><span class="display_order_product"><?php echo $value['pro_brand']."-".$value['pro_generic'];?></span><div class="hide_select2">
        <select name="select_pro_id[]" class="edit_order_select" id="wide-select">
        <?php
        $prod_list = $products->get_products();
        
        if($prod_list){
          ?>
          <?php
              foreach($prod_list AS $prod_row){
                if($prod_row['pro_id']==$value['product_id']){
          ?>
          <option selected value=<?php echo $prod_row['pro_id'];?>><?php echo $prod_row['pro_brand']." (".$prod_row['pro_generic'].") - ".$prod_row['pro_formulation']." / ".$prod_row['pro_packaging'];?></option>
          <?php
                }else{
                  ?>
<option value=<?php echo $prod_row['pro_id'];?>><?php echo $prod_row['pro_brand']." (".$prod_row['pro_generic'].") - ".$prod_row['pro_formulation']." / ".$prod_row['pro_packaging'];?></option>
                  <?php
                }
          }

      }
      ?>
        </select>

    </div>

      <td  class="edit_hidden"><?php echo $value['pro_formulation'];?></td>
    </td>
     

      <td class="center edit_hidden"><?php echo $value['pro_unit_price'];?></td>
       <td style="text-align:center;"><span class="display_order_qty"><?php echo $value['qty_total'];?></span><input class="edit_order_qty" type="text" style="text-align: center; width: 30px;" value="<?php echo $value['qty_total'];?>" name="qty[]"/></td>
      <td class="center edit_hidden"><?php echo $value['total'];?></td>
      <td class="remove_order_btn "value="<?php echo $value['product_id'];?>" style="font-size: 24px;color: red; cursor: pointer; margin-top: 3px; height: 100%;">&times;</td>
      
    </tr>
<?php 
$row_ctr++;
}
?>
   <tr class="edit_hidden">
  <td colspan="4" class="right" style="border-top:1px solid #cacaca;padding-bottom: 8px;"><b>Status:</b>
  <?php 
    if($orders->get_order_status($id)==0){
      echo "Pending";
    }else{
      echo "Delivered";
    } 
  ?>
  </td>
  <td colspan="1" class="right" style="border-top:1px solid #cacaca;padding-bottom: 8px;padding-right: -5px;"><b>Total Amount:</b> <?php echo "P".$orders->get_orditems_sum($id);?></td>
  </tr>
  
 
  </table>

  <td colspan="2"><button type="button" name="add" id="add_row" class="material-button">Add Product</button>
  <div class="material-button-wrapper ta-center">
        <button class="edit_order_btn material-button-main" type="button" style="float: left; margin-right: 8px;">Edit
        </button>
        <button class="save_order_btn material-button-main" type="button" style="float: left; margin-right: 8px;">Save
        </button>
        
        <button type="button" class="material-button-main" style="float: right;" id="approve_order_btn">Approve Order</button>
        <button type="button" class="material-button-main" style="float: right;background-color: red;margin-right: 8px" id="remove_row">Decline Order</button>
        <!--<button type="button" class="material-button-main" onclick="edit_popup_hide()" style="float: right;">Close</button>-->
      </div>
      </form>
  <script>
    $(document).ready(function(){
      //default check ang order litem na list dapat may unod

      var i=0;
      var ctr=0;
      $(document).on("click","#add_row", function(){
            i++;
      var sqlStatement = "";
            var products_select = $(".edit_order_select");
              $(".remove_order_btn").css("display","block");
              if(products_select.length==1){
                $(".remove_order_btn").css("display","none");
              }else{
                $(".remove_order_btn").html("&times;");
                $(".remove_order_btn").css("display","block");

              }
            //CHECKER
            for(var x = 0; x < products_select.length; x++){
               if(x==0&&products_select.length==1){
                sqlStatement+=$(products_select[x]).val()+")";  
              }else if(x==products_select.length-1){
                sqlStatement+=$(products_select[x]).val()+")";
              }else{
                sqlStatement+=$(products_select[x]).val()+",";
              }       
            }
    
            $('#dynamic_table').append('<tr id="appended_row'+i+'"></tr>');
            $.ajax({
              url: "orders/process.php",
              type: "POST",
              async: false,
              data: {
                "append_new_row": 1,
                "i": i,
                "sqlStatement": sqlStatement
              },
              success: function(data){
                //alert(data);
                  $("#appended_row"+i).html(data);
                  $(".edit_order_select").select2();
              }
            });  
      });
      //$('.edit_order_select').select2();
      popupCheck();

      $(document).on("click", ".edit_order_btn", function(){
        $('#status_order_btn').val(1);
        popupCheck();
      });

      $(document).on("click", ".save_order_btn", function(){
        //CONFIRM AND SAVE TO DATABASE UPDATE
          var prod_check = $(".edit_order_select");
          var qty_check = $(".edit_order_qty");
          var confirm_check = true;
          var x=0;
          while(x<qty_check.length){
            if($(qty_check[x]).val()==0){
              confirm_check=false;
            }
            x++;
          }
          if(prod_check.length>0 && confirm_check==true){
            if(confirm("Save changes in the pending order record?")){
              //CHECK RECORD KNG MAY EXISTING ITEMS NA SAME SA MEDICINE ID
              var y = 0;
              var order_id = $("#pending_ordid_selected").val();
              //AJAX SERIALIZE
               $.ajax({
                url: "orders/process.php",
                method: "POST",
                data:$("#update_order_form").serialize()+"&edit_pending_order=1&order_id="+order_id,
                success: function(data){
                   alert("Order #"+order_id+" successfully updated");
                   edit_popup_hide();
                   location.reload();
                }
              });
            }  
          }else{
            alert("There should be at least 1 quantity per order item");
          }
          
      });

      $(document).on("click", ".remove_order_btn", function(){
        //alert($(this).attr("value"));
        var whichtr = $(this).closest("tr");
        whichtr.remove();      

        var prod_check = $(".edit_order_select");
      if(prod_check.length==1){
        $(".remove_order_btn").html("");
        $(".remove_order_btn").css("display","none");
      }else{
        $(".remove_order_btn").css("display","block");
      }
      });
    });

    function popupCheck(){
      var prod_check = $(".edit_order_select");
     
        if($('#status_order_btn').val()==0){
          $('#add_row').hide();
          $('.hide_select2').hide();
          $('.display_order_product').show();
          $('.edit_order_select').hide();
          $('.edit_order_qty').hide();
          $('.display_order_qty').show();
          $('.edit_order_btn').show();
          $('.save_order_btn').hide();
          $('.remove_order_btn').hide();

        }else{
          $('#add_row').show();
          $('.hide_select2').show();
          $('.display_order_product').hide();
          $('.edit_order_select').show();
          $('.edit_order_qty').show();
          $('.display_order_qty').hide();
          $('.edit_order_btn').hide();
          $('.save_order_btn').show();
          $('.remove_order_btn').show();
          $('.remove_order_btn').css('text-align','center');
          $('.extra_show').hide();
          $('#action-header').html("<h6>Remove</h6>");
          $('#action-header').css('max-width','75px');
          $('.edit_hidden').hide();
          $('#prod_ord_header').css('max-width','50%');
          $('#wide-select').css('width','100%');
          $('#dynamic_table').css('width','80%');
         if(prod_check.length==1){
            $(".remove_order_btn").css("display","none");
            $('.none_extra').css('display','block');
            
          }else{
            $(".remove_order_btn").css("display","block");
            $('.none_extra').css('display','none');
          }
          $(".edit_order_select").select2();

        }
      }
  </script>
  <?php
}


if(isset($_POST['edit_pending_order'])){
  $pro_list = $_POST['select_pro_id'];
  $qty = $_POST['qty'];
  $order_id = $_POST['order_id'];
  $pro_ctr=count($pro_list);

  //DELETE ang daan nga wala na sa new order
  $orders->deleteOldPendingOrder($order_id);

  for($x=0; $x<$pro_ctr; $x++){
    $orders->add_orditem($order_id, $pro_list[$x], $qty[$x]);
    echo $qty[$x]."<br/>";
  }

  $orders->update_total($order_id);

}

if(isset($_POST['append_new_row'])){
  $i = $_POST['i'];
  $sqlStatement = $_POST['sqlStatement'];
  if($sqlStatement!=""){
    $sqlStatementQuery="pro_id NOT IN (".$sqlStatement;
  }else{
    $sqlStatementQuery="";
  }
  ?>
  <td><select name="select_pro_id[]" class="edit_order_select">
        <?php
        $prod_list = $products->get_products_selected_order($sqlStatementQuery);
        
        if($prod_list){
          ?>
          <?php
              foreach($prod_list AS $prod_row){
          ?>
          <option value=<?php echo $prod_row['pro_id'];?>><?php echo $prod_row['pro_brand']." (".$prod_row['pro_generic'].") - ".$prod_row['pro_formulation']." / ".$prod_row['pro_packaging'];?></option>
          <?php
          }

      }
      ?>
        </select>
  </td>
  <td style="text-align:center;">
    <input class="edit_order_qty" name="qty[]" type="text" style="text-align: center; width: 30px;" placeholder="0"/>
  </td>
  <td class="remove_order_btn" style="font-size: 24px; color: red; cursor: pointer; text-align: center;">&times;</td>
  
  <?php
  
}


if(isset($_POST['get_history_order_orditems'])){
  $id = $_POST['row_id'];
  $result = $orders->get_orditems($id);
  ?>
  <table class="popup_table form-row" border="1" bordercolor="#cacaca" >
  <th>#</th>
  <th><h6>Products</h6></th>
  <th><h6>Formulation</h6></th>
  <th class="center"><h6>Unit Price</h6></th>
  <th style="padding-left:10px;"><h6>Ordered</h6></th>
  <th style="padding-left:10px;"><h6>Delivered</h6></th>
  <th style="padding-left:10px;"><h6>Returned</h6></th>
  <th class="center"><h6>Discount</h6></th>
  <th class="center"><h6>Total</h6></th>
  <?php
  $row_ctr=1;
  foreach($result as $value){
    ?>
    <tr>
      <td><?php echo $row_ctr;?></td>
      <td><?php echo $value['pro_brand']."-".$value['pro_generic'];?></td>
      <td><?php echo $value['pro_formulation'];?></td>
      <td class="center"><?php echo $value['pro_unit_price'];?></td>
      <td class="center"><?php echo $value['qty_total'];?></td>
      <td class="center"><?php echo $value['qty_delivery'];?></td>
      <td class="center"><?php echo $value['qty_withdrawn'];?></td>
      <td class="center"><?php echo $value['discount'];?></td>
      <td class="center"><?php echo $value['total'];?></td>
    </tr>
<?php 
$row_ctr++;
}
?>
  <tr>
  <td colspan="5" class="right" style="border-top:1px solid #cacaca;"><b>Status:</b>
  <?php 
  $ordstatus = $orders->get_order_status($id);
  switch($ordstatus){
    case 0: echo "Pending";
    break;
    case 1: echo "Approved";
    break;
    case 2: echo "Incomplete Delivery";
    break;
    case 3: echo "Delivered";
    break;
    case 4: echo "Cancelled";
    break;
    case 5: echo "Declined";
    break;
  }
  ?>
  </td>
  <td colspan="4" class="right" style="border-top:1px solid #cacaca;"><b>Total Amount:</b> <?php echo "P".$orders->get_orditems_sum($id);?></td>
  </tr>
  </table>
  <?php
}

if(isset($_POST['get_approved_orditems'])){
  $id = $_POST['row_id'];
  $result = $orders->get_orditems($id);
  ?>
  <table class="popup_table form-row" border="1" bordercolor="#cacaca" >
  <th>#</th>
  <th><h6>Products</h6></th>
  <th><h6>Formulation</h6></th>
  <th class="center"><h6>Unit Price</h6></th>
  <th class="center"><h6>Ordered</h6></th>
  <th class="center"><h6>Delivered</h6></th>
  <th class="center"><h6>Discount</h6></th>
  <th class="center"><h6>Total</h6></th>
  <?php
  $row_ctr=1;
  foreach($result as $value){
    ?>
    <tr>
      <td class="table-pad-5"><?php echo $row_ctr;?></td>
      <td class="table-pad-5"><?php echo $value['pro_brand']."-".$value['pro_generic'];?></td>
      <td class="table-pad-5"><?php echo $value['pro_formulation'];?></td>
      <td class="center table-pad-5"><?php echo $value['pro_unit_price'];?></td>
      <td class="center table-pad-5"><?php echo $value['sumtotal'];?></td>
      <td class="center table-pad-5"><?php echo $value['sumdelivery'];?></td>
      <td class="center table-pad-5"><?php echo $value['sumdiscount'];?></td>
      <td class="center table-pad-5"><?php echo $value['total'];?></td>
    </tr>
<?php 
$row_ctr++;
}
?>
  <tr>
  <td colspan="5" class="right" style="border-top:1px solid #cacaca;"><b>Status:</b>
  <?php 
  $ordstatus = $orders->get_order_status($id);
  switch($ordstatus){
    case 0: echo "Pending";
    break;
    case 1: echo "Approved";
    break;
    case 2: echo "Incomplete Delivery";
    break;
    case 3: echo "Delivered";
    break;
    case 4: echo "Cancelled";
    break;
    case 5: echo "Declined";
    break;
  }
  ?>
  </td>
  <td colspan="4" class="right" style="border-top:1px solid #cacaca;"><b>Total Amount:</b> <?php echo "P".$orders->get_orditems_sum($id);?></td>
  </tr>
  </table>

<div class="material-button-wrapper ta-center">
  <button type="button" class="material-button-main" onclick="edit_popup_hide_two_approved()" style="float: right;">Close</button>
  <button type="button" id="btn_set_delivery" class="material-button-main" style="float: right; background:
#4c4cff;margin-right: 5px;">Set Delivery</button>
</div>

  <?php
}


if(isset($_POST['add_order'])){
  $client = $_POST['client'];
  $type = $_POST['type'];
  $terms = $_POST['terms'];
  $products_added = $_POST['product'];
  $quantity_added = $_POST['quantity'];
  $ctr = count($quantity_added);
  $ctr_input = count(array_filter($quantity_added));

  if($ctr==$ctr_input){ //MEANING MAY UNOD TANAN NA QUANTITY
    $ord_id = $orders->new_order($client, $type, $terms); 
    for($x=0; $x<$ctr; $x++){
      $orders->add_orditem($ord_id, $products_added[$x], $quantity_added[$x]);
    }
    $orders->update_total($ord_id);
    //CREATE INVOICE FOR "CONSIDERED SOLD" ORDERS
    //$pay_total = $orders->get_order_total($ord_id);
    //$payments->new_invoice($pay_total, $terms);
    echo json_encode($ord_id);
  }else{
    echo json_encode("incomplete");
  }
}

if(isset($_POST['display_select_product'])){
  $array_selected=$_POST['selected'];
  $i = $_POST['i'];
  ?>
  <td class="max-80">
  <?php 
    $prod_list = $products->get_products_select($array_selected);
    if($prod_list){
      ?>
      <select class="prod-select rows-text max-80" placeholder="Select Product" name="product[]" id=<?php echo "select".$i;?> style="width: 100%; left: -29;">
      <?php 
        foreach($prod_list AS $prod_row){
          ?>
          <option value=<?php echo $prod_row['pro_id'];?>><?php echo $prod_row['pro_brand']." (".$prod_row['pro_generic'].") - ".$prod_row['pro_packaging'];?></option>
          <?php 
          }?>
          </select>
            </td>
            <td width="22%"><input type="number" min="1"  id=<?php echo "quantity".$i;?> name="quantity[]" placeholder="Qty" class="rows-qty input_val_qty" /></td><td><button type="button" name="remove" id=<?php echo $i;?> class="btn btn-danger btn_remove">X</button></td>

            <script>
              
              $('.input_val_qty').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });
            </script>
  <?php
    }
}

if(isset($_POST['display_orders_table'])){
  $list = $orders->get_pending_orders();
        
        ?>
            <div class="table-title add-marg">Pending Orders</div>
                <table class="table table-responsive table-striped table-hover" id="orderlist">
                  <thead>
                  <tr>
                    <th class="column_one">Order #</th>
                    <th class="column_two">Client</th>
                    <th class="column_three">Type</th>
                    <th class="column_four">Payment Term</th>
                    <th class="column_five">Date Ordered</th>
                    <th class="column_six">Total</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      ?>
                      <tr onclick="edit_popup_show()" id=<?php echo $values['order_id'];?> class="pending_selected">
                        <td class="column_one"><?php echo $values['order_id'];?></td>
                        <td class="column_two"><?php echo $values['client_name'];?></td>
                        <td class="column_three"><?php echo $values['ordtype_name'];?></td>
                        <td class="column_four"><?php echo $values['term_name'];?></td>
                        <td class="column_five"><?php echo date('F d, Y', strtotime($values['order_date']));?></td>
                        <td class="column_six"><?php echo $values['total_amount'];?></td>
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
            $('#orderlist').dataTable(
                {language: {searchPlaceholder: "Search Pending Order" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
<?php
}

if(isset($_POST['display_approved_table'])){
  $list = $orders->get_approved_orders();
        
        ?>
            <div class="table-title add-marg">Approved Orders</div>
                <table class="table table-responsive table-striped table-hover" id="orderlist">
                  <thead>
                  <tr>
                    <th class="column_one">Order #</th>
                    <th class="column_two">Client</th>
                    <th class="column_three">Type</th>
                    <th class="column_four">Payment Term</th>
                    <th class="column_five">Date Ordered</th>
                    <th class="column_six">Total</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  if($list){
                    foreach($list as $values){
                      //check if may undelivered pa
                      //if(($orders->check_approved_undelivered($values['order_id']))==true){
                        ?>
                        <tr onclick="edit_popup_show_two_approved()" id=<?php echo $values['order_id'];?> class="approved_selected">
                          <td class="column_one"><?php echo $values['order_id'];?></td>
                          <td class="column_two"><?php echo $values['client_name'];?></td>
                          <td class="column_three"><?php echo $values['ordtype_name'];?></td>
                          <td class="column_four"><?php echo $values['term_name'];?></td>
                          <td class="column_five"><?php echo date('F d, Y', strtotime($values['order_date']));?></td>
                          <td class="column_six"><?php echo $values['total_amount'];?></td>
                        </tr>
                        <?php
                      //}
                    }
                  ?>
                  </tbody>
                </table>


        <?php
        }
        ?>
            <script>
            $('#orderlist').dataTable(
                {language: {searchPlaceholder: "Search Pending Order" }, "bLengthChange" : false, "pageLength":10,"aaSorting": [[3, "desc"]], "bSort": false});
            </script>
<?php
}

function valid_number($arr) {
    return ($arr['discount'] >= 0);
}
?>



