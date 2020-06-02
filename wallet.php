<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
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
					<div class="container">
					<?php
						if(isset($_GET['message']))
						{
							echo "<div class='alert alert-info'>".$_GET['message']."</div>";
						}

						if(isset($_GET['status']) && $_GET['status'] == "cancel")
						{
							echo "<div class='alert alert-info'>Wallet Recharge Cancelled.</div>";
						}
					?>
					</div>
					<div class="row" style="margin:10px;">
						<div class="well col-md-4">
							<form action="<?php echo $paypalUrl; ?>" method="post">
								<div class="panel price panel-red" style="padding:50px 5px;">
									<input type="hidden" name="business" value="<?php echo $paypalId; ?>">
									<input type="hidden" name="cmd" value="_xclick">
									<input type="hidden" name="item_name" value="Wallet Recharge - MYR">
									<input type="hidden" name="item_number" value="1">
									<input type="hidden" name="no_shipping" value="1">
									<input type="hidden" name="currency_code" value="MYR">
									<input type="hidden" name="cancel_return" value="<?php echo $paypal_cancel_url; ?>">
									<input type="hidden" name="return" value="<?php echo $paypal_success_url; ?>">
									
									<h2>Recharge Your Wallet (MYR)</h2>
									<br><br>
									<input type="number" class="form-control" name="amount" placeholder="Amount (in MYR)">
									<br><br>
									<input type="submit" class="btn btn-block btn-primary" value="Recharge">
								</div>
							</form>
						</div>
						<div class="well col-md-4">
							<form action="<?php echo $paypalUrl; ?>" method="post">
								<div class="panel price panel-red" style="padding:50px 5px;">
									<input type="hidden" name="business" value="<?php echo $paypalId; ?>">
									<input type="hidden" name="cmd" value="_xclick">
									<input type="hidden" name="item_name" value="Wallet Recharge - USD">
									<input type="hidden" name="item_number" value="1">
									<input type="hidden" name="no_shipping" value="1">
									<input type="hidden" name="currency_code" value="USD">
									<input type="hidden" name="cancel_return" value="<?php echo $paypal_cancel_url; ?>">
									<input type="hidden" name="return" value="<?php echo $paypal_success_url; ?>">
									
									<h2>Recharge Your Wallet (USD)</h2>
									<br><br>
									<input type="number" class="form-control" name="amount" placeholder="Amount (in USD)">
									<br><br>
									<input type="submit" class="btn btn-block btn-primary" value="Recharge">
								</div>
							</form>
						</div>

						<div class="well col-md-4">
							<form action="" method="post">
								<div class="panel price panel-red" style="padding:50px 5px;">
									
									
									<input type="hidden" name="business" value="<?php echo $paypalId; ?>">
									<input type="hidden" name="cmd" value="_xclick">
									<input type="hidden" name="item_name" value="Wallet Recharge - CNY">
									<input type="hidden" name="item_number" value="1">
									<input type="hidden" name="no_shipping" value="1">
									<input type="hidden" name="currency_code" value="CNY">
									<input type="hidden" name="cancel_return" value="<?php echo $paypal_cancel_url; ?>">
									<input type="hidden" name="return" value="<?php echo $paypal_success_url; ?>">
									
									
									
									<h2>Recharge Your Wallet (Koo Coin)</h2>
									<br><br>
									<input type="number" class="form-control" name="amount" placeholder="Amount (in CNY)">
									<br><br>
									<input type="submit" name="submit" class="btn btn-block btn-primary" value="Recharge">
								</div>
							</form>
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
