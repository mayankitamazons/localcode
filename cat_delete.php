<?php
include('config.php');
 echo $id=$_POST['userid'];

//
$remove = mysqli_query($conn,"UPDATE category SET status=1 WHERE id='$id'");

?>
