<?php 
include("config.php");

    if(!isset($_SESSION['login']))
    {
    	header("location:login.php");
    }
    $user_account = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
    $result_subscribe = "";
    if(isset($_POST['subscribe'])){
        $referral_id = $_POST['referral_id'];
        $mobile_number = $_POST['userphone'];
        $name = $_POST['username'];
        $date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO subscribes SET name='$name', mobile_number='$mobile_number', referral_id='$referral_id', created_dt='$date'";
        mysqli_query($conn, $sql);
        $result_subscribe = "Subscribe Successfully!";
    }
    
    $merchants_result = mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2'");
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
		text-align: center;
	}
	.subscribe{
	    font-size: 2rem;
	    line-height: 3rem;
	}
	@media (max-width: 900px) and (min-width: 360px){
	    .subscribe{
	        font-size: 1.5rem;
	        line-height: 2rem;
	    }
	    .main-wrapper{
	        padding: 0 0.5rem 2.5rem;
	    }
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
					</div>
					<div class="container" >
					    <div class="row">
					        <div class="well col-md-12">
					            <form method="post" action="">
					                <input type="hidden" name="username" value="<?php echo $user_account['name'];?>" >
					                <input type="hidden" name="userphone" value="<?php echo $user_account['mobile_number'];?>">
					                <input type="hidden" name="referral_id" value="<?php echo $user_account['referral_id'];?>">
					                <input type="hidden" name="subscribe" value="subscribe">
					                <h2 class="subscribe">We are launching a crowdfunding campaign soon. You can now subscribe to our newsletter for special discount and offer.</h2>
							        <button class="btn btn-primary subscribe_btn" type="submit">Subscribe <i class='fa fa-share-alt-square'></i></button>
					                <h4 style="color:green;"><?php echo $result_subscribe;?></h4>
					            </form>
							    
							</div>
						</div>
						<div class="row">
					        <div class="well col-md-12">
							    <h2 class="subscribe">Our current business partners.</h2>
							    <button class="btn btn-primary" data-toggle="collapse" data-target="#merchant">Click me <i class='fa fa-address-book'></i></button>
							</div>
							<div class="collapse" id="merchant" style="margin: 0 auto;">
							    <table class="table table-striped">
							        <thead>
							            <th>Avatar</th>
							            <th>Name</th>
							        </thead>
							        <tbody>
							            <?php while ($row=mysqli_fetch_assoc($merchants_result)){ ?>
							            <tr>
							                <td>
							                    <?php if($row['image'] != ""){ ?>
							                        <img style="height: 50px;" src="<?php echo $row['image'];?>">
							                    <?php }?>
							                </td>
							                <td><?php echo $row['name'];?></td>   
							            </tr>
							            <?php }?>
							        </tbody>
							    </table>
							</div>
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
