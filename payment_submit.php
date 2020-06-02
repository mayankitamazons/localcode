<?php
// print_R($_POST);
// die;
include("config.php");
$payment_order_id=$_POST['payment_order_id'];
$wallet=$_POST['wallet'];
// echo "UPDATE `order_list` SET wallet='$wallet',payment_alert='verified' WHERE `id` = '$payment_order_id'";
// die;
$ttw = mysqli_query($conn,"UPDATE `order_list` SET wallet='$wallet',payment_alert='verified' WHERE `id` = '$payment_order_id'");
header("Location: ".$site_url."/orderlist.php");
?>