<?php
include("config.php");
$user_id=$_POST['user_id'];
$register_password=$_POST['register_password'];
$save_password=$_POST['save_password'];
if($user_id && $save_password=="y")
{
	$ttw = mysqli_query($conn,"UPDATE `users` SET password='$register_password',password_created='y' WHERE `id` = '$user_id'");
	 unset($_SESSION['tmp_login']);   
	$_SESSION['user_id']=$user_id;
	$_SESSION['login']=$user_id;
}
echo 1;
die;
?>