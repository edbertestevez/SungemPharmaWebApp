<?php
  switch($sub){
    case 'stocks': 
    	$p_indicator = "Reports &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Inventory Report";
    	break;
    case 'consignment': 
        $p_indicator = "Reports &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Consignment Report";
        break;
    case 'products': 
        $p_indicator = "Reports &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Medicine Sales Report";
        break;
    case 'payments': 
        $p_indicator = "Reports &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Payments Report";
        break;
    case 'medreps': 
        $p_indicator = "Reports &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Medical Representatives Report";
        break; 
    default: $p_indicator = "Reports";
  }
?>

<div class="exhidden-menu">
   <button class="crossmenu">&#735;</button>
   <a href="index.php?mod=reports&sub=stocks"><div class="subitem hidden-sub-menu">Inventory</div></a>
  <a href="index.php?mod=reports&sub=consignmen"> <div class="subitem hidden-sub-menu">Consignment</div></a>
   <a href="index.php?mod=reports&sub=products"><div class="subitem hidden-sub-menu">Medicine Sales</div></a>
   <a href="index.php?mod=reports&sub=payments"><div class="subitem hidden-sub-menu">Payments</div></a>
     <a href="index.php?mod=reports&sub=medreps"> <div class="subitem hidden-sub-menu">Medical Representatives</div></a>

 </div>

<div class="card-wrapper">
  <div class="card-style-subnavi">
  <div class="sub-navi">
   <div class="hidden-menu"><button class="burgermenu">&#9776;</button></div>
  	<div class="subitem menu-subs"><a href="index.php?mod=reports&sub=stocks">Inventory</a></div>
  	<div class="subitem menu-subs"><a href="index.php?mod=reports&sub=consignment">Consignment</a></div>
    <div class="subitem menu-subs"><a href="index.php?mod=reports&sub=products">Medicine Sales</a></div>
      <div class="subitem menu-subs"><a href="index.php?mod=reports&sub=payments">Payments</a></div>
      <div class="subitem menu-subs"><a href="index.php?mod=reports&sub=medreps">Medical Representatives</a></div>
  </div>
  </div>
  <div class="indicator"><img src="img/icons/reports-black.png"/><?php echo $p_indicator;?></div>
</div>

