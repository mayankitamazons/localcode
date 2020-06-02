<?php 
include("config.php");


if(isset($_POST['submit']))
{

$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tranfer WHERE id='".$_POST['id']."'"));

$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$user_data['sender_id']."'"));
$receiver_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$user_data['receiver_id']."'"));
	 $reciever_id = addslashes($user_data['receiver_id']);
	 $amount = addslashes($user_data['amount']);
	 $wallet = addslashes($user_data['wallet']);
	 $sender_id = addslashes($user_data['sender_id']);
	$tra_id = $_POST['id'];
	 $stl_key = $_POST['stl_key'];
	
	$fund = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id =".$_SESSION['login']));
	
	if($flag == false)
	{

		if($stl_key == $_POST['stl_key']) 
			{
		mysqli_autocommit($conn,FALSE);
		
		if($wallet == "INR")
		{
			$receiver_new_balance = $receiver_row['balance_inr'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_inr='$receiver_new_balance' WHERE id='$reciever_id'");
			mysqli_query($conn, "UPDATE tranfer SET status='1' WHERE id='$tra_id'");
		}
		else if($wallet == "MYR")
		{
			//~ echo $wallet;
			$receiver_new_balance = $receiver_row['balance_myr'] + $amount;
			
			mysqli_query($conn, "UPDATE users SET balance_myr='$receiver_new_balance' WHERE id='$reciever_id'");
			mysqli_query($conn, "UPDATE tranfer SET status='1' WHERE id='$tra_id'"); 
			
		}
		else if($wallet == "USD")
		{
		
			$receiver_new_balance = $receiver_row['balance_usd'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_usd='$receiver_new_balance' WHERE id='$reciever_id'");
			mysqli_query($conn, "UPDATE tranfer SET status='1' WHERE id='$tra_id'");
			

		}
		else
		{
			echo "System Info : An Error Occured"; die;
		}
		
		
		mysqli_query($conn, "INSERT INTO transactions SET sender_id='".$sender_id."', receiver_id='".$reciever_id."', amount='".$amount."', wallet='".$wallet."', created_on='".time()."'");
		
		$sender = $balance['name']; // sender name
		mysqli_query($conn, "INSERT INTO notifications SET user_id='$reciever_id', notification='You Successfully Received $amount $wallet from $sender', type='receive', created_on='".time()."', readStatus='0'");
		
		mysqli_commit($conn);
		 $_SESSION['stl_key'] = "empty";
		$success = "You Successfully Transferred $amount $wallet to $receiver";
		
	}
	  header("Location: " . $_SERVER['REQUEST_URI']);

}

}



  
  else {
	  
	  if(isset($_POST['submit1']))
  {
	  //~ print_r($_POST);
	 
	 
	  
	 $reject_id =$_POST['id'];
	 
	 $stl_key = $_POST['stl_key'];
	 if($stl_key == $_SESSION['stl_key']) 
			{
	 mysqli_query($conn, "UPDATE tranfer SET status='2' WHERE id='$reject_id'");
	 $reject_eamt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tranfer WHERE id='".$reject_id."'"));
	  $balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$reject_eamt['sender_id']."'"));
	  $sender_id = $reject_eamt['sender_id'];
	  $amount = $reject_eamt['amount'];
	  $wallet = $reject_eamt['wallet'];
	 
	 if($wallet == "INR")
		{
			$sender_new_balance = $balance['balance_inr'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_inr='$sender_new_balance' WHERE id='$sender_id'");
		}
		else if($wallet == "MYR")
		{
			$sender_new_balance = $balance['balance_myr'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='$sender_id'");
		}
		else if($wallet == "USD")
		{
		
			 $sender_new_balance = $balance['balance_usd'] + $amount;
			
			mysqli_query($conn, "UPDATE users SET balance_usd='$sender_new_balance' WHERE id='$sender_id'");
		}
		else
		{
			echo "System Info : An Error Occured"; die;
		}
	
$_SESSION['stl_key'] = "empty";  
}  	
	}

  }




if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
else
{
	$user_email = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM users WHERE id='".$_SESSION['login']."'"))['email'];
		$user_mobile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$_SESSION['login']."'"))['mobile_number'];

	//$transfer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT  * FROM tranfer WHERE receiver_id='".$_SESSION['login']."'"));
	  $transfer = mysqli_query($conn, "SELECT tranfer.*,users.name FROM tranfer INNER JOIN users ON users.id = tranfer.sender_id WHERE tranfer.receiver_id='".$_SESSION['login']."' && status = '0'");

	
	 //$sender_trans = $transfer['sender_id'];
	
	 

	
	
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
						<div style="margin:10px">
							<img src="qrcode/qrcode.php?text=<?php echo $user_mobile; ?>" style="width:100%">
						</div>
						<div style="padding:10px;">
							<h6>Or Send Money to This Number</h6>
							<h3 style="text-align:center;"><?php echo $user_mobile; ?></h3>
						</div>
					</div>
					
					<div class="col-md-3"></div>
					<?php while ($row = mysqli_fetch_array($transfer))   
							{ 
						$time_create = $row['created_on'];
						
					
					$current_time= date("m/d/Y h:i:s a", time());
						
 $d1 = date('Y-m-d H:i:s', $time_create);
 date('Y-m-d H:i:s', $current_time);
  $datetime1 = new DateTime(date('Y-m-d H:i:s', $time_create));
 $datetime2 = new DateTime($current_time);
    $interval = date_diff($datetime1, $datetime2);
    $day_cal= $interval->d;
	//~ print_r($interval);

							$stl_key = rand();
							$_SESSION['stl_key'] = $stl_key; 
							if($row['wallet'] == "INR")
							{
								$wat = "CNY";
							}
							else
							{
								$wat = $row['wallet'];
							}
						 if($day_cal == 0 ){	
					echo  '<form  action="" id="money_transfer" method="POST">';
					echo '<p> You have received amount '.$row['amount'].'-'.$wat.' from ' .$row['name'].'';
					//echo '<form id="myform" action="">';
					echo '<input type="hidden" name="id" value="'.$row['id'].'">';
					echo '<input type="hidden" name="stl_key" value="'. $stl_key.'">';
					echo '<button class="button" type="submit" onClick="window.location.reload()" name="submit" value="accept" id="accept" >Accept</button>';
					echo '<button class="button1" type="submit1" onClick="window.location.reload()" name="submit1" value="decline" id="decline">Decline</button>';
					//echo '</form>';
					echo '<br></p> </form>';
		 }
							}
							echo '<br>';
							 ?>

				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
</body>


</html>
<style>
.button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 8px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin:15px;
}
.button1 {
    background-color:red;
    border: none;
    color: white;
    padding: 8px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
     margin:15px;
}
form#money_transfer {
    width: 1000px;
    }
</style>
