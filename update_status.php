<?php
include("config.php");
$conn2 = mysqli_connect("166.62.120.154", "koofamil_B277", "rSFihHas];1P", "koofamil_B277");  
if( isset( $_POST['method']) && ( $_POST['method'] == "updatePrinted" )  ) {
    $id= $_POST['id'];
    $printed = $_POST['printed'];
    mysqli_query($conn, "UPDATE order_list SET printed='$printed' WHERE id='$id'");
    echo('update printed.');
} else {
	if(isset($_POST['bulk_invoice_id']))
	{
			$status= $_POST['status'];
		$bulk_array=implode(",",$_POST['bulk_invoice_id']);
		mysqli_query($conn, "UPDATE order_list SET status='$status', status_change_date = CURDATE() WHERE id in($bulk_array)");
	}
	else
	{
		$select_wallet=$_POST['select_wallet_2'];
		if($select_wallet!='-1')
		$pay_mode=$select_wallet;
		else
		$pay_mode="cash";
		$id= $_POST['id'];
		$oid= $_POST['oid'];
		$orid= $_POST['orid'];
		$status= $_POST['status'];
		$_SESSION['mm_id'] = $id;
		$_SESSION['o_id'] = $oid;
		$_SESSION['orid'] = $orid;
		$merchant_id = $_SESSION['login'];
		$invoice = mysqli_fetch_assoc(mysqli_query($conn, "SELECT max(invoice_no) no FROM order_list WHERE merchant_id='$merchant_id'"));
		$invoice_no += $invoice['no'] + 1;
		mysqli_query($conn, "UPDATE order_list SET status='$status',wallet='$pay_mode',status_change_date = CURDATE() WHERE id='$id'");  
		$order_place=$rows['order_place'];
		if($order_place=="live")
		mysqli_query($conn2, "UPDATE order_list SET status='$status', status_change_date = CURDATE() WHERE id='$id'");
		
		// echo $status;
		//die;
		$status=9;
		if($status==1)
		{
			$rows = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id='$id'"));
			$quantity_ids = explode(",",$rows['quantity']);
             $amount_val = explode(",",$rows['amount']);
			 $m_id=$rows['merchant_id'];
			 $user_id=$rows['user_id'];
			 $mobile_check=$rows['user_mobile'];
			 $invoice_no=$rows['invoice_no'];
			 
			 if($order_place=="local" || $order_place=="poslocal")
			 {
				$total = 0;
                foreach ($amount_val as $key => $value){
                    if( $quantity_ids[$key] && $value ) {
                        $total =  $total + ($quantity_ids[$key] *$value );
					} 
				}
				// update all local order sale to live website   
				$live_user = mysqli_fetch_assoc(mysqli_query($conn2, "SELECT id FROM users WHERE mobile_number='".$mobile_check."'"));
				if($live_user)
				{
					$live_user_id=$live_user['id'];
					$local_ip=$_SERVER['SERVER_ADDR'];
					$live_insert=mysqli_query($conn2, "INSERT INTO `local_coin_sync` (`merchant_id`, `user_id`, `user_mobile`, `local_invoice_id`, `local_coin`,`local_ip`) VALUES ('$m_id', '$user_id', '$mobile_check', '$invoice_no', '$total','$local_ip')");
					if($live_insert)  
					{
						$defalut_plan="select count(plan.id) as total_count from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$m_id' and plan.default_plan='y'
						and u.user_id='$live_user_id'";
						$defalutplan = mysqli_fetch_assoc(mysqli_query($conn2,$defalut_plan))['total_count'];
						if($defalutplan>0)
						{
						    $total_shop=mysqli_fetch_assoc(mysqli_query($conn2,"select sum(total_cart_amount) as total_order_amount from order_list where user_mobile='$mobile_check' and merchant_id='$m_id'"));
							if($total_shop['total_order_amount'])
							{
								$total_shop_amount=number_format($total_shop['total_order_amount'],2);
								$total_shop=$total_shop['total_order_amount'];
							}
							else
							{
								$total_shop_amount=0;
								$total_shop=0;
							}
								$local_coin = mysqli_fetch_assoc(mysqli_query($conn2,"select sum(local_coin) as total_coin from local_coin_sync where merchant_id='$m_id' and user_mobile='$mobile_check'"));
							if($local_coin['total_coin']>0)
							{
								$total_shop=$local_coin['total_coin']+$total_shop;
							}
								$query="SELECT user_membership_plan.*, membership_plan.user_id as memberplan_user, membership_plan.* FROM user_membership_plan INNER JOIN membership_plan ON membership_plan.id = user_membership_plan.plan_id WHERE user_membership_plan.plan_active='y' and user_membership_plan.user_id='$live_user_id' and user_membership_plan.merchant_id = '$m_id' and membership_plan.total_max_order_amount>='$total_shop' and  membership_plan.total_min_order_amount<='$total_shop'";
							
							// check local order synch coin detail 
							
							$user_plan = mysqli_fetch_assoc(mysqli_query($conn2,$query));
							// print_R($user_plan);
							// die;
							$membership_plan_id=$user_plan['plan_id'];
							if($user_plan['plan_type'] == 'per'){
								$discount = $total_cart_amount*($user_plan['plan_benefit']/100);
								// $total_cart_amount = $total_cart_amount - $discount;
								
							}else{
								$discount = $user_plan['plan_benefit'];
								// $total_cart_amount = $total_cart_amount - $discount;
							}
							if(is_null($user_plan)){
								
								$plan_detail = mysqli_fetch_assoc(mysqli_query($conn2, "SELECT * FROM membership_plan WHERE user_id='$m_id' and $total_shop BETWEEN total_min_order_amount AND total_max_order_amount;"));
								
								// print_r($plan_detail);
								// die;
								if(!is_null($plan_detail)){
									// disactive all old membership 
									$past_plan = mysqli_fetch_assoc(mysqli_query($conn2, "SELECT * FROM user_membership_plan WHERE user_id='$live_user_id' and merchant_id='$m_id'"));
									if($past_plan)
									{
										$is_upgrade="y";
									}
									else
									{
										$is_upgrade="n";
									}  
									$date = date('Y-m-d H:i:s');
									mysqli_query($conn2,"update user_membership_plan set plan_active='n' where user_id='$live_user_id' and merchant_id='$m_id'");
									$user_member_plan = "INSERT INTO `user_membership_plan`(`user_id`, `merchant_id`, `plan_id`, `paid_via`, `paid_date`, `plan_active`, `created`, `is_upgrade`) VALUES ('$live_user_id','$m_id','".$plan_detail['id']."','Cash','$date','y','$date','$is_upgrade')";
									$user_plan_list = mysqli_query($conn2, $user_member_plan);
									$membership_plan_id = mysqli_insert_id($conn2);      
								}
								if($plan_detail['plan_type'] == 'per'){  
									
									$plan_la=$plan_detail['plan_benefit']." %";
									$discount = $total_cart_amount*($plan_detail['plan_benefit']/100);
									// $total_cart_amount = $total_cart_amount;
									
								}else{
									$plan_la="Rm ".$plan_detail['plan_benefit']." off";
									$discount = $plan_detail['plan_benefit'];
									// $total_cart_amount = $total_cart_amount - $discount;
								}
								$membership_discount_input=$plan_la;
							}	
						}
						
					}
				}
			}
		}
	}
}
?>
