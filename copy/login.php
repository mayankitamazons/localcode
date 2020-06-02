<?php
include("config.php");
 session_start();
if(isset($_GET['language'])){
	$_SESSION["langfile"] = $_GET['language'];
}  
// print_r($_COOKIE['session_id']);
// die;
$_SESSION['IsVIP'] = null ;
if (empty($_SESSION["langfile"])) { $_SESSION["langfile"] = "english"; }
require_once ("languages/".strtolower($_SESSION["langfile"]).".php");
if(isset($_GET['code']) && !empty($_GET['code']) && isset($_GET['id']) && is_numeric($_GET['id']))
{
	  $code = $_GET['code']; 
	  $user_id = $_GET['id'];
	 $if_exists = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE verification_code='$code' AND id='$user_id'"));
	
	if($if_exists > 0)
	{
		mysqli_query($conn, "UPDATE users SET verification_code='', isLocked='0' WHERE id='$user_id'");
		 $error = "You have verified your account successfully. You can now login to use our service.<br>";
	}
	else
	{
		 $error = "User registration pending, Please through the link sent to your mobile number.<br>";
	}	
}
function checkToken(){
	$conn = $GLOBALS['conn'];
	$tget = $_GET['tk'];
	$ref_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id,token, referral_id FROM users WHERE token = '$tget'"));
	if($tget === $ref_id['token']){
		$session_id =  uniqid($ref_id['id'] . "_",true);
		$c = setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		if($c){
			$_SESSION['login'] = $ref_id['id'];
			$_SESSION['referral_id'] = $ref_id['referral_id'];
			return true;
		}
	}else{
		return false;
	}

}

function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg){           
    $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
    $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
    $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
    $url = "http://gateway.onewaysms.com.au:10001/".$query_string;       
    $fd = @implode ('', file ($url));      
    if ($fd){                       
		if ($fd > 0) {
			$ok = "success";
		} else {
			print("Please refer to API on Error : " . $fd);
			$ok = "fail";
	    }
    } else {                       
        // no contact with gateway                      
        $ok = "fail";       
    }           
    return $ok;  
}  

function checkSession(){
	$conn = $GLOBALS['conn'];
	$session = $_COOKIE['session_id'];
	$rw = mysqli_fetch_row(mysqli_query($conn, "SELECT id FROM users WHERE session = '$session'"));
	if($rw > 0){
		return true;
	}else{
		return false;
	}
}
 
if(isset($_SESSION['login']) && !empty($_SESSION['login']) && checkSession() && isset($_COOKIE["session_id"]))
{
	header("location: dashboard.php");

}else if(isset($_SESSION['login']) && empty($_SESSION['login'])) {

	header('location: logout.php');

}else if(isset($_COOKIE["session_id"])){

	$ss = explode("_", $_COOKIE['session_id']);
	if(sizeof($ss) > 1){
		if(!empty($ss[1])){
			if(checkSession()){
				$_SESSION['login'] = $ss[0];
				header("location: login.php");
			}else{
				header('location: logout.php');
			}
		}else{
			header('location: logout.php');
		}
	}else{
		header('location: logout.php');
	}


}else if(isset($_GET['tk']) && !empty($_GET['tk'])){

	if(checkToken()){

		header('location: dashboard.php');

	}else{

		header('location: ./login.php');

	}

}else if(isset($_GET['tk']) && empty($_GET['tk'])){
	header('location: ./login.php');
}
if(isset($_SESSION['invitation_id'])){
    unset($_SESSION['invitation_id']);
}
if($_POST['checkphone'])
{
	 $checkphone=$_POST['checkphone'];

	$loginmatch = mysqli_query($conn, "SELECT id FROM users WHERE mobile_number ='".$checkphone."'");	
	$logincount=mysqli_num_rows($loginmatch);  
	if($logincount>0)
	{
		echo 1;
	}
	die;
}
if($_POST['method']=="otp_submit")
{
	 $usermobile=$_POST['usermobile'];

	$res1= mysqli_query($conn,"UPDATE `users` set otp_verified ='y' WHERE mobile_number ='$usermobile'");
	if($res1)
	{
		echo 1;
	}
	die;
}
if($_POST['usermobile'] && $_POST['login_password'])
{
	 $usermobile=$_POST['usermobile'];
	 $login_password=$_POST['login_password'];

	$loginmatch = mysqli_query($conn, "SELECT id FROM users WHERE password='$login_password' and mobile_number ='".$usermobile."'");	
	$logincount=mysqli_num_rows($loginmatch);  
	if($logincount>0)
	{
		$user_row = mysqli_fetch_assoc($loginmatch); 
		$id=$user_row['id'];
		   unset($_SESSION['tmp_login']);   
		$_SESSION['user_id']=$user_row['id'];
		$_SESSION['login']=$user_row['id'];
		$setup_session = $user_row['setup_shop'];
		$session_id =  uniqid($id . "_",true);
		// $setup_session
		updateCookieStatus($session_id,$setup_session,$id);
		echo 1;
	}
	else
	{
		echo 0;
	}
	die;
}
if($_POST['user_id'] && $_POST['method']=="registerajax")
{
	 $user_id=$_POST['user_id'];
	 $order_id=$_POST['order_id'];
	 $register_password=$_POST['register_password'];
	
	$loginmatch = mysqli_query($conn, "SELECT id FROM users WHERE id ='".$user_id."'");	
	$logincount=mysqli_num_rows($loginmatch);  
	if($logincount>0)
	{
		$user_row = mysqli_fetch_assoc($loginmatch); 
		$_SESSION['login']=$user_row['id'];
		$_SESSION['user_id']=$user_row['id'];
		$setup_session=$user_row['shop_open'];
		unset($_SESSION['tmp_login']);
		// echo "UPDATE users SET password='$register_password',newuser='join' WHERE id='$user_id'";
		// die;
		if($register_password)   
		mysqli_query($conn, "UPDATE users SET password='$register_password',guest_user='active',otp_verified='y' WHERE id='$user_id'");
	   else
		 mysqli_query($conn, "UPDATE users SET guest_user='active',otp_verified='y',password_created='y' WHERE id='$user_id'");  
		mysqli_query($conn, "UPDATE order_list SET newuser='join' WHERE id='$order_id'");
		$session_id =  uniqid($user_id . "_",true);
		updateCookieStatus($session_id,$setup_session,$user_id);
		echo 1;
	}
	die;
}
if(isset($_GET['code']) && isset($_GET['id']) && is_numeric($_GET['id']))
{
	$code = $_GET['code']; $user_id = $_GET['id'];
	$if_exists = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM users WHERE verification_code='$code' AND id='$user_id'"));
	if($if_exists > 0)
	{
		mysqli_query($conn, "UPDATE users SET verification_code='', isLocked='0' WHERE id='$user_id'");
		$error = "You have verified your account successfully. You can now login to use our service.<br>";
	}
	else
	{
		$error = "User registration pending, Please through the link sent to your mobile number.<br>";
	}	
}

