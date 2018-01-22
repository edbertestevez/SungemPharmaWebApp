<?php
  switch($sub){
    case 'receive_supplies': 
      $p_indicator = "Stocks &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Receive Supplies";
      break;
    case 'view_stocks': 
      $p_indicator = "Stocks  &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  View Stocks";
      break;
    case 'prod_dispose': 
      $p_indicator = "Stocks  &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Dispose Products";
      break;
    case 'consignment': 
      $p_indicator = "Stocks  &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Consignment";
      break;
    case 'client_withdraw': 
      $p_indicator = "Stocks  &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Goods Return";
      break;
    default: $p_indicator = "Stocks";
  }
?>
<div class="exhidden-menu">
   <button class="crossmenu">&#735;</button>
   <a href="index.php?mod=stocks&sub=view_stocks"><div class="subitem hidden-sub-menu">View Stocks</div></a>
  <a href="index.php?mod=stocks&sub=receive_supplies"> <div class="subitem hidden-sub-menu">Receive Supplies</div></a>
  <a href="index.php?mod=stocks&sub=consignment"> <div class="subitem hidden-sub-menu">Consignment</div></a>
  <a href="index.php?mod=stocks&sub=prod_dispose"> <div class="subitem hidden-sub-menu">Dispose Products</div></a>
  <a href="index.php?mod=stocks&sub=client_withdraw"> <div class="subitem hidden-sub-menu">Goods Return</div></a>
 </div>

<div class="card-wrapper">
  <div class="card-style-subnavi">
    <div class="sub-navi">
      <div class="hidden-menu"><button class="burgermenu">&#9776;</button></div>
        <div class="subitem menu-subs"><a href="index.php?mod=stocks&sub=view_stocks">View Stocks</a></div>
      <div class="subitem menu-subs"><a  href="index.php?mod=stocks&sub=receive_supplies">Receive Supplies</a></div>
      <div class="subitem menu-subs"><a href="index.php?mod=stocks&sub=consignment">Consignment</a></div>
      <div class="subitem menu-subs"><a href="index.php?mod=stocks&sub=prod_dispose">Dispose Products</a></div>
      <div class="subitem menu-subs"><a href="index.php?mod=stocks&sub=goods_return">Goods Return</a></div>
      
    </div>
  </div>
  <div class="indicator">
    <img src="img/icons/stocks-black.png"/><?php echo $p_indicator;?>
  </div>
</div>

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
  <h3 id="selected_prodname"></h3>
  <div id="prod_selected_info">
    <input type="hidden" id="clicked_id"/>
    <h5 class="info" id="selected_addinfo"></h5>
    <button class="material-button-main" id="stock_card_btn" onclick="popup_hide()">View Stock Card</button>
    <h5 class="info" id="selected_total"></h5>
    <div id="stock_prod_available">
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

<?php
  switch($sub){
    case 'receive_supplies': 
      $suplist = $suppliers->get_suppliers_active();
      ?>
 <!--     <div id="loading-screen-2">
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
          <div id="sub-container">
            <form id="supplies_form" method="post" name="supplies_form">
            <input type="hidden" name="add_supplies" value="1">
            <div class="full-invi">
            <div class="select-container">
            <h3>Select Supplier:</h3>
            
            <select class="supplier-select" name="supplier" id="supplier" style="width: 100%;">
                <?php
                    if($suplist){
                    foreach($suplist AS $suprow){ 
                ?>
                  <option value=<?php echo $suprow['supplier_id'];?>><?php echo $suprow['supplier_name'];?></option>
                <?php
                  }
                }
                ?>
            </select>
            </div>
            </div>

            <!--UPDATES SA BARCODE-->
            <input type="text" placeholder="Enter Barcode . . . ." id="barcode_input"/> 

            <h3 class="low-margin">Received Products:</h3>
            <table class="table table-responsive table-striped light-border" id="table-supplies" style="margin-bottom: 5px;">
              <thead>
              <tr>
                <th class="normal">Product Name</th>
                <th class="normal">Lot No.</th>
                <th class="normal">Expiry Date</th>
                <th class="normal">Quantity</th>
                <th class="normal"></th>
              </tr>
              </thead>
              <tbody>
              <!--AJAX CONTENT-->
             
              <script>
                $('.material-input').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
      
    });



              </script>

              </tbody>

            </table>
             <input type="button" class="material-button" value="+ Add Product" id="addrow" />
          </form>
          </div>
          <div class="full-area">
              <input type="button" class="material-button-main ta-center" value="Save" id="save_supplied" />
          </div>
        </div>
      </div>
      <?php
      break;
  }
