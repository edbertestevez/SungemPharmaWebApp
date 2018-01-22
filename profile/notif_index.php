<?php
if(isset($_GET['nid'])){
  require_once 'profile/notif_view.php';
}else{
  require_once 'profile/notif.php';
}

?>