if(isset($_POST['signup']))
{
 
	$name = addslashes($_POST['name']);
	$user_role = addslashes($_POST['user_role']);
	$email = addslashes($_POST['email']);
	$password = addslashes($_POST['password']);
	$security = addslashes($_POST['security']);
	$questions = addslashes($_POST['questions']);
	$countrycode = addslashes($_POST['countrycode']);
	$mobile_number = addslashes($_POST['mobile_number']);
	$account_type = addslashes($_POST['account_type']);
	
	$cm =	$countrycode.''.$mobile_number;

	$error = "";
	
	if($name == "")
	{
		$error .= "Name cannot be Empty.<br>";
	}
	
	$already_exists1 = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' && user_roles= '$user_role'"));

	 if($already_exists1 > 0)
	 {
		 $error .= "Mobile Number Already Exists.<br>";
	 }

	if($error == "")
	{
		$code = uniqid();

		$fund_pass = mt_rand(100000, 999999);

		$ref_exists = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number='$cm' AND user_roles != '3'"));
		
		if($ref_exists > 0 )
		{ 
		 $ref_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number ='".$cm."'"));
		//~ $ref_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE mobile_number ='".$cm."'"));
		 $ref = $ref_id['referral_id'];

		}else{
			$ref = $name." ".$code;
		}
		
	
        if($account_type == ""){
            $k_date = "";
        } else {
            $k_date = date("Y-m-d");
        }
        
		$reffered_by= $_POST['referral_id'];
	    if($reffered_by != "") $date = date('Y-m-d');
	    else $date = "";
	    $code = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(SUBSTR(merchant_code,5)) + 1 code FROM users "))['code'];
	    $merchant_code = "KOO_".str_pad($code,5,'0',STR_PAD_LEFT);
	    if($user_role == "1"){
	        $merchant_code = "";
	    }
	    mysqli_query($conn, "INSERT INTO users SET name='$name',user_roles='$user_role',verification_code='$code',account_type='$account_type', k_date='$k_date', password='$password', joined='".time()."', isLocked='1',referral_id='$ref',referred_by='$reffered_by',security_answer= '$security',security_questions= '$questions',fund_password='$fund_pass',email='$email',mobile_number='$cm', created_at='$date', merchant_code='$merchant_code'");
        
		$user_id = mysqli_insert_id($conn);
		$datetime = date('Y-m-d');
		if($account_type != ""){
		    mysqli_query($conn, "INSERT INTO k_type SET user_id='$user_id', type='$account_type', date='$datetime'");
		}
		$current_url = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
      if($user_role=="1")
	  {
	 Print("Sending to one way sms .This link is only valid for 10minutes" . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Verify Your Account on koofamilies $current_url?code=$code&id=$user_id"));
		$current_url = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
		$subject = "Verify Your Account | koofamilies";

		$message = "
		<html>
		<head>
		<title>Verify Your Account | koofamilies</title>
		
		<script type='text/javascript'>
    	navigator.serviceWorker.getRegistrations().then(function(registrations) {
		 for(let registration of registrations) {
		  registration.unregister()
		} })
    </script>
		</head>
		<body>
		<h3>Verify Your Account on koofamilies</h3>
		<p>You Can Verify Your Account By Visiting The Following Link :</p>
		<p style='text-align:center'><a href='$current_url?code=$code&id=$user_id'>Verify</a></p>
		</body>
		</html>
		";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: <info@kooexchange.com>' . "\r\n";
		
		$error = "Registered Successfully, Verification Mobile Number has been sent to your Mobile Number.";
	  }   
	  else
	  {
		  $error = "Registered Successfully";
	  }

	}
		
		
	
}

if(isset($_POST['login']))
{
	$mobile_number = addslashes($_POST['mobile_number']);
	$password = addslashes($_POST['password']);
	$countrycode = addslashes($_POST['countrycode']);
	$user_role = addslashes($_POST['user_role']);
	$cm =	$countrycode.''.$mobile_number;
    // print_R($_POST);
	// die;
 	function updateStatus($session_id,$setup_session,$id,$active_login,$cash_system,$parentid){
		setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		
		// Set User Cookie
		$salt=md5(mt_rand());
		$my_cookie_id = hash_hmac('sha512', $session_id, $salt);
		$t_sql = "INSERT INTO pcookies SET user_id = '$id', cookie_id = '$my_cookie_id', salt = '$salt'";
		setcookie("my_cookie_id", $my_cookie_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		setcookie("my_token", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");

 		$cm = $GLOBALS['cm'];
 		$password = $GLOBALS['password'];
		$date = date('Y-m-d H:i:s');
		$dateutc=strtotime($date);
 		$conn = $GLOBALS['conn'];
 		$token = bin2hex(openssl_random_pseudo_bytes(64));
	    // echo $setup_session;
		// die;
		if($cash_system=="on")
		{
			// echo "SELECT id FROM cash_system WHERE is_active='y' AND id='$id'";
			// die;
			$pastcash = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM cash_system WHERE is_active='y' AND user_id='$parentid'"));
			if($pastcash)
			{
			    // $cashq="INSERT INTO cash_system (`user_id`, `login_time`) VALUES ('$parentid', '$dateutc')";
			// die;
			  // mysqli_query($conn,$cashq);
			   // $cash_id=mysqli_insert_id($conn);
			   $cash_id=$pastcash['id'];
		
			 $_SESSION['cash_id']=$cash_id;	
			}
			else
			{
				 $cashq="INSERT INTO cash_system (`user_id`, `login_time`) VALUES ('$parentid', '$dateutc')";
			
			  mysqli_query($conn,$cashq);
			   $cash_id=mysqli_insert_id($conn);
			   // $cash_id=$pastcash['id'];
		
			 $_SESSION['cash_id']=$cash_id;	
			}
	
		}
		if($setup_session=="y")
		{
		   if($active_login=="n")
		   {
				$sql = "UPDATE users SET already_login='y',shop_open='1',session = '$session_id', token = '$token',last_login='$dateutc',active_login='y' WHERE mobile_number = '$cm' AND password = '$password'";
			 $s2="INSERT INTO user_login (`user_id`,`login_time`) VALUES ('$id', '$dateutc')";
			  
			 $_SESSION['last_login']=$dateutc;
				mysqli_query($conn,$s2);
				
		  }
			else if($active_login=="y")
			$sql = "UPDATE users SET already_login='y',shop_open='1',session = '$session_id', token = '$token' WHERE mobile_number = '$cm' AND password = '$password'";
		
			 
		}
		else
		{
			if($active_login=="n")
			{
				$sql = "UPDATE users SET session = '$session_id', token = '$token',last_login='$dateutc',active_login='y' WHERE mobile_number = '$cm' AND password = '$password'";	
				$s2="INSERT INTO user_login(`user_id`,`login_time`) VALUES ('$id', '$dateutc')";
				
				  $cash_id=mysqli_insert_id($conn);
			 
				$_SESSION['cash_id']=$cash_id;
				$_SESSION['last_login']=$dateutc;
				mysqli_query($conn,$s2);
			}
			else if($active_login=="y")
			$sql = "UPDATE users SET session = '$session_id', token = '$token' WHERE mobile_number = '$cm' AND password = '$password'";		
		}   
		// die;
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
	// if($user_row2['already_login'] == "y")
	// {
		// $error .= "Already Login on different browser or session, Logout From there.<br>";
	// }
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
		$user_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT parentid,user_roles,cash_system,id,isLocked,referral_id,name, mobile_number,setup_shop,active_login FROM users WHERE mobile_number='$cm' AND password='$password' AND user_roles = '$user_role'"));
		// print_R($user_row);
		// die;
		 $id = $user_row['id'];
		$parentid=$id;
		$referral_id = $user_row['referral_id'];
		$name = $user_row['name'];
		$mobile_number = $user_row['mobile_number'];
		$setup_session = $user_row['setup_shop'];
		$active_login = $user_row['active_login'];
		$cash_system = $user_row['cash_system'];
		  $user_role = $user_row['user_roles'];
		
		if($user_role=='5')
		{
			 $parentid=$user_row['parentid'];
			
			$parent_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$parentid'"));
			
			$cash_system=$parent_data['cash_system'];
		}
		// echo $cash_system;
		// die;
		// $_SESSION['setup_shop'] = $setup_session;
		
		if(!isset($cookie_id) || !isset($session_token)){
				$session_id =  uniqid($id . "_",true);
				setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
				$_SESSION['login']=$id;
				$_SESSION['cash_allow']='y';
				$_SESSION['user_id']=$id;
			if(updateStatus($session_id,$setup_session,$id,$active_login,$cash_system,$parentid)){
				//lucky
				
				$insert="insert into stafflogin set staff_id='$id',logintime='$time',session_id='$session_id'";
				mysqli_query($conn,$insert);
				
		    	header("location:dashboard.php");
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
		setcookie("session_id", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		
		// Set User Cookie
		$salt=md5(mt_rand());
		$my_cookie_id = hash_hmac('sha512', $session_id, $salt);
		$t_sql = "INSERT INTO pcookies SET user_id = '$id', cookie_id = '$my_cookie_id', salt = '$salt'";
		setcookie("my_cookie_id", $my_cookie_id, time() + 3600 * 24 * 30 * 12 * 10,"/");
		setcookie("my_token", $session_id, time() + 3600 * 24 * 30 * 12 * 10,"/");

 		
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
if(isset($_POST['forget']))
{
	$mobile_number = addslashes($_POST['mobile_number']);
	$countrycode = addslashes($_POST['countrycode']);
	$cm =	$countrycode.''.$mobile_number;
	$user_role = addslashes($_POST['user_role']);
	//$email = addslashes($_POST['email']);	
	$error = "";
	if($mobile_number == "" )
	{
		$error .= "Mobile Number is not Valid.<br>";
	}
	
	//~ if($email == "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	//~ {
		//~ $error .= "Email is not Valid.<br>";
	//~ }
	$data = mysqli_query($conn, "SELECT  password,isLocked FROM users WHERE mobile_number='$cm' AND user_roles = '$user_role' ");
	//~ $data = mysqli_query($conn, "SELECT password,isLocked FROM users WHERE email='$email'");
	$count = mysqli_num_rows($data);
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
	}
	
	$row = mysqli_fetch_assoc($data);      
	$lock_status = $row['isLocked'];
	$password = $row['password'];
	
	if($lock_status == 1)
	{
		$error .= "Sorry, the user account blocked, please contact support.<br>";
	}
	
	if($error == "")
	{
		$rand =mt_rand();
		$forgot_url = "https://".$_SERVER['HTTP_HOST']."/demo1/forgot_password.php?rand=".$rand."&mn=".$cm;
		 mysqli_query($conn, "UPDATE users SET rand_num='$rand',resetdate='".time()."' WHERE mobile_number='$cm' AND user_roles = '$user_role' ");

		Print("Sending to one way sms " . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Password for your Account ($cm) : $forgot_url"));
		

		
		
	}
}

if(isset($_POST['forget_fund']))
{
	
	$mobile_number = addslashes($_POST['mobile_number']);
	$countrycode = addslashes($_POST['countrycode']);
	$cm =	$countrycode.''.$mobile_number;
	//$email = addslashes($_POST['email']);	
	$error = "";
	if($mobile_number == "" )
	{
		$error .= "Mobile Number is not Valid.<br>";
	}
	
	//~ if($email == "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	//~ {
		//~ $error .= "Email is not Valid.<br>";
	//~ }
	$data = mysqli_query($conn, "SELECT  fund_password,isLocked FROM users WHERE mobile_number='$cm'");
	
	//~ $data = mysqli_query($conn, "SELECT password,isLocked FROM users WHERE email='$email'");
	$count = mysqli_num_rows($data);
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
	}
	
	$row = mysqli_fetch_assoc($data);
	
	$lock_status = $row['isLocked'];
	$password = $row['fund_password'];
	
	if($lock_status == 1)
	{
		$error .= "Sorry, the user account blocked, please contact support.<br>";
	}
	
	if($error == "") 
	{
		
		Print("Sending to one way sms " . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Fund Password for your Account ($cm) : $password"));
		
		
		
	}
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login | koofamilies</title>
    <!--Custom Theme files-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Tab Login Form widget template Responsive, Login form web template,Flat Pricing tables,Flat Drop downs  Sign up Web Templates, Flat Web Templates, Login signup Responsive web template, SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design"
    />
    <script type="application/x-javascript">
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); }
    </script>
    <script type="text/javascript">
    	navigator.serviceWorker.getRegistrations().then(function(registrations) {
		 for(let registration of registrations) {
		  registration.unregister()
		} })
    </script>
    <!-- Custom Theme files -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!--web-fonts-->
    <link href='css/Signika.css' rel='stylesheet' type='text/css'>
    <link href='css/Righteous.css' rel='stylesheet' type='text/css'>
    <link href="css/custom.css" rel="stylesheet" type="text/css" media="all" />
    <!--//web-fonts-->

        <!-- jquery validation plugin //-->
        
<link rel="stylesheet" href="css/smooth.css">


        
        <style type="text/css">
        
        .hidden{
        
        display:none;
        
        }
        
        </style>
    <!--js-->
 
   
	<style>
	.alert {
	padding: 15px;
    margin: 15px;
    border: 1px solid transparent;
    border-radius: 4px;
	}
	.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
	}
	img.logo_main {
    display: block;
    text-align: center;
    margin: 0 auto;
}

	</style>
    <!--//js-->
</head>

<body>


    <!-- main -->
    <div class="main">
<!--
        <h1>koofamilies</h1>
-->
        <img src="images/logo_new.jpg" width="170px" height="100px" class="logo_main">
        <div class="login-form">
            <div class="login-left">
                <div class="logo" style="margin-top: 55px;">
                    <img style="    max-width: 92%;" src="images/Icon-user.png" alt="" />
                    <h2>Hello </h2>
                    <p>Welcome to koofamilies</p>
                </div>
            </div>
            <div class="login-right">
                <div class="sap_tabs">
                    <div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
					 <?php if(isset($_SESSION['msg'])){ ?>
					 <p><?php echo $_SESSION['msg']; ?></p> 
					 <?php  unset($_SESSION['msg']);  } ?>
                        <div class="language">
							<select class="language_option form-control" name="language">
								<option <?php if($_SESSION["langfile"] == "English") echo "selected"?>>English</option>
								<option <?php if($_SESSION["langfile"] == "Chinese") echo "selected"?>>Chinese</option>
								<option value="malaysian" <?php if($_SESSION["langfile"] == "malaysian") echo "selected"?>>Malay</option>
							</select>
						</div>
						
								 <div class="login-bottom login-bottom1" style="width:100%;clear:both;">

<a class="col-md-2" href="<?php echo $site_url;?>/facebook-login/fbconfig.php?via=login"><img src="img/login-cont-facebook.jpg" style=""></a>

</div>
  <hr class="first_test"> Or <hr class="second_test">
                        <ul class="resp-tabs-list">
                            <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span><?php echo $language["login"];?></span></li>
                            <li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><label>/</label><span><?php echo $language["signup"];?></span></li>
							<li class="resp-tab-item sign_up" aria-controls="tab_item-2" role="tab"><label>/</label><span><?php echo $language["forget_password"];?></span></li>
                            <div class="clear"> </div>
                        </ul>
                        <div class="resp-tabs-container">
                            <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
								<?php
								if(isset($error) && $error != "")
								{ 
									echo "<div class='alert alert-info'>$error</div>";
								}
								?>
								<br>
								
                                <form method="post">
                                <div class="login-top">	
                 
                                    <select name="countrycode" id="countrycode" class="ct_ctycode">
                                        	<option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                        	<option data-countryCode="CN" value="86">China (+86)</option>
                                        	<option data-countryCode="TH" value="66">Thailand (+66)</option>
                                        	<option data-countryCode="SG" value="65">Singapore (+65)</option>
                                        
                                        	<optgroup label="Other countries">
                                        	<option data-countryCode="DZ" value="213">Algeria (+213)</option>
                                        		<option data-countryCode="AD" value="376">Andorra (+376)</option>
                                        		<option data-countryCode="AO" value="244">Angola (+244)</option>
                                        		<option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                        		<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                        		<option data-countryCode="AR" value="54">Argentina (+54)</option>
                                        		<option data-countryCode="AM" value="374">Armenia (+374)</option>
                                        		<option data-countryCode="AW" value="297">Aruba (+297)</option>
                                        		<option data-countryCode="AU" value="61">Australia (+61)</option>
                                        		<option data-countryCode="AT" value="43">Austria (+43)</option>
                                        		<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                        		<option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                        		<option data-countryCode="BH" value="973">Bahrain (+973)</option>
                                        		<option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                        		<option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                        		<option data-countryCode="BY" value="375">Belarus (+375)</option>
                                        		<option data-countryCode="BE" value="32">Belgium (+32)</option>
                                        		<option data-countryCode="BZ" value="501">Belize (+501)</option>
                                        		<option data-countryCode="BJ" value="229">Benin (+229)</option>
                                        		<option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                        		<option data-countryCode="BT" value="975">Bhutan (+975)</option>
                                        		<option data-countryCode="BO" value="591">Bolivia (+591)</option>
                                        		<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                        		<option data-countryCode="BW" value="267">Botswana (+267)</option>
                                        		<option data-countryCode="BR" value="55">Brazil (+55)</option>
                                        		<option data-countryCode="BN" value="673">Brunei (+673)</option>
                                        		<option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                        		<option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                        		<option data-countryCode="BI" value="257">Burundi (+257)</option>
                                        		<option data-countryCode="KH" value="855">Cambodia (+855)</option>
                                        		<option data-countryCode="CM" value="237">Cameroon (+237)</option>
                                        		<option data-countryCode="CA" value="1">Canada (+1)</option>
                                        		<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                        		<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                        		<option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                        		<option data-countryCode="CL" value="56">Chile (+56)</option>
                                        	<!--	<option data-countryCode="CN" value="86">China (+86)</option> -->
                                        		<option data-countryCode="CO" value="57">Colombia (+57)</option>
                                        		<option data-countryCode="KM" value="269">Comoros (+269)</option>
                                        		<option data-countryCode="CG" value="242">Congo (+242)</option>
                                        		<option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                        		<option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                        		<option data-countryCode="HR" value="385">Croatia (+385)</option>
                                        		<option data-countryCode="CU" value="53">Cuba (+53)</option>
                                        		<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                        		<option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                        		<option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                        		<option data-countryCode="DK" value="45">Denmark (+45)</option>
                                        		<option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                        		<option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                        		<option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                        		<option data-countryCode="EC" value="593">Ecuador (+593)</option>
                                        		<option data-countryCode="EG" value="20">Egypt (+20)</option>
                                        		<option data-countryCode="SV" value="503">El Salvador (+503)</option>
                                        		<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                        		<option data-countryCode="ER" value="291">Eritrea (+291)</option>
                                        		<option data-countryCode="EE" value="372">Estonia (+372)</option>
                                        		<option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                        		<option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                        		<option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                        		<option data-countryCode="FJ" value="679">Fiji (+679)</option>
                                        		<option data-countryCode="FI" value="358">Finland (+358)</option>
                                        		<option data-countryCode="FR" value="33">France (+33)</option>
                                        		<option data-countryCode="GF" value="594">French Guiana (+594)</option>
                                        		<option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                        		<option data-countryCode="GA" value="241">Gabon (+241)</option>
                                        		<option data-countryCode="GM" value="220">Gambia (+220)</option>
                                        		<option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                        		<option data-countryCode="DE" value="49">Germany (+49)</option>
                                        		<option data-countryCode="GH" value="233">Ghana (+233)</option>
                                        		<option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                        		<option data-countryCode="GR" value="30">Greece (+30)</option>
                                        		<option data-countryCode="GL" value="299">Greenland (+299)</option>
                                        		<option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                        		<option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                        		<option data-countryCode="GU" value="671">Guam (+671)</option>
                                        		<option data-countryCode="GT" value="502">Guatemala (+502)</option>
                                        		<option data-countryCode="GN" value="224">Guinea (+224)</option>
                                        		<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                        		<option data-countryCode="GY" value="592">Guyana (+592)</option>
                                        		<option data-countryCode="HT" value="509">Haiti (+509)</option>
                                        		<option data-countryCode="HN" value="504">Honduras (+504)</option>
                                        		<option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                        		<option data-countryCode="HU" value="36">Hungary (+36)</option>
                                        		<option data-countryCode="IS" value="354">Iceland (+354)</option>
                                        		<option data-countryCode="IN" value="91">India (+91)</option>
                                        		<option data-countryCode="ID" value="62">Indonesia (+62)</option>
                                        		<option data-countryCode="IR" value="98">Iran (+98)</option>
                                        		<option data-countryCode="IQ" value="964">Iraq (+964)</option>
                                        		<option data-countryCode="IE" value="353">Ireland (+353)</option>
                                        		<option data-countryCode="IL" value="972">Israel (+972)</option>
                                        		<option data-countryCode="IT" value="39">Italy (+39)</option>
                                        		<option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                        		<option data-countryCode="JP" value="81">Japan (+81)</option>
                                        		<option data-countryCode="JO" value="962">Jordan (+962)</option>
                                        		<option data-countryCode="KZ" value="8">Kazakhstan (+7)</option>
                                        		<option data-countryCode="KE" value="254">Kenya (+254)</option>
                                        		<option data-countryCode="KI" value="686">Kiribati (+686)</option>
                                        		<option data-countryCode="KP" value="850">Korea North (+850)</option>
                                        		<option data-countryCode="KR" value="82">Korea South (+82)</option>
                                        		<option data-countryCode="KW" value="965">Kuwait (+965)</option>
                                        		<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                        		<option data-countryCode="LA" value="856">Laos (+856)</option>
                                        		<option data-countryCode="LV" value="371">Latvia (+371)</option>
                                        		<option data-countryCode="LB" value="961">Lebanon (+961)</option>
                                        		<option data-countryCode="LS" value="266">Lesotho (+266)</option>
                                        		<option data-countryCode="LR" value="231">Liberia (+231)</option>
                                        		<option data-countryCode="LY" value="218">Libya (+218)</option>
                                        		<option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                        		<option data-countryCode="LT" value="370">Lithuania (+370)</option>
                                        		<option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                        		<option data-countryCode="MO" value="853">Macao (+853)</option>
                                        		<option data-countryCode="MK" value="389">Macedonia (+389)</option>
                                        		<option data-countryCode="MG" value="261">Madagascar (+261)</option>
                                        		<option data-countryCode="MW" value="265">Malawi (+265)</option>
                                        		<option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                        		<option data-countryCode="MV" value="960">Maldives (+960)</option>
                                        		<option data-countryCode="ML" value="223">Mali (+223)</option>
                                        		<option data-countryCode="MT" value="356">Malta (+356)</option>
                                        		<option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                        		<option data-countryCode="MQ" value="596">Martinique (+596)</option>
                                        		<option data-countryCode="MR" value="222">Mauritania (+222)</option>
                                        		<option data-countryCode="YT" value="269">Mayotte (+269)</option>
                                        		<option data-countryCode="MX" value="52">Mexico (+52)</option>
                                        		<option data-countryCode="FM" value="691">Micronesia (+691)</option>
                                        		<option data-countryCode="MD" value="373">Moldova (+373)</option>
                                        		<option data-countryCode="MC" value="377">Monaco (+377)</option>
                                        		<option data-countryCode="MN" value="976">Mongolia (+976)</option>
                                        		<option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                        		<option data-countryCode="MA" value="212">Morocco (+212)</option>
                                        		<option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                        		<option data-countryCode="MN" value="95">Myanmar (+95)</option>
                                        		<option data-countryCode="NA" value="264">Namibia (+264)</option>
                                        		<option data-countryCode="NR" value="674">Nauru (+674)</option>
                                        		<option data-countryCode="NP" value="977">Nepal (+977)</option>
                                        		<option data-countryCode="NL" value="31">Netherlands (+31)</option>
                                        		<option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                        		<option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                        		<option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                        		<option data-countryCode="NE" value="227">Niger (+227)</option>
                                        		<option data-countryCode="NG" value="234">Nigeria (+234)</option>
                                        		<option data-countryCode="NU" value="683">Niue (+683)</option>
                                        		<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                        		<option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                        		<option data-countryCode="NO" value="47">Norway (+47)</option>
                                        		<option data-countryCode="OM" value="968">Oman (+968)</option>
                                        		<option data-countryCode="PW" value="680">Palau (+680)</option>
                                        		<option data-countryCode="PA" value="507">Panama (+507)</option>
                                        		<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                        		<option data-countryCode="PY" value="595">Paraguay (+595)</option>
                                        		<option data-countryCode="PE" value="51">Peru (+51)</option>
                                        		<option data-countryCode="PH" value="63">Philippines (+63)</option>
                                        		<option data-countryCode="PL" value="48">Poland (+48)</option>
                                        		<option data-countryCode="PT" value="351">Portugal (+351)</option>
                                        		<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                        		<option data-countryCode="QA" value="974">Qatar (+974)</option>
                                        		<option data-countryCode="RE" value="262">Reunion (+262)</option>
                                        		<option data-countryCode="RO" value="40">Romania (+40)</option>
                                        		<option data-countryCode="RU" value="8">Russia (+7)</option>
                                        		<option data-countryCode="RW" value="250">Rwanda (+250)</option>
                                        		<option data-countryCode="SM" value="378">San Marino (+378)</option>
                                        		<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                        		<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                        		<option data-countryCode="SN" value="221">Senegal (+221)</option>
                                        		<option data-countryCode="CS" value="381">Serbia (+381)</option>
                                        		<option data-countryCode="SC" value="248">Seychelles (+248)</option>
                                        		<option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                        		<!-- <option data-countryCode="SG" value="65">Singapore (+65)</option> -->
                                        		<option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                        		<option data-countryCode="SI" value="386">Slovenia (+386)</option>
                                        		<option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                        		<option data-countryCode="SO" value="252">Somalia (+252)</option>
                                        		<option data-countryCode="ZA" value="27">South Africa (+27)</option>
                                        		<option data-countryCode="ES" value="34">Spain (+34)</option>
                                        		<option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                        		<option data-countryCode="SH" value="290">St. Helena (+290)</option>
                                        		<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                        		<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                        		<option data-countryCode="SD" value="249">Sudan (+249)</option>
                                        		<option data-countryCode="SR" value="597">Suriname (+597)</option>
                                        		<option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                        		<option data-countryCode="SE" value="46">Sweden (+46)</option>
                                        		<option data-countryCode="CH" value="41">Switzerland (+41)</option>
                                        		<option data-countryCode="SI" value="963">Syria (+963)</option>
                                        		<option data-countryCode="TW" value="886">Taiwan (+886)</option>
                                        		<option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                        	<!--	<option data-countryCode="TH" value="66">Thailand (+66)</option> -->
                                        		<option data-countryCode="TG" value="228">Togo (+228)</option>
                                        		<option data-countryCode="TO" value="676">Tonga (+676)</option>
                                        		<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                        		<option data-countryCode="TN" value="216">Tunisia (+216)</option>
                                        		<option data-countryCode="TR" value="90">Turkey (+90)</option>
                                        		<option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                        		<option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                        		<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                        		<option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                        		<option data-countryCode="UG" value="256">Uganda (+256)</option>
                                        	<option data-countryCode="GB" value="44">UK (+44)</option> 
                                        		<option data-countryCode="UA" value="380">Ukraine (+380)</option>
                                        		<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                        		<option data-countryCode="UY" value="598">Uruguay (+598)</option>
                                        		<option data-countryCode="US" value="1">USA (+1)</option>
                                        		<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                        		<option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                        		<option data-countryCode="VA" value="379">Vatican City (+379)</option>
                                        		<option data-countryCode="VE" value="58">Venezuela (+58)</option>
                                        		<option data-countryCode="VN" value="84">Vietnam (+84)</option>
                                        		<option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                        		<option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                        		<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                        		<option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                        		<option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                        		<option data-countryCode="ZM" value="260">Zambia (+260)</option>
                                        		<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                                        	</optgroup>
                                        </select>
                                        <input type="text" class="mobile_number" placeholder="<?php echo $language["telephone_number"];?>" name="mobile_number" required />
                                        <div class="select_optionss">
                                        <input type="radio" name="user_rolehwe" value="1" checked> <?php echo $language["member"];?>
										<input type="radio" name="user_rolehwe" value="2"> <?php echo $language["merchant"];?>
										
							
										<input type="hidden" name="user_role" value="1"> 
								<div style="display:none;margin-left: 89px;margin-top: 16px;" id="merchantoptionset">
									<input type="radio" name="user_roleget" value="2" checked="checked"> <?php echo $language["merchant"];?>
									<input type="radio" name="user_roleget" value="5"> <?php echo "Staff";?>
							</div>
									</div>
									
                                        <input type="password" name="password" id='login_pass' class="password" placeholder="<?php echo $language["password"];?>" required />
                                      <div class="input-group-addon">
										   <i  onclick="myFunction()" id="eye_slash" class="fa fa-eye-slash" aria-hidden="true"></i>
										  <span onclick="myFunction()" id="eye_pass"> Show Password </span>
									  </div>
									
                                 <br>
                                 <br>
								        
                                 
                                    <div class="login-bottom login-bottom1">
                                        <div class="submit">
                                            <input type="submit" value="<?php echo $language["login"];?>" name="login" class="submint_login" style="padding:14px;margin-top: -40px;" />
                                        </div>
                                          
<!--
                                        <div class="clear" style="padding:9px;"></div>
-->

                                    </div>
                                </div>
                                </form>
                                                            <!-- newly added guest user button--->

<br>
								          <hr class="first_test"> Or <hr class="second_test">
								          <br>
								          <br>
                                 <button class="guest_user_bt" style="padding:15px;" onclick="location.href='<?php echo $site_url; ?>/merchant_find.php';"><?php echo $language["visitor_guest"];?></button>
								 
         

<!--
                           <button class="guest_user_bt" style="padding:5px;" onclick="location.href='http://kooexchange.com/demo/guest_user.php';">GUEST USER</button>
-->

           <div class="clear" style="padding:25px;"></div>
                            </div>
                            <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">

                                <form method="post" id="koosignup">
								<div class="login-top sign-top">
									<input type="radio" name="user_role" value="1" checked> <?php echo $language["member"];?>
									<input type="radio" name="user_role" value="2"> <?php echo $language["merchant"];?>
									<input type="text" class="name active" placeholder="User Name Here" name="name" value="<?php isset($name) ? $name : ""; ?>" id="reg_name" />
									<select name="countrycode" id="countrycode" class="ct_ctycode">
                                    	<option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                    	<option data-countryCode="CN" value="86">China (+86)</option>
                                    	<option data-countryCode="TH" value="66">Thailand (+66)</option>
                                    	<option data-countryCode="SG" value="65">Singapore (+65)</option>
                                    
                                    	<optgroup label="Other countries">
                                    	<option data-countryCode="DZ" value="213">Algeria (+213)</option>
                                    		<option data-countryCode="AD" value="376">Andorra (+376)</option>
                                    		<option data-countryCode="AO" value="244">Angola (+244)</option>
                                    		<option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                    		<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                    		<option data-countryCode="AR" value="54">Argentina (+54)</option>
                                    		<option data-countryCode="AM" value="374">Armenia (+374)</option>
                                    		<option data-countryCode="AW" value="297">Aruba (+297)</option>
                                    		<option data-countryCode="AU" value="61">Australia (+61)</option>
                                    		<option data-countryCode="AT" value="43">Austria (+43)</option>
                                    		<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                    		<option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                    		<option data-countryCode="BH" value="973">Bahrain (+973)</option>
                                    		<option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                    		<option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                    		<option data-countryCode="BY" value="375">Belarus (+375)</option>
                                    		<option data-countryCode="BE" value="32">Belgium (+32)</option>
                                    		<option data-countryCode="BZ" value="501">Belize (+501)</option>
                                    		<option data-countryCode="BJ" value="229">Benin (+229)</option>
                                    		<option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                    		<option data-countryCode="BT" value="975">Bhutan (+975)</option>
                                    		<option data-countryCode="BO" value="591">Bolivia (+591)</option>
                                    		<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                    		<option data-countryCode="BW" value="267">Botswana (+267)</option>
                                    		<option data-countryCode="BR" value="55">Brazil (+55)</option>
                                    		<option data-countryCode="BN" value="673">Brunei (+673)</option>
                                    		<option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                    		<option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                    		<option data-countryCode="BI" value="257">Burundi (+257)</option>
                                    		<option data-countryCode="KH" value="855">Cambodia (+855)</option>
                                    		<option data-countryCode="CM" value="237">Cameroon (+237)</option>
                                    		<option data-countryCode="CA" value="1">Canada (+1)</option>
                                    		<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                    		<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                    		<option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                    		<option data-countryCode="CL" value="56">Chile (+56)</option>
                                    	<!--	<option data-countryCode="CN" value="86">China (+86)</option> -->
                                    		<option data-countryCode="CO" value="57">Colombia (+57)</option>
                                    		<option data-countryCode="KM" value="269">Comoros (+269)</option>
                                    		<option data-countryCode="CG" value="242">Congo (+242)</option>
                                    		<option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                    		<option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                    		<option data-countryCode="HR" value="385">Croatia (+385)</option>
                                    		<option data-countryCode="CU" value="53">Cuba (+53)</option>
                                    		<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                    		<option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                    		<option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                    		<option data-countryCode="DK" value="45">Denmark (+45)</option>
                                    		<option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                    		<option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                    		<option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                    		<option data-countryCode="EC" value="593">Ecuador (+593)</option>
                                    		<option data-countryCode="EG" value="20">Egypt (+20)</option>
                                    		<option data-countryCode="SV" value="503">El Salvador (+503)</option>
                                    		<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                    		<option data-countryCode="ER" value="291">Eritrea (+291)</option>
                                    		<option data-countryCode="EE" value="372">Estonia (+372)</option>
                                    		<option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                    		<option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                    		<option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                    		<option data-countryCode="FJ" value="679">Fiji (+679)</option>
                                    		<option data-countryCode="FI" value="358">Finland (+358)</option>
                                    		<option data-countryCode="FR" value="33">France (+33)</option>
                                    		<option data-countryCode="GF" value="594">French Guiana (+594)</option>
                                    		<option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                    		<option data-countryCode="GA" value="241">Gabon (+241)</option>
                                    		<option data-countryCode="GM" value="220">Gambia (+220)</option>
                                    		<option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                    		<option data-countryCode="DE" value="49">Germany (+49)</option>
                                    		<option data-countryCode="GH" value="233">Ghana (+233)</option>
                                    		<option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                    		<option data-countryCode="GR" value="30">Greece (+30)</option>
                                    		<option data-countryCode="GL" value="299">Greenland (+299)</option>
                                    		<option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                    		<option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                    		<option data-countryCode="GU" value="671">Guam (+671)</option>
                                    		<option data-countryCode="GT" value="502">Guatemala (+502)</option>
                                    		<option data-countryCode="GN" value="224">Guinea (+224)</option>
                                    		<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                    		<option data-countryCode="GY" value="592">Guyana (+592)</option>
                                    		<option data-countryCode="HT" value="509">Haiti (+509)</option>
                                    		<option data-countryCode="HN" value="504">Honduras (+504)</option>
                                    		<option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                    		<option data-countryCode="HU" value="36">Hungary (+36)</option>
                                    		<option data-countryCode="IS" value="354">Iceland (+354)</option>
                                    		<option data-countryCode="IN" value="91">India (+91)</option>
                                    		<option data-countryCode="ID" value="62">Indonesia (+62)</option>
                                    		<option data-countryCode="IR" value="98">Iran (+98)</option>
                                    		<option data-countryCode="IQ" value="964">Iraq (+964)</option>
                                    		<option data-countryCode="IE" value="353">Ireland (+353)</option>
                                    		<option data-countryCode="IL" value="972">Israel (+972)</option>
                                    		<option data-countryCode="IT" value="39">Italy (+39)</option>
                                    		<option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                    		<option data-countryCode="JP" value="81">Japan (+81)</option>
                                    		<option data-countryCode="JO" value="962">Jordan (+962)</option>
                                    		<option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                    		<option data-countryCode="KE" value="254">Kenya (+254)</option>
                                    		<option data-countryCode="KI" value="686">Kiribati (+686)</option>
                                    		<option data-countryCode="KP" value="850">Korea North (+850)</option>
                                    		<option data-countryCode="KR" value="82">Korea South (+82)</option>
                                    		<option data-countryCode="KW" value="965">Kuwait (+965)</option>
                                    		<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                    		<option data-countryCode="LA" value="856">Laos (+856)</option>
                                    		<option data-countryCode="LV" value="371">Latvia (+371)</option>
                                    		<option data-countryCode="LB" value="961">Lebanon (+961)</option>
                                    		<option data-countryCode="LS" value="266">Lesotho (+266)</option>
                                    		<option data-countryCode="LR" value="231">Liberia (+231)</option>
                                    		<option data-countryCode="LY" value="218">Libya (+218)</option>
                                    		<option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                    		<option data-countryCode="LT" value="370">Lithuania (+370)</option>
                                    		<option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                    		<option data-countryCode="MO" value="853">Macao (+853)</option>
                                    		<option data-countryCode="MK" value="389">Macedonia (+389)</option>
                                    		<option data-countryCode="MG" value="261">Madagascar (+261)</option>
                                    		<option data-countryCode="MW" value="265">Malawi (+265)</option>
                                    		<option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                    		<option data-countryCode="MV" value="960">Maldives (+960)</option>
                                    		<option data-countryCode="ML" value="223">Mali (+223)</option>
                                    		<option data-countryCode="MT" value="356">Malta (+356)</option>
                                    		<option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                    		<option data-countryCode="MQ" value="596">Martinique (+596)</option>
                                    		<option data-countryCode="MR" value="222">Mauritania (+222)</option>
                                    		<option data-countryCode="YT" value="269">Mayotte (+269)</option>
                                    		<option data-countryCode="MX" value="52">Mexico (+52)</option>
                                    		<option data-countryCode="FM" value="691">Micronesia (+691)</option>
                                    		<option data-countryCode="MD" value="373">Moldova (+373)</option>
                                    		<option data-countryCode="MC" value="377">Monaco (+377)</option>
                                    		<option data-countryCode="MN" value="976">Mongolia (+976)</option>
                                    		<option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                    		<option data-countryCode="MA" value="212">Morocco (+212)</option>
                                    		<option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                    		<option data-countryCode="MN" value="95">Myanmar (+95)</option>
                                    		<option data-countryCode="NA" value="264">Namibia (+264)</option>
                                    		<option data-countryCode="NR" value="674">Nauru (+674)</option>
                                    		<option data-countryCode="NP" value="977">Nepal (+977)</option>
                                    		<option data-countryCode="NL" value="31">Netherlands (+31)</option>
                                    		<option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                    		<option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                    		<option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                    		<option data-countryCode="NE" value="227">Niger (+227)</option>
                                    		<option data-countryCode="NG" value="234">Nigeria (+234)</option>
                                    		<option data-countryCode="NU" value="683">Niue (+683)</option>
                                    		<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                    		<option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                    		<option data-countryCode="NO" value="47">Norway (+47)</option>
                                    		<option data-countryCode="OM" value="968">Oman (+968)</option>
                                    		<option data-countryCode="PW" value="680">Palau (+680)</option>
                                    		<option data-countryCode="PA" value="507">Panama (+507)</option>
                                    		<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                    		<option data-countryCode="PY" value="595">Paraguay (+595)</option>
                                    		<option data-countryCode="PE" value="51">Peru (+51)</option>
                                    		<option data-countryCode="PH" value="63">Philippines (+63)</option>
                                    		<option data-countryCode="PL" value="48">Poland (+48)</option>
                                    		<option data-countryCode="PT" value="351">Portugal (+351)</option>
                                    		<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                    		<option data-countryCode="QA" value="974">Qatar (+974)</option>
                                    		<option data-countryCode="RE" value="262">Reunion (+262)</option>
                                    		<option data-countryCode="RO" value="40">Romania (+40)</option>
                                    		<option data-countryCode="RU" value="8">Russia (+7)</option>
                                    		<option data-countryCode="RW" value="250">Rwanda (+250)</option>
                                    		<option data-countryCode="SM" value="378">San Marino (+378)</option>
                                    		<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                    		<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                    		<option data-countryCode="SN" value="221">Senegal (+221)</option>
                                    		<option data-countryCode="CS" value="381">Serbia (+381)</option>
                                    		<option data-countryCode="SC" value="248">Seychelles (+248)</option>
                                    		<option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                    		<!-- <option data-countryCode="SG" value="65">Singapore (+65)</option> -->
                                    		<option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                    		<option data-countryCode="SI" value="386">Slovenia (+386)</option>
                                    		<option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                    		<option data-countryCode="SO" value="252">Somalia (+252)</option>
                                    		<option data-countryCode="ZA" value="27">South Africa (+27)</option>
                                    		<option data-countryCode="ES" value="34">Spain (+34)</option>
                                    		<option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                    		<option data-countryCode="SH" value="290">St. Helena (+290)</option>
                                    		<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                    		<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                    		<option data-countryCode="SD" value="249">Sudan (+249)</option>
                                    		<option data-countryCode="SR" value="597">Suriname (+597)</option>
                                    		<option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                    		<option data-countryCode="SE" value="46">Sweden (+46)</option>
                                    		<option data-countryCode="CH" value="41">Switzerland (+41)</option>
                                    		<option data-countryCode="SI" value="963">Syria (+963)</option>
                                    		<option data-countryCode="TW" value="886">Taiwan (+886)</option>
                                    		<option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                    	<!--	<option data-countryCode="TH" value="66">Thailand (+66)</option> -->
                                    		<option data-countryCode="TG" value="228">Togo (+228)</option>
                                    		<option data-countryCode="TO" value="676">Tonga (+676)</option>
                                    		<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                    		<option data-countryCode="TN" value="216">Tunisia (+216)</option>
                                    		<option data-countryCode="TR" value="90">Turkey (+90)</option>
                                    		<option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                    		<option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                    		<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                    		<option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                    		<option data-countryCode="UG" value="256">Uganda (+256)</option>
                                    	<option data-countryCode="GB" value="44">UK (+44)</option> 
                                    		<option data-countryCode="UA" value="380">Ukraine (+380)</option>
                                    		<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                    		<option data-countryCode="UY" value="598">Uruguay (+598)</option>
                                    		<option data-countryCode="US" value="1">USA (+1)</option>
                                    		<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                    		<option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                    		<option data-countryCode="VA" value="379">Vatican City (+379)</option>
                                    		<option data-countryCode="VE" value="58">Venezuela (+58)</option>
                                    		<option data-countryCode="VN" value="84">Vietnam (+84)</option>
                                    		<option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                    		<option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                    		<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                    		<option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                    		<option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                    		<option data-countryCode="ZM" value="260">Zambia (+260)</option>
                                    		<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                                    	</optgroup>
                                    </select>
                                     <input type="text" class="mobile_number" placeholder="<?php echo $language["telephone_number"];?> " name="mobile_number" id="reg_mobnum" /> 
<!--
                                      <input type="text" class="company_name" placeholder="Company Name " name="company_name" id="company_name" /> 
-->
                                     
                                        <input type="password" class="password" id="Password"  placeholder="Password" name="password"  />
										 <div class="input-group-addon">
										   <i  onclick="myFunction2()" id="eye_slash_2" class="fa fa-eye-slash" aria-hidden="true"></i>
										  <span onclick="myFunction2()" id="eye_pass_2"> Show Password </span>
									  </div>
                                         <input type="password" name="cpassword" id="cpassword"  placeholder="Confirm Password" class="col-md-9" >
										 <div class="input-group-addon">
										   <i  onclick="myFunction3()" id="eye_slash_3" class="fa fa-eye-slash" aria-hidden="true"></i>
										  <span onclick="myFunction3()" id="eye_pass_3"> Show Password </span>
									  </div>
                                        <span id="message"></span> 
                                        <input type="text" class="referral_id" placeholder="Referral Id" name="referral_id" />    
                                       <div class="clear"></div> 
        								<label>Security Questions</label>
        								<select name= "questions" style="width: 100%;">
        								<option value="default">Select a desired question</option>
        								<option value="what is the name of your secondary school?">what is the name of your secondary school?</option>
        								<option value="What's the name of your best friend?">What's the name of your best friend?</option>
        								<option value="What is your favorite model of car?">What is your favorite model of car?</option>
        								<option value="Where would you like to visit again?">Where would you like to visit again?</option>
        								</select>
        								<input type="text" class="referral_id" placeholder="Security Answers" name="security" />
        								<input value="<?php isset($email) ? $email : ""; ?>" type="email" class="email" placeholder="Email" name="email"  />
        								
        								 <input type="hidden" name="signup" value="signup"/>
        								 <br>
        								 <label style="padding-right: 10px;">K1 / K2 Type: </label>
        								 <select name="account_type" class="form-control">
        								     <option value="">Non K1 / K2</option>
	                                         <option value="K1">K1</option>
	                                         <option value="K2">K2</option>
	                                         <option value="K1 & K2">K1 & K2</option>
	                                    </select> 
        								<div class="terms_condtions">
        								    <input type="checkbox" name="checkbox" value="check" id="agree" required/> I have read and agree with the <a class="termsss" href="<?php echo $site_url;?>/documents/terms/Terms and conditions for koofood.docx">"Terms and Conditions"</a> and <a class="termsss" href="<?php echo $site_url;?>/documents/terms/privacy agreement.docx">"Privacy Policy"</a>  From KOO.
        								</div>
                                           
                                        <div class="login-bottom">
                                            <div class="submit">
                                                <input type="submit" value="REGISTER" style="padding:14px;" />
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
								</form>
                            </div>
							<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-2">
                                <form method="post">
									
								<div class="login-top sign-top">
									<label>User Login Password</label> <br>
									
									 <div class="select_optionss">
                                        <input type="radio" name="user_role" value="1" checked> <?php echo $language["member"];?>
									<input type="radio" name="user_role" value="2"> <?php echo $language["merchant"];?>
									</div>
									
								    <select name="countrycode" id="countrycode" class="ct_ctycode">
	<option data-countryCode="MY" value="60">Malaysia (+60)</option>
	<option data-countryCode="CN" value="86">China (+86)</option>
	<option data-countryCode="TH" value="66">Thailand (+66)</option>
	<option data-countryCode="SG" value="65">Singapore (+65)</option>

	<optgroup label="Other countries">
	<option data-countryCode="DZ" value="213">Algeria (+213)</option>
		<option data-countryCode="AD" value="376">Andorra (+376)</option>
		<option data-countryCode="AO" value="244">Angola (+244)</option>
		<option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
		<option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
		<option data-countryCode="AR" value="54">Argentina (+54)</option>
		<option data-countryCode="AM" value="374">Armenia (+374)</option>
		<option data-countryCode="AW" value="297">Aruba (+297)</option>
		<option data-countryCode="AU" value="61">Australia (+61)</option>
		<option data-countryCode="AT" value="43">Austria (+43)</option>
		<option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
		<option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
		<option data-countryCode="BH" value="973">Bahrain (+973)</option>
		<option data-countryCode="BD" value="880">Bangladesh (+880)</option>
		<option data-countryCode="BB" value="1246">Barbados (+1246)</option>
		<option data-countryCode="BY" value="375">Belarus (+375)</option>
		<option data-countryCode="BE" value="32">Belgium (+32)</option>
		<option data-countryCode="BZ" value="501">Belize (+501)</option>
		<option data-countryCode="BJ" value="229">Benin (+229)</option>
		<option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
		<option data-countryCode="BT" value="975">Bhutan (+975)</option>
		<option data-countryCode="BO" value="591">Bolivia (+591)</option>
		<option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
		<option data-countryCode="BW" value="267">Botswana (+267)</option>
		<option data-countryCode="BR" value="55">Brazil (+55)</option>
		<option data-countryCode="BN" value="673">Brunei (+673)</option>
		<option data-countryCode="BG" value="359">Bulgaria (+359)</option>
		<option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
		<option data-countryCode="BI" value="257">Burundi (+257)</option>
		<option data-countryCode="KH" value="855">Cambodia (+855)</option>
		<option data-countryCode="CM" value="237">Cameroon (+237)</option>
		<option data-countryCode="CA" value="1">Canada (+1)</option>
		<option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
		<option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
		<option data-countryCode="CF" value="236">Central African Republic (+236)</option>
		<option data-countryCode="CL" value="56">Chile (+56)</option>
	<!--	<option data-countryCode="CN" value="86">China (+86)</option> -->
		<option data-countryCode="CO" value="57">Colombia (+57)</option>
		<option data-countryCode="KM" value="269">Comoros (+269)</option>
		<option data-countryCode="CG" value="242">Congo (+242)</option>
		<option data-countryCode="CK" value="682">Cook Islands (+682)</option>
		<option data-countryCode="CR" value="506">Costa Rica (+506)</option>
		<option data-countryCode="HR" value="385">Croatia (+385)</option>
		<option data-countryCode="CU" value="53">Cuba (+53)</option>
		<option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
		<option data-countryCode="CY" value="357">Cyprus South (+357)</option>
		<option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
		<option data-countryCode="DK" value="45">Denmark (+45)</option>
		<option data-countryCode="DJ" value="253">Djibouti (+253)</option>
		<option data-countryCode="DM" value="1809">Dominica (+1809)</option>
		<option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
		<option data-countryCode="EC" value="593">Ecuador (+593)</option>
		<option data-countryCode="EG" value="20">Egypt (+20)</option>
		<option data-countryCode="SV" value="503">El Salvador (+503)</option>
		<option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
		<option data-countryCode="ER" value="291">Eritrea (+291)</option>
		<option data-countryCode="EE" value="372">Estonia (+372)</option>
		<option data-countryCode="ET" value="251">Ethiopia (+251)</option>
		<option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
		<option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
		<option data-countryCode="FJ" value="679">Fiji (+679)</option>
		<option data-countryCode="FI" value="358">Finland (+358)</option>
		<option data-countryCode="FR" value="33">France (+33)</option>
		<option data-countryCode="GF" value="594">French Guiana (+594)</option>
		<option data-countryCode="PF" value="689">French Polynesia (+689)</option>
		<option data-countryCode="GA" value="241">Gabon (+241)</option>
		<option data-countryCode="GM" value="220">Gambia (+220)</option>
		<option data-countryCode="GE" value="7880">Georgia (+7880)</option>
		<option data-countryCode="DE" value="49">Germany (+49)</option>
		<option data-countryCode="GH" value="233">Ghana (+233)</option>
		<option data-countryCode="GI" value="350">Gibraltar (+350)</option>
		<option data-countryCode="GR" value="30">Greece (+30)</option>
		<option data-countryCode="GL" value="299">Greenland (+299)</option>
		<option data-countryCode="GD" value="1473">Grenada (+1473)</option>
		<option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
		<option data-countryCode="GU" value="671">Guam (+671)</option>
		<option data-countryCode="GT" value="502">Guatemala (+502)</option>
		<option data-countryCode="GN" value="224">Guinea (+224)</option>
		<option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
		<option data-countryCode="GY" value="592">Guyana (+592)</option>
		<option data-countryCode="HT" value="509">Haiti (+509)</option>
		<option data-countryCode="HN" value="504">Honduras (+504)</option>
		<option data-countryCode="HK" value="852">Hong Kong (+852)</option>
		<option data-countryCode="HU" value="36">Hungary (+36)</option>
		<option data-countryCode="IS" value="354">Iceland (+354)</option>
		<option data-countryCode="IN" value="91">India (+91)</option>
		<option data-countryCode="ID" value="62">Indonesia (+62)</option>
		<option data-countryCode="IR" value="98">Iran (+98)</option>
		<option data-countryCode="IQ" value="964">Iraq (+964)</option>
		<option data-countryCode="IE" value="353">Ireland (+353)</option>
		<option data-countryCode="IL" value="972">Israel (+972)</option>
		<option data-countryCode="IT" value="39">Italy (+39)</option>
		<option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
		<option data-countryCode="JP" value="81">Japan (+81)</option>
		<option data-countryCode="JO" value="962">Jordan (+962)</option>
		<option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
		<option data-countryCode="KE" value="254">Kenya (+254)</option>
		<option data-countryCode="KI" value="686">Kiribati (+686)</option>
		<option data-countryCode="KP" value="850">Korea North (+850)</option>
		<option data-countryCode="KR" value="82">Korea South (+82)</option>
		<option data-countryCode="KW" value="965">Kuwait (+965)</option>
		<option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
		<option data-countryCode="LA" value="856">Laos (+856)</option>
		<option data-countryCode="LV" value="371">Latvia (+371)</option>
		<option data-countryCode="LB" value="961">Lebanon (+961)</option>
		<option data-countryCode="LS" value="266">Lesotho (+266)</option>
		<option data-countryCode="LR" value="231">Liberia (+231)</option>
		<option data-countryCode="LY" value="218">Libya (+218)</option>
		<option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
		<option data-countryCode="LT" value="370">Lithuania (+370)</option>
		<option data-countryCode="LU" value="352">Luxembourg (+352)</option>
		<option data-countryCode="MO" value="853">Macao (+853)</option>
		<option data-countryCode="MK" value="389">Macedonia (+389)</option>
		<option data-countryCode="MG" value="261">Madagascar (+261)</option>
		<option data-countryCode="MW" value="265">Malawi (+265)</option>
		<option data-countryCode="MY" value="60">Malaysia (+60)</option>
		<option data-countryCode="MV" value="960">Maldives (+960)</option>
		<option data-countryCode="ML" value="223">Mali (+223)</option>
		<option data-countryCode="MT" value="356">Malta (+356)</option>
		<option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
		<option data-countryCode="MQ" value="596">Martinique (+596)</option>
		<option data-countryCode="MR" value="222">Mauritania (+222)</option>
		<option data-countryCode="YT" value="269">Mayotte (+269)</option>
		<option data-countryCode="MX" value="52">Mexico (+52)</option>
		<option data-countryCode="FM" value="691">Micronesia (+691)</option>
		<option data-countryCode="MD" value="373">Moldova (+373)</option>
		<option data-countryCode="MC" value="377">Monaco (+377)</option>
		<option data-countryCode="MN" value="976">Mongolia (+976)</option>
		<option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
		<option data-countryCode="MA" value="212">Morocco (+212)</option>
		<option data-countryCode="MZ" value="258">Mozambique (+258)</option>
		<option data-countryCode="MN" value="95">Myanmar (+95)</option>
		<option data-countryCode="NA" value="264">Namibia (+264)</option>
		<option data-countryCode="NR" value="674">Nauru (+674)</option>
		<option data-countryCode="NP" value="977">Nepal (+977)</option>
		<option data-countryCode="NL" value="31">Netherlands (+31)</option>
		<option data-countryCode="NC" value="687">New Caledonia (+687)</option>
		<option data-countryCode="NZ" value="64">New Zealand (+64)</option>
		<option data-countryCode="NI" value="505">Nicaragua (+505)</option>
		<option data-countryCode="NE" value="227">Niger (+227)</option>
		<option data-countryCode="NG" value="234">Nigeria (+234)</option>
		<option data-countryCode="NU" value="683">Niue (+683)</option>
		<option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
		<option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
		<option data-countryCode="NO" value="47">Norway (+47)</option>
		<option data-countryCode="OM" value="968">Oman (+968)</option>
		<option data-countryCode="PW" value="680">Palau (+680)</option>
		<option data-countryCode="PA" value="507">Panama (+507)</option>
		<option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
		<option data-countryCode="PY" value="595">Paraguay (+595)</option>
		<option data-countryCode="PE" value="51">Peru (+51)</option>
		<option data-countryCode="PH" value="63">Philippines (+63)</option>
		<option data-countryCode="PL" value="48">Poland (+48)</option>
		<option data-countryCode="PT" value="351">Portugal (+351)</option>
		<option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
		<option data-countryCode="QA" value="974">Qatar (+974)</option>
		<option data-countryCode="RE" value="262">Reunion (+262)</option>
		<option data-countryCode="RO" value="40">Romania (+40)</option>
		<option data-countryCode="RU" value="7">Russia (+7)</option>
		<option data-countryCode="RW" value="250">Rwanda (+250)</option>
		<option data-countryCode="SM" value="378">San Marino (+378)</option>
		<option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
		<option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
		<option data-countryCode="SN" value="221">Senegal (+221)</option>
		<option data-countryCode="CS" value="381">Serbia (+381)</option>
		<option data-countryCode="SC" value="248">Seychelles (+248)</option>
		<option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
		<!-- <option data-countryCode="SG" value="65">Singapore (+65)</option> -->
		<option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
		<option data-countryCode="SI" value="386">Slovenia (+386)</option>
		<option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
		<option data-countryCode="SO" value="252">Somalia (+252)</option>
		<option data-countryCode="ZA" value="27">South Africa (+27)</option>
		<option data-countryCode="ES" value="34">Spain (+34)</option>
		<option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
		<option data-countryCode="SH" value="290">St. Helena (+290)</option>
		<option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
		<option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
		<option data-countryCode="SD" value="249">Sudan (+249)</option>
		<option data-countryCode="SR" value="597">Suriname (+597)</option>
		<option data-countryCode="SZ" value="268">Swaziland (+268)</option>
		<option data-countryCode="SE" value="46">Sweden (+46)</option>
		<option data-countryCode="CH" value="41">Switzerland (+41)</option>
		<option data-countryCode="SI" value="963">Syria (+963)</option>
		<option data-countryCode="TW" value="886">Taiwan (+886)</option>
		<option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
	<!--	<option data-countryCode="TH" value="66">Thailand (+66)</option> -->
		<option data-countryCode="TG" value="228">Togo (+228)</option>
		<option data-countryCode="TO" value="676">Tonga (+676)</option>
		<option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
		<option data-countryCode="TN" value="216">Tunisia (+216)</option>
		<option data-countryCode="TR" value="90">Turkey (+90)</option>
		<option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
		<option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
		<option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
		<option data-countryCode="TV" value="688">Tuvalu (+688)</option>
		<option data-countryCode="UG" value="256">Uganda (+256)</option>
	<option data-countryCode="GB" value="44">UK (+44)</option> 
		<option data-countryCode="UA" value="380">Ukraine (+380)</option>
		<option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
		<option data-countryCode="UY" value="598">Uruguay (+598)</option>
		<option data-countryCode="US" value="1">USA (+1)</option>
		<option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
		<option data-countryCode="VU" value="678">Vanuatu (+678)</option>
		<option data-countryCode="VA" value="379">Vatican City (+379)</option>
		<option data-countryCode="VE" value="58">Venezuela (+58)</option>
		<option data-countryCode="VN" value="84">Vietnam (+84)</option>
		<option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
		<option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
		<option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
		<option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
		<option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
		<option data-countryCode="ZM" value="260">Zambia (+260)</option>
		<option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
	</optgroup>
</select>
                                        <input type="text" class="mobile_number" placeholder="<?php $language["telephone_number"];?>" name="mobile_number" required />
 
                                    <div class="forgot-bottom">
                                        <div class="submit res_submit" style="margin-top:67px;">
                                                <input type="submit" value="<?php echo $language["submit"];?>" name="forget" style="padding:14px;" />
                                        </div><br/>
                                        <br />
                                        <br />
                                        <div class="clear" ></div>
                                    </div>
                                </div>
								</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"> </div>
        </div>
    </div>
    <!--//main -->
    <div class="copyright">
        <p> &copy; 2018 | All rights reserved | Developed by <a href="#" target="_blank">stallioni</a></p>
    </div>
</body>
<style>
    #koosignup label.error
    {
        
        color:red;
        
    }
    .select_optionss {
    margin-top: 12px;
}
</style>
<script src="js/jquery.min.js"></script>

<script src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script src="js/easyResponsiveTabs.js" type="text/javascript"></script>
   <script>
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
function myFunction2() {
  var x = document.getElementById("Password");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass_2").html('Hide Password');
			 $('#eye_slash_2').removeClass( "fa-eye-slash" );
            $('#eye_slash_2').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass_2").html('Show Password');
	  $('#eye_slash_2').addClass( "fa-eye-slash" );
            $('#eye_slash_2').removeClass( "fa-eye" );
  }
}
function myFunction3() {
  var x = document.getElementById("cpassword");
  if (x.type === "password") {
    x.type = "text";
	    $("#eye_pass_3").html('Hide Password');
			 $('#eye_slash_3').removeClass( "fa-eye-slash" );
            $('#eye_slash_3').addClass( "fa-eye" );
			
  } else {
    x.type = "password";
	 $("#eye_pass_3").html('Show Password');
	  $('#eye_slash_3').addClass( "fa-eye-slash" );
            $('#eye_slash_3').removeClass( "fa-eye" );
  }
}
</script>
    <script type="text/javascript">
    $(document).ready(function()

{
	
 $.validator.addMethod("valueNotEquals", function(value, element, arg){
  return arg !== value;
 }, "Value must not equal arg.");
jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\\s+/g, ""); 
	return this.optional(element) || 
		phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
}, "Please specify a valid telephone number");
$.validator.addMethod('mypassword', function(value, element) {
        return this.optional(element) || (value.match(/[a-zA-Z]/) || value.match(/[0-9]/));
    },
    'Password must contain at least one numeric and one alphabetic character.');
   var theForm = $("#koosignup");
theForm.validate(

{

rules:{
    'countrycode': {
    required: true
 },

'name':{

required: true,

minlength: 1

},
//~ 'mobile_number':{

//~ required: true,
 //~ phoneUS: true,
 //~ minlength: 1,
//~ remote:{

//~ url: "validatorAJAX.php",

//~ type: "post",
//~ data: { countrycode: function(){
     //~ var countrycode = $('#countrycode').val();
     //~ return countrycode;
    //~ } ,
                  
        //~ },
//~ cache : false

//~ }
//~ },



//~ 'email':{


//~ email: true,

//~ remote:{

//~ url: "validatorAJAX.php",

//~ type: "post"

//~ }
//~ },
'password':{

required: true,
mypassword: true,
minlength: 6

},

'cpassword':{
    required: true,

equalTo: '#Password'

},


 //~ 'questions': { valueNotEquals: "default" },


 //~ 'security':{

//~ required: true,

//~ minlength: 1

//~ },



},

messages:{

'name':{

required: "The name field is mandatory!",

minlength: "Choose a username of at least 1 letters!",

},

'mobile_number':{

required: "The telephone number field is mandatory!",
remote: "The telephone number is already in use by another user!"

},

'email':{


   email: "Please enter a valid email address!",
	remote: "The email is already in use by another user!"

 },

'password':{

required: "The password field is mandatory!",

minlength: "Please enter a password at least 6 characters!"

},

'cpassword':{
required: "The confirm password field is mandatory!",

equalTo: "The two passwords do not match!"

},


    //~ 'questions': { valueNotEquals: "Please select an item!" },
//~ 'security':{

//~ required: "The security answers field is mandatory!",

//~ minlength: "Choose a username of at least 1 letters!",

//~ },

}

});
/*
if(theForm.valid() ) {
            theForm.submit(); //submitting the form
        }*/


        			$('#horizontalTab').easyResponsiveTabs({
        				type: 'default', //Types: default, vertical, accordion           
        				width: 'auto', //auto or any width like 600px
        				fit: true   // 100% fit in a container
        			});
        		});
    </script>  
        <script>  
$(document).ready(function(){ 
	 


      $("input[name='user_role']").on("click", function() {
           
            if($(this).val() == 1) 
            { 
    $("#reg_name").attr("placeholder", "User Name Here");
	}
	else
	{
		    $("#reg_name").attr("placeholder", "Company Name Here");

	}
 
            
        });
        });
        
$(document).ready(function(){ 
    
    $(".language_option").change(function(e){
        var lang = $(this).val();
        window.location.href="/login.php?language="+lang;
    });
	$(".sign_up").on("click", function() {
		
		// alert('goodgood');
		 
		   //~ $(".sign_up").css("background-color","green");
		   //~ $(".login-left").css("height", "255px !important");
		   //~ $(".res_submit").css("margin-top", "67px !important");
		   
		   
$('.logo').css('margin-top',"4px");

		  
		   //~ $(".login-left h2").css("margin-top", "0.5em !important");
		   
		
	});
});     
        
    </script>  
    <script type="text/javascript">
$(document).ready(function()
{
	
	
$("input[name='user_rolehwe']").change(function(){

//alert("sdfds");

					var getvalue=$("input[name='user_rolehwe']:checked").val();
					
					if(getvalue==1)
					{
						$("input[name='user_role']").val("1");
						$("#merchantoptionset").hide("slow");
					}
					else{
						$("#merchantoptionset").show("slow");
						$("input[name='user_role']").val("2");
					
					}
					
		});
		
		
		$("input[name='user_roleget']").change(function(){

					var getvalue=$("input[name='user_roleget']:checked").val();
					
					if(getvalue==2)
					{
						$("input[name='user_role']").val("2");
						
					}
					else{
							$("input[name='user_role']").val("5");
					
					}
					
		});
	
	
	



});

</script>
</html>

<!-- newly added by tamil--->
<style>
.login-top {
    margin-top: 0em;
    padding: 12px 12px 0px;
}
button.guest_user_bt {
    margin: 0 auto;
    display: block;
    width: 90%;
    background:#FFB87A;
    color:#1F868B;
    font-weight: 600;
    font-size: 16px;
    border: 2px solid #FFB87A;
    margin-right: 20px;
    cursor: pointer;
}
hr.second_test {
    width: 150px;
    float: right;
}
.terms_condtions {
    margin-top: 12px;
}t
.submit {
     float: none; 
}
hr.first_test {
    width: 150px;
    float: left;
    margin-left: 20px;
    margin-right: 14px;
}
.submint_login {gin 2
    margin: 0 auto;
    display: block;
    width: 90%;
    font-weight: 600;
    font-size: 16px!important;
  
}
input[type="submit"] {
    margin: 0 auto;
    display: block;
    width: 90%;
    font-weight: 600;
    font-size: 16px!important;
    margin-right:0px;
}

.login-left h2 {
     margin-top: 1.5em;
}
.login-form {
	background: url(../images/banner.jpg)no-repeat 0px 0px;
    background-size: cover;
}

@media (min-width: 328px) and (max-width:628px) {  
.login-right 
{
	padding:20px !important;
}
}

</style>
