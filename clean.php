<?php
	include("config.php");
	$user_id=5299;
	// delete all other merchant
	if($user_id)
	{
		
		$users=mysqli_query($conn, "delete  FROM users WHERE id not in('$user_id','3136')");  
		// delete order user too 
		// $users=mysqli_query($conn, "delete  FROM users WHERE ");
		$arrange_system=mysqli_query($conn, "delete  FROM arrange_system WHERE user_id != '$user_id'");
		$cashflow=mysqli_query($conn, "delete  FROM cash_flow WHERE user_id != '$user_id'");
		$cash_system=mysqli_query($conn, "delete  FROM cash_system WHERE user_id != '$user_id'");
		$category=mysqli_query($conn, "delete  FROM category WHERE user_id != '$user_id'");
		$cat_mater=mysqli_query($conn, "delete  FROM cat_mater WHERE UserID != '$user_id'");
		$order_list=mysqli_query($conn, "delete  FROM order_list WHERE merchant_id != '$user_id'");
		$order_list_temp=mysqli_query($conn, "delete  FROM order_list_temp WHERE merchant_id != '$user_id'");
		$order_varient=mysqli_query($conn, "delete  FROM order_varient WHERE merchant_id != '$user_id'");
		$products=mysqli_query($conn, "delete  FROM products WHERE user_id != '$user_id'");
		$pos_product_system=mysqli_query($conn, "delete  FROM pos_product_system WHERE user_id != '$user_id'");
		// section 
		$sections=mysqli_query($conn, "delete  FROM sections WHERE user_id != '$user_id'");
		
	}
echo "All Database setup";	
?>