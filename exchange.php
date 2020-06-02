<?php 
function curl_advanced($type, $url, $dataArray = false, $paramArray = false)
{
	$curl = curl_init();  
	
	if($type == "POST")
	{
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		if(is_array($dataArray))
		{
			curl_setopt($curl, CURLOPT_POSTFIELDS, $dataArray);
		}
	}
	else
	{
		if(is_array($dataArray))
		{
			$data = http_build_query($dataArray);
			curl_setopt($curl, CURLOPT_URL, $url . "?" . $data);
		}
		else
		{
			curl_setopt($curl, CURLOPT_URL, $url);
		}
	}
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	if(is_array($paramArray))
	{
		foreach($paramArray as $option => $value)
		{
			curl_setopt($curl, $option, $value);
		}
	}
	
	$result = curl_exec($curl);
	
	if(!$result)
	{
		return array("status"=>"error","response"=>curl_error($curl));
	}
	
	curl_close($curl);
	
	return array("status"=>"success","response"=>$result);
}

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
                            $error .= "SMS '.$ok Send .<br>";
                        //return $ok; 
                         
            }
            
if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

 $exchange_rates = json_decode(curl_advanced("GET","http://data.fixer.io/api/latest?access_key=4a09fd56906d7e19824484da8dd99f65&symbols=MYR,CNY")['response'], true);
  $exchange_rates2 = json_decode(curl_advanced("GET","http://data.fixer.io/api/latest?access_key=4a09fd56906d7e19824484da8dd99f65&symbols=CNY")['response'], true);

 $security = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions,security_answer FROM users WHERE id='".$_SESSION['login']."'"));
//~ $exchange_rates = json_decode(curl_advanced("GET","http://api.fixer.io/latest?symbols=MYR,INR&base=USD")['response'], true);
//~ $exchange_rates2 = json_decode(curl_advanced("GET","http://api.fixer.io/latest?symbols=INR&base=MYR")['response'], true);

$margin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT margin FROM ex_margin LIMIT 1"))['margin'];
$minified_rate = ( 100 - $margin ) / 100;

$rates = array(
	'usd_inr'=>($exchange_rates['rates']['CNY'] * $minified_rate),
	'inr_usd'=>(1 / $exchange_rates['rates']['CNY'] / $minified_rate),
	'usd_myr'=>($exchange_rates['rates']['MYR'] * $minified_rate),
	'myr_usd'=>(1 / $exchange_rates['rates']['MYR'] / $minified_rate),
	'myr_inr'=>($exchange_rates2['rates']['CNY'] * $minified_rate),
	'inr_myr'=>(1 / $exchange_rates2['rates']['CNY'] / $minified_rate)
);
$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_usd,balance_inr,balance_myr FROM users WHERE id='".$_SESSION['login']."'"));

