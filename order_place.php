<?php
include("config.php");
/* jupiter 24.02.19*/
$merchant_id = $_SESSION['merchant_id'];
$sql = "SELECT * FROM users where id='$merchant_id'";
// print_R($_POST);
// die;
$result = mysqli_query($conn, $sql);
$merchant = mysqli_fetch_assoc($result);

$stock_inventory = $merchant['stock_inventory'];
$credit_check = $merchant['credit_check'];
$wallet_check = $merchant['wallet_check'];
$boost_check = $merchant['boost_check'];
$grab_check = $merchant['grab_check'];
$wechat_check = $merchant['wechat_check'];
$touch_check = $merchant['touch_check'];
$fpx_check = $merchant['fpx_check'];
$cash_image = "available";
$credit_image = "available";
$wallet_image = "available";
$boost_image = "available";
$grab_image = "available";
$wechat_image = "available";
$touch_image = "available";
$fpx_image = "available";
if($cash_check == "0")
	$cash_image = "unavailable";
if($credit_check == "0")
	$credit_image = "unavailable";
if($wallet_check == "0")
	$wallet_image = "unavailable";
if($boost_check == "0")
	$boost_image = "unavailable";
if($grab_check == "0")
	$grab_image = "unavailable";
if($wechat_check == "0")
	$wechat_image = "unavailable";
if($touch_check == "0")
	$touch_image = "unavailable";
if($fpx_check == "0")
	$fpx_image = "unavailable";

if(isset($_POST['method'])){
	$user = $_POST['user'];
	$payment = $_POST['payment'];
	$sql = "SELECT * FROM payments WHERE type='$payment' and user = '$user'";
	$result = mysqli_query($conn, $sql);
	$payment = mysqli_fetch_assoc($result);
	$res = array(
		"name" => $payment['name'],
		"mobile" => $payment['mobile'],
		"remark" => $payment['remark'],
		"qr_code" => $payment['qr_code']
	);
	echo json_encode($res);
	exit();
}

