<?php
include("config.php");
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));

if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
if($_POST['id'])
{
	$id=$_POST['id'];
	$reorder_level=$_POST['reorder_level'];
	$stock_value=$_POST['stock_value'];
	$supplier_id=$_POST['supplier_id'];
	$parent_id=$_POST['parent_id'];
	// $maintain_stock=$_POST['maintain_stock'];
	if(isset($_POST['maintain_stock']))
	{
		$maintain_stock="on";
	}
	else
	{
		$maintain_stock="";
	}
	 $qu="UPDATE `products` SET maintain_stock='$maintain_stock',reorder_level='$reorder_level',`stock_value`='$stock_value', supplier_id='$supplier_id',parent_id='$parent_id'  WHERE `id`=$id";

 $tt = mysqli_query($conn,$qu);
}	
?>