<?php
include("config.php");

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

if(!isset($_SESSION['login']) || empty($_SESSION['login']))
{
	header("location:logout.php");
}else{
	if(!checkSession()){
		header("location:logout.php");
	}
}


//if($st_phone)

function getURL(){
	global $site_url;
	$a = explode("_", $_COOKIE['session_id']);
	$conn = $GLOBALS['conn'];
	$id = $a[0];
	$ref_token = mysqli_fetch_assoc(mysqli_query($conn, "SELECT token FROM users WHERE id = '$id'"));
	$url = $site_url . "/login.php?tk=" . $ref_token['token'];
	return $url;
}

	// ------------
	// Debug purposes

// var_dump($_COOKIE);
// var_dump($_SESSION);

	// ------------
$token = getURL(); // This is the URL that the user has to save to access the account, everytime the user logs off the account the token is removed, but if the user did not log off and the session expired when he access with this URL it will automatically create new session for the user for ther period of one month. (You can change it on the cookie time)

// echo "<h2>Save " . "<a href=\"" . $token . "\">this URL </a>" . "as shortcut to acces directly to your account.</h2>";
// $loginidset=$_SESSION['login'];
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($profile_data);
// die;
if($profile_data['user_roles']==5)
{
	$loginidset=$profile_data['parentid'];
}
else
{

	$loginidset=$_SESSION['login'];

}
$balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cash_system,balance_usd,balance_inr,balance_myr FROM users WHERE id='".$_SESSION['login']."'"));
$parent_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$loginidset'"));
// var_dump($_SESSION['login']);
 $cash_check=$parent_data['cash_system'];

$cash_match="n";
  $cash_allow=$_SESSION['cash_allow'];

 $cash_id=$_SESSION['cash_id'];

if($cash_allow)
{
	if($cash_check=="on")
	{
		
		if($cash_id)
		{
			$cash_match="y";
		}
	}
}
// echo $cash_match;
// die;
$_SESSION['cash_allow']='';
?>

<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	<style>
	/*.sidebar-toggle .ripple{     padding: 0 100px; }*/
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

	.wallet_h{
	        font-size: 30px;
    color: #213669;

	}
	</style>
	<?php
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
if($_SESSION['login'])
{
	$user_id=$_SESSION['login'];
	$name=$profile_data['name'];
	$email=$profile_data['email'];
	$mobile_number=$profile_data['mobile_number'];
    file_put_contents("./sessioned-user.txt", $user_id);
}

?>

	<!-- Manifest -->
	<link rel="manifest" href="manifest.json">
</head>

<body class="header-light sidebar-dark sidebar-expand pace-done">

    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        <?php include("includes1/navbar.php"); ?>
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <?php include("includes1/sidebar.php"); ?>
            <!-- /.site-sidebar -->


            <main class="main-wrapper clearfix" style="min-height: 522px;">
                <div class="container-fluid" id="main-content" style="padding-top:25px">
					<h2 class="text-center wallet_h"><?php echo $language['wallet_balance'];?></h2>
					
					<div class="row">
						<div class="col-md-4 well text-center"><h3 style="color:#51d2b7;">MYR</h3> <h4><?php echo $balance['balance_myr']; ?></h4></div>
						<div class="col-md-4 well text-center"><h3 style="color:#51d2b7;">Community Fund(CF)</h3> <h4><?php echo $balance['balance_usd']; ?></h4></div>
						<div class="col-md-4 well text-center"><h3 style="color:#51d2b7;">Koo Coin</h3> <h4><?php echo $balance['balance_inr']; ?></h4></div>
					</div>
					<h2 class="wallet_h text-center">Notifications</h2>
					<input type='hidden' id='cash_id' name='cash_id' value='<?php echo $cash_id; ?>'/>
					<div class="row" style="width:80%;">
						<table class="table table-striped" style="width:80%;">
							<tr>
								<th>Type</th>
								<th>Notification</th>
								<th>Arrived on</th>
							</tr>
							<?php
							$notifications = mysqli_query($conn, "SELECT * FROM notifications WHERE user_id='".$_SESSION['login']."' AND readStatus='0' ORDER BY id DESC LIMIT 10");
							while($notification = mysqli_fetch_assoc($notifications))
							{
							?>
							<tr>
								<td><?php echo $notification['type']; ?></td>
								<td><?php echo $notification['notification']; ?></td>
								<td><?php echo date("d-m-Y H:i A",$notification['created_on']); ?></td>
							</tr>
							<?php
							}

							mysqli_query($conn, "UPDATE notifications SET readStatus='1' WHERE user_id='".$_SESSION['login']."'");
							?>
						</table>
						<?php
						if(mysqli_num_rows($notifications) == 0)
						{
						    echo "<div style='text-align:center;    color: red;
    font-size: 17px;'>No More New Notifications</div>";
						}
						?>
					</div>
				</div>
				<button class="btn btn-primary" onclick='transfer("<?php echo $_SESSION['login'];?>")'>Transfer</button>
				<a href="transaction_history.php" class="btn btn-primary">Transaction history</a>
				<div id="fund_wallet_model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<p>Transfer <span id="total_wallet_amount"></span></p>
				<button type="button" class="close"data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="credentials-container">
					<h5>Enter your password</h5>
					<div>
						<div class="input-group mb-2" style="margin-bottom:5px !important;">
							<input type="password" autocomplete="tel" id="fund_pass" class="fund_pass form-control" style="min-width:250px;" placeholder="" name="fund_pass" required="" />
							<input type="submit" id="confirm_fund" class="btn btn-primary" value="Confirm"/>
						</div>
						<div class="input-group mb-2" style="margin-bottom:5px !important;">
							<span class="error-block-fund-pass" for="fund_pass" style="display: none; color: red">Your password is incorrect.</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
