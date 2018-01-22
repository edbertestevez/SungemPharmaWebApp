<?php
$sub = (isset($_GET['sub']) && $_GET['sub'] != '') ? $_GET['sub'] : '';
switch($sub){
	case 'monitoring':
		require_once 'profile/sli_history.php';
		break;
	case 'topcon':
		require_once 'profile/sli_topcon.php';
		break;
	case 'topsold':
		require_once 'profile/sli_topsold.php';
		break;
	default:
    	require_once 'profile/sli.php';
    	break;
}
?>