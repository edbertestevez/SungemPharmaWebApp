<div class="card-wrapper profile-right-main-cont">
	<div class="card-style w-100">
		<div class="card-header">
			<ul style="width: 60%;">
				<li class="header-title"><h3 style="float: left;">Payments</h3></li>
				<li>
				</li>
			</ul>
			<div style="padding-top: 8px;float: left; width: 40%;text-align: right;">
						<span class="material-label">Total Remaining Balance</span></br>
						<span class="balance-counter"><?php $balance = $user->get_total_balance($_SESSION['clientid']); echo number_format ((float)$balance, 2, '.', ',');?></span>
			</div>
			<h5 style="width: 100%; display: inline-block;margin-bottom: 20px; margin-top: 0;">A list of all your unpaid invoices</h5>
		</div>
<table class="table table-striped font-roboto" id="table-sli">
	<thead>
		<tr>
					 <th class="table_one_title_profile">Invoice</th>
					 <th>Order</th>
        	<th class="table_two_title_profile">Due Date</th>
        	<th class="table_three_title_profile">Total Amount</th>
        	<th class="table_four_title_profile">Amount Paid</th>
        	<th class="table_five_title_profile">Remaining Balance</th>
      	</tr>
	</thead>
	<tbody>
	<?php
	$list = $user->get_paylist($_SESSION['clientid']);
	if($list){
		foreach($list as $pay){?>
    	<tr style="height: 48px;">
					 <td class="table_one_cont_profile"><span style="font-weight: 500;"><?php echo $pay['invoice_id'];?></span></td>
					 <td><?php echo $user->get_order_by_invoice($pay['invoice_id']);?></td>
       		<td class="table_two_cont_profile"><?php $date = new DateTime($pay['date_due']); echo $date->format('F j, Y');?></td>
       		<td class="table_three_cont_profile"><?php echo number_format ((float)$pay['total_amount'], 2, '.', ',');?></td>
       		<td class="table_four_cont_profile"><?php echo number_format ((float)$pay['amount_paid'], 2, '.', ',');?></td>
       		<td class="table_five_cont_profile"><span style="font-weight: 500;"><?php echo number_format ((float)$pay['amount_remaining'], 2, '.', ',');?></span></td>
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
  $('#table-sli').dataTable({

	});
</script>