<?php
include '../library/config.php';
include '../classes/class.users.php';
include '../classes/products.users.php';



if(isset($_POST['indicator'])){
$users = new Users();
$name = $_POST['name'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$usr_id = $_POST['usr_id'];
$id = $_POST['usr_id'];