$old_phone = 'SELECT users.mobile_number FROM users inner join transfer on transfer.receiver_id = users.id where transfer.user_id = '.$_SESSION["login"];;
?>
<div id="fund_wallet_input_modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<p>Transfer Information</p>
				<!-- <button type="button" class="close"data-dismiss="modal">&times;</button> -->
			</div>
			<div class="modal-body" style="text-align: left;">
				<div class="credentials-container">
					<div>
						<form action="dashboard.php" method="post" id="form-transfer">
							<input type="hidden" name="sender_id" id="sender_id" value="<?php echo $_SESSION['login']; ?>" />
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">Transfer To</div>
								</div>
								<input type="text" autocomplete="tel" id="transfer_to" class="transfer_to form-control" style="min-width:250px;" placeholder="mobile phone number" name="transfer_to" required="" />
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-mobile" style="display: none;color: red">This is invalid mobile number</span>
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">Amount</div>
								</div>
								<input type="text" autocomplete="tel" id="transfer_amount" class="transfer_amount form-control" style="min-width:250px;" placeholder="amount of transfer" name="transfer_amount" required="" />
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="error-block-for-amount" style="display: none;color: red">Please type transfer amount</span>
							</div>
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">Wallet</div>
								</div>
								<!-- <input type="text" id="transfer_wallet_type" class="transfer_wallet_type form-control" style="min-width:250px;" placeholder="Wallet Type" name="transfer_wallet_type" required="" /> -->
								<select id="transfer_wallet_type" class="transfer_wallet_type form-control" style="min-width: 250px;" name="transfer_wallet_type" required="">
									<option value="">Select Wallet Type</option>
									<option value="MYR">MYR</option>
									<option value="CF">CF</option>
									<option value="INR">KOO Coin</option>
								</select>
							</div>
							<div class="input-group mb-2" style="margin-bottom:20px !important;">
								<span class="error-block-for-wallet-type" style="display: none;color: red">Please select this area</span>
							</div>

							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<span class="current-balance" style="display: none;color: #595d70;display: none;">Current Balance:<b></b></span>
							</div>

							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<input type="button" id="confirm_transfer" class="btn btn-primary" value="Confirm" style="width: 40%;" />
								<input type="button" id="cancel_transfer" class="btn btn-primary" value="Cancel" style="width: 40%; margin-left:20%;">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->
	<div id="ShiftModel" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
									   
											<div class="modal-header">
												<button type="button" class="close" id='sectionclose' data-dismiss="modal">&times;</button>
												   Set Opening Balance to Start
											</div>
											 <div class="modal-body" style="padding-bottom:0px;">
											   <div class=" col-md-6 form-group">
													<label>Opening Balance :</label>
													<input type="Number" class="form-control" id='opening_balance' name="opening_balance" value="" placeholder="Opening Balance" required>  
												 <input type="hidden" name="merchant_id" id="merchant_id" value="<?php echo $loginidset;?>">
												 </br>
												 <button type="submit" class="btn btn-primary start_show">Start</button>
												
												</div>
												
											 </div>
										
										
									
					    		</div>

				  			</div>
	</div>

    <!-- /.content-wrapper -->
	<?php include("includes1/footer.php"); ?>