if(isset($_POST['submit']))
{
	$wallet_from = addslashes($_POST['wallet_from']);
	$wallet_to = addslashes($_POST['wallet_to']);
	$amount_from = addslashes($_POST['amount_from']);
	$amount_to = addslashes($_POST['amount_to']);
	$user_note = addslashes($_POST['user_note']);
	$verify_code = addslashes($_POST['verify_code']);
	
	$flag = false;
	$error = ""; 
	
	if($wallet_from == "" || $wallet_to == "" || $amount_from == "" || $amount_to == "")
	{
		$error .= "All Fields are Required.<br>";
		$flag = true;
	}
	
	if(!is_numeric($amount_from) || $amount_from == 0)
	{
		$error .= "Amount (From) is not Valid.<br>";
		$flag = true;
	}
	
	if(!is_numeric($amount_to) || $amount_to == 0)
	{
		$error .= "Amount (To) is not Valid.<br>";
		$flag = true;
	}
	
	if($wallet_from == "INR")
	{
		if($balance['balance_inr'] < $amount_from)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
		
		if($wallet_to == "USD")
		{
			$rate = $rates['inr_usd'];
		}
		else
		{
			$rate = $rates['inr_myr'];
		}
	}
	else if($wallet_from == "MYR")
	{
		if($balance['balance_myr'] < $amount_from)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
		
		if($wallet_to == "USD")
		{
			$rate = $rates['myr_usd'];
		}
		else
		{
			$rate = $rates['myr_inr'];
		}
	}
	else if($wallet_from == "USD")
	{
		if($balance['balance_usd'] < $amount_from)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
		
		if($wallet_to == "MYR")
		{
			$rate = $rates['usd_myr'];
		}
		else
		{
			$rate = $rates['usd_inr'];
		}
	}
	else
	{
		$error .= "Current Currency is not Supported.<br>";
		$flag = true;
	}
	$fund = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id =".$_SESSION['login']));
	if(isset($fund['fund_password']))
	{
		if($fund['fund_password'] != $verify_code)
		{
			$error .= "Verification Code is Invalid.<br>";
			$flag = true;
		}
		//~ else
		//~ {
			//~ unset($_SESSION['verify_code']); 
		//~ }
	}
	else
	{
		$error .= "Please Generate Verification Code and Verify It.<br>";
		$flag = true;
	}
	
	if($flag == false)
	{
		
		mysqli_query($conn, "INSERT INTO ex_requests SET user_id='".$_SESSION['login']."', amount_from='".$amount_from."', wallet_from='".$wallet_from."', amount_to='".$amount_to."', wallet_to='".$wallet_to."', user_note='".$user_note."', created_on='".time()."', status='0', rate='$rate'");
		$last_id = mysqli_insert_id($conn);
		curl_advanced("GET","$site_url/admin_panel/update_ex_request.php?recordid=$last_id&updatedstatus=2");
		$error = "Exchange Request Successfully Submitted.<br>";
	}
}
?>
<?php
if(isset($_POST['forget_fund']))
{
	$security_questions = addslashes($_POST['security_questions']);
	$security_answer = addslashes($_POST['security_answer']);
	$mobile_number = addslashes($_POST['mobile_number']);
	$countrycode = addslashes($_POST['countrycode']);
	$cm =	$countrycode.''.$mobile_number;
	//~ print_r($cm);
	
	

	//$email = addslashes($_POST['email']);	
	$error = "";
	//~ if($mobile_number == "" )
	//~ {
		//~ $error .= "Mobile Number is not Valid.<br>";
	//~ }
	
	//~ if($email == "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	//~ {
		//~ $error .= "Email is not Valid.<br>";
	//~ }
	$data = mysqli_query($conn, "SELECT  fund_password,isLocked,security_questions,security_answer,mobile_number FROM users WHERE id='".$_SESSION['login']."'");

	//~ $data = mysqli_query($conn, "SELECT password,isLocked FROM users WHERE email='$email'");
	$count = mysqli_num_rows($data);
	if($count == 0)
	{
		$error .= "Account does not exists in our Database.<br>";
	}
	
	$row = mysqli_fetch_assoc($data);
	
	$lock_status = $row['isLocked'];
	$password = $row['fund_password'];
	$dsecurity_questions = $row['security_questions'];
	$dsecurity_answer = $row['security_answer'];
	$mobile_number = $row['mobile_number'];
	
	
	if($lock_status == 1)
	{
		$error .= "Your account is blocked by Admin.<br>";
	}
	
	//~ if($error == "")
	//~ {
		
		//~ //Print("Sending to one way sms " . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Fund Password for your Account ($cm) : $password"));
		
		
		
	//~ }
	
	if(($dsecurity_question == $security_question) && ($dsecurity_answer == $security_answer))
	{
                    $error .= "SMS Send your phone .<br>";
		// Print( gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$mobile_number", "Fund Password for your Account ($mobile_number) : $password"));
	}
	else
	{

		  $error .= "Your Answer is wrong. .<br>";
	}
	

}
?>

