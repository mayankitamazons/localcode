<?php
include("config.php");
// ~ print_r($_POST); 
// ~ print_r($_FILES); 
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
$userid=$_SESSION['login'];
if(isset($_POST['id']) && isset($_POST['update_stock'])){
    $id = $_POST['id'];
    // echo $id;
    $q1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT on_stock FROM products WHERE id='$id'"));
    $v = !$q1['on_stock'];
    // echo $v;
    $query = mysqli_query($conn, "UPDATE `products` SET `on_stock`='$v' WHERE `id`=$id");
    echo ($query) ? true : false;
    die();
}

 $id=$_POST['id'];
 $productname=$_POST['productname'];
 $category=$_POST['category'];
 $product_type=$_POST['product_type'];
 $product_price=$_POST['product_price'];
 $print_ip_address=$_POST['print_ip_address'];
 $printer_ip_2=$_POST['printer_ip_2'];
 $printer_profile=$_POST['printer_profile'];
  $varient_must=$_POST['varient_must'];
 $usb_name=$_POST['usb_name'];
 $remark=$_POST['remark'];
  $product_discount=$_POST['product_discount'];


 if($varient_must=="on")
{
	$varient_must="y";
}
else
{
	$varient_must="n";
}
if(!$_POST['always_active']){
    $days_active = $_POST['days_active'];
    $startTime = $_POST['start_hours'];
    $endTime = $_POST['end_hours'];
    $result = [];
    foreach ($days_active as $index => $days) {
        array_push($result, array("days"=>$days, "start"=>$startTime[$index],"end"=>$endTime[$index]));
    }
    $active_date = json_encode($result);
}else{
    $active_date = 1;
}

 if($category)
 {
	$categories = mysqli_query($conn, "SELECT id FROM category WHERE user_id ='".$loginidset."'and category_name ='".$category."'");
	$categoryrow=mysqli_fetch_assoc($categories);
	$category_id=$categoryrow['id'];
 }
 	$image_pic =  $_FILES["image_pic"]["name"] != '' ? $_FILES["image_pic"]["name"] : $_POST['img']; 
    $image_code =  $_FILES["image_code"]["name"] != '' ? $_FILES["image_code"]["name"] : $_POST['img_code']; 
 
 //~ $image_pic=$_FILES["image_pic"]["name"]; 
  $qu="UPDATE `products` SET product_discount='$product_discount',varient_must='$varient_must',`product_name`='$productname', category='$category' , product_type='$product_type', product_price='$product_price',print_ip_address='$print_ip_address',remark = '$remark', image='$image_pic', code='$image_code',category_id='$category_id',active_time='$active_date',modifiedid='$userid',`printer_profile`='$printer_profile',`usb_name`='$usb_name' WHERE `id`=$id";
 // die;
 $tt = mysqli_query($conn,$qu);

//~ mysqli_query($conn,"UPDATE `products` SET `product_name`='ytyt',`category`='yty',`product_type`='tyt',`image`='yyytytyh.png' WHERE `id` = 4");
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
			
               move_uploaded_file($_FILES["image_pic"]["tmp_name"], "images/product_images/" . $_FILES["image_pic"]["name"]);
        } else{
            echo "Error: There was a problem uploading your file. Please try again.";   
die;			
        }
    } 

    if(isset($_FILES["image_code"]) && $_FILES["image_code"]["error"] == 0){
		
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg","JPEG" => "image/JPEG", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image_code"]["name"];
        $filetype = $_FILES["image_code"]["type"];
        $filesize = $_FILES["image_code"]["size"];
        

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 5MB maximum
        //~ $maxsize = 5 * 1024 * 1024;
        //~ if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("upload/" . $_FILES["image_code"]["name"])){
                echo $_FILES["image_code"]["name"] . " is already exists.";
            } else{
                move_uploaded_file($_FILES["image_code"]["tmp_name"], "images/product_images/" . $_FILES["image_code"]["name"]);
               // echo "Your file was uploaded successfully.";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } 

?>
