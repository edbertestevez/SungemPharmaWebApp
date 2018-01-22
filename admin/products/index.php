<?php
  switch($sub){
    case 'addprod': 
    	$p_indicator = "Products &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Product List";
    	break;
    case 'addcat': 
    	$p_indicator = "Products  &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Add Category";
    	break;
    case 'prodlist': 
    	$p_indicator = "Products  &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Product List";
    	break;
    case 'catlist': 
    	$p_indicator = "Products  &nbsp;&nbsp;<b>></b>&nbsp;&nbsp;  Category List";
    	break;
    default: $p_indicator = "Products";
  }
?>
<div class="exhidden-menu">
   <button class="crossmenu">&#735;</button>
   <a href="index.php?mod=products&sub=prodlist"><div class="subitem hidden-sub-menu">Product List</div></a>
  <a href="index.php?mod=products&sub=catlist"> <div class="subitem hidden-sub-menu">Category List</div></a>
 </div>
<div class="card-wrapper">
  

  <div class="card-style-subnavi">

       <!--<div clas="hidden-menu-1">
        <button class="crossmenu">&#735;</button>
        <div class="subitem menu1"><a href="index.php?mod=products&sub=prodlist">Product List</a></div>
      </div>-->

      <div class="sub-navi">
       
        <div class="hidden-menu"><button class="burgermenu">&#9776;</button></div>
      	<div class="subitem menu-subs"><a href="index.php?mod=products&sub=prodlist">Product List</a></div>
      	<div class="subitem menu-subs"><a href="index.php?mod=products&sub=catlist">Category List</a></div>
         <!-- <div class="subitem"><a onclick="popup_show()">Add Product</a></div>-->
          <!--<div class="subitem"><a onclick="popup_show_two()">Add Category</a></div>-->
      </div>
  </div>
  <div class="indicator">
    <img src="img/icons/products-black.png"/><?php echo $p_indicator;?>
  </div>
</div>


  
  <!---------------POP UP FORM NI SYA KA ADD PRE-------------------------->
	<div id="back-black" onclick="popup_hide()"></div>
	<div id="pop-container" class="pop-container style-4">
        <div id="popup-form" style="width: 450px;">
        <form id="addprodform" method="post" name="form">   
        <h4 id="close" onclick ="popup_hide()">&times;</h4>
        <h3>New Product</h3>
        <hr>
        <input class="form-row" id="barcode" name="barcode" placeholder="Barcode Value" type="text" required>
        <input class="form-row" id="brand" name="brandname" placeholder="Brand Name" type="text" required>
        <input class="form-row" id="generic" name="genericname" placeholder="Generic Name" type="text" required>
        <select class="form-row" id="category" name="category">
            <?php
            $listcat = $products->get_category();
            if($listcat){
                foreach ($listcat as $valuecat) {
                    ?>
                    <option value=<?php echo $valuecat['cat_id'];?>><?php echo $valuecat['cat_name'];?></option>
                    <?php
                }
            }
            ?>
        </select>
        <input class="form-row" id="formulation" name="formulation" placeholder="Formulation" type="text" required>
        <input class="form-row" id="packaging" name="packaging" placeholder="Packaging" type="text" required>
        <input class="form-row" id="price" name="price" placeholder="Unit Price" type="text" required>
        <input class="form-row" id="reorder" name="reorder" placeholder="Reorder Level" type="number" required>
        <div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" id="submit_add_product" value="SAVE"/> 
          <input type="button" class="material-button float-right" onclick="popup_hide()" value="CANCEL"/>
        </div>
        </form>
        </div>
	</div>

    <!----------------POP UP FORM NI SYA KA CATEGORY PRE-------------------------->
    <div id="back-black" onclick="popup_hide_two()"></div>
    <div id="pop-container-two" class="pop-container style-4">
        <div id="popup-form" style="width: 450px;">
        <form id="addcatform" method="post" name="form"> 


        <h4 id="close" onclick ="popup_hide_two()">&times;</h4>
        <h3>New Category</h3>
        <hr>
        <input class="form-row" id="catname" name="catname" placeholder="Category Name" type="text" required>
        <textarea class="form-textarea-medium" id="description" name="description" form="addcatform" placeholder="Description" /></textarea>
        <div class="material-button-wrapper">
          <input type="button" class="material-button-main float-right" id="submit_add_category" value="Save"/>  
          <input type="button" class="material-button float-right" onclick="popup_hide_two()" value="Close"/>
        </div>
        </form>
        </div>
    </div>

    <!----------------EDIT POP UP FORM NI SYA KA CATEGORY PRE-------------------------->
    <div id="back-black" onclick="edit_popup_hide_two()"></div>
    <div id="edit-pop-container-two" class="pop-container style-4">
        <div id="popup-form" style="width: 450px;">
        <form id="editcatform" method="post" name="form"> 

        <div id="loading-screen-popup" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>


        <div id="hide-data">    
        <input type="hidden" id="edit_cat_id">
        <h4 id="close" onclick ="edit_popup_hide_two()">&times;</h4>
        <h3>Category Details</h3>
        <hr>
        <h6>Category Name</h6><input class="form-row" id="edit_catname" name="catname" placeholder="Category Name" type="text" required>
        <h6>Description</h6><textarea class="form-textarea-medium" id="edit_description" name="description" form="addcatform" placeholder="Description" /></textarea>
        <div class="material-button-wrapper">
            <input type="button" class="material-button-main float-right" id="update_category" value="Update"/>
            <input type="button" class="material-button float-right" id="remove_category" value="Remove"/>
        </div>
        </div>
        </form>
        </div>
    </div>

    <!----------------POP UP FORM NI SYA KA ADD PRE-------------------------->
  <div id="back-black" onclick="edit_popup_hide()"></div>
  <div id="edit-pop-container" class="pop-container style-4">
        <div id="popup-form" style="width: 450px;">
        <form id="editprodform" method="post" name="form">   
        <div id="loading-screen-popup-2" style="height: 250px; padding-top: 20%;">
