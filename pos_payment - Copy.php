<?php
include("config.php");
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($_POST);
// die;
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
$_SESSION['pos']="y";       
 $_SESSION['mm_id']= $loginidset;
 $merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$loginidset."'"));
$stock_inventory=$merchant_data['stock_inventory'];
$user_roles=$merchant_data['user_roles'];
	// $_SESSION['mm_id']= $_SESSION['login']; //merchant id
    $date = date('Y-m-d H:i:s');
	$location =$_POST['location'];
	$status =$_POST['status'];
	
	 $table_type =$_POST['table'];
	// $cust_id =$_POST['cust_id'];
	 $section_type =$_POST['Section'];
	 $pro_id = implode(',',$_POST['pro_id']);
	$name = implode(',', $_POST['name']);
	// if($_POST['remark_val'])
	// $remark_val = implode(',', $_POST['remark_val']);
	// else
	// $remark_val='';	
	if(count($_POST['remark_val'])>0)
	{
	   foreach($_POST['remark_val'] as $sremark)
	   {
		$remark_val.=$sremark."|";
	   }		   
	}
	else
	{
		$remark_val='';
	}
	// echo $remark_val;
	// die;
	 $price = implode(',', $_POST['price']);
	 $subpro_price = implode(',', $_POST['subpro_price']);
	 $qty = implode(',', $_POST['qty']);
	 $varient_type = $_POST['varient_type'];
	 $select_wallet = $_POST['select_wallet'];
	 $wallet_paid_amount = $_POST['wallet_paid_amount'];
	 $t_price=[];
	 $c=0;
	 // print_R($_POST['subpro_price'][0]);
	 // die;
	 foreach($_POST['price'] as $p)
	 {
		
		 $t_price[$c]=$p+$_POST['subpro_price'][$c];
		$c++;
	 }
	 $price=$t_price;
	 $price = implode(',', $price);
	 // print_R($price);
	 // die;
	 if($select_wallet!='-1')
		 $pay_mode=$select_wallet;
	 else
		 $pay_mode="cash";
	 if($varient_type)
		{ 
			$vcount=0;
			// print_R($varient_type);
			// die;
			
			foreach($varient_type as $v)
			{
				// print_R($v);
				if($vcount==0)
				{
					$v_str=$v;
				}
				else
				{
				  $v_str=$v_str."|".$v;
				}
				$vcount++;
			}
			
		}
	  $p_code = implode(',',$_POST['p_code']);
    
	 // $remark_val = implode(',',$_POST['remark_val']);
	// if($remark_val==",")
		// $remark_val='';
	 $m_id=$_SESSION['mm_id'];
	 $sql = "SELECT MAX(invoice_seq) invoice_seq
            FROM order_list
            WHERE merchant_id = '$m_id'";
	 $invoice_seq = mysqli_fetch_assoc(mysqli_query($conn, $sql))['invoice_seq'];
			
			// $inv=explode('_',$invoice_no);
			// $invoice_no=$inv[0];
			if($invoice_seq == NULL) $invoice_seq = 1;
			else $invoice_seq += 1;
			$invoice_no=$invoice_seq."L";
			$invoice_seq=$invoice_seq;

    $discount_amount=$_POST['discount_amount'];
    $paid_amount_pos=$_POST['paid_amount_pos'];
    $change_pos=$_POST['change_pos'];
	if($discount_amount)
		$discount_amount=number_format($discount_amount,2);
	
	 $sqlFinalIns = "INSERT INTO order_list SET wallet='$pay_mode',wallet_paid_amount='$wallet_paid_amount',paid_amount_pos='$paid_amount_pos',change_pos='$change_pos',discount_amount='$discount_amount',status='$status',order_place='poslocal',product_id='$pro_id',  user_id='$m_id', merchant_id='$m_id', quantity='$qty', amount='$price', remark='$remark_val',table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',invoice_seq='$invoice_seq',varient_type='$v_str',product_code='$p_code'";
  
		 $test_method = mysqli_query($conn, $sqlFinalIns);
	    	if($test_method)
	    	{   
				$order_id = mysqli_insert_id($conn);
				$vs=0;
		  $v_order=1;
		  $parray=explode(",",$pro_id);
			 $qarray=explode(",",$qty);
	  $v_order=1;
	  $ps=0;
	   // print_R($stock_inventory);
	   // die;
		if($stock_inventory=="on")
		{
			// update stock value 
			foreach($parray as $s_id)
			{
				  $qty_s=$qarray[$ps];
				
				$sdetail= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='".$s_id."'"));
				$parent_id=$sdetail['parent_id'];
				$stock_value=$sdetail['stock_value'];
				
				if($parent_id)
				{
					$sdetail= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='".$parent_id."'"));	
					$single_p_id=$parent_id;
				}
				else
				{
					$single_p_id=$s_id;
				}
				
				 $maintain_stock=$sdetail['maintain_stock'];
				
				if($maintain_stock=="on")
				{
					$old_pending_stock=$sdetail['pending_stock'];
					$p_name=$sdetail['product_name'];
					$reorder_level=$sdetail['reorder_level'];
					
					if($stock_value>1)
					{
						$qty_s=$stock_value*$qty_s;
					}
					 $new_stock=$old_pending_stock-$qty_s;
					// echo $new_stock;
					// die;
					if($new_stock<=0)
					{  
						
						$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_stock',on_stock='0' WHERE id='$single_p_id'");
							
						$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_stock',on_stock='0' WHERE id='$s_id'");
						$noti=$p_name."is Reached Below Reorder Level,Refill it";
						mysqli_query($conn, "INSERT INTO `stock_notification` (`product_id`, `product_name`, `current_stock`, `reorder_level`, `notification`,`merchant_id`) VALUES ('$single_p_id', '$p_name', '$new_stock', '$reorder_level','$noti','$merchant_id')");
					
					}
					else
					{
						$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_stock' WHERE id='$single_p_id'");	
					}  
					if($update)
					{
						$qu="INSERT INTO `inventory_stock` (`product_id`, `stock_count`, `stock_type`, `order_id`, `comment`,`child_id`) VALUES ('$single_p_id','$qty_s', 'out', '$order_id', 'productsell','$s_id')";
						mysqli_query($conn,$qu);   
					}
				}
				$ps++;
			}
		}
		  if($varient_type)
		  {
			  
			$vsql = "SELECT MAX(id) v_id FROM order_varient";
			$v_count = mysqli_fetch_assoc(mysqli_query($conn, $vsql))['v_id'];
			if($v_count == NULL) $v_count = 1;
			else $v_count += 1;
			$v_no=$v_count;
			foreach($varient_type as $vr)
			{
				
				 $product_id=$parray[$vs];
				
				if($vr)
				{
					
					$v_match=$vr;
					 $v_match = ltrim($v_match, ',');
					  $v_match = rtrim($v_match, ',');
					
					// echo "SELECT * FROM sub_products WHERE id  in ($v_match)";
					$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
					while ($srow=mysqli_fetch_assoc($sub_rows)){
						// print_R($srow);
						// die;
						$v_id=$srow['id'];
						 $query="INSERT INTO order_varient SET product_id='$product_id', varient_id='$v_id', invoice_no='$invoice_no',order_id='$order_id',merchant_id='$m_id',v_order='$v_order',v_code='$v_no'";
						
						 mysqli_query($conn,$query);
						$v_count++;	
						$v_no++;
						}  
				}
				$vs++;	
				$v_order++;
			}
		  }
		  // die;
		     if($user_roles==5)
	    		header("Location: ".$site_url."/orderview-staff.php");
			else 
			  header("Location: ".$site_url."/orderview.php");	
	    	}  
	       else{
	       	return false;
	       }
?>