<?php 

include("config.php"); 

	$id = $_POST['idspecialpass'];
		//echo $id; echo $tbl; echo "adasd"; 
		$upt_tt = mysqli_query($conn,"DELETE FROM `order_list` WHERE `order_list`.`id` = $id");	

		echo 1;
   

 
