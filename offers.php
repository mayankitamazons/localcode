<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

$id = $_SESSION['login'];
if(isset($_POST['submit'])){

	if($_POST['insert']=="insert")
	{
		$sql = "select * from offers_statement where merchant_id =$id";
		$rel = mysqli_query($conn,$sql);

		if (mysqli_num_rows($rel) > 0) {
			echo"<script>alert('Offer already set,you have only edit or delete');</script>";
	  	  
	  	}else{

	  		$words = $_POST['offer_stm'];
	//$id = $_SESSION['login'];
	$info =mysqli_query($conn,"INSERT INTO offers_statement(merchant_id,discp) value('$id','$words')");
	if($info)
	{
		$error = "Data Inserted Successfully.";
		echo"<script> setTimeout(function() {
		    $('#error').fadeOut('show');
		}, 500);</script>";


	}else
	{
		$error = "Data Inserted Unsuccessfully.";
	
	}
	  	}	
	
	
  }	
//header("location:offers.php");

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
								
								<input type="text" name="offer_stm" class="form-control" style="margin:5px 0;" required>
								
							</div>
							<input type="hidden" name="insert" value="insert">
							<input type="Submit" id="submit" name="submit" class="btn btn-primary btn-lg btn-block" value="Submit">
							</form>
						</div>
					</div>
					
				</div>
				<div class="container" >
					    <div class="row">
					        <div class="well col-md-12">
					        	<table class="table table-striped">
									        <thead>
                                                <tr>
                                                    <th>Description</th>
                                                 
                                                    <th>Action</th>    
                                              </tr>
                                           </thead>
                                           <tbody >
                                           	<?php 
                                           	$sql = mysqli_query($conn, "SELECT * FROM offers_statement WHERE merchant_id='".$id."'");
                                           	
                                           	while($data = mysqli_fetch_array($sql))
                                           	 {
                                           	 	
                                           	 	echo'<tr><td>'.$data['discp'].'</td>
                                           	 	
                                           		<td><a href="edit_offers.php?Edit='.$data['id'].'" id="'.$data['id'].'" class="trash" style="margin-right:5%">Edit</a><a href="" class="trash" onclick="return onclickDelete('.$data['id'].')">Delete</a></td></tr>';
                                           	
                                              }

                                           	?>
                                          
                                         	</tbody>
                                         	
									    </table>

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

<script type="text/javascript">
	

function onclickDelete(del_Id){
	//alert(del_Id);
    $.ajax({
        type: "get",
        url: "offer_detele.php",
        data: {
            del_Id:del_Id
        },
        success: function (data){
            alert(data);
           
        },
        error: function (xhr, ajaxOptions, thrownError){
        		alert(data);
        }
       
    });
    //return false;
}

</script>