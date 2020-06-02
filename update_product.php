<?php 
include("config.php");
$id=$_POST['showid'];
$user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='$id'"));
//print_r($user_data);
echo json_encode($user_data);

?>
