<?php
include('config.php');
 echo $id=$_POST['userid'];

//
$remove = mysqli_query($conn,"UPDATE rating SET status=1 WHERE id='$id'");
 //$remove = mysqli_query($conn,"DELETE FROM products WHERE id ='$id'");

?>
