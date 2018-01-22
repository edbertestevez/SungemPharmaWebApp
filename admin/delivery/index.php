<?php
  switch($sub){
    case 'pending': 
        $p_indicator = "Delivery &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Pending Delivery";
        break;
    case 'history': 
        $p_indicator = "Delivery &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Delivery History";
        break; 
    default: $p_indicator = "Delivery";
  }
?>
<div class="exhidden-menu">
   <button class="crossmenu">&#735;</button>
  <a href="index.php?mod=delivery&sub=pending"> <div class="subitem hidden-sub-menu">Pending Delivery</div></a>
   <a href="index.php?mod=delivery&sub=history"> <div class="subitem hidden-sub-menu">Delivery history</div></a>
 </div>
<div class="card-wrapper">
  <div class="card-style-subnavi">
    <div class="sub-navi">
     <div class="hidden-menu"><button class="burgermenu">&#9776;</button></div>
    	<div class="subitem menu-subs"><a href="index.php?mod=delivery&sub=pending">Pending Delivery</a></div>
        <div class="subitem menu-subs"><a href="index.php?mod=delivery&sub=history">Delivery History</a></div>
    </div>
  </div>
  <div class="indicator"><img src="img/icons/delivery-black.png"/><?php echo $p_indicator;?></div>
</div>

<!--POPUPS-->
<div id="back-black" onclick="popup_hide()"></div>
<div class="pop-container style-4" id="pop-container">
<div id="popup-form" style="width: 750px;margin-left:-400px">
  <form method="post" id="form_del">
  <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>

  <div class="hide-data-class">
  <h4 id="close" onclick ="popup_hide()">&times;</h4>
  <h3 id="selected_delivery"></h3>
  <input type="hidden" id="del_id">
  <div id="invoice_selected_info">

    <h5 id="delivery_info"></h5>
    <div id="delivery_record">
      <!----CALL AJAX FOR CONTENT-->
    </div>
     <!---PRINT BUTTON---->
  <input type="button" id="print_delivery_form" class="print_btn_pop" value="PRINT DELIVERY RECEIPT">
  <!---PRINT BUTTON---->
  <input type="button" id="print_consignment_form" class="print_btn_pop" value="PRINT CONSIGNMENT" style="display:none;">
    <br/><br/>

    <h5 style="margin-bottom: 8px;">UPDATE DELIVERY STATUS </h5>
    
    <h6 class="fc-green">Enter the name of the receiver  *</h6>
    <input type="text" name="or_num" id="or_num" placeholder="Name of Receiver">
    <br/><br/>
    <div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" id="submit_delivered" value="Delivered"/>
          <input type="button" class="material-button float-right" id="submit_close" onclick="popup_hide()" value="Close"/>
    </div>
  </div>
  </div>
  </form>
</div>
</div>

<!--POPUPS HISTORY-->
<div id="back-black" onclick="popup_hide_two()"></div>
<div class="pop-container style-4" id="pop-container-two">
<div id="popup-form" style="width: 750px;margin-left:-400px">
  <form method="post" id="form_del_two">
    <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div class="hide-data-class">
  <h4 id="close" onclick ="popup_hide_two()">&times;</h4>
  <h3 id="selected_delivery_two"></h3>
  <div id="invoice_selected_info">
    <h5 id="delivery_info_two"></h5>
    <div id="delivery_record_two">
      <!----CALL AJAX FOR CONTENT-->
    </div>
    <br/>
    <div class="form-buttons-area">
    </div>
  </div>
  </div>
  </form>
</div>
</div>
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

//CANCEL NI
/*
$('#or_num').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });

*/


$(".exhidden-menu").hide();
$(".burgermenu").click(function(){
  $(".exhidden-menu").slideToggle("slow", function(){
  
  });
});

$(".crossmenu").click(function(){
  $(".exhidden-menu").slideToggle("slow", function(){
   
  });
});