if(isset($_GET['sub']) && $_GET['sub']!="receive_supplies"){
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
      <div id="sub-container-rows"></div>
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

$("#barcode_input").focus();

displayCurrentPage();
//Display sang wala option
loadLotNone();
  
  //STOCK CARD
  $(document).on("click", "#stock_card_btn", function(){
    var product_id = $("#clicked_id").val();
    $.ajax({
        url: "stocks/process.php",
        type: "POST",
        async: false,
        data:{
          "view_stock_card": 1,
          "pro_id": product_id
        },
        success: function(data){
            $("#sub-container-rows").html(data);
        }
      });
  });


  //BARCODE APPEND ROW PRODUCT
  $(document).on("keydown", "#barcode_input", function(){
    var barcode_value = $("#barcode_input").val();

    //ON ENTER PRESS
    if (event.keyCode == 13) {
      $.ajax({
        url: "stocks/process.php",
        type: "POST",
        async: false,
        data:{
          "search_barcode": 1,
          "barcode_value": barcode_value
        },
        success: function(data){
            if(data=="0"){
              alert("Product does not exist")

            }else{
              //put value para ma check kag selected
              appendProductRow(data);              
            }
            $("#barcode_input").val("");
        }
      });
    }
  });

  $(document).on("click", "#client_consign_btn", function(){
    var client_id = $("#consign-client-select").val();
    document.getElementById("loading-screen-3").style.display = "block";
    document.getElementById("subsub-container").style.display = "none";
    $.ajax({
      url: "stocks/process.php",
      type: "POST",
      async: false,
      data:{
        "display_consignment_stock": 1,
        "client_id": client_id
      },
      success: function(data){
       

             $("#subsub-container").html(data);
              document.getElementById("loading-screen-3").style.display = "none";
              document.getElementById("subsub-container").style.display = "block";
   
    
      }
    });
  });

  $(document).on("click", "#submit_consign_withdraw_btn", function(){
    var client_id = $("#consign-client-select").val();
    var with_quan = $("#withdraw_qtyss").val();

    if(confirm("Are you sure you want to return goods?")){
      $.ajax({
      url: "stocks/process.php",
      type: "POST",
      async: false,
      data:$('#withdraw_consign_form').serialize()+"&client_id="+client_id,
      success: function(data){
        if(data=="" || data==null){
          alert("Products successfully returned");
          $("#subsub-container").html("");
          $("#subsubsub-container").html("");
        }else{
          alert(data);
        }
      }
    });
    }
  });

  $(document).on("click", "#load_consign_orders_btn", function(){
    var client_id = $("#consign-client-select").val();
    document.getElementById("loading-screen-custom").style.display = "block";
    document.getElementById("subsubsub-container").style.display = "none";
    $.ajax({
    url: "stocks/process.php",
    type: "POST",
    async: false,
    data:{
      "load_consignment_items": 1,
      "client_id": client_id
    },
    success: function(data){
         $("#subsubsub-container").html(data);
      document.getElementById("loading-screen-custom").style.display = "none";
    document.getElementById("subsubsub-container").style.display = "block";
     
    
    }
  });
  });

  $(document).on("click", "#load_sold_orders_btn", function(){
    var client_id = $("#return-client-select").val();
    document.getElementById("loading-screen-custom").style.display = "block";
    document.getElementById("subsubsub-container").style.display = "none";
    $.ajax({
    url: "stocks/process.php",
    type: "POST",
    async: false,
    data:{
      "load_sold_orders": 1,
      "client_id": client_id
      },
    success: function(data){
       
             $("#subsubsub-container").html(data);
             $("#select-order").select2();
              document.getElementById("loading-screen-custom").style.display = "none";
              document.getElementById("subsubsub-container").style.display = "block";
        
     
    }
  });
  });

  $(document).on("click", "#load_ordtype_content", function(){
    var type_id = $("#select-type").val();
      $.ajax({
      url: "stocks/process.php",
      type: "POST",
      async: false,
      data:{
        "display_client_withdraw": 1,
        "type_id": type_id
      },
      success: function(data){
        $("#subsub-container").html(data);
        $("#return-client-select").select2();
        //$("#select-order").select2();
        $("#consign-client-select").select2();
      }
    });
  });



  $(document).on("click", "#submit_withdraw_btn", function(){
    var order_id = $("#select-order").val();
    if(confirm("Are you sure you want to withdraw?")){
      $.ajax({
      url: "stocks/process.php",
      type: "POST",
      async: false,
      data:$('#withdraw_form').serialize()+"&withdraw_action=1&order_id="+order_id,
      success: function(data){
        if(data!="validated"){
          alert(data);
        }else{
          alert("Goods Return successfully recorded");
          $("#subsub-container").html("");
          $("#subsubsub-container").html("");
        }
      }
    });
    }
  });


  $(document).on("click", "#load_orders_btn", function(){
    var order_id = $("#select-order").val();
    document.getElementById("loading-screen-custom").style.display = "block";
    document.getElementById("items-container").style.display = "none";
    $.ajax({
    url: "stocks/process.php",
    type: "POST",
    async: false,
    data:{
      "load_delivered_orders": 1,
      "order_id": order_id
    },
    success: function(data){
             $("#items-container").html("<hr/><br/>"+data);
              document.getElementById("loading-screen-custom").style.display = "none";
              document.getElementById("items-container").style.display = "block";
        
     
    }
  });
  });

  $(document).on("click", "#submit_dispose_btn", function(){
    var dispose_select = $("#dispose_select").val();
    var dispose_lot = $("#dispose_lot").val();
    var dispose_quantity_number = $("#dispose_quantity_number").val();


    if((dispose_quantity_number == "") || (dispose_lot == null) || (dispose_select == null)){

      alert("input field empty");

  
  }else{
    

     if(confirm("Proceed disposing this product?")){
   $.ajax({
            url: "stocks/process.php",
            type: "POST",
            async: false,
            data: $('#disposal_form').serialize(),
            success: function(data){
              if(data=="success"){
                alert("Product has been disposed");
                window.location.reload();
              }else{
                alert(data);
              }
            }
        });
  }
  }

  });

  $("#dispose_select").on('change', function(){
    var pro_id = $("#dispose_select").val();
    $.ajax({
            url: "stocks/process.php",
            type: "POST",
            async: false,
            data:{
              "load_lot_dispose":1,
              "pro_id": pro_id
            },
            success: function(data){
              $("#select_lot_area").html(data);
            }
        });
    $.ajax({
            url: "stocks/process.php",
            type: "POST",
            async: false,
            data:{
              "load_dispose_packaging":1,
              "pro_id": pro_id
            },
            success: function(data){
              $("#dispose_package").html(data);
              $("#dispose_lot").select2();
            }
        });
  });

  $(document).on("click", ".stock_selected", function(){
      var row_id = $(this).attr("id");
      $(".loading-screen-popup-2-class").show();
       $(".hide-data-class").hide();
            //LOAD PRODUCT INFO
            $.ajax({
            url: "stocks/process.php",
            method: "POST",
            data:{
              "get_stock_name": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              var prodname = data.pro_brand + " " + data.pro_generic;
              $("#clicked_id").val(data.pro_id);
              $("#selected_prodname").html(prodname);
              $("#selected_addinfo").html("Formulation: "+data.pro_formulation+"<br/>Packaging: "+data.pro_packaging);
              $("#selected_total").html("Warehouse Stocks: ("+data.pro_total_qty+")");
            }
          });
            //LOAD LOT NUMBERS AVAILABLE
            $.ajax({
            url: "stocks/process.php",
            method: "POST",
            data:{
              "load_avail_stock": 1,
              "row_id": row_id
            },
            success: function(data){
              $("#stock_prod_available").html(data);
               setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);

            }
          });
    });

  $("#save_supplied").click(function(){

    var lot = $("#lot").val();
    var expiry = $("#expiry").val();
    var quantity = $("#quantity").val();
    var addrow = document.getElementById("addrow");

   
  if (confirm('Confirm supplied products')) {
        $('select').removeAttr('disabled');
          $.ajax({
            url: "stocks/process.php",
            type: "POST",
            dataType: "JSON",
            async: false,
            data:$('#supplies_form').serialize(), //May hidden na add-Order sa form
            success: function(data){
              if(data=="incomplete"){
                alert("Please fill up all fields");
                for(var y=0; y<i; y++){
                if($("#select"+y).val()!=null){
                    document.getElementById("select"+y).disabled=true;
                }
              }
            }else{
              alert("Supplies successfully added");
              window.location.reload();
            } 
          },
          error: function(xhr, status, error) {
              alert("No Products Supplied!");
          }
      });
        }
      

      });
  //SELECT2 JQUERY
  $(".supplier-select").select2();
  $(".prod-select").select2();


   /*where you edit*/
  setTimeout(function(){
    document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
   }, 1000);

});
  

  //APPEND PRODUCT ROW
  var i=0;
  var ctr=0;
  $('#addrow').click(function(){
    appendProductRow(null);          
  });

      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
            if(ctr>0){
            ctr--;
          }
      });

