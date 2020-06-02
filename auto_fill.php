<?php 
include("config.php");
if(isset($_POST['auto_category_id'])){
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
		 $category_id=$_POST['auto_category_id'];
		if($category_id)
		{
			$total_rows = mysqli_query($conn, "SELECT * FROM products WHERE user_id ='".$user_id."' and category_id='$category_id' and status=0 order by product_name asc");
			$total_num_rows = mysqli_num_rows($total_rows);
			if($total_num_rows>0)
			{
				$listq = mysqli_query($conn,"delete from arrange_system where category_id='$category_id' and page_type='p' and user_id='".$user_id."'");
				$shift_pos=1;
				while ($row=mysqli_fetch_assoc($total_rows)){
					$entity_id=$row['id'];
					$q="INSERT INTO arrange_system(id,entity_id,user_id,shift_pos,page_type,status,category_id) VALUES (NULL, '$entity_id', '$user_id', '$shift_pos', 'p', 'active','$category_id')";
					mysqli_query($conn,$q);
					$shift_pos++;
				}
				echo "product updated ";
			}
		}
	}
if(isset($_POST['master_id'])){
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
	$master_id=$_POST['master_id'];
		if($master_id)
		{
			  
			$total_rows = mysqli_query($conn, "SELECT * FROM category WHERE user_id ='".$user_id."' and catparent='$master_id' and status=0");
			$total_num_rows = mysqli_num_rows($total_rows);
			if($total_num_rows>0)
			{
				// $category_id=$catdata['id'];
				
				$shift_pos=1;
				while ($row=mysqli_fetch_assoc($total_rows)){
					// print_R($row);
					// die;
					$entity_id=$row['id'];
					$query="delete from arrange_system where entity_id='$entity_id' and page_type='c' and user_id='".$user_id."'";
					// die;
					$listq = mysqli_query($conn,$query);
					$q="INSERT INTO arrange_system(id,entity_id,user_id,shift_pos,page_type,status,category_id) VALUES (NULL, '$entity_id', '$user_id', '$shift_pos', 'c', 'active','$master_id')";
					mysqli_query($conn,$q);
					$shift_pos++;
				}
				echo "Category updated ";
			}
		}
	}

?>