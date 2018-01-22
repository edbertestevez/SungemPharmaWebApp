<div class="center-div-register w-100">
	<div class="card-wrapper w-100">
		<div class="card-style w-100">
			<div id="login-form">
				<h2 class="material-header">Contact information</h2>
					<span class="required">* Required</span>
					<form name="login" onsubmit="return validateFormTwo();"  onkeyup="onkeyUpFunctionForRegister()" method="post" action="register/process.php?action=newUser">
						<h5 style="margin-top: 25px;" class="material-input-header">Company Name <span style="color: #E54043;">*</span></h5>
						<input type="text" class="w-70 material-input" autocomplete="off" id="company-name" name="Company-Name" placeholder="Your answer" required/>
						
						<h5 class="material-input-header">LTO Number <span style="color: #E54043;">*</span></h5>
						<input type="number" placeholder="Your answer" class="w-50 material-input" autocomplete="off" min="1" id="lto_number" name="LTO-Number" required/>
						
						<h5 class="material-input-header">Area <span style="color: #E54043;">*</span></h5>
						<select name="area-name" class="material-input-dd w-50" required>
							<?php
							$list = $user->get_area();
							foreach($list as $value){
							?>
								<option value="<?php echo $value['area_id'];?>">
								<?php echo $value['area_name'];?>
								</option>
							<?php
							}
							?>
						</select>
						<h5 class="material-input-header">Address <span style="color: #E54043;">*</span></h5>
						<input type="text" placeholder="Your answer" class="w-100 material-input" autocomplete="off" id="address" name="Address" required/>
						
						<h5 class="material-input-header">Contact Number <span style="color: #E54043;">*</span></h5>
						<input type="number" placeholder="Your answer" class="w-60 material-input" autocomplete="off" id="field_contact" name="Contact-Number" required/>
						
						<h5 class="material-input-header">Email Address <span style="color: #E54043;">*</span></h5>
						<input type="text" placeholder="Your answer" class="w-60 material-input" autocomplete="off" id="email" name="Email-Address"/>
						
						<h5 class="material-input-header">First Name <span style="color: #E54043;">*</span></h5>
						<input type="text" placeholder="Your answer" class="w-60 material-input" autocomplete="off" id="first-name" name="First-Name" required/>
						
						<h5 class="material-input-header">Last Name <span style="color: #E54043;">*</span></h5>
						<input type="text" placeholder="Your answer" class="w-60 material-input" autocomplete="off" id="last-name" name="Last-Name" required/>
						
						<h5 class="material-input-header">Username <span style="color: #E54043;">*</span></h5>
						<input type="text" placeholder="Your answer" class="w-60 material-input" autocomplete="off" id="field_username" name="Username" required/>
						
						<h5 class="material-input-header">Password <span style="color: #E54043;">*</span></h5>
						<input type="password" placeholder="Password" class="w-60 material-input" autocomplete="off" id="pass" name="Password" required/>
						
						<h5 class="material-input-header">Confirm Password <span style="color: #E54043;">*</span></h5>
						<input type="password" placeholder="Confirm Password" class="w-60 material-input" autocomplete="off" id="retype" name="Retype-Password" required/>
						<div style="display: inline-block" class="w-100">
							<input type="submit" style="margin-top: 70px; margin-bottom: 10px; float: right;" class="material-button-main" id="val" name="submit" value="SUBMIT"/>
							<button style="margin-top: 70px; margin-bottom: 10px; float: right;" class="material-button" onclick="window.location.href='index.php?mod=login'">CANCEL</button>
						</div>
					</form>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#lto_number').on('keypress', function(e){
		  return e.metaKey || // cmd/ctrl
		    e.which <= 0 || // arrow keys
		    e.which == 8 || // delete key
		    /[0-9]/.test(String.fromCharCode(e.which)); // numbers
		});

		$('#field_contact').on('keypress', function(e){
		  return e.metaKey || // cmd/ctrl
		    e.which <= 0 || // arrow keys
		    e.which == 8 || // delete key
		    /[0-9]/.test(String.fromCharCode(e.which)); // numbers
		});

		$('#field_username').on('keypress', function(e){
		  return e.metaKey || // cmd/ctrl
		    e.which <= 0 || // arrow keys
		    e.which == 8 || // delete key
		    /[0-9a-zA-Z]/.test(String.fromCharCode(e.which)); // numbers
		});
	});
</script>