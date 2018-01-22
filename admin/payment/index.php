<?php
  switch($sub){
    case 'receive': 
    	$p_indicator = "Payment &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Receive Payment";
    	break;
    case 'unpaid':
      $p_indicator = "Payment &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Unpaid Invoice";
      break;
    case 'pending': 
        $p_indicator = "Payment &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Pending PDC Payment";
        break;
    case 'records': 
        $p_indicator = "Payment &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Payment Records";
        break; 
    case 'invoice': 
        $p_indicator = "Payment &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Invoice Records";
        break;
    default: $p_indicator = "Payment";
  }

?>

<div class="exhidden-menu">
   <button class="crossmenu">&#735;</button>
   <a href="index.php?mod=payment&sub=receive"><div class="subitem hidden-sub-menu">Receive Payment</div></a>
  <a href="index.php?mod=payment&sub=unpaid"> <div class="subitem hidden-sub-menu">Unpaid Invoice</div></a>
   <a href="index.php?mod=payment&sub=pending"><div class="subitem hidden-sub-menu">Pending PDC Payments</div></a>
  <a href="index.php?mod=payment&sub=records"> <div class="subitem hidden-sub-menu">Payment Records</div></a>
   <a href="index.php?mod=payment&sub=invoice"><div class="subitem hidden-sub-menu">Invoice Records</div></a>

 </div>

<div class="card-wrapper">
  <div class="card-style-subnavi">
    <div class="sub-navi">
     <div class="hidden-menu"><button class="burgermenu">&#9776;</button></div>
    	<div class="subitem menu-subs"><a href="index.php?mod=payment&sub=receive">Receive Payment</a></div>
        <div class="subitem menu-subs"><a href="index.php?mod=payment&sub=unpaid">Unpaid Invoice</a></div>
    	<div class="subitem menu-subs"><a href="index.php?mod=payment&sub=pending">Pending PDC Payment</a></div>
        <div class="subitem menu-subs"><a href="index.php?mod=payment&sub=records">Payment Records</a></div>
            <div class="subitem menu-subs"><a href="index.php?mod=payment&sub=invoice">Invoice Records</a></div>
    </div>
  </div>
  <div class="indicator"><img src="img/icons/payment-black.png"/><?php echo $p_indicator;?></div>
</div>

<!--POPUPS-->
<div id="back-black" onclick="popup_hide()"></div>
<div class="pop-container" id="pop-container">
<div id="popup-form" style="width: 450px;">
  <div class="form_area">
  <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>

  <div class="hide-data-class">
  <h4 id="close" onclick ="popup_hide()">&times;</h4>
  <h3 id="selected_invoice"></h3>
  <input type="hidden" id="inv_id">
  <!---PRINT BUTTON---->
  <input type="button" id="print_invoice" class="print_btn_pop" value="PRINT INVOICE">
  <br/><br/>

  <div id="invoice_selected_info">
    <h5 id="invoice_info" class="info"></h5>
    <h5>PAYMENT RECORD:</h5>
    <div id="invoice_payment_history">
      <!----CALL AJAX FOR CONTENT-->
    </div>
    <div class="material-button-wrapper">
    <button class="material-button-main float-right" onclick="popup_hide()">Close</button>
    </div>
    </div>
  </div>
  </div>
</div>
</div>


