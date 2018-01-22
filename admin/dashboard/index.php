

  <div id="loading-screen">
  <div class="card-wrapper" style="">
  <div class="card-style" style="height: 30vh; padding-top: 10%;">
  <svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
  </div>
  </div>
  </div>



<div class="card-wrapper ta-center" id="hide-top-dasboard">
<h1 style="font-weight: 500; margin-bottom: -8px;">DASHBOARD</h1>
<h2 style="font-weight: 400; font-size: 16px; padding-top: 0;"><?php echo date("F d, Y");?></h2>
  <div style="margin-right: 10px;" class="card-style-inline dashboard_three_boxes">
    <div class="notif-image"><img src="img/icons/notif_expiry.png"></div>
      <div class="notif-value" id="expiry_num"><?php echo ($products->get_count_expiry_notif() +  $products->get_count_expiry_consign());?></div>
    <button class="notif-button" id="btn_expired" onclick="popup_show_main();">View Expiring Products</button>
  </div>
  <div style="margin-right: 10px;" class="card-style-inline dashboard_three_boxes">
    <div class="notif-image"><img src="img/icons/notif_reorder.png"></div>
      <div class="notif-value" id="restock_num"><?php echo $products->get_count_restock_notif();?></div>
    <button class="notif-button" id="btn_restock" onclick="popup_show_main();">View Need Restock Products</button>
  </div>
  <div style="margin-left: 0px;" class="card-style-inline dashboard_three_boxes cancel_ML">
    <div class="notif-image"><img src="img/icons/notif_payment.png"></div>
      <div class="notif-value" id="due_payment_num"><?php echo ($payment->get_count_duepayment_notif() + $payment->get_count_pdc_notif());?></div>    
    <button class="notif-button" id="btn_overdue" onclick="popup_show_main();">View Overdue/Claim Payments</button>
  </div>
</div>
<br/>
<div class="card-wrapper-notop" id="hide-pending-trans">
  <div class="card-style-inline w-100" >
        <div class="graph_dash_title">Pending Transactions</div>
        <div class="dash-four pend_left_side"><h1 class="overview_value" id="overview_sold_sales">
        <?php echo($orders->get_pending_count());?>
        </h1><h3 class="dash-overview-h3">Pending Orders</h3></div>
        <div class="dash-four pend_left_side"><h1 class="overview_value" id="overview_con_sales">
        <?php echo($delivery->get_pending_count());?>
        </h1><h3 class="dash-overview-h3">Pending Delivery</h3></div>
        <div class="dash-four pend_left_side"><h1 class="overview_value" id="overview_con_sales">
        <?php echo($payment->get_pending_invoice());?>
        </h1><h3 class="dash-overview-h3 ">Unpaid Invoice</h3></div>
        <div class="dash-four pend_left_side"><h1 class="overview_value" id="overview_orders">
        <?php echo($payment->get_pending_cheques());?>
        </h1><h3 class="dash-overview-h3 ">Post Dated Cheques</h3></div>
  </div>
</div>

<div class="card-wrapper ta-center" id="hide-Med-Sales">
  <div class="card-style-inline w-100" >
    <div class="graph_dash_title">
      Sungem Pharma Medicine Sales Value For the Year <?php echo date("Y");?>
    </div>
    <div class="dash-graphs-large">
      <canvas id="year-sales" height="320px"></canvas>
    </div>
  </div>
</div>
<div class="card-wrapper ta-center" id="hide-Man-Sales">
  <div class="card-style-inline w-100">
    <div class="graph_dash_title">
      Sungem Pharma Management Sales Value For the Year <?php echo date("Y");?>
    </div>
    <div class="dash-graphs-large">
      <canvas id="year-actual-sales" height="320px"></canvas>
    </div>
  </div>
