<?php
include("config.php");
 //~ $bank_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
$total_rows = mysqli_query($conn, "SELECT * FROM users WHERE user_roles='2' ");

if(isset($_POST['submit_pass']))
{
	
 $rand = $_GET['rand'];
 $mn = $_GET['mn'];
	$new_password = addslashes($_POST['new_password']);
	$confirm_new_password = addslashes($_POST['confirm_new_password']);
	$questions = addslashes($_POST['questions']);
	$answers = addslashes($_POST['answer']);
	$flag = false;
	$error = "";
	
	if($new_password == "" || $confirm_new_password == "")
	{
		$flag = true;
		$error .= "All Fields are required.<br>";
	} 
	//~ $user_old_password = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM users WHERE mobile_number ='".$mn."'"))['password'] ;
	//~ $security_questions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions FROM users WHERE mobile_number ='".$mn."'"))['security_questions'];
	//~ $security_answer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_answer FROM users WHERE mobile_number ='".$mn."'"))['security_answer'];

	
	//~ if($user_old_password != $old_password)
	//~ {
		//~ $flag = true;
		//~ $error .= "Old Password is incorrect.<br>";
	//~ }
	
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
		//~ if($security_questions != $questions)
	//~ {
		//~ $flag = true;
		//~ $error .= "Your chosen question is incorrect.<br>";
	//~ }
		//~ if($security_answer != $answers)
	//~ {
		//~ $flag = true;
		//~ $error .= "Your Answer is wrong.<br>";
	//~ }
	
	; 
	if($flag == false)
	{
		 mysqli_query($conn, "UPDATE users SET password='$new_password', rand_num = '' WHERE rand_num='".$rand."'");
		 $error = "Password Successfully Changed.";
		 session_start();
session_destroy();
header("location:login.php");
		
	}
}

  echo $expire_stamp = date('Y-m-d H:i:s', strtotime("+5 min"));
  echo '<br>';
echo $now_stamp    = date("Y-m-d H:i:s");

//~ echo "Right now: " . $now_stamp;
//~ echo "5 minutes from right now: " . $expire_stamp;


?>


<div class="col-md-6 well">
						    <form method="post">
        						<h3>Forgot Password</h3>
        						
        						<div class="form-group">
        							<label>New Password</label>
        							<input type="password" class="form-control" name="new_password" required>
        						</div>
        						<div class="form-group">
        							<label>Confirm New Password</label>
        							<input type="password" class="form-control" name="confirm_new_password" required>
        						</div>        						
        						<input type="submit" value="Change Password" name="submit_pass" class="btn btn-block btn-primary">
        					</form>
        					<!--fund password-->
        					</div>
<!-- CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="./Dashboard_files/material-icons.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/monosocialiconsfont.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/sweetalert2.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/magnific-popup.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/mediaelementplayer.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/css(1)" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/css(2)" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/weather-icons.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/weather-icons-wind.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/daterangepicker.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/morris.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/slick.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/slick-theme.min.css" rel="stylesheet" type="text/css">
<link href="./Dashboard_files/style.css" rel="stylesheet" type="text/css">
<!-- Head Libs -->
<script src="./Dashboard_files/modernizr.min.js.download"></script>
<style>
.col-md-6.well {
    margin: 15px auto;
    border: 1px solid #8080803b;
        width: 100%;s
}
select {
    padding: 8px;
}

</style>
