<?php 
include("config.php");
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$loginidset."'"));
$Cat_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM cat_mater WHERE UserID='".$loginidset."'"));
 $current_id = $bank_data['id'];
   $current_date= date("Y/m/d");
if(isset($_POST['submit']))
{
    if($Cat_data['CMasterID'] > 0 ){
        
        $categoryname = implode(',' , $_POST['categoryname']); 
         $categoryname=mysqli_real_escape_string($conn,$categoryname);
		
		 mysqli_query($conn, "update  cat_mater SET CatName='$categoryname' where UserID='$current_id'");
        
        /**
        
        $OldCat = implode(',' , $_POST['categoryname']); 
        $OldArrayCat = explode(',' ,  $Cat_data['CatName'] );
        $differntarray = array_diff($_POST['categoryname'] , $OldArrayCat) ;
        foreach($differntarray as $valuearray){
        mysqli_query($conn, "update  category SET catparent='$valuearray' where UserID='$current_id' and catparent = '$valuearray'");
        }
        **/
    
    }else{
        $categoryname = implode(',' , $_POST['categoryname']);
     
      $categoryname=mysqli_real_escape_string($conn,$categoryname);
    mysqli_query($conn, "INSERT INTO  cat_mater SET CatName='$categoryname',UserID='$current_id',DateAdded='$current_date'");
    }    
	
    header("location:add_mater_category.php");
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
					<div class="container" >
					    <div class="row">
					        <div class="well col-md-10">
							<form method="post" method="post" enctype="multipart/form-data">
								<div class="panel price panel-red">
									<h2>Master Category Details</h2>
									<br><br>
									<?php
									$M_Cat_array = explode(',' , $Cat_data['CatName']);
									
									?>
									<div class="form-group">
										<label>mater category list</label>
										<input type="text" name="categoryname[]" class="form-control" value="<?php echo  @$M_Cat_array[0] ?>" required /> <br/>
										<input type="text" name="categoryname[]" class="form-control" value="<?php echo  @$M_Cat_array[1] ?>" required /><br/>
										<input type="text" name="categoryname[]" class="form-control" value="<?php echo  @$M_Cat_array[2] ?>" required /><br/>
										<input type="text" name="categoryname[]" class="form-control" value="<?php echo  @$M_Cat_array[3] ?>" required /><br/>
									</div>
									<br>
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
<style>
select {
    height: 30px;
}
</style>