</div>
<div class="card-wrapper-notop" id="hide-overview">
  <div class="card-style-inline w-100">
        <div class="graph_dash_title">Overview for the month of <?php echo date("F Y");?></div>
        <div class="dash-four overview_tab"><h1 class="overview_value" id="overview_sold_sales">
        <?php 
        $month_sales= $reports->get_monthly_management_sales();
        if($month_sales!=""){
          echo "&#8369;".number_format($month_sales, 2, '.', ',');
        }else{
          echo "&#8369;0";
        }
        ?>
        </h1><h3 class="dash-overview-h3">Management Sales</h3></div>
        <div class="dash-four overview_tab"><h1 class="overview_value" id="overview_con_sales">
        <?php 
        $med_sales= $reports->get_monthly_medicine_sales();
        if($med_sales!=""){
          echo "&#8369;".number_format($med_sales, 2, '.', ',');
        }else{
          echo "&#8369;0";
        }
        ?>
        </h1><h3 class="dash-overview-h3">Medicines Sales Value</h3></div>
        <div class="dash-four overview_tab"><h1 class="overview_value" id="overview_orders">
        <?php 
        $order_count= $reports->get_monthly_order_count();
        if($order_count!=""){
          echo $order_count;
        }else{
          echo "0";
        }
        ?>
        </h1><h3 class="dash-overview-h3">Orders Received</h3></div>
        <div class="dash-four overview_tab"><h1 class="overview_value" id="overview_consigned">
        <?php 
        $consigned_count= $reports->get_monthly_consigned_count();
        if($consigned_count!=""){
          echo $consigned_count;
        }else{
          echo "0";
        }
        ?>
        </h1><h3 class="dash-overview-h3">Consigned Medicines</h3></div>
  </div>
</div>
<div class="card-wrapper-notop w-100" id="hide-top-sell-meds">
    <div class="card-style-inline2 top-med" style="">
        <div class="graph_dash_title ">Top Selling Medicines (<?php echo date("F, Y");?>)</div>
          <div class="dash-graphs-large">
            <canvas height="445px" id="month-med-sales"></canvas>
          </div>
    </div>
    <div class="card-style-inline2 med-sales" style="" id="hide-medRep-sales">
      <div class="graph_dash_title">Medical Representative Sales (<?php echo date("F, Y");?>)</div>
        <canvas id="month-medrep-sales" height="210px"></canvas>
    </div>
</div>
      


  <!---POPUP NOTIFICATION---->
  <div id="back-black" onclick="popup_hide_main()"></div>
    <div class="pop-container style-4" id="pop-container-main">
      <div id="popup-form" style="width: 1100px;margin-left: -550px;">
        <div class="form_area">
        <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div class="hide-data-class">
          <h4 id="close" onclick ="popup_hide_main()">&times;</h4>
          <h3 id="notif_title" class="red-text"></h3>
            <div id="notif_content_list">
              <!----CALL AJAX FOR CONTENT-->
            </div>
            <div class="material-button-wrapper">
              <button style="float: right;" class="material-button-main ta-right" onclick="popup_hide_main()">Close</button>
            </div>
        </div>
        </div>
      </div>
    </div>
</div>
<!-------------------------->

<script type="text/javascript">
$(document).ready(function(){	


	$('body').css('background-color', '#e9e8e8'); // CHANGE BACKGROUND
	//PAMUTANG SA ISA KA AJAX PARA MAY LOADING GIF KNG LOADING PA NI TANAN
  displayYearSales();
  displayYearManagementSales();
	displayTopMedsales();
	displayMedrepSales();

  $(document).on("click", "#btn_expired", function(){
    $("#notif_title").html("Expiring Products");
    $(".loading-screen-popup-2-class").show();
      $(".hide-data-class").hide();
    $.ajax({
    url: "dashboard/process.php",
    type: "POST",
    async: false,
    data: {
        "get_expiring_products": 1
    },
    success: function(data){
      $("#notif_content_list").html(data);
      setTimeout(function(){
      $(".loading-screen-popup-2-class").hide();
      $(".hide-data-class").show();
      }, 1000);
    }
  });

  });

  $(document).on("click", "#btn_restock", function(){
    $("#notif_title").html("Needs Restock Products");
    $(".loading-screen-popup-2-class").show();
      $(".hide-data-class").hide();
    $.ajax({
    url: "dashboard/process.php",
    type: "POST",
    async: false,
    data: {
        "get_needs_restock": 1
    },
    success: function(data){
      $("#notif_content_list").html(data);
      setTimeout(function(){
      $(".loading-screen-popup-2-class").hide();
      $(".hide-data-class").show();
      }, 1000);
    }
  });
  });

  $(document).on("click", "#btn_overdue", function(){
    $("#notif_title").html("Pending Post Dated Cheques (2 days)");
    $(".loading-screen-popup-2-class").show();
      $(".hide-data-class").hide();
    $.ajax({
    url: "dashboard/process.php",
    type: "POST",
    async: false,
    data: {
        "get_overdue_payments": 1
    },
    success: function(data){
      $("#notif_content_list").html(data);
       setTimeout(function(){
      $(".loading-screen-popup-2-class").hide();
      $(".hide-data-class").show();
      }, 1000);

    }
  });
  });

  /**e";
  **/

  setTimeout(function(){
    document.getElementById("loading-screen").style.display = "none";
    document.getElementById("year-sales").style.height = "320px";
  document.getElementById("year-actual-sales").style.height = "320px";
  document.getElementById("month-med-sales").style.height = "445px";
  document.getElementById("month-medrep-sales").style.height = "210px";
  document.getElementById("hide-pending-trans").style.height = "190px";
  document.getElementById("hide-top-dasboard").style.display = "block";
  document.getElementById("hide-pending-trans").style.display = "block";
  document.getElementById("hide-Med-Sales").style.display = "block";
  document.getElementById("hide-Man-Sales").style.display = "block";
  document.getElementById("hide-overview").style.display = "block";
  document.getElementById("hide-top-sell-meds").style.display = "block";
  document.getElementById("hide-medRep-sales").style.display = "block";
  }, 1000);
  
 
 

});

