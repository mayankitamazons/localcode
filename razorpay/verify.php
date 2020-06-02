<?php
include("../config.php");

require('config.php');
require('vendor/autoload.php');

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;
$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($RAZORPAY_CRED['keyId'], $RAZORPAY_CRED['keySecret']);

    try
    {
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

unset($_SESSION['razorpay_order_id']);

if ($success === true)
{	
	mysqli_query($conn, "INSERT INTO recharges SET user_id='".$_SESSION['login']."', currency='INR', amount='".$_POST['form-amount']."', paypal_txn_id='".$_POST['razorpay_payment_id']."', created_on='".time()."'");
	
	$current_balance = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance_inr FROM users WHERE id='".$_SESSION['login']."'"))['balance_inr'];
	$new_balance = $current_balance + $_POST['form-amount'];
	mysqli_query($conn, "UPDATE users SET balance_inr='$new_balance' WHERE id='".$_SESSION['login']."'");

	header("location:../wallet.php?message=".urlencode("Recharge Successful of ".$_POST['form-amount']." INR"));
}
else
{
	header("location:../wallet.php?message=".urlencode("AN ERROR OCCURED."));
}
