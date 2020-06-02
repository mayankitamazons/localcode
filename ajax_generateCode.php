<?php 
include("config.php");

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(!isset($_SESSION['login']))
{
	die;
}

$email = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM users WHERE id='".$_SESSION['login']."'"))['email'];

if(isset($_POST['request']))
{
	$code = generateRandomString(5);
	$_SESSION['verify_code'] = $code;
	
	$subject = "Your Verification Code | KooExchange";
	$message = "
	<html>
	<head>
	<title>Your Verification Code | KooExchange</title>
	</head>
	<body>
	<p>Your Verification Code For Exchange Request :</p>
	<h3 style='text-align:center'>$code</h3>
	</body>
	</html>
	";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <info@kooexchange.com>' . "\r\n";
	//$headers .= 'Cc: myboss@example.com' . "\r\n";

	mail($email,$subject,$message,$headers);
	$error = "Verification Code Has Been Sent To Your Email Address.";
	echo $error;
	//echo $code;
}

die;
?>