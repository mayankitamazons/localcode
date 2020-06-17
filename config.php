<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
if(!isset($_SESSION))
{
 session_start();
}
error_reporting(0);
$conn = mysqli_connect("127.0.0.1", "root", "", "lifelocal");
// $conn2 = mysqli_connect("166.62.120.154", "koofamil_B277", "rSFihHas];1P", "koofamil_B277");
if(!$conn)
{
	// Header('Location: '.$_SERVER['PHP_SELF']);
// echo "database error"; die;
}
// $site_url = "http://127.0.0.1/woi";   // Prod
 $SERVER_NAME=$_SERVER['SERVER_NAME'];

  $site_url = "http://".$SERVER_NAME."/woilocal";   // Prod
$paypalUrl='https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypalId='wjchong@koofamilies.com';
$paypal_cancel_url = $site_url . "/view_merchant.php?status=paypalcancel";
$paypal_success_url = $site_url . "/ordersuccess.php";   
if(!function_exists('redirectToUrl')) {   
	function redirectToUrl($url) {
		echo '<script language="javascript">window.location.href ="'.$url.'"</script>';
		exit;
	}
}
?>  
