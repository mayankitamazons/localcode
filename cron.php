<?php
include("config.php");

	$total_rows = mysqli_query($conn, "SELECT * FROM users ");
	$total_rows1 = mysqli_query($conn, "SELECT * FROM users ");
	$selectedTime = date("Y-m-d H:i:s");
	$endTime = strtotime("-10 minutes", strtotime($selectedTime));
	date('h:i:s', $endTime);
   	 $timestamp = strtotime($selectedTime);
		while ($row=mysqli_fetch_assoc($total_rows))
		{  
			
			if(!empty($row['verification_code']))
			{
			 mysqli_query($conn, "DELETE FROM users WHERE ".$row['joined']." < $endTime and `isLocked` = 1 ");

			}
		} 
		while ($row1=mysqli_fetch_assoc($total_rows1))
		{  
			
			if(!empty($row1['rand_num']))
			{
			
			 mysqli_query($conn, "UPDATE users SET rand_num='',resetdate='' WHERE ".$row1['resetdate']." < $endTime");

			}
		}
		
		

	?>
