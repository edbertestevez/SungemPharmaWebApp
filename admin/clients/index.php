<?php
  switch($sub){
    case 'add': 
    	$p_indicator = "Clients &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Add Client";
      break;
    case 'pending': 
        $p_indicator = "Clients &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Pending Web Requests";
        break;
    case 'list': 
        $p_indicator = "Clients &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Clients List";
        break;
    case 'area': 
        $p_indicator = "Clients &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Area List";
        break;
    default: $p_indicator = "Clients";
  }
?>
<div class="exhidden-menu">
   <button class="crossmenu">&#735;</button>
   <a href="index.php?mod=clients&sub=list"><div class="subitem hidden-sub-menu">Client List</div></a>
  <a href="index.php?mod=clients&sub=pending"> <div class="subitem hidden-sub-menu">Pending Web Requests List</div></a>
   <a href="index.php?mod=clients&sub=area"> <div class="subitem hidden-sub-menu">Area List</div></a>
 </div>
<div class="card-wrapper">
  <div class="card-style-subnavi">
<div class="sub-navi">
 <div class="hidden-menu"><button class="burgermenu">&#9776;</button></div>
  <div class="subitem menu-subs" id="menu_clients"><a href="index.php?mod=clients&sub=list">Clients List</a></div>
<!--	<div class="subitem" id="popup" onclick="popup_show()"><a>Add Client</a></div>-->
	<div class="subitem menu-subs" id="menu_requests"><a href="index.php?mod=clients&sub=pending">Pending Web Requests</a></div>
  <div class="subitem menu-subs" id="menu_requests"><a href="index.php?mod=clients&sub=area">Area List</a></div>
  <!--<div class="subitem" id="menu_requests" onclick="popup_show_two()"><a>Add Area</a></div>-->
</div>
</div>
	<div class="indicator"><img src="img/icons/clients-black.png"/><?php echo $p_indicator;?></div>
</div>

<div id="back-black" onclick="edit_popup_hide_two()"></div>
<div class="pop-container" id="edit-pop-container-two">
        <div id="popup-form" style="width: 450px;">
        <form id="editareaform" method="post" name="form">
        <div class="loading-screen-popup-2-class" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
  <div class="hide-data-class">
        <input type="hidden" id="edit_area_id">   
        <h4 id="close" onclick ="edit_popup_hide_two()">&times;</h4>
        <h3>Edit Area</h3>
        <hr>
        <h6>Old Area Name</h6>
        <input class="form-row" id="edit_area_name" name="edit_area_name" type="text" disabled>
        <h6>Updated Area Name</h6>
        <input class="form-row" id="update_area_name" name="update_area_name" type="text">
        <div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" id="submit_edit_area" value="Save"/>
          <input type="button" class="material-button float-right" onclick="edit_popup_hide_two()" value="Close"/>
        </div>
        </div>
        </form>
        </div>
</div>

<div id="back-black" onclick="popup_hide_two()"></div>
<div class="pop-container" id="pop-container-two">
        <div id="popup-form" style="width: 450px;">
        <form id="addareaform" method="post" name="form">   
        <h4 id="close" onclick ="popup_hide_two()">&times;</h4>
        <h3>New Area</h3>
        <hr>
        <input class="form-row" id="area_name" name="area_name" placeholder="Area Name" type="text">
        <div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" id="submit_add_area" value="Save"/>
          <input type="button" class="material-button float-right" onclick="popup_hide_two()" value="Close"/>
        </div>
        </form>
        </div>
</div>

