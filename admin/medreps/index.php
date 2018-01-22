<?php
  switch($sub){
    case 'list': 
        $p_indicator = "Medical Representatives &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Medical Representatives List";
        break; 
    case 'add': 
    	$p_indicator = "Medical Representatives &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Add Medical Representative";
    	break;
    case 'performance': 
        $p_indicator = "Medical Representatives &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Monthly Performance";
        break;
    case 'assignments': 
        $p_indicator = "Medical Representatives &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Manage Assignments";
        break;

    default: $p_indicator = "Medical Representatives";
  }
?>
<div class="exhidden-menu">
   <button class="crossmenu">&#735;</button>
   <a href="index.php?mod=medreps&sub=list"><div class="subitem hidden-sub-menu">Medical Representatives List</div></a>
  <a href="index.php?mod=medreps&sub=performance"> <div class="subitem hidden-sub-menu">Monthly Performance</div></a>
  <a href="index.php?mod=medreps&sub=assignments"> <div class="subitem hidden-sub-menu">Manage Assignments</div></a>
 </div>

<div class="card-wrapper">
  <div class="card-style-subnavi">
    <div class="sub-navi">
     <div class="hidden-menu"><button class="burgermenu">&#9776;</button></div>
      <div class="subitem menu-subs"><a href="index.php?mod=medreps&sub=list">Medical Representatives List</a></div>
    	<!--<div class="subitem" id="popup" onclick="popup_show()"><a>Add Med Rep</a></div>-->
    	<div class="subitem menu-subs"><a href="index.php?mod=medreps&sub=performance">Monthly Performance</a></div>
      <div class="subitem menu-subs"><a href="index.php?mod=medreps&sub=assignments">Manage Assignments</a></div>
    </div>
  </div>
	<div class="indicator"><img src="img/icons/medreps-black.png"/><?php echo $p_indicator;?></div>
</div>


<!----------------POP UP FORM NI SYA PRE KA MEDREP LIST-------------------------->
  <div id="back-black" onclick="popup_hide()"></div>
  <div class="pop-container" id="pop-container">
        <div id="popup-form" style="width: 450px;">
        <form action="medreps/process.php?action=add" id="addform" method="post" name="form">   
        <h4 id="close" onclick ="popup_hide()">&times;</h4>
        <h3>New Medical Representative</h3>
        <hr>
        <input class="form-row" id="firstname" name="firstname" placeholder="First Name" type="text" required>
        <input class="form-row" id="middlename" name="middlename" placeholder="Middle Name" type="text" required>
        <input class="form-row" id="lastname" name="lastname" placeholder="Last Name" type="text" required>
        <textarea class="form-textarea" id="address" name="address" form="addform" placeholder="Address" /></textarea>
        <input class="form-row" id="contact" name="contact" placeholder="Contact No." type="number" required>
        <input class="form-row" id="email" name="email" placeholder="Email" type="text" required>
        <br/><br/><br/>
        <h6>Mobile App Account</h6>
        <input class="form-row" id="username" name="username" placeholder="Username" type="text" required>
        <input class="form-row" id="pass" name="pass" placeholder="Password" type="password" required>
        <input class="form-row" id="conpass" name="conpass" placeholder="Confirm Password" type="password" required>
        <div class="material-button-wrapper">  
          <input type="button" class="material-button-main float-right" id="submit_add" value="Save"/>
          <input type="button" class="material-button float-right" onclick="popup_hide()" value="Close"/>
        </div>
        </form>
        </div>
  </div>

