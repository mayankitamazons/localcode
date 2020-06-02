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
                        //return $ok;  
            }
$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$_SESSION['login']."'"));
$security = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions,security_answer FROM users WHERE id='".$_SESSION['login']."'"));

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

if(isset($_POST['submit']))
{
	
	
	$receiver = addslashes($_POST['receiver']);
	$countrycode = addslashes($_POST['countrycode']);
	$amount = addslashes($_POST['amount']);
	$wallet = addslashes($_POST['wallet']);
	$details = addslashes($_POST['details']);
	$verify_code = addslashes($_POST['verify_code']);
	$stl_key = $_POST['stl_key'];
	
	$flag = false;
	$error = "";
	
	
	$fund = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id =".$_SESSION['login']));
	
	if(isset($fund['fund_password']))
	{
		  
		if($fund['fund_password'] != $verify_code)
		{
			//mysqli_query($conn, "INSERT INTO notifications SET user_id='$reciever_id', notification='You Successfully Received $amount $wallet from $sender', type='receive', created_on='".time()."', readStatus='0'");

			$error .= "Verification Code is Invalid.<br>";
			$flag = true;
		}
		
	}
	else
	{
		$error .= "Please Generate Verification Code and Verify It.<br>";
		$flag = true;
	}
	
	if($receiver == "" || $amount == "" || $wallet == "")
	{
		$error .= "All Fields are Required.<br>";
		$flag = true;
	}
	
	$mobil_pho = $countrycode.''.$receiver;
	
	$reciever_id_result = mysqli_query($conn, "SELECT id,name,balance_usd,balance_inr,balance_myr FROM users WHERE mobile_number='$mobil_pho'");

	 $account_exists = mysqli_num_rows($reciever_id_result);
	//$receiver_row1 = mysqli_fetch_assoc($reciever_id_result);
	if($account_exists == 0)
	{
		$error .= "Receiver does not exists in our Database.<br>";
		$flag = true;
	}
	else
	{
		$sender_id = $_SESSION['login'];
		
		$receiver_row = mysqli_fetch_assoc($reciever_id_result);
		$reciever_id = $receiver_row['id'];
		if($reciever_id == $sender_id)
		{
			$error .= "Transferring Money to Self is not Possible.<br>";
			$flag = true;
		}
	}
	
	if(!is_numeric($amount) || $amount == 0)
	{
		$error .= "Amount is not Valid.<br>";
		$flag = true;
	}
	
	if($wallet == "INR")
	{
		if($balance['balance_inr'] < $amount)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
	}
	else if($wallet == "MYR")
	{
		if($balance['balance_myr'] < $amount)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
	}
	else if($wallet == "USD")
	{
		if($balance['balance_usd'] < $amount)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
	}
	else
	{
		$error .= "Current Currency is not Supported.<br>";
		$flag = true;
	}
	
 if($flag == false)
	{
	if($stl_key == $_SESSION['stl_key']) {
		if($fund['fund_password'] == $verify_code)
		{
	  mysqli_query($conn, "INSERT INTO tranfer SET sender_id='".$sender_id."', receiver_id='".$reciever_id."', amount='".$amount."', details='".$details."',wallet='".$wallet."', created_on='".time()."', status='0'");
	  if($wallet == "INR")
		 {
			 $sender_new_balance = $balance['balance_inr'] - $amount;
			 mysqli_query($conn, "UPDATE users SET balance_inr='$sender_new_balance' WHERE id='$sender_id'");
		 }
		 else if($wallet == "MYR")
		 {
			$sender_new_balance = $balance['balance_myr'] - $amount;
			mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id='$sender_id'");
		}
		else if($wallet == "USD")
		{
			$sender_new_balance = $balance['balance_usd'] - $amount;
			mysqli_query($conn, "UPDATE users SET balance_usd='$sender_new_balance' WHERE id='$sender_id'");
		}
		else
		{
			echo "System Info : An Error Occured"; die;
		}
		$sender = $balance['name']; // sender name
		
		mysqli_query($conn, "INSERT INTO notifications SET user_id='$reciever_id', notification='You Successfully Received $amount $wallet from $sender', type='receive', created_on='".time()."', readStatus='0'");
		//$rece_name = $receiver_row1['name'];
		$success = "You Successfully Transferred $amount $wallet to $receiver";

}
$_SESSION['stl_key'] = "empty";  
}
}


}

