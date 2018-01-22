<?php
include '../library/config.php';
include '../classes/class.users.php';

$users = new Users();


if(isset($_POST['deactivate_user'])){
  $usr_id = $_POST['usr_id'];
  $result = $users->deactivate_user($usr_id);
  echo $result;
}

if(isset($_POST['edit_account'])){
  $fname=$_POST['form_edit_fname'];
  $lname=$_POST['form_edit_lname'];
  $cur_pass=$_POST['form_edit_currentpassword'];
  $new_pass=$_POST['form_edit_newpassword'];
  $con_pass=$_POST['form_edit_confirm'];

  $msg = "";
  if($fname!="" && $lname!=""){
    if($cur_pass!="" || $new_pass!="" || $con_pass!=""){
      if($_SESSION['current_password']!=md5($cur_pass)){
        $msg.="* Incorrect current password \n";
      }
      if($new_pass!=$con_pass){
        $msg.="* Passwords does not match \n";
      }
    }
  }else{
    $msg.="* Please fill up required fields \n";
  }

  if($msg==""){
    if(!empty($_FILES['image']['name'])){
      $temp = explode(".", $_FILES["image"]["name"]);
      $img_link = "img/users/".$_SESSION['userid'].".".end($temp);
      $target = "../img/users/".$_SESSION['userid'].".".end($temp);
      $image = $_FILES['image']['name'];
      if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $msg .= "There Was A problem uploading image \n";
      }
      $result = $users->update_user_with_image($_SESSION['userid'],$new_pass,$lname,$fname,$img_link);
      $_SESSION['image']=$img_link;
    }else{
      $result = $users->update_user($_SESSION['userid'],$new_pass,$lname,$fname);
    }
      $_SESSION['userfname']=$fname;
      $_SESSION['userlname']=$lname;
      
      $_SESSION['userdata']= $fname. " ".$lname;
      if($new_pass!=""){
        $_SESSION['current_password']=md5($new_pass);
      }
    echo $result;
  }else{
    echo $msg;
  }
}

if(isset($_POST['display_users_table'])){
	$list = $users->get_users();
        ?>
            <div class="table-title">Sungem Pharma System Users</div>
            <div id="button-container-2"><button onclick="popup_show()" id="add_product_butt">ADD USER</button></div>
                <table class="table table-responsive table-striped table-hover" id="userslist">
                  <thead>
                  <tr>
                    <th class="column_one">Username</th>
                    <th class="column_two">Last Name</th>
                    <th class="column_three">First Name</th>
                    <th class="column_four">Access</th>
                    <th class="">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach($list as $values){
                      ?>
                      <tr>
                        <td class="column_one"><?php echo $values['usr_username'];?></td>
                        <td class="column_two"> <?php echo $values['usr_lastname'];?></td>
                        <td class="column_three"><?php echo $values['usr_firstname'];?></td>
                        <td class="column_four"><?php echo $values['acc_name'];?></td>
                        <td class="max30"><input type="button" id=<?php echo $values['usr_id'];?> class="btn_action_remove" value="Deactivate"></td>
                      </tr>
                      <?php
                    }
                  ?>
                  </tbody>
                </table>
            <script>
            $('#userslist').dataTable(
                {language: {searchPlaceholder: "Search User" }, "bLengthChange" : false,
                 "pageLength":10}
            );
            </script>
<?php
}

if(isset($_POST['add_user'])){
	$lastname = ucwords($_POST['lastname']);
	$firstname = ucwords($_POST['firstname']);
	$access = $_POST['access'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	$result = $users->new_user($username,$password,$access,$lastname,$firstname);
	echo $result;	
}

if(isset($_POST['update_user'])){
	$id = $_POST['row_id'];
	$lastname = ucwords($_POST['lastname']);
	$firstname = ucwords($_POST['firstname']);
	$access = $_POST['access'];
	$password = $_POST['password'];

	$result = $users->update_user($id,$password,$access,$lastname,$firstname);
	echo $result;	
}

if(isset($_POST['get_specific_row'])){
	$id = $_POST['row_id'];
	$result = $users->get_specific_user($id);
	echo json_encode($result);
}