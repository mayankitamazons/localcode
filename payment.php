<?php
include("config.php");
// print_R($_FILES);
// print_R($_POST);
// die;  
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
$merchant_id=$_POST['m_id'];
$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$merchant_id."'"));
$stock_inventory=$merchant_data['stock_inventory'];
// print_R($merchant_id);
// die;
if( $_POST['wallet'] == "MYR" )
{
	$verify_code = addslashes($_POST['verify_code']);
	$o_id = addslashes($_POST['o_id']);
	$m_id = addslashes($_POST['m_id']);
	$amount = addslashes($_POST['amount']);
	$wallet = 'MYR';
	$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
	$m_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$m_id."'"));


	if(isset($balance['fund_password']))
	{

		if($balance['fund_password'] != $verify_code)
		{
			$error .= "Verification Code is Invalid.<br>";
			$flag = true;
		}

	}
 if($balance['balance_myr'] < $amount)
		{
			echo $error .= "Insufficient Balance In Your Wallet, Recharge Your Wallet First.";
			$flag = true;
		}

	if($flag == false)
	{


			$sender_new_balance = $balance['balance_myr'] - $amount;
			$reciever_new_balance = $m_balance['balance_myr'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='".$_SESSION['login']."'");
			mysqli_query($conn, "UPDATE users SET balance_myr='$reciever_new_balance' WHERE id='$m_id'");
			mysqli_query($conn, "UPDATE order_list_temp SET wallet='$wallet' WHERE id='$o_id'");
			
	}

}

