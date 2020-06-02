<?php 
include('config.php');
$merchant_id =  $_SESSION['login'];
$sync_date=date('Y-m-d H:i:s');
$query = "SELECT id,user_id,merchant_id,user_mobile,total_cart_amount,invoice_no,sync  FROM `order_list` WHERE created_on BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 day) and status = 1 and sync = 0 and merchant_id = '$merchant_id' and order_place = 'local'";
$orderData = mysqli_query($conn, $query);
$count = mysqli_num_rows($orderData);
if($count > 0){
	$conn2 = mysqli_connect("166.62.120.154", "koofamil_B277", "rSFihHas];1P", "koofamil_B277");
	if(!$conn2)
    {
	  $msg="Failed to Connect Live Database,Try Again";
	}
	else 
	{
		$orderIDs = array();
	    $insertQuery = "insert into `local_coin_sync`(`merchant_id`,`user_id`,`user_mobile`,`local_invoice_id`,`local_coin`) values ";
		$i = 0;
		while ($r=mysqli_fetch_assoc($orderData)){
			array_push($orderIDs,$r['id']);
			extract($r);
			$insertQuery.="($merchant_id,'$user_id','$user_mobile','$invoice_no','$total_cart_amount'),";
			$i++;
			echo "Local Invoice No ".$invoice_no." Sync with Point ".$total_cart_amount;
			echo "</br>";
		}
		$insertQuery = substr($insertQuery, 0, -1);
		$result =  mysqli_query($conn2, $insertQuery);
		if($result == true){
			$orderIDs = join(',', $orderIDs);
			mysqli_query($conn, "UPDATE order_list SET sync=1,sync_date='$sync_date' WHERE id IN(".$orderIDs.")");
			$msg="Total ".$i." Record Sync";
		}
		else
		{
			$msg="Fail to Update";
		}
	}
}
else{ $msg="No Record to Sync"; ?>		
	 	
	<?php } ?>
	<div><h2><?php echo $msg; ?></h2></div>
	<?php 
	echo "<meta http-equiv='refresh' content='5;url=dashboard.php'>";	
?>