<?php 
include("config.php");

if(!isset($_SESSION['login']))
{
	header("location:login.php");
}

$bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
$user_mobile = mysqli_fetch_assoc(mysqli_query($conn, "SELECT mobile_number FROM users WHERE id='".$_SESSION['login']."'"))['mobile_number'];
$k_history = mysqli_query($conn, "SELECT * FROM k_type WHERE user_id='".$_SESSION['login']."'");

if(isset($_POST['submit']))
{
	$ref_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, created_at, referred_by, k_date, account_type FROM users WHERE id='".$_SESSION['login']."'"));
	$user_id = $ref_result['id'];
	if(($ref_result['created_at'] != null) || ($ref_result['created_at'] != "")){
		$created_date = strtotime($ref_result['created_at']);
		$num_date = ceil((time() - $created_date)/60/60/24);
	} else {
		$date = date('y-m-d');
		$num_date = 183;
	}
	if($ref_result['referred_by'] == ""){
	    $num_date = 183;
		$date = "";
	}
	
	$type_expire = ceil((time() - strtotime($ref_result['k_date']))/60/60/24);
	
	$error = "";
	$expired_flag = false;
	$expired_type_flag = false;
	
	$realname = addslashes($_POST['realname']);
	$mric_number = addslashes($_POST['mric_number']);
	$address = addslashes($_POST['address']);
	$email = addslashes($_POST['email']);
	$facebook = addslashes($_POST['facebook']);
	$authentication = addslashes($_POST['authentication']);
	$bankName = addslashes($_POST['bankName']);
	$branchName = addslashes($_POST['branchName']);
	$ifsc_code = addslashes($_POST['ifsc_code']);
	$ac_num = addslashes($_POST['ac_num']);
	$doc_copy = addslashes($_POST['doc_copy']);
    $number_lock = addslashes($_POST['number_lock']);
    $account_type = addslashes($_POST['account_type']);
    $print_ip_address_user = $_POST['print_ip_address_user'];
    
    if($number_lock =="on") $number_lock = '1';
	else $number_lock = "0";
	
	if($k_lock == "on") $k_lock = '1';
	else $k_lock = "0";
	
    if($num_date < 183){
		$expired_flag = false;
		$referral_id = $ref_result['referred_by'];
		$date = $ref_result['created_at'];
	} else {
		$expired_flag = true;
		$referral_id = addslashes($_POST['referral_id']);
		$existing_referral = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE referral_id='".$referral_id."'"));
		if(count($existing_referral) > 0){
		    $referred_by = $referral_id;
		    $date = date('y-m-d');
		} else {
		    $referred_by = $ref_result['referred_by'];
		    $date = $ref_result['created_at'];
		    
		}
		$referral_id = addslashes($_POST['referral_id']);
		$date = date('y-m-d');
	}
	
	if($type_expire < 2){
	    $expired_type_flag = false;
	    $account_type = $ref_result['account_type'];
	    $k_date = $ref_result['k_date'];
	} else {
	    $expired_type_flag = true;
	    $account_type = addslashes($_POST['account_type']);
	    $k_date = date('Y-m-d');
	    mysqli_query($conn, "INSERT INTO k_type SET user_id='$user_id', type='$account_type', date='$k_date'");
	}
	
	$flag = false;
	$error = ""; 
	
	/*if($bankName == "" || $branchName == "" || $ifsc_code == "" || $ac_num == "" || $realname == "" || $mric_number == "" || $address == "" || $facebook == "" || $authentication == ""|| $email == "")
	{
		$flag = true;
		$error = "All Fields are required.<br>";
	}*/
	
	$already_exists = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='$email'"));
	/*if($already_exists > 0)
	{
		$error .= "Email Address Already Exists.Try Another Email<br>";
	}*/
	if($_FILES['doc_copy']['name'] != "")
	{
		$filename = $_FILES['doc_copy']['name'];
		move_uploaded_file($_FILES['doc_copy']['tmp_name'], "documents/".$filename);
	}
	else
	{
		$filename = $bank_data['doc_copy'];
	}
	
	if($flag == false)
	{
		mysqli_query($conn, "UPDATE users SET print_ip_address_user='$print_ip_address_user',bank_name='$bankName',referred_by='$referral_id', real_name='$realname', mric_no='$mric_number', address='$address',email='$email', number_lock='$number_lock', facebook='$facebook', authentication='$authentication', bank_branch='$branchName', bank_ifsc='$ifsc_code', bank_ac_num='$ac_num', doc_copy='$filename', created_at='$date', account_type='$account_type', k_date='$k_date', k_lock='$k_lock' WHERE id='".$_SESSION['login']."'");
		$error = "Successfully Updated Bank Details.<br>";
		if($expired_flag == false){
			$error .= "You can change the referral id after ".(183 - $num_date)." days. <br />"; 
		}
		if($expired_type_flag == false){
		    $error .= "You can change the K1/K2 tomorrow.";
		}
	}
}
else
{
	$realname = $bank_data['real_name'];
	$mric_number = $bank_data['mric_no'];
	$address = $bank_data['address'];
	$email = $bank_data['email'];
	$facebook = $bank_data['facebook'];
	$authentication = $bank_data['authentication'];
	$bankName = $bank_data['bank_name'];
	$print_ip_address_user = $bank_data['print_ip_address_user'];
	$branchName = $bank_data['bank_branch'];
	$ifsc_code = $bank_data['bank_ifsc'];
	$ac_num = $bank_data['bank_ac_num'];
	$filename = $bank_data['doc_copy'];
	$referral_id = $bank_data['referred_by'];
	$number_lock = $bank_data['number_lock'];
	$account_type = $bank_data['account_type'];
}

