
<div class="card-wrapper profile-right-main-cont">
	<div class="card-style w-100">
    <div class="card-header">
      <ul>
        <li class="header-back"><a href="index.php?mod=profile&view=sli" class="card-button-back">< BACK</a></li>
      </ul>
    </div>
    <div class="card-content">
      <h3 style="width: 100%; margin: 20px 0px 8px 10px;float: left;">Top 5 Considered Sold</h3>
      <h5 style="margin-left: 10px;">Your top 5 list of most sold medicine</h5>
      <div class="card-table no-hover">
      <table class="">
        <tr style="font-weight: 500;">
          <td>Product Name</td>
          <td>Formulation</td>
          <td>Packaging</td>
          <td class="ta-right">Total Quantity</td>
        </tr>
      <?php
      $ret = $user->get_top_considered($_SESSION['clientid']);
      foreach($ret as $data){
      ?>
        <tr class="no-hover">
          <td><span style="font-weight: 500;"><?php echo $data['pro_brand'];?></span></br><?php echo $data['pro_generic'];?></td>
          <td><?php echo $data['pro_formulation'];?></td>
          <td><?php echo $data['pro_packaging'];?></td>
          <td class="ta-right"><?php echo $data['chuchu'];?></td>
        </tr>
      <?php
      }
      ?>
      </table>
    </div>
    </div>
  </div>
</div>