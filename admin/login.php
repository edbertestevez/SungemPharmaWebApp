<?php
include 'library/config.php';
include 'classes/class.users.php';

$users = new Users();

if(isset($_REQUEST['submit'])){
	extract($_REQUEST);
	$login = $users->check_login($userid,md5($password)); //md5($password)
	if($login){
		header('location: index.php');
	}else{
  header('location: login.php?auth=error');
	/**	echo "<script type='text/javascript'>alert('Wrong Username or Password');</script>";**/
      /**echo "<script type='text/javascript'> document.getElementById('ex').style.borderBottom = '1px solid #DEDEDE'; </script>";**/
	}
}

if (isset($_GET['message'])) {
    print '<script type="text/javascript">alert("' . $_GET['message'] . '");</script>';
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sungem Pharma Admin</title>
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class="background-material">
<div id="main">
    <div class="center-div-register">
     <div class="login-logo"></div>
        <div class="card-wrapper w-100">
            <div class="card-style">
                <h2 class="ta-center">Sungem Pharma</h2>
                <form id="login" action="" method="POST" name="login">
                    <input id="userid" name="userid" type="text" class="material-input w-100" autocomplete="off" placeholder="Username">
                    <input id="password" name="password" type="password" class="material-input w-100" autocomplete="off" placeholder="Password">
                   <?php 
                  if(isset($_GET['auth'])){?>
                    <p id="login-failed-label" style="color: red;">Username or password does not exist.</p>
                  <?php
                  }
                  ?>
                    <div class="material-button-wrapper">
                        <button type="submit" id="submit" name="submit" class="material-button-main" style="float: right;" value="Login">Login</button>
                    </div>
                </form>
             
            </div> 
        </div>
    </div>
</div>
</body>
</html>
