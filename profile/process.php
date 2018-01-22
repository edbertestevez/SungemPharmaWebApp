<?php
include '../library/config.php';
include '../classes/class.graph.php';

$graph = new Graph();

if(isset($_POST['get_max_month'])){
	$result = $graph->get_json_max_month();
  	echo json_encode($result);
}

if(isset($_POST['graph_consigned'])){
  $result = $graph->get_json_consigned($_SESSION['clientid']);
  echo json_encode($result);
}

if(isset($_POST['graph_sold'])){
  $result = $graph->get_json_sold($_SESSION['clientid']);
  echo json_encode($result);
}

?>