<!----------------EDIT NGA POP UP FORM NI SYA PRE KA MEDREP LIST-------------------------->
  <div id="back-black" onclick="edit_popup_hide()"></div>
  <div class="pop-container" id="edit-pop-container">
        <div id="popup-form" style="width: 450px;">
        <form id="editform" method="post" name="form">  

        <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
        <div class="hide-data-class">  
        <h4 id="close" onclick ="edit_popup_hide()">&times;</h4>
        <h3>Medical Representative Information</h3>
        <hr>
        <input type="hidden" id="edit_id">
        <h6>First Name</h6><input class="form-row" id="edit_firstname" name="firstname" placeholder="First Name" type="text" required>
        <h6>Middle Name</h6><input class="form-row" id="edit_middlename" name="middlename" placeholder="Middle Name" type="text" required>
        <h6>Last Name</h6><input class="form-row" id="edit_lastname" name="lastname" placeholder="Last Name" type="text" required>
        <h6>Address</h6><textarea class="form-textarea" id="edit_address" name="address" form="addform" placeholder="Address" /></textarea>
        <h6>Contact No.</h6><input class="form-row" id="edit_contact" name="contact" placeholder="Contact No." type="number" required>
        <h6>Email</h6><input class="form-row" id="edit_email" name="email" placeholder="Email" type="text" required>
        <div class="material-button-wrapper">  
          <input type="button" class="material-button-main float-right" id="update_row" value="Update">
            <input type="button" id="actID" class="activator-button material-button float-right" value="">

          <!--<input type="button" class="material-button float-right" id="remove_row" value="Remove">-->
        </div>
        </div>
        </form>
        </div>
  </div>

  <!----------------MANAGE ASSSIGNMENT NGA POP UP FORM NI SYA PRE-------------------------->
  <div id="back-black" onclick="edit_popup_hide_two()"></div>
  <div class="pop-container" id="edit-pop-container-two">
        <div id="popup-form" style="width: 450px;">
        <form id="manageform" method="post" name="form">   
         <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div class="hide-data-class"> 
        <h4 id="close" onclick ="edit_popup_hide_two()">&times;</h4>
        <h3>Manage Assignment</h3>
        <hr>
        <input type="hidden" id="manage_id">
        <div id="assignment_info">
          <!------------CONTAINS INFO OF CLIENT AND ASSIGNMENT---------->
        </div>
        <hr>
        <br/>
        <h6>New Assigned Medical Representative</h6>
        <?php 
          $medreplist=$medreps->get_medreps();
          if($medreplist){
        ?>
        <select id="new_assign" class="material-input-dd" name="assign_select">
          <?php foreach($medreplist as $mr ){?>
          <option value=<?php echo $mr['medrep_id'];?>><?php echo $mr['mr_firstname']. " ".$mr['mr_middlename']." ".$mr['mr_lastname'];?></option>
          <?php } ?>
        </select>
        <?php
          }
        ?>
        <div class="material-button-wrapper">  
          <input type="button" class="material-button-main float-right" id="update_assign" value="Save">
          <input type="button" class="material-button float-right" onclick="edit_popup_hide_two()" value="Close">
        </div>
        </div>
        </form>
        </div>
  </div>

