<?php 

include("config.php");



if(!isset($_SESSION['login']))

{

	header("location:login.php");

}





$merchant_id=$_SESSION['login'];

// print_R($subscription_data);   

// die;            



	// $sql = "SELECT user_id,name,mobile_number,user_membership_plan.paid_date FROM user_membership_plan INNER JOIN users ON user_membership_plan.user_id = users.id WHERE user_membership_plan.plan_active='y' and user_membership_plan.plan_id='".$_GET['plan_id']."'";

	      // $sql="select users.name,users.mobile_number,membership_plan.plan_name,user_membership_plan.* from user_membership_plan inner join membership_plan on membership_plan.id=user_membership_plan.plan_id 

// right join users on users.id=user_membership_plan.user_id

// where user_membership_plan.merchant_id='$merchant_id' and user_membership_plan.plan_active='y'";
  $sql="select user_membership_plan.*,membership_plan.plan_name  from user_membership_plan inner join membership_plan on membership_plan.id=user_membership_plan.plan_id  where user_membership_plan.merchant_id='$merchant_id' and user_membership_plan.plan_active='y' and user_membership_plan.merchant_id='5326'";


	$result = $conn->query($sql);

	if ($result->num_rows > 0) {

	    // output data of each row

	    while($row = $result->fetch_assoc()) {

	        $list_users[] = $row;

	    }

	}
	// print_R($list_users);
	// die;

	// var_dump(date('Y', strtotime($list_users[0]['paid_date'])));

	// exit();

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

						<h3><?php echo $language['list_user']; ?></h3>

						

						<?php if (empty($list_users)): ?>

							<h4>No Data Found!</h4>

						<?php endif ?>

						<?php if (!empty($list_users)): ?>

								

							

						<table class="table table-striped">

							<tr>

								

								<th>User Name</th>

								<th>Mobile</th>

								<th>Plan Name</th>

								<th>Trial Purchase</th>
								<th>Date of Purchase</th>

								<th>Plan Upgraded</th>

								
								<th>Total Amount After Trial </th>
								<th>Local Order Point After Trial </th>
								<th>Final Amount After Trial </th>

							</tr>   					

							<?php
							foreach ($list_users as $key):
								// print_R($key);
								// die;
							$user_id=$key['user_id'];
							 $mobile_number=$key['user_mobile'];
							
							// echo "SELECT sum(local_coin) as local_coin FROM local_coin_sync WHERE user_id='$user_id' and merchant_id='$merchant_id'";
							
							$defalut_plan="select count(plan.id) as total_count,u.created from membership_plan as plan inner join user_membership_plan as u on u.plan_id=plan.id where plan.user_id='$merchant_id' and plan.default_plan='y'
							and u.user_id='$user_id'";
							$defalutarray = mysqli_fetch_assoc(mysqli_query($conn,$defalut_plan));
							$defalutplan=$defalutarray['total_count'];
							if($defalutplan>0)
							{
							   $created_date=$defalutarray['created'];
							
							// echo "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_id='$user_id' and merchant_id='$merchant_id' annd created_on>='$created_date'";
							// die;
							// $local_coin=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(local_coin) as local_coin FROM local_coin_sync WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and order_date>='$created_date'"))['local_coin'];
							// echo "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and created_on>='$created_date'";
							// die;
							// echo "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and created_on>='$created_date' and order_place='live'";
							// die;
								$local_coin=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and created_on>='$created_date' and order_place='local'"))['total_amount'];
								$total_amount=mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(total_cart_amount) as total_amount FROM order_list WHERE user_mobile='$mobile_number' and merchant_id='$merchant_id' and created_on>='$created_date' and order_place='live'"))['total_amount'];

							?>

								<tr>

									<td><?php echo $key['name']; ?></td>

									<td><?php echo $key['user_mobile']; ?></td>

									<td><?php echo $key['plan_name']; ?></td>  

									<td><?php echo date('F d, Y h:i:A', strtotime($created_date)); ?></td>	
									<td><?php echo date('F d, Y h:i:A', strtotime($key['paid_date'])); ?></td>	

									<td><?php if($key['is_upgrade']=="y"){echo "YES";} else { echo "NO";} ?></td>  

									<td><a class="mr-4" href="memberorder.php?user_id=<?php echo $key['user_mobile'];?>&promo_id=<?php echo $key['plan_id'] ?>">

									<i class="fa fa-list" aria-hidden="true"></i>

									<?php echo number_format($total_amount,2); ?>

									</a></td>
									<td><?php echo number_format($local_coin,2); ?></td>
									<td><p style="font-weight:bold;"><?php echo number_format($local_coin+$total_amount,2); ?></p></td>

								</tr>  

							<?php } endforeach ?>								

						</table>

						<?php endif ?>	

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

		var transfer_to=$('#transfer_to').val();

		// alert(transfer_to);

		if(transfer_to=='')

		{

			$('.error-block-for-mobile').html('Mobile No is Required');

			$('.error-block-for-mobile').show();

			return false;

		}

		else

		{

			// $('.error-block-for-mobile').html('');

			$('.error-block-for-mobile').hide();

		}

		$.post('transfer_module.php', {mobile_number:$('#transfer_to').val(), sender_id: "<?php echo $_SESSION['login'];?>"}, function (data){

			if (data != -1) { 

				$('.error-block-for-mobile').hide();

				var balance_data = JSON.parse(data);

				// alert(balance_data);

				var postdata = {};

				postdata.created = new Date();

				postdata.created = postdata.created.getTime();

				postdata.sender_name = '<?php echo $profile_data["name"] ?>';

				postdata.sender_id ="<?php echo $_SESSION['login'];?>",

				postdata.receiver_id = balance_data['id'];

				postdata.amount = $('#transfer_amount').val();

				postdata.wallet_type =balance_data['special_coin_name'];

			var wallet_bal =balance_data['CF'];

				postdata.special_wallet ="y";       

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

							if (parseFloat(postdata.amount) > parseFloat(wallet_bal)) {

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

