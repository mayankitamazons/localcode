<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
  $bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
  $subscription =mysqli_query($conn, "SELECT * FROM subscription ");
  $subscrtpion_test = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM subscription WHERE id='".$_SESSION['login']."'"));
   $current_id = $bank_data['id'];
   $data_usd=$bank_data['balance_usd'];
   $data_myr=$bank_data['balance_myr'];
if(isset($_POST['submit']))
{
	     $sender_id = $_SESSION['login'];
		  $product= $_POST['product'];
		  $wallet= $_POST['curr'];
		  $stl_key = $_POST['stl_key'];
		  $sub_date = date('Y-m-d H:i:s');
		
		 $subscription_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM subscription WHERE id='".$product."'"));
	 	 $amount = $subscription_data['subscription_rate'];
	 	 $type = $subscription_data['id'];

								
		 if($stl_key == $_SESSION['stl_key']) {
		 if($wallet == "INR")
		 {
			 $sender_new_balance = $bank_data['balance_inr'] - $amount;
			 mysqli_query($conn, "UPDATE users SET balance_inr='$sender_new_balance' WHERE id='$sender_id'");
		 }
		
		 else if($wallet == "MYR")
		 {
			 $sender_new_balance = $bank_data['balance_myr'] - $amount;
			mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='$sender_id'");
		}
		else if($wallet == "USD")
		{
			$sender_new_balance = $bank_data['balance_usd'] - $amount;
			 mysqli_query($conn, "UPDATE users SET balance_usd='$sender_new_balance' WHERE id='$sender_id'");
		}
		else
		{
			echo "System Info : An Error Occured"; die;
		}
			
	 
	   $res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM merchant_subscription WHERE user_id='".$_SESSION['login']."'"));
       
       
       if($current_id==$res['user_id'])
       {
               $tt = mysqli_query($conn,"UPDATE merchant_subscription SET type = '$amount',subscription_date='$sub_date' WHERE `user_id`='".$res['user_id']."'");

	}
       else
       {
		   
		    	
	   $ext=mysqli_query($conn, "INSERT INTO merchant_subscription SET type = '$type',user_id ='$current_id',subscription_date='$sub_date'"); 

	}   
		$_SESSION['stl_key'] = "empty";  
}
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
	.ordinary_plan {
    font-weight: 600;
}
.followings {
    font-weight: 600;
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
					<div class="container">
					<?php
						if(isset($error))
						{
							echo "<div class='alert alert-info'>".$error."</div>";
						}
					?>
					</div>
			
					<div class="container" >
					    <div class="row">
					        <div class="well col-md-10">
									<?php  
				 $subscription_dd = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM merchant_subscription WHERE user_id='".$_SESSION['login']."'and current_status = 1"));
				
				$subscription_detal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM subscription WHERE id='".$subscription_dd['type']."'"));
				 $subscription_detal['subscription_period'];
				 $date=date_create($subscription_dd['subscription_date']);
				 $period = $subscription_detal['subscription_period'] == '1 year' ? "1 year" : "1 month";
				 date_add($date,date_interval_create_from_date_string($period));
				 $nxt_suv_date =  date_format($date,"Y-m-d");
				 $sub_date = date('Y-m-d H:i:s');
				 if($nxt_suv_date <= $sub_date || empty($subscription_dd)) 	
				 {
					?>
					<p class="test_subscr">No subscription plan added</p>
<!--
							<form method="post" method="post" enctype="multipart/form-data">
								<div class="panel price panel-red">
									<h2>Upgrade Package</h2>
									
									
									<div class="form-group">

										<label>Additional Product</label><br>
										<?php 
										//while ($row=mysqli_fetch_assoc($subscription)){
										?> 

					<input type="radio" name="product" value="<?php echo $row['id'] ; ?>" > <?php echo $row['subscription_name'];  ?><?php echo ', Rate : ('.$row['subscription_rate'] .')';  ?><br>

									<?php// }
									?>
							<?php 
							//~ $stl_key = rand();
							//~ $_SESSION['stl_key'] = $stl_key;
							 ?>
							<input type="hidden" name="stl_key" value="<?php echo $stl_key; ?>">
							
									</div>
								

								
									    <select name="curr">
									    	<option value="USD">USD</option>
									    	<option value="MYR">MYR</option>
									    </select><br>
							
									   
									<br>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Submit">
								</div>
							</form>
							
--> 
							<?php
						}
						else
						{
	$subscription_detal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM subscription WHERE id='".$subscription_dd['type']."'"));
	if($subscription_dd['type'] !='1') {
		echo '<h4>Current Subscription plan</h4>';		

echo '<div class="ordinary_plan" >Your Subscription package is :  '.$subscription_detal['subscription_name'].'</div>';
echo '<br>';
echo '<br>';

						?>
								<table class="table table-striped  subscription_plan">
								<tbody>
								<?php echo '<div class="followings">Your Package includes the followings </div>'; ?>
								
								<tr>
									<td>1</td>
								<td>The package rate is</td>
								<td> <?php echo $subscription_detal['subscription_rate'] ;?></td>
								</tr>
								<tr>
									<td>2</td>
								<td>Your subscription plan ending date is :</td>
								<td><?php echo $nxt_suv_date ?></td>
								</tr>
								<tr>
								<td>3</td>
								<td>Categories Creation</td>
								<td><?php echo $subscription_detal['categories'] ;?></td>
								</tr>
								 
								<tr>
									<td>4</td>
								<td>Product Listing</td>
								<td><?php echo $subscription_detal['subscription_qyt'] ;?> Items</td>
								</tr>
								<tr>
									<td>5</td>
								<td>Display of Company Website</td>
								<td> <?php echo $subscription_detal['company_website'] ;?></td>
								</tr>
								<tr>
									<td>6</td>
								<td>Upload of company Video</td>
								<td> <?php echo $subscription_detal['company_video'] ;?></td>
								</tr>
								<tr>
									<td>7</td>
								<td>Free Advertisement for Customer</td>
								<td> <?php echo $subscription_detal['advertisement_sms'] ;?></td>
								</tr>
								<tr>
									<td>8</td>
								<td>Words for "About us</td>
								<td> <?php echo $subscription_detal['about'] ;?></td>
								</tr>
								
								</tbody>
							
						</table>
						
						<?php }
						else {
						echo '<p class="test_subscr"> No subscription plan added</p>';
						}
						
						
						  }
						?>
						 
						<?php
						 $subscription_dd_old = mysqli_query($conn, "SELECT * FROM merchant_subscription WHERE user_id='".$_SESSION['login']."'and current_status = 2");
						  $subscription_count = mysqli_num_rows($subscription_dd_old);
		              if($subscription_count > 0) 
        {   
						echo '<h4>Past Subscription plans</h4>';	
						while($row=mysqli_fetch_assoc($subscription_dd_old))
						{

$subscription_old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM subscription WHERE id='".$row['type']."'"));
 
 if($subscription_old['subscription_name'] != 'Trial Plan') { 
echo '<div class="ordinary_plan" >Your Past Subscription package is :  '.$subscription_old['subscription_name'].'</div>';
echo '<br>';
echo '<br>';

						?>
								<table class="table table-striped  subscription_plan">
								<tbody>
								<?php echo '<div class="followings">Your Package includes the followings </div>'; ?>
								
								<tr>
									<td>1</td>
								<td>The package rate is</td>
								<td> <?php echo $subscription_old['subscription_rate'] ;?></td>
								</tr>
																<tr>
								<td>2</td>
								<td>Categories Creation</td>
								<td><?php echo $subscription_old['categories'] ;?></td>
								</tr>
								 
								<tr>
									<td>3</td>
								<td>Product Listing</td>
								<td><?php echo $subscription_old['subscription_qyt'] ;?> Items</td>
								</tr>
								<tr>
									<td>4</td>
								<td>Display of Company Website</td>
								<td> <?php echo $subscription_old['company_website'] ;?></td>
								</tr>
								<tr>
									<td>5</td>
								<td>Upload of company Video</td>
								<td> <?php echo $subscription_old['company_video'] ;?></td>
								</tr>
								<tr>
									<td>6</td>
								<td>Free Advertisement for Customer</td>
								<td> <?php echo $subscription_old['advertisement_sms'] ;?></td>
								</tr>
								<tr>
									<td>7</td>
								<td>Words for "About us</td>
								<td> <?php echo $subscription_old['about'] ;?></td>
								</tr>
								
								</tbody>
							
						</table>
					
						<?php } }
		} ?>
						
						</div>
						
					</div>
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
select {
    height: 30px;
}

p.test_subscr {
    font-weight: 600;
    font-size: 20px;
}

</style>