<!---------TABLE RECORD IS FOUND ON PROCESS.PHP FOR AUTOMATIC UPDATE. REFER ON AJAX BELOW------------>
<?php
if(isset($_GET['sub'])){
?>

 <!--<div id="loading-screen-2">
  <div class="card-wrapper" style="height: 100%;">
  <div class="card-style" style="height: 100%;">
  <img src="img/loading-icon.gif" alt="Loading" style="width:75px;height:75px;margin-top: 17%;">
  </div>
  </div>
  </div>-->

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
<?php
}
?> 
<script type="text/javascript">
  $(document).ready(function(){

    $(".exhidden-menu").hide();
$(".burgermenu").click(function(){
  $(".exhidden-menu").slideToggle("slow", function(){
  
  });
});

$(".crossmenu").click(function(){
  $(".exhidden-menu").slideToggle("slow", function(){
   
  });
});

    displayCurrentPage();

    $(document).on("click", ".activator-button", function(){
        var medrep_id = $(this).attr("id");
        var name = $(".activator-button").val();
        

        if(name == "deactivate"){
          if(confirm("Deactivate Medrep Account?")){
            $.ajax({
            url: "medreps/process.php",
            type: "POST",
            async: false,
            data: {
              "deactivate_medrep": 1,
              "medrep_id": medrep_id
            },
            success: function(data){
                if(data==1){
                    alert("Medrep account has been deactivated");
                    displayCurrentPage();
                    edit_popup_hide()
                }else{
                    alert("There was a problem deactivating the account");
                    edit_popup_hide()
                     
                }
            }
          });
        } 
        }else{
           if(confirm("activate Medrep Account?")){
            $.ajax({
            url: "medreps/process.php",
            type: "POST",
            async: false,
            data: {
              "activate_medrep": 1,
              "medrep_id": medrep_id
            },
            success: function(data){
                if(data==1){
                    alert("Medrep account has been activated");
                    
                     displayCurrentPage();
                     edit_popup_hide()
                }else{
                    alert("There was a problem activating the account");
                  edit_popup_hide()
                }
            }
          });
        }
        }
    });
    

    //EDIT FUNCTION BUTANG RECORDS SA POPUP SA MANAGE ASSIGNMENT
    $(document).on("click", ".manage_assign", function(){
       $(".loading-screen-popup-2-class").show();
                  $(".hide-data-class").hide();
      var row_id = $(this).attr("id");
      $("#manage_id").val(row_id);
            $.ajax({
            url: "medreps/process.php",
            method: "POST",
            data:{
              "get_assign_info": 1,
              "row_id": row_id
            },
            success: function(data){
              $('#assignment_info').html(data);
              setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
            }
          });
    });

    //Mag save edit assignment na di pre
    $(document).on("click", "#update_assign", function(){
      if(confirm("Save changes of assignment?")){
      var row_id = $("#manage_id").val();
      var medrep_id = $("#new_assign").val();
          $.ajax({
            url: "medreps/process.php",
            method: "POST",
            data:{
              "update_assignment": 1,
              "row_id": row_id,
              "medrep_id": medrep_id
            },
            success: function(data){
              alert("Assigned Medical Representative successfully updated");
              displayCurrentPage();
            }
          });
      }
    });

    //EDIT FUNCTION BUTANG RECORDS SA POPUP
    $(document).on("click", ".row_selected", function(){
      var row_id = $(this).attr("id");
       $(".loading-screen-popup-2-class").show();
                  $(".hide-data-class").hide();
      $("#edit_id").val(row_id);
            $.ajax({
            url: "medreps/process.php",
            method: "POST",
            data:{
              "get_specific_row": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              $('#edit_id').val(data.medrep_id);
              $('#edit_firstname').val(data.mr_firstname);
              $('#edit_middlename').val(data.mr_middlename);
              $('#edit_lastname').val(data.mr_lastname);
              $('#edit_address').val(data.mr_address);
              $('#edit_contact').val(data.mr_contact_no);
              $('#edit_email').val(data.mr_email);
              $('.activator-button').attr("id", data.medrep_id);
              if((data.status) == 1){
                $('.activator-button').val('deactivate');
              }else{
                $('.activator-button').val('activate');
              }
               setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
            }
          });
    });

    $('#contact').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });

    $("#submit_add").click(function(){
      var firstname = $("#firstname").val();
      var middlename = $("#middlename").val();
      var lastname = $("#lastname").val();
      var address = $("#address").val();
      var email = $("#email").val();
      var contact = $("#contact").val();
      var username = $("#username").val();
      var password = $("#pass").val();
      var conpassword = $("#conpass").val();
if(firstname!=null && firstname!="" && middlename!=null && middlename!="" && lastname!=null && lastname!="" && address!=null && address!="" && email!=null && email!="" && contact!=null && contact!="" && username!=null && username!="" && pass!=null && pass!="" && conpass!=null && conpass!=""){
      if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(firstname)){
        if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(middlename)){
          if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(lastname)){
            if((/^[0-9a-zA-Z\-\,\.\s\-\#]+$/).test(address)){
              if((/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/).test(email)){

        if(password==conpassword){
        if (confirm('Continue creating new Medical Representative?')) {
          $.ajax({
            url: "medreps/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "add_medrep": 1,
              "firstname": firstname,
              "middlename": middlename,
              "lastname": lastname,
              "address": address,
              "email": email,
              "contact": contact,
              "username": username,
              "password": password
            },
            success: function(data){
              if(data=="username"){
                alert("Username already exists. Please try again.");  
              }else if(data=="unable"){
                alert("Medical Representative already exists. Please try again.");
              }else{
                alert("Medical Representative successfully added");
                popup_hide();
                displayCurrentPage();
                document.getElementById("addform").reset();
                }
            }
          });
        } 
      }else{
        alert("Password and confirm password do not match");
      }

              }else{
                alert("Please enter a valid email address only");
              }
            }else{
              alert("Failed: Address should contain alphanumeric values only");
            }
          }else{
            alert("Failed: lastname must only contain alphanumeric values");
          }
        }else{
          alert("Failed: middlename must only contain alphanumeric values");  
        }
      }else{
        alert("Failed: firstname must only contain alphanumeric values");
      }
        }else{
        alert("Please fill up all fields");
      }
           

      
    });


    $('#edit_contact').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });


    $("#update_row").click(function(){
      var row_id = $("#edit_id").val();
      var firstname = $("#edit_firstname").val();
      var middlename = $("#edit_middlename").val();
      var lastname = $("#edit_lastname").val();
      var address = $("#edit_address").val();
      var email = $("#edit_email").val();
      var contact = $("#edit_contact").val();




      if(firstname!=null && firstname!="" && middlename!=null && middlename!="" && lastname!=null && lastname!="" && address!=null && address!="" && email!=null && email!="" && contact!=null && contact!=""){
      if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(firstname)){
        if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(middlename)){
          if((/^[0-9a-zA-Z\-\s\,\.]+$/).test(lastname)){
            if((/^[0-9a-zA-Z\-\,\.\s\-\#]+$/).test(address)){
              if((/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/).test(email)){


        if (confirm('Are you sure you want to update this record?')) {
          $.ajax({
            url: "medreps/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "update_medrep": 1,
              "row_id": row_id,
              "firstname": firstname,
              "middlename": middlename,
              "lastname": lastname,
              "address": address,
              "email": email,
              "contact": contact
            },
            success: function(data){
              if(data==0){
                alert("Medical Representative name already exist! Please try again");
              }else{
                alert("Medical Representative information successfully updated");
                displayCurrentPage();
              }
            }
          });
        }

              }else{
                alert("Please enter a valid email address only");
              }
            }else{
              alert("Failed: Address should contain alphanumeric values only");
            }
          }else{
            alert("Failed: lastname must only contain alphanumeric values");
          }
        }else{
          alert("Failed: middlename must only contain alphanumeric values");  
        }
      }else{
        alert("Failed: firstname must only contain alphanumeric values");
      }
        }else{
        alert("Please fill up all fields");
      }


    });

    //REMOVE IS CLICKED
    $('#remove_row').click(function (event) {
      var row_id = $("#edit_id").val();
      if (confirm('Are you sure you want to remove this record?')) {
            $.ajax({
            url: "medreps/process.php",
            method: "POST",
            data:{
              "remove_medrep": 1,
              "row_id": row_id
            },
            success: function(data){
              alert("Medical Representative removed successfully");
              displayCurrentPage();
              edit_popup_hide();
            }
          });
        }
    });

     /*where you edit*/
      setTimeout(function(){
          document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
        }, 1000);
  });
  function displayMedrepTable(){          
          $.ajax({
            url: "medreps/process.php",
            type: "POST",
            async: false,
            data: {
              "display_medrep_table": 1
            },
            success: function(data){
                $("#sub-container").html(data);
            }
          });
  }

  function displayAssignmentsTable(){          
          $.ajax({
            url: "medreps/process.php",
            type: "POST",
            async: false,
            data: {
              "display_assignments_table": 1
            },
            success: function(data){
                $("#sub-container").html(data);
            }
          });
  }

  function displayMonthlyPerformance(){          
          $.ajax({
            url: "medreps/process.php",
            type: "POST",
            async: false,
            data: {
              "display_monthly_performance": 1
            },
            success: function(data){
                $("#sub-container").html(data);
            }
          });
  }

  function displayCurrentPage(){
  var page = <?php echo (json_encode($_GET['sub']));?>;
    switch(page){
      case null:
      case 'list': displayMedrepTable();
      break;
      case 'assignments': displayAssignmentsTable();
      break;
      case 'performance': displayMonthlyPerformance();
      break;
      
    }
}

  </script>