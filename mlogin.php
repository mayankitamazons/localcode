<?php
include("config.php");
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
 // session_start();
if(isset($_SESSION['login']))
{
	header("location: dashboard.php");
}
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
} 
// print_r($_COOKIE['session_id']);
// die;
// if(isset($_COOKIE['session_id'])) {
    // if(checkSession()) {
        // $_SESSION['login'] = 1;
        // $_SESSION['session_id'] = $_COOKIE['session_id'];
        // header("location: dashboard.php");
    // }
// }
$_SESSION['IsVIP'] = null ;
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
// echo $_SESSION["langfile"];
// die;
require_once ("languages/".strtolower($_SESSION["langfile"]).".php");
?>

<!DOCTYPE html>
<html>
    
<head>
	<title>Transfer Money | Send and Earn Money Online | Koo Families</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!------ Include the above in your HEAD tag ---------->
</head>
<!--Coded with love by Mutiullah Samim-->
<body>
<style type="text/css">
	/* Coded with love by Mutiullah Samim */
		body,
		html {
			margin: 0;
			padding: 0;
			height: 100%;
			background: #1573EC !important;
		}
		.user_card {
			height: 450px;
			width: 350px;
			margin-top: auto;
			margin-bottom: auto;
			background: #f39c12;
			position: relative;
			display: flex;
			justify-content: center;
			flex-direction: column;
			padding: 10px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 5px;

		}
		.brand_logo_container {
			position: absolute;
			height: 170px;
			width: 170px;
			top: -19px;
			border-radius: 50%;
			background: #60a3bc;
			padding: 10px;
			text-align: center;
		}
		.brand_logo {
			height: 150px;
			width: 150px;
			border-radius: 50%;
			border: 2px solid white;
		}
		.form_container {
			margin-top: 10px;
		}
		.login_btn {
			width: 100%;
			background: #c0392b !important;
			color: white !important;
		}
		.login_btn:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.login_container {
			padding: 0 2rem;
		}
		.input-group-text {
			background: #c0392b !important;
			color: white !important;
			border: 0 !important;
			border-radius: 0.25rem 0 0 0.25rem !important;
		}
		.input_user,
		.input_pass:focus {
			box-shadow: none !important;
			outline: 0px !important;
		}
		.custom-checkbox .custom-control-input:checked~.custom-control-label::before {
			background-color: #c0392b !important;
		}