$profile_data = isset($_SESSION['login']) ? mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'")) : '';
// print_R($profile_data);
// die;
$session_id = session_id();
if(isset($_POST['submit']))
{
	
    if($profile_data)
	{
		$name=$profile_data['name'];
		$email=$profile_data['email'];
		$user_mobile=$profile_data['mobile_number'];
	}
	else
	{
		$name='';
		$user_mobile='';
	}
 	$m_id=$_POST['m_id'];
	$sql = "SELECT MAX(invoice_seq) invoice_seq
            FROM order_list
            WHERE merchant_id = '$m_id'";
	$invoice_seq = mysqli_fetch_assoc(mysqli_query($conn, $sql))['invoice_seq'];
	
	// $inv=explode('_',$invoice_no);
	// $invoice_no=$inv[0];
	if($invoice_seq == NULL) $invoice_seq = 1;
	else $invoice_seq += 1;
    $invoice_no=$invoice_seq."L";
	$invoice_seq=$invoice_seq;

    if( !isset( $m_id ) || $m_id == '' || $m_id == 0 ) {
        echo('Something went wrong.');
    }
	$stl_key = isset($_POST['stl_key']) ? $_POST['stl_key'] : '';
	$u_id = $_SESSION['login'];
	$date = date('Y-m-d H:i:s');
	$location =$_POST['location'];
	$table_type =$_POST['table_type'];
	 $section_type =$_POST['section_type'];
	$p_code = implode(',', $_POST['p_code']);
	$pro_id = implode(',', $_POST['p_id']);
	$varient_type=$_POST['varient_type'];
	$qty_list = implode(',', $_POST['qty']);
	$prices = $_POST['p_price'];
	$p_extra = explode('|', $_POST['price_extra']);
	$p_price = [];
	foreach ($prices as $i => $item) {
		if(sizeof(explode(",",$p_extra[$i])) > 1){
			$totalExtra = explode(",",$p_extra[$i]);
			$p_extra_ind = 0;
			foreach ($totalExtra as $xtr) {
				$p_extra_ind += $xtr;
			}
		}else{
			$p_extra_ind = $p_extra[$i];
		}
		array_push($p_price, $p_extra_ind + $item);
	}
	$p_price = implode(",", $p_price);
	// var_dump($p_extra);
	// echo "<br>";
	// echo($p_price);

	$option = $_POST['options'];
	$product_name =isset($_POST['product_name']) ? $_POST['product_name'] : '';
	$product_code =isset($_POST['product_code']) ? $_POST['product_code'] : '';
	if($varient_type)
	{ 
		$vcount=0;
		// print_R($varient_type);
		
		foreach($varient_type as $v)
		{
			// print_R($v);
			if($vcount==0)
			{
				$v_str=$v;
			}
			else
			{
			  $v_str=$v_str."|".$v;
			}
			$vcount++;
		}
		// echo $v_str;
		// die;
		// $varient_type=$v_str;
	}
	$flag = 0;
	 if(!empty($_SESSION['login'])){
		
	     $merchant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT account_type, k_lock FROM users WHERE id='".$m_id."'"));
	     $merchant_kType = $merchant['account_type'];
	     $k_lock = $merchant['k_lock'];
	     $user_kType = mysqli_fetch_assoc(mysqli_query($conn, "SELECT account_type FROM users WHERE id='".$u_id."'"))['account_type'];
		 $discount ="";
		 if(($merchant_kType != "") && ($user_kType != "") && ($merchant_kType != $user_kType) && (strlen($merchant_kType) != strlen($user_kType))){
		    $discount = "2%";
		 } else if(($merchant_kType != "") && ($user_kType != "") && ($merchant_kType == $user_kType) && (strlen($merchant_kType) == 2)){
		     $discount = "2%";
		 } else if(($merchant_kType != "") && ($user_kType != "") && ($merchant_kType == $user_kType) && (strlen($merchant_kType) == 7)){
		     $discount = "4%";
		 }
		// $session_id = session_id();
		
        if(($k_lock == '1') && ($discount != '')){
			
			$Delete = mysqli_query($conn, "DELETE FROM `order_list_temp` where session_id='$session_id' ") ;
			
             $test_method = mysqli_query($conn, "INSERT INTO order_list_temp SET user_name='$name',user_mobile='$user_mobile',varient_type='$v_str',product_id='$pro_id',user_id='$u_id',merchant_id='$m_id',session_id='$session_id',quantity='$qty_list',product_code='$p_code',amount='$p_price',remark='$option',location='".$location."',table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',invoice_seq='$invoice_seq'");
	       
			$order_id = mysqli_insert_id($conn);
        
            mysqli_query($conn, "INSERT INTO k1k2_history SET user_id='$u_id', merchant_id='$m_id', k_user='$user_kType', k_merchant='$merchant_kType', order_id='$order_id', discount='$discount'");
        
            $flag = 1;
        }
        if($k_lock == '0'){
			$Delete = mysqli_query($conn, "DELETE FROM `order_list_temp` where session_id='$session_id' ") ;
			//echo "INSERT INTO order_list_temp SET user_name='$name',user_mobile='$user_mobile',varient_type='$v_str',product_id='$pro_id',user_id='$u_id',merchant_id='$m_id',session_id='$session_id',quantity='$qty_list',product_code='$p_code',amount='$p_price',remark='$option',location='".$location."',table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',invoice_seq='$invoice_seq'";
			
            $test_method = mysqli_query($conn, "INSERT INTO order_list_temp SET user_name='$name',user_mobile='$user_mobile',varient_type='$v_str',product_id='$pro_id',user_id='$u_id',merchant_id='$m_id',session_id='$session_id',quantity='$qty_list',product_code='$p_code',amount='$p_price',remark='$option',location='".$location."',table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',invoice_seq='$invoice_seq'");
	      
		  $order_id = mysqli_insert_id($conn);
       
            if($discount != ""){
	            mysqli_query($conn, "INSERT INTO k1k2_history SET user_id='$u_id', merchant_id='$m_id', k_user='$user_kType', k_merchant='$merchant_kType', order_id='$order_id', discount='$discount'");
	        }
	        $flag = 1;
        }
	 } else {
	     $flag = 1;
	 	if($stl_key == $_SESSION['stl_key']) {
			$Delete = mysqli_query($conn, "DELETE FROM `order_list_temp` where session_id='$session_id' ") ;
    		$test_method = mysqli_query($conn, "INSERT INTO order_list_temp SET user_name='$name',user_mobile='$user_mobile',varient_type='$v_str',roduct_id='$pro_id',user_id='$u_id',session_id='$session_id',merchant_id='$m_id',quantity='$qty_list',product_code='$p_code',amount='$p_price',remark='$option',location='".$location."',table_type='".$table_type."',section_type='$section_type',created_on='$date', invoice_no='$invoice_no',invoice_seq='$invoice_seq'");
    		$_SESSION['stl_key'] = "empty";
        }
     }

	$order_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `order_list` WHERE id = (SELECT MAX(id) FROM `order_list`)"));

// print_R($order_total);
// die;  
	$tttt_qt= $order_total['quantity'] ;
	$tt_amt= $order_total['amount'];

	$quantity = explode(",",$order_total['quantity']);
	$amount = explode(",",$order_total['amount']);
	$c = array_combine($quantity, $amount);
	$total = 0;
	foreach ($c as $key => $val){
	    $total = $total + ($key * $val);
	}
	if($flag == 0){
	    header("location:merchant_find.php");
	}
}

