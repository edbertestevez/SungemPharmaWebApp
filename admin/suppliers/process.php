<?php
include '../library/config.php';
include '../classes/class.suppliers.php';

$suppliers = new Suppliers();


if(isset($_POST['add_supplier'])){
	$name = $_POST['name'];
	$address = $_POST['address'];
	$contact = $_POST['contact'];
	$email = $_POST['email'];
	$result = $suppliers->add_supplier($name, $address, $contact, $email, $_SESSION['userid']);
	echo json_encode($result);
}

if(isset($_POST['get_specific_row'])){
	$id = $_POST['row_id'];
	$result = $suppliers->get_specific_supplier($id);
	echo $result;
}

if(isset($_POST['update_supplier'])){
	$id = $_POST['row_id'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$contact = $_POST['contact'];
	$email = $_POST['email'];
	$result = $suppliers -> update_supplier($id, $name, $address, $contact, $email, $_SESSION['userid']);
	echo json_encode($result);
}

if(isset($_POST['remove_supplier'])){
  if($_POST['remove_supplier']==1){
	$id = $_POST['row_id'];
	$result = $suppliers -> remove_supplier($id, $_SESSION['userid']);
  echo json_encode($result);
  }
  if($_POST['remove_supplier']==2){
    $id = $_POST['row_id'];
    $result = $suppliers -> activate_supplier($id, $_SESSION['userid']);
    echo json_encode($result);
  }
}

if(isset($_POST['display_supplier_table'])){
  $list = $suppliers->get_suppliers();
    if($list){
    ?>
        
        <div class="table-title">Official Suppliers of Sungem Pharma</div>
        <div id="button-container-2"><button onclick="popup_show()" id="add_product_butt">ADD SUPPLIER</button></div>
            <table class="table table-responsive table-striped table-hover" id="supplierlist">
              <thead>
              <tr>
                <th class="column_one">Supplier Name</th>
                <th class="column_two">Address</th>
                <th class="column_three">Contact No.</th>
                <th class="column_four">Email Address</th>
                <th class="column_four">Status</th>
              </tr>
              </thead>
              <tbody>
             <?php
                if($list){
                foreach($list as $values){
                  ?>
                  <tr class="row_selected" id=<?php echo $values['supplier_id'];?> onclick="edit_popup_show()">
                    <td class="column_one"><?php echo $values['supplier_name'];?></td>
                    <td class="column_two"><?php echo $values['address'];?></td>
                    <td class="column_three"><?php echo $values['contact_no'];?></td>
                    <td class="column_four"><?php echo $values['email'];?></td>
                    <td><?php if($values['status']==0){echo "DEACTIVATED";}else{echo "ACTIVATED";}?></td>
                  </tr>
                  <?php
                }
             }
              ?>
              </tbody>
            </table> 
            <?php
            }else{
              echo "NO SUPPLIERS AVAILABLE";
            }
            ?>
        <script>
        $('#supplierlist').dataTable(
            {language: {searchPlaceholder: "Search Supplier" }, "bLengthChange" : false,
            "columnDefs": [{"targets": [2,3],"orderable": false}], "pageLength":8, "aaSorting":[4, 'asc']}
        );
        </script>
        <?php
}

