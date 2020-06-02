<?php 
include("config.php");
$id=$_POST['showid'];

$sub_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM subscription WHERE id='$id'"));

echo json_encode($sub_data);

?>
