<?php
  switch($sub){
    case 'pending': 
        $p_indicator = "Orders &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Pending Orders";
        break;
    case 'approved': 
      $p_indicator = "Orders &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Approved Orders";
      break;
    case 'history': 
        $p_indicator = "Orders &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Order History";
        break; 
    default: $p_indicator = "Orders";
  }
?>
<div class="exhidden-menu">
   <button class="crossmenu">&#735;</button>
   <a onclick="popup_show()"> <div class="subitem hidden-sub-menu">Create Order</div></a>
  <a href="index.php?mod=orders&sub=pending"> <div class="subitem hidden-sub-menu">Pending Orders</div></a>
  <a href="index.php?mod=orders&sub=approved"> <div class="subitem hidden-sub-menu">Approved Orders</div></a>
   <a href="index.php?mod=orders&sub=history"> <div class="subitem hidden-sub-menu">Order History</div></a>
</div>
<div class="card-wrapper">
  <div class="card-style-subnavi">
    <div class="sub-navi">
       <div class="hidden-menu"><button class="burgermenu">&#9776;</button></div>
    	<div class="subitem menu-subs"><a onclick="popup_show()">Create Order</a></div>
      <div class="subitem menu-subs"><a href="index.php?mod=orders&sub=pending">Pending Orders</a></div>
      <div class="subitem menu-subs"><a href="index.php?mod=orders&sub=approved">Approved Orders</a></div>
        <div class="subitem menu-subs"><a href="index.php?mod=orders&sub=history">Order History</a></div>
    </div>
  </div>
      <div class="indicator"><img src="img/icons/orders-black.png"/><?php echo $p_indicator;?></div>
</div>

<div id="back-black" onclick="popup_hide()"></div>
<div class="pop-container style-4" id="pop-container">
        <div id="popup-form" style="width: 500px; margin-left: -250px;">
        <form id="addorder" method="post" name="addorder">   
        <input type="hidden" name="add_order" value="1">
        <h4 id="close" onclick ="popup_hide()">&times;</h4>
        <h3>New Order</h3>
        <hr>
        <?php
        $c_list = $clients->get_clients_activated();
        if($c_list){
            ?>
            <h6>Client</h6>
            
        <div class="rows-select">
            <select class="client-select" name="client" id="client" style="width: 100%; ">
            <?php
                foreach($c_list AS $c_row){ 
            ?>
            <option value=<?php echo $c_row['client_id'];?>><?php echo $c_row['client_name'];?></option>
            <?php
            }
            ?>
            </select>
            <?php
        }
        ?>
        </div>
         
        <h6>Products</h6>
        <table class="rows-field" id="dynamic_field">  
            <tr>
                <td>  
                <?php
                $prod_list = $products->get_products_active();
                if($prod_list){
                    ?>
                    <select class="prod-select w-100" style="width: 300px;" placeholder="Select Product" name="product[]" id="select0">
                    <?php
                        foreach($prod_list AS $prod_row){
                    ?>
                    <option value=<?php echo $prod_row['pro_id'];?>><?php echo $prod_row['pro_brand']." (".$prod_row['pro_generic'].") - ".$prod_row['pro_formulation']." / ".$prod_row['pro_packaging'];?></option>
                    <?php
                    }
                    ?>
                    </select>
                    <?php
                }
                ?>
                </td>
                <td width="22%"><input type="number" min="1" id="quantity0" name="quantity[]" placeholder="Qty" class="rows-qty input_val_qty" /></td>  
            </tr> 
        </table>  
        <table class="rows-add">
            <tr>
                <td><button type="button" name="add" id="add_new" class="material-button">Add Product</button></td>
            </tr>
        </table>
        <?php
        $type_list = $orders->get_ordtype();
        if($type_list){
            ?>
            <h6>Type of Order</h6>
            <div class="rows-select" style="margin-bottom: 20px;">
            <?php
                $ctr=0;
                foreach($type_list AS $type_row){
                if($ctr==0){
            ?>  
                <input type="radio" id="rdo_sold" name="type" value=<?php echo $type_row['ordtype_id'];?> checked><?php echo $type_row['ordtype_name'];?><br>
            <?php
            }else{
            ?>
                <input type="radio" id="rdo_consignment" name="type" value=<?php echo $type_row['ordtype_id'];?>><?php echo $type_row['ordtype_name'];?><br>
            <?php
            }
            $ctr++;
            
            }
        }
        ?>
        </div>
        <h6 id="payment_terms">Terms of Payment</h6>
        <div class="rows-select1" id="terms_field"><!----AJAX CALL------></div>
        <div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" id="submit_add" value="Save"/>
          <input type="button" class="material-button float-right" id="submit_close" onclick="popup_hide()" value="Close"/>
        </div>
        </form>
        </div>
