<?php 
include("config.php"); 


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
			mysqli_query($conn, "UPDATE order_list SET wallet='$wallet' WHERE id='$o_id'");

	}	

}

else
{
			$order = explode("_",$_POST['wallet']);

			 $wallet_c = $order['0'];
			 $wallet_oid = $order['1'];
	
		mysqli_query($conn, "UPDATE order_list SET wallet='$wallet_c' WHERE id='$wallet_oid'");
		header("Location: http://kooexchange.com/demo/orderlist.php");


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
