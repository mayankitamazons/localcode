<?php
include("config.php");
if(isset($_GET['st']) && $_GET['st'] == "Completed")
{
	$order_id=$_GET['order_id'];
	$total_amount=$_GET['amount'];
	$o_status=true;
	if($order_id)
	{
		$order_q = mysqli_query($conn, "SELECT * FROM order_list WHERE user_id='".$_SESSION['login']."' and id='$order_id'");
		$total_rows=mysqli_num_rows($order_q);
		if($total_rows>0)
		{
		  $odata=mysqli_fetch_assoc($order_q);
		  $o_status=$odata['status'];
		  if($o_status==3)
		  {
			mysqli_query($conn, "update order_list set status='2',paypal_tras_id='".$_GET['tx']."'");     
		  }
		  else
		  {
			$o_status=false;
			$msg="Something Went Wrong ,Contact to support with Tras id:".$odata['id'].$odata['invoice_no']; 
		  }  
		}
		else
		{
			$o_status=false;
			$msg="Order Not Completed, Try Again or try different method";
		}
	}
	else
	{
		$msg="Failed Try again";
		$o_status=false;
	}
	if($o_status)
	{
		header("Location: ".$site_url."/orderlist.php");
	}
	else
	{
		header("Location: ".$site_url."/view_merchant.php?msg=".$msg);
	}
}
?>