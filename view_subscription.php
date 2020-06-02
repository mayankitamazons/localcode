<?php 
include("config.php");
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$subscription_set = mysqli_fetch_assoc(mysqli_query($conn, "SELECT membership_plan FROM users WHERE id ='".$_SESSION['login']."'"));
$subscription_set = $subscription_set['membership_plan'];
if ($subscription_set == 0 || empty($subscription_set)) {
	header('location: dashboard.php');
}
$site_url="https://www.koofamilies.com/";
$plan_id = $_GET['plan_id'];
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
$plan_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM membership_plan WHERE id='".$plan_id."'"));
// print_R($bank_data);
// die;

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
								<!--div class="row">
								<div class="col-3"><h4><small>Plan Image:</small></h4></div>
								<div class="col-6"><img src="uploads/<?php echo $plan_data['plan_img'] ?>" alt="plan_img" style="width: 50%;"></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small>Belongs to:</small></h4></div>
								<div class="col-6"><h4><?php echo $_SESSION['name'] ?></h4></div>
								</div!-->
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['plan_name']; ?>:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['plan_name'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['order_min']; ?> Amount:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['total_min_order_amount'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['order_max']; ?> Amount:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['total_max_order_amount'] ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['discount']; ?>:</small></h4></div>
								<div class="col-6"><h4>
								<?php $plan_type=$plan_data['plan_type']; 
										if($plan_type=="fix")
										$plan_label="Rm ".$plan_data['plan_benefit']." off";
										else 
											$plan_label=$plan_data['plan_benefit']." % off";
										echo $plan_label;
									?>
								</h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['valid_from']; ?>:</small></h4></div>
								<div class="col-6"><h4><?php if(($plan_data['valid_from']!='0000-00-00 00:00:00')){ echo date('M d, Y', strtotime($plan_data['valid_from']));} else { echo "--";}  ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['valid_to']; ?>:</small></h4></div>
								<div class="col-6"><h4><?php if(($plan_data['valid_from']!='0000-00-00 00:00:00')){ echo date('M d, Y', strtotime($plan_data['valid_from']));} else { echo "--";}  ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small>Plan Description:</small></h4></div>
								<div class="col-6"><h4><?php echo $plan_data['plan_desc'] ?></h4></div>
								</div>
								
								
								<div class="row">
								<div class="col-3"><h4><small><?php echo $language['status']; ?>:</small></h4></div>
								<div class="col-6"><h4><?php if($plan_data['status']) echo 'Active'; else echo 'Inactive' ?></h4></div>
								</div>
								<div class="row">
								<div class="col-3"><h4><small>Plan Created on:</small></h4></div>
								<div class="col-6"><h4><?php echo date('F d, Y', strtotime($plan_data['created'])); ?></h4></div>
								</div> 
								<div class="row">
								<div class="col-3"><h4><small> Default:</small></h4></div>
								<div class="col-6"><h4><?php $default_plan=$plan_data['default_plan']; if($default_plan=="y")echo "Yes"; else echo "No"; ?></h4></div>
								</div>
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

   
    