<?php
$p_indicator = "Suppliers &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Suppliers List";
?>
<div class="card-wrapper">
<!--
  <div class="card-style-subnavi">
<div class="sub-navi">
    <div class="subitem"><a href="index.php?mod=suppliers&sub=list">Suppliers List</a></div>
	<div class="subitem" id="popup_add" onclick="popup_show()"><a>Add Supplier</a></div>
</div>
</div>
-->
	<div class="indicator"><img src="img/icons/supplier-black.png"/><?php echo $p_indicator;?></div>
</div>

<!----------------POP UP FORM NI SYA PRE-------------------------->
<div id="back-black" onclick="popup_hide()"></div>
  <div class="pop-container" id="pop-container">
        <div id="popup-form" style="width: 450px;">
        <form id="addform" method="post" name="form">   
        <h4 id="close" onclick ="popup_hide()">&times;</h4>
        <h3>New Supplier</h3>
        <hr>
        <input class="form-row" id="name" name="name" placeholder="Name" type="text" required>
        <textarea class="form-textarea" id="address" name="address" form="addform" placeholder="Address" /></textarea>
        <input class="form-row" id="contact" name="contact" placeholder="Contact No." type="text" required>
        <input class="form-row" id="email" name="email" placeholder="Email" type="text" required>
        <div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" id="submit_add" value="Save"/>
          <input type="button" class="material-button float-right" onclick="popup_hide()" value="Close"/>
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
      <h3>Supplier Information</h3>
      <hr>
      <input type="hidden" id="edit_id">
      <h6>Name</h6><input class="form-row" id="edit_name" name="name" placeholder="Name" type="text" required>
      <h6>Address</h6><textarea class="form-textarea" id="edit_address" name="address" form="addform" placeholder="Address" required/></textarea>
      <h6>Contact No.</h6><input class="form-row" id="edit_contact" name="contact" placeholder="Contact No." type="text" required>
      <h6>Email</h6><input class="form-row" id="edit_email" name="email" placeholder="Email" type="text" required>
      <div class="material-button-wrapper">
        <input type="button" class="material-button-main float-right" id="update_row" value="Update">
        <button type="button" class="material-button float-right" id="remove_row" value="Remove">button</button>
      </div>
      </div>
      </form>
      </div>
</div>

<!---------TABLE RECORD IS FOUND ON PROCESS.PHP FOR AUTOMATIC UPDATE. REFER ON AJAX BELOW------------>
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
    <div id="sub-container">
      
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function(){
   displaySupplierTable();  


    $('#contact').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });
    
    $("#submit_add").click(function(){
      var name = $("#name").val();
      var address = $("#address").val();
      var email = $("#email").val();
      var contact = $("#contact").val();

      if(name!=null && name!="" && address!=null && address!="" && email!=null && email!="" && contact!=null && contact!=""){

         if((/^[0-9a-zA-Z\-\s\,\.\()]+$/).test(name)){
          if((/^[0-9a-zA-Z\-\,\.\s\-\#]+$/).test(address)){
            if((/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/).test(email)){

            if(confirm("Continue saving new supplier?")){
          $.ajax({
            url: "suppliers/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "add_supplier": 1,
              "name": name,
              "address": address,
              "email": email,
              "contact": contact
            },
            success: function(data){
              if(data=="unable"){
                alert("Supplier already exists. Please try again.");
              }else{
                alert("Supplier successfully added");
                popup_hide();
                displaySupplierTable();
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


    $('#edit_contact').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });

    
    $("#update_row").click(function(){
      var row_id = $("#edit_id").val();
      var name = $("#edit_name").val();
      var address = $("#edit_address").val();
      var email = $("#edit_email").val();
      var contact = $("#edit_contact").val();

      if(name!=null && name!="" && address!=null && address!="" && email!=null && email!="" && contact!=null && contact!=""){

        if((/^[0-9a-zA-Z\-\s\,\.\()]+$/).test(name)){
          if((/^[0-9a-zA-Z\-\,\.\s\-\#]+$/).test(address)){
            if((/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)\b/).test(email)){

          if (confirm('Are you sure you want to update this record?')) {
          $.ajax({
            url: "suppliers/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "update_supplier": 1,
              "row_id": row_id,
              "name": name,
              "address": address, 
              "email": email,
              "contact": contact
            },
            success: function(data){
              if(data=="error"){
                alert("Supplier Name already exist");
              }else{
                alert("Supplier Information updated successfully");
                displaySupplierTable();
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
            url: "suppliers/process.php",
            method: "POST",
            data:{
              "get_specific_row": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
               setTimeout(function(){
              if(data.status == 0){
                var btn_status = "Activate";
              }else{
                var btn_status = "Deactivate";
              }
              $('#edit_id').val(data.supplier_id);
              $('#edit_name').val(data.supplier_name);
              $('#edit_address').val(data.address);
              $('#edit_contact').val(data.contact_no);
              $('#remove_row').val(data.status);
              $('#remove_row').html(btn_status);
              $('#edit_email').val(data.email);
               $(".loading-screen-popup-2-class").hide();
                  $(".hide-data-class").show();
              },500);
            }
          });
    });

    //REMOVE IS CLICKED
    $('#remove_row').click(function (event) {
      var row_id = $("#edit_id").val();
      var row_status = $('#remove_row').val();

      if(row_status == 1){
        if (confirm('Are you sure you want to deactivate this record?')) {
              $.ajax({
              url: "suppliers/process.php",
              method: "POST",
              data:{
                "remove_supplier": 1,
                "row_id": row_id
              },
              success: function(data){
                alert("Supplier deactivated successfully");
                displaySupplierTable();
                edit_popup_hide();
              }
            });
          }
        }else{
          if (confirm('Are you sure you want to activate this record?')) {
              $.ajax({
              url: "suppliers/process.php",
              method: "POST",
              data:{
                "remove_supplier": 2,
                "row_id": row_id
              },
              success: function(data){
                alert("Supplier activated successfully");
                displaySupplierTable();
                edit_popup_hide();
              }
            });
          }
        }
    });

        /*where you edit*/

     setTimeout(function(){
         document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
      },1000);

  });

  function displaySupplierTable(){          
          $.ajax({
            url: "suppliers/process.php",
            type: "POST",
            async: false,
            data: {
              "display_supplier_table": 1
            },
            success: function(data){
              $("#sub-container").html(data);
            }
          });
    }

    
  
</script>