</div>

<!--------- PARA SA PENDING NGA POPUP-------->

<div class="pop-container style-4" id="edit-pop-container">
      <div id="popup-form" style="width: 1000px; margin-left:-475px;">
      <form id="editform" method="post" name="form">  
      <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>

        <div class="hide-data-class"> 
      <h3 id="ordnum"></h3>
      <input type="hidden" name="pending_ordid_selected" id="pending_ordid_selected" value=""/>
      <h4 id="close" onclick ="edit_popup_hide()">&times;</h4> 
      <input type="hidden" id="edit_id">
      <div id="form-wide">
      <h6>Client</h6><input class="form-row-wide" type="text" id="ordclient" disabled/>
      <h6>Order Type</h6><input class="form-row-wide" type="text" id="ordtype" disabled/>
      <h6>Terms of Payment</h6><input class="form-row-wide" type="text" id="ordterms" disabled/>
      <input type="hidden" id="status_order_btn" value="0">
      <div id="orders_table" class="orders-table"></div>
      <hr>
      <input type="hidden" id="edit_id">
      </div>
      </div>
      </form>
      </div>
</div>

<!--------- PARA SA APPROVED NGA POPUP-------->

<div id="back-black-approved"></div>
<div class="pop-container style-4" id="edit-pop-container-two-approved">
      <div id="popup-form" style="width: 1200px; margin-left:-600px;">
      <form id="editform_delivery" method="post" name="editform_delivery">  
      <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>

        <div class="hide-data-class"> 
      <h3 id="ordnum_approved"></h3>
      <input type="hidden" name="approved_ordid_selected" id="approved_ordid_selected" value=""/>
      <h4 id="close" onclick ="edit_popup_hide_two_approved()">&times;</h4> 
      <div id="form-wide">
      <h6>Client</h6><input class="form-row-wide" type="text" id="ordclient_two" disabled/>
      <h6>Order Type</h6><input class="form-row-wide" type="text" id="ordtype_two" disabled/>
      <h6>Terms of Payment</h6><input class="form-row-wide" type="text" id="ordterms_two" disabled/>
      <input type="hidden" id="status_order_btn_two" value="0">
      <div id="orders_table_two" class="orders-table-two"></div>
      <hr>
      </div>
      </div>

      </form>
      </div>
</div>


<!--------- PARA SA HISTORY NGA POPUP-------->
<div id="back-black" onclick="popup_hide_three()"></div>
<div class="pop-container style-4" id="edit-pop-container-three">
      <div id="popup-form" style="width: 1100px; margin-left:-550px;">
      <form>   
      <h3 id="history_ordnum"></h3>
      <h4 id="close" onclick ="edit_popup_hide_three()">&times;</h4> 
      <input type="hidden" id="history_edit_id">
      <div id="form-wide">
      <h6>Client</h6><input class="form-row" type="text" id="history_ordclient" disabled/>
      <h6>Order Type</h6><input class="form-row" type="text" id="history_ordtype" disabled/>
      <h6>Terms of Payment</h6><input class="form-row" type="text" id="history_ordterms" disabled/>
      <div id="history_orders_table" class="orders-table"></div>
      <hr>
      </div>
      
      <div class="material-button-wrapper">
    <input type="button" value="Close" class="material-button-main float-right" id="close_btn_history" onclick="edit_popup_hide_three()"/>
    </div>
      </form>

      </div>