</body>
	<!-- <script>
		
		// It has been commented because it does not exist such file service-worker.js and it throws an error on console

	  if ('serviceWorker' in navigator) {
	    navigator.serviceWorker.register('/service-worker.js')
	      .then(function(reg){
	        console.log("Service Worker loaded correctly");
	      }).catch(function(err) {
	        console.log("Service Worker error: ", err)
	      });
	  }
	</script> -->
<script>
$(document).ready(function() {
	    var cash_match='<?php echo $cash_match;?>';
		var cash_id=$('#cash_id').val();
		// alert(cash_id);
		if(cash_match=="y")
		$('#ShiftModel').modal('show');
		$('.start_show').click(function() {
		var opening_balance=$('#opening_balance').val();
		
		var user_id=$('#merchant_id').val();
		
		if(opening_balance)
		{
			var data = {user_id:user_id,opening_balance:opening_balance,method:"startcash",cash_id:cash_id};
			$.ajax({
				  
				  url :'functions.php',
				  type:'POST',
				  dataType : 'json',
				  data:data,
				  success:function(response){
					  var data = JSON.parse(JSON.stringify(response));
					  if(data.status==true)
					  {
						location.reload();  
					  }
					  else
					  {
						  alert('Failed to start cash system');
					  }
					 
					}		  
			  });
		}
		else
		{
			alert('Opening Balance is Required To start');
		}
	});
});
</script>
<script>
	function transfer(user_id) {
		$('#fund_user_id').val(user_id);
		$('#fund_wallet_model').modal('show');
	}
	$('#confirm_fund').click(function () {
		fund_engine();
	});
	$('#fund_pass').keyup(function (){
		// fund_engine();
	});
	$('#cancel_transfer').click(function (){
		$('#fund_wallet_input_modal').modal('hide');
	});
	function fund_engine(){
		if ($('#fund_pass').val() == '<?php echo $profile_data["fund_password"];?>') {
			$('.error-block-fund-pass').hide();
			// alert('success');
			$('#fund_wallet_model').modal('hide');
			$('#fund_wallet_input_modal').modal('show');
			// $('#fund_pass').val('');

		}else {
			$('.error-block-fund-pass').show();
		}
	}
	$('#confirm_transfer').click(function(){
		$.post('transfer_module.php', {mobile_number:$('#transfer_to').val(), sender_id: "<?php echo $_SESSION['login'];?>"}, function (data){
			if (data != -1) {
				$('.error-block-for-mobile').hide();
				var balance_data = JSON.parse(data);

				var postdata = {};
				postdata.created = new Date();
				postdata.created = postdata.created.getTime();
				postdata.sender_name = '<?php echo $profile_data["name"] ?>';
				postdata.sender_id = $('#sender_id').val();
				postdata.receiver_id = balance_data['id'];
				postdata.amount = $('#transfer_amount').val();
				postdata.wallet_type = $('#transfer_wallet_type').val();
				if (postdata.sender_id == postdata.receiver_id) {
					$('.error-block-for-mobile').html('This phone number is not valid');
					$('.error-block-for-mobile').show();
				}
				else {
					$('.error-block-for-mobile').hide();
					if (postdata.amount == '') {
						$('.error-block-for-amount').show();
					}else {
						$('.error-block-for-amount').hide();
						if (postdata.wallet_type == '') {
							$('.error-block-for-wallet-type').show();
						} else {
							$('span.current-balance>b').html(parseFloat(balance_data[postdata.wallet_type]));
							$('span.current-balance').show();

							$('.error-block-for-wallet-type').hide();
							if (parseFloat(postdata.amount) > parseFloat(balance_data[postdata.wallet_type])) {
								$('span.error-block-for-amount').html('Your balance is not enough to transfer');
								$('span.error-block-for-amount').show();
							}
							else {
								$('span.error-block-for-amount').html('Please type amount to transfer');
								$('span.error-block-for-amount').hide();
								// alert('success');
							
								$.post('transfer_module.php', postdata, function(result){
									// location.href = 'dashboard.php';
									$('form#form-transfer').submit();
								});
							}
						}
					}
				}
			}
			else {
				$('.error-block-for-mobile').show();
			}
		});
	});
</script>
<script>
	$('#private').click(function (){
		$.post('private.php', {'sender_id':"<?php echo $_SESSION['login'];?>"}, function(data) {
			console.log(data);
		});
	});
</script>
</html>