if(isset($_POST['update_cash'])){
    $money=$_POST['money'];
    $upt_tt = mysqli_query($conn,"UPDATE `order_list` SET `wallet`='$money'");
}

?>
<!DOCTYPE html>
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">

<head>
<style>
.text_payment{
    width: 50%!important;
    text-align: center;
    margin: 0 auto;
}
.pay_wallet{
    font-size: 14px;
    text-align: center;
}
.order_whole {
    text-align: center;
    border: 1px solid;
    width: 50%;
    margin: 0 auto;
    padding: 15px;
}
.wallet_hr{
    width: 510px;
    margin-left: -15px;
    border-top: 1px solid black;
}
/*jupiter 24.02.19*/
	.img60{
		width: 40px;
		height: auto;
	}
	.payment_title{
		margin-top: 0.8rem;
		font-size: 16px;
	}
	.table td{
		border-top: 1px solid black !important;
	}
/**/
 @media (min-width: 360px) and (max-width:650px) {
.order_whole {
    text-align: center;
    border: 1px solid;
    width: 100%;
    margin: 0 12px;
    padding: 14px;
}
.wallet_hr {
    width: 325px;
}
}
 @media (min-width: 700px) and (max-width:800px) {

.wallet_hr {
    width: 335px;
   }
}
 @media (min-width: 650px) and (max-width:700px) {

.wallet_hr {
    width: 307px;    
}
}
 @media (min-width: 430px) and (max-width:400px) {

.wallet_hr {
    width: 360px!important;
}
}

