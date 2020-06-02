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
 $current_id = $bank_data['id'];
 $stock_inventory = $bank_data['stock_inventory'];
 $discounted_product = $bank_data['discounted_product'];

	$total_rows = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$loginidset."' and status=0");

if(isset($_POST['submit']))
{
	
		//~ $currentDir = getcwd();
	    //~ $uploadDirectory = "koofamilies.com/images/product_images/";
		$productname = addslashes($_POST['productname']);
		// $category = addslashes($_POST['category']);
		$category_id = $_POST['category_id'];
		$product_type = addslashes($_POST['product_type']);
		$product_price = addslashes($_POST['product_price']);
		$remark = addslashes($_POST['product_remark']);
		$print_ip_address=$_POST['print_ip_address'];
		$printer_ip_2=$_POST['printer_ip_2'];
		$printer_profile=$_POST['printer_profile'];
		$usb_name=$_POST['usb_name'];
		$total_stock=$_POST['total_stock'];
		$reorder_level=$_POST['reorder_level'];
		$stock_value=$_POST['stock_value'];
		$supplier_id=$_POST['supplier_id'];
		$parent_id=$_POST['parent_id'];
		$maintain_stock=$_POST['maintain_stock'];
		$product_discount=$_POST['product_discount'];
		$image = $_FILES["image_pic"]["name"];	
		$code = $_FILES["code_pic"]["name"];
     if($category_id)
 {
	$categories = mysqli_query($conn, "SELECT id,category_name FROM category WHERE user_id ='".$loginidset."'and id ='".$category_id."'");
	$categoryrow=mysqli_fetch_assoc($categories);
	// print_R($categoryrow);
	// die;
	$category_name=$categoryrow['category_name'];
 }
     if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if file was uploaded without errors
    if(isset($_FILES["image_pic"]) && $_FILES["image_pic"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg","JPEG" => "image/JPEG", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image_pic"]["name"];
        $filetype = $_FILES["image_pic"]["type"];
        $filesize = $_FILES["image_pic"]["size"];
        
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 5MB maximum
        //~ $maxsize = 5 * 1024 * 1024;
        //~ if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("upload/" . $_FILES["image_pic"]["name"])){
                echo $_FILES["image_pic"]["name"] . " is already exists.";
            } else{
                move_uploaded_file($_FILES["image_pic"]["tmp_name"], "/images/product_images/" . $_FILES["image_pic"]["name"]);
               // echo "Your file was uploaded successfully.";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    }
    if(isset($_FILES["code_pic"]) && $_FILES["code_pic"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg","JPEG" => "image/JPEG", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["code_pic"]["name"];
        $filetype = $_FILES["code_pic"]["type"];
        $filesize = $_FILES["code_pic"]["size"];
        
        
   
        
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 5MB maximum
        //~ $maxsize = 5 * 1024 * 1024;
        //~ if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("upload/" . $_FILES["code_pic"]["name"])){
                echo $_FILES["code_pic"]["name"] . " is already exists.";
            } else{
                move_uploaded_file($_FILES["code_pic"]["tmp_name"], "/images/product_images/" . $_FILES["code_pic"]["name"]);
               // echo "Your file was uploaded successfully.";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } 
}
       // print_R($_POST);
	   // die;
		if($product_discount=='')
			$product_discount='';
      $current_date= date("Y/m/d");  
	  if($stock_inventory=="on")
	  {
		$qu1="INSERT INTO products SET product_discount='$product_discount',maintain_stock='$maintain_stock',pending_stock='$total_stock',total_stock='$total_stock',reorder_level='$reorder_level',stock_value='$stock_value',supplier_id='$supplier_id',parent_id='$parent_id',product_name='$productname',user_id='$current_id', category='$category_name',category_id='$category_id',product_type='$product_type',product_price='$product_price', remark = '$remark', image='$image', code='$code',created_date='$current_date',print_ip_address='$print_ip_address',printer_ip_2='$printer_ip_2',printer_profile='$printer_profile',usb_name='$usb_name'";
		$insert=mysqli_query($conn,$qu1);
		 $inserted_id=mysqli_insert_id($conn);
	
		if($parent_id>0)
		{
			$sdetail= mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='".$parent_id."'"));
			// print_R($sdetail);
			// die;
			if($sdetail)
			{
				$old_pending_stock=$sdetail['pending_stock'];
				$old_total_stock=$sdetail['pending_stock'];
				$new_pen_stock=$old_pending_stock+$total_stock;
				 $new_total_stock=$old_total_stock+$total_stock;
				 if($new_pen_stock>0)
				$update=mysqli_query($conn, "UPDATE products SET on_stock='1',pending_stock='$new_pen_stock',total_stock='$new_total_stock' WHERE id='$parent_id'");
				else 
				$update=mysqli_query($conn, "UPDATE products SET pending_stock='$new_pen_stock',total_stock='$new_total_stock' WHERE id='$parent_id'");
				
					
			}
			
			$qu="INSERT INTO `inventory_stock` (`product_id`, `stock_count`, `stock_type`, `supplier_id`, `comment`,`child_id`) VALUES ('$parent_id','$total_stock', 'in', '$supplier_id', 'newchildproduct','$inserted_id')";
		
		}
		else
		{
			$qu="INSERT INTO `inventory_stock` (`product_id`, `stock_count`, `stock_type`, `supplier_id`, `comment`) VALUES ('$inserted_id','$total_stock', 'in', '$supplier_id', 'newproduct')";
		
		}
		
		
		mysqli_query($conn,$qu);
		
	  }
	  else
	  {
		$qu1="INSERT INTO products SET product_name='$productname',user_id='$current_id', category='$category_name',category_id='$category_id',product_type='$product_type',product_price='$product_price', remark = '$remark', image='$image', code='$code',created_date='$current_date',print_ip_address='$print_ip_address',printer_ip_2='$printer_ip_2',printer_profile='$printer_profile',usb_name='$usb_name'";
		$insert=mysqli_query($conn,$qu1);  
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
	  <link href="css/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
	  
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
									<h2>Product Details</h2>
									<br><br>
									<div class="form-group">
										<label>Product Name</label>
										<input type="text" name="productname" maxlength="35" class="form-control" value="" required>
									</div>
								<?php	if(!empty($product_type['type']))
			{?>
									<div class="form-group">
										<label>Category</label>
<!--
										<input type="text" name="category"  value="" required>
-->
										
										<select  name="category_id" class="form-control">
									
											<?php 
										while ($row=mysqli_fetch_assoc($total_rows))
	{                                   ?>
								 <option value="<?php echo $row['id'];?>"><?php echo $row["category_name"];?></option>
								 <br>
							<?php }
								?>
										</select>
									</div>
									<?php } ?>   
									<div class="form-group">
										<label>Product Price</label>
										<input type="text" name="product_price" class="form-control" value="" >
									</div>
									<div class="form-group">
										<label>Product Code</label>
										<input type="text" name="product_type" class="form-control" value="" >
									</div>
									 <div class="form-group">
                                        <label>Printer Type <?php  $printer_profile=$row['printer_profile']; ?></label>
										
                                        <select class='' name="printer_profile" style="">
										  <option <?php if($printer_profile == 'ip') echo 'selected'; ?> value="ip">IP PRINTER</option>
                                            <option <?php if($printer_profile == 'usb') echo 'selected'; ?> value="usb">USB</option>
                                          
                                        </select>
                                    </div>
									<div class="form-group">
										<label>USB Sharing Name </label>
										<input type="text" name="usb_name" class="form-control"/>
									   <p>Hint :From the Control Panel, open Devices and Printers.
										Right-click the printer you want to share. Click Printer Properties, and then select the Sharing tab.
										Check Share this Printer. Under Share name, select a shared name to identify the printer. Click OK. </p>
									</div>  
									<div class="form-group">
										<label>Print Ip address</label>
										<input type="text" name="print_ip_address" class="form-control" value="" >
									</div>
									<div class="form-group">
										<label>Print Ip address 2</label>
										<input type="text" name="printer_ip_2" class="form-control" value="" >
									</div>

									<div class="form-group">
										<label>Remark</label>
										<input type="text" name="product_remark" class="form-control" value="" >
									</div>
									<?php if($discounted_product){ ?>
									
									<div class="form-group">
										<label>Discount (in %)</label>
										<input type="text" name="product_discount" class="form-control" placeholder="Discount on Product in %">
									</div>
									<?php } ?>
									<div class="form-group">
										<label>Image</label><br>
										<input type="file" name="image_pic">
									</div>
									<div class="form-group">
										<label>Picture Code</label><br>
										<input type="file" name="code_pic">
									</div>
									
									<br>
									<?php if($stock_inventory=="on"){ ?>
									<h3>Inventory Managment </h3>
									<div class="form-group">
									
										<input class="maintain_stock" type="checkbox" name="maintain_stock"> Maintain Stock<br>
										
									</div> 
									<div class="form-group">
										<label>Total Stock</label>
										<input type="Number" name="total_stock" class="form-control">
									</div>
									<div class="form-group">
										<label>Reorder Level</label>
										<input type="Number" name="reorder_level" class="form-control">
									</div>
									<div class="form-group">
										<label>Stock Value</label>
										<input type="Number" value="1" name="stock_value" class="form-control">
									</div>
										<div class="form-group">
							
										<label>Select Parent Product</label>
										 <select id="parent_id" name="parent_id"  class="select2  form-control" style="width: 100%">
                                    <option value='0'> Select Parent Product</option>
									<?php $p_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$loginidset."' and status=0");
									while ($ru=mysqli_fetch_assoc($p_rows)){ ?>
                                    <option  value="<?php echo $ru['id']; ?>"><?php echo $ru['product_name']; ?></option>
									<?php } ?>   
                                </select>
									</div>
									<div class="form-group">
									<?php 
									 
									  $supplierdata = mysqli_query($conn, "select * from supplier_list where user_id='".$_SESSION['login']."'");
									?>
										<label>Select Supplier</label>
										 <select id="supplier_name" name="supplier_id"  class="select2 supplier_name form-control" style="width: 100%">
                                    <option>Select Supplier</option>
									<?php while ($ru=mysqli_fetch_assoc($supplierdata)){ ?>
                                    <option supplier_name="<?php echo $ru['supplier_name']; ?>" value="<?php echo $ru['id']; ?>"><?php echo $ru['supplier_name']; ?></option>
									<?php } ?>   
                                </select>
									</div>
									<?php } ?>
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="css/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
 <script>
    $(document).ready(function () {
		// alert(3);
		$(".select2").select2();
		  $('#supplier_name').change(function() {
			  var id=$(this).val();
			  alert(id);
			  
        $.ajax({
            url: 'supplierdetail.php',
            dataType: 'json',
            type: 'GET',
            // This is query string i.e. country_id=123
            data: {id :id},
            success: function(data) {  
					$('.seller_detail').show();  
				  $('#supplier_label').html(data.supplier_name);
				  // $('#seller_address_id').val(data.seller_address_id);
				  // $('#percantage_value').val(data.per_value);
					 $('#supplier_product').html(data.supplier_product);
			},
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    });
	});
	
 </script>
 <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
</body>

</html>
<style>
select {
    height: 30px;
}
</style>
