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
$m_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['mm_id']."'"));
$security = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions,security_answer FROM users WHERE id='".$_SESSION['login']."'"));

 
if(isset($_POST['submit'])) 
{
	$amount = addslashes($_POST['amount']);
	$fund_password = addslashes($_POST['fund_password']);
	
$fund = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id =".$_SESSION['login']));

	if(isset($fund['fund_password']))
	{
		  
		if($fund['fund_password'] != $fund_password)
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
 if($balance['balance_myr'] < $amount)
		{
			echo $error .= "Insufficient Balance In Your Wallet, Recharge Your Wallet First.";
			$flag = true;
		}
		
	if($flag == false)
	{
			$merchant_id = $_SESSION['mm_id'];
			$userid= $_SESSION['login'];
			$sender_new_balance = $balance['balance_myr'] - $amount;
			$reciever_new_balance = $m_balance['balance_myr'] + $amount;
			mysqli_query($conn, "UPDATE users SET balance_myr='$sender_new_balance' WHERE id= $userid");
			mysqli_query($conn, "UPDATE users SET balance_myr='$reciever_new_balance' WHERE id= $merchant_id");

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
	$error = "";
	$data = mysqli_query($conn, "SELECT  fund_password,isLocked,security_questions,security_answer,mobile_number FROM users WHERE id='".$_SESSION['login']."'");
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


if(!isset($_SESSION['login']))
{
	header("location:login.php");
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
						<form method="post">
				<div class="panel price panel-red" style="padding:50px 5px;">
   <h2>Payment</h2>
   <br>
   <form method="post" id="paymen_menu">
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
      <div class="login-top">
		  <label class="phone_number">Phone Number:</label><br>
		  <p class="mobile_num"><?php echo $m_balance['mobile_number']; ?> </p> 
		  <label class="phone_number">Wallet:</label><br>
		  <p class="mobile_num">MYR </p> 
		  <div name="amount" id="amount" value="amount ">Amount</div>
         <input type="text" name="amount" class="form-control" placeholder="Enter Amount Here">
         <br>                
           <div name="fund" id="fundpassword" value="Fund Password">Fund Password</div>
         <br>
          <input type="password" name="fund_password" id="details" class="form-control" value=""  placeholder="Enter Fund password Here">
         <br><br>
           <div name="forgot" id="forgot_pass" value="Forgotten Password">Forgotten Password</div>
            <br><br>
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
    
    
    <!-- end tamil-->
    



<!-- /.widget-body badge -->
</div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
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
p.mobile_num {
    border: 1px solid #e4e9f0;
    padding: 10px;
}
label.phone_number {
    font-weight: 400;
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
	
</body>

</html>
    
