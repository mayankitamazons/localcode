<?php 
include("../config.php");

require('config.php');
require('vendor/autoload.php');

use Razorpay\Api\Api;
	
if(!isset($_SESSION['login']))
{
	header("location:../login.php");
}
?>
<html>
	<head>
		<title>Pay with RazorPay</title>
		<meta name="viewport" content="width=device-width">
		<style rel="stylesheet">
		    .razorpay-payment-button{
		            display: inline-block;
                    font-weight: 400;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: middle;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    user-select: none;
                    border: 1px solid transparent;
                    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                    color: #fff;
                    background-color: #007bff;
                    border-color: #007bff;
                    cursor: pointer;
                    padding: .5rem 1rem;
                    font-size: 1.25rem;
                    line-height: 1.5;
                    border-radius: .3rem;
		    }
		</style>
	</head>
	<body style="text-align:center;">
	<?php
	if(isset($_POST['submit']))
	{
		if(!is_numeric($_POST['amount']) || $_POST['amount'] == 0)
		{
			header("location:../wallet.php?status=cancel");
		}
		
		$receipt_number = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM recharges ORDER BY id DESC LIMIT 1"))['id'] + 1;
		$customer_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name,email FROM users WHERE id='".$_SESSION['login']."'"));
		
		// Customer Details Should Goes Here
		$customer_details = array(
			"name"=>$customer_data['name'],
			"email"=>$customer_data['email'],
			"phone"=>"",
			"purchase_description"=>"Wallet Recharge - INR",
			"receipt_number"=>$receipt_number,
			"order_amount"=>$_POST['amount'] // amount in rupees
		);
		
		// Any extra fields to be submitted with the form but not sent to Razorpay, max 15
		$notes_vars = array("custom_var"=>"<custom value>");
		
		// NOTHING TO CHANGE AFTER THIS COMMENT
		$orderData = [
			'receipt'         => $customer_details['receipt_number'],
			'amount'          => $customer_details['order_amount'] * 100, // amount is paise
			'currency'        => 'INR',
			'payment_capture' => 1
		];
		$api = new Api($RAZORPAY_CRED['keyId'], $RAZORPAY_CRED['keySecret']);
		$razorpayOrder = $api->order->create($orderData);
		$_SESSION['razorpay_order_id'] = $razorpayOrder['id']; 
		$displayAmount = $orderData['amount'];
		?>
		<form action="verify.php" method="POST">
		  <script
			src="https://checkout.razorpay.com/v1/checkout.js"
			data-key="<?php echo $RAZORPAY_CRED['keyId']; ?>"
			data-amount="<?php echo $orderData['amount']; ?>"
			data-currency="INR"
			data-name="<?php echo $MERCHANT_DETAILS['name']; ?>"
			data-image="<?php echo $MERCHANT_DETAILS['logo_url']; ?>"
			data-theme.color="<?php echo $MERCHANT_DETAILS['theme_color']; ?>"
			data-description="<?php echo $customer_details['purchase_description']; ?>"
			data-prefill.name="<?php echo $customer_details['name']; ?>"
			data-prefill.email="<?php echo $customer_details['email']; ?>"
			data-prefill.contact="<?php echo $customer_details['phone']; ?>"
			data-order_id="<?php echo $razorpayOrder['id']; ?>"
			<?php
			foreach($notes_vars as $key => $value)
			{
				echo "data-notes.$key='$value'\n";
			}
			?>
		  >
		  </script>
		  <?php
		  foreach($notes_vars as $key => $value)
		  {
			echo "<input type='hidden' name='$key' value='$value'>\n";
		  }
		  ?>
		  <input type="hidden" name="form-amount" value="<?php echo $orderData['amount']; ?>">
		</form>
	<?php
	}
	else
	{
		echo "<h1 style='text-align:center; color:red;'>Direct Access Not Work</h1>";
		die;
	}
	?>
	</body>
</html>