<!--POPUP SANG PAYMENT-->
<div id="back-black" onclick="popup_hide_two()"></div>
<div class="pop-container" id="pop-container-two">
<div id="popup-form" style="width: 450px;">
  <div class="form_area">
  <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>

  <div class="hide-data-class">
  <h4 id="close" onclick ="popup_hide_two()">&times;</h4>
  <h3 id="selected_payment"></h3>
  <input type="hidden" id="pay_id">
  <h6 id="pay_client"></h6>
  <h6 id="pay_invoice"></h6>
  <h6 id="pay_mode"></h6>
  <h6 id="pay_pdc_number"></h6>
  <h6 id="pay_pdc_date"></h6>
  <h6 id="pay_pdc_bank"></h6>
  <h6 id="pay_amount"></h6>
  <h6 id="pay_date"></h6>
  <h6 id="pay_time"></h6>
    <div class="material-button-wrapper">
      <button class="material-button-main float-right" onclick="popup_hide_two()">Close</button>
      <input type="button" class="material-button float-right" id="remove_payment" value="Delete"/>
    </div>
    </div>
  </div>
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

    //REMOVE PAYMENT
     $(document).on("click", "#remove_payment", function(){
      var pay_id = $("#pay_id").val();

      if(confirm("Are you sure you want to delete this record?")){
      $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:{
              "remove_payment": 1,
              "row_id": pay_id
            },
            success: function(data){
              if(data){
                alert("Payment Record successfully deleted.");
                window.location.reload();
              }else{
                alert("An error occured. Cannot delete record");
              }
            }
          });
    }
    });
    //PAYMENT
    $(document).on("click", ".payment_row", function(){
      var row_id = $(this).attr("id");
       $(".loading-screen-popup-2-class").show();
            $(".hide-data-class").hide();
      $("#pay_id").val(row_id) ;
      $("#selected_payment").html("Payment #"+row_id);
            //LOAD PRODUCT INFO
            $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:{
              "get_payment_details": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              if(!data){
                $("#pay_client").html("KAYUHON PA NI SA ACTUAL DATA NA KAY GN CHANGE KO. GIN CONNECT KO ANG PAYMENT INTO CLIENT SA 'ERD' THAT'S WHY KNG WALA CLIENT ID NA GA GWA WALA MAY GA GWA SA RECORD :D <br/><br/>-EDBERT<br/><br/>SEPTEMBER 8, 2017");    
              }else{
                $("#pay_client").html("CLIENT: "+data.client_name);
                $("#pay_invoice").html("INVOICE NO. "+data.invoice_id);
                $("#pay_mode").html("PAYMENT MODE: "+data.paymode_name);
                if(data.pdc_no==""){
                  $("#pay_pdc_number").html("PDC #: N/A");  
                  $("#pay_pdc_date").html("PDC DATE: N/A");
                $("#pay_pdc_bank").html("BANK: N/A");
                }else{
                  $("#pay_pdc_number").html("PDC # :"+data.pdc_no);
                  $("#pay_pdc_date").html("PDC DATE: "+ data.pdc_date);
                $("#pay_pdc_bank").html("BANK: "+data.pdc_bank);
                }
                
                $("#pay_amount").html("AMOUNT: "+data.payment_amount);
                $("#pay_date").html("DATE RECEIVED: "+data.payment_date);
                $("#pay_time").html("TIME RECEIVED: "+data.payment_time);
            }
            setTimeout(function(){
            $(".loading-screen-popup-2-class").hide();
            $(".hide-data-class").show();
            }, 1000);
            }
          });
    });

    //PRINT SALES REPORT
    $(document).on("click", "#print_invoice", function(){
      var inv_id = $("#inv_id").val();
      window.open ("payment/print_invoice.php?invoice_id="+inv_id);
    });

    $('#load_history_payment_btn').click(function (event) {
      if($("#date_start").val()=="" || $("#date_end").val()==""){
        $("#subsub-container").html("<br/><b class='red-text'>* Please enter valid dates</b>");
      }
      else{
      if($("#date_start").val()<$("#date_end").val()){
        $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:$("#history_payment_form").serialize(),
            success: function(data){
               $("#subsub-container").html(data);
            }
          });
    }else{
      $("#subsub-container").html("<br/><b class='red-text'>* [End Date] should be later than [Start Date]</b>");
    }
    }
    });

    $('#load_history_invoice_btn').click(function (event) {
      if($("#date_start").val()=="" || $("#date_end").val()==""){
        $("#subsub-container").html("<br/><b class='red-text'>* Please enter valid dates</b>");
      }
      else{
      if($("#date_start").val()<$("#date_end").val()){
        $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:$("#history_invoice_form").serialize(),
            success: function(data){
               $("#subsub-container").html(data);
            }
          });
    }else{
      $("#subsub-container").html("<br/><b class='red-text'>* [End Date] should be later than [Start Date]</b>");
    }
    }
    });

    $('#search_invoice_btn').click(function (event) {
      var client_id = $("#invoice-client-select").val();
       document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("subsub-container").style.display = "none";
      $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:{
              "load_invoice_search": 1,
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

    $('#search_payment_btn').click(function (event) {
      var client_id = $("#payment-client-select").val();
       document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("subsub-container").style.display = "none";
      $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:{
              "load_payment_search": 1,
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

    $(document).on("click", ".cancel_pdc", function(){
        var pdc_no = $(this).attr("id");
        if(confirm("Confirm Removal of PDC No. "+pdc_no+"?")){
         $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:{
              "decline_pdc": 1,
              "pdc_no": pdc_no
            },
            success: function(data){
                alert("PDC No."+pdc_no+" successfully removed");
                displayCurrentPage();
            }
          });
        }
    });

    $(document).on("click", ".accept_pdc", function(){
        var pdc_no = $(this).attr("id");
        if(confirm("Confirm that PDC No. "+pdc_no+" has been received")){
         $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:{
              "update_pdc_status": 1,
              "pdc_no": pdc_no
            },
            success: function(data){
                alert("PDC No."+pdc_no+" successfully received");
                displayCurrentPage();
            }
          });
        }
    });

    $(document).on("click", ".unpaid_selected", function(){
      var row_id = $(this).attr("id");
      $(".loading-screen-popup-2-class").show();
                  $(".hide-data-class").hide();
      $("#inv_id").val(row_id) ;
      $("#selected_invoice").html("Invoice #"+row_id);
            //LOAD PRODUCT INFO
            $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:{
              "get_invoice_details": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
                var issued_str = new Date(data.date_issued);
                var issued = (issued_str.getMonth() + 1) + '/' + issued_str.getDate() + '/' +  issued_str.getFullYear();
                var due_str = new Date(data.date_due);
                var due = (due_str.getMonth() + 1) + '/' + due_str.getDate() + '/' +  due_str.getFullYear();
              $("#invoice_info").html("Total Amount: P"+data.total_amount+"<br/> Remaining Balance: P"+data.amount_remaining+"<br/> Date Issued: "+issued+"<br/> Due Date: "+due);
            }
          });
             //LOAD PAYMENTS MADE
            $.ajax({
            url: "payment/process.php",
            method: "POST",
            data:{
              "load_payments_made": 1,
              "row_id": row_id
            },
            success: function(data){
              $("#invoice_payment_history").html(data);
               setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
            }
          });
    });

    var client_id="";
    $("#load-invoice-btn").click(function(){

     client_id = $("#select-client").val();
        $.ajax({
            url: "payment/process.php",
            type: "POST",
            async: false,
            data:{
                "load_invoice": 1,
                "client_id": client_id
            }, 
            success: function(data){
                $("#sub-full-select").html(data);
                $(".invoice-select").select2();    
                $("#subsub-container").html(""); 
            }
        });
    });

    $('body').on('click', '#btn_submit_payments', function(e){
      var amount_whole = $("#amount_whole").val();
      var invoice = $("#inv_no").val();
      var amt_remain = $("#amt_remain").val();
      var count_D_Per = (amount_whole.match(/\./g) || []).length;  
      var rdo_pdc = document.getElementById("rdo_pdc");
      var pdc_bank = $("#pdc_bank").val();
      var pdc_no = $("#pdc_no").val();
      var pdc_date = $("#pdc_date").val();




      if(rdo_pdc.checked){

        if((amount_whole == "") || (pdc_bank == "") || (pdc_no == "") || (pdc_date == "")){
        alert("input field empty");
      }else{
        if((/^[0-9a-zA-Z\-\s]+$/).test(pdc_bank)){
         if(count_D_Per < 2){
        

        if(confirm("Save payment record?")){
        $.ajax({
            url: "payment/process.php",
            type: "POST",
            async: false,
            data: $("#form_payment").serialize()+"&invoice="+invoice+"&amt_remain="+amt_remain,
            success: function(data){
                if(data=="validated"){
                    alert("Payment record successfully made");
                    window.location.reload();
                } else{
                    alert(data);
                }
            }
        });
      }
    }else{
      alert("wrong number format");
    }
    }else{
     alert("Failed: Prohibited charatcters found, bank name should contain alphanumeric values only") 
    }
    }



      }else{
        if(amount_whole == ""){
        alert("amount field empty");
      }else{
         if(count_D_Per < 2){

        if(confirm("Save payment record?")){
        $.ajax({
            url: "payment/process.php",
            type: "POST",
            async: false,
            data: $("#form_payment").serialize()+"&invoice="+invoice+"&amt_remain="+amt_remain,
            success: function(data){
                if(data=="validated"){
                    alert("Payment record successfully made");
                    window.location.reload();
                } else{
                    alert(data);
                }
            }
        });
      }
    }else{
      alert("wrong number format");
    }
    }
    }
     });


    $('body').on('click', '#rdo_pdc', function(e){
        $.ajax({
            url: "payment/process.php",
            type: "POST",
            async: false,
            data:{
                "show_pdc": 1
            }, 
            success: function(data){
                $("#pdc_area").html(data);   
            }
        });
    });
    
    $('body').on('click', '#rdo_cash', function(e){
        $("#pdc_area").html("");
    });

    $('body').on('click', '#load-payment-btn', function(e){
        var invoice_id = $("#select-invoice").val();
        $.ajax({
            url: "payment/process.php",
            type: "POST",
            async: false,
            data:{
                "load_payment": 1,
                "invoice_id": invoice_id,
                "client_id": client_id
            }, 
            success: function(data){
                $("#subsub-container").html(data);   
            }
        });
    });

     /*where you edit*/

   setTimeout(function(){

   document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";

 }, 1000);


});

