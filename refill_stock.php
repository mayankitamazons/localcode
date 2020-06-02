<?php
include("config.php");
// print_R($_POST);
// die;
if(isset($_POST))
{
	if($_POST['refill'])
	{
		extract($_POST);
		$product_detail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='".$refill_product_id."'"));
		 $id=$product_detail['id'];
		 $child_exit = mysqli_query($conn, "SELECT id FROM products WHERE parent_id='".$id."'");
		 $child_count=mysqli_num_rows($child_exit);
		 $old_total=$product_detail['total_stock'];
		$old_pending=$product_detail['pending_stock'];
		if($refill_type=="add")
		{
			$new_total=$old_total+$product_count;
			$new_pending=$old_pending+$product_count;
			$type="in";
		} else if($refill_type=="deduct")
		{
			
			$new_total=$old_total - $product_count;
			$new_pending=$old_pending-$product_count;
			$type="out";
		}
		if($new_pending>0 && $refill_type=="add")
		$update=mysqli_query($conn, "UPDATE products SET on_stock='1',pending_stock='$new_pending',total_stock='$new_total' WHERE id='$refill_product_id'");
		else 
		$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_pending',total_stock='$new_total' WHERE id='$refill_product_id'");
		
		if($child_count>0)
		{
			while ($cr=mysqli_fetch_assoc($child_exit)){
				$c_id=$cr['id'];
				if($new_pending>0 && $refill_type=="add")
				$update=mysqli_query($conn, "UPDATE products SET on_stock='1',pending_stock='$new_pending',total_stock='$new_total' WHERE id='$c_id'");
				else
				$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_pending',total_stock='$new_total' WHERE id='$c_id'");
			}
		}
		$update=mysqli_query($conn, "UPDATE stock_notification SET status='1'  WHERE product_id='$refill_product_id'");
		if($comment=='')
			$comment="prorefill";
				if($update)
				{
					$qu="INSERT INTO `inventory_stock` (`product_id`, `stock_count`, `stock_type`,`comment`,`supplier_id`) VALUES ('$refill_product_id','$product_count', '$type', '$comment','$supplier_id')";
					mysqli_query($conn,$qu);
				}
	}
}
header("Location: stock.php");
?>