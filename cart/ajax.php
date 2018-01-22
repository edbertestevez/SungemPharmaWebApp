<?php
include '../library/config.php';
include '../classes/class.products.php';
include '../classes/class.users.php';

$product = new Products();
$user = new Users();

if(isset($_POST['inform_client'])){
	$user->receive_order_notif($_SESSION['clientid']);
}

if(isset($_POST['submit_checkout'])){
	$type = $_POST['type'];
	$terms = $_POST['terms'];
	$total = $_POST['total'];

	$order_id = $product->create_order_record($_SESSION['clientid'],$terms,$type,$_SESSION['c_userid']);
	
	$list = $product->get_cart($_SESSION['clientid']);

	if($list){
		foreach($list as $val){
			$product->insert_orderitem($order_id,$val['pro_id'],$val['c_qty'],$val['c_subtotal'],$_SESSION['c_userid']);
		}
		$sum = $product->get_order_sum($order_id);
		$product->update_order_amount($order_id,$sum);
		$product->remove_all_cart($_SESSION['c_userid']);
	}
}

if(isset($_POST['remove_cart'])){
	$cid = $_POST['id'];
	$result = $product->remove_from_cart($cid);
	echo $result; 
}
//MAG SELECT KA RADIO BUTTON MA CHANGE ANG CONTENT KA TERMS OF PAYMENT
if(isset($_POST['payterm_radio'])){
  $terms_list = $product->get_terms();
    if($terms_list){
 ?>
  <select class="terms-select rows-text height-wide" placeholder="Select Terms of Payment" name="terms" id="terms" style="width: 100%;">
   <?php
    foreach($terms_list as $terms_row){
  ?>
   <option value=<?php echo $terms_row['term_id'];?>><?php echo $terms_row['term_name'];?></option>
  <?php
  }
  ?>
  </select>
   <?php
  } 
}
if(isset($_POST['payterm_radio_consignment'])){
  $terms_list = $product->get_terms_consigned();
    if($terms_list){
 ?>
  <select class="terms-select rows-text height-wide" placeholder="Select Terms of Payment" name="terms" id="terms" style="width: 100%;">
   <?php
    foreach($terms_list as $terms_row){
  ?>
   <option value=<?php echo $terms_row['term_id'];?>><?php echo $terms_row['term_name'];?></option>
  <?php
  }
  ?>
  </select> 
   <?php
  } 
}
if(isset($_POST['display_table'])){
	if($user->get_session()){?>

	<?php
		$list = $product->get_cart($_SESSION['clientid']);

		if($list){
	?>

			<div id="cart-content">
				<table id="cart-table">
					<tr class="cart-head">
				    	<th class="cart-th-itemdetails">Item Details</th>
				    	<th class="cart-th-packaging">Packaging</th>
				    	<th class="ta-center">Unit Price</th>
						<th class="ta-right">Quantity</th>
						<th class="ta-right product-link" style="color: red;" onclick="pop_cart_removeall_show()">Remove All</th>
					</tr>
					<?php
					foreach($list as $values){
						$price = $product->get_price($values['pro_id']);
						$stocks = $values['c_qty'];
					?>
						<tr class="cart-tr">
							<td style="margin: 10px 0px 10px 0px;"><?php echo $product->get_product_brand($values['pro_id']);?> <?php echo $product->get_product_generic($values['pro_id']);?>
							<td><?php echo $product->get_product_packaging($values['pro_id']);?></td>
							<td class="ta-center bold">PHP <?php echo number_format ((float)$price, 2, '.', ',');?></td>
							<td class="ta-right"><?php echo $stocks;?></td>
							<td class=""><a class="x-btn-cart" id=<?php echo $values['c_id'];?> onclick="pop_cart_show(<?php echo $values['c_id'];?>)">&times;</a></td>
							<!-- href="products/process.php?action=remove&id=<?php echo $values['c_id'];?>" -->
						</tr>
					<?php
					}
					?>

				</table>
				
				<div id="cart-summary">
					<h5>Cart Summary</h5>
					<hr>
					<table id="cart-summary-table">
						<tr>
							<td>Total Items: </td>
							<td class="ta-right"><?php echo $product->count_qty_cart($_SESSION['c_userid']);?> items</td>
						</tr>	
						<tr>
							<td>Subtotal</td>
							<td class="ta-right">PHP <?php $sub = $product->get_cart_total($_SESSION['c_userid']); echo number_format ((float)$sub, 2, '.', ',');?></td>
						</tr>
					</table>
					<hr>
					
					<form id="form_cart" method="post">	            
			        <input type="hidden" name="submit_checkout" value="1">

					<table id="cart-summary-total">
						<tr>
							<td class="bold fs-16">Total</td>
							<td class="bold fs-16 ta-right"><input type="hidden" name="total" value="<?php echo $product->get_cart_total($_SESSION['c_userid']);?>">PHP <?php echo number_format ((float)$product->get_cart_total($_SESSION['c_userid']), 2, '.', ',');?></td>
						</tr>
					</table>
					<?php
		        	$type_list = $product->get_ordtype();
			        if($type_list){
			            ?>
			            <h6>Type of Order</h6>
			            
			            <div class="rows-select" style="margin-bottom: 20px;">
			            <?php
			                $ctr=0;
			                foreach($type_list AS $type_row){
			                if($ctr==0){
			            ?>  
			                <input type="radio" id="rdo_sold" name="type" value=<?php echo $type_row['ordtype_id'];?> checked><?php echo $type_row['ordtype_name'];?><br>
			            <?php
			            }else{
			            ?>
			                <input type="radio" id="rdo_consignment" name="type" value=<?php echo $type_row['ordtype_id'];?>><?php echo $type_row['ordtype_name'];?><br>
			            <?php
			            }
			            $ctr++;
			            
			            }
			        }
			        ?>

			        <h6 id="payment_terms">Terms of Payment</h6>
			        <div class="rows-select1" id="terms_field">
							<?php
							$terms_list = $product->get_terms();
							if($terms_list){
								?>
									<select class="terms-select rows-text height-wide" placeholder="Select Terms of Payment" name="terms" id="terms" style="width: 100%;">
									<?php
										foreach($terms_list as $terms_row){
									?>
									<option value=<?php echo $terms_row['term_id'];?>><?php echo $terms_row['term_name'];?></option>
									<?php
									}
									?>
									</select>
									<?php
									} 
									?>	
							<!----AJAX CALL------></div>
			        
			        </form>
					
					<a id="submit_checkout" class="button-checkout">PROCEED CHECKOUT</a>
				</div>
			</div>

	<?php
		}else{?>
		<div class="card-wrapper w-100">
				<div id="cart-empty">
					<h4>Your cart is empty.</h4>
				</div>
		</div>
	<?php
		}
	}else{?>
		<div id="cart-empty">
			<h4>You must be logged in to view your cart.</h4>
			<a href="index.php?mod=login">Go to login page</a>
		</div>
	<?php
	}
}
?>