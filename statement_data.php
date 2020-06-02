<?php

session_start();
include("config.php");
if($_SESSION['login']=='')
{
    header('Location: '. $site_url .'/login.php');
    die;
}

$statementid = $_POST['statementid'];
 $Merchantid = $_SESSION['login'];
 $paid = $_POST['paid'];
 $change = $_POST['change'];
 $chan = number_format($change,2);
 $tol_qty = $_POST['tol_qty'];
 $tol_mnt = $_POST['tol_mnt'];

$product_name = $_POST['product_name'];
$product_code = $_POST['product_code'];
$remarks = $_POST['remark'];
$user = $_POST['user'];
$tablety = $_POST['tablety'];
$invo = $_POST['invo'];
$orderid = $_POST['orderid'];
 $count = count($orderid);
$section = $_POST['section'];
$qtyno = $_POST['qtyno'];
$total = $_POST['total']; 
//extra field for discount feature 
$total_amount = $_POST['total_amount'];
$select_wallet = $_POST['select_wallet'];
$wallet_paid_amount = $_POST['wallet_paid_amount'];
$discount_amount=$_POST['discount_amount'];
$paid_amount_pos=$_POST['paid_amount_pos'];
$change_pos=$_POST['change_pos'];
if($select_wallet!='-1')
	$pay_mode=$select_wallet;
	else
	$pay_mode="cash";
if($discount_amount)
$discount_amount=number_format($discount_amount,2);

$qry = "insert into statement_transection (statement_id,merchant_id,staff_id,tol_qty,subtotal,paid,balance) 
values('$statementid','$Merchantid','0','$tol_qty','$tol_mnt','$paid','$chan')";
$rel = mysqli_query($conn,$qry);
$last_id = mysqli_insert_id($conn);

for($i=0;$i<$count ;$i++){
   
    $_user  = $user[$i];
    $_tablety  = $tablety[$i];
    $_invo  = $invo[$i];
    $_qtyno  = $qtyno[$i];
    $_total  = $total[$i];
    $_section  = $section[$i];
   
 
    $query = "INSERT INTO statement_data(data_id,username,table_num,invoice_num,qty_num,amount,section_type,total_amount,wallet_paid_amount,discount_amount,paid_amount_pos,change_pos,wallet_type)
	values ('$last_id','$_user','$_tablety','$_invo','$_qtyno','$_total','$_section','$total_amount','$wallet_paid_amount','$discount_amount','$paid_amount_pos','$change_pos','$wallet_type')";
    $result = mysqli_query($conn, $query); 
}

if($result)
    {
    	$orderid = $_POST['orderid'];
    	//print_r($orderid);
    	 // $or_cont = implode(",",$orderid);

    	 $co = count($orderid);

	    	for($i=0;$i<$co;$i++){    
				   $sql = "update order_list set status ='1',wallet='$pay_mode' where id = '".$orderid[$i]."'";
				 $rel = mysqli_query($conn, $sql);
				}

        	echo"Data inserted successfully !";
    }
    else
    {
    	echo "not done";
    }




?>