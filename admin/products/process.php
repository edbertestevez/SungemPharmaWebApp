<?php
include '../library/config.php';
include '../classes/class.products.php';

$products = new Products();

if(isset($_POST['add_product'])){
	$brandname = $_POST['brand'];
	$genericname = $_POST['generic'];
	$category = $_POST['category'];
  $formulation = $_POST['formulation'];
	$packaging = $_POST['packaging'];
	$price = $_POST['price'];
	$reorder = $_POST['reorder'];
  $barcode = $_POST['barcode'];
	
	$result = $products->add_product($brandname, $genericname, $category, $formulation, $packaging, $price, $reorder, $barcode);
	echo $result;
}

if(isset($_POST['update_product'])){
	$id = $_POST['row_id'];
	$brandname = $_POST['brand'];
	$genericname = $_POST['generic'];
	$category = $_POST['category'];
  $formulation = $_POST['formulation'];
	$packaging = $_POST['packaging'];
	$price = $_POST['price'];
	$reorder = $_POST['reorder'];
	$barcode = $_POST['barcode'];

	$result = $products->update_product($id, $brandname, $genericname, $category, $formulation, $packaging, $price, $reorder, $barcode);
	echo json_encode($result);
}

if(isset($_POST['add_category'])){
	$catname = $_POST['catname'];
	$description = $_POST['description'];
	$result = $products->add_category($catname, $description);
	echo $result;
}

if(isset($_POST['remove_category'])){
	$id = $_POST['row_id'];
	$result = $products->remove_category($id);
	echo $result;
}

if(isset($_POST['remove_product'])){
  if($_POST['remove_product'] == 1){
    $id = $_POST['row_id'];
    $result = $products->remove_product($id);
    echo $result;
  }else{
    $id = $_POST['row_id'];
    $result = $products->activate_product ($id);
    echo $result;
  }
}

if(isset($_POST['update_category'])){
	$id = $_POST['row_id'];
	$catname = $_POST['catname'];
	$description = $_POST['description'];
	$result = $products->update_category($id, $catname, $description);
	echo $result;
}

if(isset($_POST['get_category_row'])){
	$id = $_POST['row_id'];
	$result = $products->get_specific_category($id);
	echo $result;
}

if(isset($_POST['get_product_row'])){
	$id = $_POST['row_id'];
	$result = $products->get_specific_product($id);
	echo $result;
}

if(isset($_POST['display_product_table'])){
	$list = $products->get_products();
        ?>
            <div class="table-title"> Product List of Sungem Pharma  </div>
            <div id="button-container" style=""><button onclick="popup_show()" id="add_product_butt">Add Product</button></div>
                <table class="table table-responsive table-striped table-hover" id="productlist">
                  <thead>
                  <tr>
                    <th class="column_one">Product Name</th>
                    <th class="column_two">Formulation</th>
                    <th class="column_three">Packaging</th>
                    <th class="ta-right column_four">Unit Price</th>
                    <th class="ta-right column_five">Reorder Level</th>
                    <th class="column_six">Category</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach($list as $values){
                      ?>
                      <tr id=<?php echo $values['pro_id'];?> class="product_selected" onclick="edit_popup_show()">
                        <td class="column_one"><?php echo "<b>".$values['pro_brand']."</b><br/>".$values['pro_generic']."";?></td>
                        <td class="column_two"><?php echo $values['pro_formulation'];?></td>
                        <td class="column_three"><?php echo $values['pro_packaging'];?></td>
                        <td class="ta-right column_four"><?php echo $values['pro_unit_price'];?></td>
                        <td class="ta-right column_five"><?php echo $values['pro_reorder_level'];?></td>
                        <td class="column_six"><?php echo $values['cat_name'];?></td>
                        <td><?php /*echo $values['product_status'];*/if($values['product_status']==0){echo "DEACTIVATED";}else{echo "ACTIVATED";}?></td>
                      </tr>
                      <?php
                    }
                  ?>
                  </tbody>
                </table> 
              </div>
            </div>
            <script>
            $('#productlist').dataTable(
                {language: {searchPlaceholder: "Search Product" }, "lengthMenu": [25,50,100],
                "columnDefs": [{"targets": [1,2,3,4],"orderable": false}]}
            );
            $('product_status_btn').click();
            </script>
        <?php
} 

if(isset($_POST['display_category_table'])){
	$list = $products->get_category();
        ?>
            <div class="table-title"> Product Categories</div>
             <div id="button-container-2" style=""><button onclick="popup_show_two()" id="add_product_butt">Add Category</button></div>
                <table class="table table-responsive table-striped table-hover" id="catlist">
                  <thead>
                  <tr>
                    <th class="column_one">Category Name</th>
                    <th class="column_two"> Description</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    foreach($list as $values){
                      ?>
                      <tr id=<?php echo $values['cat_id'];?> class="category_selected" onclick="edit_popup_show_two()">
                        <td class="column_one"><?php echo $values['cat_name'];?></td>
                        <td class="column_two"><?php echo $values['description'];?></td>
                      </tr>
                      <?php
                    }
                  ?>
                  </tbody>
                </table> 
            <script>
            $('#catlist').dataTable(
                {language: {searchPlaceholder: "Search Category" }, "pageLength": 20, "bLengthChange" : false,
                "columnDefs": [{"targets": [1],"orderable": false}]}
            );
            </script>
    <?php
}