if(isset($_POST['submit_pass']))
{
	$old_password = addslashes($_POST['old_password']);
	$new_password = addslashes($_POST['new_password']);
	$confirm_new_password = addslashes($_POST['confirm_new_password']);
	$questions = addslashes($_POST['questions']);
	$answers = addslashes($_POST['answer']);
	$flag = false;
	$error = "";
	
	if($old_password == "" || $new_password == "" || $confirm_new_password == "")
	{
		$flag = true;
		$error .= "All Fields are required.<br>";
	}
	$user_old_password = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM users WHERE id='".$_SESSION['login']."'"))['password'] ;
	$security_questions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions FROM users WHERE id='".$_SESSION['login']."'"))['security_questions'];
	$security_answer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_answer FROM users WHERE id='".$_SESSION['login']."'"))['security_answer'];

	
	if($user_old_password != $old_password)
	{
		$flag = true;
		$error .= "Old Password is incorrect.<br>";
	}
	
	if(strlen($new_password) < 7 || strlen($new_password) > 15)
	{
		$flag = true;
		$error .= "New Password must between 7 to 15 characters.<br>";
	}
	
	if($new_password != $confirm_new_password)
	{
		$flag = true;
		$error .= "New Password does not match.<br>";
	}
		if($security_questions != $questions)
	{
		$flag = true;
		$error .= "Your chosen question is incorrect.<br>";
	}
		if($security_answer != $answers)
	{
		$flag = true;
		$error .= "your answer is incorrect, please try again.<br>";
	}
	
	; 
	if($flag == false)
	{
		 mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE id='".$_SESSION['login']."'");
		 $error = "Password Successfully Changed.";
		 session_start();
header("location:login.php");
		
	}
}
/*fund password*/

