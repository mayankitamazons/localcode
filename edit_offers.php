
<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

$id = $_SESSION['login'];
if(isset($_POST['submit'])){

	if($_POST['update']=="update")
	{
	
	$words = $_POST['offer_stm'];
	//$id = $_SESSION['login'];
	$info =mysqli_query($conn,"UPDATE  offers_statement SET discp='$words' where merchant_id='$id'");
	if($info)
	{
		$error = "Data Updated Successfully.";
		echo"<script> setTimeout(function() {
		    $('#error').fadeOut('show');
		}, 500);</script>";


	}else
	{
		$error = "Data Updated Unsuccessfully.";
	
	}
  }	
header("location:offers.php");

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
					<div class="container" id="error">
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
				        	<form method="post">
							<div class="form-group">
								<label>Offers Statement</label>
								<?php 
									 $edit_id = $_GET['Edit'];
										$whr = mysqli_query($conn, "select * from offers_statement where id='$edit_id'");
										$row = mysqli_fetch_array($whr);
										
										$discp = $row['discp'];
									?>
								
								<input type="text" name="offer_stm" class="form-control" style="margin:5px 0;" value="<?php if( $edit_id !=null){echo $discp; }?>" required>
								
							</div>
							<input type="hidden" name="update" value="update">
							<input type="Submit" id="submit" name="submit" class="btn btn-primary btn-lg btn-block" value="Update">
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