<div id="back-black" onclick="popup_hide()"></div>
<div class="pop-container" style="height: 650px;" id="pop-container">
        <div id="popup-form" style="width: 950px;margin-left: -500px;">
        <form>   
        <h4 id="close" onclick ="popup_hide()">&times;</h4>
        <h3 id="pro_date_selected">Consigned Medicine Report:</h3>
        <hr>
        <h5 style="margin-top: 10px;" class="selected_detail" id="pro_name_selected"></h5>
        <h5 style="margin-top: 10px;" class="selected_detail" id="pro_formulation_selected"></h5>
        <h5 style="margin-top: 10px;" class="selected_detail" id="pro_packaging_selected"></h5>
        <div class="pop_report_table" id="consign_prod_table"><!----AJAX CALL FOR PER AREA----></div>
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

    //PRINT SALES REPORT
    $(document).on("click", "#print_payment", function(){
      var date_start = $("#start_date").val();
      var date_end = $("#end_date").val();
      window.open ("reports/reports_payment.php?start="+date_start+"&end="+date_end);
    });

    //PRINT SALES REPORT
    $(document).on("click", "#print_medrep", function(){
      var date_start = $("#start_date").val();
      var date_end = $("#end_date").val();
      window.open ("reports/reports_medrep.php?start="+date_start+"&end="+date_end);
    });

  //PRINT SALES REPORT
    $(document).on("click", "#print_sales", function(){
      var date_start = $("#start_date").val();
      var date_end = $("#end_date").val();
      window.open ("reports/reports_sales.php?start="+date_start+"&end="+date_end);
    });

    //PRINT CONSIGNMENT REPORT
    $(document).on("click", "#print_consignment", function(){
      var date_start = $("#start_date").val();
      var date_end = $("#end_date").val();
      window.open ("reports/reports_consignment.php?start="+date_start+"&end="+date_end);
    });

    //PRINT STOCKS REPORT
    $(document).on("click", "#print_stock", function(){
      var date_start = $("#start_date").val();
      var date_end = $("#end_date").val();
      window.open ("reports/reports_stocks.php?start="+date_start+"&end="+date_end);
    });

    //SELECTED ROW
    $(document).on("click", ".con_prod_selected", function(){
    var monthNames = ["January", "February", "March", "April", "May", "June",
          "July", "August", "September", "October", "November", "December"
    ];
     var pro_id = $(this).attr("id");
     var start_date = $("#start_date").val();
     var end_date = $("#end_date").val();
     var dateStart = new Date(start_date);
     var dateEnd = new Date(end_date);
      $("#pro_date_selected").html("Consigned Medicine Report: ("+monthNames[dateStart.getMonth()]+" "+dateStart.getDate()+", "+dateStart.getFullYear()+" to "+monthNames[dateEnd.getMonth()]+" "+dateEnd.getDate()+", "+dateEnd.getFullYear()+")");
     $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        dataType: "json",
        data: {
            "consignment_selected": 1,
            "pro_id": pro_id
        },
        success: function(data){
            $("#pro_name_selected").html("Product Name: "+ data.pro_brand+" "+data.pro_generic);
            $("#pro_formulation_selected").html("Formulation: "+ data.pro_formulation);
            $("#pro_packaging_selected").html("Packaging: "+ data.pro_packaging);
            $.ajax({

                url: "reports/process.php",
                type: "POST",
                async: false,
                data: {
                    "get_consign_prod_table": 1,
                    "pro_id": pro_id,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    $("#consign_prod_table").html(data);
                }
            });
        }
        });
    });


    //PAYMENTS NI PRE
    $(document).on("click", "#submit_payments_date", function(){
       document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("reports_area").style.display = "none";
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: $("#date_payments_form").serialize(),
        success: function(data){
            $("#reports_area").html(data);
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();

            //GRAPH one TRUEST
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data:{
                    "graph_receive_management": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var client = [];
                    var sales = [];

                      for(var i in data){
                        client.push(data[i].client_name);
                        sales.push(data[i].total_sales);
                      }

                      var chartdata = {

                        labels: client,
                        datasets:[
                        {
                          label: "Payment Collection",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: sales
                        },
                        ]
                      };

                      var ctx = $("#payments-graph-one");

                      var barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                          legend: {
                            display: false
                            },
                            scales: {
                         xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}, ticks: {fontSize: 0}}],
                        yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
                         },
                            responsive:false,
                             maintainAspectRatio: false,
                          title: 
                          {
                          display: false
                          }
                        },
                      });
                }
            });

            //AREA SALES GRAPH 2
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_management_area": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var area_name = [];
                    var area_sales = [];

                      for(var i in data){
                        area_name.push(data[i].area_name);
                        area_sales.push(data[i].total_sales);
                      }

                      var chartdata = {

                        labels: area_name,
                        datasets:[
                        {
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: area_sales
                        },
                        ]
                      };

                      var ctx = $("#payments-graph-two");

                      var barGraph = new Chart(ctx, {
                        type: 'doughnut',
                        data: chartdata,
                        options: {
                        legend: {
                            display: false
                            },
                            responsive:false,
                             maintainAspectRatio: false,
                          title: 
                          {
                          display: true,
                            text: 'Overall Sales Value'
                          }

                        },
                      });
                }
            });
        }
      });
        document.getElementById("loading-screen-custom").style.display = "none";
        document.getElementById("reports_area").style.display = "block";
      });



    //STOCKS NI PRE
    $(document).on("click", "#submit_date", function(){
        document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("reports_area").style.display = "none";
        $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: $("#date_form").serialize(),
        success: function(data){
            $("#reports_area").html(data);
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();

            //TOP SUPPLIER GRAPH 3
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_top_delivered_stock": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var med_prod = [];
                    var med_delivered = [];

                      for(var i in data){
                        med_prod.push(data[i].pro_brand + " "+data[i].pro_generic);
                        med_delivered.push(data[i].total_delivered);
                      }

                      var chartdata = {

                        labels: med_prod,
                        datasets:[
                        {
                          label: "Medicines Delivered",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: med_delivered
                        },
                        ]
                      };

                      var ctx = $("#stocks-graph-three");

                      var barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                        scales: {
                         xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}, ticks: {fontSize: 0}}],
                        yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
                         },
                        legend: {
                            display: false
                            },
                          title: 
                          {
                          display: false,
                            text: ''
                          }

                        },
                      });
                }
            });

            //TOP SUPPLIER GRAPH 1
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_top_supplied_stock": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var med_name = [];
                    var med_supplied = [];

                      for(var i in data){
                        med_name.push(data[i].pro_brand+"\n"+data[i].pro_generic);
                        med_supplied.push(data[i].total_supplied);
                      }

                      var chartdata = {

                        labels: med_name,
                        datasets:[
                        {
                          label: "Medicine Quantity",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: med_supplied
                        },
                        ]
                      };

                      var ctx = $("#stocks-graph-one");

                      var barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                        scales: {
                         xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}, ticks: {fontSize: 0}}],
                        yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true} }]
                         },
                        legend: {
                            display: false
                            },
                          title: 
                          {
                          display: false,
                            text: ''
                          }

                        },
                      });
                }
            });

            //TOP SUPPLIER GRAPH 2
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_top_supplier": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var supplier_name = [];
                    var supplied = [];

                      for(var i in data){
                        supplier_name.push(data[i].supplier_name);
                        supplied.push(data[i].total_supplied);
                      }

                      var chartdata = {

                        labels: supplier_name,
                        datasets:[
                        {
                          label: "# Supplied",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: supplied
                        },
                        ]
                      };

                      var ctx = $("#stocks-graph-two");

                      var barGraph = new Chart(ctx, {
                        type: 'doughnut',
                        data: chartdata,
                        options: {
                        legend: {
                            display: false
                            },
                          title: 
                          {
                          display: true,
                            text: 'No. of Medicines Supplied'
                          }

                        },
                      });
                }
            });

            //TOP SUPPLIED CLIENT GRAPH 2
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_top_supplied_clients": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var client_name = [];
                    var supplied = [];

                      for(var i in data){
                        client_name.push(data[i].client_name);
                        supplied.push(data[i].total_supplied);
                      }

                      var chartdata = {

                        labels: client_name,
                        datasets:[
                        {
                          label: "Qty Supplied",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f","#28B463", "#F1C40F", "#E74C3C", "#2471A3","#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f","#006c0f","#28B463"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: supplied
                        },
                        ]
                      };

                      var ctx = $("#stocks-graph-four");

                      var barGraph = new Chart(ctx, {
                        type: 'doughnut',
                        data: chartdata,
                        options: {
                        legend: {
                            display: false
                            },
                          title: 
                          {
                          display: true,
                            text: 'No. of Medicines Delivered'
                          }

                        },
                      });
                }
            });
            setTimeout(function(){
              document.getElementById("loading-screen-custom").style.display = "none";
            document.getElementById("reports_area").style.display = "block";
            },1000);  
        }
        });
    });

    $(document).on("click", "#submit_consignment_date", function(){
      document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("reports_area").style.display = "none";
        $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: $("#date_consignment_form").serialize(),
        success: function(data){
            $("#reports_area").html(data);
            //TOP CONSIGNED GRAPH
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_top_consigned": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var pro_name = [];
                    var qty = [];

                      for(var i in data){
                        pro_name.push(data[i].pro_brand+" "+data[i].pro_generic);
                        qty.push(data[i].total_qty);
                      }

                      var chartdata = {

                        labels: pro_name,
                        datasets:[
                        {
                          label: "Quantity",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: qty
                        },
                        ]
                      };

                      var ctx = $("#consign-graph-four");

                      var barGraph = new Chart(ctx, {
                        type: 'doughnut',
                        data: chartdata,
                        options: {
                        legend: {
                            display: false
                            },
                          title: 
                          {
                          display: true,
                            text: 'Delivered Consigned Medicines (Quantity)'
                          }

                        },
                      });
                    }
                });

                //2nd GRAPH KA CONSIGNMENT REPORT
                $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_area_consigned": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var area_name = [];
                    var area_qty = [];

                      for(var i in data){
                        area_name.push(data[i].area_name);
                        area_qty.push(data[i].area_total);
                      }

                      var chartdata = {

                        labels: area_name,
                        datasets:[
                        {
                          label: "Quantity",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: area_qty
                        },
                        ]
                      };

                      var ctx = $("#consign-graph-two");

                      var barGraph = new Chart(ctx, {
                        type: 'doughnut',
                        data: chartdata,
                        options: {
                        legend: {
                            display: false
                            },
                          title: 
                          {
                          display: true,
                            text: 'Delivered Consigned Medicines (Quantity)'
                          }

                        },
                      });
                }
            });

            //3nd GRAPH KA CONSIGNMENT REPORT
                $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_topsales_consigned": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                   var pro_name = [];
                    var consign_sales = [];

                      for(var i in data){
                        pro_name.push(data[i].pro_brand+"\n"+data[i].pro_generic+"\n"+data[i].pro_formulation);
                        consign_sales.push(data[i].consign_sales);
                      }

                      var chartdata = {

                        labels: pro_name,
                        datasets:[
                        {
                          label: "Consignment Sales",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: consign_sales
                        }
                        ]
                      };

                      var ctx = $("#consign-graph-three");

                      var barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                        scales: {
                         xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}, ticks: {fontSize: 0}}],
                        yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
                         },
                         legend: {
                            display: false
                            },
                          title: 
                          {
                          display: false
                          }

                        },
                      }); 
                }
            });

            //3nd GRAPH KA CONSIGNMENT REPORT
                $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_area_consign_sales": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                   var client_name = [];
                    var consign_sales = [];

                      for(var i in data){
                        client_name.push(data[i].client_name);
                        consign_sales.push(data[i].consign_sales);
                      }

                      var chartdata = {

                        labels: client_name,
                        datasets:[
                        {
                          label: "Consignment Sales",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: consign_sales
                        }
                        ]
                      };

                      var ctx = $("#consign-graph-one");

                      var barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                        scales: {
                         xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}, ticks: {fontSize: 0}}],
                        yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
                         },
                         legend: {
                            display: false
                            },
                          title: 
                          {
                          display: false
                          }

                        },
                      }); 
                }
            });
                 setTimeout(function(){
              document.getElementById("loading-screen-custom").style.display = "none";
            document.getElementById("reports_area").style.display = "block";
            },1000);    
        }
        });
    });

     $(document).on("click", "#submit_medsales_date", function(){
       document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("reports_area").style.display = "none";
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: $("#date_medsales_form").serialize(),
        success: function(data){
            $("#reports_area").html(data);
            

            //AREA SALES GRAPH 2
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    "graph_medsales_area": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var area_name = [];
                    var area_sales = [];

                      for(var i in data){
                        area_name.push(data[i].area_name);
                        area_sales.push(data[i].total_sales);
                      }

                      var chartdata = {

                        labels: area_name,
                        datasets:[
                        {
                          label: "# Supplied",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: area_sales
                        },
                        ]
                      };

                      var ctx = $("#medsales-graph-two");

                      var barGraph = new Chart(ctx, {
                        type: 'doughnut',
                        data: chartdata,
                        options: {
                        legend: {
                            display: false
                            },
                          title: 
                          {
                          display: true,
                            text: 'Overall Sales Value'
                          }

                        },
                      });
                }
            });
            //GRAPH one
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data:{
                    "graph_top_medsales": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var medicine = [];
                    var medicine_sales = [];

                      for(var i in data){
                        medicine.push(data[i].pro_brand + "-" +data[i].pro_generic+"-"+data[i].pro_formulation);
                        medicine_sales.push(data[i].total_sales);
                      }

                      var chartdata = {

                        labels: medicine,
                        datasets:[
                        {
                          label: "Sales Value",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: medicine_sales
                        },
                        ]
                      };

                      var ctx = $("#medsales-graph-one");

                      var barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                          legend: {
                            display: false
                            },
                            scales: {
                         xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}, ticks: {fontSize: 0}}],
                        yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
                         },
                            responsive:true,
                             maintainAspectRatio: false,
                          title: 
                          {
                          display: false
                          }
                        },
                      });
                }
            });
            
               setTimeout(function(){
              document.getElementById("loading-screen-custom").style.display = "none";
            document.getElementById("reports_area").style.display = "block";
            },1000);   
        }
        });
    });

     $(document).on("click", "#submit_medrepsales_date", function(){
      document.getElementById("loading-screen-custom").style.display = "block";
        document.getElementById("reports_area").style.display = "none";
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: $("#date_medrepsales_form").serialize(),
        success: function(data){
            $("#reports_area").html(data);
            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data:{
                    "graph_medrep_sales": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var medrep = [];
                    var medrep_sales = [];

                      for(var i in data){
                        medrep.push(data[i].mr_lastname);
                        medrep_sales.push(data[i].actual_sales);
                      }

                      var chartdata = {

                        labels: medrep,
                        datasets:[
                        {
                          label: "Actual Sales",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: medrep_sales
                        },
                        ]
                      };

                      var ctx = $("#medrep-graph-one");

                      var barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                          legend: {
                            display: false
                            },
                            scales: {
                         xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}}],
                        yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
                         },
                            responsive:true,
                             maintainAspectRatio: false,
                          title: 
                          {
                          display: false
                          }
                        },
                      });
                }
            });

            $.ajax({
                url: "reports/process.php",
                type: "POST",
                dataType: "json",
                async: false,
                data:{
                    "graph_medrep_medsales": 1,
                    "start_date": start_date,
                    "end_date": end_date
                },
                success: function(data){
                    var medrep = [];
                    var medsales = [];

                      for(var i in data){
                        medrep.push(data[i].mr_lastname);
                        medsales.push(data[i].total_medsales);
                      }

                      var chartdata = {

                        labels: medrep,
                        datasets:[
                        {
                          label: "Medicine Value",
                          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400","#21ed3e", "#1f5727", "#d9ffde", "#a9ffb5", "#053e0d", "#006c0f"],
                          borderColor: '#cacaca',
                          hoverBackgroundColor: 'rgba(200,200,200,1)',
                          hoverBorderColor: 'rgba(200,200,200,1)',
                          data: medsales
                        },
                        ]
                      };

                      var ctx = $("#medrep-graph-two");

                      var barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartdata,
                        options: {
                          legend: {
                            display: false
                            },
                            scales: {
                         xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}}],
                        yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
                         },
                            responsive:true,
                             maintainAspectRatio: false,
                          title: 
                          {
                          display: false
                          }
                        },
                      });
                }
            });
            setTimeout(function(){
              document.getElementById("loading-screen-custom").style.display = "none";
            document.getElementById("reports_area").style.display = "block";
            },1000); 
        }
        });
    });



    /*where you edit*/

    setTimeout(function(){
        document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";

    },1000);

});

