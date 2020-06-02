<?php

include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

if(isset($_GET['st']) && $_GET['st'] == "Completed")
{
	mysqli_query($conn, "INSERT INTO recharges SET user_id='".$_SESSION['login']."', currency='".$_GET['cc']."', amount='".$_GET['amt']."', paypal_txn_id='".$_GET['tx']."', created_on='".time()."'");
	
	if($_GET['cc'] == "USD")
	{
		$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_usd FROM users WHERE id='".$_SESSION['login']."'"))['balance_usd'];
		$new_balance = $current_balance + $_GET['amt'];
		mysqli_query($conn, "UPDATE users SET balance_usd='$new_balance' WHERE id='".$_SESSION['login']."'");
	}
	else
	{
		$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_myr FROM users WHERE id='".$_SESSION['login']."'"))['balance_myr'];
		$new_balance = $current_balance + $_GET['amt'];
		mysqli_query($conn, "UPDATE users SET balance_myr='$new_balance' WHERE id='".$_SESSION['login']."'");
	}
	
	header("location:wallet.php?message=".urlencode("Recharge Successful of ".$_GET['amt']." ".$_GET['cc'].""));
}
else
{
	header("location:wallet.php?message=".urlencode("AN ERROR OCCURED."));
}

?>