<?php
include("config.php");


function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg)  
            {           
                        $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
                        $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
                        $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
                        $url = "http://gateway.onewaysms.com.au:10001/".$query_string;       
                        $fd = @implode ('', file ($url));      
                        if ($fd)  
                        {                       
				    if ($fd > 0) {
					//Print("MT ID : " . $fd);
					$ok = "success";
				    }        
				    else {
					print("Please refer to API on Error : " . $fd);
					$ok = "fail";
				    }
                        }           
                        else      
                        {                       
                                    // no contact with gateway     
                                    $ok = "fail";       
                        }           
                        return $ok;  
            }

//~ print_r($_FILES); 

 $m_id=$_POST['m_id'];
 $p_id=$_POST['p_id'];
 $u_id=$_POST['u_id'];
 $wallet=$_POST['wallet'];
 $qty=$_POST['qty'];
 $details='order';
 $date = date('Y-m-d H:i:s');
 $amount = $_POST['product_price'];
 $product_name = $_POST['product_name'];
 $image = $_POST['image'];
 
 $balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$u_id."'"));
 $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id ='".$p_id."'"));	
 $m_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$m_id."'"));

 $m_mobile_number = $m_balance['mobile_number'];
 $pro_name = $product['product_name'];
 $client_name =$balance['name'];

 $product_price = $product['product_price'];
 $amount = $product_price * $qty;
 

 $image = $product['image'];

 
if($wallet == "INR")
	{
		if($balance['balance_inr'] < $amount)
		{
			echo $error .= "Insufficient Balance In Your Wallet, Recharge Your Wallet First.";
			$flag = true;
		}
	}
	else if($wallet == "MYR")
	{
		if($balance['balance_myr'] < $amount)
		{
			echo $error .= "Insufficient Balance In Your Wallet, Recharge Your Wallet First.";
			$flag = true;
		}
	}
	else if($wallet == "USD")
	{
		if($balance['balance_usd'] < $amount)
		{
			echo $error .= "Insufficient Balance In Your Wallet, Recharge Your Wallet First.";
			$flag = true;
		}
	}

if($flag == false)
	{
		
		mysqli_query($conn, "INSERT INTO order_list SET product_id='$p_id',user_id='$u_id',merchant_id='$m_id',quantity='$qty',amount='$amount',wallet='$wallet',created_on='$date'");
	
	   mysqli_query($conn, "INSERT INTO  tranfer SET sender_id='".$u_id."', receiver_id='".$m_id."', amount='".$amount."', details='".$details."',wallet='".$wallet."', created_on='".time()."', status='0'");
	  if($wallet == "INR")
		 {
			 $sender_new_balance = $balance['balance_inr'] - $amount;
			 $reciever_new_balance = $m_balance['balance_inr'] + $amount;
			 mysqli_query($conn, "UPDATE users SET balance_inr='$sender_new_balance' WHERE id='$u_id'");
			 mysqli_query($conn, "UPDATE users SET balance_inr='$reciever_new_balance' WHERE id='$m_id'");
		 }
		 else if($wallet == "MYR")
		 {
			$sender_new_balance = $balance['balance_myr'] - $amount;
			$reciever_new_balance = $m_balance['balance_myr'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='$u_id'");
			mysqli_query($conn, "UPDATE users SET balance_myr='$reciever_new_balance' WHERE id='$m_id'");
		}
		else if($wallet == "USD")
		{
			$sender_new_balance = $balance['balance_usd'] - $amount;
			$reciever_new_balance = $m_balance['balance_usd'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_usd='$sender_new_balance' WHERE id='$u_id'");
			mysqli_query($conn, "UPDATE users SET balance_usd='$reciever_new_balance' WHERE id='$m_id'");
		}
		else
		{
			echo "System Info : An Error Occured"; die;
		}
		$sender = $balance['name']; // sender name 
			 
		mysqli_query($conn, "INSERT INTO notifications SET user_id='$m_id', notification='You Successfully Received $amount $wallet from $u_id', type='receive', created_on='".time()."', readStatus='0'");
		
		
		
		$rece_name = $receiver_row1['name'];
		
		//Print( gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$m_mobile_number", "This Product is $pro_name order by  $client_name"));
		echo $success = "You Successfully Transferred $amount $wallet to $m_id";


}

?>