</div>


<script>
  $(document).ready(function(){
    /*
    $('.edit_order_qty').hide();
    if($('#status_order_btn').val()==0){
      $('.edit_order_btn').show();
      $('.save_order_btn').hide();
    }else{
      $('.edit_order_btn').hide();
      $('.save_order_btn').show();
    }

    $(document).on("click", ".edit_order_btn", function(){
      $('.edit_order_btn').hide();
      $('.save_order_btn').show();
      $('#status_order_btn').val(1);
      alert($('#status_order_btn').val());
      $('.edit_order_qty').show();
    });
    $(document).on("click", ".save_order_btn", function(){
      $('.edit_order_btn').show();
      $('.save_order_btn').hide();
      $('#status_order_btn').val(0);
      alert($('#status_order_btn').val());
      $('.edit_order_qty').hide();
    });*/
  });
</script>

<?php
if(isset($_GET['sub'])){
?>

<!--<div id="loading-screen-2">
  <div class="card-wrapper" style="height: 100%;">
  <div class="card-style" style="height: 100%;">
  <img src="img/loading-icon.gif" alt="Loading" style="width:75px;height:75px;margin-top: 17%;">
  </div>
  </div>
  </div>-->

  <div id="loading-screen-2">
  <div class="card-wrapper" style="">
  <div class="card-style" style="height: 30vh; padding-top: 10%;">
  <svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
  </div>
  </div>
  </div>

<div class="card-wrapper-notop" id="hide-card-wrapper">
    <div class="card-style">
      <div id="sub-container"></div>
    </div>
</div>
<?php
}
?>
<script type="text/javascript">
$(document).ready(function(){

//SET DELIVERY PROCESS  
$(document).on("click", "#btn_set_delivery", function(){
    var approved_order_id = $('#approved_ordid_selected').val();
    $.ajax({
      url: "orders/process.php",
      method: "POST",
      data:{
        "set_delivery_action": 1,
        "approved_order_id": approved_order_id
      },
      success: function(data){
        $("#orders_table_two").html(data);
      }
      });
});


//BASE THIS IN DELIVERY AJAX --> COPIED
$('body').on('click', '.btn_add_lot', function (){
        var row_id = $(this).closest('tr').prop('id');
        var array_selected = "";
        $('.lot'+row_id).each(function() {
            array_selected = array_selected + ($(this).val()) + ",";
            $(this).attr("disabled", true);
        });
        $.ajax({
            url: "delivery/process.php",
            type: "POST",
            async: false,
            data:{
                "add_lot_row": 1,
                "pro_id": row_id,
                "selected": array_selected
            }, 
            success: function(data){
                if(data==""){
                    alert("No additional lot number available for this product!");
                    $('.lot'+row_id).last().attr("disabled", false);
                }else{
                    $("."+row_id).last().after(data);
                }
            }
        });
    });

//REMOVE LOT ADDED
    $('body').on('click', '.lot_remove_btn', function(e){
       $(this).closest('tr').prev().find('select').attr("disabled", false);
       $(this).closest('tr').remove();  
    })

//Para mag save
    $(document).on('click', '#btn_save_delivery', function (){
          var prod_arr = [];
          var qty_arr = [];
          var prod_total_qty =[];
          var row_prod = [];
          var row_lot = [];
          var row_lot_qty = [];
          var sum=0;
          //IYA MAIN CONTAINER
            $("#pendingtable tr").each(function() { 
                if(this.id!=""){
                    row_prod.push(this.id);
                }
              if(prod_arr.includes(this.id)==false){
                if(this.id!=""){
                prod_arr.push(this.id);
                 $(".qty"+this.id).each(function(){
                sum += parseInt($(this).val());
                if(parseInt($(this).val())>=0){
                  row_lot_qty.push(parseInt($(this).val()));
                }else{
                  row_lot_qty.push("null");
                }
                
              });
                 qty_arr.push(sum);
                 sum=0;
                 
              }
             }
             if($(this).find('.row_lot_line').find('select').val()!=""){
                row_lot.push($(this).find('.row_lot_line').find('select').val());
              }else{
                row_lot.push(0);
              }
            });
            $('.pro_total_row').each(function() {
                prod_total_qty.push($(this).val());
             });

            /*var y=0;
            var txt="";
            $("#pendingtable tr").each(function() {
             txt+=row_prod[y]+"=>"+row_lot[y+1]+"=>"+row_lot_qty[y]+"\n";
              y++;
            });
            alert(txt);*/
            if(confirm("Proceed creating delivery?")){
            $.ajax({
            url: "delivery/process.php",
            type: "POST",
            async: false,
            data:{
                "validate_inputs": 1,
                "prod_arr": prod_arr,
                "qty_arr": qty_arr,
                "prod_total_qty": prod_total_qty,
                "row_lot": row_lot,
                "row_prod": row_prod,
                "row_lot_qty": row_lot_qty
            }, //May hidden na add-Order sa form
            success: function(data){
                if(data=="validated"){
                $('select').attr("disabled", false);
                $.ajax({
                    url: "orders/process.php",
                    type: "POST",
                    async: false,
                    data:$("#editform_delivery").serialize(),
                    success: function(data){
                        if(data=="empty"){
                          alert("No delivery record was made. 0 quantity was entered on all items.");
                        }else if(data=="unable"){
                          alert("There was a problem making the delivery record. Please try again");
                        }else{
                          alert("Delivery No."+data+" has been created");
                          displayCurrentPage();
                          window.location.reload();
                        }

                    }
                  });
                }else{
                   alert(data);
                }
            }
          });
          }
    });

$(".exhidden-menu").hide();
$(".burgermenu").click(function(){
  $(".exhidden-menu").slideToggle("slow", function(){
  
  });
});

$(".crossmenu").click(function(){
  $(".exhidden-menu").slideToggle("slow", function(){
   
  });
});

  
   $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "payterm_radio": 1
            },
            success: function(data){
               $("#terms_field").html(data);
            }
          });
  displayCurrentPage();
    
    //HISTORY BUTTON LOAD
    
    $('#search_history_btn').click(function (event) {
      var client_id = $("#order-client-select").val();
       document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("subsub-container").style.display = "none";
      $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "load_order_search": 1,
              "client_id": client_id
            },
            success: function(data){
              setTimeout(function(){
                 $("#subsub-container").html(data);
                  document.getElementById("loading-screen-custom").style.display = "none";
                   document.getElementById("subsub-container").style.display = "block";
              }, 1000);
              
            }
          });
    });
  /*
    $('#history_btn').click(function (event) {

      if($("#date_start").val()=="" || $("#date_end").val()==""){
        $("#subsub-container").html("<br/><b class='red-text'>* Please enter valid dates</b>");
      }
      else{
      if($("#date_start").val()<$("#date_end").val()){
        document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("subsub-container").style.display = "none";
        $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:$("#history_form").serialize(),
            success: function(data){
              setTimeout(function(){
                 $("#subsub-container").html(data);
                 document.getElementById("loading-screen-custom").style.display = "none";
        document.getElementById("subsub-container").style.display = "block";
              }, 0);
              
            }
          });
    }else{
      $("#subsub-container").html("<br/><b class='red-text'>* [End Date] should be later than [Start Date]</b>");
    }
    }
    });
  */
    //RADIO BUTTON CLICKED EVENT (CONSIDERED SOLD)
     $('#rdo_sold').click(function (event) {
      $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "payterm_radio": 1
            },
            success: function(data){
               $("#terms_field").html(data);
            }
          });
     });

     //RADIO BUTTON CLICKED EVENT (CONSIGNMENT==MONTHLY)
     $('#rdo_consignment').click(function (event) {
      $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "payterm_radio_consignment": 1
            },
            success: function(data){
               $("#terms_field").html(data);
            }
          });
     });

    //REMOVE IS CLICKED
    $(document).on("click", "#remove_row", function(){
      var row_id = $("#edit_id").val();
      if (confirm('Are you sure you want to decline Order #'+row_id+"?")) {
            $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "remove_order": 1,
              "row_id": row_id
            },
            success: function(data){
              alert("Order #"+data+" declined successfully");
              displayCurrentPage();
              edit_popup_hide();
            }
          });
        }
    });

    //REMOVE IS CLICKED
    $(document).on("click", "#approve_order_btn", function(){
      var row_id = $("#edit_id").val();
      if (confirm('Are you sure you want to approve Order #'+row_id+"?")) {
            $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "approve_order_action": 1,
              "row_id": row_id
            },
            success: function(data){
              alert("Order #"+data+" approved successfully");
              displayCurrentPage();
              edit_popup_hide();
            }
          });
        }
    });

    //EDIT FUNCTION BUTANG RECORDS SA POPUP SA PENDING
    $(document).on("click", ".pending_selected", function(){
      var row_id = $(this).attr("id");
       $(".loading-screen-popup-2-class").show();
      $(".hide-data-class").hide();
      $('#status_order_btn').val(0);
      $("#edit_id").val(row_id);
            $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "get_specific_order": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              $('#edit_id').val(data.ordid);
              $('#ordnum').html("Order #"+data.ordid);
              $('#pending_ordid_selected').val(data.ordid);
              $('#ordclient').val(data.client_name);
              $('#ordterms').val(data.term_name);
              $('#ordtype').val(data.ordtype_name);
              
            }
          });

            $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "get_order_orditems": 1,
              "row_id": row_id
            },
            success: function(data){
              $('#orders_table').html(data);
              setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
            }
          });
    });

    //APPROVED ORDERS LIST
    //EDIT FUNCTION BUTANG RECORDS SA POPUP SA APPROVED
    $(document).on("click", ".approved_selected", function(){
      var row_id = $(this).attr("id");
       $(".loading-screen-popup-2-class").show();
      $(".hide-data-class").hide();
      $('#status_order_btn').val(0);
      $("#edit_id").val(row_id);
            $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "get_specific_order": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              $('#edit_id').val(data.ordid);
              $('#ordnum_approved').html("Order #"+data.ordid);
              $('#approved_ordid_selected').val(data.ordid);
              $('#ordclient_two').val(data.client_name);
              $('#ordterms_two').val(data.term_name);
              $('#ordtype_two').val(data.ordtype_name);
              
            }
          });

            $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "get_approved_orditems": 1,
              "row_id": row_id
            },
            success: function(data){
              $('#orders_table_two').html(data);
              setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
            }
          });
    });

    //EDIT FUNCTION BUTANG RECORDS SA POPUP SA HISTORY
    $(document).on("click", ".history_selected", function(){
      var row_id = $(this).attr("id");
           $(".loading-screen-popup-2-class").show();
      $(".hide-data-class").hide();
            $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "get_specific_order": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              $('#history_edit_id').val(data.ordid);
              $('#history_ordnum').html("Order #"+data.ordid);
              $('#history_ordclient').val(data.client_name);
              $('#history_ordterms').val(data.term_name);
              $('#history_ordtype').val(data.ordtype_name);
              setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
            }
          });

            $.ajax({
            url: "orders/process.php",
            method: "POST",
            data:{
              "get_history_order_orditems": 1,
              "row_id": row_id
            },
            success: function(data){
              $('#history_orders_table').html(data);
              setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
            }
          });
    });

    //ADD FUNCTION
    $("#submit_add").click(function(){
      if (confirm("Proceed creating order?")) {
        $('select').removeAttr('disabled');
          $.ajax({
            url: "orders/process.php",
            type: "POST",
            dataType: "json",
            async: false,
            data:$('#addorder').serialize(), //May hidden na add-Order sa form
            success: function(data){
              if(data=="incomplete"){
                alert("Please fill up all information");
                for(var y=0; y<i; y++){
                if($("#select"+y).val()!=null){
                    document.getElementById("select"+y).disabled=true;
                } 
              }
              }else{
                alert("Order #"+data+" successfully created");
                displayCurrentPage();
                popup_hide();
                document.getElementById("addorder").reset();
              }
            }
          });
        }
    });

    var i=0;
    var ctr=0;
      $('#add_new').click(function(){
            i++;
            ctr++;
            var x=0;
            var array_selected = "";
            if(ctr>0){
            for(x; x<=i; x++){
                if($("#select"+x).val()!=null){
                    document.getElementById("select"+x).disabled=true;
                    if(x<i-1){
                        array_selected+=$("#select"+x).val()+",";
                    }else{
                        array_selected+=$("#select"+x).val();
                    }
                } 
            }
            }else{
                document.getElementById("select0").disabled=false;
            }
           $('#dynamic_field').append('<tr id="row'+i+'"></tr>');  
           $.ajax({
            url: "orders/process.php",
            type: "POST",
            async: false,
            data: {
              "display_select_product": 1,
              "i": i,
              "selected": array_selected
            },
            success: function(data){
                $("#row"+i).html(data);
                $(".prod-select").select2();
            }
          });  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
           ctr--;
            var x=0;

            if(ctr>0){
            for(x; x<i; x++){
                if($("#select"+x).val()!=null){
                    document.getElementById("select"+x).disabled=true; 
                } 
            }
            }else{
                document.getElementById("select0").disabled=false;
            }
      });


    $(".client-select").select2();
    $(".terms-select").select2({
        minimumResultsForSearch: -1
    });
    $(".prod-select").select2();
    $(".type-select").select2({
        minimumResultsForSearch: -1
    });

    /*where you edit*/

    
    setTimeout(function(){
      document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
    }, 1000);

});

