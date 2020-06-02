<?php 
include("config.php");

$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$_SESSION['login']."'"));

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

if(isset($_POST['submit']))
{
	$receiver = addslashes($_POST['receiver']);
	$amount = addslashes($_POST['amount']);
	$wallet = addslashes($_POST['wallet']);
	
	$flag = false;
	$error = "";
	
	if($receiver == "" || $amount == "" || $wallet == "")
	{
		$error .= "All Fields are Required.<br>";
		$flag = true;
	}
	
	if(filter_var($receiver, FILTER_VALIDATE_EMAIL) === false)
	{
		$error .= "Email Address is not Valid.<br>";
		$flag = true;
	}
	
	$reciever_id_result = mysqli_query($conn, "SELECT id,balance_usd,balance_inr,balance_myr FROM users WHERE email='$receiver'");
	$account_exists = mysqli_num_rows($reciever_id_result);
	if($account_exists == 0)
	{
		$error .= "Receiver does not exists in our Database.<br>";
		$flag = true;
	}
	else
	{
		$sender_id = $_SESSION['login'];
		
		$receiver_row = mysqli_fetch_assoc($reciever_id_result);
		$reciever_id = $receiver_row['id'];
		if($reciever_id == $sender_id)
		{
			$error .= "Transferring Money to Self is not Possible.<br>";
			$flag = true;
		}
	}
	
	if(!is_numeric($amount) || $amount == 0)
	{
		$error .= "Amount is not Valid.<br>";
		$flag = true;
	}
	
	if($wallet == "INR")
	{
		if($balance['balance_inr'] < $amount)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
	}
	else if($wallet == "MYR")
	{
		if($balance['balance_myr'] < $amount)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
	}
	else if($wallet == "USD")
	{
		if($balance['balance_usd'] < $amount)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
	}
	else
	{
		$error .= "Current Currency is not Supported.<br>";
		$flag = true;
	}
	 'sender_id :' . $sender_id;
	 'reciever_id :' . $reciever_id;
	 'amount :' . $amount;
	
	  mysqli_query($conn, "INSERT INTO tranfer SET sender_id='".$sender_id."', receiver_id='".$reciever_id."', amount='".$amount."', wallet='".$wallet."', created_on='".time()."', status='0'");
echo mysqli_info($conn); 

	//~ if($flag == false)
	//~ {
		//~ mysqli_autocommit($conn,FALSE);
		
		//~ if($wallet == "INR")
		//~ {
			//~ $sender_new_balance = $balance['balance_inr'] - $amount;
			//~ $receiver_new_balance = $receiver_row['balance_inr'] + $amount;
			//~ mysqli_query($conn, "UPDATE users SET balance_inr='$sender_new_balance' WHERE id='$sender_id'");
			//~ mysqli_query($conn, "UPDATE users SET balance_inr='$receiver_new_balance' WHERE id='$reciever_id'");
		//~ }
		//~ else if($wallet == "MYR")
		//~ {
			//~ $sender_new_balance = $balance['balance_myr'] - $amount;
			//~ $receiver_new_balance = $receiver_row['balance_myr'] + $amount;
			//~ mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='$sender_id'");
			//~ mysqli_query($conn, "UPDATE users SET balance_myr='$receiver_new_balance' WHERE id='$reciever_id'");
		//~ }
		//~ else if($wallet == "USD")
		//~ {
			//~ $sender_new_balance = $balance['balance_usd'] - $amount;
			//~ $receiver_new_balance = $receiver_row['balance_usd'] + $amount;
			//~ mysqli_query($conn, "UPDATE users SET balance_usd='$sender_new_balance' WHERE id='$sender_id'");
			//~ mysqli_query($conn, "UPDATE users SET balance_usd='$receiver_new_balance' WHERE id='$reciever_id'");
		//~ }
		//~ else
		//~ {
			//~ echo "System Info : An Error Occured"; die;
		//~ }
	 	
		
		 //~ mysqli_query($conn, "INSERT INTO transactions SET sender_id='".$sender_id."', receiver_id='".$reciever_id."', amount='".$amount."', wallet='".$wallet."', created_on='".time()."'");
		
		
		
		 //~ mysqli_query($conn, "INSERT INTO tranfer SET sender_id='".$sender_id."', receiver_id='".$reciever_id."', amount='".$amount."', wallet='".$wallet."', created_on='".time()."'");	
		
	
		
		//~ $sender = $balance['name']; // sender name
		//~ mysqli_query($conn, "INSERT INTO notifications SET user_id='$reciever_id', notification='You Successfully Received $amount $wallet from $sender', type='receive', created_on='".time()."', readStatus='0'");
		
		//~ mysqli_commit($conn);
		
		//~ $success = "You Successfully Transferred $amount $wallet to $receiver";
	//~ }
}

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	<style>
	.well
	{
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	</style>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="col-md-3"></div>
					<div class="well col-md-6">
						<form method="post" onsubmit="return checkBal()">
							<?php
							if(isset($error) && $error != "")
							{
								echo "<div class='alert alert-danger'>$error</div>";
							}
							if(isset($success))
							{
								echo "<div class='alert alert-success'>$success</div>";
							}
							?>
							<div class="panel price panel-red" style="padding:50px 5px;">
								<h2>Transfer Money</h2>
								<br>
								<input type="email" class="form-control" name="receiver" placeholder="Email Address of Receiver" required>
								<br>
								<select class="form-control" required="true" name="wallet" id="wallet">
									<option value="">Select Wallet</option>
									<option value="MYR">Malaysian Ringgit</option>
									<option value="USD">US Dollar</option>
									<!--<option value="INR">Indian Rupees</option>-->
								</select>
								<br>
								<input type="number" class="form-control" name="amount" id="amount" step="0.01" placeholder="Amount" required="true">
								<br><br>
								<input type="submit" class="btn btn-block btn-primary" name="submit" id="send_pop" value="Send">
							</div>
						</form>
					</div>
					<div class="col-md-3"></div>
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	
	<script type="text/javascript">
		var balance_myr = '<?php echo $balance['balance_myr']; ?>';
		var balance_inr = '<?php echo $balance['balance_inr']; ?>';
		var balance_usd = '<?php echo $balance['balance_usd']; ?>';
		
		function checkBal()
		{
			var wallet = $("#wallet").val();
			var amount = parseFloat($("#amount").val());
			
			var balance = "";
			
			if(wallet == "INR")
			{
				balance = balance_inr;
			}
			else if(wallet == "MYR")
			{
				balance = balance_myr;
			}
			else if(wallet == "USD")
			{
				balance = balance_usd;
			}
			else
			{
				alert("Please Select Wallet.");
				return false;
			}
			
			balance = parseFloat(balance);
			
			if(amount > balance)
			{
				alert("Not Enough Balance In Your Wallet, Recharge Your Wallet First");
				return false;
			}
			else
			{
				return true;
			}
		}
	</script>
</body>

</html>