function appendProductRow(product){
            ctr++;
            var x=0;
            var error = 0;
            //var array_selected = "";
            /*if(ctr>0){
            for(x; x<i; x++){
                if($("#select"+x).val()!=null){
                    document.getElementById("select"+x).disabled=true;
                    if(x<i-1){
                      if($("#select"+x).val()==product){
                        error++;
                      }
                      array_selected+=$("#select"+x).val()+",";
                    }else{
                        array_selected+=$("#select"+x).val();
                    }
                }     
            }
            }else{
                document.getElementById("select0").disabled=false;
            }*/
            //if(error==0){
               $('#table-supplies').append('<tr id="row'+i+'"></tr>');  
                $.ajax({
                url: "stocks/process.php",
                type: "POST",
                async: false,
                data: {
                  "add_row": 1,
                  "i": i,
                  //"selected": array_selected,
                  "pro_id": product
                },
                success: function(data){
                    $("#row"+i).html(data);
                    $(".prod-select").select2();
                }
              }); 
              //add row id
              i++;
          //}else{
            //alert("Product is already part of the list");
          //}
}

function displayStocksTable(){          
          $.ajax({
            url: "stocks/process.php",
            type: "POST",
            async: false,
            data: {
              "display_stocks_table": 1
            },
            success: function(data){
                $("#sub-container-rows").html(data);
            }
          });
}

