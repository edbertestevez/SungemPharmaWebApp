<?php
if($user->get_session()){
?><div id="popup-modal-edit-password">
	<div class="modal-center-wrapper">
<div id="products-popup-order">
	<div class="modal-header">
		<span onclick ="div_hide_change_password()" class="close">&times;</span>
		<div id="popup-profile-title">Change Password</div>
	</div>
	<div class="modal-body">
	<?php
		$id = $_SESSION['c_userid'];
		$list = $user->get_profile_info($id);
		foreach($list as $value){
	?>
	
<form method="POST" id="change_pass_form" class="font-roboto">
<input type="hidden" name="indicator-pass" value="1">
<input class="material-input" type="hidden" id="usr_id" name="usr_id" value="<?php echo $value['client_id']?>" required/>
<input class="material-input" type="hidden" id="pass" name="pass" value="<?php echo $value['usr_password']?>" required/>
<div class="popup-edit-title">Current Password <span class="redcol"> <strong>*</strong></span></div>	
<input class="material-input w-100" type="password" id="current-pass" name="current-pass" required/>
<div class="popup-edit-title">New Password<span class="redcol"> <strong>*</strong></span></div>	
<input class="material-input w-100" type="password" id="new-pass" name="new-pass" required/>
<div class="popup-edit-title">Confirm New Password<span class="redcol"> <strong>*</strong></span></div>	
<input class="material-input w-100" type="password" id="confirm-new-pass" name="confirm-new-pass" required/>
	<?php
		
	}
	?>
	</div>
<div class="modal-footer">
	<button class="material-button" onclick="div_hide_change_password()">Cancel</button>
	<input type="button" onclick="passDoesNotMatch();" id="submit-change-pass" class="material-button-main" value="Save">
		<!--<button type="submit" class="popup-qty-btn" onclick="confirm_cart()" name="submit">Save edit</button>-->
</div>
</form>
</div>
</div>
</div>
<?php
}
?>