<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	<style>
	.well
	{
		min-height: 20px;
		padding: 19px;
		margin-bottom: 20px;
		background-color: #fff;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
	</style>
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="container">
					<?php
						if(isset($error))
						{
							echo "<div class='alert alert-info'>".$error."</div>";
						}
					?>
					</div>
					<div class="row" style="padding:15px">
						<div class="well col-md-6">
							<form method="post" onsubmit="return change_amount()">
								<div class="panel price panel-red" style="padding:50px 5px;">
									<h2>Exchange Request</h2>
									<br><br>
									<div class="form-group">
										<label>I Have :</label>
										<div>
										<select class="form-control" required="true" name="wallet_from" id="wallet_from" onchange="change_amount()">
											<option value="">Select Currency</option>
											<option value="MYR">Malaysian Ringgit (<?php echo $balance['balance_myr']; ?>) </option>
											<option value="USD">US Dollar  (<?php echo $balance['balance_usd']; ?>) </option>
											<option value="INR">Chinese Yuan  (<?php echo $balance['balance_inr']; ?>)</option>
										</select>
										<br>
										<input type="text" class="form-control" name="amount_from" id="amount_from" placeholder="Amount I Have" onkeyup="change_amount('from')">
										</div>
									</div>
									<div class="form-group">
										<label>I Want :</label>
										<div>
										<select class="form-control" required="true" name="wallet_to" id="wallet_to" onchange="change_amount()">
											<option value="">Select Currency</option>
											<option value="MYR">Malaysian Ringgit</option>
											<option value="USD">US Dollar</option>
											<option value="INR">Chinese Yuan</option>
										</select>
										<br>
										<input type="text" class="form-control" name="amount_to" id="amount_to" placeholder="Amount I Want" onkeyup="change_amount('to')">
										</div>
									</div>
									<div class="form-group">
										<label>Note:</label>
										<textarea class="form-control" name="user_note" placeholder="Add Details Here"></textarea>
									</div>
									<br>
									<!--<div class="input-group mb-3">-->
<!--
									  <div class="input-group-prepend">
										<button class="btn btn-outline-secondary" id="generateCode" type="button">Generate</button>
									  </div>
-->
									  <input type="text" name="verify_code" class="form-control" placeholder="Enter Fund Password Here"><br>
									<!--</div>-->
									<div name="forgot" id="forgot_pass" value="Forgotten Password">Forgotten Password</div><br>
							       <input type="text" name="details" id="details" class="form-control" placeholder="Enter Details Here">
									<br><br>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Request Exchange">
								</div>
							</form>
						</div>
						<div class="col-md-6"><div class="row" style="padding:0px 10px">
							<div class="col-md-6 well text-center"><div style="padding:5px"><img src="db_icons/mobile.png" style="width:50%"></div><h4>Prep. Mobile-M'...</h4></div>
							<div class="col-md-6 well text-center"><div style="padding:5px"><img src="db_icons/mobile.png" style="width:50%"></div><h4>Prep. Mobile-M...</h4></div>
							<div class="col-md-6 well text-center"><div style="padding:5px"><img src="db_icons/mobile.png" style="width:50%"></div><h4>Oversea IDD</h4></div>
							<div class="col-md-6 well text-center"><div style="padding:5px"><img src="db_icons/internet.png" style="width:50%"></div><h4>IDD/Internet</h4></div>
							<div class="col-md-6 well text-center"><div style="padding:5px"><img src="db_icons/games.png" style="width:50%"></div><h4>Online Games</h4></div>
							<div class="col-md-6 well text-center"><div style="padding:5px"><img src="db_icons/bills.png" style="width:50%"></div><h4>Bill Payment</h4></div>
							<div class="col-md-6 well text-center"><div style="padding:5px"><img src="db_icons/others.png" style="width:50%"></div><h4>Others</h4></div>
							<div class="col-md-6 well text-center"><div style="padding:5px"><img src="db_icons/pay.png" style="width:50%"></div><h4>Remittance</h4></div>
						</div></div>
					</div>
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->
    <div id="overlay">
      <div id="popup">
        <div id="close">X</div>
      								
								<form method="post">
									
								<div class="login-top sign-top">

								<label>Security Questions</label><br>

							<input type="text" class="form-control" name="security_questions" value="<?php echo $security['security_questions']; ?>" readonly>
							

							<br>
							
								<input type="text" class="security_answer form-control" placeholder="Security Answers" name="security_answer" />
                                    <div class="forgot-bottom">
                                        <div class="submit test_save">
                                                <input type="submit" value="SUBMIT" name="forget_fund" />
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
								</form>
                           
                           
      </div>
    </div>
    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	<script type="text/javascript">
     jQuery(document).ready(function() {
       jQuery('#forgot_pass').click(function() {
       jQuery('#overlay').fadeIn(300);  
      });
     jQuery('#close').click(function() {
     jQuery('#overlay').fadeOut(300);
    });
  });

	$("#generateCode").click(function(){
		$.post("ajax_generateCode.php", { request:true }, function(response){
			alert(response);
		});
	});
	</script>
	
	<script type="text/javascript">
		function change_amount(changingIt)
		{
			var amount_from = $("#amount_from").val(); var amount_to = $("#amount_to").val();
			var wallet_from = $("#wallet_from").val(); var wallet_to = $("#wallet_to").val();
			
			if(wallet_from == "" || wallet_to == "")
			{
				return false;
			}

			if(wallet_from == wallet_to)
			{
				alert("Error : Conversion to Same Currency is not Allowed.");
				$("#wallet_from").val(""); $("#wallet_to").val("");
				$("#amount_from").val(""); $("#amount_to").val("");
				return false;
			}
			
			var rate = "";
			
			if(wallet_from == "USD" && wallet_to == "INR")
			{
				rate = <?php echo $rates['usd_inr']; ?>;
			}
			else if(wallet_from == "INR" && wallet_to == "USD")
			{
				rate = <?php echo $rates['inr_usd']; ?>;
			}
			else if(wallet_from == "USD" && wallet_to == "MYR")
			{
				rate = <?php echo $rates['usd_myr']; ?>;
			}
			else if(wallet_from == "MYR" && wallet_to == "USD")
			{
				rate = <?php echo $rates['myr_usd']; ?>;
			}
			else if(wallet_from == "MYR" && wallet_to == "INR")
			{
				rate = <?php echo $rates['myr_inr']; ?>;
			}
			else if(wallet_from == "INR" && wallet_to == "MYR")
			{
				rate = <?php echo $rates['inr_myr']; ?>;;
			}
			
			if(changingIt == "to")
			{
				if(amount_to == "" || amount_to == 0)
				{
					$("#amount_from").val("");
					return false;
				}
				
				$("#amount_from").val( amount_to / rate );
			}
			else
			{
				if(amount_from == "" || amount_from == 0)
				{
					$("#amount_to").val("");
					return false;
				}
				$("#amount_to").val( amount_from * rate );
			}
			
			return true;
		}
	</script>

<style>
	#overlay {
  position: fixed;
  height: 100%;
  width: 100%;
  top: 0;  
  right: 0;
  bottom: 0;
  left:0;
  background: rgba(0, 0, 0, 0.21);
  display: none;
  margin-top: 100px;
}
.fund_country{
width:45%;
margin-right: 12px;
}
div#forgot_pass {
    cursor: pointer;
}
.submit.test_save.input-has-value {
    margin-top: 25px;
    text-align: center;
}

#popup {
  max-width: 445px;
  width: 80%;
  max-height: 300px;
  padding: 20px;
  position: relative;
  background: #fff;
  margin: 20px auto;
}

#close {
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
  color: #000;
}
label.fund_password {
    margin-top: 15px;
    margin-bottom: 20px;
}
	</style>

</body>

</html>