function displayOrdersTable(){          
          $.ajax({
            url: "orders/process.php",
            type: "POST",
            async: false,
            data: {
              "display_orders_table": 1
            },
            success: function(data){
                $("#sub-container").html(data);
            }
          });
}
function displayApprovedTable(){          
          $.ajax({
            url: "orders/process.php",
            type: "POST",
            async: false,
            data: {
              "display_approved_table": 1
            },
            success: function(data){
                $("#sub-container").html(data);
            }
          });
}
/*
function displayHistoryTable(){          
          $.ajax({
            url: "orders/process.php",
            type: "POST",
            async: false,
            data: {
              "display_history_table": 1
            },
            success: function(data){
                $("#sub-container").html(data);
            }
          });
}
*/
function displayHistoryDate(){          
          $.ajax({
            url: "orders/process.php",
            type: "POST",
            async: false,
            data: {
              "display_date_history": 1
            },
            success: function(data){
                $("#sub-container").html(data);
                $("#order-client-select").select2();
            }
          });
}

function displayCurrentPage(){
  var page = <?php echo (json_encode($_GET['sub']));?>;
    switch(page){
      case null:
      case 'pending': displayOrdersTable();
      break;
      case 'approved': displayApprovedTable();
      break;
      case 'history': displayHistoryDate();
      break;
    }
}

$('.input_val_qty').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });

</script>
<script>
  $(document).ready(function(){
    
  });
</script>