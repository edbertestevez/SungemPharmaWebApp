<?php
include 'library/config.php';
include 'classes/class.graph.php';
include 'classes/class.products.php';
include 'classes/class.payments.php';

$products = new Products();
$payments = new Payments();

if(isset($_POST['display_notif_count'])){
	$due = $payments->get_count_duepayment_notif();
	$pdc = $payments->get_count_pdc_notif();
	$restock = $products->get_count_restock_notif();
	$expiry = $products->get_count_expiry_notif();
	$expiry_consign = $products->get_count_expiry_consign();

	echo $due+$restock+$expiry+$expiry_consign+$pdc;
}

?>