<?php 
include("config.php");

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	<style>
		.create_date
		{
			float: right;
		}
		
		.comment_box {
    border: 1px solid #ccc;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    margin-top: 15px;
    box-shadow: 0 0 5px 0px;
	}
		.submit_button
		{
			width:25% !important;
		}
		.comment{
			width:90%;
		}
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
	.pro_name
	{   
	 text-align: center;
    font-size: 22px;
    font-weight: 600;
    margin: 10px 0px;
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

<div class="well col-md-12"> 
	<?php
	$comment_valid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rating WHERE merchant_id='".$id."' ORDER BY 'Created_on' DESC"));
	$date=date_create($comment_valid['Created_on']);
	
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Good' and merchant_id='".$_SESSION['login']."'" ));
	echo 'Good : ('.$rating['rate'].')' ; ?> 
	
	<?php 
	
	
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Neutral' and merchant_id='".$_SESSION['login']."'" ));
	echo 'Normal : ('.$rating['rate'].')' ; ?>
	<?php
	$rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id) as rate FROM rating WHERE rating='Poor' and merchant_id='".$_SESSION['login']."'" ));
	echo 'Poor : ('.$rating['rate'].')' ; ?>
		
  <br><br>
    
  <?php
  $rate_comment = mysqli_query($conn, "SELECT * FROM rating WHERE merchant_id ='".$_SESSION['login']."' ORDER BY `Created_on` DESC
");
 
   ?>
  <?php 
   
	while ($row=mysqli_fetch_assoc($rate_comment)){
 
	$name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id ='".$row['user_id']."'"));	
  ?>
  <div class="comment_box">
  <p><?php echo $row['comment'];  ?></p>
  <p><?php echo 'Rating : ' .$row['rating']; ?></p>
  <span class="name_cm"><?php echo 'Name : ' .$name['name'];  ?></span>
  <span class="create_date"><?php echo $row['Created_on']; ?></span>
  
  </div>
   
 <?php } 	?>
  


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
  

