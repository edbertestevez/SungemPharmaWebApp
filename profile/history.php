<?php

?>
<div class="card-wrapper profile-right-main-cont">
	<div class="card-style w-100">
    <div id="products-right-content">
    <div class="card-header">
			<ul style="width: 50%;">
				<li class="header-title"><h3 style="float: left;">Recent Orders</h3></li>
				<li>
				</li>
			</ul>
			<div class="profile-topright-details">
						<span class="material-label">Total Orders</span></br>
						<span class="balance-counter"><?php echo $user->get_total_orders($_SESSION['clientid']);?></span>
			</div>
      <div class="profile-topright-details">
						<span class="material-label">Pending Orders</span></br>
						<span class="balance-counter"><?php echo $user->get_pending_orders($_SESSION['clientid']);?></span>
      </div>
      <div class="profile-topright-details">
						<span class="material-label">Approved Orders</span></br>
						<span class="balance-counter"><?php echo $user->get_approved_orders($_SESSION['clientid']);?></span>
			</div>
			<h5 style="width: 35%; display: inline-block;margin-bottom: 20px; margin-top: 0;">A list of all your previous order transactions</h5>
		</div>
<table class="table table-striped table-hover font-roboto" id="table-history">
	<thead>
		<tr>
 			    <th class="table_one_title_profile">Order</th>
        	<th class="table_two_title_profile">Date Ordered</th>
        	<th class="table_three_title_profile">Delivery Status</th>
      	</tr>
	</thead>
	<tbody>
	<?php
	$list = $user->get_order_history($_SESSION['clientid']);
	if($list){
		foreach($list as $sli){?>
    	<tr id="<?php echo $sli['order_id'];?>" style="height: 48px;" class="product-link select-order" onclick="ohistory_show()">
       		<td class="table_one_cont_profile"><span style="font-weight: 500;"><?php echo $sli['order_id'];?></span></td>
       		<td class="table_two_cont_profile" ><?php $date = new DateTime($sli['order_date']); echo $date->format('F j, Y');?></td>
           <td class="ta-right table_three_cont_profile"><?php echo $user->convert_delivery_status($sli['status']);?></td>
        </tr>
    <?php
    	}
	}
    ?>
	</tbody>
</table>

  

    </div>
  </div>
</div>

<script>
    $('#table-history').dataTable({
      language: {searchPlaceholder: "Search" }, "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
        "aaSorting": [0,'desc']
    });

    $(document).ready(function(){
      $('body').on("click", ".select-order", function(e){
        $('#main-container').css("filter","blur(6px)");
        $("body").disableScroll();
        var row_id = $(this).attr("id");
        $('.cancel-order-btn').attr("id",row_id);
        $('.show-cancel-modal').attr("id",row_id);
        $("#loading-screen").show();
        $("#ohistory-content").hide();
        $.ajax({
            url: "profile/ajax.php",
            method: "POST",
            data:{
              "history_click": 1,
              "or_id": row_id
            },
            success: function(data){
              setTimeout(function() {
                $("#loading-screen").hide();
              $("#ohistory-content").show();
              $('#ohistory-content').html(data);
              if($('.check-delivery-value').attr("id") != "0"){
                $('.show-cancel-modal').hide();
              }else{
                $('.show-cancel-modal').show();
              }
              }, 1000);
              
            }
        });
      });
    });

</script>