else
{
	 if(isset($_FILES["paymentproff"]) && $_FILES["paymentproff"]["error"] == 0){
		
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg","JPEG" => "image/JPEG", "gif" => "image/gif", "png" => "image/png");
       $filename = $_FILES["paymentproff"]["name"];
        $filetype = $_FILES["paymentproff"]["type"];
        $filesize = $_FILES["paymentproff"]["size"];
		 $file_ext=strtolower(end(explode('.',$_FILES['paymentproff']['name'])));
      
        $payment_image= $filename;
  
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 5MB maximum
        //~ $maxsize = 5 * 1024 * 1024;
        //~ if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("screenshot/" . $_FILES["paymentproff"]["name"])){
                echo $_FILES["paymentproff"]["name"] . " is already exists.";
            } else{
                move_uploaded_file($_FILES["paymentproff"]["tmp_name"], "/home/koofamilies/public_html/screenshot/" . $_FILES["paymentproff"]["name"]);
               // echo "Your file was uploaded successfully.";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } 
	
	if(isset($_POST['guest_id']))
{
	$guest_user_id=$_POST['guest_id'];
	$order_id=$_POST['guest_order_id'];
	 // $order_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT merchant_id FROM order_list_temp WHERE id='".$order_id."'"));
			  // $merchant_id=$order_data['merchant_id'];
			
			 
			   
	$order1 = explode("_",$_POST['wallet']);

	 $wallet = $order1['0'];
   mysqli_query($conn, "UPDATE order_list_temp SET wallet='$wallet',payment_image='$payment_image' WHERE id='$order_id'");
	$session_id = session_id();
	$finalorder_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `order_list_temp` WHERE session_id = '$session_id' "));
	  $varient_type = $finalorder_total['varient_type'];
	 $pro_id = $finalorder_total['product_id'] ;
	 $user_id = $finalorder_total['user_id'] ;
	 $m_id = $finalorder_total['merchant_id'] ;
	 $qty_list = $finalorder_total['quantity'] ;
	 $p_price = $finalorder_total['amount'] ;
	 $p_code = $finalorder_total['product_code'] ;
	 $option = $finalorder_total['remark'] ;
	 $location = $finalorder_total['location'] ;
	 $table_type = $finalorder_total['table_type'] ;
	  $section_type = $finalorder_total['section_type'] ;
	 $date = $finalorder_total['created_on'] ;
	 $payment_image = $finalorder_total['payment_image'] ;
	 $wallet = $finalorder_total['wallet'] ;
	  $user_name = $finalorder_total['user_name'] ;
	 $user_mobile = $finalorder_total['user_mobile'] ;
	 $invoice_no = $finalorder_total['invoice_no'] ;
	 $invoice_seq = $finalorder_total['invoice_seq'] ;
	 if($user_id ==0){
	 $user_id = $_SESSION['login'];
	 }
	    
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
		
		$vsql = "SELECT MAX(id) v_id FROM order_varient";
		$v_count = mysqli_fetch_assoc(mysqli_query($conn, $vsql))['v_id'];
		if($v_count == NULL) $v_count = 1;
		else $v_count += 1;
		$v_no=$v_count."L";
		
  if(empty($guest_user_id)){
      
     $guest_user_id =  $_SESSION['login'] ; 
  }
     $_SESSION['login']=$guest_user_id;  
	 $merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$m_id."'"));
		$stock_inventory=$merchant_data['stock_inventory'];
	  $sqlFinalIns = "INSERT INTO order_list SET user_name='$user_name',user_mobile='$user_mobile',payment_image='$payment_image',wallet='$wallet',varient_type='$varient_type',product_id='$pro_id',  user_id='$guest_user_id', merchant_id='$m_id', quantity='$qty_list', amount='$p_price',product_code='$p_code', remark='$option', location='".$location."', table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',invoice_seq='$invoice_seq'";
    
	  $test_method = mysqli_query($conn, $sqlFinalIns);
	   $order_id = mysqli_insert_id($conn);
	  $v_array=explode("|",$varient_type);
	  $vs=0;
	  $parray=explode(",",$pro_id);
	  $qarray=explode(",",$qty_list);
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
	  foreach($v_array as $vr)
		{
			$product_id=$parray[$vs];
			if($vr)
			{
				$v_match=$vr;
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
					$v_id=$srow['id'];
					 mysqli_query($conn, "INSERT INTO order_varient SET product_id='$product_id', varient_id='$v_id', invoice_no='$invoice_no',order_id='$order_id',merchant_id='$m_id',v_order='$v_order',v_code='$v_no'");
					$v_count++;	
					}  
			}
			$vs++;	
			$v_order++;
		}
	  $sqlDtemp = "DELETE FROM `order_list_temp` WHERE session_id = '$session_id'  ";
		mysqli_query($conn, $sqlDtemp);
		
	  
	if($_POST['member'] == '1'){
         header("Location: ".$site_url."/orderlist.php");
     } else {
		header("Location: " .$site_url . "/order_guest.php");
     }	
			 //header("Location: " .$site_url. "/order_guest.php");
}   
else
{
	   // print_R($_POST);
// die;
			$order = explode("_",$_POST['wallet']);
		   
			 $wallet_c = $order['0'];
			 $wallet_oid = $order['1'];
			 
            $session_id = session_id();
			// echo "UPDATE order_list_temp SET wallet='$wallet_c',payment_image='$payment_image' WHERE session_id='$session_id'";
			// die;
			 mysqli_query($conn, "UPDATE order_list_temp SET wallet='$wallet_c',payment_image='$payment_image' WHERE session_id='$session_id'");
			 
			 
		
		$finalorder_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `order_list_temp` WHERE session_id = '$session_id' "));
        $varient_type = $finalorder_total['varient_type'] ;
		$pro_id = $finalorder_total['product_id'] ;
		$user_id = $finalorder_total['user_id'] ;
		$m_id = $finalorder_total['merchant_id'] ;
		$qty_list = $finalorder_total['quantity'] ;
		$p_price = $finalorder_total['amount'] ;
		$p_code = $finalorder_total['product_code'] ;
		$option = $finalorder_total['remark'] ;
		$location = $finalorder_total['location'] ;
		$table_type = $finalorder_total['table_type'] ;
		$section_type = $finalorder_total['section_type'] ;
		$date = $finalorder_total['created_on'] ;
		$invoice_no = $finalorder_total['invoice_no'] ;
		$wallet = $finalorder_total['wallet'] ;
		$payment_image = $finalorder_total['payment_image'] ;
          $user_name = $finalorder_total['user_name'] ;
	 $user_mobile = $finalorder_total['user_mobile'] ;
		$sql = "SELECT MAX(invoice_seq) invoice_seq
		FROM order_list
		WHERE merchant_id = '$m_id'";
		$invoice_seq = mysqli_fetch_assoc(mysqli_query($conn, $sql))['invoice_seq'];
		// $inv=explode('_',$invoice_no);
		// $invoice_no=$inv[0];
		if($invoice_seq == NULL) $invoice_seq = 1;
		else $invoice_seq += 1;
		 $invoice_no=$invoice_seq."L";
		
		$vsql = "SELECT MAX(id) v_id FROM order_varient";
		$v_count = mysqli_fetch_assoc(mysqli_query($conn, $vsql))['v_id'];
		if($v_count == NULL) $v_count = 1;
		else $v_count += 1;	
        $v_no=$v_count."L";
		
if(empty($user_id)){  
        
        $user_id =  $_SESSION['login'] ; 
        }   
		
		$sqlFinalIns = "INSERT INTO order_list SET user_name='$user_name',user_mobile='$user_mobile',payment_image='$payment_image',wallet='$wallet',varient_type='$varient_type',product_id='$pro_id',  user_id='$user_id', merchant_id='$m_id', quantity='$qty_list', amount='$p_price',product_code='$p_code', remark='$option', location='".$location."', table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',invoice_seq='$invoice_seq'";
		$test_method = mysqli_query($conn, $sqlFinalIns);
		$order_id = mysqli_insert_id($conn);
		
	  $v_array=explode("|",$varient_type);
	  $vs=0;
	  $v_order=1;
	  $parray=explode(",",$pro_id);
	  $qarray=explode(",",$qty_list);
	     $ps=0;
	   // print_R($qarray);
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
	  foreach($v_array as $vr)
		{
			$product_id=$parray[$vs];
			if($vr)
			{
				$v_match=$vr;
				$v_match = ltrim($v_match, ',');
				$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
				while ($srow=mysqli_fetch_assoc($sub_rows)){
					$v_id=$srow['id'];
					 $v_q="INSERT INTO order_varient SET product_id='$product_id', varient_id='$v_id', invoice_no='$invoice_no',order_id='$order_id',merchant_id='$m_id',v_order='$v_order',v_no='$v_no'";
					
					mysqli_query($conn,$v_q);
						$v_count++;
					}  
			}
			$v_order++;						
			$vs++;						
		}
		if($order_id)
		{
			include("functions.php");
			$orderdata=getOrderDetail($order_id,$conn);
			// print_r($orderdata);
			// die;
		}
		$sqlDtemp = "DELETE FROM `order_list_temp` WHERE session_id = '$session_id'  ";
		mysqli_query($conn, $sqlDtemp);
		
        if($profile_data['user_roles'] !=  '') {
		header("Location: ".$site_url."/orderlist.php");
 } else {
    header("Location: " .$site_url . "/order_guest.php");
     
 }   
}

}




 //~ $u_id= $_SESSION['login'];
 //~ $m_id= $_POST['m_id'];
  //~ $wallet=$_POST['wallet'];
 //~ $amount= $_POST['amount'];
 //~ $o_id= $_POST['o_id'];
 //~ if($wallet == "wallet")
 //~ {
 //~ $balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$u_id."'"));
 //~ $m_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$m_id."'"));



	//~ if(isset($balance['fund_password']))
	//~ {

		//~ if($balance['fund_password'] != $verify_code)
		//~ {
			//~ $error .= "Verification Code is Invalid.<br>";
			//~ $flag = true;
		//~ }

	//~ }
 //~ if($balance['balance_myr'] < $amount)
		//~ {
			//~ echo $error .= "Insufficient Balance In Your Wallet, Recharge Your Wallet First.";
			//~ $flag = true;
		//~ }
	//~ if($flag == false)
	//~ {
			//~ $sender_new_balance = $balance['balance_myr'] - $amount;
			//~ $reciever_new_balance = $m_balance['balance_myr'] + $amount;
			//~ mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='$u_id'");
			//~ mysqli_query($conn, "UPDATE users SET balance_myr='$reciever_new_balance' WHERE id='$m_id'");
			//~ mysqli_query($conn, "UPDATE order_list SET wallet='$wallet' WHERE id='$o_id'");
	//~ }
//~ }
//~ else
//~ {
		//~ mysqli_query($conn, "UPDATE order_list SET wallet='$wallet' WHERE id='$o_id'");

//~ }

?>
