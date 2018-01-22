<?php
include '../library/config.php';
include '../classes/class.products.php';
include "../classes/class.users.php";

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
	case 'removeall':
		process_remove_all_cart();
		break;
	case 'create':
		process_create_order();
		break;
}

function process_remove_all_cart(){
	$product = new Products();
	$product->remove_all_cart($_SESSION['c_userid']);
	header("location: ../index.php?mod=viewcart");
	exit;
}

?>