/*tamil */


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

		Print( gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$mobile_number", "Fund Password for your Account ($mobile_number) : $password"));
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
					<div class="col-md-3"></div>
					<div class="well col-md-6">
						<form method="post" onsubmit="return checkBal()">
							<?php 
							$stl_key = rand();
							$_SESSION['stl_key'] = $stl_key; ?>
							<input type="hidden" name="stl_key" value="<?php echo $stl_key; ?>">
							<?php
							if(isset($error) && $error != "")
							{
								echo "<div class='alert alert-danger'>$error</div>";
							}
							if(isset($success))
							{
								echo "<div class='alert alert-success'>$success</div>";
							}
							?>
				<div class="panel price panel-red" style="padding:50px 5px;">
   <h2>Transfer Money</h2>
   <br>
   <form method="post" autocomplete="off">
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
         <input type="text" class="mobile_number" placeholder="Telephone Number " value="<?php if(!empty($_POST['receiver'])) { echo $_POST['receiver'] ; } ?> " name="receiver" required="" />								<br>
         
        
         <select class="form-control" required="true" name="wallet" id="wallet">
            <option value="" >Select Wallet</option>
            <option value="MYR" <?php if ($_POST['wallet']== "MYR") { ?> selected="selected" <?php } ?> >Malaysian Ringgit (<?php echo $balance['balance_myr']; ?>)</option>
            <option value="USD" <?php if ($_POST['wallet']== "USD") { ?> selected="selected" <?php } ?> >US Dollar (<?php echo $balance['balance_usd']; ?>) </option>
            <option value="INR" <?php if ($_POST['wallet']== "INR") { ?> selected="selected" <?php } ?> >Chinese Yuan (<?php echo $balance['balance_inr']; ?>)</option>
         </select>
         <br>
         <input type="number" class="form-control" name="amount" id="amount" step="0.01" value="<?php if(!empty($_POST['amount'])) { echo $_POST['amount'] ; } ?> " placeholder="Amount" required="true">
         <br>
         <input type="password" name="verify_code" class="form-control" placeholder="Enter Fund Password Here">
         <br>                
           <div name="forgot" id="forgot_pass" value="Forgotten Password">Forgotten Password</div>
<!--
         <input type="button" class="btn btn-block btn-primary" name="forgot" id="forgot_pass" value="Forgotten Password">
-->
         <br>
          <input type="text" name="details" id="details" class="form-control" value="<?php if(!empty($_POST['details'])) { echo $_POST['details'] ; } ?>"  placeholder="Enter Details Here">
         <br><br>
         <input type="submit" class="btn btn-block btn-primary" name="submit" id="send_pop" value="Send">
      </div>
   </form>
</div>
<div class="col-md-3"></div>
</div>
</main>
</div>
<!-- tamil -->

<div id="overlay">
      <div id="popup">
        <div id="close">X</div>
      								
								<form method="post">
									
								<div class="login-top sign-top">

								<label>Security Questions</label><br>
<!--
								<select name= "questions" class="form-control"style="width: 100%;">
								<option value="default">Select a desired question</option>
								<option value="what is the name of your secondary school?">what is the name of your secondary school?</option>
								<option value="What's the name of your best friend?">What's the name of your best friend?</option>
								<option value="What is your favorite model of car?">What is your favorite model of car?</option>
								<option value="Where would you like to visit again?">Where would you like to visit again?</option>
								</select><br><br>
-->
							
							<input type="text" class="form-control" name="security_questions" value="<?php echo $security['security_questions']; ?>" readonly>
							

							<br>
							
								<input type="text" class="security_answer form-control" placeholder="Security Answers" name="security_answer" />

<!--
                                        <input value="<?php //isset($email) ? $email : ""; ?>" type="email" class="email" placeholder="Email Address" name="email" required="true" />
-->

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
    
    
    <!-- end tamil-->
    
<!-- /.widget-body badge -->
</div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	
	<script type="text/javascript">
		var balance_myr = '<?php echo $balance['balance_myr']; ?>';
		var balance_inr = '<?php echo $balance['balance_inr']; ?>';
		var balance_usd = '<?php echo $balance['balance_usd']; ?>';
		
		function checkBal()
		{
			var wallet = $("#wallet").val();
			var amount = parseFloat($("#amount").val());
			
			var balance = "";
			
			if(wallet == "INR")
			{
				balance = balance_inr;
			}
			else if(wallet == "MYR")
			{
				balance = balance_myr;
			}
			else if(wallet == "USD")
			{
				balance = balance_usd;
			}
			else
			{
				alert("Please Select Wallet.");
				return false;
			}
			
			balance = parseFloat(balance);
			
			if(amount > balance)
			{
				alert("Not Enough Balance In Your Wallet, Recharge Your Wallet First");
				return false;
			}
			else
			{
				return true;
			}
		}
	</script>
	
	<!-- tamil-->
	<script>
	jQuery(document).ready(function() {
  jQuery('#forgot_pass').click(function() {
    jQuery('#overlay').fadeIn(300);  
  });
  jQuery('#close').click(function() {
    jQuery('#overlay').fadeOut(300);
  });
});
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
    
