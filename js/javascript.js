//Function To Display Popup
var pro_id = 0;
var pro_qty = 0;
var c_id = 0;
var pro_price = 0.00;
var pro_formulation = "";
var pro_brand = "";
var subtotal = 0;
var pro_packaging = "";

$("val").bind("click", validateFormTwo);

function validateInputs(email_add){ 
var ex = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/;
return ex.test(email_add);
}

function validateLogin(){
var password_login = $("#pass_login").val();
var user_login = $("#user_login").val();
var value_login = 0;

if(((/^[0-9a-zA-Z\_]+$/).test(user_login))){
	if(((/^[0-9a-zA-Z\_\#\@\$\%\^\&\*]+$/).test(pass))){
		value = 2;
	}else{
		alert("you have typed prohibited characters");
		value = 1;
	}
}else{
	alert("you have typed prohibited characters");
	value = 1;
}

if(value_login == 1){
	return false;
}else{
	return true;
}

}


function validateFormTwo(){
var email_add = $("#email").val();
var pass = $("#pass").val();
var cout_pass = pass.length;
var retype = $("#retype").val();
var value = 0;
var lto_number = $("#lto_number").val();
var company_name = $("#company-name").val();
var address = $("#address").val(); 
var first_name = $("#first-name").val();
var last_name = $("#last-name").val();
var username = $("#username").val();


if((validateInputs(email_add))){
	document.getElementById('email').style.borderBottom = "1px solid #DEDEDE";
	if(pass==retype){
			if((/^[0-9a-zA-Z\-\s]+$/).test(company_name)){
				if((/^[0-9a-zA-Z\-\,\.\s\-\#]+$/).test(address)){
					if(((/^[0-9a-zA-Z\.\s]+$/).test(first_name)) && ((/^[0-9a-zA-Z\s\.]+$/).test(last_name))){
						if(((/^[0-9a-zA-Z\_]+$/).test(username))){
							if((cout_pass >= 6) && ((/^[0-9a-zA-Z\_\#\@\$\%\^\&\*]+$/).test(pass)) && ((/^[0-9a-zA-Z\_\#\@\$\%\^\&\*]+$/).test(retype))){
								alert("Registration Successful! We will notify you through your contact number if your account has been validated. Thank you!");
								value = 2;
							}else{
								document.getElementById('pass').style.borderBottom = "1px solid #DEDEDE";
								alert("Failed: Password should contain alphanumeric,'_','@','#','$','%','^','&','*', only and must be atleast 6 characters");
								value = 1;
							}
						}else{
							alert("Failed: Username shoud only contain '_', letters, and numbers");
							value = 1;
						}
					}else{
						alert("Failed: First Name and Last Name should contain alphanumeric values only");
						value = 1;
					}
				}else{
					alert("Failed: Address should contain alphanumeric values only");
					value = 1;
				}
			}else{
				alert("Failed: Company name should contain alphanumeric values only");
				value = 1;
			}
		
	}else{
		alert("Failed: Passwords do not match");
		value = 1;
	}
}else{
	document.getElementById('email').style.borderBottom = "1px solid #E54043";
	alert("Please enter a valid email address only");
	value = 1;


}

if(value == 1){
	return false;
}else{
	return true;
}
}


function onkeyUpFunctionForRegister(){
var email_add = $("#email").val();
var pass = $("#pass").val();
var cout_pass = pass.length;
var retype = $("#retype").val();
var value = 0;
var lto_number = $("#lto_number").val();
var company_name = $("#company-name").val();
var address = $("#address").val(); 
var first_name = $("#first-name").val();
var last_name = $("#last-name").val();
var username = $("#username").val();

if(pass == retype){
	document.getElementById('pass').style.borderBottom = "1px solid #DEDEDE";
	document.getElementById('retype').style.borderBottom = "1px solid #DEDEDE";
}else{
	document.getElementById('pass').style.borderBottom = "1px solid #E54043";
	document.getElementById('retype').style.borderBottom = "1px solid #E54043";
}

if((validateInputs(email_add))){
	document.getElementById('email').style.borderBottom = "1px solid #DEDEDE";
}else{
	document.getElementById('email').style.borderBottom = "1px solid #E54043";
}
}
$(document).ready(function(){
$("#hidden-menu").hide();
$("#cross").hide();
$("#burgers").click(function(){
	$("#hidden-menu").slideToggle("slow", function(){
		$("#burgers").hide();
		$("#cross").show();
	});
});

$("#cross").click(function(){
	$("#hidden-menu").slideToggle("slow", function(){
		$("#burgers").show();
		$("#cross").hide();
	});
});


$("#hiddenmenu3").hide();
$("#burgersmenu2").click(function(){
		$("#crossmenu2").show();
	$("#hiddenmenu3").slideToggle("slow", function(){
	
		
	});
		
});

$("#crossmenu2").click(function(){
		$("#burgersmenu2").show();
	$("#hiddenmenu3").slideToggle("slow", function(){
		
	});
});




})

$(document).ready(function(){
	$("#submit-change-pass").click(function(){

	

		if(($("#current-pass").val()=="") || ($("#new-pass").val()=="")|| ($("#confirm-new-pass").val()=="")){
			alert("Please Fill out required fields");
		}else{

			if((/^[0-9a-zA-Z\_\#\@\$\%\^\&\*]+$/).test($("#new-pass").val()) && $("#new-pass").val().length >=6){

				if($("#confirm-new-pass").val() == $("#new-pass").val()){
				
			$.ajax({
				url: "profile/ajax.php",
				type: "POST",
				async: false,
				data:$("#change_pass_form").serialize(),
				success: function(data){
					if(data == "wrong"){
						alert("Failed: Current password is incorrect");
					}else{
						alert("Password has been changed successfully");
						div_hide_change_password();
						$('#current-pass').val('');
						$('#new-pass').val('');
						$('#confirm-new-pass').val('');
					}
				}
			});
			}else{
				alert("Failed: Passwords do not match");
			}
			}else{
				alert("Failed: Password should contain alphanumeric,'_','@','#','$','%','^','&','*', only and must be atleast 6 characters");
			}

			
		}
	});
});

$(document).ready(function(){
displayFromDatabase();
$("#submit-edit").click(function(){
	//alert("HAHA");
	var use_id = $("#usr_id").val();
	var name = $("#name").val();
	var address = $("#address").val();
	var email = $("#email").val();

	if(($("#name").val()=="") || ($("#address").val()=="") || ($("#contact").val()==""))
	{
		alert("Please Fill out required fields");
	}
	else{
	if((/^[0-9a-zA-Z\-\s\.]+$/).test(name)){
		if((/^[0-9a-zA-Z\-\,\.\s\-\#]+$/).test(address)){
			if((/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/).test(email)){

					$.ajax({
			url: "profile/ajax.php",
			type: "POST",
			async: false,
			data:$("#profile_form").serialize(),
			success: function(data){
				displayFromDatabase();
				alert("Saved changes successfully!");
				div_hide_edit_profile();
			}
		});

			}else{
				alert("Please enter a valid email address only");
			}

		}else{
			alert("Failed: Address should contain alphanumeric values only");
		}
	}else{
		alert("Failed: Company name should contain alphanumeric values only");
	}


		
	}
});
});

function displayFromDatabase(){
$.ajax({
	url: "profile/ajax.php",
	type: "POST",
	async: false,
	data: $("#profile_form").serialize(),
	success: function(data){
		setTimeout(function() {
			$("#profile-info").css("text-align","left");
			$("#profile-info").html(data);
		}, 0);
		
	}
});
}
$.fn.disableScroll = function() {
	window.oldScrollPos = $(window).scrollTop();

	$(window).on('scroll.scrolldisabler',function ( event ) {
		 $(window).scrollTop( window.oldScrollPos );
		 event.preventDefault();
	});
};
$.fn.enableScroll = function() {
	$(window).off('scroll.scrolldisabler');
};

function div_show(){
	document.getElementById('popup-modal').style.display = "table";
	document.getElementById('main').style.overflow = "none";
	$('#main-container').css("filter","blur(5px)");
	$("body").disableScroll();
}
function ohistory_show(){
	document.getElementById('ohistory-modal').style.display = "table";
}
function ohistory_hide(){
	document.getElementById('ohistory-modal').style.display = "none";
	$('#main-container').css("filter","blur(0px)");
	$("body").enableScroll();
	$('.show-cancel-modal').hide();
}

function pop_cancel_order_hide(){
	$('#cancel-order-modal').hide();
}
function div_show_change_password(){
	document.getElementById('popup-modal-edit-password').style.display = "table";
	$('#main-container').css("filter","blur(5px)");
}
function div_hide_change_password(){
	document.getElementById('popup-modal-edit-password').style.display = "none";
	$('#main-container').css("filter","blur(0px)");
}
function div_show_edit_profile(){
	$('#popup-modal-edit-profile').css("display","table");
	$('#main-container').css("filter","blur(5px)");
}
function div_hide_edit_profile(){
	$('#popup-modal-edit-profile').css("display","none");
	$('#main-container').css("filter","blur(0px)");
}
//Function to Hide Popup
function div_hide(){
	$('#main-container').css("filter","blur(0px)");
	$("body").enableScroll();
document.getElementById('popup-modal').style.display = "none";
document.getElementById('popup-modal-2').style.display = "none";
document.getElementById('addtocart-qty').value = "1";

	
//document.getElementById('addtocart-qty').value = "1";
}
function pop_cart_show(cid){
c_id = cid;
document.getElementById('cart-modal').style.display = "table";
document.getElementById('id_remove').value=c_id;
$('#main-container').css("filter","blur(5px)");
}
function pop_cart_hide(){
document.getElementById('cart-modal').style.display = "none";
$('#main-container').css("filter","blur(0px)");
}
function pop_cart_removeall_show(){
document.getElementById('cart-removeall-modal').style.display = "table";
$('#main-container').css("filter","blur(5px)");
}
function pop_cart_removeall_hide(){
document.getElementById('cart-removeall-modal').style.display = "none";
$('#main-container').css("filter","blur(0px)");
}
function pop_cart_removeall_confirm(){
window.location = "cart/process.php?action=removeall";
}
function pop_remove_confirm_cart(){
window.location = "products/process.php?action=remove&id=" + c_id;
}
/*
function confirm_cart(){
var qty = document.getElementById('addtocart-qty').value;
if(qty != 0){
if(pro_qty >= qty){
	subtotal = qty * pro_price;
	window.location = "products/process.php?action=addtocart&id=" + pro_id + "&qty=" + qty + "&subtotal=" + subtotal;
	document.getElementById('popup-modal').style.display = "none";
}else{
	alert("Insufficient Stocks.");
}
}else{
alert("Input non-zero numeric values only");
}

}*/
function login_alert(){
document.getElementById('popup-modal').style.display = "inline-block";
}
function login_alert_OK(){
	$('#main-container').css("filter","blur(0px)");
document.getElementById('popup-modal').style.display = "none";
$("body").enableScroll();
}
function login_error(){
alert("error!!!");
}
function dbdb(){
alert("asdasdasd");
}