<?php
include '../library/config.php';
include '../classes/class.clients.php';

$clients = new Clients();
?>
<?php

if(isset($_POST['update_area'])){
  $area_id = $_POST['area_id'];
  $area_name = $_POST['area_name'];
  $result = $clients->update_area($area_id, $area_name);
  echo $result;
}
if(isset($_POST['get_area_row'])){
  $area_id = $_POST['row_id'];
  $result = $clients->get_specific_area($area_id);
  echo $result;
}
if(isset($_POST['add_area'])){
  $area_name = $_POST['area_name'];
  $result = $clients->add_area($area_name);
  echo $result;
}

if(isset($_POST['add_client'])){
	$name = $_POST['name'];
	$area = $_POST['area'];
	$address = $_POST['address'];		
	$license = $_POST['license'];
	$contact = $_POST['contact'];
	$email = $_POST['email'];
	$result = $clients -> add_client($name, $area, $address, $license, $contact, $email);
	echo $result;
}

if(isset($_POST['get_specific_row'])){
	$id = $_POST['row_id'];
	$result = $clients->get_specific_client($id);
	echo $result;
}

if(isset($_POST['update_client'])){
	$id = $_POST['row_id'];
	$name = $_POST['name'];
	$area = $_POST['area'];
	$address = $_POST['address'];		
	$license = $_POST['license'];
	$contact = $_POST['contact'];
	$email = $_POST['email'];
	$result = $clients -> update_client($id, $name, $area, $address, $license, $contact, $email);
  echo $result;
}

if(isset($_POST['remove_client'])){
  if($_POST['remove_client'] == 1){
	  $id = $_POST['row_id'];
    $result = $clients -> remove_client($id);
  }
  if($_POST['remove_client'] == 2){
    $id = $_POST['row_id'];
    $result = $clients -> activate_client($id);
  }
}

if(isset($_POST['accept_request'])){
	$id = $_POST['row_id'];
	$resultval = $clients -> accept_request($id);
  $result = $clients -> delete_request($id);
	echo $resultval;
}

if(isset($_POST['decline_request'])){
	$id = $_POST['row_id'];
	$result = $clients -> delete_request($id);
}

if(isset($_POST['display_area_table'])){
        $list = $clients->get_area();
?>

    <div class="table-title">Client's Area List</div>
    <div id="button-container-2"><button onclick="popup_show_two()" id="add_product_butt">ADD AREA</button></div>
                <table class="table table-responsive table-striped table-hover" id="arealist">
                  <thead>
                  <tr>
                    <th>Area Name</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    if($list){
                    foreach($list as $values){
                      ?>
                      <tr class="area_selected" id=<?php echo $values['area_id'];?> onclick="edit_popup_show_two()">
                        <td><?php echo $values['area_name'];?></td>
                      </tr>
                      <?php
                    }
                    }
                  ?>
                  </tbody>
                </table>
                <script>

                $('#arealist').dataTable(
                {language: {searchPlaceholder: "Search" }, "pageLength": 10, "bLengthChange" : false}
            );
            </script>
<?php
}

if(isset($_POST['display_client_table'])){
        $list = $clients->get_clients();
?>

		<div class="table-title">Client List</div>
    <div id="button-container-2"><button onclick="popup_show()" id="add_product_butt">ADD CLIENT</button></div>
                <table class="table table-responsive table-striped table-hover" id="clientlist">
                  <thead>
                  <tr>
                    <th class="column_one">Client Name</th>
                    <th class="column_two">Area</th>
                    <th class="column_three">Address</th>
                    <th class="column_four">Contact No.</th>
                    <th class="column_five">Email Address</th>
                    <th class="column_five">Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  	if($list){
                    foreach($list as $values){
                      ?>
                      <tr class="row_selected" id=<?php echo $values['client_id'];?> onclick="edit_popup_show()">
                        <td class="column_one"><?php echo $values['client_name'];?></td>
                        <td class="column_two"><?php echo $values['area_name'];?></td>
                        <td class="column_three"><?php echo $values['address'];?></td>
                        <td class="column_four"><?php echo $values['contact_no'];?></td>
                        <td class="column_five"><?php echo $values['email'];?></td>
                        <td><?php if($values['client_status'] == 0){echo "DEACTIVATED";}else{echo "ACTIVATED";}?></td>
                      </tr>
                      <?php
                  	}
                  	}
                  ?>
                  </tbody>
                </table>
                <script>

                $('#clientlist').dataTable(
                {language: {searchPlaceholder: "Search Client" }, "bLengthChange" : false,
                "columnDefs": [{"targets": [2,3,4],"orderable": false}]}
            );
            </script>
<?php
}
if(isset($_POST['display_requests_table'])){
	$list = $clients->get_requests();
?>       
<div class="table-title add-marg">Pending Online Registration Requests</div><br/><br/>
                <table class="table table-responsive table-striped table-hover" id="requestlist">
                  <thead>
                  <tr>
                    <th class="column_one">Client Name</th>
                    <th class="column_two">Area</th>
                    <th class="column_three">Address</th>
                    <th class="column_four">LTO No.</th>
                    <th class="column_five">Contact No.</th>
                    <th class="column_six">Email Address</th>
                    <th class="">Action</th>
                    <th class=""></th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  	if($list){
                    foreach($list as $values){
                      ?>
                      <tr>
                        <td class="column_one"><?php echo $values['name'];?></td>
                        <td class="column_two"><?php echo $values['area_name'];?></td>
                        <td class="column_three"><?php echo $values['address'];?></td>
                        <td class="column_four"><?php echo $values['lto_no'];?></td>
                        <td class="column_five"><?php echo $values['contact_no'];?></td>
                        <td class="column_six"><?php echo $values['email'];?></td>
                        <td class="ta-center"><input type="button" id=<?php echo $values['request_id'];?> class="material-button-main accept_request" value="Accept"/></td>
                       	<td class="ta-center"><input type="button" id=<?php echo $values['request_id'];?> class="material-button-main decline_request red" value="Decline"/></td>
                       </tr>
                      <?php
                  	}
                  	}
                  ?>
                  </tbody>
                </table>
                <script>

                $('#requestlist').dataTable(
                {language: {searchPlaceholder: "Search Client" }, "pageLength":10, "bLengthChange" : false, "bFilter":false, "bSort":false}
            );
            </script>
<?php
}
exit();
?>