function displayDisposeProducts(){          
          $.ajax({
            url: "stocks/process.php",
            type: "POST",
            async: false,
            data: {
              "display_dispose": 1
            },
            success: function(data){
                $("#sub-container-rows").html(data);
                
            }
          });
}

function loadLotNone(){          
          $.ajax({
            url: "stocks/process.php",
            type: "POST",
            async: false,
            data: {
              "load_lot_none": 1
            },
            success: function(data){
                $("#select_lot_area").html(data);
                $("#dispose_lot").select2();
            }
          });
}

function displayTypeWithdraw(){
  $.ajax({
    url: "stocks/process.php",
    type: "POST",
    async: false,
    data:{
      "display_type_withdraw": 1
    },
    success: function(data){
      $("#sub-container-rows").html(data);
      $("#select-type").select2();
    }
  });
}

function displayConsignedClients(){
  $.ajax({
    url: "stocks/process.php",
    type: "POST",
    async: false,
    data:{
      "display_consigned_clients": 1
    },
    success: function(data){
      $("#sub-container-rows").html(data);
      $("#consign-client-select").select2();
    }
  });
}

function displayCurrentPage(){
 var page = <?php echo (json_encode($_GET['sub']));?>;
    switch(page){
      case null:
      case 'view_stocks': displayStocksTable();
      break;
      case 'prod_dispose': displayDisposeProducts();

$("#dispose_select").select2();
      break;
      case 'goods_return': displayTypeWithdraw();
      break;
      case 'consignment': displayConsignedClients();
      break;
    }
}


$('#lot').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
      
    });

$('#dispose_quantity_number').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
      
    });



  

</script>