<svg class="spinner" width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
       <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
  </svg>
        </div>
          <div id="hide-data-2">
        <input type="hidden" id="edit_pro_id">
        <h4 id="close" onclick ="edit_popup_hide()">&times;</h4>
        <h3>Product Information</h3>
        <hr>
        <h6>Barcode Value</h6><input class="form-row" id="edit_barcode" name="barcode1" placeholder="Barcode Value" type="text" required>
        <h6>Brand Name</h6><input class="form-row" id="edit_brand" name="brandname" placeholder="Brand Name" type="text" required>
        <h6>Generic Name</h6><input class="form-row" id="edit_generic" name="genericname" placeholder="Generic Name" type="text" required>
        <h6>Category</h6>
        <div class="rows-select">
        <select class="cat-select" id="edit_category" name="category" style="width:100%;">
            <?php
            $listcat = $products->get_category();
            if($listcat){
                foreach ($listcat as $valuecat) {
                    ?>
                    <option value=<?php echo $valuecat['cat_id'];?>><?php echo $valuecat['cat_name'];?></option>
                    <?php
                }
            }
            ?>
        </select>
        </div>
        <h6>Formulation</h6><input class="form-row" id="edit_formulation" name="formulation" placeholder="Formulation" type="text" required>
        <h6>Packaging</h6><input class="form-row" id="edit_packaging" name="packaging" placeholder="Packaging" type="text" required>
        <h6>Unit Price</h6><input class="form-row" id="edit_price" name="price" placeholder="Unit Price" type="text" required>
        <h6>Reorder Level</h6><input class="form-row" id="edit_reorder" name="reorder" placeholder="Reorder Level" type="number" required>
        <div class="material-button-wrapper">
            <input type="button" class="material-button-main float-right" id="update_product" value="Update"/>
            <button type="button" class="material-button float-right" id="remove_product">button</button>
        </div>
        </div>
        </form>
        </div>
  </div>
  <?php
  if(isset($_GET['sub'])){
  ?>

 <!-- <div id="loading-screen-2">
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
          <!------------ CONTAINS DISPLAYED TABLE FOR CATEGORY BASED ON AJAX BELOW------------>
      </div>
      <div id="sub-container-rows">
          <!------------ CONTAINS DISPLAYED TABLE FOR PRODUCTS BASED ON AJAX BELOW------------>
      </div>
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

  $(".cat-select").select2({
    formatSelection: formatSelection
  });



    displayCurrentPage();

    //EDIT FUNCTION BUTANG RECORDS SA POPUP
    $(document).on("click", ".category_selected", function(){
      var row_id = $(this).attr("id");
        document.getElementById("hide-data").style.display = "none";
      document.getElementById("loading-screen-popup").style.display = "block";
      $("#edit_cat_id").val(row_id);
            $.ajax({
            url: "products/process.php",
            method: "POST",
            data:{
              "get_category_row": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){
              $('#edit_cat_id').val(data.cat_id);
              $('#edit_catname').val(data.cat_name);
              $('#edit_description').val(data.description);
              setTimeout(function(){
              document.getElementById("hide-data").style.display = "block";
              document.getElementById("loading-screen-popup").style.display = "none";
              }, 1000);
            }
          });
    });

    $(document).on("click", ".product_selected", function(){
      var row_id = $(this).attr("id");
        document.getElementById("hide-data-2").style.display = "none";
      document.getElementById("loading-screen-popup-2").style.display = "block";
      $("#edit_pro_id").val(row_id);
            $.ajax({
            url: "products/process.php",
            method: "POST",
            data:{
              "get_product_row": 1,
              "row_id": row_id
            },
            dataType:"json",
            success: function(data){  
              if(data.status==0){
                var product_btn = "Activate";
              }else{
                var product_btn = "Deactivate";
              }
              $('#edit_barcode').val(data.barcode);
              $('#edit_brand').val(data.pro_brand);
              $('#edit_generic').val(data.pro_generic);
              $('#edit_category').val(data.cat_id);
              $('#edit_formulation').val(data.pro_formulation);
              $('#edit_packaging').val(data.pro_packaging);
              $('#edit_price').val(data.pro_unit_price);
              $('#edit_reorder').val(data.pro_reorder_level);
              $('#remove_product').html(product_btn);
              $('#remove_product').val(data.status);
              $('#edit_category').trigger('change');

              setTimeout(function(){
              document.getElementById("hide-data-2").style.display = "block";
              document.getElementById("loading-screen-popup-2").style.display = "none";
              }, 1000);
            }
          });
    });

    $("#update_category").click(function(){
      var catname = $("#edit_catname").val();
      var description = $("#edit_description").val();
      var row_id = $("#edit_cat_id").val();
      

      if(catname!=null && catname!="" && description!=null && description!=""){

        if(((/^[0-9a-zA-Z\-\s\/\+\()\,\''\'\`\\\=\.]+$/).test(catname)) && ((/^[0-9a-zA-Z\-\s\/\+\()\,\''\'\`\\\=\.]+$/).test(description))){

           if (confirm('Are you sure you want to update this category?')) {
          $.ajax({
            url: "products/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "update_category": 1,
              "catname": catname,
              "row_id": row_id,
              "description": description
            },
            success: function(data){
                alert("Category successfully updated");
                displayCurrentPage();
            }
          });
        }

          
        }else{
          alert("failed: you have entered prohibited characters.");
        }

         
      }else{
        alert("Please fill up all fields");
      }



    

    });

    $("#remove_category").click(function(){
      var row_id = $("#edit_cat_id").val();
      if (confirm('Are you sure you want to remove this category?')) {
          $.ajax({
            url: "products/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "remove_category": 1,
              "row_id": row_id
            },
            success: function(data){
                alert("Category successfully removed");
                edit_popup_hide_two();
                displayCurrentPage();
            }
          });
      }
    });

    $("#remove_product").click(function(){
      var row_id = $("#edit_pro_id").val();
      var row_status = $("#remove_product").val(); 
      
      if(row_status == 0){
        if (confirm('Are you sure you want to activate this product?')) {
            $.ajax({
              url: "products/process.php",
              type: "POST",
              async: false,
              dataType: "json",
              data: {
                "remove_product": 2,
                "row_id": row_id
              },
              success: function(data){
                  alert("Product has been successfully activated!");
                  edit_popup_hide();
                  displayCurrentPage();
              }
            });
        }
      }else{
        if (confirm('Are you sure you want to deactivate this product?')) {
            $.ajax({
              url: "products/process.php",
              type: "POST",
              async: false,
              dataType: "json",
              data: {
                "remove_product": 1,
                "row_id": row_id
              },
              success: function(data){
                  alert("Product has been successfully deactivated!");
                  edit_popup_hide();
                  displayCurrentPage();
              }
            });
        }
      }
    });

    $('#edit_price').on('keypress', function(e){
     
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9\.]/.test(String.fromCharCode(e.which)); // numbers
      
    });

    $('#edit_reorder').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
      
    });


    $("#update_product").click(function(){
      var row_id = $("#edit_pro_id").val(); 
      var brand = $("#edit_brand").val();
      var generic = $("#edit_generic").val();
      var category = $("#edit_category").val();
      var formulation = $("#edit_formulation").val();
      var packaging = $("#edit_packaging").val();
      var price = $("#edit_price").val();
      var reorder = $("#edit_reorder").val();
      var barcode = $("#edit_barcode").val();
      var countPer = (price.match(/\./g) || []).length;


      if(brand!=null && brand!="" && generic!=null && generic!="" && category!=null && category!="" && formulation!=null && formulation!="" && packaging!=null && packaging!="" && price!=null && price!="" && reorder!=null && reorder!=""){
        if((/^[0-9a-zA-Z\-\s\\\''\.']+$/).test(brand)){
          if((/^[0-9a-zA-Z\-\s\/\+\()\,\\]+$/).test(generic)){
            if((/^[0-9a-zA-Z\-\s\/\+\()\,\''\'\`\\\=\.]+$/).test(formulation)){
              if((/^[0-9a-zA-Z\-\s\/\+\()\,\''\'\`\\\=\.\;\:]+$/).test(packaging)){
                if(countPer > 1){
                  alert("Wrong Price Format");
                 
                }else{

                  if (confirm('Are you sure you want to update this product?')) {
               $.ajax({
            url: "products/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "update_product": 1,
              "row_id": row_id,
              "brand": brand,
              "generic": generic,
              "category": category,
              "formulation": formulation,
              "packaging": packaging,
              "price": price,
              "reorder": reorder,
              "barcode": barcode
            },
            success: function(data){
              if(data=="barcode"){
                alert("Identical Barcode Value with other products"); 
              }else{
                alert("Product Information successfully updated");
                displayCurrentPage();
              }
             }
          });
        }

                }
            

              }else{
                alert("Failed: prohibited characters in packing detected");
              }

            
            }else{
              alert("Failed: prohibited characters in formulation detected");
            }

          

          }else{
            alert("Failed: prohibited characters in generic name detected");
          }
         
        }else{
          alert("Failed: prohibited characters in brand name detected");
        }


      }else{
        alert("Please fill up all fields");
      }



     /* if(brand!=null && brand!="" && generic!=null && generic!="" && category!=null && category!="" && formulation!=null && formulation!="" && packaging!=null && packaging!="" && price!=null && price!="" && reorder!=null && reorder!=""){
          if (confirm('Are you sure you want to update this product?')) {
          $.ajax({
            url: "products/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "update_product": 1,
              "row_id": row_id,
              "brand": brand,
              "generic": generic,
              "category": category,
              "formulation": formulation,
              "packaging": packaging,
              "price": price,
              "reorder": reorder
            },
            success: function(data){
                alert("Product Information successfully updated");
                displayCurrentPage();
             }
          });
        }
      }else{
        alert("Please fill up all fields");
      }*****/
    });


    $('#price').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
        /[0-9\.]/.test(String.fromCharCode(e.which)); // numbers
    });

    $('#reorder').on('keypress', function(e){
      return e.metaKey || // cmd/ctrl
        e.which <= 0 || // arrow keys
        e.which == 8 || // delete key
       
        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
    });


    $("#submit_add_product").click(function(){
      var brand = $("#brand").val();
      var generic = $("#generic").val();
      var category = $("#category").val();
      var formulation = $("#formulation").val();
      var packaging = $("#packaging").val();
      var price = $("#price").val();
      var reorder = $("#reorder").val();
      var barcode = $("#barcode").val();
      var countPer_add = (price.match(/\./g) || []).length;

      if(brand!=null && brand!="" && generic!=null && generic!="" && category!=null && category!="" && formulation!=null && formulation!="" && packaging!=null && packaging!="" && price!=null && price!="" && reorder!=null && reorder!=""){
        if((/^[0-9a-zA-Z\-\s\\\''\']+$/).test(brand)){
          if((/^[0-9a-zA-Z\-\s\/\+\()\,\\]+$/).test(generic)){
            if((/^[0-9a-zA-Z\-\s\/\+\()\,\''\'\`\\\=\.]+$/).test(formulation)){
              if((/^[0-9a-zA-Z\-\s\/\+\()\,\''\'\`\\\=\.\;\:]+$/).test(packaging)){
                if(countPer_add > 1){
                  alert("wrong price format");
                }else if(confirm("Are you sure you want to add this product?")){
                
                  $.ajax({
                  url: "products/process.php",
                  type: "POST",
                  async: false,
                  dataType: "json",
                  data: {
                    "add_product": 1,
                    "brand": brand,
                    "generic": generic,
                    "category": category,
                    "formulation": formulation,
                    "packaging": packaging,
                    "price": price,
                    "reorder": reorder,
                    "barcode": barcode
                  },
                  success: function(data){
                    alert("GAFSA");
                    alert(data);
                    if(data=="name"){
                      alert("Product already exists. Please try again.");
                    }else if(data=="barcode"){
                      alert("Identical Barcode Value with other products");
                    }else{
                      alert("Product successfully added");
                      popup_hide();
                      displayCurrentPage();
                      document.getElementById("addprodform").reset();
                      }
                  }
                });
                
              }
            

              }else{
                alert("Failed: prohibited characters in packing detected");
              }

            
            }else{
              alert("Failed: prohibited characters in formulation detected");
            }

          

          }else{
            alert("Failed: prohibited characters in generic name detected");
          }
         
        }else{
          alert("Failed: prohibited characters in brand name detected");
        }


      }else{
        alert("Please fill up all fields");
      }
    });

    $("#submit_add_category").click(function(){
      var catname = $("#catname").val();
      var description = $("#description").val();

      if(catname!=null && catname!="" && description!=null && description!=""){

        if(((/^[0-9a-zA-Z\-\s\/\+\()\,\''\'\`\\\=\.]+$/).test(catname)) && ((/^[0-9a-zA-Z\-\s\/\+\()\,\''\'\`\\\=\.]+$/).test(description))){


           $.ajax({
            url: "products/process.php",
            type: "POST",
            async: false,
            dataType: "json",
            data: {
              "add_category": 1,
              "catname": catname,
              "description": description
            },
            success: function(data){
              if(data=="unable"){
                alert("Category already exists. Please try again.");
              }else{
                alert("Category successfully added");
                displayCurrentPage();
                popup_hide_two();
                document.getElementById("addcatform").reset();
                }
            }
          });
        }else{
          alert("failed: you have entered prohibited characters.");
        }

         
      }else{
        alert("Please fill up all fields");
      }

    });

    /*where you edit*/

   setTimeout(function(){
    document.getElementById("hide-card-wrapper").style.display = "block";
    document.getElementById("loading-screen-2").style.display = "none";
  }, 1000); 

});

function displayProductTable(){          
    $.ajax({
    url: "products/process.php",
    type: "POST",
    async: false,
    data: {
        "display_product_table": 1
    },
    success: function(data){
        $("#sub-container-rows").html(data);
    }
    });
}

function displayCategoryTable(){          
    $.ajax({
    url: "products/process.php",
    type: "POST",
    async: false,
    data: {
        "display_category_table": 1
    },
    success: function(data){
        $("#sub-container").html(data);
    }
    });
}
function formatSelection(val) {
  return val.id;
}
function displayCurrentPage(){
  var page = <?php echo (json_encode($_GET['sub']));?>;
    switch(page){
      case null:
      case 'prodlist': displayProductTable();
      break;
      case 'catlist': displayCategoryTable();
      break;
  }
}

</script>