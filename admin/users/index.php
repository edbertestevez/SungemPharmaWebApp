<?php
  $p_indicator = "Users &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Users List";
?>
<div class="card-wrapper">
	<div class="indicator"><img src="img/icons/users-black.png"/><?php echo $p_indicator;?></div>
</div>

<!----------------POP UP FORM NI SYA PRE-------------------------->
<div id="back-black" onclick="popup_hide()"></div>
<div class="pop-container" id="pop-container">
        <div id="popup-form" style="width: 450px;">
        <form id="addform" method="post" name="form">   
        <h4 id="close" onclick ="popup_hide()">&times;</h4>
        <h3>New System User</h3>
        <hr>
        <h6>Access Type</h6><select class="material-input-dd" id="access" name="access">
            <?php
            $listaccess = $users->get_accesslist();
            if($listaccess){
                foreach ($listaccess as $valueaccess) {
                    ?>
                    <option value=<?php echo $valueaccess['acc_id'];?>><?php echo $valueaccess['acc_name'];?></option>
                    <?php
                }
            }
            ?>
        </select>
        <h6>Last Name</h6><input class="form-row" id="lastname" name="lastname" placeholder="Last Name" type="text" required>
        <h6>First Name</h6><input class="form-row" id="firstname" name="firstname" placeholder="First Name" type="text" required>
        <h6>Username</h6><input class="form-row" id="username" name="username" placeholder="Username" type="text" required>
        <h6>Password</h6><input class="form-row" id="pass1" name="password" placeholder="Password" type="password" required>
        <h6>Confirm Password</h6><input class="form-row" id="pass2" name="confirm" placeholder="Confirm Password" onkeyup="checkPass(); return false;" type="password" required>
        <div class="material-button-wrapper">
            <input type="button" class="material-button-main float-right" id="submit_add" value="Save"/>
            <input type="button" class="material-button float-right" id="submit_close" onclick="popup_hide()" value="Close"/>
        </div>
        </form>
        </div>
</div>


  <div id="loading-screen-2">
  <div class="card-wrapper" style="">
  <div class="card-style" style="height: 30vh; padding-top: 10%;">
  <svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
  </div>
  </div>
  </div>

<div class="card-wrapper-notop" id="hide-card-wrapper">
    <div class="card-style">
        <div id="sub-container"></div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    displayUsersTable();

     $(document).on("click", ".btn_action_remove", function(){
        var usr_id = $(this).attr("id");
        if(confirm("Deactivate User Account?")){
            $.ajax({
            url: "users/process.php",
            type: "POST",
            async: false,
            data: {
              "deactivate_user": 1,
              "usr_id": usr_id
            },
            success: function(data){
                if(data==1){
                    alert("User account has been deactivated");
                    displayUsersTable();
                }else{
                    alert("There was a problem deactivating the account");
                }
            }
          });
        }
    });

    //ADD FUNCTION
    $("#submit_add").click(function(){
      var firstname = $("#firstname").val();
      var lastname = $("#lastname").val();
      var username = $("#username").val();
      var password = $("#pass1").val();
      var confirm = $("#pass2").val();
      var access = $("#access").val();
      var count_pass = password.length;

      if(firstname!=null && firstname!="" && lastname!=null && lastname!="" && username!=null && username!="" && password!=null && password!="" && confirm!=null && confirm!="" && access!=null && access!=""){

        if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(firstname)){
     
          if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(lastname)){
            if(((/^[0-9a-zA-Z\_]+$/).test(username))){
                if((count_pass >= 6) && ((/^[0-9a-zA-Z\_\#\@\$\%\^\&\*]+$/).test(password)) && ((/^[0-9a-zA-Z\_\#\@\$\%\^\&\*]+$/).test(confirm))){
                               
                            if(password==confirm){
          $.ajax({
            url: "users/process.php",
            type: "POST",
            async: false,
            data: {
              "add_user": 1,
              "firstname": firstname,
              "lastname": lastname,
              "username": username,
              "password": password,
              "access": access
            },
            success: function(data){
              if(data=="unable"){
                alert("Username already exists. Please try again.");
              }else{
                alert("User successfully added");
                popup_hide();
                displayUsersTable();
                document.getElementById("addform").reset();
                }
            }
          });
        
        }else{
            alert("Passwords does not match");
        }

                         }else{
                            alert("Failed: Password should contain alphanumeric,'_','@','#','$','%','^','&','*', only and must be atleast 6 characters");
                             
                            }
             }else{
                 alert("Failed: Username shoud only contain '_', letters, and numbers");
             }


          }else{
            alert("Failed: lastname must only contain alphanumeric values");
          }
       
      }else{
        alert("Failed: firstname must only contain alphanumeric values");
      }



        
        }else{
            alert("Please fill up all fields");
        }
    });
     /*where you edit*/

    setTimeout(function(){
      document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
     },1000);
});
function displayUsersTable(){          
    $.ajax({
    url: "users/process.php",
    type: "POST",
    async: false,
    data: {
        "display_users_table": 1,
    },
    success: function(data){
        $("#sub-container").html(data);
    }
    });
}

function checkPass()
{
    var pass1 = document.getElementById('pass1');
    var pass2 = document.getElementById('pass2');
    var message = document.getElementById('confirmMessage');
    var button = document.getElementById('submit_add');
    var goodColor = "#fff";
    var badColor = "#e04e4e";
    if(pass1.value == pass2.value){
        pass2.style.backgroundColor = goodColor;
        button.disabled=false;
        message.style.color = goodColor;
        message.innerHTML = "Passwords Match!"
    }else{
        pass2.style.backgroundColor = badColor;
        message.style.color = badColor;
        document.getElementById('submit_add').disabled=true;
        message.innerHTML = "Passwords Do Not Match!"
    }
}  
</script>
