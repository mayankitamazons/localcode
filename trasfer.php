<?php 
include("config.php");

	$user_id=$_SESSION['login'];
	//~$order_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `order_list` WHERE id = 75"));
$security = mysqli_fetch_assoc(mysqli_query($conn, "SELECT security_questions,security_answer FROM users WHERE id='".$_SESSION['login']."'"));


 $balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$u_id."'"));
 
 
/*tamil */


if(isset($_POST['forget_fund']))
{77777777777
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
						<form method="post"  id="data">
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
      <table >
      <tr>
      <td>Phone Number :</td>
      <td> <?php echo $m_balance['mobile_number'] ?></td>      
      </tr>
      <tr>
      <td>payment :</td>
      <td>MYR</td>      
      </tr>
      <tr>
      <td>Amount :</td>
      <td><?php echo $total; ?>
         <input type="hidden" name="m_id" class="form-control" value="<?php echo $m_id; ?>">
         <input type="hidden" name="amount" class="form-control" value="<?php echo $total; ?>">
         <input type="hidden" name="o_id" class="form-control" value="<?php echo $o_id; ?>">
         <input type="hidden" name="wallet" class="form-control" value="MYR">

      </td>      
      </tr>
      
      </table>
      
      
           
         <br>
         <input type="password" name="verify_code" class="form-control" placeholder="Enter Fund Password Here">
         <br><br>
          <div name="forgot" id="forgot_pass" value="Forgotten Password">Forgotten Password</div>
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
    
<!-- new payment ------>



<script>
	$("form#data").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);


    $.ajax({
        url: 'payment.php',
        type: 'POST',
        data: formData,
        success: function (data) {
              //~ alert(data);
           window.location = "<?php echo $site_url; ?>/orderlist.php";
        },
        cache: false,
        contentType: false,
        processData: false
    });
});






	</script>    
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
