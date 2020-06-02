<?php
include("config.php");

if(isset($_POST))
{
	
	// print_R($_POST);
	// die;
	extract($_POST);
	$merchant_id=$_POST['m_id'];
	$merchant_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$merchant_id."'"));
	 $guest_permission=$merchant_data['guest_permission'];
	 $online_pay=0;
	 if($merchant_data['credit_check'] || $merchant_data['wallet_check'] || $merchant_data['boost_check'] || $merchant_data['grab_check']
	 || $merchant_data['wechat_check'] || $merchant_data['touch_check'] || $merchant_data['fpx_check'])
	 {
		  $online_pay=1;
		  $payment_alert="y";
	 }		 
	$show_alert="y";
	$otp_verified="n";
	$password_created="n";
	$rebate_applicable="n";
	$rebate_amount=0;
	$total_amount=0;
	
	if($mobile_number)
	{
		if($guest_permission==1)
		{
			 $f_letter=$mobile_number[0];
			
			// check mobile start with 1 and  greater than 9 
		
			if (($f_letter=="1") ) {
				 // Yes
				 $show_alert="y";
				 $password_created="n";
				 $otp_verified="n";
			}
			else
			{
				$show_alert="n";
				 $password_created="y";
				  $otp_verified="y";
			}
		}
		else
		{
			$show_alert="y";
			$otp_verified="n";
		}  
		// echo $show_alert;
		// die;
	$mobile_check="60".$mobile_number;
	if($_SESSION['login']=='')
	  {
	     $loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE  mobile_number ='".$mobile_check."'");	
	  }
	  else
	  {
		  $loginmatch = mysqli_query($conn, "SELECT * FROM users WHERE  id ='".$_SESSION['login']."'");	
	  }
	  $logincount=mysqli_num_rows($loginmatch);
	}
	else
	{
		$show_alert="n";
		$logincount=0;
	}
	$mobile_block = array("60123456789", "601234567890", "Glenn", "Cleveland");

	if(in_array($mobile_check,$mobile_block))
	{
		echo "Your number has been barred from placing order, please contact 012-3115670 for clarification.";
		die;
	}
	if($logincount>0)
	{
		$userdata=mysqli_fetch_assoc($loginmatch);
		$user_id=$userdata['id'];
		
		$user_mobile=$userdata['mobile_number'];
		if($mobile_check==$user_mobile)
		{
		}
		else
		{
			$user_mobile=$mobile_check;
		}
		$myr_bal=$userdata['balance_myr'];
	    $usb_bal=$userdata['balance_usd'];
	    $inr_bal=$userdata['balance_inr'];
		$user_name=$userdata['name'];
		$otp_verified=$userdata['otp_verified'];
		if($otp_verified=="y")
		$newuser="n";
		else
		$newuser="y";
		// $_SESSION['tmp_login']=$user_id;     
		$_SESSION['user_id']=$user_id;
	}
	else
	{
		if($mobile_number)
		{
			// create new user account with respect to merchant 
			$user_role=1;
			$reocrd=mysqli_query($conn, "INSERT INTO users SET name='$name',user_roles='$user_role',mobile_number='$mobile_check',guest_user='y',login_status='1',password_created='$password_created',otp_verified='y'");
            $user_id=mysqli_insert_id($conn);
			$user_mobile="60".$mobile_number;   
			$newuser="y";
		}
		else
		{
			
			if($guest_permission == "1"){
				$user_id=3366;
				$user_mobile='';
				$$neuser="n";
				$guest_user_order="y";
			}
			else
			{
				// not permission to place order as guest 
				echo "Not permssion to place order as a guest";
				die;
			}
			// create guest user id 
			
		}
         
		
	}
	
	  if($_SESSION['login']=='')
	  {
		$_SESSION['tmp_login']=$user_id;
	  }		
	  // print_R($_SESSION);
	  // die;
		// $_SESSION['user_id']=$user_id;
	// insert data into order table 
		$stl_key = isset($_POST['stl_key']) ? $_POST['stl_key'] : '';
		// $u_id = $_SESSION['login'];
		$date = date('Y-m-d H:i:s');
		$location =$_POST['location'];
		$table_type =$_POST['table_type'];
		 $section_type =$_POST['section_type'];
		$p_code = implode(',', $_POST['p_code']);
		$pro_id = implode(',', $_POST['p_id']);
		// $varient_type=$_POST['varient_type'];
		$qty_list = implode(',', $_POST['qty']);
		$prices = $_POST['p_price'];
		$p_extra = explode('|', $_POST['price_extra']);
		$p_price = [];
		foreach ($prices as $i => $item) {
			if(sizeof(explode(",",$p_extra[$i])) > 1){
				$totalExtra = explode(",",$p_extra[$i]);
				$p_extra_ind = 0;
				foreach ($totalExtra as $xtr) {
					$p_extra_ind += $xtr;
				}
			}else{
				$p_extra_ind = $p_extra[$i];
			}
			array_push($p_price, $p_extra_ind + $item);
		}
		$p_price = implode(",", $p_price);
		$option = $_POST['options'];
		$product_name =isset($_POST['product_name']) ? $_POST['product_name'] : '';
		$product_code =isset($_POST['product_code']) ? $_POST['product_code'] : '';
		// fields for rebate process 
		$w_type =$_POST['selected_wallet'];
		if($w_type=='')
		{
			$w_type="cash";
			$prepaid='n';
		}
		else
		{
			$prepaid='y';
		}
		$total_rebate_amount =$_POST['total_rebate_amount'];
		$total_rebate_amount=0;
		if($_POST['rem_amount']>0)
		$rem_amount =$_POST['rem_amount'];
		else
		$rem_amount=$_POST['total_cart_amount'];	
		$total_cart_amount =$_POST['total_cart_amount'];
		$wallet_paid_amount =$_POST['payable_amount'];
		$rebate_amount = implode(',',$_POST['rebate_amount']);
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
		// $order_process=true;
		if($wallet_paid_amount>0)
		{
		   if($w_type=="myr_bal")
		   {
			  $myr_bal=$userdata['balance_myr']-$wallet_paid_amount;
		   }
			else if($w_type=="usd_bal")
			{
				$usb_bal=$userdata['balance_usd']-$wallet_paid_amount;
			}
			else if($w_type=="inr_bal")
			{
				$inr_bal=$userdata['balance_inr']-$wallet_paid_amount;
			} else if($w_type=="LIFE COIN")
			{
				$merchantwallet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE  id='$m_id'"));
				$walletcheck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM special_coin_wallet WHERE  user_id='$user_id' and merchant_id ='".$m_id."'"));
				if($walletcheck)
				{
					$balance_special=$walletcheck['coin_balance'];
					$mer_bal=$merchantwallet['balance_usd'];
					$new_mer_bal=$mer_bal+$wallet_paid_amount;
					$new_special=$balance_special-$wallet_paid_amount;
					$q2="update special_coin_wallet set coin_balance='$new_special' where user_id='$user_id' and merchant_id='$m_id'";
					$qmerchant="update users set balance_usd='$new_mer_bal' where id='$m_id'";
					$update2=mysqli_query($conn,$q2);
					$update3=mysqli_query($conn,$qmerchant);
				}
				
			}
            $wallet=$w_type;
						if($wallet=="myr_bal")
						$wal_label="MYR WALLET";
						else if($wallet=="inr_bal")
						$wal_label="KOO COIN";
						 else if($wallet=="usd_bal")
						$wal_label="CF WALLET";
						 else if($wallet=="cash")
						$wal_label="CASH";
						else $wal_label=$w_type;		
		}
		  $doublein="select id from order_list where created_timestamp > date_sub(now(), interval 1 minute) and user_name='$user_name' and user_mobile='$user_mobile' and wallet='cash' and varient_type='$v_str' and product_id='$pro_id' and
		user_id='$user_id' and merchant_id='$m_id' and  quantity='$qty_list' and  amount='$p_price' and product_code='$p_code' and  remark='$option' and table_type='".$table_type."' and section_type='$section_type'";
	 // die
		 $double_invoice_count = mysqli_num_rows(mysqli_query($conn, $doublein));
		// $double_invoice_count=0;
		if($double_invoice_count==0)
		{
			$sql = "SELECT MAX(invoice_seq) invoice_no
			FROM order_list
			WHERE merchant_id = '$m_id'";
			$invoice_seq = mysqli_fetch_assoc(mysqli_query($conn, $sql))['invoice_no'];
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
			$v_no=$v_count;
			if($section_type && $table_type)
			$section_saved='y';
		else
			$section_saved='n';
		  		         $sqlFinalIns = "INSERT INTO order_list SET invoice_seq='$invoice_seq',rebate_amount='$rebate_amount',prepaid='$prepaid',total_cart_amount='$total_cart_amount',total_rebate_amount='$total_rebate_amount',wallet_paid_amount='$wallet_paid_amount',online_pay='$online_pay',payment_alert='$payment_alert',user_name='$user_name',user_mobile='$user_mobile',wallet='$w_type',varient_type='$v_str',product_id='$pro_id',  user_id='$user_id', merchant_id='$m_id', quantity='$qty_list', amount='$p_price',product_code='$p_code', remark='$option', location='".$location."', table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',newuser='$newuser',show_alert='$show_alert',section_saved='$section_saved'";
			// die;
			$test_method = mysqli_query($conn, $sqlFinalIns);
			  $order_id = mysqli_insert_id($conn); 
			    // deduct rebate balance 
			$query="UPDATE users SET balance_myr= '$myr_bal',balance_usd='$usb_bal',balance_inr='$inr_bal' WHERE `users`.`id`='$user_id'";
			$insert=mysqli_query($conn,$query);
			$creaed_on=strtotime(date('Y-m-d H:i:s'));
			if($wallet_paid_amount>0)
			mysqli_query($conn,"INSERT INTO tranfer (`sender_id`, `amount`, `receiver_id`, `wallet`, `created_on`, `status`, `details`,`invoice_no`,`type_method`) VALUES ('$user_id', '$wallet_paid_amount', '$m_id', '$wal_label', '$creaed_on', '1', 'ewallet used','$invoice_no','ewallet')");
		
			if($test_method)
			{
				  $_SESSION['new_order']='y';
				 $push_id=$merchant_data['moengage_unique_id'];
				$push_id='';
				if ($push_id) {
					$result=exec("/usr/bin/python myscript.py");
				 $resultarray=explode(",",$result);
				 // print_R($resultarray);
				 // die;
				 if (count($resultarray)>0) {
					 // code...
					 $data['camp_name']=$camp_name=$resultarray[0];
					 $data['sign']=$sign=$resultarray[1];
					 $data['push_email']=$push_id;
					 $data['title']='Order Ready';
					 $data['message']='Congratulation! You have a new order. Please check your order list.';
					 $data['redirectURL']= $site_url .'/orderview.php';
					 include 'push.php';
					 $user = new Push();
					 $resultpush = $user->send_push($data);

				 }
				}
			}
		   
		   if($guest_user_order=="y")
		   {
			   $_SESSION['guest_order_id']=$order_id;
		   }
			// $v_array=explode("|",$varient_type);
		  $vs=0;
		  $v_order=1;
		  $parray=explode(",",$pro_id);
		// print_R($varient_type);
		  if($varient_type)
		  {
			foreach($varient_type as $vr)
			{
				
				$product_id=$parray[$vs];
				if($vr)
				{
					$v_match=$vr;
					$v_match = ltrim($v_match, ',');
					$sub_rows = mysqli_query($conn, "SELECT * FROM sub_products WHERE id  in ($v_match)");
					while ($srow=mysqli_fetch_assoc($sub_rows)){
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
		}
		$merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE  id='$m_id'"));
		$m_mobile=$merchant['mobile_number'];
	    // header("Location: ".$site_url."/orderlist.php");
		$_SESSION['o']='done';
	    header("Location: ".$site_url."/view_merchant.php?sid=".$m_mobile);
		
	
}
?>