</style>

    <?php include("includes1/head.php"); ?>
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
                <div class="row" id="main-content" style="padding-top:25px">
					<div class="well" style="width:100%">

						<div class="order_whole">
						<h4><?php echo $language["your_order_has_been_sent"];?></h4>
						<h5>
						    <?php if(isset($total)) {echo sprintf($language["following_mode_of_payments"],'');} ?>
							<!--Please pay RM <?php  echo $total; ?> by selecting the following mode of payments-->
						</h5>
            			<form  id="data" method="POST"  enctype="multipart/form-data" action="payment.php">   
							<select class="form-control text_payment required" style="font-weight: bold;" name="wallet">  
        						<option class="text_csah" value='cash_<?php echo $order_total['id'];?>'><?php echo $language["cash"] ;?></option>
        						<option value='MYR'><?php echo $language["wallet"];?></option>
								<?php if($merchant['mobile_number']!="60172669613"){ ?>
        						<option>Credit Card</option>
        						<?php if($boost_check == "1"){?>
    						    	<option title="Boost Pay" value='1'>Boost Pay</option>
    						    <?php }?>
    						    <?php if($grab_check == "1"){?>
    						    	<option value='2' title="Grab Pay">Grab Pay</option>
    						    <?php }?>
    						    <?php if($wechat_check == "1"){?>
    						    	<option value='3' title="WeChat">WeChat</option>
    						    <?php }?>
    						    <?php if($touch_check == "1"){?>
    						    	<option value='4' title="Touch & Go">Touch & Go</option>
    						    <?php }?>
    						    <?php if($fpx_check == "1"){?>
    						    	<option value='5' title="FPX">FPX</option>
    						    <?php } }?>
        					</select>  
                      
						   <input type="hidden" id="id" name="m_id" value="<?php echo $m_id;?>">
						   <input type="hidden" id="amount" name="amount" value="<?php echo $total;?>">
						    <input type="hidden" id="member" name="member" value="<?php echo $member;?>">
					       <input type="hidden" id="o_id" name="o_id" value="<?php echo $order_total['id'];?>">
						    <?php if(isset($_GET['user_id'])){  ?>
						    <input type="hidden" id="guest_id" name="guest_id" value="<?php echo $_GET['user_id'];?>">
						    <input type="hidden" id="guest_order_id" name="guest_order_id" value="<?php echo $_GET['order_id'];?>">
						   <?php } ?>
						   <button class="btn btn-block btn-primary confirm_pay"> Confirm </button>
					
						<!-- jupiter 24.02.19 -->
							<?php if($merchant['mobile_number']!="60172669613"){    ?>
					  <div class="payment_section">
					  	<table class="table" border="1" style="margin-top: 10px; " >
					  		<tbody>
					  			<tr>
					  				<td><h5 class="payment_title">Cash</h5></td>
						  			<td><img src="images/payments/cash.png" class="img60"></td>
						  			<td><img src="images/payments/<?= $cash_image;?>.jpg" class="img60"></a></td>
					  			</tr>
					  			<tr>
					  				<td><h5 class="payment_title">Credit Card</h5></td>
						  			<td><img src="images/payments/credit.jpg" class="img60"></td>
						  			<td><img src="images/payments/<?= $credit_image;?>.jpg" class="img60"></a></td>
					  			</tr>
					  			<tr>
					  				<td><h5 class="payment_title">Wallet</h5></td>
						  			<td><img src="images/payments/wallet.png" class="img60"></td>
						  			<td><img src="images/payments/<?= $wallet_image;?>.jpg" class="img60"></a></td>
					  			</tr>
								<?php if($boost_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Boost Pay</h5></td>
						  			<td><img src="images/payments/boost.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="1" title="Boost Pay"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  				
						  			
						  			</td>
					  			</tr>
									<?php }?>
									<?php if($grab_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Grab Pay</h5></td>
						  			<td><img src="images/payments/grab.jpg" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="2" title="Grab Pay"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  					
						  			</td>
					  			</tr>
								<?php }?>
									<?php if($wechat_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">WeChat</h5></td>
						  			<td><img src="images/payments/wechat.jpg" class="img60"></td>
						  			<td>
						  			
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="3" title="WeChat"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  			
						  			</td>
					  			</tr>
									<?php }?>
									<?php if($touch_check == "1"){?>
					  			<tr>
					  				<td><h5 class="payment_title">Touch & Go</h5></td>
						  			<td><img src="images/payments/touch.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="4"title="Touch & Go"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  				
						  				
						  			</td>
					  			</tr>
								<?php }?>
					  			<?php if($fpx_check == "1"){?>
								<tr>
					  				<td><h5 class="payment_title">FPX</h5></td>
						  			<td><img src="images/payments/fpx.png" class="img60"></td>
						  			<td>
						  				
						  				<a href="#" data-toggle="modal" data-target="#paymentModal" user="<?= $merchant_id;?>" class="payment_btn" payment="5" title="FPX"><img src="images/payments/available.jpg" class="img60">
						  				</a>
						  			
						  			</td>
					  			</tr>
									<?php }?>
					  		</tbody>
					  	</table>
					  </div>  
					  	<?php } ?>
					  	<!-- /.widget-body badge -->
				        <div id="paymentModal" class="modal fade" role="dialog">
				  			<div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
					      			<div class="modal-header">
					        			<button type="button" class="close" data-dismiss="modal">&times;</button>
					        			<h4 class="modal-title payment_header">Modal Header<img src="images/payments/boost.png"></h4>
					      				
					      			</div>
				      				<div class="modal-body" style="text-align: left;">
				      					<input type="hidden" value="" class="payment_type">
					        			<h5 class="">Please pay to <span class="merchant_name">sdf</span></h5>
					        			<h5>Mobile Number +60 <span class="mobile"></span></h5>
					        			<h5>QR Code:</h5>
					        			<img class="qr_code_image">
					        			<h5 class="">Reference: <span class="reference"></span></h5>
										 <div class="form-group" style="width:70%;">
    <label for="pwd">Upload image (proof of payment):</label>
    <input type="file" class="form-control" name="paymentproff" id="paymentproff">
  </div>
					        			<button type="button" class="btn btn-primary confirm_payment_btn"  style="margin-bottom: 10px;">I have paid to the merchant</button>
					        			<button type="button" class="btn btn-default" data-dismiss="modal">I want to pay with another method</button>
					      			</div>
					      			<div class="modal-footer">
					        			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					      			</div>
					      			
					    		</div>

				  			</div>
						</div>
					<!--  -->
					<!--div class="wallet_price">
					<h4 class="pay_wallet">Pay through wallet and stand a chance to win a trip Bali and money more benefit.</h4>

					</div!-->
                          </form>
						</div>

