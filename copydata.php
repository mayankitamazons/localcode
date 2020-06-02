<?php
   include("config.php");
   if(isset($_POST['submit']))
   {
	  
	   if($_POST['main_merchant'] && $_POST['child_merchant'])
	   {
		    // print_R($_POST);
			// die;
		   // catgry copy 
			$m_id=$_POST['main_merchant'];
			$c_id=$_POST['child_merchant'];
			//  cat master copy
			$query = mysqli_query($conn,"select * from cat_mater  where UserID='$m_id'");
			while ($rowc=mysqli_fetch_assoc($query)){
				$CatName=$rowc['CatName'];
				$IsEnable=$rowc['IsEnable'];
				$DateAdded=$rowc['DateAdded'];
				$UserID=$c_id;
				mysqli_query($conn, "INSERT INTO  cat_mater SET CatName='$CatName', IsEnable='$IsEnable', DateAdded='$DateAdded',UserID= '$UserID'");
	
			}
			$query = mysqli_query($conn,"select * from category  where user_id='$m_id'");
			while ($row=mysqli_fetch_assoc($query)){
				$cat_id=$row['id'];
				
				$category_name=$row['category_name'];
				$catparent=$row['catparent'];
				$catparent_name=$row['catparent_name'];
				$status=$row['status'];
				$created_date=$row['created_date'];
				mysqli_query($conn, "INSERT INTO  category SET category_name='$category_name', catparent='$catparent', user_id='$c_id',status= '0',created_date='$created_date'");
	
			}
			//product copy 
			$query = mysqli_query($conn,"select * from products  where user_id='$m_id'");
			while ($row=mysqli_fetch_assoc($query)){
				$cat_id=$row['id'];
				// $user_id=$row['user_id'];
				$productname=$row['product_name'];
				$varient_exit=$row['varient_exit'];
				$product_id=$row['id'];
				$category=$row['category'];
				if($category)
					 {
						// echo "SELECT id FROM category WHERE user_id ='".$loginidset."' and category_name ='".$category."'";
						// die;
						$categories = mysqli_query($conn, "SELECT id FROM category WHERE status='0' and user_id ='".$c_id."' and category_name ='".$category."'");
						$categoryrow=mysqli_fetch_assoc($categories);
						$category_id=$categoryrow['id'];
					 }
				$product_type=$row['product_type'];
				$product_price=$row['product_price'];
				$remark=$row['remark'];
				$image=$row['image'];
				$current_date=$row['current_date'];
				$print_ip_address=$row['print_ip_address'];
				$printer_ip_2=$row['printer_ip_2'];
				$printer_profile=$row['printer_profile'];
				$usb_name=$row['usb_name'];
				
				mysqli_query($conn, "INSERT INTO products SET product_name='$productname',user_id='$c_id', category='$category',category_id='$category_id',product_type='$product_type',product_price='$product_price', remark = '$remark', image='$image', code='$code',created_date='$current_date',print_ip_address='$print_ip_address',printer_ip_2='$printer_ip_2',printer_profile='$printer_profile',usb_name='$usb_name'");
				if($varient_exit=="y")
				{
					
					$new_product_id = mysqli_insert_id($conn);
        
				   //sub product copy
					$query2 = mysqli_query($conn,"select * from sub_products  where product_id='$product_id'");
					while ($row2=mysqli_fetch_assoc($query2)){
						
						$p_name=$row2['name'];
						$product_type=$row2['product_type'];
						$product_price=$row2['product_price'];
						$status=$row2['status'];
						$merchant_id=$row2['merchant_id'];
						mysqli_query($conn, "INSERT INTO sub_products SET product_id='$new_product_id',name='$p_name', product_type='$product_type',product_price='$product_price',product_type='$product_type',status='$status', merchant_id = '$merchant_id'");

					}
				}
			}
			
			echo "Data Copy Successfully";
			
	   }
   } 
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Copy Data From One Merchant to other</h2>
  <form method="post" action=''>
    <div class="form-group">
      <label for="email">Main Merchant:</label>
      <input type="text" class="form-control" name="main_merchant" placeholder="Enter Merchant User id">
    </div>
    <div class="form-group">
      <label for="pwd">Other Merchant:</label>
     <input type="text" class="form-control" name="child_merchant" placeholder="Enter Merchant User id">
    </div>   
    <small><b>Note : That tool copy Category Master ,Category , Product , Sub Product</b></small>
    <input type="submit" class="btn btn-block btn-primary" name="submit" value="Update Details">
  </form>
</div>

</body> 
</html>