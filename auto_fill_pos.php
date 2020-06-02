<?php 
include("config.php");
if(isset($_POST['user_id'])){
		$user_id=$_SESSION['login'];
		$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='".$_SESSION['login']."'"));
		if($profile_data['user_roles']==5)
		{
			$user_id=$profile_data['parentid'];
		}
		else
		{
			$user_id=$_SESSION['login'];
		}
		// if(isset($_POST['search'])){
		 $user_id=$_POST['user_id'];
		if($user_id)
		{
			$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$user_id."' and status=0 order by product_name asc");
			 $total_num_rows = mysqli_num_rows($total_rows);

			if($total_num_rows>0)
			{
				$listq = mysqli_query($conn,"delete from pos_product_system where user_id='$user_id'");
				$shift_pos=1;
				while ($row=mysqli_fetch_assoc($total_rows)){
					$entity_id=$row['id'];
					  $q="INSERT INTO pos_product_system(entity_id,user_id,shift_pos,status) VALUES ('$entity_id', '$user_id', '$shift_pos','active')";
					mysqli_query($conn,$q);
					$shift_pos++;
					
				}
				echo "1";
			}
		}
	}


?>