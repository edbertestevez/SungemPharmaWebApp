
<div style="float: right;" class="card-wrapper-primary profile-right-main-cont">
<div class="card-style w-100">


<div class="card-header">
<ul>
	<li class="header-title"><h3 style="float: left;">Stock Level Inventory (Consignment)</h3></li>
	<!--<li><a class="card-button" href="index.php?mod=profile&view=sli&sub=monitoring">Monitor</a></li>-->
	<li><a class="card-button" href="index.php?mod=profile&view=sli&sub=topcon">Top 5 Consigned</a></li>
</ul>
<h5 style="margin-bottom: 20px; margin-top: 0;">A list of your last monitored consignment inventory</h5>
</div>

<table class="table table-striped font-roboto" id="table-consignment">
<thead>
	<tr>
				 <th class="table_one_title_profile">Product Name</th>
				<th class="table_two_title_profile">Formulation</th>
				<th class="table_three_title_profile">Packaging</th>
				<th class="table_four_title_profile">Lot #</th>
				<th class="table_five_title_profile">Expiry Date</th>
				<th class="table_five_title_profile">Quantity</th>
			</tr>
</thead>
<tbody>
<?php
$list = $user->get_sli($_SESSION['clientid']);
if($list){
	foreach($list as $sli){?>
		<tr>
				 <td class="table_one_cont_profile"><span style="font-weight: 500;"><?php echo $sli['brand'];?></span></br><?php echo $sli['generic'];?></td>
				 <td class="table_two_cont_profile"><?php echo $sli['formu']?></td>
				 <td class="table_three_cont_profile"><?php echo $sli['pack'];?></td>
				 <td class="table_four_cont_profile"><?php echo $sli['lotno'];?></td>
				 <td class="table_five_cont_profile"><?php $date = new DateTime($sli['expiry']); echo $date->format('F j, Y');?></td>
				 <td><?php echo $sli['quantity'];?></td>
			</tr>
	<?php
		}
}
	?>
</tbody>
</table>
</div>
</div>

<div style="float: right; margin: 0;" class="card-wrapper-secondary profile-right-main-cont">
	<div class="card-style w-100">


<div class="card-header">
	<ul>
		<li class="header-title"><h3 style="float: left;">Considered Sold</h3></li>
		<!--<li><a class="card-button" href="index.php?mod=profile&view=sli&sub=monitoring">Monitor</a></li>-->
		<li><a class="card-button" href="index.php?mod=profile&view=sli&sub=topsold">Top 5 Sold</a></li>
	</ul>
<h5 style="margin-bottom: 20px; margin-top: 0;">Your considered sold items</h5>
</div>

<table class="table table-striped font-roboto" id="table-sold">
	<thead>
		<tr>
 			    <th class="table_one_title_profile">Product Name</th>
        	<th class="table_two_title_profile">Formulation</th>
        	<th class="table_three_title_profile">Packaging</th>
					<th class="table_five_title_profile">Quantity</th>
      	</tr>
	</thead>
	<tbody>
	<?php
	$list = $user->get_consideredsold($_SESSION['clientid']);
	if($list){
		foreach($list as $sli){?>
    	<tr>
       		<td class="table_one_cont_profile"><span style="font-weight: 500;"><?php echo $sli['pro_brand'];?></span></br><?php echo $sli['pro_generic'];?></td>
       		<td class="table_two_cont_profile"><?php echo $sli['pro_formulation']?></td>
       		<td class="table_three_cont_profile"><?php echo $sli['pro_packaging'];?></td>
					 <td><?php echo $sli['chuchu'];?></td>
        </tr>
    <?php
    	}
	}
    ?>
	</tbody>
</table>
</div>
</div>
<script>
  $('#table-consignment').dataTable({
		language: {searchPlaceholder: "Search" }, "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ]
	});
	$('#table-sold').dataTable({
		language: {searchPlaceholder: "Search" }, "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ]
	});
</script>