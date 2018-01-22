<?php
	require 'library/config.php';
	unset($_SESSION['login']);
	header('location: index.php');
?>