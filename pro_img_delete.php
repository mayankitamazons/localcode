<?php
include('config.php');
  $id=$_POST['id'];

//
echo "UPDATE products SET image='' WHERE id='$id'";
$remove = mysqli_query($conn,"UPDATE products SET image='' WHERE id='$id'");


?>