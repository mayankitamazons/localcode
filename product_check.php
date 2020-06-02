<?php
include("config.php"); 
$pr_code = $_POST['pr_code'];
$p_name = $_POST['p_name'];
if($pr_code)
{
	$search=$pr_code;
}
if($p_name)
{
	$search=$p_name;
}
if($search)
{
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE product_type ='".$search."'"));	
echo json_encode($product);
}
?>