displayCurrentPage();
$(".lot-select").select2();
  
   //PRINT SALES REPORT
    $(document).on("click", "#print_consignment_form", function(){
      var del_id = $("#del_id").val();
      window.open ("delivery/print_consignment.php?delivery_id="+del_id);
    });
    //PRINT SALES REPORT
    $(document).on("click", "#print_delivery_form", function(){
      var del_id = $("#del_id").val();
      window.open ("delivery/print_delivery.php?delivery_id="+del_id);
    });

    $('#history_btn').click(function (event) {
      if($("#date_start").val()=="" || $("#date_end").val()==""){
        $("#subsub-container").html("<br/><b class='red-text'>* Please enter valid dates</b>");
      }
      else{
      if($("#date_start").val()<$("#date_end").val()){
        $.ajax({
            url: "delivery/process.php",
            method: "POST",
            data:$("#history_form").serialize(),
            success: function(data){
               $("#subsub-container").html(data);
            }
          });
    }else{
      $("#subsub-container").html("<br/><b class='red-text'>* [End Date] should be later than [Start Date]</b>");
    }
    }
    });

    $('#search_history_btn').click(function (event) {
      var client_id = $("#delivery-client-select").val();
       document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("subsub-container").style.display = "none";
      $.ajax({
            url: "delivery/process.php",
            method: "POST",
            data:{
              "load_delivery_search": 1,
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

    $(document).on("click", "#submit_delivered", function(){
        if($("#or_num").val()!=""){
            var or_num = $("#or_num").val();
        if(confirm("Update delivery status of this record?")){
            var delivery_id = $("#del_id").val();
            $.ajax({
            url: "delivery/process.php",
            method: "POST",
            data:{
              "update_delivery_status": 1,
              "delivery_id": delivery_id,
              "or_num": or_num
            },
            success: function(data){
                alert("Delivery #"+delivery_id+" has been successfully delivered");
                alert(data);
                popup_hide();
                displayCurrentPage();
                document.getElementById("form_del").reset();
            }
        });
        }
    }else{
        alert("Enter the name of the receiver for the delivery");
    }
    });

$(document).on("click", ".selected_history", function(){
      var row_id = $(this).attr("id");
       $(".loading-screen-popup-2-class").show();
                  $(".hide-data-class").hide();
      $("#selected_delivery_two").html("Delivery #"+row_id);
            //LOAD PRODUCT INFO
            $.ajax({
            url: "delivery/process.php",
            method: "POST",
            data:{
              "get_delivery_details": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
                var ord_id = data.order_id;
                var d_delivered="";
                var t_delivered="";
                var or_number="";
                if(data.date_delivered=='0000-00-00'){
                    d_delivered="Pending";
                }else{
                    d_delivered=data.date_delivered;
                }
                if(data.time_delivered=='00:00:00'){
                    t_delivered="Pending";
                }else{
                    t_delivered=data.time_delivered;
                }
                if(data.or_number==""){
                  or_number = "Pending";
                }else{
                  or_number = data.or_number;
                }
                $("#delivery_info_two").html("Client: "+data.client_name+"<br/> Order No. "+data.order_id+"<br/> Type: "+data.ordtype_name+"<br/><br/> Date Delivered: "+d_delivered+"<br/> Time Delivered: "+t_delivered+"<br/> Received by: "+or_number);

                //LOAD ORDER ITEMS
                $.ajax({
                url: "delivery/process.php",
                method: "POST",
                data:{
                  "show_details_table": 1,
                  "delivery_id": row_id
                },
                success: function(data){
                  $("#delivery_record_two").html(data);
                   setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
                }
              });
            }
          });

    });

    $(document).on("click", ".selected_pending", function(){
      var row_id = $(this).attr("id");
      $(".loading-screen-popup-2-class").show();
                  $(".hide-data-class").hide();

      $("#del_id").val(row_id);
      $("#selected_delivery").html("Delivery #"+row_id);
            //LOAD PRODUCT INFO
            $.ajax({
            url: "delivery/process.php",
            method: "POST",
            data:{
              "get_delivery_details": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              if(data.ordtype_id=="11"){
                document.getElementById("print_consignment_form").style.display="block";
              }else{
                document.getElementById("print_consignment_form").style.display="none";
              }
                $("#delivery_info").html("Client: "+data.client_name+"<br/> Order No. "+data.order_id+"<br/> Order Type: "+data.ordtype_name);

                //LOAD ORDER ITEMS
                $.ajax({
                url: "delivery/process.php",
                method: "POST",
                data:{
                  "show_details_table_pending": 1,
                  "delivery_id": row_id
                },
                success: function(data){
                  $("#delivery_record").html(data);
                   setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
                }
              });
            }
          });

    });

    //REMOVE LOT ADDED
    $('body').on('click', '.lot_remove_btn', function(e){
       $(this).closest('tr').prev().find('select').attr("disabled", false);
       $(this).closest('tr').remove();  
    })

    //Para mag save
    $('body').on('click', '#save_delivery', function (){
          var prod_arr = [];
          var qty_arr = [];
          var prod_total_qty =[];
          var row_prod = [];
          var row_lot = [];
          var row_lot_qty = [];
          var sum=0;
            $("#pendingtable tr").each(function() { 
                if(this.id!=""){
                    row_prod.push(this.id);
                }
              if(prod_arr.includes(this.id)==false){
                if(this.id!=""){
                prod_arr.push(this.id);
                 $(".qty"+this.id).each(function(){
                sum += parseInt($(this).val());
                row_lot_qty.push(parseInt($(this).val()));
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
                    url: "delivery/process.php",
                    type: "POST",
                    async: false,
                    data:$('#form_delivery').serialize(), //May hidden na delivery_create sa form
                    success: function(data){
                        alert("Delivery information successfully added");
                        displayCurrentPage();
                        window.location.reload();
                    }
                  });
                }else{
                   alert(data);
                }
            }
          });
          }
    });

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

    $("#load-order-btn").click(function(){
        var order_id = $("#select-pending-order").val();
        $.ajax({
            url: "delivery/process.php",
            type: "POST",
            async: false,
            data:{
                "load_pending": 1,
                "order_id": order_id
            }, 
            success: function(data){
                $("#pending_table").html(data);
            }
        });
    });

    $(".btn_delivered").click(function(){
        var delivery_id = $(this).prop('id');
        $.ajax({
            url: "delivery/process.php",
            type: "POST",
            async: false,
            data:{
                "update_pending": 1,
                "delivery_id": delivery_id
            }, 
            success: function(data){

            }
        });
    });

    /*where you edit*/

   setTimeout(function(){
      document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
    },1000);
});


function displayPendingDelivery(){          
    $.ajax({
    url: "delivery/process.php",
    type: "POST",
    async: false,
    data: {
        "display_pending_delivery": 1
    },
    success: function(data){
        $("#sub-container").html(data);
    }
    });                      
}          
/*
function displayHistoryDelivery(){          
    $.ajax({
    url: "delivery/process.php",
    type: "POST",
    async: false,
    data: {
        "display_history_delivery": 1
    },
    success: function(data){
        $("#sub-container").html(data);
    }
    });                      
}            
*/
function displayHistoryDate(){          
          $.ajax({
            url: "delivery/process.php",
            type: "POST",
            async: false,
            data: {
              "display_date_history": 1
            },
            success: function(data){
                $("#sub-container").html(data);
                $("#delivery-client-select").select2();
            }
          });
}
function displayCurrentPage(){
    var page = <?php echo (json_encode($_GET['sub']));?>;
    switch(page){
      case null:
      case 'pending': displayPendingDelivery();
      break;
      case 'history': displayHistoryDate();
      break;
    }
}
</script>