function displayRecordPayment(){          
    $.ajax({
    url: "payment/process.php",
    type: "POST",
    async: false,
    data: {
        "display_record_payment": 1
    },
    success: function(data){
        $("#sub-container").html(data);
    }
    });                      
}

function displayReceivePayment(){          
    $.ajax({
    url: "payment/process.php",
    type: "POST",
    async: false,
    data: {
        "display_receive_payment": 1
    },
    success: function(data){
        $("#sub-container").html(data);
        $(".client-select").select2();
    }
    });                      
}          

function displayInvoiceList(){          
    $.ajax({
    url: "payment/process.php",
    type: "POST",
    async: false,
    data: {
        "display_invoice_list": 1
    },
    success: function(data){
        $("#sub-container").html(data);
    }
    });                      
}

function displayUnpaidInvoice(){          
    $.ajax({
    url: "payment/process.php",
    type: "POST",
    async: false,
    data: {
        "display_unpaid_invoice": 1
    },
    success: function(data){
        $("#sub-container").html(data);
    }
    });                      
}         
 
function displayPendingPayment(){          
    $.ajax({
    url: "payment/process.php",
    type: "POST",
    async: false,
    data: {
        "display_pdc_list": 1
    },
    success: function(data){
        $("#sub-container").html(data);
    }
    });                      
}    

function displayInvoiceDate(){          
    $.ajax({
    url: "payment/process.php",
    type: "POST",
    async: false,
    data: {
        "display_date_invoice": 1
    },
    success: function(data){
        $("#sub-container").html(data);
        $("#invoice-client-select").select2();
    }
    });                      
}

function displayPaymentDate(){          
    $.ajax({
    url: "payment/process.php",
    type: "POST",
    async: false,
    data: {
        "display_date_payment": 1
    },
    success: function(data){
        $("#sub-container").html(data);
        $("#payment-client-select").select2();
    }
    });                      
}     

function displayCurrentPage(){
  var page = <?php echo (json_encode($_GET['sub']));?>;
    switch(page){
      case null:
      case 'receive': displayReceivePayment();
      break;
      case 'unpaid': displayUnpaidInvoice();
      break;
      case 'invoice': displayInvoiceDate();
      break;
      case 'pending': displayPendingPayment();
      break;
      case 'records': displayPaymentDate();
      break;
    }
} 
</script>

