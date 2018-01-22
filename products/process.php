<?php
include '../library/config.php';
include '../classes/class.products.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
	case 'addtocart':
		process_add_to_cart();
		break;
	case 'remove':
		process_remove_from_cart();
		break;
	case 'removeall':
		process_remove_all_cart();
		break;
}

function process_add_to_cart(){
	$product = new Products();
	$product->insert_to_cart($_GET['id'],$_GET['qty'],$_SESSION['c_userid'],$_GET['subtotal']);
	header("location: ../index.php?mod=products");
	exit;
}

function process_remove_from_cart(){
	$cid = $_GET['id'];
	$product = new Products();
	$product->remove_from_cart($cid);
	header("location: ../index.php?mod=viewcart");
	exit;
}

function process_remove_all_cart(){
	$product = new Products();
	$product->remove_all_cart($_SESSION['c_userid']);
	header("location: ../index.php?mod=viewcart");
	exit;
}


?>