<?php
include '../library/config.php';
include '../classes/class.medreps.php';

$medreps = new Medreps();

if(isset($_POST['deactivate_medrep'])){
  $medrep_id = $_POST['medrep_id'];
  $result = $medreps->deactivate_medrep($medrep_id);
  echo $result;
}

if(isset($_POST['activate_medrep'])){
  $medrep_id = $_POST['medrep_id'];
  $result = $medreps->activate_medrep($medrep_id);
  echo $result;
}


if(isset($_POST['display_monthly_performance'])){
    $list = $medreps->get_medrep_monthly();
    ?>
    <div class="table-title center add-marg">Medical Representatives Performance (<?php echo date('F')." ".date('Y'); ?>)</div>
    <br/><br/>
    <table class="table table-responsive table-striped table-hover" style="cursor: none;" id="performancelist">
    <thead>
    <tr>
        <th class="normal center column_one">Rank</th>
        <th class="normal center">Medical Representative</th>
        <th class="normal center column_three">Orders</th>
        <th class="normal center column_four">Deliveries Made</th>
        <th class="normal center column_five">Medicines Sold<br/>(Invoice Items)</th>
        <th class="normal center column_six">Total Received Sales<br/>(Payment)</th>
        <th class="normal center column_seven">Commission Received<br/>(Based on Received Sales)</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if($list){
        $rank=1;
        foreach($list as $values){
            ?>
            <tr>
                <td class="center column_one"><?php echo $rank;?></td>
                <td class="center"><?php echo $values['mr_firstname']." ".$values['mr_lastname'];?></td>
                <td class="center column_three">
                <?php 
                  $orders_made = $medreps->medrep_orders_monthly($values['medrep_id']);
                  if($orders_made!=""){
                    echo $orders_made;
                  }else{
                    echo "0";
                  }
                ?>  
                </td>
                <td class="center column_four">
                <?php 
                  $delivery_made = $medreps->medrep_delivery_monthly($values['medrep_id']);
                  if($delivery_made!=""){
                    echo $delivery_made;
                  }else{
                    echo "0";
                  }
                ?>  
                </td>
                <td class="center column_five">
                <?php 
                  $product_sold = $medreps->medrep_prod_monthly($values['medrep_id']);
                  if($product_sold!=""){
                    echo $product_sold;
                  }else{
                    echo "0";
                  }
                ?>    
                </td>
                <td class="center column_six">
                <?php 
                $row_sales = $values['actual_sales'];
                    if($row_sales!=""){
                        echo "&#8369;".number_format($row_sales, 2, '.', ',');;
                    }else{
                        echo "&#8369;"."0.00";
                    }
                ?>
                </td>
                <td class="center column_seven">
                <?php
                //BASED ON COMMISSION LEVEL
                if($row_sales>=500000){
                    echo "<b>"."&#8369;".number_format($row_sales*.05, 2, '.', ',')."</b>";
                }else if($row_sales>=400000){
                    echo "<b>"."&#8369;".number_format($row_sales*.04, 2, '.', ',')."</b>";
                }else if($row_sales>=300000){
                    echo "<b>"."&#8369;".number_format($row_sales*.03, 2, '.', ',')."</b>";
                }else if($row_sales>=200000){
                    echo "<b>"."&#8369;".number_format($row_sales*.02, 2, '.', ',')."</b>";
                }else if($row_sales<200000){
                    echo "<b>"."&#8369;".number_format($row_sales*.01, 2, '.', ',')."</b>";
                }
                ?>
                </td>
            </tr>
            <?php
            $rank++;
        }
    }
    ?>
    </table>
    </tbody>
    <?php
}

