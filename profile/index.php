<div class="card-wrapper-sub profile-left-main-cont">
<div class="w-100">
	<div class="card-style w-100">
		<div id="products-side-navi">
			<h2>Profile</h2>
			<ul>
			<li class="dashboard-butt"><a href="index.php?mod=profile&view=dashboard">Dashboard</a></li>
			<li class="dashboard-butt"><a id="notif-counter" href="index.php?mod=profile&view=notifications">Notifications <?php 
			if($user->get_notif_count($_SESSION['clientid'])){ 
				echo "(".$user->get_notif_count($_SESSION['clientid']).")";
			}
				?></a></li>
			<li class="dashboard-butt"><a href="index.php?mod=profile&view=sli">Inventory</a></li>
			
			<li class="dashboard-butt"><a href="index.php?mod=profile&view=history">Orders</a></li>
			<li class="dashboard-butt"><a href="index.php?mod=profile&view=paylist">Payments</a></li>
			<li class="screen-size">Screen size is too small to view graphs and tables</li>
			</ul>
		</div>
	</div>
</div>
<div style="margin-top: 13px;" class="w-100">
	<div class="card-style w-100">
		<h2 class="material-header-small w-90" style="display: inline-block;">Profile Information</h2><img onclick="div_show_edit_profile()" class="profile-icon" src="img/edit.svg">
		<div id="profile-info" style="text-align: center;">
		<svg id="loading-screen" style="margin-bottom: 20px;" class="spinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
  	<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
		</div>
		
	</div>
</div>
</div>
		<?php 
		$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
		switch($view){
			case 'notifications':
				require_once 'profile/notif_index.php';
				break;
			case 'sli':
				require_once 'profile/sli_index.php';
				break;
			case 'paylist':
				require_once 'profile/paylist.php';
				break;
			case 'history':
				require_once 'profile/history.php';
				break;
			case 'dashboard':
				require_once 'profile/dashboard.php';
				break;
			default:
				require_once 'profile/dashboard.php';
				break;
			}?>

<script>
$(document).ready(function(){
	updateNotifCounter();
});

function updateNotifCounter(){ 
	$( "#notif-counter" ).load(window.location.href + " #notif-counter" );
}
</script>