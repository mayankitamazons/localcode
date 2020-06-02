<?php
include_once("config.php");
function clear(){
	$conn = $GLOBALS['conn'];
	$date = date('Y-m-d H:i:s');
	$dateutc=strtotime($date);
	$sql = "SELECT * FROM users";
	if (isset($_COOKIE['session_id'])) {
		$a = explode("_", $_COOKIE['session_id']);
		$id = $a[0];
		$sql = "UPDATE users SET session = '', token = '' WHERE id = '$id'";
		unset($_COOKIE['session_id']);		
    	setcookie('session_id', '', time() - 3600, '/');
	}
    $remove_sql = "SELECT * FROM pcookies";
	
    if (isset($_COOKIE['my_cookie_id'])) {
    	$my_cookie_id = $_COOKIE['my_cookie_id'];
		$remove_sql = "DELETE FROM pcookies WHERE cookie_id = '$my_cookie_id'";
		unset($_COOKIE['my_cookie_id']);
		unset($_COOKIE['my_token']);
    }
	if(mysqli_query($conn, $sql) && mysqli_query($conn, $remove_sql)){
		return true;
	}else{
		return false;
	}
	$_SESSION['login'] = null;
}
$date = date('Y-m-d H:i:s');
$dateutc=strtotime($date);
if(isset($_GET['s']))
{
	if($_GET['s']=="shop_close")
	{
		$id=$_SESSION['login'];
	   $sql = "UPDATE users SET shop_open = '0',active_login='n' WHERE id = '$id'";	
	   mysqli_query($conn,$sql);
	  
	}
	if($_GET['s']=="shift_close")
	{
		$id=$_SESSION['login'];
	   $sql2 = "UPDATE user_login SET logout_time = '$dateutc',is_active='n' WHERE user_id = '$id' and is_active='y'";	
	   $sql = "UPDATE users SET last_login = '$dateutc',active_login='n' WHERE id = '$id'";	   
	   $query="UPDATE cash_system SET is_active= 'n',logout_time='$dateutc' WHERE user_id = '$id' and is_active='y'";  
	   mysqli_query($conn,$query);
	   mysqli_query($conn,$sql);
	   mysqli_query($conn,$sql2);   
	  
	}
}
if(clear()){
	session_destroy();
	mysqli_query($conn, "UPDATE users SET moengage_unique_id='',already_login='n',active_login='n' WHERE id='".$id."'");  
	header('location: login.php');

}else{
	echo "Error occuried. Please try again later.";
}


?>
