<?php
if(isset($_POST['step1'])){
?>
	<h3 class="step-title">Choose A Product</h3>
	<p class="step-desc">Go to Products page, all items that are available will be displayed as a list. You can also filter the results by selecting on a category on the left side of the screen. You can directly click an item from the list to display a pop-up window showing detailed information about the product</p>
	<img src="img/step1_final.jpg" class="step_picture"/>
	<h6 style="padding: 0; margin: 0;" class="ta-center"><i>Figure 1 (Image above)</i></h6>
	<p class="step-desc">Enter your desired quantity for purchase, then click Add to Cart. You may proceed adding more items to your cart.</p>
	<p class="step-desc" style="margin-bottom: 30px;">Once done, click the Cart button on the upper-right corner of your screen to view your cart.</p>
<?php
}

if(isset($_POST['step2'])){
?>

	<h3 class="step-title">Proceed to Checkout</h3>
	<p class="step-desc">By clicking the items navigation with the cart logo which can be found at the top right corner of the screen, you will be directed to your cart. Where all products that you have ordered appears along with the important informations and unit's price. You can also remove a product by clicking the "x" button or by clicking the "remove all" button if you wish to remove all products you have ordered</p>
	<img src="img/step2_final.jpg" class="step_picture"/>
	<h6 style="padding: 0; margin: 0;" class="ta-center"><i>Cart List (Left), Cart Summary (Right)</i></h6>
	<p class="step-desc" style="margin-bottom: 30px;">Your cart summary is displayed at the right side your screen where you can see the total number of items you ordered along wih the subtotal payment and total payment. Selection to what type of order you are going to go to is displayed which  you can choose between Considered Sold and Consignment.</p>
	

<?php
}

if(isset($_POST['step3'])){
?>
	<h3 class="step-title">Delivery Process</h3>
	<p class="step-desc">Once your order has been confirmed, your items will be delivered as soon as possible within business days. Please do check if the products delivered has been damaged or has issues, Sungem Pharma will immediately investigate and take necessary action.</p>
	<p class="step-desc">You can check your orders and payments by going to your profile account, click on the upper-right corner of the screen. The pending payments button and order history button can be found at the top left corner of the screen.</p>
	<img src="img/step3_final.jpg" class="step_picture"/>
	<h6 style="padding: 0; margin: 0;" class="ta-center"><i>History List (Right)</i></h6>
	<p class="step-desc" style="margin-bottom: 30px;">After the products have been delivered sucessfully, payment comes in, basing on what type of payment you have chosen on checkout.</p>
<?php
}
?>