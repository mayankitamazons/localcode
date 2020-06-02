<?php
  include('conn.php');
  if(isset($_GET['id']))
  {
	$id=$_GET['id'];
    $query="select * from users where id='$id'";
	
	$exe=mysqli_query($conn,$query);
	$totalcount=mysqli_num_rows($exe);
	if($totalcount>0)
	{
		$data=mysqli_fetch_assoc($exe);
		
	}
	 else
	 {
		header('Location:q.php?id'); 
	 }
  }
  else
  {
	  header('Location:q.php');
  }
	  
  
  
	  
?>
<input type="text" name="name" value="<?php echo $data['name'];?>"/>