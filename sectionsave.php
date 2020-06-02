<?php
include("config.php");
$id=$_POST['order_id'];
// print_R($_POST);
// die;
$section_type = $_POST['section_type'];
$table_type = $_POST['table_booking'];
$user_id = $_POST['user_id'];

 // "UPDATE `order_list` SET `section_type` = '$section_type',table_type='$table_type' WHERE `id` = '$id'";
if($table_type)
$ttw = mysqli_query($conn,"UPDATE `order_list` SET table_type='$table_type',section_saved='y' WHERE `id` = '$id'");
if($section_type)
$ttw = mysqli_query($conn,"UPDATE `order_list` SET section_type='$section_type',section_saved='y' WHERE `id` = '$id'");



header('Location: view_merchant.php');  
?>
