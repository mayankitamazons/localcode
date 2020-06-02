<?php
include("config.php");
$s_id=$_POST['s_id'];
if($s_id)
{
	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id='".$s_id."'"));
	
	if($row)
	{
		$product_ids = explode(",",$row['product_id']);
		$quantity_ids = explode(",",$row['quantity']);
		$amount_val = explode(",",$row['amount']);
		$product_code = explode(",",$row['product_code']);
		$amount_data = array_combine($product_ids, $amount_val);
		$total_data = array_combine($quantity_ids, $amount_val);
		 $remark_ids = explode("|",str_replace("_", " ", $row['remark']));
		// echo "Product Name </br>";
		$i=0;
		 $varient_type=$row['varient_type'];
			if($varient_type)
			{
				$v_str=$row['varient_type'];
				$v_array=explode("|",$v_str);
			}
		// print_R($v_array);
		foreach ($product_ids as $key )
        {
			if(is_numeric($key))
            {
                $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$key."'"));
				echo "<b>".$product['product_name'].'  ,qty-'.$quantity_ids[$i].'</b><br>';
			}
			else
			{
               echo $key.'<br>';
			}
			if($remark_ids[$i])
			{
				echo "Remark :".$remark_ids[$i].'<br>';
			}
			if($v_array[$i])
			{
				$v_match=$v_array[$i];
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
					echo "&nbsp;&nbsp;&nbsp;&nbsp;"."-".$srow['name'];
					echo "</br>";
					}
			}
			echo "<hr>";
			$i++;
		}
		
	}
}
?>