if(isset($_POST['submit_fundpass']))
{
	$old_fundpassword = addslashes($_POST['old_fundpassword']);
	$new_fundpassword = addslashes($_POST['new_fundpassword']);
	$confirm_new_fundpassword = addslashes($_POST['confirm_new_fundpassword']);
	$questions = addslashes($_POST['questions']);
	$answers = addslashes($_POST['answer']);
	$flag = false;
	$error = "";    
	
	if($old_fundpassword == "" || $new_fundpassword == "" || $confirm_new_fundpassword == "")
	{
		$flag = true;
		$error .= "All Fields are required.<br>";
	}
	$user_old_fundpassword = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fund_password FROM users WHERE id='".$_SESSION['login']."'"))['fund_password'] ;
	$security_questions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions FROM users WHERE id='".$_SESSION['login']."'"))['security_questions'];
	$security_answer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_answer FROM users WHERE id='".$_SESSION['login']."'"))['security_answer'];
	if($user_old_fundpassword != $old_fundpassword)
	{
		$flag = true;
		$error .= "Old Password is incorrect.<br>";
	}
	
	if(strlen($new_fundpassword) < 7 || strlen($new_fundpassword) > 15)
	{
		$flag = true;
		$error .= "New Password must between 7 to 15 characters.<br>";
	}
	
	if($new_fundpassword != $confirm_new_fundpassword)
	{
		$flag = true;
		$error .= "New Password does not match.<br>";
	}
		if($security_questions != $questions)
	{
		$flag = true;
		$error .= "Your chosen question is incorrect.<br>";
	}
		if($security_answer != $answers)
	{
		$flag = true;
		$error .= "your answer is incorrect, please try again.<br>";
	}
	
	
	if($flag == false)
	{
		//mysqli_query($conn, "UPDATE users SET fund_password='$new_fundpassword' WHERE id='".$_SESSION['login']."'");
		$error = "Fund Password Successfully Changed.";
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
					<div class="container" >
					    <div class="row">
					        <div class="well col-md-6">
							<form method="post" onsubmit="return change_amount()" enctype="multipart/form-data" id="profile_account">
								<div class="panel price panel-red">
									<h2>Update Bank Details</h2>
									<br><br>
									<div class="form-group">
										<label>Real Name (as per IC)</label>
										<input type="text" name="realname" class="form-control" value="<?php if(isset($realname)){ echo $realname; }?>" >
									</div>
									<div class="form-group">
										<label>NRIC number</label>
										<input type="text" name="mric_number" class="form-control" value="<?php if(isset($mric_number)){ echo $mric_number; }?>" >

									</div>
									<div class="form-group">
										<label>Address</label>
										<input type="text" name="address" class="form-control" value="<?php if(isset($address)){ echo $address; }?>" >
									</div>
									<div class="form-group">
										<label>Email Address</label>
										<input type="text" name="email" class="form-control" value="<?php if(isset($email)){ echo $email; }?>" >
									</div>
										<div class="form-group">
										<label>User Printer IP Address</label>
										<input type="text" name="print_ip_address_user" class="form-control" value="<?php if(isset($print_ip_address_user)){ echo $print_ip_address_user; }?>">
									</div>
									<div class="form-group">
										<label>Phone Number <br>
										<span class="tele_num"><?php echo $bank_data['mobile_number']; ?></span> 
										</label>
									</div>
									<div class="form-group">
										<label>Hide Number</label><br>
										<input class="hide_number" type="checkbox" name="number_lock" <?php if($number_lock == '1') echo "checked='checked'";?>" ><br>
									</div>
									<div class="form-group">
										<label>K1/K2 Type</label><br>
										<select class='account_kType' name="account_type" style="">
										    <option <?php if($account_type == '') echo 'selected'; ?> value="">Non K1/K2</option>
										    <option <?php if($account_type == 'K1') echo 'selected'; ?> value="K1">K1</option>
										    <option <?php if($account_type == 'K2') echo 'selected'; ?> value="K2">K2</option>
										    <option <?php if($account_type == 'K1 & K2') echo 'selected'; ?> value="K1 & K2">K1 & K2</option>
										</select>
										<br />
										<!--<input type="checkbox" class="k_lock" name="k_lock" <?php if($k_lock == '1') echo "checked='checked'";?>" >
										I only allow above members to place orders.--> 
										<br>
									</div>
									
									<div class="form-group">
									    <label>Date of changing - Discount to K1/K2</label><br>
									    <table class="table table-striped" id="kType_table">
									        <thead>
                                                <tr>
                                                    <th>Date</th>           
                                                    <th>Type</th>                    
                                                    <th>Discount</th>    
                                              </tr>
                                           </thead>
                                           <tbody>
                                               <?php while ($row=mysqli_fetch_assoc($k_history)){ ?>
                                                <tr>
                                                    <td><?php echo substr($row['date'], 0, 10); ?></td>
                                                    <td><?php echo $row['type']?></td>
                                                    <?php if(strlen($row['type']) > 2){?>
                                                        <td>4%</td>
                                                    <?php } else if(strlen($row['type']) == 2){?>
                                                        <td>2%</td>
                                                    <?php } else if(strlen($row['type']) < 2){ ?>
                                                        <td>0%</td>
                                                    <?php }?>
                                                </tr>
                                               <?php }?>
                                           </tbody>
									    </table>
									</div>
									
									<div class="form-group input-has-value">
										<label>Referral ID<br>
										<input type="text" name="referral_id" class="form-control" value="<?php if(isset($referral_id)){ echo $referral_id; }?>" >
									</div>
									<div class="form-group">
										<label>Facebook</label>
										<input type="text" name="facebook" class="form-control" value="<?php if(isset($facebook)){ echo $facebook; }?>" >
									</div>
									<div class="form-group">
										<label>Authy Authentication</label>
										<input type="text" name="authentication" class="form-control" value="<?php if(isset($authentication)){ echo $authentication; }?>" >
									</div>
									<div class="form-group">
										<label>Bank Name</label>
										<input type="text" name="bankName" class="form-control" value="<?php if(isset($bankName)){ echo $bankName; }?>" >
									</div>
									<div class="form-group">
										<label>Branch Name</label>
										<input type="text" name="branchName" class="form-control" value="<?php if(isset($branchName)){ echo $branchName; }?>" >
									</div>
									<div class="form-group">
										<label>IFSC Code</label>
										<input type="text" name="ifsc_code" class="form-control" value="<?php if(isset($ifsc_code)){ echo $ifsc_code; }?>" >
									</div>
									<div class="form-group">
										<label>Account Number</label>
										<input type="text" name="ac_num" class="form-control" value="<?php if(isset($ac_num)){ echo $ac_num; }?>" >
									</div>
									<div class="form-group">
										<label>Document Copy</label><br>
										<input type="file" name="doc_copy" >
										<?php
										if(isset($filename) && $filename != "")
										{
										?>
										<a href="documents/<?php echo $filename; ?>" target="_blank">View Older Copy</a>
										<?php
										}
										?>
									</div>
									<br>
									<input type="submit" class="btn btn-block btn-primary" name="submit" value="Update Details">
								</div>
							</form>
						</div>
						<div class="col-md-6 well">
						    <form method="post">
        						<h3>Change Password</h3>
        						<div class="form-group">
        							<label>Old Password</label>
        							<input type="password" class="form-control" name="old_password" required>
        						</div>
        						<div class="form-group">
        							<label>New Password</label>
        							<input type="password" class="form-control" name="new_password" required>
        						</div>
        						<div class="form-group">
        							<label>Confirm New Password</label>
        							<input type="password" class="form-control" name="confirm_new_password" required>
        						</div>
        						<!--<div class="form-group">								
        							<label>Security Questions</label>
        							<br>
										<select name= "questions">
										<option value="">Select a desired question</option>
										<option value="what is the name of your secondary school?">what is the name of your secondary school?</option>
										<option value="What's the name of your best friend?">What's the name of your best friend?</option>
										<option value="What is your favorite model of car?">What is your favorite model of car?</option>
										<option value="Where would you like to visit again?">Where would you like to visit again?</option>
										</select>   
								</div>-->
        						
<!--
        						<div class="form-group">
        							<label>Answer</label>
        							<input type="text" class="form-control" name="answer" required>
        						</div>
-->
        						<input type="submit" value="Change Password" name="submit_pass" class="btn btn-block btn-primary">
        					</form>
        					<!--fund password-->
        				<form method="post">
        				    <?php //$user_fundpassword = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fund_password FROM users WHERE id='".$_SESSION['login']."'")); ?>
<!--
							<label>	Fund Password : <?php //print_r($user_fundpassword['fund_password']); ?>
</label>
-->
        						<h3>Change Fund Password</h3>
        						<div class="form-group">
        							<label>Old Password</label>
        							<input type="password" class="form-control" name="old_fundpassword" required>
        						</div>
        						<div class="form-group"> 
        							<label>New Password</label>
        							<input type="password" class="form-control" name="new_fundpassword" required>
        						</div>
        						<div class="form-group">
        							<label>Confirm New Password</label>
        							<input type="password" class="form-control" name="confirm_new_fundpassword" required>
        						</div>
        						<div class="form-group">								
        							<label>Security Questions</label>
        							<br>
										<select name= "questions">
										<option value="">Select a desired question</option>
										<option value="what is the name of your secondary school?">what is the name of your secondary school?</option>
										<option value="What's the name of your best friend?">What's the name of your best friend?</option>
										<option value="What is your favorite model of car?">What is your favorite model of car?</option>
										<option value="Where would you like to visit again?">Where would you like to visit again?</option>
										</select>   
								</div>
        						
        						<div class="form-group">
        							<label>Answer</label>
        							<input type="text" class="form-control" name="answer" required>
        						</div>
        						<input type="submit" value="Change Fund Password" name="submit_fundpass" class="btn btn-block btn-primary">
        					</form>		
					    </div>
					</div>
				</div>
				
<h3 class="text_qrcode">QR Code</h3>
		<br>
		<div class="col-md-3"></div>
		<div class="well col-md-6">
						<div style="margin:10px">
							<img src="qrcode/qrcode.php?text=<?php echo $user_mobile; ?>" style="width:100%" class="text_qrcode">
						</div>
					</div>
						</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
</body>

</html>
<style>
select {
    height: 30px;
}
</style>
 <script type="text/javascript">
    $(document).ready(function()
{
   var theForm = $("#profile_account");
theForm.validate(

{

'email':{


email: true,

remote:{

url: "validatorAJAX.php",

type: "post"

}
},


},

messages:{
'email':{
email: "Please enter a valid email address!",
remote: "The email is already in use by another user!"
},



}

});

    </script>   
<style>
  .tele_num{
	font-weight: 400;
    display: block;
    width: 345%;
    padding: 0.5625rem 1.2em;
    font-size: 0.875rem;
    line-height: 1.57143;
    color: #74708d;
    background-color: #fff;
    background-image: none;
    background-clip: padding-box;
    border: 1px solid #e4e9f0;
    border-radius: 0.25rem;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s, -webkit-box-shadow ease-in-out 0.15s;
}
h3.text_qrcode {
    width: 100%;
}
@media only screen and (max-width: 600px) and (min-width: 360px)  {
.well.col-md-6 {
    width: 335px;
}
}
</style>