function displayExpiryNum(){
  //BOTH SA STOCKS KAG CONSIGNMENT NI SYA
  $.ajax({
    url: "dashboard/process.php",
    type: "POST",
    async: false,
    data: {
        "display_expiry_num": 1
    },
    success: function(data){
      if(data==0){
          $("#expiry_num").html("0");
      }else{
          $("#expiry_num").html(data);
      }
    }
  });
}
function displayMedrepSales(){
	var dateToday = new Date();
	$.ajax({
    url: "dashboard/process.php",
    type: "POST",
    async: false,
    dataType: "json",
    data: {
        "graph_medrep_sales": 1
    },
    success: function(data){
    	var medrep_name = [];
        var sales = [];
		var monthNames = ["January", "February", "March", "April", "May", "June",
		  "July", "August", "September", "October", "November", "December"
		];
      for(var i in data){
        medrep_name.push(data[i].mr_firstname);
        sales.push(data[i].total_sales);
      }

      var chartdata = {

        labels: medrep_name,
        datasets:[
        {
          label: "Sales",
          backgroundColor: ["#28B463", "#F1C40F", "#E74C3C", "#2471A3","#8E44AD","#D35400"],
          borderColor: '#cacaca',
          hoverBackgroundColor: 'rgba(200,200,200,1)',
          hoverBorderColor: 'rgba(200,200,200,1)',
          data: sales
        }
        ]
      };

      var ctx = $("#month-medrep-sales");

      var barGraph = new Chart(ctx, {
        type: 'horizontalBar',
        data: chartdata,
        options: {
          legend: { display: false },
          scales:{
          xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}}],
          yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
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
}

function displayTopMedsales(){
	var dateToday = new Date();
	$.ajax({
    url: "dashboard/process.php",
    type: "POST",
    async: false,
    dataType: "json",
    data: {
        "graph_top_medsales": 1
    },
    success: function(data){
    	var prod_name = [];
        var sales = [];
		var monthNames = ["January", "February", "March", "April", "May", "June",
		  "July", "August", "September", "October", "November", "December"
		];
      for(var i in data){
        prod_name.push(data[i].pro_brand+"\n"+data[i].pro_generic+"\n("+data[i].pro_packaging+")");
        sales.push(data[i].med_sales);
      }

      var chartdata = {

        labels: prod_name,
        datasets:[
        {
          label: 'Sales',
          backgroundColor: ["#E74C3C","#2471A3","#F1C40F","#D35400","#28B463"],
          borderColor: '#cacaca',
          hoverBackgroundColor: 'rgba(200,200,200,1)',
          hoverBorderColor: 'rgba(200,200,200,1)',
          data: sales
        }
        ]
      };

      var ctx = $("#month-med-sales");

      var barGraph = new Chart(ctx, {
        type: 'bar',
        data: chartdata,
        options: {
          scales:{
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
}


function displayYearSales(){
	var dateToday = new Date();
	var max_month=0;
	$.ajax({
    url: "dashboard/process.php",
    type: "POST",
    async: false,
    dataType: "json",
    data: {
        "get_max_month": 1
    },
    success: function(data){
    	max_month=12; //LATEST MONTH NA MAY SALES
		//GRAPH AJAX CALL
    	$.ajax({
	    url: "dashboard/process.php",
	    type: "POST",
	    dataType: "json",
	    data: {
	        "graph_year_sales": 1
	    },
	    success: function(data){
	     var month = [];
     	 var sales = [];
     	 var month_ctr=1;
     	 var month_check=false;
     	 var cur_month="";
     	 var cur_sales="";
     	 var monthNames = ["January", "February", "March", "April", "May", "June",
		  "July", "August", "September", "October", "November", "December"
		];

     	 for(month_ctr; month_ctr<=max_month; month_ctr++){
     	 for(var i in data){
     	 	if(month_ctr==data[i].month_selected){
     	 		month_check=true;
     	 		cur_month = data[i].month_selected;
     	 		cur_sales = data[i].month_sales;
     	 	}
     	 }
     	 
     	 if(month_check==true){
     	 	month.push(monthNames[cur_month-1]); //MINUS 1 kay start ka array is 0
        	sales.push(cur_sales);
        }else{
        	month.push(monthNames[month_ctr-1]);  //MINUS 1 kay start ka array is 0
        	sales.push(0);
        }
        month_check=false;
     	}
        	
      	
      	var chartdata = {

	        labels: month,
	        datasets:[
	        {
            pointRadius: 4,
            pointHoverRadius: 8,
	          label: 'Medicines Sales',
	          backgroundColor: '#2471A3',
	          borderColor: '#cacaca',
	          hoverBackgroundColor: 'rgba(200,200,200,1)',
	          hoverBorderColor: 'rgba(200,200,200,1)',
	          data: sales
	        }
	        ]
	      };
	      var ctx = $("#year-sales");

	      var barGraph = new Chart(ctx, {
	        type: 'line',
	        data: chartdata,
	        options: {
            responsive:true,
          maintainAspectRatio: false,
            scales:{
          xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}}],
          yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
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
    }
    });     

                
}       
function displayYearManagementSales(){
  var dateToday = new Date();
      
    //GRAPH AJAX CALL
      $.ajax({
      url: "dashboard/process.php",
      type: "POST",
      dataType: "json",
      data: {
          "graph_year_actual_sales": 1
      },
      success: function(data){
       var max_month=12; 
       var month = [];
       var sales = [];
       var month_ctr=1;
       var month_check=false;
       var cur_month="";
       var cur_sales="";
       var monthNames = ["January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];

       for(month_ctr; month_ctr<=max_month; month_ctr++){
       for(var i in data){
        if(month_ctr==data[i].month_selected){
          month_check=true;
          cur_month = data[i].month_selected;
          cur_sales = data[i].month_collection;
        }
       }
       
       if(month_check==true){
        month.push(monthNames[cur_month-1]); //MINUS 1 kay start ka array is 0
          sales.push(cur_sales);
        }else{
          month.push(monthNames[month_ctr-1]);  //MINUS 1 kay start ka array is 0
          sales.push(0);
        }
        month_check=false;
      }
          
        
        var chartdata = {

          labels: month,
          datasets:[
          {
            pointRadius: 4,
            pointHoverRadius: 8,
            label: 'Management Sales',
            backgroundColor: '#D35400',
            borderColor: '#cacaca',
            hoverBackgroundColor: 'rgba(200,200,200,1)',
            hoverBorderColor: 'rgba(200,200,200,1)',
            data: sales
          }
          ]
        };
        var ctx = $("#year-actual-sales");

        var barGraph = new Chart(ctx, {
          type: 'line',
          data: chartdata,
          options: {
            responsive:true,
          maintainAspectRatio: false,
            scales:{
          xAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",}}],
          yAxes: [{gridLines: {color: "rgba(0, 0, 0, 0)",},ticks: {beginAtZero: true}}]
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

                
}       
</script>
