<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

if(isset($_POST['submit']))
{
	$query_type = addslashes($_POST['query_type']);
	$subject = addslashes($_POST['subject']);
	$message = addslashes($_POST['message']);
	
	$flag = false;
	$error = "";
	
	if($query_type == "" || $subject == "" || $message == "")
	{
		$flag = true;
		$error .= "All fields are required.<br>";
	}
	
	if(!in_array($query_type, array("1", "2")))
	{
		$flag = true;
		$error .= "Query Type is not Valid.<br>";
	}
	
	if($flag == false)
	{
		mysqli_query($conn, "INSERT INTO contacts SET user_id='".$_SESSION['login']."', subject='".$subject."', message='".$message."', query_type='".$query_type."', created_on='".time()."'");
		$error = "Contact Request Successfully Submitted.<br>";
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
					<div class="container-fluid" >
						<div class="well col-md-6" style="margin:0px auto;">
							<form method="post" onsubmit="return change_amount()">
								<div class="panel price panel-red" style="padding:20px 5px;">
									<h2>Contact</h2>
									<br>
									<h4 style="font-size:1.2rem">Kemajuan Ladang Cemerlang Sdn. Bhd.</h4>
									<h5 style="font-size: 0.9rem">No. 1400, Jalan Lagenda 50,</h5>
									<h5 style="font-size: 0.9rem">Taman Lagenda Putra, </h5>
									<h5 style="font-size: 0.9rem">81000, Kulai, Johor </h5>
									<h5 style="font-size: 0.9rem; margin-bottom: 15px;">Malaysia </h5>
									<h5 style="font-size: 0.9rem; margin-bottom: 10px;">Telephone / Fascimile : +607-6626205 </h5>
									<h5 style="font-size: 0.9rem; margin-bottom: 25px;">Email : wjchong@koofamilies.com </h5>
									<div class="form-group">
										<label>Query Type</label>
										<select class="form-control" name="query_type" required>
											<option value="1">Feedback</option>
											<option value="2">Complain</option>
										</select>
									</div>
									<div class="form-group">
										<label>Subject</label>
										<input type="text" class="form-control" name="subject" placeholder="Enter Subject Here" required>
									</div>
									<div class="form-group">
										<label>Message</label>
										<textarea class="form-control" name="message" placeholder="Enter Message Here" required></textarea>
									</div>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Submit">
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