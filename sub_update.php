<?php
include("config.php"); 

 $id=$_POST['id'];
 $productname=$_POST['productname'];
 $category=$_POST['category'];
 $product_type=$_POST['product_type'];
 
//~ print_r($_POST);


 $tt = mysqli_query($conn,"UPDATE `subscription` SET `subscription_name`='$productname', subscription_rate='$category' , subscription_qyt='$product_type' WHERE `id`=$id");
?>
