<?php
if($user->get_session()){
?><div id="popup-modal-edit-profile">
		<div class="modal-center-wrapper">
<!-- Popup Div Starts Here -->
<!-- Contact Us Form -->
<div id="products-popup-order">
	<div class="modal-header">
		<span onclick ="div_hide_edit_profile()" class="close">&times;</span>
		<div id="popup-profile-title">Profile Information</div>
	</div>
	<div class="modal-body">
	<?php
		$id = $_SESSION['c_userid'];
		$list = $user->get_profile_info($id);
		foreach($list as $value){
	?>
<form method="POST" id="profile_form" class="font-roboto">
<input type="hidden" name="indicator" value="1">
<input class="popup_profile" type="hidden" id="usr_id" name="usr_id" value="<?php echo $value['client_id']?>" required/>
<div class="popup-edit-title">Company Name<span class="redcol"> <strong>*</strong></span></div>	
<input class="material-input w-100" type="text" id="name" name="name" value="<?php echo $value['client_name']?>" required/>
<div class="popup-edit-title">Address<span class="redcol"> <strong>*</strong></span></div>
<input class="material-input w-100" type="text" id="address" name="address" value="<?php echo $value['address']?>" required/>
<div class="popup-edit-title">Contact Number<span class="redcol"> <strong>*</strong></span></div>
<input class="material-input w-100" type="text" id="contact" name="contact" value="<?php echo $value['contact_no']?>" required/>
<div class="popup-edit-title">Email Address</div>
<input class="material-input w-100" type="text" id="email" name="email" value="<?php echo $value['email']?>" placeholder="No Email">
	<?php
		
	}
	?>
	</div>
<div class="modal-footer">
	<a class="material-button" onclick="div_hide_edit_profile()">Cancel</a>
	<input type="button" id="submit-edit" class="material-button-main" value="Save">
		<!--<button type="submit" class="popup-qty-btn" onclick="confirm_cart()" name="submit">Save edit</button>-->
</div>
</form>
</div>
<!-- Popup Div Ends Here -->
</div>
</div>
<?php
}
?>

<script type="text/javascript">

$('#contact').on('keypress', function(e){
		  return e.metaKey || // cmd/ctrl
		    e.which <= 0 || // arrow keys
		    e.which == 8 || // delete key
		    /[0-9]/.test(String.fromCharCode(e.which)); // numbers
		});

$(document).ready(function(){
$("#submit-edit").click(function(){
	

	});
})

</script>