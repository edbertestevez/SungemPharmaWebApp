<?php
include 'library/config.php';
include 'classes/class.clients.php';
include 'classes/class.suppliers.php';
include 'classes/class.users.php';
include 'classes/class.medreps.php';
include 'classes/class.products.php';
include 'classes/class.orders.php';
include 'classes/class.payments.php';
include 'classes/class.reports.php';
include 'classes/class.delivery.php';

$clients = new Clients();
$suppliers = new Suppliers();
$users = new Users();
$medreps = new Medreps();
$products = new Products();
$orders = new Orders();
$payment = new Payments();
$reports = new Reports();
$delivery = new Delivery();

//GN PA UPDATE KO LNG ANG PREVIOUS RECORD
//$delivery->update_delivered();

if(!$users->get_session()){
  header("location: login.php");
}

$mod = (isset($_GET['mod']) && $_GET['mod'] != '') ? $_GET['mod'] : '';
$sub = (isset($_GET['sub']) && $_GET['sub'] != '') ? $_GET['sub'] : ''; 
$process = (isset($_GET['pro']) && $_GET['pro'] != '') ? $_GET['pro'] : '';


?>
<!DOCTYPE html>
<html class="style-4">
<head>
<title>Sungem Pharma - Admin</title>
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="css/style.css"/>
  <link href="css/jquery.dataTables.css" rel="stylesheet">
  <link href="css/select_box.css" rel="stylesheet">
  <link href="css/table_style.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/responsive.css" media="screen and (max-width: 1260px)">
  <script src="http://www.datejs.com/build/date.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/Chart.min.js"></script> 
  <script type="text/javascript" src="js/chart_js_newline.js"></script> 
  <script src="js/jquery.js"></script>
  <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.js"></script>
  <script src="js/jquery.dataTables.js"></script> 
  <script src="js/select_box.js"></script> 
  <script src="js/popup.js"></script> 
  <!-- Embed Roboto Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,600,700,900" rel="stylesheet">
</head>
<body class="background-material">
 <!----AJAX LOADING---->
   <!-- <div id="loading_ajax">
      <img src='img/loading.gif' width="100%" height="100%" />
    </div>
    <div id="blackloading"></div>
    <!----AJAX LOADING---->
  <div class="topnav" id="myTopnav">
    <a href="index.php"><div id="topnav-logo"><img src="img/logo.PNG"/><h4 style="font-weight: 300; font-size: 14px; padding: 4px;">Sungem <strong>Pharma</strong></h4></div></a>
    <a href="javascript:void(0);" style="font-size:25px;" class="icon" onclick="myFunction()">&#9776;</a>
    <!--NEW NOTIFICATIONS-->
    <div id="toptop">
    <div class="notification-top">
      <div class="top-circle-notif">
      <?php
        if(($payment->get_count_duepayment_notif() + $payment->get_count_pdc_notif())>0) 
        echo ($payment->get_count_duepayment_notif() + $payment->get_count_pdc_notif());
      ?></div>
      <img class="top-notif" onclick="popup_show_main();" id="top_pay_notif" src="img/icons/payment.png" width="35px" height="35px"/>
    </div>
    <div class="notification-top">
    <div class="top-circle-notif">
    <?php 
      if($products->get_count_restock_notif()>0){
        echo $products->get_count_restock_notif();
      }
    
    ?></div>
      <img class="top-notif" onclick="popup_show_main();" id="top_restock_notif" src="img/icons/stocks.png" width="30px" height="30px"/>
    </div>
    <div class="notification-top">
    <div class="top-circle-notif">
    <?php 
    if(($products->get_count_expiry_notif() +  $products->get_count_expiry_consign())>0){
      echo ($products->get_count_expiry_notif() +  $products->get_count_expiry_consign());
    }
    ?></div>
      <img class="top-notif" onclick="popup_show_main();" id="top_expiry_notif" src="img/icons/notif_expiry_white.png" style="color: white;" width="30px" height="30px"/>
    </div>
    </div>
    <!---->
  </div>
  <div class="sidenav sidenav-scroll" id="mySideNav"> 
    <div class="current-user">
      <img class="profile_pic" src="<?php echo $_SESSION['image'];?>"/>
      <h4>Welcome, <?php echo $_SESSION['userdata'];?>!</h4>
    </div>
    <a href="index.php"><div class="sideitem"><img src="img/icons/dashboard.png"/>Dashboard
    </div></a>
    <?php if($_SESSION['access']==4001){ ?><a href="index.php?mod=products"><div class="sideitem"><img src="img/icons/products.png"/>Products</div></a><?php } ?>
    <a href="index.php?mod=stocks"><div class="sideitem"><img src="img/icons/stocks.png"/>Stocks</div></a>
    <a href="index.php?mod=orders"><div class="sideitem"><img src="img/icons/orders.png"/>Orders</div></a>
    <a href="index.php?mod=delivery"><div class="sideitem"><img src="img/icons/delivery.png"/>Delivery</div></a>
    <a href="index.php?mod=payment"><div class="sideitem"><img src="img/icons/payment.png"/>Payment</div></a>
    <a href="index.php?mod=reports"><div class="sideitem"><img src="img/icons/reports.png"/>Reports</div></a>
    <?php if($_SESSION['access']==4001){ ?>
    <a href="index.php?mod=medreps"><div class="sideitem"><img src="img/icons/medreps.png"/>Med Reps</div></a>
    <a href="index.php?mod=clients"><div class="sideitem"><img src="img/icons/clients.png"/>Clients</div></a>
    <a href="index.php?mod=suppliers"><div class="sideitem"><img src="img/icons/supplier.png"/>Suppliers</div></a>
    <a href="index.php?mod=users"><div class="sideitem"><img src="img/icons/users.png"/>Users</div></a><?php } ?>
    <a href="index.php?mod=account"><div class="sideitem"><img src="img/icons/account.png"/>My Account</div></a>
    <a href="logout.php"><div class="sideitem"><img src="img/icons/logout.png"/>Logout</div></a>
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


  <div class="flex-container w-100">
    <div class="adjust-container">
          <?php
  switch($mod){
    case '':
    case 'dashboard': 
      require_once("dashboard/index.php");
      break;
    case 'products': 
      require_once("products/index.php");
      break;
    case 'stocks': 
      require_once("stocks/index.php");
      break;
    case 'orders': 
      require_once("orders/index.php");
      break;
    case 'delivery': 
      require_once("delivery/index.php");
      break;
    case 'payment': 
      require_once("payment/index.php");
      break;
    case 'reports': 
      require_once("reports/index.php");
      break;
    case 'medreps': 
      require_once("medreps/index.php");
      break;
    case 'clients': 
      require_once("clients/index.php");
      break;
    case 'suppliers': 
      require_once("suppliers/index.php");
      break;
    case 'users': 
      require_once("users/index.php");
      break;
    case 'account': 
      require_once("users/account.php");
      break;
  }
?>
    </div>
  </div>
<script type="text/javascript">
$(document).ready(function(){

$(document).on("click", "#top_expiry_notif", function(){
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

  $(document).on("click", "#top_restock_notif", function(){
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

  $(document).on("click", "#top_pay_notif", function(){
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
});


function myFunction() {
    var x = document.getElementById("mySideNav");
    if (x.className === "sidenav") {
        x.className += " responsive";
    } else {
        x.className = "sidenav";
    }
}
</script>
</body>
</html>


