<!-- <div id="products-side-navi">
		<h2>Cart</h2>
		<ul>
			<li><a class="products-label-type">OPTIONS</a></li>
			<hr style="margin-bottom: 10px; margin-top: 10px; width: 80%; margin-right: 20%; float: left; background-color: black;">
			<li><a href="products/process.php?action=removeall">REMOVE ALL</a></li>
		</ul>
</div> -->
	<div id="cart-container">
	<a class="btn-continueshopping" href="index.php?mod=products&cat=all">&larr; Continue Shopping</a>
		<hr>
		<h3>Cart</h3>
		<div class="cart-wrapper w-100">
			<div class="card-style w-100" style="min-height: 300px; text-align: center;">
				<svg id="loading-screen" style="margin-top: 10%;" class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
  				<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  			</svg>
				<div id="cart-loaded">
				</div>
			</div>
		</div>
		<div id="cart-ads-wrapper">
			<div id="cart-ads-content">
				<h5>WHEN WILL I RECEIVE MY ORDERED MEDICINES?</h5>
				<p>Ordered medicines made will automatically be processed. The timeframe for the delivery of items will be based on the location of the shipping address.  This approximately takes 3-14 delivery days.</p>
			</div>
			<div id="cart-ads-content">
				<h5>WHAT PAYMENT METHODS SHOULD I USE?</h5>
				<p>To offer you a good shopping experience, Sungem Pharma accepts payments through CASH and POST-DATED CHEQUES ONLY.</p>
			</div>
			<div id="cart-ads-content">
				<h5>AM I SECURED WITH ONLINE ORDERING?</h5>
				<p>Sungem Pharma takes the utmost care of securing information provided to our website. We prioritize safety during the ordering process to be restricted to staff and ensures that employees are up-to-date with security and privacy policies.</p>
			</div>
		</div>
	</div>		<!-- ARI DI TANAN NA TABLE SANG AJAX, MR.CLEAN, DOWNY, ROBERT DOWNY IRONMAN-->


<style> hr { background-color: #e4e4e4; height: 1px; border: 0; } </style>

<script>
$(document).ready(function(){

	displayTable();

	$('body').on("click", "#submit_checkout", function(e){
      	$.ajax({
            url: "cart/ajax.php",
            method: "POST",
            data:$("#form_cart").serialize(),
            success: function(data){
							$.ajax({
								url: "cart/ajax.php",
								method: "POST",
								data:{
									"inform_client":1
								},
								success: function(data){
									alert("Order success!");
								}
							});
              	window.location = "index.php?mod=profile";
            }
          });
      		
     });
      	
	//RADIO BUTTON CLICKED EVENT (CONSIDERED SOLD)
     $('body').on("click", "#rdo_sold", function(e){
      $.ajax({
            url: "cart/ajax.php",
            method: "POST",
            data:{
              "payterm_radio": 1
            },
            success: function(data){
               $("#terms_field").html(data);
            }
          });
     });

     //RADIO BUTTON CLICKED EVENT (CONSIGNMENT==MONTHLY)
     $('body').on("click", "#rdo_consignment", function(e){
      $.ajax({
            url: "cart/ajax.php",
            method: "POST",
            data:{
              "payterm_radio_consignment": 1
            },
            success: function(data){
               $("#terms_field").html(data);
            }
          });
     });

	$("#remove_btn").click(function(){
		var id_remove = $("#id_remove").val();
		$.ajax({
			url: "cart/ajax.php",
			method: "POST",
			data:{
				"remove_cart": 1,
				"id": id_remove
			},
			success: function(data){
				updateCartCounter();
				pop_cart_hide();
				displayTable();
				
			}
		});
	});
});

function displayTable(){
	$.ajax({
			url: "cart/ajax.php",
			method: "POST",
			data:{
				"display_table": 1
			},
			success: function(data){
				setTimeout(function() {
					$("#cart-loaded").html(data);
					$("#loading-screen").css("display","none");
					$(".card-style").css("text-align","left");
				}, 0);
			}
	});
	$.ajax({
            url: "cart/ajax.php",
            method: "POST",
            data:{
              "payterm_radio": 1
            },
            success: function(data){
               $("#terms_field").html(data);
            }
          });

}

	
function updateCartCounter()
	{ 
	    $( "#cart-item-counter" ).load(window.location.href + " #cart-item-counter" );
	}
</script>