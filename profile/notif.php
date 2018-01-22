<!-- Popup Modal -->
<div id="notif-modal">
		<input type="hidden" id="id_remove">
		<div class="background_overlay" style="display:block"></div>
		<!-- Popup Div Starts Here -->
		<div id="products-popup-order" style="margin-top: 15%;">
			<div class="modal-header">
		    	<span class="close close-modal">&times;</span>
		    	<h2 id="addtocart-title" style="color: red;	text-transform: uppercase; font-weight: 400;" value="asd">Warning</h2>
		  	</div>
		  	<div class="modal-body">
		  		<p class="remove-warning">Clear all notifications? This action cannot be undone.</p>
		  	</div>
		  	<div class="modal-footer">
					<button type="submit" class="material-button close-modal" name="submit">Cancel</button>
		    	<button type="submit" class="material-button-main clear-all-btn" id="<?php echo $_GET['nid'];?>" name="submit">Clear</button>
		  	</div>
		</div>
	<!-- Popup Div Ends Here -->
</div>

<div class="card-wrapper w-80">
  <div class="card-style w-100" style="min-height: 80vh;">
    <div class="card-header">
      <ul>
        <li class="header-title"><h3 style="float: left;">Notifications</h3></li>
        <li><a class="card-button clear-all-modal">Clear All</a></li>
      </ul>
    <h5 style="margin-bottom: 25px; margin-top: 0;">View incoming messages from Sungem Pharma</h5>
    </div>
    <div class="card-table">
      <table class="table-hover">
        <hr style="margin: 0; padding: 0; border: 0.5px solid #e5e5e5;">
    <?php
    $list = $user->get_notifications($_SESSION['clientid']);
    if($list){
      foreach($list as $notif){?>
        <tr id="<?php echo $notif['n_id'];?>" class="select-notif <?php if($notif['n_status']==0){?>notif-unread<?php } ?>">
          <td class="w-20"><?php echo $notif['n_title'];?></td>
          <td class="w-60" style=""><?php if(strlen($notif['n_message']) > 80){echo substr($notif['n_message'], 0, 80)."..";}else{ echo $notif['n_message'];}?></td>
          <td class="ta-right w-20"><?php $date = new DateTime($notif['n_date_added']); echo $date->format('F j, Y');?></td>
        </tr>
      <?php
      }
    }else{?>
      <div class="notif-empty">
        <span>&#x2639;</span>
        <h5>your inbox is empty</h5>
        <h6>Sungem Pharma would notice you if you start ordering</h6>
      </div>
    <?php
    }
      ?>
      </table>
    </div>
  </div>
</div>
<script>
$('body').on("click", ".select-notif", function(e){
  var nid = $(this).attr("id");
  window.location = "index.php?mod=profile&view=notifications&nid="+nid;
});
$('body').on("click",".clear-all-modal",function(e){
  $('#notif-modal').show();
});
$('body').on("click",".close-modal",function(e){
  $('#notif-modal').hide();
});


$('body').on("click",".clear-all-btn",function(e){
  $.ajax({
    url: "profile/ajax.php",
    method: "POST",
    data:{
      "clear_all": 1
    },
    success: function(data){
      window.location = "index.php?mod=profile&view=notifications";
    }
  });
});
</script>