function displayStocksReport(){
    $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: {
            "load_datepicker": 1
        },
        success: function(data){
            $("#sub-container").html(data);
        }
    });
}

function displayConsignmentReport(){
    $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: {
            "load_consignment_datepicker": 1
        },
        success: function(data){
            $("#sub-container").html(data);
        }
    });
}

function displayMedsalesReport(){
    $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: {
            "load_medsales_datepicker": 1
        },
        success: function(data){
            $("#sub-container").html(data);
        }
    });
}

function displayMedrepsalesReport(){
    $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: {
            "load_medrepsales_datepicker": 1
        },
        success: function(data){
            $("#sub-container").html(data);
        }
    });
}

function displayPaymentsReport(){
    $.ajax({
        url: "reports/process.php",
        type: "POST",
        async: false,
        data: {
            "load_payments_datepicker": 1
        },
        success: function(data){
            $("#sub-container").html(data);
        }
    });
}

function displayCurrentPage(){
    var page = <?php echo (json_encode($_GET['sub']));?>;
    switch(page){
      case 'stocks': 
        displayStocksReport();
      break;
      case 'consignment': 
        displayConsignmentReport();
      break;
      case 'products': 
        displayMedsalesReport();
      break;
      case 'payments': 
        displayPaymentsReport();
      break;
      case 'medreps': 
        displayMedrepsalesReport();
      break;
    }
}
</script>