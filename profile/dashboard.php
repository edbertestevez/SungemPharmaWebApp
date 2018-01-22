<div class="card-wrapper-leftright profile-right-main-cont">
	<div class="card-style w-100 orange">

      <h3 style="color: #fff;">Dashboard</h3>
      <h5 style="margin-bottom: 25px;"></h5>
      <div class="profile-dash-graph">
        <div class="profile-dash-counter">
          <div class="profile-dash-counter-col">
            <h5>Consigned Medicines</h5>
            <h1><?php if($user->get_consigned_meds($_SESSION['clientid'])){ echo $user->get_consigned_meds($_SESSION['clientid']);}else{ echo "0";}?></h1>
					</div>
					<div class="profile-dash-counter-col">
            <h5>Unpaid Invoice</h5>
            <h1><?php echo $user->get_unpaid_invoice($_SESSION['clientid']);?></h1>
          </div>
          <div class="profile-dash-counter-col">
            <h5>Total Orders</h5>
            <h1><?php $count = 0; $list = $user->get_order_history($_SESSION['clientid']); if($list){foreach($list as $l){
              $count++;}} echo $count;?></h1>
          </div>
          <div class="profile-dash-counter-col">
						<h5>Pending Orders</h5>
						<h1><?php echo $user->get_pending_orders($_SESSION['clientid']);?></h1>
					</div>
					<div class="profile-dash-counter-col">
            <h5>Approved Orders</h5>
            <h1><?php echo $user->get_approved_orders($_SESSION['clientid']);?></h1>
          </div>
          
        </div>
      </div>

  </div>
</div>


<div class="card-wrapper-primary profile-right-main-cont">
  <div class="card-style w-100" >
    <h4 style="color: rgba(0,0,0,0.75);">Consignment Stats</h4>
    <div class="dash-graphs-large" style="height: 286px;">
			<div class="loading-wrapper" style="text-align: center;">
				<svg id="loading-screen" class="spinner" width="40px" height="40px" style="margin-bottom: 60px;" viewBox="0 0 66 66">
  				<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  			</svg>
			</div>
      <canvas id="year-consignment" style="display: none;" height="320px"></canvas>
    </div>
  </div>
</div>
<div style="margin-top: 8px;" class="card-wrapper-secondary profile-right-main-cont">
  <div class="card-style w-100" >
    <h4 style="color: rgba(0,0,0,0.75);">Considered Sold Stats</h4>
    <div class="dash-graphs-large" style="height: 286px;">
			<div class="loading-wrapper" style="text-align: center;">
				<svg id="loading-screen" class="spinner" width="40px" height="40px" style="margin-bottom: 60px;" viewBox="0 0 66 66">
  				<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  			</svg>
			</div>
      <canvas id="year-sold" style="display: none;" height="320px"></canvas>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){	
	$('body').css('background-color', '#e9e8e8'); // CHANGE BACKGROUND
	//PAMUTANG SA ISA KA AJAX PARA MAY LOADING GIF KNG LOADING PA NI TANAN
	displayConsignmentYearSales();
	displaySoldYearSales();
  

});

function displayConsignmentYearSales(){
	var dateToday = new Date();
	var max_month=0;
  
	$.ajax({
    url: "profile/process.php",
    type: "POST",
    async: false,
    dataType: "json",
    data: {
        "get_max_month": 1
    },
    success: function(data){
			setTimeout(function() {
			$("#year-consignment").css("display","block");
			$(".loading-wrapper").css("display","none");
    	max_month=12; //LATEST MONTH NA MAY SALES
		//GRAPH AJAX CALL
    	$.ajax({
	    url: "profile/process.php",
	    type: "POST",
	    dataType: "json",
	    data: {
	        "graph_consigned": 1
	    },
	    success: function(data){
				
	     var month = [];
     	 var con = [];
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
        	con.push(cur_sales);
        }else{
        	month.push(monthNames[month_ctr-1]);  //MINUS 1 kay start ka array is 0
        	con.push(0);
        }
        month_check=false;
     	}
        	
      	
      	var chartdata = {

	        labels: month,
	        datasets:[
	        {
            pointRadius: 4,
            pointHoverRadius: 8,
	          label: 'Consignment',
	          backgroundColor: '#ff6029',
	          borderColor: '#cacaca',
	          hoverBackgroundColor: 'rgba(200,200,200,1)',
	          hoverBorderColor: 'rgba(200,200,200,1)',
	          data: con
	        }
	        ]
	      };
	      var ctx = $("#year-consignment");

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
		}, 0);
    }
    });                   
}    

function displaySoldYearSales(){
	var dateToday = new Date();
	var max_month=0;
  
	$.ajax({
    url: "profile/process.php",
    type: "POST",
    async: false,
    dataType: "json",
    data: {
        "get_max_month": 1
    },
    success: function(data){
			setTimeout(function() {
			$("#year-sold").css("display","block");
			$(".loading-wrapper").css("display","none");
    	max_month=12; //LATEST MONTH NA MAY SALES
		//GRAPH AJAX CALL
    	$.ajax({
	    url: "profile/process.php",
	    type: "POST",
	    dataType: "json",
	    data: {
	        "graph_sold": 1
	    },
	    success: function(data){
				
	     var month = [];
     	 var con = [];
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
        	con.push(cur_sales);
        }else{
        	month.push(monthNames[month_ctr-1]);  //MINUS 1 kay start ka array is 0
        	con.push(0);
        }
        month_check=false;
     	}
        	
      	
      	var chartdata = {

	        labels: month,
	        datasets:[
	        {
            pointRadius: 4,
            pointHoverRadius: 8,
	          label: 'Considered Sold',
	          backgroundColor: '#FF9900',
	          borderColor: '#cacaca',
	          hoverBackgroundColor: 'rgba(200,200,200,1)',
	          hoverBorderColor: 'rgba(200,200,200,1)',
	          data: con
	        }
	        ]
	      };
	      var ctx = $("#year-sold");

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
		}, 0);
    }
    });                   
}    

</script>