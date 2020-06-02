<?php 
include("config.php");

function gw_send_sms($user,$pass,$sms_from,$sms_to,$sms_msg)  
            {           
                        $query_string = "api.aspx?apiusername=".$user."&apipassword=".$pass;
                        $query_string .= "&senderid=".rawurlencode($sms_from)."&mobileno=".rawurlencode($sms_to);
                        $query_string .= "&message=".rawurlencode(stripslashes($sms_msg)) . "&languagetype=1";        
                        $url = "http://gateway.onewaysms.com.au:10001/".$query_string;       
                        $fd = @implode ('', file ($url));      
                        if ($fd)  
                        {                       
				    if ($fd > 0) {
					//Print("MT ID : " . $fd);
					$ok = "success";
				    }        
				    else {
					print("Please refer to API on Error : " . $fd);
					$ok = "fail";
				    }
                        }           
                        else      
                        {                       
                                    // no contact with gateway                      
                                    $ok = "fail";       
                        }           
                        return $ok;  
            }  
 if(isset($_POST['signup']))
{
  
	$name = addslashes($_POST['name']);
	$user_role = addslashes($_POST['user_role']);
	$email = addslashes($_POST['email']);
	$password = addslashes($_POST['password']);
	$security = addslashes($_POST['security']);
	$questions = addslashes($_POST['questions']);
	 $mobile_number = addslashes($_POST['mobile_number']);
	 $error = "";
	
		if($error == "")
	{
		$code = uniqid();

		$fund_pass = mt_rand(100000, 999999);

		$ref= $name." ".$code;
		$reffered_by= $_POST['referral_id'];
	
		 mysqli_query($conn, "INSERT INTO users SET name='$name',user_roles='$user_role', password='$password', joined='".time()."', isLocked='1', verification_code='$code', referral_id='$ref',referred_by='$reffered_by',security_answer= '$security',security_questions= '$questions',fund_password='$fund_pass',email='$email',mobile_number='$mobile_number'");
		 
		$user_id = mysqli_insert_id($conn);
			$current_url = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];

		Print("Sending to one way sms " . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$mobile_number", "Verify Your Account on koofamilies $current_url?code=$code&id=$user_id"));
		$current_url = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
		$subject = "Verify Your Account | koofamilies";

		$message = "
		<html>
		<head>
		<title>Verify Your Account | koofamilies</title>
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
		//$headers .= 'Cc: myboss@example.com' . "\r\n";

	//	mail($email,$subject,$message,$headers);
	//	  $result = @mail( $to, '', $message );
		$error = "Registered Successfully, Verification Mobile Number has been sent to your Mobile Number.";
		
		

		
		
		
	}
		
		
		
	
}

 
 ?>
