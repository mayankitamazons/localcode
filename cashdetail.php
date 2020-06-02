<?php 
include("config.php");
 $cash_id=$_POST['cash_id'];
$cquery = mysqli_query($conn, "SELECT * FROM cash_flow WHERE cash_id='".$cash_id."'");
$to=mysqli_fetch_assoc($cquery);
print_R($to);
die;
$totalc=mysqli_num_rows($query);
?>
<?php if($totalc>0){ ?>
  <table class="table">
		<thead>
						  <tr>
							<th>Amount</th>
							<th>Cash Type</th>
							<th>Invoice No</th>
							<th>Paid From</th>
							<th>Cash Description</th>
							<th>Remark</th>
							<th>Trascation Time</th>
							
						  </tr>
						</thead>
						<?php  ?>
						<tbody>
						<?php   while ($p=mysqli_fetch_assoc($cquery)){?>
						   <tr>
						   <td><?php echo $p['amount']; ?></td>
						   <td><?php echo $p['cash_type']; ?></td>
						   <td><?php echo $p['invoice_no']; ?></td>
						   <td><?php echo $p['paid_from']; ?></td>
						   <td><?php echo $p['cash_description']; ?></td>
						   <td><?php echo $p['remark']; ?></td>
						   <th><?php echo date("Y-m-d H:i:s", $p['tras_utc']); ?></th>
							
						   </tr>
						<?php } ?>
						</tbody>
	</table>
<?php  } else { echo "No data";}?>
	