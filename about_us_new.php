<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}
$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));

 $current_id = $bank_data['id'];
 
 $merchant_subscriptions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM merchant_subscription"));
  $current_uid = $_SESSION['login'];
$about = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM about WHERE userid='".$_SESSION['login']."'"));
 $product_type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM merchant_subscription WHERE user_id='".$_SESSION['login']."'"));
 
 $user_mobile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$_SESSION['login']."'"))['mobile_number'];

if(isset($_POST['submit']))
{
	
		$merchant_id =$_POST['id'];
		$description = addslashes($_POST['description']);
		$link = addslashes($_POST['link']);
		$welcome_note = addslashes($_POST['welcome_note']);
		$image = $_FILES["image_pic"]["name"];
		$video = $_FILES["video"]["name"];	
	//insert code //
	 if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if file was uploaded without errors
    if(isset($_FILES["image_pic"]) && $_FILES["image_pic"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image_pic"]["name"];
        $filetype = $_FILES["image_pic"]["type"];
        $filesize = $_FILES["image_pic"]["size"];
 
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");    
        
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("upload/" . $_FILES["image_pic"]["name"])){
                echo $_FILES["image_pic"]["name"] . " is already exists.";
            } else{
                move_uploaded_file($_FILES["image_pic"]["tmp_name"], "/home/kooexchange/public_html/demo/images/about_images/" . $_FILES["image_pic"]["name"]);
               // echo "Your file was uploaded successfully.";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } 
    
     
}
/*videos*/

$name= $_FILES['file']['name'];

$tmp_name= $_FILES['file']['tmp_name'];

$position= strpos($name, ".");

$fileextension= substr($name, $position + 1);

$fileextension= strtolower($fileextension);


if (isset($name)) {

$path= '/home/kooexchange/public_html/demo/images/videos';
    $uploadfile = $path . basename($_FILES['file']['name']);

if (empty($name))
{
 "Please choose a file";
}
else if (!empty($name)){
if (($fileextension !== "mp4") && ($fileextension !== "ogg") && ($fileextension !== "webm"))
{
echo "The file extension must be .mp4, .ogg, or .webm in order to be uploaded";
}


else if (($fileextension == "mp4") || ($fileextension == "ogg") || ($fileextension == "webm"))
{ 
if (move_uploaded_file($_FILES['file']['tmp_name'], "/home/kooexchange/public_html/demo/images/videos/" . $_FILES['file']['name'])) {
 //echo 'Uploaded!';
}
}
}
}
$name_image = $image != "" ? $image  : $_POST['image_up'];
$name_video = $name != "" ? $name  : $_POST['video_up'];

	if($about['userid'] != "")
	{
		   $id_abt = $_POST['id']; 
				 $test_video =  mysqli_query($conn, "UPDATE about SET description='$description',link='$link', welcome_note='$welcome_note',image='$name_image',video_upload='$name_video ' WHERE userid='$id_abt'");
 header('Location: '.$_SERVER['REQUEST_URI']);


  }
  else
  {
	 
	  		   $id_abt = $_POST['id']; 
	  	 

	  $insert_test  =  mysqli_query($conn, "INSERT INTO about SET userid='$id_abt',description='$description',link='$link',welcome_note='$welcome_note',image='$name_image',video_upload='$name_video'");
	// echo $insert_test  =  mysqli_query($conn, "INSERT INTO about SET description='$description',userid='$id_abt',link='$link',welcome_note='$welcome_note',image='$name_image',video_upload='$name_video");
	  header('Location: '.$_SERVER['REQUEST_URI']);

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
					<div class="container" >
					    <div class="row">
					        <div class="well col-md-10">
							<form method="post" method="post" enctype="multipart/form-data">
								<div class="panel price panel-red">
									<h2>About Us</h2>
									<br><br>
									<div class="form-group">
										<label>Welcome Note</label>
										<textarea class="welcome text_about" name="welcome_note" rows="10" cols="30"> <?php echo $about['welcome_note']; ?></textarea>
										<div class="form-group">
										<label>Company logo</label><br>
										<input type="file" name="image_pic">
										<input type="hidden" name = "image_up" value="<?php echo $about['image'] ?>" />
										<label><?php echo $about['image'] ?></label>
									</div>
										<label>Description</label>
										
										<br><br>
											<?php if($merchant_subscriptions['type'] = '1') {  ?>
											<textarea class="text_about" name="description" rows="4" cols="30"maxlength="500"> <?php echo $about['description']; ?></textarea>
											<?php } elseif($merchant_subscriptions['type'] = '2') { ?>
											<textarea class="text_about" name="description" rows="6" cols="30"maxlength="500"> <?php echo $about['description']; ?></textarea> 
											<?php } elseif($merchant_subscriptions['type'] = '3') {  ?>
											<textarea class="text_about" name="description" rows="8" cols="30"maxlength="1000"> <?php echo $about['description']; ?></textarea> 												  
											<?php } elseif($merchant_subscription['type'] = '4'){ ?>
											<textarea class="text_about" name="description" rows="8" cols="30"maxlength="3000"> <?php echo $about['description']; ?></textarea>
											
											
											<?php } else { ?>
											<textarea class="text_about" name="description" rows="10" cols="30"> <?php echo $about['description']; ?></textarea>
											<?php } ?>
											
											<?php 			//if(!empty($product_type['type']))
			//{?>
											<label>Link</label>
											<input type="text" name="link" class="link_text" value="<?php echo $about['link']; ?>">
											<br>
												<div class="form-group">
										<label>Video update</label><br>
<!--
										<input type="file" name="video">
-->

										<input type="hidden" name = "video_up" value="<?php echo $about['video_upload'] ?>" />

										<input type="file" name="file"/> <br><br>
										<label><?php echo $about['video_upload'] ?></label>
									</div>
									<?php //} ?>
																						
											
											<input type="hidden" id="id" name="id" value="<?php echo $current_id;?>"> 

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
textarea.text_about {
    width: 100%;
}
input.link_text {
    width: 100%;
    padding: 12px;
}
h3.text_qrcode {
    width: 100%;
}
</style>