if(isset($_POST['display_assignments_table'])){
    $list = $medreps->get_assignments();
?>
 <div class="table-title add-marg">Medical Representatives Assignments</div>
    <table class="table table-responsive table-striped table-hover" style="cursor: none;" id="assignmentlist">
    <thead>
        <tr>
            <th class="column_one">Client Name</th>
            <th class="column_three">Area</th>
            <th class="column_four">Address</th>
            <th class="column_five">Assigned Medical Representative</th>
            <th class="ta-center">Manage</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if($list){
    foreach($list as $values){
    ?>
    <tr>
        <td class="column_one"><?php echo $values['client_name'];?></td>
        <td class="column_three"><?php echo $values['area_name'];?></td>
        <td class="column_four"><?php echo $values['address'];?></td>
        <td class="column_five"><?php if($values['mr_firstname']==null || $values['mr_middlename']==null || $values['mr_lastname']==null){
            echo '<b style="color:red";>No assigned Med Rep</b>';
            $assign="Assign";
            $style = '"background-color:#4e850e;"';
        }else{
            echo $values['mr_firstname']." ".$values['mr_middlename']." ".$values['mr_lastname'];
            $assign="Change";
            $style = '"background-color:#115703;"';
        }
        ?>
        </td>
        <td class="ta-center"><input type="button" id=<?php echo $values['client_id'];?> onclick="edit_popup_show_two()" class="material-button-main manage_assign" value=<?php echo $assign;?>></td>
    </tr>
    <?php
    }
    }
    ?>
    </tbody>
    </table>
    <script>
        $('#assignmentlist').dataTable(
            {language: {searchPlaceholder: "Search" }, "pageLength": 10, "bLengthChange" : false,
            "columnDefs": [{"targets": [2, 4],"orderable": false}]}
        );
    </script>   
<?php
}

if(isset($_POST['display_medrep_table'])){
    $list = $medreps->get_medreps();
?>
<div class="table-title">Medical Representatives of Sungem Pharma</div>
<div id="button-container-2"><button onclick="popup_show()" id="add_product_butt">ADD MEDREP</button></div>
    <table class="table table-responsive table-striped table-hover" id="medreplist">
    <thead>
        <tr>
            <th class="column_one">Medrep Name</th>
            <th class="column_two">Address</th>
            <th class="column_three">Contact No.</th>
            <th class="column_four">Email Address</th>
             <th clas="">Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach($list as $values){
    ?>
    <tr class="row_selected" id=<?php echo $values['medrep_id'];?> onclick="edit_popup_show()">
        <td class="column_one"><?php echo $values['mr_firstname']." ".$values['mr_middlename']." ".$values['mr_lastname'];?></td>
        <td class="column_two"><?php echo $values['mr_address'];?></td>
        <td class="column_three"><?php echo $values['mr_contact_no'];?></td>
        <td class="column_four"><?php echo $values['mr_email'];?></td>
        <td ><?php 
       if(($values['status']) == 1){
        ?>
        activated
        <?php
       }else{
        ?>
       deactivated
        <?php
       }
        ?></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
    </table>
    <script>
        $('#medreplist').dataTable(
            {language: {searchPlaceholder: "Search Medical Rep" }, "bLengthChange" : false,
            "columnDefs": [{"targets": [2,3],"orderable": false}], "aaSorting":[4, 'asc']}
        );
    </script>
<?php
}

if(isset($_POST['get_assign_info'])){
    $client_id = $_POST['row_id'];
    $result = $medreps->get_assign_info($client_id);
    ?>
        <h6>Client: <?php echo $result['client_name'];?></h6>
        <h6>Area: <?php echo $result['area_name'];?></h6>
        <h6>Address: <?php echo $result['address'];?></h6>
        <h6>Current Med Rep: <?php 
        if($result['mr_firstname']==null || $result['mr_middlename']==null || $result['mr_lastname']==null){
            echo "None";
        }else{
            echo $result['mr_firstname']." ".$result['mr_middlename']." ".$result['mr_lastname'];
        }?>
        </h6>
        <hr>
    <?php   
}

if(isset($_POST['add_medrep'])){
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $result = $medreps->add_medrep($firstname, $middlename, $lastname, $address, $contact, $email, $username, $password, $_SESSION['userid']);
    echo json_encode($result);
}

if(isset($_POST['get_specific_row'])){
    $id = $_POST['row_id'];
    $result = $medreps->get_specific_medrep($id);
    echo $result;
}

if(isset($_POST['update_assignment'])){
    $client_id = $_POST['row_id'];
    $medrep_id = $_POST['medrep_id'];
    $result = $medreps->update_assignment($client_id, $medrep_id);
    echo $result;
}

if(isset($_POST['update_medrep'])){
    $id = $_POST['row_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $result = $medreps->update_medrep($id, $firstname, $middlename, $lastname, $address, $contact, $email, $_SESSION['userid']);
    echo $result;
}

if(isset($_POST['remove_medrep'])){
    $id = $_POST['row_id'];
    $result = $medreps -> remove_medrep($id);
}