</style>
<?php
if(isset($_POST['login']))
{
	$mobile_number = addslashes($_POST['mobile_number']);
	$password = addslashes($_POST['password']);
	$countrycode ="60";
	$user_role=2;
	$cm =	$countrycode.''.$mobile_number;
    // print_R($_POST);
	// die;
 	function updateStatus($session_id,$setup_session,$id){
		setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		
		// Set User Cookie
		$salt=md5(mt_rand());
		$my_cookie_id = hash_hmac('sha512', $session_id, $salt);
		$t_sql = "INSERT INTO pcookies SET user_id = '$id', cookie_id = '$my_cookie_id', salt = '$salt'";
		setcookie("my_cookie_id", $my_cookie_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		setcookie("my_token", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");

 		$cm = $GLOBALS['cm'];
 		$password = $GLOBALS['password'];
 		$conn = $GLOBALS['conn'];
 		$token = bin2hex(openssl_random_pseudo_bytes(64));
		if($setup_session=="y")
		$sql = "UPDATE users SET shop_open='1',session = '$session_id', token = '$token' WHERE mobile_number = '$cm' AND password = '$password'";
		else
		$sql = "UPDATE users SET session = '$session_id', token = '$token' WHERE mobile_number = '$cm' AND password = '$password'";	
		if(mysqli_query($conn, $sql) && mysqli_query($conn, $t_sql)){
			return true;
		}else{
			return false;
		}
	}

	$error = "";
	
	if($mobile_number == "" )
	{
		$error .= "Mobile Number is not Valid.<br>";
	}
	$query1 = mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' AND user_roles = '$user_role'");
	if($query1){
		$user_row1 = mysqli_num_rows($query1);
	}

	if($user_row1 == 0)
	{
			$error .= "Account not found, do you want to signup?.<br>";
	}   
	$user_row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm'AND password='$password'")); 
	if($user_row2 == 0)
		{
				$error .= "You have entered wrong password, please try again.<br>";
		}  

	//~ if(!($user_role === $user_row2['user_roles'])){
		
		//~ // echo $user_role . " <---> " . $user_row2['user_roles'];
		//~ $error .= "Invalid type of account.";

	//~ }

	if($user_row2['isLocked'] == "1" && $user_row2['verification_code'] != "" )
	{
		$error .= "User registration pending, Please through the link sent to your mobile number?.<br>";
	}
	//~ if($count == 0)
	//~ {
		//~ $error .= "Account does not exists in our Database.<br>";
	//~ } 
	if(strlen($password) >= 15 || strlen($password) <= 5)
	{
		$error .= "Password must be between 6 and 15.<br>";
	}
	// echo $error;
	// die;
	if(empty($error))
	{
		$time=time();	
		$user_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_roles,id,isLocked,referral_id,name, mobile_number,setup_shop FROM users WHERE mobile_number='$cm' AND password='$password' AND user_roles = '$user_role'"));
		
		 $id = $user_row['id'];
		 $user_roles = $user_row['user_roles'];
		
		$referral_id = $user_row['referral_id'];
		$name = $user_row['name'];
		$mobile_number = $user_row['mobile_number'];
		$setup_session = $user_row['setup_shop'];
		// $_SESSION['setup_shop'] = $setup_session;
		
		if(!isset($cookie_id) || !isset($session_token)){
				$session_id =  uniqid($id . "_",true);
				setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
				$_SESSION['login']=$id;
				$_SESSION['user_id']=$id;
					$koofamilieslsvid = encrypt_decrypt('encrypt', $_SESSION['login']);
	
				setcookie("koofamilieslsvid",$koofamilieslsvid,time()+31556926 ,'/');		
				
				
			if(updateStatus($session_id,$setup_session,$id)){
				//lucky
				
				$insert="insert into stafflogin set staff_id='$id',logintime='$time',session_id='$session_id'";
				mysqli_query($conn,$insert);
				if($user_roles==1)
		    	header("location:dashboard.php");
				else if($user_roles==2)
				header("location:orderview.php");
			    else if($user_roles==5)
				header("location:orderview-staff.php");	
			}else{
				echo "An error occuried, please, try again later.";
			}
		}
		if($id)
		{
			
		    if($user_row['isLocked'] == "0")
    		{
				
				$_SESSION['login'] = $id;
				$_SESSION['user_id'] = $id;
				$_SESSION['setup_shop'] = $setup_shop;
				$_SESSION['referral_id'] = $referral_id;
				$_SESSION['name'] = $name;
				$_SESSION['login_user_role'] = $user_role;
				$_SESSION['mobile'] = $mobile_number;
				
    		}
    		else
    		{
    			$error .= "Sorry, the user account is blocked, please contact support.<br>";
    		}
		}
		
		else
		{
			$error .= "Authentication failed. You entered an incorrect username or password.<br>";
		}
	}
}
	function updateCookieStatus($session_id,$setup_session,$id){
		// setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		
		
		// $salt=md5(mt_rand());
		// $my_cookie_id = hash_hmac('sha512', $session_id, $salt);
		// $t_sql = "INSERT INTO pcookies SET user_id = '$id', cookie_id = '$my_cookie_id', salt = '$salt'";
		// setcookie("my_cookie_id", $my_cookie_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		// setcookie("my_token", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");

 		
 		$conn = $GLOBALS['conn'];
 		$token = bin2hex(openssl_random_pseudo_bytes(64));
		if($setup_session=="y")
		$sql = "UPDATE users SET shop_open='1',session = '$session_id', token = '$token' WHERE id = '$id'";
		else
		$sql = "UPDATE users SET session = '$session_id', token = '$token' WHERE id = '$id'";	
		if(mysqli_query($conn, $sql) && mysqli_query($conn, $t_sql)){
			return true;
		}else{
			return false;
		}
	}
    
?>
	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
			<div class="d-flex justify-content-center mt-3 login_container">
							
						   <button style="margin-top:5%;" class="submint_login btn login_btn" id="shop_open">Shop Open</button>             
							
							</div>
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="images/logo_new.jpg" class="brand_logo" alt="Koofamilies">
					</div>
				</div>
					
				<div class="d-flex justify-content-center form_container">
				   
					    <form method="post" id="shop_open_form">
						 
						
					
						<div class="input-group mb-3">
						<div class="input-group-prepend">
					  <div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">+60</div>
					</div>
							
							<input type="text" name="mobile_number" class="form-control input_user" value="" placeholder="Telephone Number">
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="password" id="login_pass" class="password form-control input_pass" value="" placeholder="Password">
							
						</div>
						 <div class="input-group-addon">
										   <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
										  <span onclick="myFunction()" id="eye_pass"> Show Password </span>
									  </div>
								
								
							<input type="submit" name="login" id="login_input" style="display:none;"/>
							<?php
								if(isset($error) && $error != "")
								{
									
									echo "<div class='alert alert-info'>$error</div>";
									?>
									<style type="text/css">
									 .login_btn{
										 margin-top:60% !important;
									 }
									</style>
								<?php }
								?>
						</form>
						
				</div>  
			
				
				
			</div>
		</div>
	</div>
</body>
</html>
 <script>
$(document).ready(function(){
	  	$('#shop_open').click(function() {
			
			    $("#login_input").click();
		});
	});
function myFunction() {
  var x = document.getElementById("login_pass");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass").html('Hide Password');
			 $('#eye_slash').removeClass( "fa-eye-slash" );
            $('#eye_slash').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass").html('Show Password');
	  $('#eye_slash').addClass( "fa-eye-slash" );
            $('#eye_slash').removeClass( "fa-eye" );
  }
}

 </script>