<div id="back-black" onclick="popup_hide()"></div>
<div class="pop-container" id="pop-container">
        <div id="popup-form" style="width: 450px;">
        <form id="addform" method="post" name="form">   
        <h4 id="close" onclick ="popup_hide()">&times;</h4>
        <h3>New Client</h3>
        <hr>
        <input class="form-row" id="name" name="name" placeholder="Name" type="text" required>
        <select class="material-input-dd" id="area" name="area">
            <?php
            $listarea = $clients->get_area();
            if($listarea){
                foreach ($listarea as $valuearea) {
                    ?>
                    <option value=<?php echo $valuearea['area_id'];?>><?php echo $valuearea['area_name'];?></option>
                    <?php
                }
            }
            ?>
        </select>
        <textarea class="form-textarea" id="address" name="address" form="addform" placeholder="Address" required="" /></textarea>
        <input class="form-row" id="license" name="license" placeholder="LTO No." type="text" required>
        <input class="form-row" id="contact" name="contact" placeholder="Contact No." type="text" required>
        <input class="form-row" id="email" name="email" placeholder="Email" type="text" required>
        <div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" id="submit_add" value="Save"/>
          <input type="button" class="material-button float-right" id="submit_close" onclick="popup_hide()" value="Close"/>
        </div>
        </form>
        </div>