<div>

			</main>
        </div>
        <!-- /.widget-body badge -->
    </div>
    <!-- /.widget-bg -->

    <!-- /.content-wrapper -->
    <?php include("includes1/commonfooter.php"); ?>
</body>
<script>
$(document).ready(function(e){
	$("select.text_payment").change(function(e) {
			e.preventDefault();
		var payment=$(this).val();
		var title = $(this).attr('title');
			var title = $(this).attr('title');
			var user='<?php echo $merchant_id; ?>';
				$(".payment_type").val(payment);
	  var action = $(this).val() == "MYR" ? "wallet_pay.php" : "payment.php";
	  $("#data").attr("action", action);
	   // alert(payment);
	   	$(".payment_type").val(payment);
		if((payment=='1') || (payment=="2") || (payment=="3") || (payment=="4") || (payment=="5"))
		{
			
		     $.ajax({
            url : 'order_place.php',
            type : 'post',
            dataType : 'json',
            data: {payment: payment, user:user, method: "getPayment"},
            success: function(data){
				// alert(payment);
            	if(payment == "1"){
            		$image = "boost.png";
            	} else if(payment == "2"){
            		$image = "grab.jpg";
            	} else if(payment == "3"){
            		$image = "wechat.jpg";
            	} else if(payment == "2"){
            		$image = "touch.png";
            	} else if(payment == "5"){
            		$image = "fpx.png";
            	}
            	$(".payment_header").html(title + "&nbsp <img style='width:90px;' src='images/payments/"+$image+"'>");
            	$(".merchant_name").html(data['name']);
            	$(".mobile").html(data['mobile']);
            	$(".qr_code_image").attr({"src": "uploads/"+data['qr_code']});
            	$(".reference").html(data['remark']);
				$("#paymentModal").modal("show");
            }
			}); 	
		}			

		
		
	});
	})

	$(".payment_btn").click(function(e){
		// alert("sdf");
		e.preventDefault();
		var payment = $(this).attr("payment");
		var user = $(this).attr("user");
		var title = $(this).attr('title');
		$(".payment_type").val(payment);
		$.ajax({
            url : 'order_place.php',
            type : 'post',
            dataType : 'json',
            data: {payment: payment, user:user, method: "getPayment"},
            success: function(data){
            	if(payment == "1"){
            		$image = "boost.png";
            	} else if(payment == "2"){
            		$image = "grab.jpg";
            	} else if(payment == "3"){
            		$image = "wechat.jpg";
            	} else if(payment == "2"){
            		$image = "touch.png";
            	} else if(payment == "5"){
            		$image = "fpx.png";
            	}
            	$(".payment_header").html(title + "&nbsp <img style='width:90px;' src='images/payments/"+$image+"'>");
            	$(".merchant_name").html(data['name']);
            	$(".mobile").html(data['mobile']);
            	$(".qr_code_image").attr({"src": "uploads/"+data['qr_code']});
            	$(".reference").html(data['remark']);
            }
        });
	});

	$(".confirm_payment_btn").click(function(e){
		var payment = $(".payment_type").val();
		if(payment == "1"){
			payment = "Boost Pay";
		}
		 
		$(".text_payment").val(payment);
		$("#paymentModal").modal("hide");
		 document.getElementById("data").submit();
	});
</script>

</html>
