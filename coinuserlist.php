<?php 
session_start();
include("config.php");

// if(!isset($_SESSION['login']))
// {
	// header("location:login.php");
// }

if(isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = 1;
}

$limit = 50;
$date = date('Y-m-d H:i:s');
$end_dt = $date;
$filter="";
$m_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
// print_R($m_data);
// die;
$query="select scoin.*,users.name,users.mobile_number from special_coin_wallet as scoin inner join users on users.id=scoin.user_id where scoin.merchant_id='".$_SESSION['login']."'";
$qdata=mysqli_query($conn,$query);
?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
    <?php include("includes1/head.php"); ?>
	 <link href="js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
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
	
	.pagination {
		display: inline-block;
		padding-left: 0;
		margin: 20px 0;
		border-radius: 4px;
	}
	.pagination>li {
		display: inline;
	}
	.pagination>li:first-child>a, .pagination>li:first-child>span {
		margin-left: 0;
		border-top-left-radius: 4px;
		border-bottom-left-radius: 4px;
	}
	.pagination>li:last-child>a, .pagination>li:last-child>span {
		border-top-right-radius: 4px;
		border-bottom-right-radius: 4px;
	}
	.pagination>li>a, .pagination>li>span {
		position: relative;
		float: left;
		padding: 6px 12px;
		margin-left: -1px;
		line-height: 1.42857143;
		color: #337ab7;
		text-decoration: none;
		background-color: #fff;
		border: 1px solid #ddd;
	}
	.pagination a {
		text-decoration: none !important;
	}
	.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
		z-index: 3;
		color: #fff;
		cursor: default;
		background-color: #337ab7;
		border-color: #337ab7;
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
					
					<div class="well" style="width:100%">
						<h3><?php echo $m_data['special_coin_name']; ?> User List</h3>
						<button  class="btn btn-primary" onclick='transfer("<?php echo $_SESSION['login'];?>","")'>Transfer</button>
									
						<table class="table table-striped">
							<tr>
								<th>User ID</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Cur. Balance</th>
								<th>Last Trascation</th>
								<th>Total Order Amount</th>
								<th>Action</th>
								
							</tr>
							<?php
					
							while($row = mysqli_fetch_assoc($qdata))
							{
								$l_user_id=$row['user_id'];
								$merchant_id=$row['merchant_id'];
								$last=mysqli_fetch_assoc(mysqli_query($conn, "SELECT created_on FROM order_list WHERE user_id='$l_user_id' and merchant_id='$merchant_id' order by id desc limit 0,1"));
								$total_amount=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_id='$l_user_id' and merchant_id='$merchant_id'"))['total_amount'];
								if($last)
								{
									// print_R($last);
									$last_tras=$last['created_on'];
								}
								else
								{
									$last="--";
								}
								?>
								<tr>
									<td><?php echo $row['user_id']; ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['mobile_number']; ?></td>
									<td><?php echo number_format($row['coin_balance'],2); ?></td>
									<td><?php if($last){echo $last_tras;}else { echo $last;} ?></td>
									<td><?php echo number_format($total_amount,2);?></td>
									<td>
									<button class="btn btn-primary" onclick='transfer("<?php echo $_SESSION['login'];?>","<?php echo $row['mobile_number'];?>")'>Transfer</button>
									
									</td>
									
								</tr>
								<?php
								// die;
							}
							?>
						</table>
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
						<form action="" method="post" id="form-transfer">
							<input type="hidden" name="sender_id" id="sender_id" value="<?php echo $_SESSION['login']; ?>" />
								<input type="hidden" name="reciver_mobile_number" id="reciver_mobile_number"/>
							
							<div class="input-group mb-2" style="margin-bottom:5px !important;">
								<div class="input-group-prepend">
									<div class="input-group-text" style="background-color:#51D2B7;border-radius: 5px 0 0 5px;height: 100%;padding: 0 10px;display: grid;align-content: center;">Transfer To</div>
								</div>
								<input type="text" autocomplete="tel" maxlength="12" required id="transfer_to" class="transfer_to form-control" style="min-width:250px;" placeholder="mobile phone number" name="transfer_to" required="" />
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
									
									<option value="<?php echo $m_data['special_coin_name']; ?>" selected><?php echo $m_data['special_coin_name']; ?></option>
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
					</div>
					<div style="margin:0px auto;">
						<ul class="pagination">
						<?php
						  for($i = 1; $i <= $total_page_num; $i++)
						  {
							  if($i == $page)
							  {
								  $active = "class='active'";
							  }
							  else
							  {
								  $active = "";
							  }
							  echo "<li $active><a href='?page=$i'>$i</a></li>";
						  }
						?>
						</ul>
					</div>
				</div>
			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/footer.php"); ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<!-- <script src="js/components-date-time-pickers.min.js" type="text/javascript"></script> -->
</body>

</html>
<script>
	function transfer(user_id,mobile_number) {
		$('#fund_user_id').val(user_id);
		$('#reciver_mobile_number').val(mobile_number);
		$('#transfer_to').val(mobile_number);
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
		var number=$('#transfer_to').val();
		var transfer_wallet_type=$('#transfer_wallet_type').val();
		var transfer_amount=$('#transfer_amount').val();
		// alert(number.length);
		if(number.length >= 9 && number.length <= 12){
			$('.error-block-for-mobile').hide();
			if(transfer_amount)
			{  
				$('span.error-block-for-amount').hide();
				if(transfer_wallet_type)
				{
					$('.error-block-for-mobile').hide();
					$.post('transfer_module.php', {transfer_amount:transfer_amount,wallet_type:transfer_wallet_type,mobile_number:$('#transfer_to').val(), sender_id: "<?php echo $_SESSION['login'];?>"}, function (data){
					 var objresult = JSON.parse(data);
					 $('.error-block-for-wallet').hide();
					if (objresult.status==true) { 
						
						var data=objresult.data;
						// var balance_data = JSON.parse(data);
						var balance_data = data;

						var postdata = {};
						postdata.created = new Date();
						postdata.created = postdata.created.getTime();
						postdata.sender_name = '<?php echo $profile_data["name"] ?>';
						postdata.sender_mobile = '<?php echo $profile_data["mobile_number"] ?>';
						postdata.sender_id = $('#sender_id').val();
						postdata.receiver_id = balance_data['id'];
						postdata.amount = $('#transfer_amount').val();
						var wallet_bal =balance_data['CF'];  
						postdata.special_wallet ="y";    
						postdata.merchant_send ="y";    
						if (postdata.sender_id == postdata.receiver_id) {
							$('.error-block-for-mobile').html('Cant able to send amount to self no');
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
											// return false;
											$('form#form-transfer').submit();
										});
									}
								}
							}
						}
						
					}
					else {
						$('.error-block-for-mobile').html(objresult.msg);
						$('.error-block-for-mobile').show();
					}
				});
				}
				else
				{
					$('.error-block-for-wallet-type').show();
				}
			}
			else
			{
				$('span.error-block-for-amount').html('Enter Transfer Amount');
				$('span.error-block-for-amount').show();
			}
			
			
		}
		else
		{
			$('.error-block-for-mobile').html('Invalid Mobile Number');
			$('.error-block-for-mobile').show();
		}
	});
</script>
<script>
$(document).ready(function() {
 //$('.display').DataTable();
 $(".form_datetime").datetimepicker({
    autoclose: true,
    format: "yyyy-mm-dd  hh:ii:ss",
    fontAwesome: true
});
});
</script>

<style>
.dataTables_wrapper {
    width: 100%;
}
</style>
