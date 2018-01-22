<?php
	require 'library/config.php';
	unset($_SESSION['c_login']);
	//session_destroy();
	header('location: index.php?mod=login');