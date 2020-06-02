
<?php

// session_start();
include("config.php");
$fixwallet = array("MYR", "INR", "CF");
if (isset($_POST['mobile_number'])) {
	$mobile_number = $_POST['mobile_number'];
	$sender_id = $_POST['sender_id'];
	$wallet_type = $_POST['wallet_type'];
	$transfer_amount = $_POST['transfer_amount'];
	$tmp = mysqli_query($conn, "SELECT id,special_coin_name FROM users WHERE mobile_number = '$mobile_number'");
	if (mysqli_num_rows($tmp) > 0) {
		$buf = mysqli_fetch_assoc($tmp);
		$mer_id=$data->id = $buf['id'];
		$data->special_coin_name = $buf['special_coin_name'];
		$sender = mysqli_query($conn, "SELECT user_roles,id, balance_inr, balance_myr, balance_usd FROM users WHERE id = ".$sender_id);
		if (mysqli_num_rows($sender) > 0) { 
			$sender = mysqli_fetch_assoc($sender);
			if ($sender['balance_myr'] == '') {
				$data->MYR = 0;
			}
			else {
				$data->MYR = $sender['balance_myr'];
			}
			if ($sender['balance_usd'] == '') {
				$data->CF = 0;
			} else {
				$data->CF = $sender['balance_usd'];
			}
			if ($sender['balance_inr'] == '') {
				$data->CF = 0;
			} else {
				$data->INR = $sender['balance_inr'];
			}
			 $sender_user_role=$sender['user_roles'];
			if($sender_user_role==1)
			{
				 $sq="select special_coin_wallet.*,m.special_coin_name from special_coin_wallet  inner join users as m on m.id=special_coin_wallet.merchant_id where merchant_id='$mer_id' and user_id='$sender_id'";
				
				$sub_rows = mysqli_query($conn,$sq);
						  if(mysqli_num_rows($sub_rows)>0){
							while ($swallet=mysqli_fetch_assoc($sub_rows)){
								$s_coin_name=$swallet['special_coin_name'];
								$s_bal=$swallet['coin_balance'];
								$data->$s_coin_name=$swallet['coin_balance'];
							}
						  }
				if (!in_array($wallet_type,$fixwallet)) {
					if($s_coin_name)
					{
					// check reciver wallet name match with merchant name or not 
					if($buf['special_coin_name']==$s_coin_name)
					{
						if($s_bal>=$amount)
						{
						}
						else
						{
							$res['status']=false;
							$res['data']='';
							$res['msg']='Not sufficient Bal to Transfer';
							echo json_encode($res);
							die;
						}
					}
					else
					{
						$res['status']=false;
						$res['data']='';
						$res['msg']='Your Can only transfer to that Coin holder merchant number';
						echo json_encode($res);
						die;
					}
					}
					else
					{
						$res['status']=false;
						$res['data']='';
						$res['msg']='Your Can only transfer to that Coin holder merchant number';
						echo json_encode($res);
						die;
					}
				}
				
				
			}
			if($data)
			{
				$res['status']=true;
				$res['data']=$data;
				$res['msg']='Can Trasfer to that mobile';
			}
			else
			{
				$res['status']=false;
				$res['data']='';
			}
			
			
		}else{
			// echo -1;
			$res['status']=false;
			$res['data']='';
			$res['msg']='Invalid Sender Detail';
		}
		// print_r($tmp);
	} else {
		
		// echo -1;
		$res['status']=false;
			$res['data']='';
			$res['msg']='Invalid Reciver Mobile Number';
	}
    echo json_encode($res);
	die;
} else {
	$sender_name = $_POST['sender_name'];
	$sender_mobile = $_POST['sender_mobile'];
	// $created_on = $_POST['created'] / 1000;
	$datetime = date('Y-m-d H:i:s');
	$created_on = strtotime($datetime);
	$sender_id = $_POST['sender_id'];
	$receiver_id = $_POST['receiver_id'];
	$merchant_send = $_POST['merchant_send'];
	$amount = $_POST['amount'];
	$wallet_type = $_POST['wallet_type'];
	$special_wallet = $_POST['special_wallet'];
	
	$sql = 'INSERT INTO tranfer (sender_id, amount, receiver_id, wallet, created_on) VALUES ("'.$sender_id.'", "'.$amount.'", "'.$receiver_id.'", "'.$wallet_type.'", "'.$created_on.'")';
	$transfer = mysqli_query($conn, $sql);

	$sql_for_transaction = 'INSERT INTO transaction (sender_id, amount, receiver_id, wallet, created_on) VALUES ("'.$sender_id.'", "'.$amount.'", "'.$receiver_id.'", "'.$wallet_type.'", "'.$created_on.'")';
	$transaction = mysqli_query($conn, $sql_for_transaction);
	if($sender_name)
	$noti_string = 'You Successfully Received '.$amount.' '.$wallet_type.' from '.$sender_name;
	else
	$noti_string = 'You Successfully Received '.$amount.' '.$wallet_type.' from '.$sender_mobile;	
	$noti = 'INSERT into notifications (user_id, notification , type, created_on, readStatus) VALUES ("'.$receiver_id.'", "'.$noti_string.'", "receive", "'.$created_on.'", "0")';
	$notification = mysqli_query($conn, $noti);  

	$tmp = 'SELECT balance_usd, balance_myr, balance_inr FROM users WHERE id="'.$sender_id.'"';
	$sender = mysqli_fetch_assoc(mysqli_query($conn, $tmp));
	$sender_myr = $sender['balance_myr'];
	$sender_inr = $sender['balance_inr'];
	$sender_usd = $sender['balance_usd'];

	$tmp = 'SELECT mobile_number,balance_usd, balance_myr, balance_inr FROM users WHERE id="'.$receiver_id.'"';
	$receiver = mysqli_fetch_assoc(mysqli_query($conn, $tmp));
	$receiver_myr = $receiver['balance_myr'];
	$receiver_inr = $receiver['balance_inr'];
	$receiver_usd = $receiver['balance_usd'];
	$receiver_mobile= $receiver['mobile_number'];
	$sender_bal = 'UPDATE users SET ';
	$receiver_bal = 'UPDATE users SET ';
	if (in_array($wallet_type, $fixwallet))  
	{
		if ($wallet_type == 'MYR') {
			$sender_myr = floatval($sender_myr) - floatval($amount);
			$receiver_myr = floatval($receiver_myr) + floatval($amount);
			$sender_bal = $sender_bal.'balance_myr="'.$sender_myr.'" ';
			$receiver_bal = $receiver_bal.'balance_myr="'.$receiver_myr.'" ';
		}
		if ($wallet_type == 'INR') {
			$sender_inr = floatval($sender_inr) - floatval($amount);
			$receiver_inr = floatval($receiver_inr) + floatval($amount);
			$sender_bal = $sender_bal.'balance_inr="'.$sender_inr.'" ';
			$receiver_bal = $receiver_bal.'balance_inr="'.$receiver_inr.'" ';
		}
		if ($wallet_type == 'CF') {
			$sender_usd = floatval($sender_usd) - floatval($amount);
			$receiver_usd = floatval($receiver_usd) + floatval($amount);
			$sender_bal = $sender_bal.'balance_usd="'.$sender_usd.'" ';
			$receiver_bal = $receiver_bal.'balance_usd="'.$receiver_usd.'" ';
		}
		
		$sender_bal = $sender_bal.' WHERE id='.$sender_id;
		mysqli_query($conn, $sender_bal);
		
		$receiver_bal = $receiver_bal.' WHERE id='.$receiver_id;
		mysqli_query($conn, $receiver_bal);
	} else 
	{
		if($merchant_send=="y")
		{
			// $mer_bal=
			$special_coin=mysqli_fetch_assoc(mysqli_query($conn,"select * from special_coin_wallet where user_id='$receiver_id' and merchant_id='$sender_id'"));
			// $sender_usd=$sender_usd-$amount;
			if($special_coin['coin_balance'])
			{
				$receiver_amount=$new_coin=$special_coin['coin_balance']+$amount;
				$total_added=$new_coin=$special_coin['added_balance']+$amount;
				// echo "update special_coin_wallet set coin_balance='$new_coin' where user_id='$receiver_id' and merchant_id='$sender_id'";
				// die;
				
				mysqli_query($conn,"update special_coin_wallet set coin_balance='$new_coin',added_balance='$total_added' where user_id='$receiver_id' and merchant_id='$sender_id'");
				
				$receiver_usd=$new_coin;  
			}  
			else
			{  
				// make new entry 
				// echo "INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance) VALUES ('$receiver_id', '$sender_id', '$amount')";
				mysqli_query($conn,"INSERT INTO special_coin_wallet (user_id,merchant_id,coin_balance,added_balance,user_mobile) VALUES ('$receiver_id', '$sender_id','$amount','$amount','$receiver_mobile')");
				// $sender_amount=$amount;  
				$receiver_bal=$receiver_amount;  
				
			}
			$sender_amount=$sender_usd = floatval($sender_usd) - floatval($amount);
			// $sender_amount=$sender_bal;
			mysqli_query($conn,"update users set balance_usd='$sender_usd' where id='$sender_id'");
			$sender_bal=$sender_usd;  
		}  
		else
		{  
		  	// send by user 
			$special_coin=mysqli_fetch_assoc(mysqli_query($conn,"select * from special_coin_wallet where user_id='$sender_id' and merchant_id='$receiver_id'"));
			if($special_coin['coin_balance'])
			{
				$s_coin_id=$special_coin['id'];
				$sender_bal=$new_s_coin=$special_coin['coin_balance']-$amount;
				$receiver_bal=$receiver_usd=$receiver_usd+$amount;
				mysqli_query($conn,"update special_coin_wallet set coin_balance='$new_s_coin' where id='$s_coin_id'");
				mysqli_query($conn,"update users set balance_usd='$receiver_usd' where id='$receiver_id'");
			}
			else
			{
				echo -1;
				die;
			}  
			
		}
	}
	// echo $noti;
	echo $sender_bal;
	echo $receiver_bal;
}
