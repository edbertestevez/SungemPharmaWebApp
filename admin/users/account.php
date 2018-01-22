<div class="card-wrapper">
	<div class="indicator"><img src="img/icons/users-black.png"/><?php echo "My Account &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  View Account";?></div>
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

<div class="card-wrapper" id="hide-card-wrapper">
  <div class="card-style w-50">
<div id="sub-container">
<h2 class="add-marg">Settings</h2>
<h4 class="add-marg">Account Details</h4>
  <form name="form_change" id="form_change" method="post" enctype="multipart/form-data">

  <input type="hidden" name="edit_account" value="1">
    <div class="col40">
        <h4>First Name:     *</h4>
        <input type="text" class="material-input" id="form_edit_fname" name="form_edit_fname" required placeholder="First Name" autocomplete="off" value=<?php echo $_SESSION['userfname'];?>>
    </div>
    <div class="col40">
        <h4>Last Name: *</h4>
        <input type="text" class="material-input" id="form_edit_lname" name="form_edit_lname" required placeholder="Last Name" autocomplete="off" value=<?php echo $_SESSION['userlname'];?>>
    </div>
    <h4>Change Password</h4>
    <div class="col40">
        <h4>Old Password:</h4>
        <input type="password" class="material-input" id="form_edit_currentpassword" name="form_edit_currentpassword"  autocomplete="off" />
    </div>
    <div class="col40">
        <h4>New Password: </h4>
        <input type="password" class="material-input" id="form_edit_newpassword" name="form_edit_newpassword"  autocomplete="off" />
    </div>
    <div class="col40">
        <h4>Confirm Password: </h4>
        <input type="password" class="material-input" id="form_edit_confirm" name="form_edit_confirm"  autocomplete="off" />
    </div>

    <div id="account_img" class="acc_image">
        <img src=<?php echo $_SESSION['image'];?>>
        <br/><br/>
        <b>Change Profile Photo:</b>
        <br/><br/>
        <input type="file" name="image">
    </div>

    <div class="material-button-wrapper">
      <input type="button" class="material-button-main" id="save_changes" style="" name="submit" value="Save">
    </div>

  </form>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){


    //SAVE CHANGES
     $("#save_changes").on('click',(function (e){
        var firstname = $('#form_edit_fname').val();
        var lastname = $('#form_edit_lname').val();
        var form = $('#form_change')[0];
        var initiatorNumber = 0;
        var pass = $('#form_edit_currentpassword').val();
        var passnew = $('#form_edit_newpassword').val();
        var confirmed = $('#form_edit_confirm').val();
        var counting = passnew.length;
    

        if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(firstname) && (/^[0-9a-zA-Z\-\s\,\.]+$/).test(lastname)){
            if(pass == "" && passnew =="" && confirmed == ""){
                initiatorNumber = 1;
            }else{
            if(pass != "" && passnew !="" && confirmed != ""){
                if((/^[0-9a-zA-Z\_\#\@\$\%\^\&\*]+$/).test(pass) && (/^[0-9a-zA-Z\_\#\@\$\%\^\&\*]+$/).test(passnew) && counting >= 6){
                     initiatorNumber = 1;
                 }else{
                     alert("Failed: Password should contain alphanumeric,'_','@','#','$','%','^','&','*', only and must be atleast 8 characters");     
                 }
            }else{
                alert("Failed: change password failed; pls fill up the required fields");
                initiatorNumber = 2;
            } 
            }
            
                       

        }else{
            alert("Failed: First Name and Last Name should contain alphanumeric values only");
            initiatorNumber = 2;
        }

        if(initiatorNumber == 1){
        if(confirm("Apply changes on your account settings?")){
        $.ajax({
            url: "users/process.php",
            type: "POST",
            async: false,
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false, 
            data: new FormData(form),
            success: function(data){
                if(data!=true){
                    alert(data);
                }else{
                    alert("Changes made successfully");
                    window.location.reload();
                }
            }
          })
       }
   }else{
   }


        

       
     }));

             /*where you edit*/

      setTimeout(function(){
    document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
    },1000);
});
</script>