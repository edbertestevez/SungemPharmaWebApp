<?php
if($user->get_session()){
	header("location: index.php");
}
?>
<div class="individual-main-container">
	<div id="main-login-left-div">
		<div id="main-login-left-div-sub-top">Why register to Sungem Pharma web portal?</div>
		<div id="main-login-left-div-sub-mid"><strong class="font-roboto">OBJECTIVES</strong>
		</div>
		<div id="main-login-left-div-sub-bot">
		- Online Ordering For Easy Convenience</br>
		- Shopping-like Experience</br>
		- Fast Transactions</br>
		- User Friendly Interface</br>
		- Great Customer Satisfaction</br></br>
		<i>You need to be an authorized client of Sungem Pharma in order to login or register.</i></br></br>
		<strong class="font-roboto">FOR MORE INQUIRIES:</strong></br>
		Visit our Contact Us page to know more about the detailss
		</div>
	</div>
	<div id="main-login-right-div">
		<div class="material-header" style="margin-bottom: 15px;">Login</div>
		<div id="main-login-right-div-sub-mid">
			<form id="form-login">
			<input id="username" name="username" type="text" class="material-input w-100" autocomplete="off" placeholder="Username" required/>

			<input id="password" name="password" type="password" class="material-input w-100" autocomplete="off" placeholder="Password" required/>
			<div id="login-error" class="login-error">Username or password does not exist.</div>
			<?php 
			if(isset($_GET['auth'])){?>
  				<p id="login-failed-label">Username or password do not match</p>
			<?php
			}
			?>
			<button class="material-button-main" style="float: right; margin-bottom: 60px;" type="submit" name="login">LOGIN</button>
			</form>
		</div>
		<div id="main-login-right-div-sub-bot" class="w-100" style="text-align: center; margin-bottom: 20px; display: inline-block">
			Don't have an account yet? <a href="index.php?mod=register">Sign up</a>
		</div>
	</div>
</div>