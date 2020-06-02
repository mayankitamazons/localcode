<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_usd,balance_inr,balance_myr FROM users WHERE id='".$_SESSION['login']."'"));
$security = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions,security_answer FROM users WHERE id='".$_SESSION['login']."'"));
$charges_data = mysqli_query($conn, "SELECT * FROM charges");

if(isset($_POST['submit']))
{
	
	$wallet = addslashes($_POST['wallet']);
	$amount_actual = addslashes($_POST['amount_actual']);
	$user_note = addslashes($_POST['user_note']);
	$verify_code = addslashes($_POST['verify_code']);
	
	$flag = false;
	$error = "";
	
	if($amount_actual == "" || $wallet == "")
	{
		$error .= "All Fields are Required.<br>";
		$flag = true;
	}
	
	if(!is_numeric($amount_actual) || $amount_actual == 0)
	{
		$error .= "Amount is not Valid.<br>";
		$flag = true;
	}
	
	$percent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM charges WHERE wallet='$wallet'"))['percent'];
	$amount = $amount_actual * (100 + $percent) / 100;
	
	if($wallet == "USD")
	{
		if($balance['balance_usd'] < $amount)
		{
			$error .= "Not Enough Balance In Your Wallet, Recharge Your Wallet First.<br>";
			$flag = true;
		}
	}
	else if($wallet == "INR")
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
		
	}
	else
	{
		$error .= "Please Generate Verification Code and Verify It.<br>";
		$flag = true;
	}

	if($flag == false)
	{

	mysqli_query($conn, "INSERT INTO requests SET user_id='".$_SESSION['login']."', amount='".$amount."', amount_actual='".$amount_actual."', wallet='".$wallet."', created_on='".time()."', status='0', user_note='$user_note'");
		
		$error = "Your Request has been Successfully Submitted.";
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
	$security_questions = $row['security_questions'];
	$security_answer = $row['security_answer'];
	$mobile_number = $row['mobile_number'];
	
	
	if($lock_status == 1)
	{
		$error .= "Your account is blocked by Admin.<br>";
	}
	
	//~ if($error == "")
	//~ {
		
		//~ //Print("Sending to one way sms " . gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$cm", "Fund Password for your Account ($cm) : $password"));
		
		
		
	//~ }
	
	if(($security_question == $security_question) && ($security_answer == $security_answer))
	{
		//echo "good";
		
		Print( gw_send_sms("APIHKXVL33N5E", "APIHKXVL33N5EHKXVL", "9787136232", "$mobile_number", "Fund Password for your Account ($mobile_number) : $password"));
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
						<form method="post" onsubmit="return submitForm()">
							<div class="panel price panel-red" style="padding:50px 5px;">
								<?php 
								if(isset($error) && $error != "")
								{
									echo "<div class='alert alert-info'>$error</div>";
								}								
								?>
								<h2>Request Withdrawal</h2>
								<br><br>
								<select class="form-control" name="wallet" onchange="amountExec()" id="wallet">
									<option value="">Select Wallet</option>
									<option value="MYR">Malaysian Ringgit</option>
									<option value="USD">US Dollar</option>
									<option value="INR">Chinese Yuan</option>
								</select>
								<br><br>
								<input type="number" step="0.01" class="form-control" onkeyup="amountExec()" name="amount_actual" id="amount_actual" placeholder="Amount You Want">
								<br><br>
								<input type="text" class="form-control disable" id="amount" placeholder="Amount Deduct From Your Wallet" readonly>
								<br><br>
								<textarea class="form-control" name="user_note" placeholder="You Can Add Details"></textarea>
								<br><br>
								<!--<div class="input-group mb-3">-->
<!--
								  <div class="input-group-prepend">
									<button class="btn btn-outline-secondary" id="generateCode" type="button">Generate</button>
								  </div>
-->
								  <input type="text" name="verify_code" class="form-control" placeholder="Enter Fund Password Here">
								<!--</div>-->
								<br><br>
							   <div name="forgot" id="forgot_pass" value="Forgotten Password">Forgotten Password</div><br>
							   <input type="text" name="details" id="details" class="form-control" placeholder="Enter Details Here">


								<br><br>
								<input type="submit" class="btn btn-block btn-primary" name="submit" value="Request">
							</div>
						</form>
					</div>
					<div class="col-md-3"></div>
				</div> 
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->


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

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>

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
	
	<script type="text/javascript">
	$("#generateCode").click(function(){
		$.post("ajax_generateCode.php", { request:true }, function(response){
			alert(response);
		});
	});
	</script>
	<script type="text/javascript">
	<?php
	while($charges_row = mysqli_fetch_assoc($charges_data))
	{
		?>
		var <?php echo $charges_row['wallet']; ?> = <?php echo $charges_row['percent']; ?>;
		<?php
	}
	echo "\n";
	?>
	var balance_myr = '<?php echo $balance['balance_myr']; ?>';
	var balance_inr = '<?php echo $balance['balance_inr']; ?>';
	var balance_usd = '<?php echo $balance['balance_usd']; ?>';
	
	function amountExec()
	{
		var wallet = $("#wallet").val();
		
		if(wallet == "")
		{
			alert("Please Select Wallet First.");
			$("#amount").val("");
			$("#amount_actual").val("");
			return;
		}
		
		var amount_actual = $("#amount_actual").val();
		
		if(wallet == "INR")
		{
			var amount = amount_actual * (100 + INR) / 100;
		}
		else if(wallet == "MYR")
		{
			var amount = amount_actual * (100 + MYR) / 100;
		}
		else if(wallet == "USD")
		{
			var amount = amount_actual * (100 + USD) / 100;
		}
		
		$("#amount").val(amount);
	}
	
	function submitForm()
	{
		var wallet = $("#wallet").val();
		
		if(wallet == "")
		{
			alert("Please Select Wallet First.");
			$("#amount").val("");
			$("#amount_actual").val("");
			return false;
		}
		
		var amount_actual = $("#amount_actual").val();
		
		if(wallet == "INR")
		{
			var amount = amount_actual * (100 + INR) / 100;
			if(parseFloat(amount) > parseFloat(balance_inr))
			{
				alert("Amount is greater than Balance, Recharge Your Wallet First.");
				return false;
			}
		}
		else if(wallet == "MYR")
		{
			var amount = amount_actual * (100 + MYR) / 100;
			if(parseFloat(amount) > parseFloat(balance_myr))
			{
				alert("Amount is greater than Balance, Recharge Your Wallet First.");
				return false;
			}
		}
		else if(wallet == "USD")
		{
			var amount = amount_actual * (100 + USD) / 100;
			if(parseFloat(amount) > parseFloat(balance_usd))
			{
				alert("Amount is greater than Balance, Recharge Your Wallet First.");
				return false;
			}
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
