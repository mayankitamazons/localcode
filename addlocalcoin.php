<?php 
include('config.php');
$conn2 = mysqli_connect("166.62.120.154", "koofamil_B277", "rSFihHas];1P", "koofamil_B277");

if(!$conn2)
{
	echo "Failed to Connect over Live Website";
	die;
}
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
 session_start();
}
error_reporting(0);
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}       
// print_R($bank_data);
// die;
if(isset($_POST['submit']))
{	
	$mobile = $_POST['mobile_number'];
	$local_coin = $_POST['local_coin'];
	$merchant_id = $_SESSION['login'];
	// echo $query="SELECT users.id,user_membership_plan.user_id FROM `users` INNER JOIN `user_membership_plan` ON users.id = user_membership_plan.user_id WHERE users.mobile_number='$mobile' and user_membership_plan.merchant_id ='$merchant_id'";
	
	$data = mysqli_num_rows(mysqli_query($conn2, "SELECT users.id,user_membership_plan.user_id FROM `users` INNER JOIN `user_membership_plan` ON users.id = user_membership_plan.user_id WHERE users.mobile_number='$mobile' and user_membership_plan.merchant_id ='$merchant_id'"));
	// $data = mysqli_num_rows(mysqli_query($conn2, "SELECT id,name,mobile_number FROM `users` WHERE `mobile_number`= $mobile"));
	
	if ($data>0) {  
		$data_user = 0;
		$user = mysqli_fetch_assoc(mysqli_query($conn2, "SELECT id,name,mobile_number FROM `users` WHERE `mobile_number`= '$mobile'"));
		$user_doesnt_exist = 0;
		$successful = 0;
		$user_id = $user['id'];  
		// $plan_id = $_GET['plan_id'];
		
		$paid_via = "cash";
		$date = date('Y-m-d H:i:s');
		// var_dump($date);
		// exit;   
		if (is_null($user)) {
			$user_doesnt_exist = 1;
			$successful = 0;
		}
		else{  
			
			$insert=mysqli_query($conn2, "INSERT INTO `local_coin_sync`(`merchant_id`, `user_id`, `user_mobile`, `local_coin`) VALUES('$merchant_id', '$user_id', '$mobile', '$local_coin')");
			$successful = 1;
			if($insert)
			{
				
			}
		}
	}else{
		$data_user = 1;
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
	.account_kType{
	    margin-bottom: 10px;
	}
	/* Jupiter 24.02.19*/
	.payment_tick{
		width: 20px;
		height: 20px;
		margin-right: 15px;
	}
	.payment_label{
		margin-top: -27px;
    	margin-left: 30px;
	}
	.payment_btn{
		margin-left: 125px;
	    display: block;
	    margin-bottom: 15px;
	    margin-top: -45px;
	    line-height: 0.57143;
	}
	.custom_message_val{
		width: 100%;
		height: 200px;
		padding: 5px;
		box-sizing: border-box;
		border-radius: 5px;
		border: 1px solid #e4e9f0;
		resize: none;
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
					        <div class="well col-md-12">
					        	<?php if ($successful): ?>
					        		<h4>Local Point Added Successfully!</h4>
					        	<?php endif ?>
					        	<?php if ($user_doesnt_exist): ?>
					        		<h4>The specified User Doesn't Exist!</h4>
					        	<?php endif ?>
					        	<?php if ($data_user): ?>
					        		<h4>This member <?php echo $_POST['mobile_number']; ?> is not under subscription, please add him first!</h4>
					        	<?php endif ?>
								<form method="post" enctype="multipart/form-data" id="profile_account" action="">
									<div class="panel price panel-red">
										<h2>Add Local Point </h2>
									</div>
									<div class="row">
										<div class="form-group col-md-4">
											<label>User Mobile Number</label>
											<input type="text" required name="mobile_number" value="<?php echo $mobile_get; ?>" class="form-control" placeholder="Enter User's Mobile Number">
										</div>
										<div class="form-group col-md-4">
											<label>Local point</label>
											<input type="text" required name="local_coin" class="form-control" placeholder="Local Point">
										</div>
									</div>
									<button type="submit" value="submit" name="submit" class="btn btn-primary">Submit</button>
								</form>
							</div>
						</div>
					</div>
				</div>				
			</main>
        </div>
        
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
</style>

    </script>   
<style>
  .tele_num{
	font-weight: 400;
    display: block;
    width: 345%;
    padding: 0.5625rem 1.2em;
    font-size: 0.875rem;
    line-height: 1.57143;
    color: #74708d;
    background-color: #fff;
    background-image: none;
    background-clip: padding-box;
    border: 1px solid #e4e9f0;
    border-radius: 0.25rem;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
}
h3.text_qrcode {
    width: 100%;
}
.credit_card{
 display:none;
}
.branch_details{
display:none;
}
div#multiSelectCombo {
    width: 450px!important;
}
</style>
<script src="jquery-1.9.1.min.js"></script>





    <script src="http://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>

   
    