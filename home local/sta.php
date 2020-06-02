<?php
include('config.php');
extract($_POST);
if($fix_invoice_id)
{
	$discount=$fix_discount+$discountper;
	$wallet=$select_wallet_2;
	$q="UPDATE order_list SET wallet='$wallet',discount_amount='$discount',wallet_paid_amount='$wallet_paid_amount',change_pos='$change_pos' where invoice_no='$fix_invoice_id'";
	mysqli_query($conn,$q);
	
}
header('Location:orderview.php');
?>