</div>

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
      <h3>Client Information</h3>
      <hr>
      <input type="hidden" id="edit_id">
      <h6>Name</h6><input class="form-row" id="edit_name" name="name" placeholder="Name" type="text" required>
      <h6>Area</h6><select class="material-input-dd" id="edit_area" name="area">
            <?php
            $listarea = $clients->get_area();
            if($listarea){
                foreach ($listarea as $valuearea) {
                    ?>
                    <option value=<?php echo $valuearea['area_id'];?>><?php echo $valuearea['area_name'];?></option>
                    <?php
                }
            }
            ?>
        </select>
      <h6>Address</h6><textarea class="form-textarea" id="edit_address" name="address" form="addform" placeholder="Address" required/></textarea>
      <h6>LTO No.</h6><input class="form-row" id="edit_license" name="license" placeholder="LTO No." type="text" required>
      <h6>Contact No.</h6><input class="form-row" id="edit_contact" name="contact" placeholder="Contact No." type="text" required>
      <h6>Email</h6><input class="form-row" id="edit_email" name="email" placeholder="Email" type="text" required>
      <br/><br/>
      <h6 id="edit_web"></h6>
      <div class="material-button-wrapper">
        <input type="button" class="material-button-main float-right" id="update_row" value="Update">
        <button type="button" class="material-button float-right" id="remove_row" value="">button</button>
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
  function displayClientsTable(){          
          $.ajax({
            url: "clients/process.php",
            type: "POST",
            async: false,
            data: {
              "display_client_table": 1,
            },
            success: function(data){
                $("#sub-container").html(data);
            }
          });
    }
   function displayRequestsTable(){
          $.ajax({
            url: "clients/process.php",
            type: "POST",
            async: false,
            data: {
              "display_requests_table": 1,
            },
            success: function(data){
                $("#sub-container").html(data);
            }
          });
  } 
  function displayAreaTable(){
          $.ajax({
            url: "clients/process.php",
            type: "POST",
            async: false,
            data: {
              "display_area_table": 1,
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
      case 'list': displayClientsTable();
      break;
      case 'pending': displayRequestsTable();
      break;
      case 'area': displayAreaTable();
      break;
    }
}

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

    

    $("#submit_edit_area").click(function(){

      var updated = $("#update_area_name").val();

      if((/^[0-9a-zA-Z\-\s\,\.\()]+$/).test(updated)){

         if($("#update_area_name").val()!=""){
        var area_name = $("#update_area_name").val();
        var area_id = $("#edit_area_id").val();
      if(confirm("Update this area name?")){
        $.ajax({
            url: "clients/process.php",
            type: "POST",
            async: false,
            data: {
              "update_area": 1,
              "area_name": area_name,
              "area_id": area_id
            },
            success: function(data){
              if(data=="error"){
                alert("Area name already exists. Please try again.");
              }else{
                alert("Area name updated successfully");
                edit_popup_hide_two();
                displayCurrentPage();
                document.getElementById("editareaform").reset();
                }
            }
          });
      }
    }


      }else{
        alert("failed: prohibited characters has been detected");
      }


     
    });

    $("#submit_add_area").click(function(){


       if((/^[0-9a-zA-Z\-\s\,\.\()]+$/).test($("#area_name").val())){

        if($("#area_name").val()!=""){
        var area_name = $("#area_name").val();
      if(confirm("Save new area?")){
        $.ajax({
            url: "clients/process.php",
            type: "POST",
            async: false,
            data: {
              "add_area": 1,
              "area_name": area_name
            },
            success: function(data){
              if(data=="error"){
                alert("Area name already exists. Please try again.");
              }else{
                alert("Area successfully added");
                popup_hide_two();
                displayCurrentPage();
                document.getElementById("addareaform").reset();
                }
            }
          });
      }
    }

       }else{alert("failed: prohibited characters has been detected");}

      
    });

    $('#license').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });

    $('#contact').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });


    //ADD FUNCTION
    $("#submit_add").click(function(){
      var name = $("#name").val();
      var area = $("#area").val();
      var address = $("#address").val();
      var license = $("#license").val();
      var email = $("#email").val();
      var contact = $("#contact").val();

      if(name!=null && name!="" && area!=null && area!="" && address!=null && address!="" && license!=null && license!="" && email!=null && email!="" && contact!=null && contact!=""){

        if((/^[0-9a-zA-Z\-\s\,\.\()]+$/).test(name)){
          if((/^[0-9a-zA-Z\-\,\.\s\-\#]+$/).test(address)){
            if((/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/).test(email)){

              if(confirm("Proceed saving new client?")){
          $.ajax({
            url: "clients/process.php",
            type: "POST",
            async: false,
            data: {
              "add_client": 1,
              "name": name,
              "area": area,
              "address": address,
              "license": license,
              "email": email,
              "contact": contact
            },
            success: function(data){
              if(data=="unable"){
                alert("Client Name / LTO Number already exists. Please try again.");
              }else{
                alert("Client successfully added");
                popup_hide();
                displayCurrentPage();
                document.getElementById("addform").reset();
                }
            }
          });
        }

            }else{alert("Failed: pls enter a valid email address");}
          }else{alert("Failed: prohibited characters in address has been detected");}
        }else{alert("Failed: prohibited characters in name field has been detected");}

        
      }else{
        alert("Please fill up all fields");
      }
    });
    //EDIT FUNCTION BUTANG RECORDS SA POPUP
    $(document).on("click", ".row_selected", function(){
      var row_id = $(this).attr("id");
       $(".loading-screen-popup-2-class").show();
      $(".hide-data-class").hide();
      $("#edit_id").val(row_id);
            $.ajax({
            url: "clients/process.php",
            method: "POST",
            data:{
              "get_specific_row": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              if(data.status==0){
                var status_btn = "Activate";
              }else{
                var status_btn = "Deactivate";
              }
              $('#edit_id').val(data.client_id);
              $('#edit_name').val(data.client_name);
              $('select#edit_area').val(data.area_id);
              $('#edit_address').val(data.address);
              $('#edit_license').val(data.lto_no);
              $('#edit_contact').val(data.contact_no);
              $('#edit_email').val(data.email);
              $('#remove_row').html(status_btn);
              $('#remove_row').val(data.status);
              if(data.web_status==0){
                 $('#edit_web').html("Web Status: (Unregistered)");
              }else{
                 $('#edit_web').html("Web Status: (Activated)");
              }
                setTimeout(function(){
                $(".loading-screen-popup-2-class").hide();
              $(".hide-data-class").show();
              },1000);
            }
          });
    });

    //EDIT FUNCTION BUTANG RECORDS SA POPUP
    $(document).on("click", ".area_selected", function(){
      var row_id = $(this).attr("id");
       $(".loading-screen-popup-2-class").show();
                  $(".hide-data-class").hide();
      $("#edit_area_id").val(row_id);
            $.ajax({
            url: "clients/process.php",
            method: "POST",
            data:{
              "get_area_row": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              $('#edit_area_id').val(data.area_id);
              $('#edit_area_name').val(data.area_name);
               setTimeout(function(){
                  $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
                }, 1000);
            }
          });
    });

    //EDIT IS CLICKED
     $('#edit_license').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });

    $('#edit_contact').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });


    $('#update_row').click(function (event) {
      var row_id = $("#edit_id").val();
      var name = $("#edit_name").val();
      var area = $("#edit_area").val();
      var address = $("#edit_address").val();
      var license = $("#edit_license").val();
      var email = $("#edit_email").val();
      var contact = $("#edit_contact").val();

      


      if(name!=null && name!="" && area!=null && area!="" && address!=null && address!="" && license!=null && license!="" && email!=null && email!="" && contact!=null && contact!=""){

         if((/^[0-9a-zA-Z\-\s\,\.\()]+$/).test(name)){

           if((/^[0-9a-zA-Z\-\,\.\s\-\#]+$/).test(address)){

             if((/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/).test(email)){


              if (confirm('Are you sure you want to update this record?')) {
            $.ajax({
            url: "clients/process.php",
            method: "POST",
            data:{
              "update_client": 1,
              "row_id": row_id,
              "name": name,
              "area": area,
              "address": address,
              "license": license,
              "email": email,
              "contact": contact
            },
            success: function(data){
              if(data=="error"){
                alert("Client already exist! Please try again");
              }else{
                alert("Client Information updated successfully");
              displayCurrentPage();
              }
            }
          });
        }


                }else{alert("Failed: pls enter a valid email address");}
          }else{alert("Failed: prohibited characters in address has been detected");}
        }else{alert("Failed: prohibited characters in name field has been detected");}
         
           

        
      }else{
        alert("Please fill up all fields");
      }
  });
    //REMOVE IS CLICKED
    $('#remove_row').click(function (event) {
      var row_id = $("#edit_id").val();
      var row_status = $('#remove_row').val();

      if(row_status == 1){
        if (confirm('Are you sure you want to deactivate this record?')) {
              $.ajax({
              url: "clients/process.php",
              method: "POST",
              data:{
                "remove_client": 1,
                "row_id": row_id
              },
              success: function(data){
                alert("Client deactivated successfully!");
                displayCurrentPage();
                edit_popup_hide();
              }
            });
          }
          
        }else{
          if (confirm('Are you sure you want to activate this record?')) {
              $.ajax({
              url: "clients/process.php",
              method: "POST",
              data:{
                "remove_client": 2,
                "row_id": row_id
              },
              success: function(data){
                alert("Client activated successfully!");
                displayCurrentPage();
                edit_popup_hide();
              }
            });
          }
        }
    });
    // ACCEPT REQUEST BUTTON
    $(document).on("click", ".accept_request", function(){
      var row_id = $(this).attr("id");
      if (confirm('Are you sure you want to accept this registration?')) {
            $.ajax({
            url: "clients/process.php",
            method: "POST",
            data:{
              "accept_request": 1,
              "row_id": row_id
            },
            success: function(data){
              if(data=="insert"){
                alert("New client has been added."+"\n"+"Online account successfully accepted");
              }else if(data=="update"){
                alert("Client already part of the client list" + "\n" + "Client Information is only updated"+"\n"+"Online account successfully accepted");
              }
              displayCurrentPage();
            }
          });
        }
    });

    // DECLINE REQUEST BUTTON
    $(document).on("click", ".decline_request", function(){
      var row_id = $(this).attr("id");
      if (confirm('Are you sure you want to decline this registration?')) {
            $.ajax({
            url: "clients/process.php",
            method: "POST",
            data:{
              "decline_request": 1,
              "row_id": row_id
            },
            success: function(data){
              alert("Request successfully denied");
              displayCurrentPage();
            }
          });
        }
    });
      /*where you edit*/
setTimeout(function(){
  document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
},1000);
});

</script>

