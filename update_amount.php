<?php 
include("config.php"); 
    $id = $_POST['id'];
    $p_id = $_POST['p_id'];
    $amount = $_POST['amount'];
	// print_r($_POST);
	// var_dump($amount);
	// var_dump($p_id);
	// exit();
	$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM order_list WHERE id ='".$id."'"));	

$product_id = $product['product_id'];
$product_amt = $product['amount'];
		$pro_id = explode(',', $product_id); 
		$amount_ar = explode(',', $product_amt); 
foreach($pro_id as $key => $Value)
{
if($Value ==  $p_id)	$p_key = $key; 
}

$aArray = $amount_ar;   

foreach($aArray as $key => &$sValue)
{
     if ( $key==$p_key ) $sValue= $amount;
}


		$amount_arval = implode(',', $aArray); 
		
	$upt_tt = mysqli_query($conn,"UPDATE `order_list` SET `amount`='$amount_arval' WHERE id ='".$id."'");	
  echo 1;
    
 ?>
