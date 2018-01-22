<?php
  $user->mark_read_notif($_GET['nid'],$_SESSION['clientid']);
  $retrieve = $user->get_notif_data($_GET['nid'],$_SESSION['clientid']);
  foreach($retrieve as $data);
?>
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
		  		<p class="remove-warning">Delete this message?</p>
		  	</div>
		  	<div class="modal-footer">
					<button type="submit" class="material-button close-modal" name="submit">Cancel</button>
		    	<button type="submit" class="material-button-main delete-notif-btn" id="<?php echo $_GET['nid'];?>" name="submit">Delete</button>
		  	</div>
		</div>
	<!-- Popup Div Ends Here -->
</div>

<div class="card-wrapper w-80">
  <div class="card-style w-100">
    <div class="card-header">
      <ul>
        <li class="header-back"><a href="index.php?mod=profile&view=notifications" class="card-button-back">< BACK</a></li>
        <li class="header-title"></li>
        <li><a id="<?php echo $_GET['nid'];?>" class="card-button show-delete-notif">Delete</a></li>
        <li><a id="<?php echo $_GET['nid'];?>" class="card-button mark-unread">Mark AS UNREAD</a></li>
      </ul>
    </div>
    <div class="notif-content">
      <h3 style="width: 100%; margin: 0px 0px 8px 0px;float: left;"><?php echo $data['n_title'];?></h3>
      <span style="font-size: 13px; color: rgba(0,0,0,0.6); font-weight: 400;"><?php $date = new DateTime($data['n_date_added']); echo $date->format('F j, Y');?> at <?php $date = new DateTime($data['n_time_added']); echo $date->format('H:iA');?></span>
      <hr>
      <div class="notif-body">
        <label>Subject</label></br>
        <p style="margin: 0px 10px 16px 0px;"><?php if($data['n_subject']){echo $data['n_subject'];}else{echo "No Subject";}?></p>
        <label>Message</label></br>
        <p style="margin-top: 8px; margin-left: 8px; margin-bottom: 80px;"><?php echo $data['n_message'];?></p>
      </div>
      <hr>
    </div>
  </div>
</div>
<?php

?>
<script>
$(document).ready(function(){
  $(document).on("click",".close-modal",function(e){
    $('#notif-modal').hide();
  });
  updateNotifCounter();
  function updateNotifCounter(){ 
    $( "#notif-counter" ).load(window.location.href + " #notif-counter" );
  }
  $(document).on("click",".mark-unread",function(e){
    var nid = $(this).attr("id");
    $.ajax({
      url: "profile/ajax.php",
      method: "POST",
      data:{
        "mark_unread": 1,
        "n_id": nid
      },
      success: function(data){
        window.location = "index.php?mod=profile&view=notifications";
      }
    });
  });

  $(document).on("click",".show-delete-notif",function(e){
    $('#notif-modal').show();
  });

  $(document).on("click",".delete-notif-btn",function(e){
    var nid = $(this).attr("id");
    $.ajax({
      url: "profile/ajax.php",
      method: "POST",
      data:{
        "delete_notif": 1,
        "n_id": nid
      },
      success: function(data){
        window.location = "index.php?mod=profile&view=notifications";
      }
    });
    
  });
});
</script>